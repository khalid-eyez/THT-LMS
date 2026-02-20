<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;

/** @var common\models\User $user */
$user = Yii::$app->user->identity;

$avatarUrl = Yii::getAlias('@web/images/avatar2.png'); // provide image here

$statusText = 'Unknown';
if ($user) {
    if ((int)$user->status === \common\models\User::STATUS_ACTIVE)   $statusText = 'Active';
    if ((int)$user->status === \common\models\User::STATUS_INACTIVE) $statusText = 'Inactive';
    if ((int)$user->status === \common\models\User::STATUS_DELETED)  $statusText = 'Deleted';
}

$updateUrl  = $user ? Url::to(['/admin/auth/update-profile', 'id' => urlencode(base64_encode($user->id))]) : '#';
$refreshUrl = Url::to(['/admin/auth/view-profile']);

$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->getCsrfToken();
?>

<div class="wizard-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="wizard-wrap-int">
                    <div class="sale-statistic-inner notika-shadow mg-tb-30" style="padding:18px;">

                        <!-- Avatar -->
                        <div class="text-center" style="margin-bottom:14px;">
                            <?= Html::img($avatarUrl, [
                                'alt' => 'Profile Avatar',
                                'class' => 'img-circle',
                                'style' => 'width:120px;height:120px;object-fit:cover;',
                            ]) ?>
                        </div>

                        <h5 class="bg-primary" style="margin-top:6px;margin-bottom:12px;padding:6px;">
                            <i class="fa fa-user"></i> My Profile
                        </h5>

                        <?php if (!$user): ?>
                            <p class="text-danger">No logged-in user found.</p>
                        <?php else: ?>
                            <p><strong>Username:</strong> <?= Html::encode($user->username) ?></p>
                            <p><strong>Name:</strong> <?= Html::encode($user->name ?: '-') ?></p>
                            <p><strong>Status:</strong> <?= Html::encode($statusText) ?></p>
                            <p><strong>Date Registered:</strong>
                                <?= Html::encode(Yii::$app->formatter->asDate($user->created_at, 'dd MMM yyyy')) ?>
                            </p>

                            <hr>

                            <div class="text-center" style="margin-top:10px;">
                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    data-toggle="modal"
                                    data-target="#profileUpdateModal"
                                >
                                    <i class="fa fa-edit"></i> Update Profile
                                </button>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($user): ?>
<!-- MODAL (styled like your Loan Types modal) -->
<div class="modal fade animated rubberBand" id="profileUpdateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white" style="padding-top:2px;padding-bottom:2px">
                <span>Update Profile</span>
                <button type="button" class="close text-white" data-dismiss="modal" style="opacity:1;">
                    <span>&times;</span>
                </button>
            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'profile-update-form',
                'action' => $updateUrl,
                'method' => 'post',

                // ajax submit; keep validations off like your other views
                'enableClientScript' => false,
                'enableClientValidation' => false,
                'enableAjaxValidation' => false,

                'fieldConfig' => [
                    'errorOptions' => ['class' => 'help-block text-danger'],
                ],
            ]); ?>

            <div class="modal-body">

                <!-- Compatible with $user->load($_POST): User[username], User[name] -->
                <?= $form->field($user, 'username')->textInput([
                    'autocomplete' => 'off',
                    'id' => 'profile-username',
                ])->label('Username') ?>

                <?= $form->field($user, 'name')->textInput([
                    'autocomplete' => 'off',
                    'id' => 'profile-name',
                ])->label('Name') ?>

                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    Close
                </button>

                <?= Html::button(
                    '<i class="fa fa-save"></i> Save',
                    [
                        'class' => 'btn btn-primary pull-right',
                        'id' => 'profile-submit',
                        'encode' => false,
                        'type' => 'button'
                    ]
                ) ?>

            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
<?php endif; ?>

<?php
$this->registerCss("
    .help-block,
    .help-block-error { color:#dc3545 !important; }
    .has-error .form-control { border-color:#dc3545; }

    /* Center toastr like your Loan Types view */
    #toast-container.toast-center-center{
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        right: auto !important;
        bottom: auto !important;
    }
");

$this->registerJs("
(function () {

    // ajax-injected view -> prevent duplicate bindings
    $(document).off('.profileUpdate');

    var updateUrl  = " . json_encode($updateUrl) . ";
    var refreshUrl = " . json_encode($refreshUrl) . ";

    var csrfParam = " . json_encode($csrfParam) . ";
    var csrfToken = " . json_encode($csrfToken) . ";

    // toastr config
    if (window.toastr && !window.__profileToastrConfigured) {
        window.__profileToastrConfigured = true;
        toastr.options = {
            timeOut: 1600,
            extendedTimeOut: 1600,
            positionClass: 'toast-center-center',
            newestOnTop: true,
            closeButton: false,
            progressBar: false
        };
    }

    function toastOnce(type, msg) {
        if (!window.toastr) return;
        toastr.clear();
        toastr.remove();
        toastr[type](msg);
    }

    function closeModalSafe() {
        var m = $('#profileUpdateModal');
        if ($.fn.modal) {
            m.modal('hide');
        } else {
            m.removeClass('show').hide().attr('aria-hidden', 'true');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        }
    }

    function withCsrf(data) {
        if (typeof data === 'string') {
            if (data.indexOf(encodeURIComponent(csrfParam) + '=') === -1) {
                data += (data.length ? '&' : '') + encodeURIComponent(csrfParam) + '=' + encodeURIComponent(csrfToken);
            }
            return data;
        }
        data = data || {};
        if (data[csrfParam] === undefined) data[csrfParam] = csrfToken;
        return data;
    }

    $.ajaxSetup({ headers: { 'X-CSRF-Token': csrfToken } });

    // prevent normal submit
    $(document).on('submit.profileUpdate', '#profile-update-form', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        return false;
    });

    /**
     * RELIABLE RELOAD:
     * Your SPA loads pages with: $('.content').load(url, ...)
     * We'll do a stronger reload:
     * - cache bust
     * - fetch HTML using $.ajax(cache:false)
     * - inject into .content (fallback .cashbook)
     * - pushState to keep URL consistent
     */
    function reloadProfile() {
        var \$content = $('.content');
      

        var url = refreshUrl + (refreshUrl.indexOf('?') >= 0 ? '&' : '?') + '_t=' + new Date().getTime();

        $('#global-loader').show();

        $.ajax({
            url: url,
            type: 'GET',
            cache: false
        }).done(function (html) {

            if (\$content.length) {
                \$content.html(html);
            } else {
                window.location.href = refreshUrl;
                return;
            }

            try { history.pushState({ url: refreshUrl }, '', refreshUrl); } catch (e) {}

            if ($.fn.tooltip) $('[data-toggle=\"tooltip\"]').tooltip();

        }).fail(function () {
            window.location.href = refreshUrl;
        }).always(function () {
            $('#global-loader').hide();
        });
    }

    // submit via ajax
    $(document).on('click.profileUpdate', '#profile-submit', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var \$btn = $(this);
        if (\$btn.data('busy')) return false;
        \$btn.data('busy', true);

        $('#global-loader').show();

        $.ajax({
            url: updateUrl,
            type: 'POST',
            dataType: 'json',
            data: withCsrf($('#profile-update-form').serialize()),
            success: function (res) {
                if (res && res.success) {
                    toastOnce('success', res.success);
                     reloadProfile();
                    // wait until modal fully closes then reload (prevents backdrop issues)
                    $('#profileUpdateModal').one('hidden.bs.modal', function () {
                        reloadProfile();
                    });

                    closeModalSafe();
                } else if (res && res.error) {
                    $('#global-loader').hide();
                    toastOnce('error', res.error);
                } else {
                    $('#global-loader').hide();
                    toastOnce('error', 'Profile Updating failed !');
                }
            },
            error: function () {
                $('#global-loader').hide();
                toastOnce('error', 'Request failed');
            },
            complete: function () {
                \$btn.data('busy', false);
            }
        });

        return false;
    });

})();
");
?>

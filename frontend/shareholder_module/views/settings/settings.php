<?php
/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap4\ActiveForm;
use common\models\Setting;

$this->title = 'Settings';

// ✅ DATA
$records = Setting::find()->orderBy(['id' => SORT_ASC])->all();

// ✅ Routes
$addUrl     = Url::to(['/shareholder/settings/add-setting']);        // JSON (ADD)
$updateUrl  = Url::to(['/shareholder/settings/update-setting']);     // JSON (EDIT)
$refreshUrl = Url::to(Yii::$app->request->url, true);               // HTML reload (same page)

// ✅ CSRF
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->getCsrfToken();

// UI libs
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js', [
  'depends' => [\yii\web\JqueryAsset::class],
]);

// ✅ ensure $model exists
if (!isset($model) || $model === null) {
  $model = new Setting();
}

$this->registerCss(<<<CSS
.settings-topbar{ margin-bottom:12px; }
.settings-topbar .topbar-flex{ display:flex; align-items:center; justify-content:space-between; gap:10px; }
.settings-title{ font-size:16px; font-weight:600; color:#333; display:flex; align-items:center; gap:8px; }
.settings-title i{ color:#058aba; }

#toast-container{ z-index: 9999999 !important; }
#toast-container.toast-center-center{
  top: 50% !important;
  left: 50% !important;
  transform: translate(-50%, -50%) !important;
  right: auto !important;
  bottom: auto !important;
}

#settingsWrap.is-loading{ opacity:.65; pointer-events:none; }

.help-block, .help-block-error { color:#dc3545 !important; }
.has-error .form-control { border-color:#dc3545; }
CSS);
?>

<div class="breadcomb-area bg-white">
  <div class="container bg-white">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg-white">
        <div class="wizard-wrap-int">

          <div id="settingsWrap">

            <div class="settings-topbar">
              <div class="topbar-flex">
                <div class="settings-title">
                  <i class="fa fa-cogs"></i>
                  <span>Settings</span>
                </div>

                <div class="topbar-actions">
                  <?= Html::button('<i class="fa fa-plus"></i> Add New', [
                    'type' => 'button',
                    'class' => 'btn btn-primary',
                    'id' => 'btnAddSetting',
                    'title' => 'Add new setting',
                    'data-toggle' => 'modal',
                    'data-target' => '#settingModal',
                  ]) ?>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-striped table-hover mb-0" id="settingsTable">
                <thead>
                  <tr>
                    <th style="width:90px;">ID</th>
                    <th style="width:220px;">Name</th>
                    <th>Value</th>
                    <th style="width:120px;" class="text-right">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($records)): ?>
                    <tr class="no-results">
                      <td colspan="4" class="text-center text-muted py-4">No settings found.</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($records as $row): ?>
                      <?php
                        $id = (int)$row->id;
                        $name = (string)$row->name;
                        $value = $row->value;
                        if (is_array($value) || is_object($value)) $value = json_encode($value);
                        $value = (string)$value;
                      ?>
                      <tr class="setting-row"
                          data-id="<?= $id ?>"
                          data-name="<?= Html::encode($name) ?>"
                          data-value="<?= Html::encode($value) ?>">
                        <td><?= $id ?></td>
                        <td><?= Html::encode($name) ?></td>
                        <td><?= Html::encode($value) ?></td>
                        <td class="text-right">
                          <button
                            type="button"
                            class="btn btn-sm btn-outline-primary js-setting-edit"
                            data-toggle="modal"
                            data-target="#settingModal"
                            title="Edit"
                          >
                            <i class="fa fa-edit"></i>
                          </button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>

          </div><!-- /#settingsWrap -->

        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL -->
<div class="modal fade animated rubberBand" id="settingModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white" style="padding-top:2px;padding-bottom:2px">
        <span id="settingModalTitle">New Setting</span>
        <button type="button" class="close text-white" data-dismiss="modal" style="opacity:1;">
          <span>&times;</span>
        </button>
      </div>

      <?php $form = ActiveForm::begin([
        'id' => 'setting-form',
        'action' => $addUrl,
        'method' => 'post',

        // ✅ mimic LoanTypes (no frontend validation)
        'enableClientScript' => false,
        'enableClientValidation' => false,
        'enableAjaxValidation' => false,

        'fieldConfig' => [
          'errorOptions' => ['class' => 'help-block text-danger'],
        ],
      ]); ?>

      <div class="modal-body">
        <input type="hidden" id="setting-id" name="id" value="">
        <input type="hidden" name="<?= Html::encode($csrfParam) ?>" value="<?= Html::encode($csrfToken) ?>">

        <?= $form->field($model, 'name')->textInput([
          'id' => 'setting-name',
          'maxlength' => 20,
          'autocomplete' => 'off',
          'required' => true,
        ])->label('Name') ?>

        <?= $form->field($model, 'value')->textarea([
          'id' => 'setting-value',
          'rows' => 3,
          'required' => true,
        ])->label('Value') ?>

        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

        <?= Html::button(
          '<i class="fa fa-save"></i> Save',
          ['class' => 'btn btn-primary pull-right', 'id' => 'setting-submit', 'encode' => false, 'type' => 'button']
        ) ?>
      </div>

      <?php ActiveForm::end(); ?>

    </div>
  </div>
</div>

<?php
$this->registerJs("
(function () {

  if (window.toastr && !window.__settingsToastrConfigured) {
    window.__settingsToastrConfigured = true;
    toastr.options = {
      timeOut: 1500,
      extendedTimeOut: 1500,
      positionClass: 'toast-center-center',
      newestOnTop: true,
      closeButton: false,
      progressBar: false
    };
  }

  var addUrl     = " . json_encode($addUrl) . ";
  var updateUrl  = " . json_encode($updateUrl) . ";
  var refreshUrl = " . json_encode($refreshUrl) . ";

  var csrfParam  = " . json_encode($csrfParam) . ";
  var csrfToken  = " . json_encode($csrfToken) . ";

  $.ajaxSetup({ headers: { 'X-CSRF-Token': csrfToken } });

  var isSubmitting = false;

  function toastOnce(type, msg) {
    if (!window.toastr) return;
    toastr.clear();
    toastr.remove();
    toastr[type](msg);
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

  // ✅ EXACT mimic: show/hide global loader like LoanTypes
  function showLoader() { $('#global-loader').show(); }
  function hideLoader() { $('#global-loader').hide(); }

  // ✅ EXACT mimic: close modal safe like LoanTypes
  function closeModalSafe() {
    var m = $('#settingModal');
    if ($.fn.modal) {
      m.modal('hide');
    } else {
      m.removeClass('show').hide().attr('aria-hidden', 'true');
      $('body').removeClass('modal-open');
      $('.modal-backdrop').remove();
    }
  }

  // ✅ EXACT mimic: refresh like LoanTypes: load container inner
  function refreshSettingsWrap() {
    // Settings page container is #settingsWrap (like #loan-types)
    $('#settingsWrap').load(refreshUrl + ' #settingsWrap > *');
  }

  // ✅ CRITICAL: avoid double-click by refreshing only AFTER modal is fully hidden
  function closeAndRefreshSafe() {
    var m = $('#settingModal');

    // if bootstrap missing, do it immediately
    if (!$.fn.modal) {
      closeModalSafe();
      refreshSettingsWrap();
      return;
    }

    // refresh only once after hide completes (no backdrop click-eating)
    m.off('hidden.bs.modal.settingsRefresh')
     .one('hidden.bs.modal.settingsRefresh', function () {
        refreshSettingsWrap();
     });

    closeModalSafe();
  }

  // avoid duplicate bindings
  $(document).off('.settings');

  // OPEN ADD
  $(document).on('click.settings', '#btnAddSetting', function () {
    $('#settingModalTitle').text('New Setting');
    $('#setting-id').val('');
    $('#setting-name').val('');
    $('#setting-value').val('');

    $('#setting-submit')
      .html('<i class=\"fa fa-save\"></i> Save')
      .data('mode', 'add');
  });

  // OPEN EDIT
  $(document).on('click.settings', '.js-setting-edit', function () {
    var tr = $(this).closest('tr.setting-row');
    if (!tr.length) return;

    $('#settingModalTitle').text('Update Setting');
    $('#setting-id').val(tr.data('id'));
    $('#setting-name').val(tr.data('name'));
    $('#setting-value').val(tr.data('value'));

    $('#setting-submit')
      .html('<i class=\"fa fa-save\"></i> Update')
      .data('mode', 'edit');
  });

  // prevent normal submit
  $(document).on('submit.settings', '#setting-form', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    return false;
  });

  // SUBMIT (ADD/EDIT)
  $(document).on('click.settings', '#setting-submit', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    if (isSubmitting) return false;

    var name = ($('#setting-name').val() || '').toString().trim();
    var val  = ($('#setting-value').val() || '').toString().trim();
    if (!name) { toastOnce('error','Name is required'); return false; }
    if (!val)  { toastOnce('error','Value is required'); return false; }

    var mode = $(this).data('mode') || 'add';
    var url  = addUrl;

    if (mode === 'edit') {
      var id = ($('#setting-id').val() || '').toString().trim();
      if (!id) { toastOnce('error', 'Missing setting id for update'); return false; }
      url = updateUrl + '?settingID=' + encodeURIComponent(id);
    }

    isSubmitting = true;
    showLoader(); // ✅ mimic template

    $.ajax({
      url: url,
      type: 'POST',
      dataType: 'json',
      data: withCsrf($('#setting-form').serialize()),
      success: function (res) {
        if (res && res.success) {
          hideLoader(); // ✅ mimic template
          toastOnce('success', res.success);

          // ✅ mimic template order: close modal then refresh
          closeAndRefreshSafe();
        } else {
          hideLoader(); // ✅ mimic template
          toastOnce('error', (res && (res.error || res.message)) ? (res.error || res.message) : 'Request failed');
        }
      },
      error: function () {
        hideLoader(); // ✅ mimic template
        toastOnce('error', 'Request failed');
      },
      complete: function () {
        hideLoader(); // ✅ mimic template (they hide in complete too)
        isSubmitting = false;
      }
    });

    return false;
  });

})();
", View::POS_END);
?>

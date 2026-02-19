<?php
/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use common\models\DepositInterest;

$this->title = 'Pending Interest Claims';

$claims = DepositInterest::getPendingClaims();

$totalClaims = count($claims);
$totalAmount = 0.0;
foreach ($claims as $c) $totalAmount += (float)$c->interest_amount;
$totalAmount = round($totalAmount, 2);

// ✅ Routes
$approveUrl = fn($id) => Url::to(['/shareholder/deposit/approve-claim', 'interestID' => $id], true);
$deleteUrl  = fn($id) => Url::to(['/shareholder/deposit/delete-claim',  'interestID' => $id], true);

// Current page URL (for reload)
$listUrl = Url::to(Yii::$app->request->url, true);

// UI libs
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js', [
  'depends' => [\yii\web\JqueryAsset::class],
]);

/**
 * ✅ FIX:
 * cdnjs jsPDF link is blocked in your browser (MIME text/html mismatch).
 * Use jsDelivr jsPDF UMD build + bridge + autotable (also jsDelivr).
 * Force them to load in HEAD so they exist before clicking PDF.
 */
$this->registerJsFile('https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js', [
  'depends' => [\yii\web\JqueryAsset::class],
  'position' => View::POS_HEAD,
]);

$this->registerJs(<<<'JS'
(function () {
  // Bridge for plugins expecting window.jsPDF
  if (!window.jsPDF && window.jspdf && window.jspdf.jsPDF) {
    window.jsPDF = window.jspdf.jsPDF;
  }
})();
JS
, View::POS_HEAD);

$this->registerJsFile('https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js', [
  'depends' => [\yii\web\JqueryAsset::class],
  'position' => View::POS_HEAD,
]);

// CSS
$css = <<<CSS
.claims-topbar{ margin-bottom:12px; }
.claims-topbar .topbar-flex{ display:flex; align-items:stretch; gap:10px; }
.claims-topbar .topbar-left{ flex: 1 1 auto; min-width: 320px; }
.claims-topbar .topbar-right{ flex: 0 0 420px; }

/* Fix Bootstrap 3 input-group alignment */
.claims-topbar .input-group{ display: table; width: 100%; }
.claims-topbar .input-group-addon,
.claims-topbar .form-control{ display: table-cell; vertical-align: middle; }
.claims-topbar .form-control{ height:46px; font-size:14px; }
.claims-topbar .input-group-addon{
  height:46px;
  background: rgba(52,152,219,.15);
  border-color:#d9edf7;
  color:#31708f;
  padding:0 16px;
  line-height:46px;
  text-align:center;
  width:1%;
  white-space:nowrap;
}

.right-flex{ height:46px; display:flex; align-items:stretch; gap:10px; }

/* Summary box */
.claims-summary{
  flex:1 1 auto;
  height:46px;
  margin:0;
  padding:6px 10px;
  background: rgba(52,152,219,.15);
  border:1px solid #d9edf7;
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:10px;
}
.claims-summary .metrics{ display:flex; align-items:center; gap:14px; }
.claims-summary .metric{ display:flex; align-items:baseline; gap:6px; white-space:nowrap; }
.claims-summary .label{ font-size:12px; color:#31708f; }
.claims-summary .value{ font-size:18px; font-weight:700; color:#245269; }

/* PDF button */
#exportPdfBtn{
  height:46px;
  width:92px;
  padding:0 14px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  white-space:nowrap;
}
#exportPdfBtn i{ font-size:18px; }

/* Busy / loading */
.tooltip{ z-index: 999999 !important; }
#toast-container{ z-index: 9999999 !important; }
.action-btns .btn{ margin:0 2px; }
.js-claim-action.is-busy{ opacity:.65; pointer-events:none; }
#claimsWrap.is-loading{ opacity:.65; pointer-events:none; }
/* 📱 Make the whole view horizontally scrollable on phones */
@media (max-width: 991px){

  /* scroll container */
  .page-x-scroll{
    width: 100%;
    overflow-x: auto;
    overflow-y: visible;
    -webkit-overflow-scrolling: touch;
  }

  /* force inner content to be wider than the phone so scrolling actually happens */
  .page-x-scroll .page-x-scroll-inner{
    min-width: 1100px; /* adjust: 900-1400 depending on how wide you want */
  }
}
CSS;

$this->registerCss($css);
?>

<div class="breadcomb-area bg-white">
  <div class="container bg-white">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg-white">
        <div class="wizard-wrap-int">
                  <div class="page-x-scroll">
  <div class="page-x-scroll-inner">
          <div class="deposit-interest-pending">

            <!-- ✅ WHOLE VIEW WRAP (reloadable) -->
            <div id="claimsWrap">

              <!-- TOP BAR -->
              <div class="claims-topbar">
                <div class="topbar-flex">
                  <div class="topbar-left">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-search"></i></span>
                      <?= Html::textInput('q', '', [
                          'id' => 'claimSearch',
                          'class' => 'form-control',
                          'placeholder' => 'Search by Customer ID, Member ID, Full Name...',
                          'autocomplete' => 'off',
                      ]) ?>
                    </div>
                  </div>

                  <div class="topbar-right">
                    <div class="right-flex">
                      <div class="well claims-summary">
                        <div class="metrics">
                          <div class="metric">
                            <span class="label">Total Claims</span>
                            <span class="value" id="totalClaimsBox"><?= (int)$totalClaims ?></span>
                          </div>
                          <div class="metric">
                            <span class="label">Total Amount</span>
                            <span class="value" id="totalAmountBox"><?= number_format($totalAmount, 2) ?></span>
                          </div>
                        </div>
                      </div>

                      <?= Html::button(
                          '<i class="fa fa-file-pdf-o"></i><span>PDF</span>',
                          [
                              'class' => 'btn btn-primary',
                              'id' => 'exportPdfBtn',
                              'type' => 'button',
                              'title' => 'Export PDF',
                              'data-toggle' => 'tooltip',
                              'data-placement' => 'top',
                          ]
                      ) ?>
                    </div>
                  </div>
                </div>
              </div>

              <!-- TABLE -->
              <div class="table-responsive">
                <table class="table table-hover table-striped" id="claimsTable">
                  <thead>
                    <tr>
                      <th style="width:120px;">Customer ID</th>
                      <th style="width:110px;">Member ID</th>
                      <th>Full Name</th>
                      <th style="width:120px;" class="text-right">Claim Months</th>
                      <th style="width:170px;">Claim Date</th>
                      <th style="width:150px;" class="text-right">Interest Amount</th>
                      <th style="width:110px;" class="text-center">Actions</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php if (empty($claims)): ?>
                      <tr class="no-results">
                        <td colspan="7" class="text-center text-muted">No pending interest claims found.</td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($claims as $claim): ?>
                        <?php
                        $deposit     = $claim->deposit;
                        $shareholder = $deposit ? $deposit->shareholder : null;
                        $customer    = $shareholder ? $shareholder->customer : null;

                        $customerID = $customer ? $customer->customerID : '-';
                        $memberID   = $shareholder ? $shareholder->memberID : '-';
                        $fullName   = $customer ? $customer->full_name : '-';
                        $claimDate  = $claim->claim_date ? date('j M Y', strtotime($claim->claim_date)) : '-';
                        ?>
                        <tr class="claim-row" data-search="<?= Html::encode(strtolower("$customerID $memberID $fullName")) ?>">
                          <td><?= Html::encode($customerID) ?></td>
                          <td><?= Html::encode($memberID) ?></td>
                          <td><?= Html::encode($fullName) ?></td>
                          <td class="text-right"><?= (int)$claim->claim_months ?></td>
                          <td><?= Html::encode($claimDate) ?></td>
                          <td class="text-right"><?= number_format((float)$claim->interest_amount, 2) ?></td>

                          <td class="text-center action-btns">
                            <?= Html::button('<i class="fa fa-check"></i>', [
                              'type' => 'button',
                              'class' => 'btn btn-xs btn-primary js-claim-action',
                              'data-url' => $approveUrl($claim->id),
                              'data-list-url' => $listUrl,
                              'data-confirm-msg' => 'Approve this claim?',
                              'title' => 'Approve',
                              'data-toggle' => 'tooltip',
                              'data-placement' => 'top',
                            ]) ?>

                            <?= Html::button('<i class="fa fa-trash"></i>', [
                              'type' => 'button',
                              'class' => 'btn btn-xs btn-danger js-claim-action',
                              'data-url' => $deleteUrl($claim->id),
                              'data-list-url' => $listUrl,
                              'data-confirm-msg' => 'Delete this claim?',
                              'title' => 'Delete',
                              'data-toggle' => 'tooltip',
                              'data-placement' => 'top',
                            ]) ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>

                  <tfoot>
                    <tr>
                      <th colspan="5" class="text-right">TOTAL</th>
                      <th class="text-right" id="totalAmountCell"><?= number_format($totalAmount, 2) ?></th>
                      <th></th>
                    </tr>
                  </tfoot>
                </table>
              </div>

            </div><!-- /#claimsWrap -->

          </div>
        </div>
      </div>
    </div>
  </div>
</div></div></div>

<?php
$js = <<<JS
(function () {

  // prevent duplicate event binding (important when partial reload happens)
  window.__pendingClaimsBound = window.__pendingClaimsBound || false;
  if (window.__pendingClaimsBound) return;
  window.__pendingClaimsBound = true;

  var listUrlDefault = '$listUrl';

  function toast(type, msg){
    if (window.toastr) {
      toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 2500
      };
      toastr[type](msg);
    } else {
      alert(msg);
    }
  }

  function initTooltips(){
    if (!$.fn.tooltip) return;
    $('[data-toggle="tooltip"]').tooltip('destroy').tooltip({ container:'body', trigger:'hover focus' });
  }

  function parseAmount(text){
    return parseFloat(String(text).replace(/,/g,'').trim()) || 0;
  }
  function fmt(n){
    return (n||0).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2});
  }

  function ensureNoMatchRow(tbodyEl){
    var rowEl = tbodyEl.find('tr.no-match');
    if (!rowEl.length) {
      rowEl = $('<tr class="no-match" style="display:none;"><td colspan="7" class="text-center text-muted">No matching records.</td></tr>');
      tbodyEl.append(rowEl);
    }
    return rowEl;
  }

  function updateFilterAndTotals(){
    var q = ($('#claimSearch').val() || '').toLowerCase().trim();
    var tbodyEl = $('#claimsTable tbody');
    var sum = 0, visible = 0;

    var rowsEl = tbodyEl.find('tr.claim-row');
    var noResultsEl = tbodyEl.find('tr.no-results');
    var noMatchEl = ensureNoMatchRow(tbodyEl);

    rowsEl.each(function(){
      var trEl = $(this);
      var ok = (q === '') || String(trEl.data('search') || '').indexOf(q) !== -1;
      trEl.toggle(ok);
      if (ok) {
        visible++;
        sum += parseAmount(trEl.find('td').eq(5).text());
      }
    });

    if (rowsEl.length === 0) {
      noMatchEl.hide();
    } else if (visible === 0) {
      noResultsEl.hide();
      noMatchEl.show();
    } else {
      noMatchEl.hide();
      noResultsEl.hide();
    }

    $('#totalAmountCell').text(fmt(sum));
    $('#totalClaimsBox').text(visible);
    $('#totalAmountBox').text(fmt(sum));
  }

  function reloadClaimsWrap(listUrl){
    var finalUrl = listUrl || listUrlDefault;
    var wrapEl = $('#claimsWrap');
    if (!wrapEl.length) { window.location.reload(); return; }

    wrapEl.addClass('is-loading');

    $.ajax({
      url: finalUrl,
      type: 'GET',
      dataType: 'html',
      cache: false
    }).done(function(html){
      var tmp = $('<div>').append($.parseHTML(html, document, true));
      var freshWrap = tmp.find('#claimsWrap');

      if (freshWrap.length) {
        wrapEl.replaceWith(freshWrap);
      } else {
        wrapEl.html(html);
      }

      initTooltips();
      updateFilterAndTotals();
    }).fail(function(xhr){
      console.log('RELOAD FAIL:', xhr.status, xhr.responseText);
      toast('error', 'Failed to reload list.');
    }).always(function(){
      $('#claimsWrap').removeClass('is-loading');
    });
  }

  // ✅ Approve/Delete AJAX (delegated)
  $(document).off('click.claimAction').on('click.claimAction', '.js-claim-action', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    var btn = $(this);
    var url = btn.data('url');
    var listUrl = btn.data('listUrl') || listUrlDefault;
    var confirmMsg = btn.data('confirmMsg') || 'Are you sure?';

    if (!url) { toast('error', 'Missing action URL'); return false; }
    if (!confirm(confirmMsg)) return false;

    var row = btn.closest('tr');
    row.find('.js-claim-action').prop('disabled', true).addClass('is-busy');

    $.ajax({
      url: url,
      type: 'GET',
      dataType: 'text',
      cache: false,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).done(function (text) {
      var resp = null;
      try { resp = JSON.parse(text); } catch (e) { resp = null; }

      if (resp && resp.success) {
        toast('success', resp.success);
        setTimeout(function(){ reloadClaimsWrap(listUrl); }, 600);
        return;
      }
      if (resp && resp.error) {
        toast('error', resp.error);
        return;
      }

      toast('error', 'Unexpected response (not JSON). Check Network tab.');
    }).fail(function (xhr) {
      console.log('AJAX FAIL:', xhr.status, xhr.responseText);
      toast('error', 'Request failed (status ' + (xhr.status || 0) + ').');
    }).always(function(){
      row.find('.js-claim-action').prop('disabled', false).removeClass('is-busy');
    });

    return false;
  });

  // Search (delegated)
  $(document).off('keyup.claimSearch change.claimSearch blur.claimSearch', '#claimSearch')
    .on('keyup.claimSearch change.claimSearch blur.claimSearch', '#claimSearch', updateFilterAndTotals);

  // Init
  initTooltips();
  updateFilterAndTotals();

  // ✅ PDF export (FIXED for UMD + bridge + works even if window.jsPDF not set)
  $(document).off('click.exportPdf').on('click.exportPdf', '#exportPdfBtn', function(){
    var JsPDF = window.jsPDF || (window.jspdf && window.jspdf.jsPDF);

    if (!JsPDF) {
      console.log('jsPDF missing. window.jspdf=', window.jspdf);
      toast('error', 'jsPDF not loaded. Check Network tab for jsDelivr links.');
      return;
    }

    var doc = new JsPDF({orientation:'landscape', unit:'pt', format:'a4'});

    if (typeof doc.autoTable !== 'function') {
      console.log('autoTable missing. doc=', doc);
      toast('error', 'AutoTable plugin not loaded. Check script order.');
      return;
    }

    var title = 'Pending Interest Claims';
    var totalClaims = $('#totalClaimsBox').text().trim();
    var totalAmount = $('#totalAmountBox').text().trim();

    doc.setFontSize(14);
    doc.text(title, 40, 40);
    doc.setFontSize(10);
    doc.text('Total Claims: ' + totalClaims + '    Total Amount: ' + totalAmount, 40, 60);

    var head = [];
    $('#claimsTable thead th').each(function(i){
      if (i === 6) return;
      head.push($(this).text().trim());
    });

    var body = [];
    $('#claimsTable tbody tr.claim-row:visible').each(function(){
      var row = [];
      $(this).find('td').each(function(i){
        if (i === 6) return;
        row.push($(this).text().trim());
      });
      body.push(row);
    });

    if (!body.length) {
      doc.text('No matching records to export.', 40, 90);
      doc.save('pending-interest-claims.pdf');
      return;
    }

    doc.autoTable({
      startY: 80,
      head: [head],
      body: body,
      styles: { fontSize: 9, cellPadding: 5 },
      columnStyles: { 3: { halign:'right' }, 5: { halign:'right' } }
    });

    doc.save('pending-interest-claims.pdf');
  });

})();
JS;

$this->registerJs($js, View::POS_END);
?>

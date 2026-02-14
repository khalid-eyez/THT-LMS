<?php
/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

use common\models\CustomerLoan;

$this->title = 'Pending applications';

// ✅ DATA: only status=="new"
$records = CustomerLoan::find()
  ->where(['status' => 'new'])
  ->orderBy(['created_at' => SORT_DESC])
  ->all();

$totalRecords = count($records);
$totalAmount = 0.0;
foreach ($records as $r) {
  $amt = 0.0;
  if (isset($r->loan_amount)) $amt = (float)$r->loan_amount;
  elseif (isset($r->amount))  $amt = (float)$r->amount;
  $totalAmount += $amt;
}
$totalAmount = round($totalAmount, 2);

// ✅ Routes (adjust if yours differ)
$viewUrl    = fn($id) => Url::to(['/loans/loans/loan-view',    'loanID' => $id], true);
$approveUrl    = fn($id) => Url::to(['/loans/loans/approve-ajax',    'loanID' => $id], true);
$disapproveUrl = fn($id) => Url::to(['/loans/loans/disapprove-ajax', 'loanID' => $id], true);

// Current page URL (for reload)
$listUrl = Url::to(Yii::$app->request->url, true);

// UI libs
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js', [
  'depends' => [\yii\web\JqueryAsset::class],
]);

/**
 * ✅ jsPDF (same working pattern as your template)
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

// CSS (keep same design)
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
.js-loan-action.is-busy{ opacity:.65; pointer-events:none; }
#loansWrap.is-loading{ opacity:.65; pointer-events:none; }

/* ✅ FULL CENTER Toastr (both axes) */
#toast-container.toast-center-center{
  top: 50% !important;
  left: 50% !important;
  right: auto !important;
  bottom: auto !important;
  transform: translate(-50%, -50%) !important;
  width: auto;
  max-width: 520px;
}
#toast-container.toast-center-center > div{
  width: 100% !important;
  margin: 0 auto 10px auto !important;
}
CSS;

$this->registerCss($css);
?>

<div class="breadcomb-area bg-white">
  <div class="container bg-white">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg-white">
        <div class="wizard-wrap-int">
          <div class="new-loans-pending">

            <!-- ✅ WHOLE VIEW WRAP (reloadable) -->
            <div id="loansWrap">

              <!-- TOP BAR -->
              <div class="claims-topbar">
                <div class="topbar-flex">
                  <div class="topbar-left">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-search"></i></span>
                      <?= Html::textInput('q', '', [
                          'id' => 'loanSearch',
                          'class' => 'form-control',
                          'placeholder' => 'Search by Customer ID, Loan ID, Customer name...',
                          'autocomplete' => 'off',
                      ]) ?>
                    </div>
                  </div>

                  <div class="topbar-right">
                    <div class="right-flex">
                      <div class="well claims-summary">
                        <div class="metrics">
                          <div class="metric">
                            <span class="label">Total Loans</span>
                            <span class="value" id="totalLoansBox"><?= (int)$totalRecords ?></span>
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
                <table class="table table-hover table-striped" border="1" id="loansTable">
                  <thead>
                    <tr>
                      <th style="width:140px;">Customer ID</th>
                      <th style="width:120px;">Loan ID</th>
                      <th style="min-width:190px;">Customer Name</th>
                      <th style="width:150px;" class="text-right">Loan Amount</th>
                      <th style="min-width:100px;">Repayment</th>
                      <th style="width:20px;" class="text-right">Duration</th>
                      <th style="width:170px;">Date Applied</th>
                      <th style="width:170px;">Date Updated</th>
                      <th style="width:185px;" class="text-center"></th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php if (empty($records)): ?>
                      <tr class="no-results">
                        <td colspan="9" class="text-center text-muted">No new loan applications found.</td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($records as $row): ?>
                        <?php
                          $customer    = $row->customer ?? null; // expects relation ->customer
                          $customerID  = $customer ? ($customer->customerID ?? '-') : '-';
                          $fullName    = $customer ? ($customer->full_name ?? '-') : '-';

                          $loanID      = $row->loanID ?? '-';

                          $loanAmount  = 0;
                          if (isset($row->loan_amount)) $loanAmount = (float)$row->loan_amount;
                          elseif (isset($row->amount))  $loanAmount = (float)$row->amount;

                          $repayFreq   = $row->repayment_frequency ?? ($row->repaymentFrequency ?? '-');

                          $duration    = $row->loan_duration_units ?? '-';

                          $createdAt   = !empty($row->created_at) ? date('j M Y', strtotime($row->created_at)) : '-';
                          $updatedAt   = !empty($row->updated_at) ? date('j M Y', strtotime($row->updated_at)) : '-';

                          $searchStr = strtolower(trim("$customerID $loanID $fullName $repayFreq $duration $loanAmount"));
                        ?>
                        <tr class="loan-row" data-search="<?= Html::encode($searchStr) ?>">
                          <td><?= Html::encode($customerID) ?></td>
                          <td><?= Html::encode($loanID) ?></td>
                          <td><?= Html::encode($fullName) ?></td>
                          <td class="text-right"><?= number_format($loanAmount, 2) ?></td>
                          <td><?= Html::encode($repayFreq) ?></td>
                          <td class="text-right"><?= Html::encode($duration) ?></td>
                          <td><?= Html::encode($createdAt) ?></td>
                          <td><?= Html::encode($updatedAt) ?></td>

                          <td class="action-btns">
                             <a href="<?= $viewUrl($row->id) ?>" title="View Loan" class="btn btn-xs btn-primary">
                                <i class="fa fa-eye"></i>
                             </a>
                            <?= Html::button('<i class="fa fa-check"></i>', [
                              'type' => 'button',
                              'class' => 'btn btn-xs btn-primary js-loan-action',
                              'data-url' => $approveUrl($row->id),
                              'data-list-url' => $listUrl,
                              'data-confirm-msg' => 'Approve this loan application?',
                              'title' => 'Approve',
                              'data-toggle' => 'tooltip',
                              'data-placement' => 'top',
                            ]) ?>

                            <?= Html::button('<i class="fa fa-times"></i>', [
                              'type' => 'button',
                              'class' => 'btn btn-xs btn-danger js-loan-action',
                              'data-url' => $disapproveUrl($row->id),
                              'data-list-url' => $listUrl,
                              'data-confirm-msg' => 'Disapprove this loan application?',
                              'title' => 'Disapprove',
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
                      <th colspan="3" class="text-right">TOTAL</th>
                      <th class="text-right" id="totalAmountCell"><?= number_format($totalAmount, 2) ?></th>
                      <th colspan="5"></th>
                    </tr>
                  </tfoot>
                </table>
              </div>

            </div><!-- /#loansWrap -->

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$js = <<<JS
(function () {

  window.__newLoansBound = window.__newLoansBound || false;
  if (window.__newLoansBound) return;
  window.__newLoansBound = true;

  var listUrlDefault = '$listUrl';

  function toast(type, msg){
    if (window.toastr) {
      toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-center-center',
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
      rowEl = $('<tr class="no-match" style="display:none;"><td colspan="9" class="text-center text-muted">No matching records.</td></tr>');
      tbodyEl.append(rowEl);
    }
    return rowEl;
  }

  function updateFilterAndTotals(){
    var q = ($('#loanSearch').val() || '').toLowerCase().trim();
    var tbodyEl = $('#loansTable tbody');
    var sum = 0, visible = 0;

    var rowsEl = tbodyEl.find('tr.loan-row');
    var noResultsEl = tbodyEl.find('tr.no-results');
    var noMatchEl = ensureNoMatchRow(tbodyEl);

    rowsEl.each(function(){
      var trEl = $(this);
      var ok = (q === '') || String(trEl.data('search') || '').indexOf(q) !== -1;
      trEl.toggle(ok);
      if (ok) {
        visible++;
        sum += parseAmount(trEl.find('td').eq(3).text());
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

    $('#totalLoansBox').text(visible);
    $('#totalAmountBox').text(fmt(sum));
    $('#totalAmountCell').text(fmt(sum));
  }

  function reloadLoansWrap(listUrl){
    var finalUrl = listUrl || listUrlDefault;
    var wrapEl = $('#loansWrap');
    if (!wrapEl.length) { window.location.reload(); return; }

    wrapEl.addClass('is-loading');

    $.ajax({
      url: finalUrl,
      type: 'GET',
      dataType: 'html',
      cache: false
    }).done(function(html){
      var tmp = $('<div>').append($.parseHTML(html, document, true));
      var freshWrap = tmp.find('#loansWrap');

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
      $('#loansWrap').removeClass('is-loading');
    });
  }

  $(document).off('click.loanAction').on('click.loanAction', '.js-loan-action', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    var btn = $(this);
    var url = btn.data('url');
    var listUrl = btn.data('listUrl') || listUrlDefault;
    var confirmMsg = btn.data('confirmMsg') || 'Are you sure?';

    if (!url) { toast('error', 'Missing action URL'); return false; }
    if (!confirm(confirmMsg)) return false;

    var row = btn.closest('tr');
    row.find('.js-loan-action').prop('disabled', true).addClass('is-busy');

    $.ajax({
      url: url,
      type: 'GET',
      dataType: 'json',
      cache: false,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).done(function (resp) {
      if (resp && resp.success) {
        toast('success', resp.success);
        setTimeout(function(){ reloadLoansWrap(listUrl); }, 600);
        return;
      }
      if (resp && resp.error) {
        toast('error', resp.error);
        return;
      }
      toast('error', 'Unexpected response. (Missing success/error)');
    }).fail(function (xhr) {
      console.log('AJAX FAIL:', xhr.status, xhr.responseText);
      var msg = 'Request failed (status ' + (xhr.status || 0) + ').';
      try {
        var j = JSON.parse(xhr.responseText || '{}');
        if (j && j.error) msg = j.error;
      } catch(e){}
      toast('error', msg);
    }).always(function(){
      row.find('.js-loan-action').prop('disabled', false).removeClass('is-busy');
    });

    return false;
  });

  $(document).off('keyup.loanSearch change.loanSearch blur.loanSearch', '#loanSearch')
    .on('keyup.loanSearch change.loanSearch blur.loanSearch', '#loanSearch', updateFilterAndTotals);

  // ✅ Robust AutoTable detection + lazy-load if missing (fixes "first load not loaded")
  var __autoTableLoadPromise = null;
  function ensureAutoTableLoaded(){
    // already available?
    if (window.autoTable || window.jspdfAutotable) return Promise.resolve();
    // already loading?
    if (__autoTableLoadPromise) return __autoTableLoadPromise;

    __autoTableLoadPromise = new Promise(function(resolve, reject){
      var s = document.createElement('script');
      s.src = 'https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js';
      s.onload = function(){ resolve(); };
      s.onerror = function(){ reject(new Error('Failed to load AutoTable')); };
      document.head.appendChild(s);
    });

    return __autoTableLoadPromise;
  }

  function getAutoTableFn(doc){
    // common attachment points
    var JsPDF = window.jsPDF || (window.jspdf && window.jspdf.jsPDF);
    return (doc && doc.autoTable) ||
      (JsPDF && JsPDF.API && JsPDF.API.autoTable) ||
      window.autoTable ||
      window.jspdfAutotable ||
      null;
  }

  // ✅ PDF export (FIXED)
  $(document).off('click.exportPdf').on('click.exportPdf', '#exportPdfBtn', async function(){
    var JsPDF = window.jsPDF || (window.jspdf && window.jspdf.jsPDF);

    if (!JsPDF) {
      console.log('jsPDF missing. window.jspdf=', window.jspdf);
      toast('error', 'jsPDF not loaded. Check Network tab for jsDelivr links.');
      return;
    }

    var doc = new JsPDF({orientation:'landscape', unit:'pt', format:'a4'});

    var autoTableFn = getAutoTableFn(doc);
    if (typeof autoTableFn !== 'function') {
      try {
        await ensureAutoTableLoaded();
        autoTableFn = getAutoTableFn(doc);
      } catch(e) {
        console.log('AutoTable load error:', e);
        toast('error', 'AutoTable plugin not loaded. Check script loading / CSP / network.');
        return;
      }
    }

    var title = 'Pending applications';
    var totalLoans = $('#totalLoansBox').text().trim();
    var totalAmt = $('#totalAmountBox').text().trim();

    doc.setFontSize(14);
    doc.text(title, 40, 40);
    doc.setFontSize(10);
    doc.text('Total Loans: ' + totalLoans + '    Total Amount: ' + totalAmt, 40, 60);

    var head = [];
    $('#loansTable thead th').each(function(i){
      if (i === 8) return; // skip Actions
      head.push($(this).text().trim());
    });

    var body = [];
    $('#loansTable tbody tr.loan-row:visible').each(function(){
      var row = [];
      $(this).find('td').each(function(i){
        if (i === 8) return; // skip Actions
        row.push($(this).text().trim());
      });
      body.push(row);
    });

    if (!body.length) {
      doc.text('No matching records to export.', 40, 90);
      doc.save('pending-applications.pdf');
      return;
    }

    body.push(['', '', 'TOTAL', totalAmt, '', '', '', '']);

    var opts = {
      startY: 80,
      head: [head],
      body: body,
      styles: { fontSize: 9, cellPadding: 5 },
      columnStyles: { 3: { halign:'right' }, 5: { halign:'right' } }
    };

    // call in a way that works for all attachment styles
    if (autoTableFn === window.autoTable || autoTableFn === window.jspdfAutotable) {
      autoTableFn(doc, opts);
    } else {
      autoTableFn.call(doc, opts);
    }

    doc.save('pending-applications.pdf');
  });

  initTooltips();
  updateFilterAndTotals();

})();
JS;

$this->registerJs($js, View::POS_END);
?>

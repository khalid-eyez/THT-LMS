<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\dynagrid\DynaGrid;
use kartik\daterange\DateRangePicker;

/**
 * @var yii\web\View $this
 * @var app\models\CustomerSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Customers List';

$gridcolumns = [
    ['class' => 'kartik\grid\SerialColumn'],
    'customerID',
    'full_name',
    'birthDate',
    'gender',
    'NIN',
    [
        'attribute' => 'created_at',
        'label' => 'Reg. Date',
        'value' => static fn($model) => Yii::$app->formatter->asDate($model->created_at, 'php:d M Y'),
        'filter' => DateRangePicker::widget([
            'model' => $searchModel,
            'attribute' => 'date_range',
            'convertFormat' => true,
            'pluginOptions' => [
                'locale' => ['format' => 'Y-m-d', 'separator' => ' - '],
                'opens' => 'left',
                'autoUpdateInput' => false,
            ],
            'options' => [
                'class' => 'form-control js-customer-range',
                'placeholder' => 'Select date range',
                'readonly' => true,
            ],
        ]),
        'format' => 'raw',
    ],
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{view}',
        'buttons' => [
            'view' => static fn($url, $model) => Html::a(
                '<i class="fa fa-eye"></i>',
                ['/loans/customer/view', 'customerID' => $model->id], // keep as-is (matches your current code)
                [
                    'data-pjax' => '0',
                    'data-toggle' => 'tooltip',
                    'title' => 'View Customer',
                ]
            ),
        ],
    ],
];

?>
<style>
    .modal.modal-fluid .modal-dialog {
        width: 70%;
        max-width: 70%;
        margin: 30px auto;
    }

    table, .summary { background-color: #fff !important; }
    .kv-grid-table th { color: #058aba !important; }

    /* Top bar */
    .customers-topbar{
        background:#fff;
        border:1px solid #e5e5e5;
        border-radius:4px;
        padding:12px 14px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        margin-bottom:10px;
    }
    .customers-title{
        display:flex;
        align-items:center;
        gap:8px;
        font-size:16px;
        font-weight:600;
        color:#333;
    }
    .customers-title i{ color:#058aba; }

    /* Custom export dropdown (same as previous view) */
    .export-dd { position: relative; display:inline-block; }
    .export-dd .btn { border:1px solid #ccc; background:#fff; }
    .export-dd .dropdown-menu{
        position:absolute;
        right:0;
        top:100%;
        z-index:9999;
        min-width:170px;
        padding:6px 0;
        margin:6px 0 0;
        background:#fff;
        border:1px solid rgba(0,0,0,.15);
        border-radius:4px;
        box-shadow:0 6px 12px rgba(0,0,0,.175);
        display:none;
        list-style:none;
    }
    .export-dd.open .dropdown-menu{ display:block; }
    .export-dd .dropdown-menu a{
        display:block;
        padding:8px 14px;
        color:#333;
        text-decoration:none;
        cursor:pointer;
        white-space:nowrap;
    }
    .export-dd .dropdown-menu a:hover{ background:#f5f5f5; }
    .export-dd .caret{
        display:inline-block;
        width:0;height:0;
        margin-left:6px;
        vertical-align:middle;
        border-top:4px dashed;
        border-right:4px solid transparent;
        border-left:4px solid transparent;
    }

    /* Neutralize disabled styles if theme tries to disable */
    #custom-export-dd-customers .btn[disabled],
    #custom-export-dd-customers .btn.disabled{
        opacity:1 !important;
        pointer-events:auto !important;
        cursor:pointer !important;
        filter:none !important;
    }
</style>

<!-- ✅ Frontend export libs -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<!-- ✅ jsPDF (UMD) + AutoTable -->
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.5.31/dist/jspdf.plugin.autotable.min.js"></script>

<div class="breadcomb-area bg-white">
    <div class="container bg-white">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg-white">

<?php
Pjax::begin([
    'id' => 'pjax-customers-grid',
    'timeout' => 0,
    'enablePushState' => true,
    'enableReplaceState' => true,
    'linkSelector' => '#pjax-customers-grid a:not([data-pjax="0"])',
    'formSelector' => '#pjax-customers-grid form',
]);
?>

                <!-- Top bar -->
                <div class="customers-topbar">
                    <div class="customers-title">
                        <i class="fa fa-users"></i>
                        <span>Customers List</span>
                    </div>

                    <!-- ✅ Custom Export dropdown (PDF + Excel) -->
                    <div class="export-dd" id="custom-export-dd-customers">
                        <button type="button" class="btn btn-default" id="btn-export-toggle-customers" data-pjax="0" aria-expanded="false">
                            <i class="fa fa-download"></i> Export <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btn-export-toggle-customers">
                            <li><a href="#" data-export="pdf"  data-pjax="0"><i class="fa fa-file-pdf-o"></i> PDF</a></li>
                            <li><a href="#" data-export="xlsx" data-pjax="0"><i class="fa fa-file-excel-o"></i> Excel</a></li>
                        </ul>
                    </div>
                </div>

                <?= DynaGrid::widget([
                    'columns' => $gridcolumns,
                    'storage' => DynaGrid::TYPE_COOKIE,
                    'theme' => 'panel-info',
                    'gridOptions' => [
                        'dataProvider' => $dataProvider,
                        'filterModel'  => $searchModel,
                        'pjax' => false, // outer PJAX only
                        'toolbar' => false,
                        'panel' => [
                            'heading' => false,
                            'before'  => false,
                            'after'   => false,
                        ],
                    ],
                    'options' => ['id' => 'dynagrid-customers-1'],
                ]); ?>

<?php Pjax::end(); ?>

            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
(function () {

  function enableExportUI(){
    var btn = document.getElementById('btn-export-toggle-customers');
    if (!btn) return;
    btn.disabled = false;
    btn.removeAttribute('disabled');
    btn.classList.remove('disabled');
    btn.style.pointerEvents = 'auto';

    var dd = document.getElementById('custom-export-dd-customers');
    if (dd) {
      var links = dd.querySelectorAll('a[data-export]');
      for (var i=0;i<links.length;i++){
        links[i].setAttribute('data-pjax','0');
        links[i].style.pointerEvents = 'auto';
        links[i].classList.remove('disabled');
        links[i].removeAttribute('disabled');
      }
    }
  }

  function enableExportUIAsync(){
    requestAnimationFrame(function(){
      enableExportUI();
      requestAnimationFrame(enableExportUI);
    });
  }

  // ------------------ dropdown ------------------
  function closeDropdown(){
    var dd = document.getElementById('custom-export-dd-customers');
    if (dd) dd.classList.remove('open');
    var btn = document.getElementById('btn-export-toggle-customers');
    if (btn) btn.setAttribute('aria-expanded','false');
  }
  function toggleDropdown(){
    enableExportUIAsync();
    var dd = document.getElementById('custom-export-dd-customers');
    if (!dd) return;
    dd.classList.toggle('open');
    var btn = document.getElementById('btn-export-toggle-customers');
    if (btn) btn.setAttribute('aria-expanded', dd.classList.contains('open') ? 'true' : 'false');
  }

  if (!window.__customersExportDropdownBound) {
    window.__customersExportDropdownBound = true;
    document.addEventListener('click', function(e){
      var toggle = e.target && e.target.closest ? e.target.closest('#btn-export-toggle-customers') : null;
      var dd = document.getElementById('custom-export-dd-customers');

      if (toggle) {
        e.preventDefault();
        e.stopPropagation();
        toggleDropdown();
        return;
      }
      if (dd && !dd.contains(e.target)) closeDropdown();
    }, true);
  }

  // ------------------ export helpers ------------------
  function getGridTable(scope){
    scope = scope || document;
    return scope.querySelector('#pjax-customers-grid .kv-grid-table') || scope.querySelector('.kv-grid-table');
  }

  function isActionCell(td){
    return !!(td && td.querySelector && td.querySelector('a i.fa-eye'));
  }

  function tableToMatrix(table, options){
    options = options || {};
    var includeHeader = options.includeHeader !== false;

    var rows = [];
    var trs = table.querySelectorAll('tr');

    for (var i=0;i<trs.length;i++){
      var tr = trs[i];
      if (tr.classList.contains('kv-filter-row')) continue;

      var isHeader = tr.querySelectorAll('th').length > 0;
      if (!includeHeader && isHeader) continue;

      var cells = tr.querySelectorAll('th,td');
      var row = [];

      for (var c=0;c<cells.length;c++){
        var cell = cells[c];

        if (!isHeader && isActionCell(cell)) continue;

        if (isHeader) {
          var t = (cell.innerText || cell.textContent || '').replace(/\\s+/g,' ').trim();
          if (t === '' && c === cells.length - 1) continue;
        }

        var text = (cell.innerText || cell.textContent || '');
        text = text.replace(/\\s+/g,' ').trim();
        row.push(text);
      }

      if (row.join('').trim() !== '') rows.push(row);
    }
    return rows;
  }

  function getCurrentPageFromUrl(){
    try {
      var url = new URL(window.location.href);
      var p = parseInt(url.searchParams.get('page') || '1', 10);
      return isNaN(p) || p < 1 ? 1 : p;
    } catch(e) {
      return 1;
    }
  }

  function getTotalPagesFromDom(){
    var pager = document.querySelector('#pjax-customers-grid .pagination') || document.querySelector('.pagination');
    if (!pager) return 1;

    var lastLink = pager.querySelector('a[rel="last"], li.last a, a[aria-label*="Last"], a[title*="Last"]');
    if (lastLink) {
      try {
        var u = new URL(lastLink.getAttribute('href'), window.location.origin);
        var lp = parseInt(u.searchParams.get('page') || '1', 10);
        if (!isNaN(lp) && lp >= 1) return lp;
      } catch(e){}
    }

    var links = pager.querySelectorAll('a');
    var max = 1;
    for (var i=0;i<links.length;i++){
      var txt = (links[i].textContent || '').trim();
      var n = parseInt(txt, 10);
      if (!isNaN(n) && n > max) max = n;
    }
    return max || 1;
  }

  function buildPageUrl(pageNumber){
    var url = new URL(window.location.href);
    url.searchParams.set('page', String(pageNumber));
    return url.toString();
  }

  function fetchHtml(url){
    return fetch(url, {
      method: 'GET',
      credentials: 'same-origin',
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(function(r){
      if (!r.ok) throw new Error('HTTP ' + r.status);
      return r.text();
    });
  }

  function extractMatrixFromHtml(html, includeHeader){
    var parser = new DOMParser();
    var doc = parser.parseFromString(html, 'text/html');
    var table = getGridTable(doc);
    if (!table) return [];
    return tableToMatrix(table, { includeHeader: includeHeader });
  }

  async function gatherAllPagesMatrix(){
    var totalPages = getTotalPagesFromDom();
    var currentPage = getCurrentPageFromUrl();
    var all = [];

    for (var p = 1; p <= totalPages; p++){
      var includeHeader = (p === 1);

      if (p === currentPage) {
        var table = getGridTable(document);
        if (!table) throw new Error('Table not found');

        var partDom = (p === 1)
          ? tableToMatrix(table, { includeHeader: true })
          : tableToMatrix(table, { includeHeader: false });

        for (var i=0;i<partDom.length;i++) all.push(partDom[i]);
      } else {
        var html = await fetchHtml(buildPageUrl(p));
        var part = extractMatrixFromHtml(html, includeHeader);
        for (var j=0;j<part.length;j++) all.push(part[j]);
      }
    }

    return all;
  }

  async function exportXLSX(){
    enableExportUIAsync();
    if (!window.XLSX) return alert('XLSX library not loaded');

    var rows = await gatherAllPagesMatrix();
    if (!rows.length) return alert('No rows to export');

    var wb = XLSX.utils.book_new();
    var ws = XLSX.utils.aoa_to_sheet(rows);
    XLSX.utils.book_append_sheet(wb, ws, 'Customers');
    XLSX.writeFile(wb, 'customers.xlsx');
  }

  // Robust AutoTable detection + lazy-load if missing (same as previous)
  var __autoTableLoadPromise = null;
  function ensureAutoTableLoaded(){
    if (window.autoTable || window.jspdfAutotable) return Promise.resolve();
    if (__autoTableLoadPromise) return __autoTableLoadPromise;

    __autoTableLoadPromise = new Promise(function(resolve, reject){
      var s = document.createElement('script');
      s.src = 'https://cdn.jsdelivr.net/npm/jspdf-autotable@3.5.31/dist/jspdf.plugin.autotable.min.js';
      s.onload = function(){ resolve(); };
      s.onerror = function(){ reject(new Error('Failed to load AutoTable')); };
      document.head.appendChild(s);
    });

    return __autoTableLoadPromise;
  }

  async function exportPDF(){
    enableExportUIAsync();

    var JsPDF = (window.jspdf && window.jspdf.jsPDF) ? window.jspdf.jsPDF : window.jsPDF;
    if (!JsPDF) return alert('jsPDF not loaded');

    function getAutoTableFn(doc){
      return doc.autoTable ||
        (JsPDF.API && JsPDF.API.autoTable) ||
        window.autoTable ||
        window.jspdfAutotable ||
        null;
    }

    var testDoc = new JsPDF('l','pt','a4');
    var fn = getAutoTableFn(testDoc);
    if (typeof fn !== 'function') {
      try { await ensureAutoTableLoaded(); }
      catch (e) { return alert('jsPDF AutoTable not loaded. Check script order.'); }
    }

    var rows = await gatherAllPagesMatrix();
    if (rows.length < 2) return alert('No rows to export');

    var doc = new JsPDF('l', 'pt', 'a4');
    doc.text('Customers', 40, 30);

    var autoTableFn = getAutoTableFn(doc);
    if (typeof autoTableFn !== 'function') {
      return alert('jsPDF AutoTable not loaded. Check script order.');
    }

    var opts = {
      head: [rows[0]],
      body: rows.slice(1),
      startY: 50,
      styles: { fontSize: 8 }
    };

    if (autoTableFn === window.autoTable || autoTableFn === window.jspdfAutotable) {
      autoTableFn(doc, opts);
    } else {
      autoTableFn.call(doc, opts);
    }

    doc.save('customers.pdf');
  }

  // Export click handler (bound once, capture)
  if (!window.__customersExportClickBound) {
    window.__customersExportClickBound = true;
    document.addEventListener('click', function(e){
      var a = e.target && e.target.closest ? e.target.closest('#custom-export-dd-customers a[data-export]') : null;
      if (!a) return;

      e.preventDefault();
      e.stopPropagation();

      closeDropdown();
      enableExportUIAsync();

      var type = a.getAttribute('data-export');
      if (type === 'pdf')  exportPDF();
      if (type === 'xlsx') exportXLSX();
    }, true);
  }

  // ------------------ daterangepicker reinit ------------------
  function reInit(scope){
    var \$s = scope ? $(scope) : $(document);

    enableExportUIAsync();

    if (\$.fn.tooltip) \$s.find('[data-toggle="tooltip"]').tooltip();

    if (!\$.fn.daterangepicker) return;
    \$s.find('input.js-customer-range').each(function(){
      var \$el = $(this);
      \$el.prop('disabled', false);

      try {
        var drp = \$el.data('daterangepicker');
        if (drp) { drp.remove(); \$el.removeData('daterangepicker'); }
      } catch(e){}

      \$el.daterangepicker({
        opens:'left',
        autoUpdateInput:false,
        locale:{format:'YYYY-MM-DD', separator:' - '}
      });

      \$el.off('.drpfix');
      \$el.on('apply.daterangepicker.drpfix', function(ev,p){
        $(this).val(
          p.startDate.format('YYYY-MM-DD') + ' - ' + p.endDate.format('YYYY-MM-DD')
        ).trigger('change');
      }).on('cancel.daterangepicker.drpfix', function(){
        $(this).val('').trigger('change');
      });
    });
  }

  // Initial
  $(document).ready(function(){ reInit(document); });

  // After PJAX navigation/filter
  $(document).on('pjax:complete pjax:end', '#pjax-customers-grid', function(e){
    reInit(e.target);
    closeDropdown();
  });

})();
JS);
?>

<?php
// Optional loader hooks (same pattern as your previous one)
$this->registerJs(<<<JS
(function () {
  $(document).on('pjax:send', '#pjax-customers-grid', function () {
    $('#global-loader').show();
  });

  $(document).on('pjax:complete pjax:end pjax:error', '#pjax-customers-grid', function () {
    $('#global-loader').hide();
  });

  $(window).on('beforeunload', function () {
    $('#global-loader').show();
  });
})();
JS);
?>

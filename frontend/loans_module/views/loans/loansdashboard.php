<?php
use yii\helpers\Json;

$this->title = 'Loans Dashboard';

$labels12 = Json::encode($labels12);

$statusLabels = Json::encode($statusLabels);
$statusCounts = Json::encode($statusCounts);

$disburseSeries = Json::encode($disburseSeries);
$repaySeries    = Json::encode($repaySeries);

$unpaidSeries  = Json::encode($unpaidSeries);
$penaltySeries = Json::encode($penaltySeries);

$typeLabels = Json::encode($typeLabels);
$typeCounts = Json::encode($typeCounts);

$accruedSeries      = Json::encode($accruedSeries);
$paidInterestSeries = Json::encode($paidInterestSeries);

$typeDisbLabels  = Json::encode($typeDisbLabels);
$typeDisbAmounts = Json::encode($typeDisbAmounts);

$interestTypeLabels  = Json::encode($interestTypeLabels);
$interestTypeAmounts = Json::encode($interestTypeAmounts);

$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['position' => \yii\web\View::POS_END]);
$this->registerCssFile('@web/css/dashboard-blue.css');

// ---- KPI hints: ONLY last 12 months number ----
$fmtMoney = fn($v) => number_format((float)$v, 2);
$fmtInt   = fn($v) => number_format((int)$v);

$hintCustomers12      = "12m: " . $fmtInt($customersLast12);
$hintShareholders12   = ($shareholdersLast12 !== null) ? ("12m: " . $fmtInt($shareholdersLast12)) : "";
$hintDisbursed12      = "12m: " . $fmtMoney($disbursedLast12);
$hintProcessing12     = "12m: " . $fmtMoney($processingLast12);
$hintOutstanding12    = "12m: " . $fmtMoney($outstandingLast12);
$hintRepaymentPaid12  = "12m: " . $fmtMoney($repaymentPaidLast12);
$hintAccruedInterest12= "12m: " . $fmtMoney($accruedInterestLast12);
$hintPaidInterest12   = "12m: " . $fmtMoney($paidInterestLast12);

// ---- NEW hints (last 12 months) for deposits ----
$hintMonthlyDeposits12 = isset($monthlyDepositsLast12) ? ("12m: " . $fmtMoney($monthlyDepositsLast12)) : "";
$hintCapitalDeposits12 = isset($capitalDepositsLast12) ? ("12m: " . $fmtMoney($capitalDepositsLast12)) : "";
?>
<style>
/* ------- compact KPI cards ------- */
.notika-kpi-card {
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
  border: 1px solid rgba(15, 23, 42, 0.06);
  padding: 8px 10px;              /* smaller */
  display: flex;
  gap: 10px;                      /* smaller */
  align-items: center;
  margin-bottom: 10px;            /* smaller */
  min-height: 56px;               /* meaningful but tight */
}

.kpi-icon {
  width: 34px;                    /* smaller */
  height: 34px;
  border-radius: 10px;
  display: grid;
  place-items: center;
  background: rgba(59, 130, 246, 0.10);
  color: #3b82f6;
  font-size: 16px;                /* smaller */
  flex: 0 0 auto;
}

.kpi-title {
  color: #475569;
  font-size: 11px;                /* smaller */
  margin-bottom: 2px;
  line-height: 1.15;
}

.kpi-value {
  color: #0f172a;
  font-size: 16px;                /* smaller */
  font-weight: 900;
  line-height: 1.1;
}

.kpi-hint {
  color: #64748b;
  font-size: 10px;                /* smaller */
  margin-top: 2px;
  line-height: 1.1;
  min-height: 0;
}

/* Hide hint area when empty to keep cards tight */
.kpi-hint:empty { display: none; }

/* ------- cards & charts styling (same as before) ------- */
.notika-card {
  background: #ffffff;
  border-radius: 14px;
  box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
  border: 1px solid rgba(15, 23, 42, 0.06);
  margin-bottom: 14px;
}
.notika-card-header {
  padding: 12px 14px;
  border-bottom: 1px solid rgba(15, 23, 42, 0.06);
}
.notika-card-header h4 {
  margin: 0;
  color: #0f172a;
  font-weight: 800;
  font-size: 14px;
}
.notika-card-body { padding: 10px 14px; }

/* ---- standardized chart heights ---- */
.chart-wrap-small { height: 180px; }
.chart-wrap-line  { height: 220px; }

.chart-wrap-small canvas,
.chart-wrap-line canvas {
  width: 100% !important;
  height: 100% !important;
  display: block;
}
@media print {
    /* Hide your includes */
  .main-menu-area,                 /* main menu block you pasted */
  .mobile-menu-area,               /* if your mobile menu uses this */
  .header-top-area,                /* top header (notika default) */
  .logo-area,                      /* if present */
  .breadcomb-area,                 /* breadcrumbs area (notika) */
  .footer-copyright-area,          /* notika footer area */
  footer,                          /* your fixed footer */
  #global-loader,                  /* spinner */
  .pdf-export-fab,                 /* floating pdf button */
  .no-print {                      /* generic */
    display: none !important;
    visibility: hidden !important;
  }
   a[href]:after {
    content: "" !important;
  }
  /* Remove body background (your layout forces blue) */
  body {
    background: #ffffff !important;
  }

  /* Remove any padding/margins caused by header/menu spacing */
  .content {
    margin: 0 !important;
    padding: 0 !important;
  }

  /* Ensure the dashboard uses full width on paper */
  .container, .container-fluid {
    width: 100% !important;
    max-width: 100% !important;
    padding-left: 6px !important;
    padding-right: 6px !important;
  }

  /* Keep cards from splitting across pages */
  .notika-card, .notika-kpi-card {
    break-inside: avoid !important;
    page-break-inside: avoid !important;
    box-shadow: none !important;
  }

  /* Print-friendly columns (2 KPI cards per row) */
  .row { margin-left: -6px !important; margin-right: -6px !important; }
  [class*="col-"] { padding-left: 6px !important; padding-right: 6px !important; }

  .col-lg-3.col-md-6.col-sm-6.col-xs-12 {
    width: 50% !important;
    float: left !important;
  }

  /* Lock chart sizes */
  .chart-wrap-small { height: 180px !important; }
  .chart-wrap-line  { height: 240px !important; }
  .chart-wrap-small canvas,
  .chart-wrap-line canvas {
    width: 100% !important;
    height: 100% !important;
  }
 
  /* Avoid browser adding extra margins */
  @page { margin: 10mm; }
  /* hide app chrome */
  .header-top-area, .footer-copyright-area, .sidebar, .nav, .breadcrumb, .no-print { display:none !important; }

  /* make cards print nicely */
  .notika-card, .notika-kpi-card { box-shadow: none !important; border: 1px solid #ddd !important; }

  /* avoid breaking cards across pages */
  .notika-card, .notika-kpi-card { break-inside: avoid; page-break-inside: avoid; }

  /* remove extra spacing */
  .container, .container-fluid { width: 100% !important; }
  html, body {
    width: 100% !important;
    height: auto !important;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }

  /* If your theme has margins/padding wrappers, flatten them */
  .container, .container-fluid {
    width: 100% !important;
    padding-left: 6px !important;
    padding-right: 6px !important;
  }

  /* Bootstrap rows can behave oddly; reduce gutters */
  .row {
    margin-left: -6px !important;
    margin-right: -6px !important;
  }
  [class*="col-"] {
    padding-left: 6px !important;
    padding-right: 6px !important;
  }

  /* Keep cards from splitting across pages */
  .notika-card, .notika-kpi-card {
    break-inside: avoid !important;
    page-break-inside: avoid !important;
  }

  /* Remove shadows (they print ugly + change sizing) */
  .notika-card, .notika-kpi-card {
    box-shadow: none !important;
  }

  /* Force KPI grid to be predictable on paper:
     use 2 per row for readability (50% each) */
  .notika-kpi-card {
    margin-bottom: 8px !important;
  }
  .col-lg-3.col-md-6.col-sm-6.col-xs-12 {
    width: 50% !important;
    float: left !important;
  }

  /* Charts: lock wrapper heights so canvases don't collapse/expand */
  .chart-wrap-small { height: 180px !important; }
  .chart-wrap-line  { height: 240px !important; }

  .chart-wrap-small canvas,
  .chart-wrap-line canvas {
    width: 100% !important;
    height: 100% !important;
  }

  /* Avoid fixed elements interfering */
  .pdf-export-fab, .no-print {
    display: none !important;
  }
}
/* ===== Floating PDF Export Button ===== */
.pdf-export-fab {
  position: fixed;
  top: 25%;              /* adjust if header height changes */
  right: 14%;
  z-index: 9999;
}

.pdf-export-fab button {
  display: flex;
  align-items: center;
  gap: 6px;
  background: #3b82f6;
  color: #ffffff;
  border: none;
  border-radius: 999px;
  padding: 8px 14px;
  font-size: 12px;
  font-weight: 700;
  cursor: pointer;
  box-shadow: 0 6px 16px rgba(59, 130, 246, 0.35);
  transition: all 0.2s ease;
}

.pdf-export-fab button:hover {
  background: #2563eb;
  transform: translateY(-1px);
}

.pdf-export-fab i {
  font-size: 14px;
}

/* Hide button when printing */
@media print {
  .no-print {
    display: none !important;
  }
}

</style>

<div class="wizard-area" >
    <!-- Floating PDF Export Button -->
<div class="pdf-export-fab no-print">
  <button onclick="window.print()" title="Export dashboard to PDF">
    <i class="notika-icon notika-print"></i>
    <span>PDF</span>
  </button>
</div>

  <div class="container" >
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
        <div class="container-fluid" >

          <!-- KPI CARDS (Row 1) -->
          <div class="row">
            <?= $this->render('_kpi', ['title' => 'Total Customers', 'value' => number_format($totalCustomers), 'icon' => 'notika-icon notika-support', 'hint' => $hintCustomers12]) ?>
            <?= $this->render('_kpi', ['title' => 'Total Shareholders', 'value' => number_format($totalShareholders), 'icon' => 'notika-icon notika-house', 'hint' => $hintShareholders12]) ?>
            <?= $this->render('_kpi', ['title' => 'Total Loans', 'value' => number_format($totalLoans), 'icon' => 'notika-icon notika-credit-card', 'hint' => '' ]) ?>
            <?= $this->render('_kpi', ['title' => 'Active Loans', 'value' => number_format($activeLoans), 'icon' => 'notika-icon notika-checked', 'hint' => '' ]) ?>
          </div>

          <!-- KPI CARDS (Row 2) -->
          <div class="row">
            <?= $this->render('_kpi', ['title' => 'Finished Loans', 'value' => number_format($finishedLoans), 'icon' => 'notika-icon notika-close', 'hint' => '' ]) ?>
            <?= $this->render('_kpi', ['title' => 'Total Disbursed', 'value' => number_format($totalDisbursed, 2), 'icon' => 'notika-icon notika-dollar', 'hint' => $hintDisbursed12]) ?>
            <?= $this->render('_kpi', ['title' => 'Processing Fees', 'value' => number_format($totalProcessingFees, 2), 'icon' => 'notika-icon notika-edit', 'hint' => $hintProcessing12]) ?>
            <?= $this->render('_kpi', ['title' => 'Outstanding Balance', 'value' => number_format($totalOutstanding, 2), 'icon' => 'notika-icon notika-finance', 'hint' => $hintOutstanding12]) ?>
          </div>

          <!-- KPI CARDS (Row 3) -->
          <div class="row">
            <?= $this->render('_kpi', ['title' => 'Total Repayment (Paid)', 'value' => number_format($totalRepaymentPaid, 2), 'icon' => 'notika-icon notika-pay', 'hint' => $hintRepaymentPaid12]) ?>
            <?= $this->render('_kpi', ['title' => 'Total Principal Paid', 'value' => number_format($totalPrincipalPaid, 2), 'icon' => 'notika-icon notika-finance', 'hint' => '' ]) ?>
            <?= $this->render('_kpi', ['title' => 'Total Accrued Interest', 'value' => number_format($totalAccruedInterest, 2), 'icon' => 'notika-icon notika-bar-chart', 'hint' => $hintAccruedInterest12]) ?>
            <?= $this->render('_kpi', ['title' => 'Total Paid Interest', 'value' => number_format($totalPaidInterest, 2), 'icon' => 'notika-icon notika-bar-chart', 'hint' => $hintPaidInterest12]) ?>
          </div>

          <!-- KPI CARDS (Row 4) -->
          <div class="row">
            <?= $this->render('_kpi', [
              'title' => 'Expected Repayment (This Week)',
              'value' => number_format($totalExpectedThisWeek, 2),
              'icon' => 'notika-icon notika-calendar',
              'hint' => ''
            ]) ?>
            <?= $this->render('_kpi', [
              'title' => 'Overdues (Unpaid + Penalties)',
              'value' => number_format($totalOverdueUnpaid + $totalPenalties, 2),
              'icon' => 'notika-icon notika-alarm',
              'hint' => ''
            ]) ?>

            <!-- ===== NEW: last 2 cards (Total Deposits & Total Capital) ===== -->
            <?= $this->render('_kpi', [
              'title' => 'Total Monthly Deposits',
              'value' => number_format($totalMonthlyDeposits ?? 0, 2),
              'icon' => 'notika-icon notika-wallet',
              'hint' => $hintMonthlyDeposits12
            ]) ?>
            <?= $this->render('_kpi', [
              'title' => 'Total Capital Deposits',
              'value' => number_format($totalCapitalDeposits ?? 0, 2),
              'icon' => 'notika-icon notika-dollar',
              'hint' => $hintCapitalDeposits12
            ]) ?>
          </div>

          <!-- SMALL CHARTS -->
          <div class="row">
            <div class="col-lg-6">
              <div class="notika-card">
                <div class="notika-card-header"><h4>Loans by Status</h4></div>
                <div class="notika-card-body">
                  <div class="chart-wrap-small"><canvas id="statusChart"></canvas></div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="notika-card">
                <div class="notika-card-header"><h4>Top Loan Types (Count)</h4></div>
                <div class="notika-card-body">
                  <div class="chart-wrap-small"><canvas id="typesChart"></canvas></div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6">
              <div class="notika-card">
                <div class="notika-card-header"><h4>Loan Types by Disbursement</h4></div>
                <div class="notika-card-body">
                  <div class="chart-wrap-small"><canvas id="typeDisbChart"></canvas></div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="notika-card">
                <div class="notika-card-header"><h4>Interest by Type</h4></div>
                <div class="notika-card-body">
                  <div class="chart-wrap-small"><canvas id="interestTypeChart"></canvas></div>
                </div>
              </div>
            </div>
          </div>

          <!-- LINE CHARTS -->
          <div class="row">
            <div class="col-lg-12">
              <div class="notika-card">
                <div class="notika-card-header"><h4>Disbursements vs Repayments (Last 12 months)</h4></div>
                <div class="notika-card-body">
                  <div class="chart-wrap-line"><canvas id="flowChart"></canvas></div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="notika-card">
                <div class="notika-card-header"><h4>Accrued vs Paid Interest (Last 12 months)</h4></div>
                <div class="notika-card-body">
                  <div class="chart-wrap-line"><canvas id="interestChart"></canvas></div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="notika-card">
                <div class="notika-card-header"><h4>Overdues Trend (Unpaid + Penalties)</h4></div>
                <div class="notika-card-body">
                  <div class="chart-wrap-line"><canvas id="overdueChart"></canvas></div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<?php
$js = <<<JS
const labels12 = $labels12;

// Lighter blues
const B1 = '#60a5fa';
const B2 = '#93c5fd';
const B3 = '#bfdbfe';
const B4 = '#dbeafe';
const SLATE = '#64748b';
const RED = '#f87171';

const baseOpts = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom',
      labels: { boxWidth: 10, boxHeight: 10, padding: 10 }
    }
  }
};

new Chart(document.getElementById('statusChart'), {
  type: 'doughnut',
  data: { labels: $statusLabels, datasets: [{ data: $statusCounts, backgroundColor: [B2, B3, B1, B4, SLATE] }] },
  options: { ...baseOpts, cutout: '65%' }
});

new Chart(document.getElementById('typesChart'), {
  type: 'bar',
  data: { labels: $typeLabels, datasets: [{ label: 'Loans', data: $typeCounts, backgroundColor: B1 }] },
  options: {
    ...baseOpts,
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
  }
});

new Chart(document.getElementById('typeDisbChart'), {
  type: 'bar',
  data: { labels: $typeDisbLabels, datasets: [{ label: 'Disbursed', data: $typeDisbAmounts, backgroundColor: B2 }] },
  options: {
    ...baseOpts,
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true } }
  }
});

new Chart(document.getElementById('interestTypeChart'), {
  type: 'doughnut',
  data: { labels: $interestTypeLabels, datasets: [{ data: $interestTypeAmounts, backgroundColor: [B1, B2, B3, B4, SLATE, '#cbd5e1', '#e2e8f0', '#a5b4fc', '#bae6fd', '#fecaca'] }] },
  options: { ...baseOpts, cutout: '65%' }
});

new Chart(document.getElementById('flowChart'), {
  type: 'line',
  data: {
    labels: labels12,
    datasets: [
      { label: 'Disbursed', data: $disburseSeries, borderColor: B1, backgroundColor: 'rgba(96, 165, 250, 0.12)', fill: true, tension: 0.35, pointRadius: 2 },
      { label: 'Repaid', data: $repaySeries, borderColor: B2, backgroundColor: 'rgba(147, 197, 253, 0.12)', fill: true, tension: 0.35, pointRadius: 2 }
    ]
  },
  options: { ...baseOpts, scales: { y: { beginAtZero: true } } }
});

new Chart(document.getElementById('interestChart'), {
  type: 'line',
  data: {
    labels: labels12,
    datasets: [
      { label: 'Accrued Interest', data: $accruedSeries, borderColor: SLATE, backgroundColor: 'rgba(100, 116, 139, 0.10)', fill: true, tension: 0.35, pointRadius: 2 },
      { label: 'Paid Interest', data: $paidInterestSeries, borderColor: B1, backgroundColor: 'rgba(96, 165, 250, 0.10)', fill: true, tension: 0.35, pointRadius: 2 }
    ]
  },
  options: { ...baseOpts, scales: { y: { beginAtZero: true } } }
});

new Chart(document.getElementById('overdueChart'), {
  type: 'line',
  data: {
    labels: labels12,
    datasets: [
      { label: 'Unpaid', data: $unpaidSeries, borderColor: SLATE, backgroundColor: 'rgba(100, 116, 139, 0.10)', fill: true, tension: 0.35, pointRadius: 2 },
      { label: 'Penalties', data: $penaltySeries, borderColor: RED, backgroundColor: 'rgba(248, 113, 113, 0.10)', fill: true, tension: 0.35, pointRadius: 2 }
    ]
  },
  options: { ...baseOpts, scales: { y: { beginAtZero: true } } }
});
JS;

$this->registerJs($js, \yii\web\View::POS_END);
?>

<?php

namespace frontend\loans_module\controllers;
use common\models\Cashbook;
use common\models\CustomerLoan;
use common\models\LoanCategory;
use common\models\RepaymentSchedule;
use Exception;
use frontend\loans_module\models\LoanCalculatorForm;
use frontend\loans_module\models\TopUp;
use yii\base\UserException;
use yii\web\Controller;
use frontend\loans_module\models\LoanService;
use frontend\loans_module\models\Attachments;
use frontend\loans_module\models\Attachment;
use frontend\loans_module\models\CustomerInfo;
use frontend\loans_module\models\LoanInfo;
use frontend\loans_module\models\CustomerLoanSearch;
use frontend\loans_module\models\LoanCalculator;
use common\helpers\PdfHelper;
use yii\web\ErrorAction;
use frontend\loans_module\models\LoanSearch;
use yii\web\UploadedFile;
use common\helpers\UniqueCodeHelper;
use frontend\loans_module\models\LoanRepayment;
use yii;
use frontend\loans_module\models\ExcelReports;
use common\models\RepaymentStatement;
use yii\db\Expression;
use common\models\Customer;
use common\models\LoanType;
use common\models\Shareholder;
use frontend\loans_module\models\ExcutiveSummary;
use yii\helpers\Html;

//use frontend\loans_module\models\LoanCalculatorForm;


class LoansController extends Controller
{
    public $layout="user_dashboard";
    public function actions()
    {
    return [
    'error' => [
        'class' => ErrorAction::class,
        'view'  => 'error', 
    ],
    ];
    }
    public function actionDashboard()
    {
           // ====== CARDS ======

    // ---------- helpers ----------
    $db = Yii::$app->db;

    $hasColumn = function(string $table, string $column) use ($db): bool {
        $schema = $db->schema->getTableSchema($table, true);
        return $schema && isset($schema->columns[$column]);
    };

    // last 12 months labels
    $labels12 = [];
    $monthStarts = [];
    $dt = new \DateTimeImmutable('first day of this month 00:00:00');
    for ($i = 11; $i >= 0; $i--) {
        $m = $dt->sub(new \DateInterval("P{$i}M"));
        $labels12[] = $m->format('M Y');
        $monthStarts[] = $m->format('Y-m-01');
    }
    $start12 = $monthStarts[0] . ' 00:00:00';

    // this week (Mon-Sun)
    $today = new \DateTimeImmutable('now');
    $weekStart = $today->modify('monday this week')->setTime(0,0,0);
    $weekEnd   = $today->modify('sunday this week')->setTime(23,59,59);

    // ---------- KPI: totals (all-time) ----------
    $totalCustomers = (int) Customer::find()->where(['isDeleted' => 0])->count();

    $totalShareholders = (int) Shareholder::find()->where(['isDeleted' => 0])->count();

    $totalLoans = (int) CustomerLoan::find()->where(['isDeleted' => 0])->count();

    $activeLoans = (int) CustomerLoan::find()
        ->where(['isDeleted' => 0, 'status' => CustomerLoan::STATUS_ACTIVE])
        ->count();

    $finishedLoans = (int) CustomerLoan::find()
        ->where(['isDeleted' => 0, 'status' => CustomerLoan::STATUS_FINISHED])
        ->count();

    $totalDisbursed = (float) CustomerLoan::find()
        ->where(['isDeleted' => 0])
        ->sum(new Expression('COALESCE(loan_amount,0) + COALESCE(topup_amount,0)'));

    $totalProcessingFees = (float) CustomerLoan::find()
        ->where(['isDeleted' => 0])
        ->sum('processing_fee');

    // Outstanding balance = SUM(latest repayment_statement.balance per loan)
    $latestBalanceSubquery = (new \yii\db\Query())
        ->select([
            'loanID',
            'max_id' => new Expression('MAX(id)')
        ])
        ->from('repayment_statement')
        ->groupBy('loanID');

    $totalOutstanding = (float) (new \yii\db\Query())
        ->from(['rs' => 'repayment_statement'])
        ->innerJoin(['mx' => $latestBalanceSubquery], 'mx.max_id = rs.id')
        ->sum(new Expression('COALESCE(rs.balance,0)'));

    // Overdues totals
    $totalOverdueUnpaid = (float) RepaymentStatement::find()->sum('unpaid_amount');
    $totalPenalties     = (float) RepaymentStatement::find()->sum('penalty_amount');

    // Total repayment (paid) + principal paid + paid interest
    $totalRepaymentPaid = (float) RepaymentStatement::find()->sum(new Expression('COALESCE(paid_amount,0)'));
    $totalPrincipalPaid = (float) RepaymentStatement::find()->sum(new Expression('COALESCE(principal_amount,0)'));
    $totalPaidInterest  = (float) RepaymentStatement::find()->sum(new Expression('COALESCE(interest_amount,0)'));

    // Accrued interest (expected interest from schedules)
    $totalAccruedInterest = (float) RepaymentSchedule::find()->sum(new Expression('COALESCE(interest_amount,0)'));

    // Expected repayment this week (active dues whose repayment_date falls in this week)
    $totalExpectedThisWeek = (float) RepaymentSchedule::find()
        ->where(['status' => RepaymentSchedule::STATUS_ACTIVE, 'isDeleted' => 0])
        ->andWhere(['between', 'repayment_date', $weekStart->format('Y-m-d H:i:s'), $weekEnd->format('Y-m-d H:i:s')])
        ->sum(new Expression('COALESCE(installment_amount,0)'));

    // ---------- KPI: last 12 months totals for hints ----------
    $customersLast12 = (float) Customer::find()
        ->where(['isDeleted' => 0])
        ->andWhere(['>=', 'created_at', $start12])
        ->count();

    $shareholdersLast12 = null;
    if ($hasColumn('shareholders', 'created_at')) {
        $shareholdersLast12 = (float) Shareholder::find()
            ->where(['isDeleted' => 0])
            ->andWhere(['>=', 'created_at', $start12])
            ->count();
    }

    $disbursedLast12 = (float) CustomerLoan::find()
        ->where(['isDeleted' => 0])
        ->andWhere(['>=', 'created_at', $start12])
        ->sum(new Expression('COALESCE(loan_amount,0) + COALESCE(topup_amount,0)'));

    $processingLast12 = (float) CustomerLoan::find()
        ->where(['isDeleted' => 0])
        ->andWhere(['>=', 'created_at', $start12])
        ->sum(new Expression('COALESCE(processing_fee,0)'));

    $repaymentPaidLast12 = (float) RepaymentStatement::find()
        ->andWhere(['>=', 'payment_date', $start12])
        ->sum(new Expression('COALESCE(paid_amount,0)'));

    $paidInterestLast12 = (float) RepaymentStatement::find()
        ->andWhere(['>=', 'payment_date', $start12])
        ->sum(new Expression('COALESCE(interest_amount,0)'));

    $accruedInterestLast12 = (float) RepaymentSchedule::find()
        ->andWhere(['>=', 'repayment_date', $start12])
        ->sum(new Expression('COALESCE(interest_amount,0)'));

    // Outstanding balance (last 12 months) – interpret as loans created in last 12 months
    $latestBalanceLast12Sub = (new \yii\db\Query())
        ->select(['rs.loanID', 'max_id' => new Expression('MAX(rs.id)')])
        ->from(['rs' => 'repayment_statement'])
        ->innerJoin(['cl' => CustomerLoan::tableName()], 'cl.id = rs.loanID')
        ->where(['cl.isDeleted' => 0])
        ->andWhere(['>=', 'cl.created_at', $start12])
        ->groupBy('rs.loanID');

    $outstandingLast12 = (float) (new \yii\db\Query())
        ->from(['rs' => 'repayment_statement'])
        ->innerJoin(['mx' => $latestBalanceLast12Sub], 'mx.max_id = rs.id')
        ->sum(new Expression('COALESCE(rs.balance,0)'));

    // ---------- CHARTS ----------

    // (A) Loans by status (force include finished)
    $statusRows = CustomerLoan::find()
        ->select(['status', 'cnt' => new Expression('COUNT(*)')])
        ->where(['isDeleted' => 0])
        ->groupBy('status')
        ->asArray()
        ->all();

    $statusMap = [];
    foreach ($statusRows as $r) {
        $statusMap[$r['status']] = (int)$r['cnt'];
    }

    $statusOrder = [
        CustomerLoan::STATUS_NEW,
        CustomerLoan::STATUS_APPROVED,
        CustomerLoan::STATUS_ACTIVE,
        CustomerLoan::STATUS_FINISHED,
        CustomerLoan::STATUS_DISAPPROVED,
    ];

    $statusLabels = $statusOrder;
    $statusCounts = array_map(fn($s) => $statusMap[$s] ?? 0, $statusOrder);

    // (B) Disbursements by month (bar/line feed)
    $disburseRows = CustomerLoan::find()
        ->select([
            'ym'  => new Expression("DATE_FORMAT(created_at, '%Y-%m')"),
            'amt' => new Expression("SUM(COALESCE(loan_amount,0) + COALESCE(topup_amount,0))")
        ])
        ->where(['isDeleted' => 0])
        ->andWhere(['>=', 'created_at', $start12])
        ->groupBy(new Expression("DATE_FORMAT(created_at, '%Y-%m')"))
        ->orderBy(['ym' => SORT_ASC])
        ->asArray()
        ->all();

    $disburseMap = [];
    foreach ($disburseRows as $r) $disburseMap[$r['ym']] = (float)$r['amt'];

    $disburseSeries = [];
    foreach ($monthStarts as $ms) {
        $ym = substr($ms, 0, 7);
        $disburseSeries[] = $disburseMap[$ym] ?? 0.0;
    }

    // (C) Repayments by month (line) sum(paid_amount)
    $repayRows = RepaymentStatement::find()
        ->select([
            'ym'  => new Expression("DATE_FORMAT(payment_date, '%Y-%m')"),
            'amt' => new Expression("SUM(COALESCE(paid_amount,0))")
        ])
        ->andWhere(['>=', 'payment_date', $start12])
        ->groupBy(new Expression("DATE_FORMAT(payment_date, '%Y-%m')"))
        ->orderBy(['ym' => SORT_ASC])
        ->asArray()
        ->all();

    $repayMap = [];
    foreach ($repayRows as $r) $repayMap[$r['ym']] = (float)$r['amt'];

    $repaySeries = [];
    foreach ($monthStarts as $ms) {
        $ym = substr($ms, 0, 7);
        $repaySeries[] = $repayMap[$ym] ?? 0.0;
    }

    // (D) Overdues trend by month (unpaid + penalties)
    $overRows = RepaymentStatement::find()
        ->select([
            'ym'      => new Expression("DATE_FORMAT(payment_date, '%Y-%m')"),
            'unpaid'  => new Expression("SUM(COALESCE(unpaid_amount,0))"),
            'penalty' => new Expression("SUM(COALESCE(penalty_amount,0))")
        ])
        ->andWhere(['>=', 'payment_date', $start12])
        ->groupBy(new Expression("DATE_FORMAT(payment_date, '%Y-%m')"))
        ->orderBy(['ym' => SORT_ASC])
        ->asArray()
        ->all();

    $unpaidMap = [];
    $penaltyMap = [];
    foreach ($overRows as $r) {
        $unpaidMap[$r['ym']]  = (float)$r['unpaid'];
        $penaltyMap[$r['ym']] = (float)$r['penalty'];
    }

    $unpaidSeries = [];
    $penaltySeries = [];
    foreach ($monthStarts as $ms) {
        $ym = substr($ms, 0, 7);
        $unpaidSeries[]  = $unpaidMap[$ym] ?? 0.0;
        $penaltySeries[] = $penaltyMap[$ym] ?? 0.0;
    }

    // (E) Top loan types (count)
    $topTypes = CustomerLoan::find()
        ->select(['t.type', 'cnt' => new Expression('COUNT(cl.id)')])
        ->alias('cl')
        ->innerJoin(['t' => LoanType::tableName()], 't.id = cl.loan_type_ID')
        ->where(['cl.isDeleted' => 0])
        ->groupBy(['t.type'])
        ->orderBy(['cnt' => SORT_DESC])
        ->limit(7)
        ->asArray()
        ->all();

    $typeLabels = array_map(fn($r) => $r['type'], $topTypes);
    $typeCounts = array_map(fn($r) => (int)$r['cnt'], $topTypes);

    // (F) Accrued vs Paid interest by month (last 12)
    $accruedRows = RepaymentSchedule::find()
        ->select([
            'ym'  => new Expression("DATE_FORMAT(repayment_date, '%Y-%m')"),
            'amt' => new Expression("SUM(COALESCE(interest_amount,0))")
        ])
        ->andWhere(['>=', 'repayment_date', $start12])
        ->groupBy(new Expression("DATE_FORMAT(repayment_date, '%Y-%m')"))
        ->orderBy(['ym' => SORT_ASC])
        ->asArray()
        ->all();

    $accruedMap = [];
    foreach ($accruedRows as $r) $accruedMap[$r['ym']] = (float)$r['amt'];

    $accruedSeries = [];
    foreach ($monthStarts as $ms) {
        $ym = substr($ms, 0, 7);
        $accruedSeries[] = $accruedMap[$ym] ?? 0.0;
    }

    $paidInterestRows = RepaymentStatement::find()
        ->select([
            'ym'  => new Expression("DATE_FORMAT(payment_date, '%Y-%m')"),
            'amt' => new Expression("SUM(COALESCE(interest_amount,0))")
        ])
        ->andWhere(['>=', 'payment_date', $start12])
        ->groupBy(new Expression("DATE_FORMAT(payment_date, '%Y-%m')"))
        ->orderBy(['ym' => SORT_ASC])
        ->asArray()
        ->all();

    $paidInterestMap = [];
    foreach ($paidInterestRows as $r) $paidInterestMap[$r['ym']] = (float)$r['amt'];

    $paidInterestSeries = [];
    foreach ($monthStarts as $ms) {
        $ym = substr($ms, 0, 7);
        $paidInterestSeries[] = $paidInterestMap[$ym] ?? 0.0;
    }

    // (G) Loan types per disbursement (sum loan_amount + topup_amount)
    $typeDisbRows = CustomerLoan::find()
        ->select([
            't.type',
            'amt' => new Expression("SUM(COALESCE(cl.loan_amount,0) + COALESCE(cl.topup_amount,0))")
        ])
        ->alias('cl')
        ->innerJoin(['t' => LoanType::tableName()], 't.id = cl.loan_type_ID')
        ->where(['cl.isDeleted' => 0])
        ->groupBy(['t.type'])
        ->orderBy(['amt' => SORT_DESC])
        ->limit(10)
        ->asArray()
        ->all();

    $typeDisbLabels = array_map(fn($r) => $r['type'], $typeDisbRows);
    $typeDisbAmounts = array_map(fn($r) => (float)$r['amt'], $typeDisbRows);

    // (H) Interest per type (pie) from schedules joined to loan -> type
    $interestTypeRows = (new \yii\db\Query())
        ->select([
            't.type',
            'amt' => new Expression("SUM(COALESCE(s.interest_amount,0))")
        ])
        ->from(['s' => RepaymentSchedule::tableName()])
        ->innerJoin(['cl' => CustomerLoan::tableName()], 'cl.id = s.loanID')
        ->innerJoin(['t' => LoanType::tableName()], 't.id = cl.loan_type_ID')
        ->where(['cl.isDeleted' => 0])
        ->groupBy(['t.type'])
        ->orderBy(['amt' => SORT_DESC])
        ->limit(10)
        ->all();

    $interestTypeLabels  = array_map(fn($r) => $r['type'], $interestTypeRows);
    $interestTypeAmounts = array_map(fn($r) => (float)$r['amt'], $interestTypeRows);

    return $this->render('loansdashboard', [
        // KPI totals
        'totalCustomers' => $totalCustomers,
        'totalShareholders' => $totalShareholders,
        'totalLoans' => $totalLoans,
        'activeLoans' => $activeLoans,
        'finishedLoans' => $finishedLoans,
        'totalDisbursed' => $totalDisbursed,
        'totalProcessingFees' => $totalProcessingFees,
        'totalOutstanding' => $totalOutstanding,
        'totalOverdueUnpaid' => $totalOverdueUnpaid,
        'totalPenalties' => $totalPenalties,
        'totalRepaymentPaid' => $totalRepaymentPaid,
        'totalPrincipalPaid' => $totalPrincipalPaid,
        'totalAccruedInterest' => $totalAccruedInterest,
        'totalPaidInterest' => $totalPaidInterest,
        'totalExpectedThisWeek' => $totalExpectedThisWeek,

        // KPI last 12 months for hints
        'customersLast12' => $customersLast12,
        'shareholdersLast12' => $shareholdersLast12,
        'disbursedLast12' => $disbursedLast12,
        'processingLast12' => $processingLast12,
        'outstandingLast12' => $outstandingLast12,
        'repaymentPaidLast12' => $repaymentPaidLast12,
        'accruedInterestLast12' => $accruedInterestLast12,
        'paidInterestLast12' => $paidInterestLast12,

        // charts
        'labels12' => $labels12,
        'statusLabels' => $statusLabels,
        'statusCounts' => $statusCounts,
        'disburseSeries' => $disburseSeries,
        'repaySeries' => $repaySeries,
        'unpaidSeries' => $unpaidSeries,
        'penaltySeries' => $penaltySeries,
        'typeLabels' => $typeLabels,
        'typeCounts' => $typeCounts,

        'accruedSeries' => $accruedSeries,
        'paidInterestSeries' => $paidInterestSeries,

        'typeDisbLabels' => $typeDisbLabels,
        'typeDisbAmounts' => $typeDisbAmounts,

        'interestTypeLabels' => $interestTypeLabels,
        'interestTypeAmounts' => $interestTypeAmounts,
    ]);
       
    }

    public function actionExcutiveSummaryReporter(){
        $model=new ExcutiveSummary();
        if(yii::$app->request->isPost)
            {
              $model->load(yii::$app->request->post());
              return $this->renderAjax('excutivesummaryview',['model'=>$model]); 
            } 
        return $this->renderAjax('excutivesummary_reporter',['model'=>$model]);
       
    }
    public function actionExcutiveSummaryPdf()
    {
        $model=new ExcutiveSummary();
        $model->load(yii::$app->request->post());
        PdfHelper::download($this->renderPartial('excutivesummaryPDF',['model'=>$model]),'ExcutiveSummary',['orientation'=>'L']);
    }
     public function actionExcutiveSummaryExcel()
    {
        $model=new ExcutiveSummary();
        $model->load(yii::$app->request->post());
        $model->excutivesummaryExcel();
    }
    public function actionLoans()
    {
            if(yii::$app->request->isAjax)
            {
                 
              $this->layout="user_dashboard";
        $searchModel = new CustomerLoanSearch();
        $params = array_merge(
        Yii::$app->request->queryParams,
        Yii::$app->request->post()
        );
        $dataProvider = $searchModel->search($params);

        return $this->renderAjax('/loans_crud/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
            }
            else{
                 $this->layout="user_dashboard";
        $searchModel = new CustomerLoanSearch();
        $params = array_merge(
        Yii::$app->request->queryParams,
        Yii::$app->request->post()
        );
        $dataProvider = $searchModel->search($params);

        return $this->render('/loans_crud/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]); 
            }
       
    }
     public function actionExportLoansJson()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $searchModel = new \frontend\loans_module\models\CustomerLoanSearch();

    // Use current filters from query string
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    // ✅ export ALL rows (no pagination)
    $dataProvider->pagination = false;

    $models = $dataProvider->getModels();

    // Must match your grid columns
    $headers = [
        'Loan ID',
        'Loan Type',
        'Loan Amount',
        'Repayment',
        'Duration',
        'Status',
        'Loan Date',
    ];

    $rows = [];
    foreach ($models as $m) {
        $rows[] = [
            (string)($m->loanID ?? ''),
            (string)($m->loanType->type ?? ''),
            (string)($m->loan_amount ?? ''),
            (string)($m->repayment_frequency ?? ''),
            (string)($m->loan_duration_units ?? ''),
            (string)($m->status ?? ''),
            Yii::$app->formatter->asDate($m->created_at, 'php:d M Y'),
        ];
    }

    return [
        'headers' => $headers,
        'rows' => $rows,
        'count' => count($rows),
    ];
}

    public function actionCreateLoan(){
        if(yii::$app->request->isPost){
            try{
                  $loan=(new LoanService(yii::$app->request))->saveLoan();
                  if($loan!=null)
                  {
                    yii::$app->session->setFlash('success',"<i class='fa fa-check-circle'></i> Loan application successful!");
                    return $this->redirect(['loan-view','loanID'=>$loan->id]);
                  }

            }
            catch(UserException $u)
            {
                yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Customer loan application failed !'.$u->getMessage());
                throw $u;
            }
            catch(\Exception $e)
            {
                yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> An unknown error occurred while submitting application!');
                throw $e;

            }
        }
        if(yii::$app->request->isAjax) {
            return $this->renderAjax('loancreate2', [
                'customerinfo' => new CustomerInfo(),
                'loaninfo' => new LoanInfo(),
                'attachments' => new Attachments()
            ]);
        }
        else{
            return $this->redirect('/loans/dashboard');
        }



    }
    public function actionCreateLoanReg($customerID){
        if(yii::$app->request->isPost){
            try{
                  $loan=(new LoanService(yii::$app->request))->saveLoanR($customerID);
                  if($loan!=null)
                  {
                    yii::$app->session->setFlash('success',"<i class='fa fa-check-circle'></i> Loan application successful!");
                    return $this->redirect(['loan-view','loanID'=>$loan->id]);
                  }

            }
            catch(UserException $u)
            {
                yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Customer loan application failed !'.$u->getMessage());
                throw $u;
            }
            catch(\Exception $e)
            {
                yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> An unknown error occurred while submitting application!');
                throw $e;

            }
        }
        if(yii::$app->request->isAjax) {
            return $this->renderAjax('loancreate1', [
                'loaninfo' => new LoanInfo(),
                'attachments' => new Attachments()
            ]);
        }
        else{
            return $this->redirect('/loans/dashboard');
        }



    }
    public function actionLoanView($loanID){
        $loan=CustomerLoan::findOne($loanID);
        return $this->render('loanview',['loan'=>$loan]);
    }
    public function actionLoanFail()
    {
        return $this->render('loanfail');
    }
    public function actionApprove($loanID)
    {
        $loan=CustomerLoan::findOne($loanID);
        $loan->status="approved";
        $loan->approvedby=yii::$app->user->identity->id;
        $loan->approved_at = date('Y-m-d H:i:s');

        if($loan->save()){
            yii::$app->session->setFlash('success','<i class="fa fa-check-circle"></i> Loan status updated successfully!');
            return $this->redirect(yii::$app->request->referrer);
        }

    }
    public function actionDisapprove($loanID)
    {
        $loan=CustomerLoan::findOne($loanID);
        $loan->status="rejected";
        if($loan->save()){
            yii::$app->session->setFlash('success','<i class="fa fa-check-circle"></i> Loan status updated successfully!');
            return $this->redirect(yii::$app->request->referrer);
        }

    }
    public function actionPay($loanID)
    {
       
        $loan=CustomerLoan::findOne($loanID);
        $document=new Attachment();
        if(yii::$app->request->isPost)
            {
             try{
                $transaction=yii::$app->db->beginTransaction();
                $loan->load(yii::$app->request->post());
                $loan->status="active";
                $loan->paidby=yii::$app->user->identity->id;
                $document->load(yii::$app->request->post());
                $uploaded=UploadedFile::getInstance($document,'file');
                $document->file=$uploaded;
                if(!$document->validate())
                {
                    throw new UserException(json_encode($document->getErrors()));
                }
                if(!$loan->save())
                {
                    throw new UserException("unable to update loan data");
                }
                //saving uploaded docs
                $savedfile=$document->saveFile();

                //updating the cashbook record

                $cashbook=new Cashbook();
                $cashbook->credit=$loan->deposit_amount;
                $cashbook->reference_no=UniqueCodeHelper::generate("LD").'-'.$loan->id.date("Y");
                $cashbook->description="[$loan->loanID] Loan disbursement";
                $cashbook->payment_document=$savedfile;
                $cashbook->category="disbursement";
                $cashbook->balance=$cashbook->updatedBalance();

                if(!$cashbook->save()){
                    throw new UserException(json_encode($cashbook->getErrors()));
                }

                // now time for the repayment schedule

                $schedule=(new LoanCalculator)->generateRepaymentSchedule(
                    $loan->loan_amount,
                    $loan->interest_rate,
                    $loan->repayment_frequency,
                    $loan->loan_duration_units,
                    date("Y-m-d H:i:s"),
                );

                    foreach($schedule as $record)
                    {
                       $repaymodel=new RepaymentSchedule();
                       $repaymodel->loanID=$loan->id;
                       $repaymodel->loan_amount=$record['loan_amount'];
                       $repaymodel->interest_amount=$record['interest'];
                       $repaymodel->installment_amount=$record['installment'];
                       $repaymodel->loan_balance=$record['balance'];
                       $repaymodel->principle_amount=$record['principal'];
                       $repaymodel->repayment_date=$record['payment_date'];
                       $repaymodel->status="active";
                       if(!$repaymodel->save())
                        {
                            throw new UserException(json_encode($repaymodel->getErrors()));
                        }

                    }
                    $transaction->commit();
                    // now generating the documents (loan agreement + repayment schedule)
                    yii::$app->session->setFlash("success","Disbursement successful ! ");
                    return $this->render("/loans/docs/loansummaryview",['loan'=>$loan]);
             }
             catch(UserException $r)
             {
                $transaction->rollBack();
               throw $r;
             }
             catch(Exception $w)
             {
               $transaction->rollBack();
               throw $w;
             }
            
            }
        if(yii::$app->request->isAjax)
            {
            return $this->renderAjax("loanpayment",['loan'=>$loan,'document'=>$document]);
            }
            return $this->redirect('/loans/dashboard');

    }
    public function actionRepaymentScheduleMini($loanID){
       $loan=CustomerLoan::findOne($loanID);
       return $this->renderAjax("/loans/docs/repaymentschedule",['loan'=>$loan]);
    }
    public function actionDownloadSummary($loanID)
    {
        $loan=CustomerLoan::findOne($loanID);
        $content=$this->renderPartial("/loans/docs/loansummarypdf",['loan'=>$loan]);
        PdfHelper::download($content,$loan->loanID);

    }
    public function actionTopUp($loanID)
    {
        if(yii::$app->request->isPost)
            {
                $loan=(new TopUp)->topUp($loanID,yii::$app->request);
                return $this->render("/loans/docs/loansummaryview",['loan'=>$loan]);
            }
       return $this->renderAjax("topup_form",['model'=>new TopUp()]);
    }

    public function actionRepay($loanID)
    {
         $repayment_model=new LoanRepayment();
         if(yii::$app->request->isPost)
            {
              $repayment_model->load(yii::$app->request->post());
              $uploaded=UploadedFile::getInstance($repayment_model,'payment_doc');
              $repayment_model->payment_doc=$uploaded;
              return $this->renderAjax('repayment_receipt',['payment_details'=>$repayment_model->pay_dry_run($loanID)]);
            }
         return $this->renderAjax('loanRepayment',['model'=>$repayment_model]);
    }
    public function actionRepaymentOverdues($loanID,$payment_date)
    {
        $loan=CustomerLoan::findOne($loanID);
        return $this->renderAjax('total_repayment',['overdues'=>$loan->computeOverdues($payment_date)]);
        
    }
    public function actionRepaymentConfirm($scheduleID, $paid_amount,$payment_date,$payment_doc){
        $schedule=RepaymentSchedule::findOne($scheduleID);
        $paid=$schedule->pay($payment_date,$paid_amount,$payment_doc);

        PdfHelper::download($this->renderPartial('/loans/docs/repayment_receipt_pdf',['paid'=>$paid]),'payment_receipt_'.$paid['reference']);

        //return $this->render('/loans/docs/repayment_receipt_pdf',['paid'=>$paid]);


    }
    public function actionCancelRepayment($file)
    {
        $filePath = Yii::getAlias('@webroot'.$file);

        if (file_exists($filePath)) {
         unlink($filePath);
        }
        yii::$app->session->setFlash('success','<i class="fa fa-info-circle"></i> Repayment Cancelled');
        return $this->redirect('dashboard');
    }
    public function actionRepaymentStatement($loanID)
    {
        $loan=CustomerLoan::findOne($loanID);

        return $this->render('/docs/repaymentstatement',['loan'=>$loan]);
    }

    public function actionLoanSearch()
    {
        $searchmodel=new LoanSearch;
        if(yii::$app->request->isPost)
            {
               $searchmodel->load(yii::$app->request->post());
               $loans=$searchmodel->searchLoans(); 
               
               return $this->renderAjax('loansearchresult',['loans'=>$loans]);
            }
        return $this->renderAjax('loanssearch',['model'=>$searchmodel]);
    }
     public function actionDownloadScheduleReportPdf($loanID)
    {
        $loan=CustomerLoan::findOne($loanID);
        $content=$this->renderPartial("/loans/docs/repaymentschedulereportpdf",['loan'=>$loan]);
        PdfHelper::download($content,$loan->loanID);

    }
    public function actionDownloadScheduleReportExcel($loanID)
    {
        $loan=CustomerLoan::findOne($loanID);
        (new ExcelReports())->ExcelSchedule($loan);
    }

     public function actionLoanSearchTwo()
    {
        $searchmodel=new LoanSearch;
        if(yii::$app->request->isPost)
            {
               $searchmodel->load(yii::$app->request->post());
               $loans=$searchmodel->searchLoans(); 
               
               return $this->renderAjax('loansearchresulttwo',['loans'=>$loans]);
            }
        return $this->renderAjax('loanssearchtwo',['model'=>$searchmodel]);
    }
    public function actionDownloadRepaymentStatementReportPdf($loanID)
    {
        $loan=CustomerLoan::findOne($loanID);
        $content=$this->renderPartial("/loans/docs/repaymentstatementreportpdf",['loan'=>$loan]);
        PdfHelper::download($content,$loan->loanID,['orientation'=>'L']);

    }
    public function actionDownloadRepaymentStatementReportExcel($loanID)
    {
        $loan=CustomerLoan::findOne($loanID);
        (new ExcelReports())->excelStatement($loan);
    }

    public function actionLoanCalculator()
    {
      $model=new LoanCalculatorForm();
      if(yii::$app->request->isPost)
        { 
            $model->load(yii::$app->request->post());
            return $this->renderAjax('/loans/docs/calculatedrepaymentschedule',['repaymentschedules'=>$model->calculate()]);
        }
      return $this->renderAjax('calculator',['model'=>$model]);
    }
     public function actionLoanCalculatorPdf()
    {
      $model=new LoanCalculatorForm();
      if(yii::$app->request->isPost)
        { 
            $model->load(yii::$app->request->post());
            $content=$this->renderPartial('/loans/docs/calculatedrepaymentschedule_pdf',['repaymentschedules'=>$model->calculate()]);
            PdfHelper::download($content,'repayment_schedule');
        }
    }

    public function actionCategories()
    {
        return $this->renderAjax('loan_categories');
    }
    public function actionAddCategory()
    {
        $category=new LoanCategory();
        $category->load(yii::$app->request->post());

        if($category->save())
            {
                return $this->asJson(['success'=>true,'message'=>'Loan Category added Successfully !']);
            }
            else
                {
                     return $this->asJson(['success'=>false,'error'=>'Loan Category Adding Failed ! '.Html::errorSummary($category)]);
                }
    }

    public function actionCategoryUpdate()
    {
        $id=yii::$app->request->post('id');
        $category=LoanCategory::findOne($id);
        $category->load(yii::$app->request->post());

           if($category->save())
            {
                return $this->asJson(['success'=>true,'message'=>'Loan Category Updated Successfully !']);
            }
            else
                {
                     return $this->asJson(['success'=>false,'error'=>'Loan Category Updating Failed ! '.Html::errorSummary($category)]);
                }
    }
     public function actionLoantypeUpdate()
    {
        $id=yii::$app->request->post('id');
        $type=LoanType::findOne($id);
        $type->load(yii::$app->request->post());

           if($type->save())
            {
                return $this->asJson(['success'=>true,'message'=>'Loan Type Updated Successfully !']);
            }
            else
                {
                     return $this->asJson(['success'=>false,'error'=>'Loan Type Updating Failed ! '.Html::errorSummary($type)]);
                }
    }
    public function actionCategoryDelete()
    {
        $id=yii::$app->request->post('id');
        $category=LoanCategory::findOne($id);
        $category->load(yii::$app->request->post());

           if($category->delete())
            {
                return $this->asJson(['success'=>true,'message'=>'Loan Category Deleted Successfully !']);
            }
            else
                {
                     return $this->asJson(['success'=>false,'error'=>'Loan Category Deleting Failed ! '.Html::errorSummary($category)]);
                }
    }
    public function actionLoantypeDelete()
    {
        $id=yii::$app->request->post('id');
        $type=LoanType::findOne($id);
        $type->load(yii::$app->request->post());

           if($type->delete())
            {
                return $this->asJson(['success'=>true,'message'=>'Loan Type Deleted Successfully !']);
            }
            else
                {
                     return $this->asJson(['success'=>false,'error'=>'Loan Type Deleting Failed ! '.Html::errorSummary($type)]);
                }
    }
    public function actionLoantypeAdd()
    {
        $type=new LoanType();
        $type->load(yii::$app->request->post());

        if($type->save())
            {
                return $this->asJson(['success'=>true,'message'=>'Loan Type added Successfully !']);
            }
            else
                {
                     return $this->asJson(['success'=>false,'error'=>'Loan Type Adding Failed ! '.Html::errorSummary($type)]);
                }
    }

}

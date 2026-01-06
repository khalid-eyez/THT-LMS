<?php
Yii::$app->assetManager->bundles['yii\bootstrap4\BootstrapAsset'] = false;
Yii::$app->assetManager->bundles['yii\bootstrap4\BootstrapPluginAsset'] = false;
// Disable Bootstrap 4 CSS
unset($this->assetBundles['yii\bootstrap4\BootstrapAsset']);

// Disable Bootstrap 4 JavaScript (Plugin)
unset($this->assetBundles['yii\bootstrap4\BootstrapPluginAsset']);

\frontend\assets\Bootstrap5Asset::register($this);

// Optional: register FormWizard after Bootstrap 5
use sandritsch91\yii2\formwizard\FormWizard;
?>

  
<?=FormWizard::widget([
    // required
    'model' => $model,                                                          // The model to be used in the form
    'tabOptions' => [                                                           // These are the options for the Bootstrap Tab widget                                        
        'items' => [
            [
                'label' => 'Step 1',                                            // The label of the tab
                'content' => $this->render('loanscustomer', ['model' => $model]),      // Either the content of the tab
            ],
            [
                'label' => 'Step 2',
                'content' => $this->render('loansdetails', ['model' => $model]),                                       // or a view to be rendered. $model and $form are passed to the view
            ]
        ],
        'navType' => 'nav-pills'
    ],
    // optional
    'validateSteps' => [                                                         // Optional, pass the fields to be validated for each step.                 
        // ['name', 'surname'],
        // [],                                                                     // Leave array empty if no validation is needed  
        // ['email', 'password']
    ],
    'options' => [],                                                            // Wizard-container html options
    'formOptions' => [],                                                        // Form html options
    'buttonOptions' => [                                                        // Button html options
        'previous' => [
            'class' => ['btn', 'btn-secondary'],
            'data' => [
                'formwizard' => 'previous'                                      // If you change this, make sure the clientOptions match
            ]
        ],
        'next' => [
        'class' => ['btn', 'btn-primary'],
        'data' => ['formwizard' => 'next'],
        ],
        'finish' => [
        'class' => ['btn', 'btn-success'],
        'data' => ['formwizard' => 'finish'],
        ],
    ],
    'clientOptions' => [                                                        // Client options for the form wizard, if you need to change them
        // 'finishSelector' => '...',
        // 'nextSelector' => '...',
        // 'previousSelector' => '...',
    ],
    'clientEvents' => [                                                         // Client events for the form wizard
        // 'onNext' => 'function () {...}',
        // 'onPrevious' => 'function () {...}',
        // 'onFinish' => 'function (){...}'
    ]
]);

?>
               
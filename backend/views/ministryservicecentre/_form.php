<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryservicecentres */
/* @var $form yii\widgets\ActiveForm */
$url = Yii::$app->getUrlManager()->createUrl('ministryservicecentre');
?>
<?php
$form = ActiveForm::begin([
            'id' => $formName,
        ]);
?>
<div class="panel-body">
    <div class="row">
        <div class="col-md-3">
            <?php if ($model->isNewRecord) : ?>
                <?=$form->field($model, 'IdServiceCentre')->widget(Select2::class, [
                    'size' => Select2::MEDIUM,
                    'data' => $model->getServicecentres(),
                    'initValueText' => ($model->IdServiceCentre ? $model->serviceCentreName : ''),
                    'options' => [
                        'placeholder' => '--SELECCION FILIAL--',
                    ],
                ])
                ?>
            <?php else: ?>
                <?= $form->field($model, 'serviceCentreName')->textInput(['disabled' => true]); ?>
                <?= $form->field($model, 'IdServiceCentre')->hiddenInput([])->label(false); ?>
            <?php endif; ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'IdState')->dropDownList($model->getStates(), []) ?>
        </div>
        <?php if($model->validatePeriod()):?>
        <div class="col-md-3">
            <?=$form->field($model, 'IdPeriodValue')->dropDownList($model->getPeriodValues(),[]); ?>
        </div>
        <?php endif; ?>
    </div>
    <?= $form->field($model, 'IdMinistry')->hiddenInput()->label(false) ?>
</div>
<?php ActiveForm::end(); ?>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryperiods */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'id' => $formName
]); ?>
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'IdState')->dropDownList($model->getStates(),[]) ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, 'StartDate')->widget(DatePicker::className(), [
                'language' => 'es',
                'readonly' => TRUE,
                'options' => ['placeholder' => 'Fecha de Inicio...'],
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true,
                    'autoclose' => true,
                #'daysOfWeekDisabled' => [0],
                ],
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, 'EndDate')->widget(DatePicker::className(), [
                'language' => 'es',
                'readonly' => TRUE,
                'options' => ['placeholder' => 'Fecha del Fin...'],
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true,
                    'autoclose' => true,
                #'daysOfWeekDisabled' => [0],
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Description')->textarea([]) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

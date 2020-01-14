<?php
use kartik\widgets\ActiveForm;
use yii\web\JsExpression;
/*@var $model backend\models\Ministryvotingballot */

?>
<?php $form = ActiveForm::begin([
    'id' => $formName,
]);?>
<div class="panel-body">
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'rangeStart')->input('number',['id' => $tableName."-rangestart"]);?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'rangeEnd')->input('number',['id' => $tableName."-rangeend"]);?>
        </div>
    </div>
</div>
<?= $form->field($model, 'IdVoting')->hiddenInput()->label(false);?>
<?php ActiveForm::end(); ?>
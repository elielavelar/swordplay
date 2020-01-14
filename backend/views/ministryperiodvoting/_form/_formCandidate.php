<?php
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;

/*@var $model backend\models\Ministryperiodvotingcandidates */

?>
<?php $form = ActiveForm::begin([
    'id' => $formName,
]);?>
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'IdMember')->widget(Select2::class, [
                'size' => Select2::MEDIUM,
                'id' => $tableName."-idmember",
                #'initValueText' => ($model->IdMember ? $model->member->displayName: ''),
                'data' => $model->getMembers(),
                'options' => [
                    'placeholder' => '--SELECCIONE MIEMBRO--',
                ],
                'pluginOptions' => [
                     'allowClear' => true,
                ],
                'pluginEvents'=> [
                    'change'=> new JsExpression('function(){}'),
                ],

            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'Sort')->input('number',[]);?>
        </div>
    </div>
</div>
<?= $form->field($model, 'Id')->hiddenInput()->label(false);?>
<?= $form->field($model, 'IdVoting')->hiddenInput()->label(false);?>
<?php ActiveForm::end(); ?>
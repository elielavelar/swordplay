<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Catalogversions */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(); ?>
<div class="panel-body">
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'Version')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'IdState')->dropDownList($model->getStates(), []) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Description')->textarea(['rows' => 6]) ?>
        </div>
    </div>    

    <?= $form->field($model, 'IdCatalog')->hiddenInput()->label(FALSE)?>
</div>
<div class="panel-footer">
    <div class="row">
        <div class="col-md-12">
            <span class="pull-right">
                <?= Yii::$app->customFunctions->userCan('catalogCreate') ? Html::submitButton('<i class="fas fa-save"></i> Guardar', ['class' => 'btn btn-success']):'' ?>
                <?= Html::a('<i class="fas fa-times"></i> Cancelar',['catalog/update','id'=>$model->IdCatalog],['class'=>'btn btn-danger']);?>
            </span>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Type */
/* @var $form yii\widgets\ActiveForm */

$url = Url::to(['type/index']);
?>

<div class="panel-body">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'Name')->textInput(['maxlength' => true, 'class'=>'form-control']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'KeyWord')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'Code')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'Value')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'IdState')->dropDownList($model->getStates()) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Description')->textarea(['maxlength' => true]) ?>
        </div>
    </div>
</div>
<div class="panel-footer">
    <div class="row">
        <div class="col-md-12">
            <span class="pull-right">
                <?= Html::submitButton("<i class='fas fa-save'></i> Guardar", ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::button('<i class="fas fa-times"></i> Cancelar',['id'=>'btn-cancel','class'=>'btn btn-danger'])?>
            </span>
        </div>
    </div>
</div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = <<<JS
    $(document).ready(function(){
        $("#btn-cancel").on('click', function(){
            window.location = '$url';
        });
    });
JS;

$this->registerJs($script);
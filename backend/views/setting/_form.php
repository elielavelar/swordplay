<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Settings */
/* @var $modelDetail backend\models\Settingsdetail */
/* @var $form yii\widgets\ActiveForm */
$url = Url::to(['index']);
?>

<div class="panel-body">
    <?php $form = ActiveForm::begin(); ?>
        <div class="panel-body">
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
            </div>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'IdState')->dropDownList($model->getStates()) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'IdType')->dropDownList($model->getTypes()) ?>
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
                <div class="col-lg-12">
                    <div class="pull-right">
                        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        <?= Html::button('Cancelar',['id'=>'btn-cancel','class'=>'btn btn-danger'])?>
                    </div>
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

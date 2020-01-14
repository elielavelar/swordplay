<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\bootstrap\Tabs;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Crear Usuario';
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$parentName = StringHelper::basename($model->className());
$tableName = 'user';
$formName = $tableName.'-form';
?>
<div class="user-update">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
        </div>
        <?php $form = ActiveForm::begin([
            'id'=>$formName,
        ]); ?>
        <?= $this->render('_form', ['model' => $model,'form'=>$form]); ?>
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group pull-right">
                        <?= Html::submitButton('<i class="fas fa-save"></i> Guardar', ['class' => 'btn btn-success']) ?>
                        <?= Html::a('<i class="fas fa-times"></i> Cancelar', ['index'], ['class'=>'btn btn-danger'])?>
                    </div>
                </div>
                
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

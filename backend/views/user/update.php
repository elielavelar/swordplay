<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use kartik\form\ActiveForm;
use yii\helpers\StringHelper;
/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Actualizar Usuario: ' . $model->DisplayName;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->DisplayName, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';

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
        <?= Tabs::widget([
                'items' => [
                    [
                        'label' => 'General',
                        'content' => $this->render('_form', ['model' => $model,'form'=>$form]),
                        'active' => true
                    ],
                    [
                        'label' => 'Configuración ',
                        'content' => $this->render('_form/_formDetail',[
                            'model'=>$model, 'searchModel'=>$searchModel, 'modelDetail'=>$modelDetail
                            ]),
                        #'active' => true
                    ],
                ]]);
         ?>
        <div class="panel-footer">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group pull-right">
                        <?= Html::submitButton('<i class="fas fa-save"></i> Actualizar', ['class' => 'btn btn-success']) ?>
                        <?= Html::a('<i class="fas fa-times"></i> Cancelar', ['index'], ['class'=>'btn btn-danger'])?>
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

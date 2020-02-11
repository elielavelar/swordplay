<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\widgets\ActiveForm;
use common\models\Servicecentres;

/* @var $this yii\web\View */
/* @var $model common\models\Servicecentres */

$this->title = 'Actualizar Filial: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Filiales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="servicecentres-update">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
        </div>
        <?php $form = ActiveForm::begin(); ?>
        <?= Tabs::widget([
                'items' => [
                    [
                        'label' => 'General',
                        'content' => $this->render('_form', ['model' => $model,'form'=>$form]),
                        'active' => true
                    ],
                    [
                        'label' => 'Ministerios',
                        'content' => $this->render('_form/_detail',['model'=>$model, 'searchModel'=>$searchModel, 'dataProvider'=>$dataProvider,'modelDetail'=>$modelDetail,]),
                        #'visible' => in_array($model->type->Code, [Servicecentres::TYPE_DUISITE]),
                    ],
                ]]);
         ?>
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-12">
                    <span class="pull-right">
                        <?= Html::submitButton('<i class="fas fa-save"></i> Guardar', ['class' =>'btn btn-success']) ?>
                        <?= Html::a('<i class="fas fa-times"></i> Cancelar', ['index'] ,['class' => 'btn btn-danger']) ?>
                    </span>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>

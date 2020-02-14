<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model common\models\Extendedmodelkeys */

$this->title = 'Actualizar Llave: ' . $model->extendedModel->Name.":".$model->extendedModel->getModelAttributeLabel($model->AttributeKeyName).($model->AttributeKeyValue ? ':'.$model->AttributeKeyValue:'');
$this->params['breadcrumbs'][] = ['label' => 'Modelos Extendidos', 'url' => ['extendedmodel/index']];
$this->params['breadcrumbs'][] = ['label' => $model->extendedModel->Name, 'url' => ['extendedmodel/view','id' => $model->IdExtendedModel]];
$this->params['breadcrumbs'][] = 'Llaves';
$this->params['breadcrumbs'][] = ['label' => $model->extendedModel->getModelAttributeLabel($model->AttributeKeyName), 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="extendedmodels-update">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
        </div>
        <?= Tabs::widget([
            'items' => [
                [
                    'label' => 'General',
                    'content' => $this->render('_form', ['model' => $model]),
                    'active' => TRUE
                ],
                [
                    'label' => 'Campos',
                    'content' => $this->render('_form/_detail',[
                        'model'=>$model, 'searchModel'=>$searchModel, 'modelDetail'=>$modelDetail, 'dataProvider'=>$dataProvider,
                        ]),
                    #'visible' => $model->IdState ? (in_array($model->state->Code, [Catalogs::STATUS_ACTIVE])):false,
                    'active' => FALSE
                ],
            ]]);
     ?>
    </div>
</div>


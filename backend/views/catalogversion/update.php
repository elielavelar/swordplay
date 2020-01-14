<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use common\models\Catalogversions;

/* @var $this yii\web\View */
/* @var $model common\models\Catalogversions */

$this->title = 'Actualizar Versión Catálogo: ' . $model->catalog->Name." ".$model->Version;
$this->params['breadcrumbs'][] = ['label' => 'Catálogos', 'url' => ['catalog/index']];
$this->params['breadcrumbs'][] = ['label' => $model->catalog->Name, 'url' => ['catalog/update','id'=> $model->IdCatalog]];
$this->params['breadcrumbs'][] = 'Versiones';
$this->params['breadcrumbs'][] = ['label' => $model->Version, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Actualización';
?>
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
                    'label' => 'Detalles',
                    'content' => $this->render('_form/_detail',[
                        'model'=>$model, 'searchModel'=>$searchModel, 'modelDetail'=>$modelDetail, 'dataProvider'=>$dataProvider,
                        ]),
                    'active' => FALSE
                ],
            ]]);
     ?>

</div>

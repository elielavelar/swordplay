<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model common\models\Catalogdetails */

$this->title = 'Actualizar Detalle: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Catalogos', 'url' => ['catalog/index']];
$this->params['breadcrumbs'][] = ['label' => $model->catalogVersion->catalog->Name, 'url' => ['catalog/update','id'=>$model->catalogVersion->IdCatalog]];
$this->params['breadcrumbs'][] = 'Versiones';
$this->params['breadcrumbs'][] = ['label' => 'Version '.$model->catalogVersion->Version, 'url' => ['catalogversion/update','id'=>$model->IdCatalogVersion]];
$this->params['breadcrumbs'][] = 'Detalles de Catálogo';
$this->params['breadcrumbs'][] = $this->title;
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
            ]
        ]);
    ?>

</div>

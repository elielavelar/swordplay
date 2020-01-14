<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use common\models\Catalogs;
/* @var $this yii\web\View */
/* @var $model common\models\Catalogs */

$this->title = 'Actualizar Catálogo: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Catálogos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Actualizar';
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
                    'label' => 'Versiones',
                    'content' => $this->render('_form/_detail',[
                        'model'=>$model, 'searchModel'=>$searchModel, 'modelDetail'=>$modelDetail, 'dataProvider'=>$dataProvider,
                        ]),
                    'visible' => $model->IdState ? (in_array($model->state->Code, [Catalogs::STATUS_ACTIVE])):false,
                    'active' => FALSE
                ],
            ]]);
     ?>
</div>
<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model backend\models\Settings */
/* @var $modelDetail backend\models\Settingsdetail */
/* @var $searchDetail backend\models\SettingsdetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Actualizar Parámetro: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Configuración', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="settings-update">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
        </div>
        <?= Tabs::widget([
                'items' => [
                    [
                        'label' => 'General',
                        'content' => $this->render('_form', ['model' => $model]),
                        'active' => true
                    ],
                    [
                        'label' => 'Detalles',
                        'content' => $this->render('_form/_detail',['model'=>$model, 'searchModel'=>$searchDetail, 'dataProvider'=>$dataProvider,'modelDetail'=>$modelDetail,]),
                    ],
                ]]);
         ?>
    </div>
</div>

<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model common\models\Competitions */
/* @var $modelDetail common\models\Competitionrounds */
/* @var $searchModel backend\models\CompetitionroundsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Actualizar Competencia: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Competencias', 'url' => ['index']];
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
                    'active' => true
                ],
                [
                    'label' => 'Rondas',
                    'content' => $this->render('_form/_rounds',['model'=>$model, 'searchModel'=>$searchModel, 'dataProvider'=>$dataProvider,'modelDetail'=>$modelDetail,]),
                ],
            ]]);
     ?>
</div>

<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model common\models\Competitionrounds */
/* @var $modelDetail common\models\Roundquestions */
/* @var $searchModel \backend\models\RoundquestionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Actualizar Ronda: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Competencias', 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = ['label' => $model->competition->Name, 'url' => ['competition/view','id'=> $model->IdCompetition]];
$this->params['breadcrumbs'][] = 'Rondas';
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
                    'label' => 'Preguntas',
                    'content' => $this->render('_form/_detail',['model'=>$model, 'searchModel'=>$searchModel, 'dataProvider'=>$dataProvider,'modelDetail'=>$modelDetail,]),
                ],
            ]]);
     ?>
</div>


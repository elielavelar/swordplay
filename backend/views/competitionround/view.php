<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Competitionrounds */

$this->title = $model->Name;

$this->params['breadcrumbs'][] = ['label' => 'Competencias', 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = ['label' => $model->competition->Name, 'url' => ['competition/view','id'=> $model->IdCompetition]];
$this->params['breadcrumbs'][] = 'Rondas';
$this->params['breadcrumbs'][] = $this->title;;

?>
<div class="competitionrounds-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->Id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->Id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'Id',
            'Name',
            'IdCompetition',
            'IdType',
            'IdState',
            'Icon',
            'QuestionTime',
            'Description:ntext',
        ],
    ]) ?>

</div>

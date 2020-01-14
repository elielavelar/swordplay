<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MinistryperiodvotingcandidatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ministryperiodvotingcandidates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ministryperiodvotingcandidates-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Ministryperiodvotingcandidates', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'Id',
            'IdVoting',
            'IdMember',
            'IdState',
            'Sort',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

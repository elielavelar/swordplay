<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MinistryperiodsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ministryperiods';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ministryperiods-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Ministryperiods', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'Id',
            'IdMinistryServiceCentre',
            'IdType',
            'IdState',
            'StartDate',
            //'EndDate',
            //'Description:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

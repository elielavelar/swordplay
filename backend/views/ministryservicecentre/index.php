<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MinistryservicecentresSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ministryservicecentres';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ministryservicecentres-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Ministryservicecentres', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'Id',
            'IdServiceCentre',
            'IdMinistry',
            'IdState',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

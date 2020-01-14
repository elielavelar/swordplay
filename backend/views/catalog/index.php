<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\State;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use common\models\Catalogs;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CatalogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Catálogos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalogs-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Yii::$app->customFunctions->userCan('catalogCreate') ? Html::a('<i class="fas fa-plus-circle"></i> Crear Catálogo', ['create'], ['class' => 'btn btn-success']):"" ?>
    </p>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            #'Id',
                            'Name',
                            'KeyWord',
                            'Code',
                            [
                                'attribute' => 'IdState',
                                'filter' => ArrayHelper::map(State::findAll(['KeyWord' => StringHelper::basename(Catalogs::class)]), 'Id', 'Name'),
                                'content' => function($model){
                                    return $model->IdState ? $model->state->Name:'';
                                }
                            ],
                            //'Description:ntext',
                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

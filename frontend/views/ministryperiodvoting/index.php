<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MinistryperiodvotingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Votaciones';
$this->params['breadcrumbs'][] = $this->title;
$controller = Yii::$app->controller->id;
$create = Yii::$app->customFunctions->userCan($controller . 'Create');
$count = Yii::$app->customFunctions->userCan($controller . 'Count');
?>
<div class="ministryperiodvoting-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= $create ? Html::a('<i class="fas fa-plus-circle"></i> Agregar', ['create'], ['class' => 'btn btn-success']):'' ?>
    </p>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            #'Id',
                            [
                                'attribute' => 'IdMinistryPeriod',
                                'content' => function($model){
                                    return $model->IdMinistryPeriod ? ($model->ministryPeriod->IdMinistryServiceCentre ? $model->ministryPeriod->ministryServiceCentre->ministry->Name.' ':''). $model->ministryPeriod->Name:'';
                                },
                            ],
                            [
                                'attribute' => 'IdState',
                                'content' => function($model){
                                    return $model->IdState ? $model->state->Name : '';
                                },
                            ],
                            'ProcessDate',
                            'TotalVotingBallot',
                            //'Description:ntext',
                            ['class' => 'yii\grid\ActionColumn',
                                'template' => '{update} {count}',
                                'buttons' => [
                                    'count' => function ($url, $model) {
                                        #$urlDetail = \Yii::$app->getUrlManager()->createUrl(['settingsdetail/delete','id'=>$model->Id]);
                                        return Html::a('<span class="fas fa-chart-pie"></span>', $url, [
                                                    'title' => Yii::t('app', 'Ver Conteo'), 
                                        ]);
                                    },
                                ],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\Select2;
use backend\models\Ministryperiodvoting;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryperiodvoting */
/* @var $searchModel backend\models\MinistryperiodvotingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Votaciones';
$this->params['breadcrumbs'][] = $this->title;
$controller = Yii::$app->controller->id;
$create = Yii::$app->customFunctions->userCan($controller . 'Create');
$update = Yii::$app->customFunctions->userCan($controller . 'Update');
$delete = Yii::$app->customFunctions->userCan($controller . 'Delete');
$view = Yii::$app->customFunctions->userCan($controller . 'View');
$report = Yii::$app->customFunctions->userCan($controller . 'Report');

$filterStates = $model->getStates();
$filterPeriods = $model->getAllMinistryPeriods();

$template =  '';
$template .= $view ? '{view}&nbsp;&nbsp;':'';
$template .= $update ? '{update}&nbsp;&nbsp;':'';
$template .= $report ? '{report}&nbsp;&nbsp;':'';
$template .= $delete ? '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;{delete}&nbsp;&nbsp;':'';
?>
<div class="ministryperiodvoting-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= $create ? Html::a('<i class="fas fa-plus-circle"></i> Agregar', ['create'], ['class' => 'btn btn-success']) : '' ?>
    </p>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <?=GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'Id',
                            [
                                'attribute' => 'IdMinistryPeriod',
                                'filter' => $filterPeriods,
                                'filterType' => GridView::FILTER_SELECT2,
                                'filterWidgetOptions' => [
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ],
                                'content' => function($model) {
                                    return $model->IdMinistryPeriod ? $model->periodName : "";
                                },
                                'width' => '18%',
                                'contentOptions' => [
                                    'style' => 'font-size:12px',
                                ]
                            ],
                            [
                                'attribute' => 'IdState',
                                'filter' => $filterStates,
                                'content' => function($model) {
                                    return $model->IdState ? $model->state->Name : '';
                                },
                            ],
                            'ProcessDate',
                            'TotalVotingBallot',
                            //'Description:ntext',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => $template,
                                'buttons' => [
                                    'report' => function ($url, $model) {
                                        #$urlDetail = \Yii::$app->getUrlManager()->createUrl(['settingsdetail/delete','id'=>$model->Id]);
                                        return Html::a('<span class="fas fa-chart-pie"></span>', $url, [
                                                    'title' => Yii::t('app', 'Ver Conteo'), 
                                        ]);
                                    },
                                ],
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
    
</div>

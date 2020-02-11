<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\models\Ministryservicecentres;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Servicecentres */
/* @var $modelDetail backend\models\Ministryservicecentres */
/* @var $searchModel backend\models\MinistryservicecentresSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$filterState = $modelDetail->getStates();
$filterServicecentre = $modelDetail->getServicecentres();
$filterPeriodValues = $modelDetail->getPeriodValues();

$create = Yii::$app->customFunctions->userCan('ministryCreate');
$update = Yii::$app->customFunctions->userCan('ministryUpdate');
$delete = Yii::$app->customFunctions->userCan('ministryDelete');
$view = Yii::$app->customFunctions->userCan('ministryView');

$template = "";
$template .= $view ? " {view} " : "";
$template .= $update ? " {update} " : "";
$template .= $delete ? "&nbsp;|&nbsp;&nbsp;&nbsp; {delete} " : "";
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class='row'>
            <div class="col-md-12">
                <span class="pull-right">
                    <?= Html::a('<i class="fas fa-plus-circle"></i> Agregar', ['ministryservicecentre/create','id' => $model->Id], ['class' => 'btn btn-success']) ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'IdMinistry',
                            'content' => function($model){
                                return $model->IdMinistry ? $model->ministry->Name : null;
                            },
                            'filter' => $modelDetail->getMinistries(),
                        ],
                        [
                            'attribute' => 'IdPeriodValue',
                            'filter' => $filterPeriodValues,
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
                                return $model->IdPeriodValue ? $model->periodValueName : "";
                            },
                            'width' => '18%',
                            'contentOptions' => [
                                'style' => 'font-size:12px',
                            ]
                        ],
                        [
                            'attribute' => 'IdState',
                            'filter' => $filterState,
                            'content' => function($model) {
                                return $model->IdState ? $model->state->Name : '';
                            },
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => $template,
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    $urlDetail = \Yii::$app->getUrlManager()->createUrl(['ministryservicecentre/view','id'=>$model->Id]);
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $urlDetail, [
                                                'title' => Yii::t('app', 'lead-edit'),
                                    ]);
                                },
                                'update' => function ($url, $model) {
                                    $urlDetail = \Yii::$app->getUrlManager()->createUrl(['ministryservicecentre/update','id'=>$model->Id]);
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $urlDetail, [
                                                'title' => Yii::t('app', 'lead-edit'),
                                    ]);
                                },
                                'delete' => function ($url, $model) {
                                    #$urlDetail = \Yii::$app->getUrlManager()->createUrl(['settingsdetail/delete','id'=>$model->Id]);
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', "javascript:deleteService($model->Id);", [
                                                'title' => Yii::t('app', 'lead-delete'),
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
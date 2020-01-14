<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\models\Syslog;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SyslogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bitácora del Sistema';
$this->params['breadcrumbs'][] = $this->title;
$syslog = new Syslog();
$actionTypeFilter = $syslog->getActionTypes();
?>
<div class="syslog-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
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
                            #'LogKey',
                            [
                                'attribute'=>'IdTransactionModel',
                                'content' => function($model){
                                    return $model->IdTransactionModel ? $model->transactionModel->ModelName:'';
                                },
                            ],
                            
                            'Title',
                            [
                                'attribute'=>'IdRecord',
                                'label' => 'Id',
                                'headerOptions' => [
                                    'style'=> 'width: 6%'
                                ]
                            ],
                            [
                                'attribute' => 'ActionType',
                                'filter' => $actionTypeFilter,
                                'content' => function($model){
                                    return $model->ActionType ? $model->getActionType($model->ActionType):'';
                                }
                            ],
                            //'IdUser',
                            'EnvironmentName',
                            'ControllerName',
                            'ActionName',
                            'userName',
                            'CreationDate',
                            //'Description:ntext',

                            [
                                'template' => '{view}',
                                'class' => 'yii\grid\ActionColumn'
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

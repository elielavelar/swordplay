<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\models\Ministries;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministries */
/* @var $searchModel backend\models\MinistriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ministerios';
$this->params['breadcrumbs'][] = $this->title;

$filterState = $model->getStates();
$filterType = $model->getTypes();
$filterValidadtionType = $model->getValidityTypes();
$filterEnvironmentType = $model->getEnvironmentTypes();

$create = Yii::$app->customFunctions->userCan('ministryCreate');
$update = Yii::$app->customFunctions->userCan('ministryUpdate');
$delete = Yii::$app->customFunctions->userCan('ministryDelete');
$view = Yii::$app->customFunctions->userCan('ministryView');

$template = "";
$template .= $view ? " {view} ": "";
$template .= $update ? " {update} ": "";
$template .= $delete ? "&nbsp;|&nbsp;&nbsp;&nbsp; {delete} ": "";

?>
<div class="ministries-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<i class="fas fa-plus-circle"></i> Agregar', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'Id',
                                'width' => '5%',
                            ],
                            'Name',
                            [
                                'attribute' => 'Code',
                                'width' => '12%',
                            ],
                            [
                                'attribute' => 'IdType',
                                'filter' => $filterType,
                                'content' => function($model){
                                    return $model->IdType ? $model->type->Name : ''; 
                                },
                            ],
                            [
                                'attribute' => 'IdValidityType',
                                'filter' => $filterValidadtionType,
                                'content' => function($model){
                                    return $model->IdValidityType ? $model->validityType->Name : ''; 
                                },
                            ],
                            [
                                'attribute' => 'IdEnvironmentType',
                                'filter' => $filterEnvironmentType,
                                'content' => function($model){
                                    return $model->IdEnvironmentType ? $model->environmentType->Name : ''; 
                                },
                            ],
                            [
                                'attribute' => 'IdState',
                                'filter' => $filterState,
                                'content' => function($model){
                                    return $model->IdState ? $model->state->Name : '';
                                },
                            ],
                            //'Description:ntext',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => $template,
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>

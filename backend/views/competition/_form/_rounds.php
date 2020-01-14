<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Type;
use common\models\State;
use common\models\Competitionrounds;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Competitions */
/* @var $modelDetail common\models\Competitionrounds */
/* @var $searchModel backend\models\CompetitionroundsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rondas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-lg-12">
                    <span class="pull-left">
                        <h4 class="panel-title">Rondas</h4>
                    </span>
                    <span class="pull-right">
                        <?= Html::a('Agregar', ['create'], ['class' => 'btn btn-success']) ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'Sort',
                            'Name',
                            [
                                'attribute' => 'IdType',
                                'filter' => $modelDetail->getTypes(),
                                'content' => function($model) {
                                    return $model->IdType ? $model->type->Name : '';
                                },
                            ],
                            [
                                'attribute' => 'IdState',
                                'filter' => $modelDetail->getStates(),
                                'content' => function($model) {
                                    return $model->IdState ? $model->state->Name : '';
                                },
                            ],
                            //'Icon',
                            //'Description:ntext',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{edit} {delete}',
                                'buttons' => [
                                    'edit' => function ($url, $model) {
                                        $urlDetail = \Yii::$app->getUrlManager()->createUrl(['competitionround/update','id'=>$model->Id]);
                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $urlDetail, [
                                                    'title' => Yii::t('app', 'lead-edit'),
                                        ]);
                                    }
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

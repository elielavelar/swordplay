<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Competitionrounds */
/* @var $searchModel backend\models\RoundquestionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Preguntas de Ronda ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <span class="pull-right">
                    <?= Html::a('<i class="fas fa-plus-circle"></i> Agregar', ['roundquestion/create', 'id' => $model->Id], ['class' => 'btn btn-success']) ?>
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
                        'Sort',
                        'Name',
                        [
                            'attribute'=>'IdType',
                            'content' => function($model){
                                return $model->IdState ? $model->state->Name:'';
                            },
                        ],
                        'IdState',
                        //'QuoteReference',
                        //'Description:ntext',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {update}&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;{delete}'
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>

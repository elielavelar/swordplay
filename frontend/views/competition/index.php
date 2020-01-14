<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\State;
use common\models\Competitions;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\FileInput;
use kartik\form\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CompetitionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Competencias Bíblicas';
$this->params['breadcrumbs'][] = $this->title;

$stateFilter = ArrayHelper::map(State::findAll(['KeyWord'=> StringHelper::basename(Competitions::class)]),'Id','Name');
?>
<div class="competitions-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'Id',
                            'Name',
                            'BookName',
                            'NumberRounds',
                            'Description:ntext',
                            [
                                'attribute' => 'IdState',
                                'filter' => $stateFilter,
                                'content' => function($model){
                                    return $model->IdState ? $model->state->Name:'';
                                },
                            ],

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template'  => '{view}',
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

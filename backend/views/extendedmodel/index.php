<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Extendedmodels */
/* @var $searchModel backend\models\ExtendedmodelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Modelos Extendidos';
$this->params['breadcrumbs'][] = 'Configuraciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extendedmodels-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

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
                            'Name',
                            'KeyWord',
                            [
                                'attribute' => 'IdNameSpace',
                                'content' => function($model){
                                    return $model->IdNameSpace ? $model->nameSpace->Name : '';
                                },
                                'filter' => $model->getNameSpaces(),
                            ],
                            [
                                'attribute' => 'IdState',
                                'content' => function($model){
                                    return $model->IdState ? $model->state->Name : '';
                                },
                                'filter' => $model->getStates(),
                            ],
                            //'Description:ntext',
                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

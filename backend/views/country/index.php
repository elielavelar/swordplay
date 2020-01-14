<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\State;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CountriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Países';
$this->params['breadcrumbs'][] = $this->title;

$filterState= ArrayHelper::map(State::findAll(['KeyWord'=>'Countries']), 'Id', 'Name');
?>
<div class="countries-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php #echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Yii::$app->customFunctions->userCan('countryCreate') ? Html::a('<i class="fas fa-plus-circle"></i> Agregar', ['create'], ['class' => 'btn btn-success']):"" ?>
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

                            'Id',
                            'Name',
                            'Code',
                            [
                                'attribute'=>'IdState',
                                'filter'=> Html::activeDropDownList($searchModel, 'IdState', $filterState, ['class'=>'form-control','prompt'=>'--']),
                                'content'=>function($data){
                                    return $data->IdState != 0 ? $data->state->Name:NULL;
                                },
                                'enableSorting'=>TRUE  
                            ],

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view} {update} {delete} {cancel}',
                                'buttons'=>[
                                    'view'=>function ($url, $model) {
                                        return $model->view ? "<a href='$url' title='Ver País'>"
                                                . "<span class='glyphicon glyphicon-eye-open'></span></a>":"";
                                    },
                                    'update'=>function ($url, $model) {
                                        return $model->update ? "<a href='$url' title='Editar País'>"
                                                . "<span class='glyphicon glyphicon-pencil'></span></a>":"";
                                    },
                                    'delete'=>function ($url, $model) {
                                        return $model->delete ? (Html::a("<span class='glyphicon glyphicon-trash'></span>",$url,[
                                            'data' => [
                                                'confirm' => 'Está seguro que desea Eliminar este Registro?',
                                                'method' => 'post',
                                            ],
                                        ])):"";
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

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\State;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$modelName = $searchModel->tableName();
$this->title = 'Tipos';
$this->params['breadcrumbs'][] = $this->title;

$filterState= ArrayHelper::map(State::findAll(['KeyWord'=>'Type']), 'Id', 'Name');
?>
<div class="type-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Yii::$app->customFunctions->userCan($modelName.'Create') ? Html::a('<i class="fas fa-plus-circle"></i> Crear Tipo', ['create'], ['class' => 'btn btn-success']):"" ?>
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
                            'KeyWord',
                            'Name',
                            'Code',
                            'Value',
                            [
                                'attribute'=>'IdState',
                                'filter'=> Html::activeDropDownList($searchModel, 'IdState', $filterState, ['class'=>'form-control','prompt'=>'--']),
                                'content'=>function($data){
                                    return $data->IdState != 0 ? $data->state->Name:NULL;
                                },
                                'enableSorting'=>TRUE  
                            ],
                            // 'Description',

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view} {update} {delete}', // {cancel}
                                'buttons'=>[
                                    'view'=>function ($url, $model) {
                                        return $model->view ? "<a href='$url' title='Ver Cita'>"
                                                . "<span class='glyphicon glyphicon-eye-open'></span></a>":"";
                                    },
                                    'update'=>function ($url, $model) {
                                        return $model->update ? "<a href='$url' title='Actualizar Tipo'>"
                                                . "<span class='glyphicon glyphicon-pencil'></span></a>":"";
                                    },
                                    'delete'=>function ($url, $model) {
                                        return $model->delete ? "<a href='$url' title='Eliminar Tipo'>"
                                                . "<span class='glyphicon glyphicon-trash'></span></a>":"";
                                    },
                //                    'cancel'=>function ($url, $model) {
                //                        return $model->cancel ? "<a href='javascript:cancel($model->Id);' title='Cancelar Cita'>"
                //                                . "<span class='glyphicon glyphicon-remove'></span></a>":"";
                //                    },
                                ],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

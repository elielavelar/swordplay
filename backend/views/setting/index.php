<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\State;
use common\models\Type;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\SettingsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Configuración';
$this->params['breadcrumbs'][] = $this->title;
$filterState= ArrayHelper::map(State::findAll(['KeyWord'=>'Settings']), 'Id', 'Name');
$filterType= ArrayHelper::map(Type::findAll(['KeyWord'=>'Settings']), 'Id', 'Name');
?>
<div class="settings-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('<i class="fas fa-plus-circle"></i> Crear Parámetro', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <?php Pjax::begin(); ?>    <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'Id',
                                'Name',
                                'KeyWord',
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
                                    'attribute'=>'IdType',
                                    'filter'=> Html::activeDropDownList($searchModel, 'IdType', $filterType, ['class'=>'form-control','prompt'=>'--']),
                                    'content'=>function($data){
                                        return $data->IdType != 0 ? $data->type->Name:NULL;
                                    },
                                    'enableSorting'=>TRUE  
                                ],
                                // 'IdType',
                                // 'Description:ntext',

                                ['class' => 'yii\grid\ActionColumn'],
                            ],
                        ]); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

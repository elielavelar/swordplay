<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use common\models\State;
use common\models\Type;
use common\models\Zones;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ServicecentresSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Filiales';
$this->params['breadcrumbs'][] = 'Administración';
$this->params['breadcrumbs'][] = 'Catálogos';
$this->params['breadcrumbs'][] = $this->title;
$filterState= ArrayHelper::map(State::findAll(['KeyWord'=>'Servicecentres']), 'Id', 'Name');
$filterType= ArrayHelper::map(Type::findAll(['KeyWord'=>'Servicecentres']), 'Id', 'Name');
$filterZone= ArrayHelper::map(Zones::find()->joinWith('state b',true)->andWhere(['b.Code'=>  Zones::STATUS_ACTIVE])->all(), 'Id', 'Name');
?>
<div class="servicecentres-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= (Yii::$app->customFunctions->userCan('servicecentreCreate') ? Html::a('<i class="fas fa-plus-circle"></i> Agregar', ['create'], ['class' => 'btn btn-success']):""); ?>
    </p>
    <div class="panel panel-default">
        <div class="panel-body">
            <?php Pjax::begin(); ?>    <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    #'Id',
                    'Name',
                    [
                        'attribute'=>'IdCountry',
                        'filter'=> Html::activeTextInput($searchModel,'IdCountry', ['class'=>'form-control']),
                        'content'=>function($data){
                            return $data->IdCountry != 0 ? $data->country->Name:NULL;
                        },
                        'enableSorting'=>TRUE  
                    ],
                    [
                        'attribute'=>'IdZone',
                        'filter'=> Html::activeDropDownList($searchModel, 'IdZone', $filterZone, ['class'=>'form-control','prompt'=>'--']),
                        'content'=>function($data){
                            return $data->IdZone != 0 ? $data->zone->Name:NULL;
                        },
                        'enableSorting'=>TRUE  
                    ],
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
                    // 'Address:ntext',
                    // 'Phone',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        <?php Pjax::end(); ?></div>
        </div>
    </div>

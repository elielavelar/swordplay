<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\State;
use common\models\Profile;
use common\models\Servicecentres;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Yii::$app->customFunctions->userCan('userCreate') ? Html::a('<i class="fas fa-plus-circle"></i> Crear Usuario', ['create'], ['class' => 'btn btn-success']):"" ; ?>
    </p>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            #['class' => 'yii\grid\SerialColumn'],

                            //'id',
                            [
                                'attribute' => 'Username',
                                'label' => 'Usuario',
                                'headerOptions' => ['style' => 'width:8%'],
                            ],
                            [
                                'attribute'=>'DisplayName',
                                'headerOptions' => ['style' => 'width:15%'],
                            ],
                            [
                               'attribute' =>'CodEmployee',
                                'headerOptions' => ['style' => 'width:7%'],
                            ],
                             'Email:email',
                            [
                                'attribute'=>'IdProfile',
                                'filter'=> ArrayHelper::map(Profile::find()
                                        ->joinWith('state b', true)
                                        ->where(['b.Code'=>'ACT'])
                                        ->all(),'Id','Name'),
                                'content'=>function($model){
                                    return $model->IdProfile ? $model->profile->Name:"";
                                },
                                'headerOptions' => ['style' => 'width:12%'],
                            ],
                            [
                                'attribute'=>'IdServiceCentre',
                                'filter'=> ArrayHelper::map(Servicecentres::find()->select(['Id','Name'])->all(),'Id','Name'),
                                'content'=>function($model){
                                    return $model->IdServiceCentre ? $model->serviceCentre->Name:"";
                                },
                            ],
                            [
                                'attribute'=>'IdState',
                                'filter'=> ArrayHelper::map(State::find()->where(['KeyWord'=>'User'])->select(['Id','Name'])->all(),'Id','Name'),
                                'content'=>function($model){
                                    return $model->IdState ? $model->state->Name:"";
                                },
                                'headerOptions' => ['style' => 'width:8%'],
                            ],
                            // 'created_at',
                            // 'updated_at',

                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

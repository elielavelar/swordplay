<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\States;
use common\models\Profiles;
#use common\models\Servicecentres;

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
        <?= ""; #Yii::$app->customFunctions->userCan('userCreate') ? Html::a('Crear', ['create'], ['class' => 'btn btn-success']):"" ; ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'Username',
            'completeName',
            //'AuthKey',
            //'PasswordHash',
            //'PasswordResetToken',
             'Email:email',
            [
                'attribute'=>'IdProfile',
                'filter'=> ArrayHelper::map(Profiles::find()
                        ->joinWith('state b', true)
                        ->where(['b.Code'=>'ACT'])
                        ->all(),'Id','Name'),
                'content'=>function($model){
                    return $model->IdProfile ? $model->profile->Name:"";
                },
            ],
            /*            
            [
                'attribute'=>'IdServiceCentre',
                'filter'=> ArrayHelper::map(Servicecentres::find()->select(['Id','Name'])->all(),'Id','Name'),
                'content'=>function($model){
                    return $model->IdServiceCentre ? $model->idServiceCentre->Name:"";
                },
            ],
             * 
             */
            [
                'attribute'=>'IdState',
                'filter'=> ArrayHelper::map(States::find()->where(['KeyWord'=>'User'])->select(['Id','Name'])->all(),'Id','Name'),
                'content'=>function($model){
                    return $model->IdState ? $model->state->Name:"";
                },
            ],
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

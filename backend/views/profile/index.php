<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use common\models\State;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Perfiles';
$this->params['breadcrumbs'][] = $this->title;

/*
$template = '';
$template .= Yii::$app->customFunctions->userCan('profileView')  ? '{view} ' : '';
$template .= Yii::$app->customFunctions->userCan('profileUpdate')  ? ' {update} ' : '';
$template .= Yii::$app->customFunctions->userCan('profileDelete')  ? ' | <span style="margin-left:10px"> {delete} </span>' : '';
 * 
 */
$template = '{view} {update}';

?>
<div class="profile-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('<i class="fa fa-plus-circle"></i> Crear', ['create'], ['class' => 'btn btn-success']) ?>
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
                            #'Id',
                            'Name',
                            'KeyWord',
                            'Code',
                            'Description',
                            [
                                'attribute'=>'IdState',
                                'filter'=> ArrayHelper::map(State::find()->where(['KeyWord'=>'Profile'])->select(['Id','Name'])->all(),'Id','Name'),
                                'content'=>function($model){
                                    return $model->IdState ? $model->state->Name:"";
                                },
                            ],

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => $template, // {cancel}
                                'buttons'=>[
                                    'view'=>function ($url, $model) {
                                        return "<a href='$url' title='Ver Perfil'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                    },
                                    'update'=>function ($url, $model) {
                                        return "<a href='$url' title='Actualizar Perfil'><span class='glyphicon glyphicon-pencil'></span></a>";
                                    }
                                ],
                            ],
                        ],
                    ]); ?>
                <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

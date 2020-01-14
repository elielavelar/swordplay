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

    <p>
        <?= Html::a('<i class="fas fa-plus-circle"></i> Crear', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <?php Modal::begin([
                            'header'=>'<h4>Cargar Competencia desde Archivo</h4>',
                            'headerOptions'=>[
                                'class'=>'bg-blue',
                            ],
                            'toggleButton'=>[
                                'label'=>'<i class="fa fa-upload"></i> Crear desde Archivo','class'=>'btn btn-default',
                            ],
                        ]);
                        $form = ActiveForm::begin([
                            'options'=>[
                                'enctype'=>'multipart/form-data',
                            ],
                        ]);
                        echo $form->field($model, 'uploadFile')->widget(FileInput::className(), [
                            'pluginOptions'=>[
                                'uploadUrl'=> Url::to(['competition/upload']),
                                'showPreview' => false,
                            ],
                        ]);
                                
                        ActiveForm::end();
                        Modal::end();
                        ?>
                    </div>
                </div>
            </div>
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

                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

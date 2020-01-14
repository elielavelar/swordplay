<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\StateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Estados';
$this->params['breadcrumbs'][] = $this->title;
$controller = Yii::$app->controller->id;
?>
<div class="state-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= (Yii::$app->user->can($controller.'Create') ?  Html::a('<i class="fas fa-plus-circle"></i> Crear Estado', ['create'], ['class' => 'btn btn-success']):NULL) ?>
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
                            // 'Description',

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view} {update} {delete}',
                                'buttons'=>[
                                    'view'=>function ($url, $model) {
                                        return $model->view ? "<a href='$url';'><span class='glyphicon glyphicon-eye-open'></span></a>":"";
                                    },
                                    'update'=>function ($url, $model) {
                                        return $model->update ? "<a href='$url';'><span class='glyphicon glyphicon-pencil'></span></a>":"";
                                    },
                                    'delete'=>function ($url, $model) {
                                        return $model->delete ? "<a href='$url';'><span class='glyphicon glyphicon-trash'></span></a>":"";
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

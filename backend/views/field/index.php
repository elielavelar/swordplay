<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model \common\models\Fields */
/* @var $searchModel backend\models\FieldSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Campos';
$this->params['breadcrumbs'][] = $this->title;

$controller = Yii::$app->controller->id;
$create = Yii::$app->customFunctions->userCan($controller.'Create');

$filterState = $model->getStates();
$filterType = $model->getTypes();
?>
<div class="fields-index">

    <h1><?= Html::encode($this->title) ?></h1>
<?php // echo $this->render('_search', ['model' => $searchModel]);  ?>
    <p>
<?= $create ? Html::a('<i class="fas fa-plus-circle"></i> Agregar', ['create'], ['class' => 'btn btn-success']): ''; ?>
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
                            'Id',
                            'Name',
                            'KeyWord',
                            'Code',
                            [
                                'attribute' => 'IdType',
                                'filter' => $filterType,
                                'content' => function($model){
                                    return $model->IdType ? $model->type->Name : '';
                                }
                            ],
                            [
                                'attribute' => 'IdState',
                                'filter' => $filterState,
                                'content' => function($model){
                                    return $model->IdState ? $model->state->Name : '';
                                }
                            ],
                            //'HasCatalog',
                            //'Value',
                            //'MultipleValue',
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

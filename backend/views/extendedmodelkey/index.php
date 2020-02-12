<?php
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ExtendedmodelkeySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Extendedmodelkeys';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extendedmodelkeys-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<i class=\"fas fa-plus-circle\"></i> Agregar Extendedmodelkeys', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'Id',
            'IdExtendedModel',
            'AttributeKeyName',
            'AttributeKeyValue',
            'IdState',
            //'Description:ntext',

                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

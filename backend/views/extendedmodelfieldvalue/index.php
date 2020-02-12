<?php
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ExtendedmodelfieldvaluesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Extendedmodelfieldvalues';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extendedmodelfieldvalues-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<i class=\"fas fa-plus-circle\"></i> Agregar Extendedmodelfieldvalues', ['create'], ['class' => 'btn btn-success']) ?>
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
            'IdExtendedModelRecord',
            'IdExtendedModelField',
            'Value:ntext',
            'IdFieldCatalog',
            //'CustomValue',
            //'Description:ntext',

                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

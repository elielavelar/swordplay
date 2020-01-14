<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\Member;

/* @var $this yii\web\View */
/* @var $model common\models\Member */
/* @var $searchModel backend\models\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Miembros';
$this->params['breadcrumbs'][] = $this->title;

$filterStates = $model->getStates();
$filterGender = $model->getGenders();
?>
<div class="member-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<i class="fas fa-plus-circle"></i> Agregar', ['create'], ['class' => 'btn btn-success']) ?>
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
                            #'Id',
                            [
                                'attribute' => 'Code',
                                'headerOptions'=> [
                                    'style'=> 'width:10%'
                                ],
                            ],
                            'FirstName',
                            'FirstLastName',
                            [
                                'attribute' => 'Gender',
                                'content' => function($model){
                                    return $model->Gender ? $model->Gender == Member::GENDER_FEMALE ? 'Femenino' : 'Masculino' : '';
                                },
                                'filter' => $filterGender,
                            ],
                            [
                                'attribute'=> 'IdServiceCentre',
                                'content' => function($model){
                                    return $model->IdServiceCentre ? $model->serviceCentre->Name : '';
                                },
                            ],
                            //'Code',
                            //'IdState',
                            //'BirthDate',
                            //'ConversionDate',
                            //'BaptismDate',
                            //'DeceaseDate',
                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>

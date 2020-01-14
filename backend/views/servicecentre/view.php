<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Servicecentres */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Filiales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicecentres-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->Id], ['class' => 'btn btn-primary']) ?>
    </p>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'Id',
                            'Name',
                            'MBCode',
                            [
                                'attribute'=>'IdCountry',
                                'value'=> $model->IdCountry ? $model->country->Name:NULL,
                            ],
                            [
                                'attribute'=>'IdState',
                                'value'=> $model->IdState ? $model->state->Name:NULL,
                            ],
                            [
                                'attribute'=>'IdType',
                                'value'=> $model->IdType ? $model->type->Name:NULL,
                            ],
                            'Address:ntext',
                            'Phone',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <?= Html::a('Cancelar', ['index'], ['class'=>'btn btn-danger']); ?>
</div>

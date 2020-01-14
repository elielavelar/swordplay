<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministries */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Ministerios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ministries-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="fas fa-edit"></i> Actualizar', ['update', 'id' => $model->Id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('<i class="fas fa-times"></i> Eliminar', ['delete', 'id' => $model->Id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Realmente desea Eliminar este Registro?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <?=
                    DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'Id',
                            'Name',
                            'Code',
                            [
                                'attribute' => 'IdType',
                                'value' => $model->IdType ? $model->type->Name : '',
                            ],
                            [
                                'attribute' => 'IdValidityType',
                                'value' => $model->IdValidityType ? $model->validityType->Name : '',
                            ],
                            [
                                'attribute' => 'IdPeriodType',
                                'value' => $model->IdPeriodType ? $model->periodType->Name : '',
                            ],
                            [
                                'attribute' => 'IdState',
                                'value' => $model->IdState ? $model->state->Name : '',
                            ],
                            'Description:ntext',
                        ],
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?= Html::a('<i class="fas fa-arrow-circle-left"></i> Cancelar', ['index'],['class' => 'btn btn-danger'])?>
</div>

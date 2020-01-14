<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\State */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Estados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="state-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="fas fa-edit"></i> Actualizar', ['update', 'id' => $model->Id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fas fa-times"></i> Eliminar', ['delete', 'id' => $model->Id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Está seguro que desea Eliminar este registro?',
                'method' => 'post',
            ],
        ]) ?>
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
                            'KeyWord',
                            'Code',
                            'Value',
                            'Description',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <?= Html::a('<i class="fas fa-arrow-circle-left"></i> Cancelar', ['index'], ['class'=>'btn btn-danger']);?>
</div>

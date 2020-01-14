<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Settings */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Configuración', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->Id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->Id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'Id',
            'Name',
            'KeyWord',
            'Code',
            [
                'attribute'=>'IdState',
                'value'=> $model->IdState ? $model->state->Name:NULL,
            ],
            [
                'attribute'=>'IdType',
                'value'=> $model->IdType != 0 ? $model->type->Name:NULL,
            ],
            'Description:ntext',
        ],
    ]) ?>
    <?= Html::a('Cancelar', ['index'], ['class'=>'btn btn-danger']);?>
</div>

<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryservicecentres */

$this->title = 'Ministerio de Filial: ' . $model->ministry->Name;
$this->params['breadcrumbs'][] = ['label' => 'Ministerios', 'url' => ['ministry/index']];
$this->params['breadcrumbs'][] = ['label' => $model->ministry->Name, 'url' => ['ministry/view','id' => $model->IdMinistry]];
$this->params['breadcrumbs'][] = 'Ministerio de Filiales';
$this->params['breadcrumbs'][] = $model->serviceCentreName;

\yii\web\YiiAsset::register($this);
?>
<div class="ministryservicecentres-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="fas fa-edit"></i> Actualizar', ['update', 'id' => $model->Id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fas fa-times"></i> Eliminar', ['delete', 'id' => $model->Id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Está seguro que desea Eliimnar este Registro?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'Id',
            [
                'attribute' => 'IdServiceCentre',
                'value' => $model->IdServiceCentre ? $model->serviceCentreName : '',
            ],
            [
                'attribute' => 'IdMinistry',
                'value' => $model->IdMinistry ? $model->ministry->Name : '',
            ],
            [
                'attribute' => 'IdPeriodValue',
                'value' => $model->IdPeriodValue ? $model->periodValue->IdCatalogDetail ? $model->periodValue->catalogDetail->Name : '' : '',
            ],
            [
                'attribute' => 'IdState',
                'value' => $model->IdState ? $model->state->Name : '',
            ],
        ],
    ]) ?>
<?= Html::a('<i class="fas fa-arrow-circle-left"></i> Cancelar',['ministry/view','id' => $model->IdMinistry],['class'=>'btn btn-danger']);?>
</div>

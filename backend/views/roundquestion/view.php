<?php

use kartik\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Roundquestions */

$this->title = "Pregunta ".$model->Sort;
$this->params['breadcrumbs'][] = ['label' => 'Competencias Bíblicas', 'url' => ['competition/index']];
$this->params['breadcrumbs'][] = ['label' => $model->round->competition->Name, 'url' => ['competition/update','id' => $model->round->IdCompetition]];
$this->params['breadcrumbs'][] = 'Rondas';
$this->params['breadcrumbs'][] = ['label' => $model->round->Name, 'url' => ['competitionround/update','id' => $model->IdRound]];
$this->params['breadcrumbs'][] = 'Preguntas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="roundquestions-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="fas fa-edit"></i> Actualizar', ['update', 'id' => $model->Id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fas fa-times"></i> Eliminar', ['delete', 'id' => $model->Id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Está seguro que desea eliminar este Registro?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'Id',
            'Name',
            [ 
                'attribute'=> 'IdType',
                'value' => $model->IdType ? $model->type->Name : '',
            ],
            [
                'attribute' => 'IdState',
                'value' => $model->IdState ? $model->state->Name : '',
            ],
            'Sort',
            'QuoteReference',
            'Description:ntext',
        ],
    ]) ?>

</div>

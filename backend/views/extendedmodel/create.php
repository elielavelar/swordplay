<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Extendedmodels */

$this->title = 'Agregar Modelo Extendido';
$this->params['breadcrumbs'][] = 'Configuraciones';
$this->params['breadcrumbs'][] = ['label' => 'Modelos Extendidos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extendedmodels-create">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

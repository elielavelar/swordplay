<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Extendedmodels */

$this->title = 'Actualizar Extendedmodels: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Extendedmodels', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="extendedmodels-update">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Extendedmodelkeys */

$this->title = 'Actualizar Extendedmodelkeys: ' . $model->Id;
$this->params['breadcrumbs'][] = ['label' => 'Extendedmodelkeys', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Id, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="extendedmodelkeys-update">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

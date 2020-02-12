<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Extendedmodelkeys */

$this->title = 'Agregar Extendedmodelkeys';
$this->params['breadcrumbs'][] = ['label' => 'Extendedmodelkeys', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extendedmodelkeys-create">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

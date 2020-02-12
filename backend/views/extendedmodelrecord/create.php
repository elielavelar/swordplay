<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Extendedmodelrecords */

$this->title = 'Agregar Extendedmodelrecords';
$this->params['breadcrumbs'][] = ['label' => 'Extendedmodelrecords', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extendedmodelrecords-create">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

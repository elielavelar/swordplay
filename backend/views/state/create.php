<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\State */

$this->title = 'Crear Estado';
$this->params['breadcrumbs'][] = ['label' => 'Estados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

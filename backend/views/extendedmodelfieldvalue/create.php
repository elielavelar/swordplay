<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Extendedmodelfieldvalues */

$this->title = 'Agregar Extendedmodelfieldvalues';
$this->params['breadcrumbs'][] = ['label' => 'Extendedmodelfieldvalues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extendedmodelfieldvalues-create">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

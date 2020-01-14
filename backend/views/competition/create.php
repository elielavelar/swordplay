<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Competitions */

$this->title = 'Create Competitions';
$this->params['breadcrumbs'][] = ['label' => 'Competitions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competitions-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

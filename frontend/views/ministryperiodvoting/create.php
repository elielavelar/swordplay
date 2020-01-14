<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryperiodvoting */

$this->title = 'Create Ministryperiodvoting';
$this->params['breadcrumbs'][] = ['label' => 'Ministryperiodvotings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ministryperiodvoting-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

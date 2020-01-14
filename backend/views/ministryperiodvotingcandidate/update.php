<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryperiodvotingcandidates */

$this->title = 'Update Ministryperiodvotingcandidates: ' . $model->Id;
$this->params['breadcrumbs'][] = ['label' => 'Ministryperiodvotingcandidates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Id, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ministryperiodvotingcandidates-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

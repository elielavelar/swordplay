<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryperiodvotingcandidates */

$this->title = 'Create Ministryperiodvotingcandidates';
$this->params['breadcrumbs'][] = ['label' => 'Ministryperiodvotingcandidates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ministryperiodvotingcandidates-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

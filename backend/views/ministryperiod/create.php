<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryperiods */

$this->title = 'Create Ministryperiods';
$this->params['breadcrumbs'][] = ['label' => 'Ministryperiods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ministryperiods-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

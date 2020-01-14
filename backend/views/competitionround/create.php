<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Competitionrounds */

$this->title = 'Create Competitionrounds';
$this->params['breadcrumbs'][] = ['label' => 'Competitionrounds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competitionrounds-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Questionanswers */

$this->title = 'Create Questionanswers';
$this->params['breadcrumbs'][] = ['label' => 'Questionanswers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="questionanswers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

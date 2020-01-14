<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Roundquestions */

$this->title = 'Create Roundquestions';
$this->params['breadcrumbs'][] = ['label' => 'Roundquestions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="roundquestions-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

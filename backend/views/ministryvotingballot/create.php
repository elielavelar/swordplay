<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryvotingballot */

$this->title = 'Create Ministryvotingballot';
$this->params['breadcrumbs'][] = ['label' => 'Ministryvotingballots', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ministryvotingballot-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

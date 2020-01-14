<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Countries */

$this->title = 'Actualizar País: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Países', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="countries-update">

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
        </div>
        <?php $form = ActiveForm::begin(); ?>

        <?= $this->render('_form', [
            'model' => $model,'form'=>$form
        ]) ?>
        <?php ActiveForm::end(); ?>
    </div>

</div>

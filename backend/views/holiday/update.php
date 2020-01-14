<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Holidays */
/* @var $modelDetails \common\models\Servicecentres */

$this->title = 'Actualizar Día Feriado: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Días Feriados', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
    </div>
    <?php $form = ActiveForm::begin(); ?>
    <?= Tabs::widget([
            'items' => [
                [
                    'label' => 'Datos',
                    'content' => $this->render('_form', ['model' => $model,'form'=>$form]),
                    'active' => true
                ],
                [
                    'label' => 'Duicentros',
                    'content' => $this->render('_form/_details',[
                        'model'=>$model, 'modelDetails'=>$modelDetails, 'form'=>$form
                        ]),
                    'visible' => $model->details,
                ],
            ]]);
     ?>
    <?php ActiveForm::end(); ?>
</div>


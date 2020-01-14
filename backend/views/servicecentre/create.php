<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model common\models\Servicecentres */

$this->title = 'Crear Filial';
$this->params['breadcrumbs'][] = ['label' => 'Filiales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicecentres-create">

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
        </div>
        <?php $form = ActiveForm::begin(); ?>
        <?= $this->render('_form', [
            'model' => $model, 'form'=>$form,
        ]) ?>
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-12">
                    <span class="pull-right">
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                        <?= Html::a('Cancelar', ['index'] ,['class' => 'btn btn-danger']) ?>
                    </span>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>

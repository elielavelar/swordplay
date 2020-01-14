<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryvotingballot */
/* @var $form yii\widgets\ActiveForm */

$options = ['class' => 'form-control input-lg'];
if(!$model->isNewRecord){
    $options['disabled'] = true;
}
?>

<?php $form = ActiveForm::begin([
    'id' => $formName,
]); ?>
<?= $form->field($model, 'IdVoting')->hiddenInput(['id' => $tableName.'-IdVoting'])->label(false) ?>
    <div class="col-md-2">
        <?= $form->field($model, 'Number')->input('number', array_merge(['id' => $tableName.'-Number'],$options)) ?>
    </div>
    <?php if(!$model->isNewRecord): ?>
    <div class="col-md-2 x3">
        <?= Html::label('Cargos Pendientes:', 'lbl_pendent_profiles')?>
        <?= Html::label('0', null, ['id' => 'lbl_pendent_profiles','class' => 'form-control input-lg']);?>
    </div>
    <?php endif; ?>

<?php ActiveForm::end(); ?>

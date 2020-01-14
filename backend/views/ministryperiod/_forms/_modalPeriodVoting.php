<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\web\View;

?>
<?php Modal::begin([
    'id' => $modalName,
    'header' => '<h3>Registrar Periodo de Ministerio</h3>',
    'size' => Modal::SIZE_LARGE,
    'headerOptions' => ['class' => 'bg-primary'],
    'footer' => Html::button('<i class="fas fa-save"></i> Guardar', ['class' => 'btn btn-success', 'id' => 'btnDetSave']) . ""
    . Html::button('<i class="fas fa-times-circle"></i> Cancelar', ['class' => 'btn btn-danger', 'id' => 'btnDetCancel'])
]); 
?>
<?= $this->render('_formPeriodVoting', [
        'model' => $modelDetail, 'formName' => $formName,
    ]) ?>
<?php Modal::end();?>

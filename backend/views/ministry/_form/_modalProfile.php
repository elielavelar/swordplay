<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\web\View;

?>
<?php Modal::begin([
    'id' => $modalName,
    'header' => '<h3>Relacionar Cargo a Ministerio</h3>',
    'headerOptions' => ['class' => 'bg-primary'],
    'footer' => Html::button('<i class="fas fa-save"></i> Guardar', ['class' => 'btn btn-success', 'id' => 'btnProfSave']) . ""
    . Html::button('<i class="fas fa-times-circle"></i> Cancelar', ['class' => 'btn btn-danger', 'id' => 'btnProfCancel'])
]); 
?>
<?= $this->render('_formProfile', [
        'model' => $modelDetail, 'formName' => $formName,
    ]) ?>
<?php Modal::end();?>

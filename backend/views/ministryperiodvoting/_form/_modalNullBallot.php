<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\web\View;

?>
<?php Modal::begin([
    'id' => $modalName,
    'header' => '<h3>Anular Rango de Boletas</h3>',
    'size' => Modal::SIZE_DEFAULT,
    'headerOptions' => ['class' => 'bg-primary'],
    'footer' => Html::button('<i class="fas fa-ban"></i> Anular', ['class' => 'btn btn-warning', 'id' => 'btnNullSave']) . ""
    . Html::button('<i class="fas fa-times-circle"></i> Cancelar', ['class' => 'btn btn-danger', 'id' => 'btnNullCancel'])
]); 
?>
<?= $this->render('_formNullBallot', [
        'model' => $model, 'formName' => $formName, 'tableName' => $tableName,
    ]) ?>
<?php Modal::end();?>

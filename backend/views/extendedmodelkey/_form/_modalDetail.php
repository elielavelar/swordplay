<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\web\View;
?>
<?php Modal::begin([
    'id' => $modalName,
    'header' => '<h3>Llave de Modelo</h3>',
    'headerOptions' => ['class' => 'bg-primary'],
    'footer' => Html::button('<i class="fas fa-save"></i> Guardar', ['class' => 'btn btn-success', 'id' => 'btnKeySave']) . ""
    . Html::button('<i class="fas fa-times-circle"></i> Cancelar', ['class' => 'btn btn-danger', 'id' => 'btnKeyCancel'])
]); 
?>
<?= $this->render('_formDetail', [
        'model' => $model, 'formName' => $formName, 'parentModel' => $parentModel
    ]) ?>
<?php Modal::end();?>

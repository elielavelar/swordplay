<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap\Tabs;

?>
<?php Modal::begin([
    'id' => $modalName,
    'header' => '<h3>Detalle Boleta</h3>',
    'size' => Modal::SIZE_DEFAULT,
    'headerOptions' => ['class' => 'bg-primary'],
    'footer' => Html::button('<i class="fas fa-times-circle"></i> Cancelar', ['class' => 'btn btn-danger', 'id' => 'btnBallotCancel'])
]); 
?>
<?= Tabs::widget([
        'id' => 'ballot-tab',
            'items' => [
                [
                    'label' => 'General',
                    'content' => $this->render('_formBallot', [
                        'model' => $model, 'formName' => $formName, 'tableName' => $tableName,
                    ]),
                    'active' => true
                ],
                [
                    'label' => 'Votos ',
                    'content' => Html::tag('div','',['id'=>'vote-content']),
                ],
            ]]);
     ?>
<?php Modal::end();?>

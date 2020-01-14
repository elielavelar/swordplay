<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryperiodvoting */

$this->title = 'Actualizar Votación: ' . $model->Id;
$this->params['breadcrumbs'][] = ['label' => 'Votaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Id, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Actualizar';
$tableName = $model->tableName();
$formName = $tableName."-form";
        
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
    </div>
    <?= Tabs::widget([
            'items' => [
                [
                    'label' => 'General',
                    'content' => $this->render('_form', ['model' => $model, 'formName' => $formName]),
                    'active' => true
                ],
                [
                    'label' => 'Candidatos ',
                    'content' => $this->render('_form/_candidates',[
                        'model'=>$model, 'searchModel'=>$searchModel, 'modelDetail'=>$modelDetail, 'dataProvider' => $dataProvider
                        ]),
                ],
                [
                    'label' => 'Boletas ',
                    'content' => $this->render('_form/_ballots',[
                        'model'=>$model, 'searchModel'=>$searchModelBallot, 'modelDetail'=>$modelDetailBallot, 'dataProvider' => $dataProviderBallot
                        ]),
                ],
                
            ]]);
     ?>
    <div class="panel-footer">
        <div class="row">
            <div class="col-md-12">
                <span class="pull-right">
                    <?= Html::button('<i class="fas fa-save"></i> Guardar', ['type'=> 'button','class' => 'btn btn-success' , 'id' => 'btnSave']) ?>
                    <?= Html::a('<i class="fas fa-times"></i> Cancelar', ['index'], ['class' => 'btn btn-danger', 'id' => 'btnCancel']) ?>
                </span>
            </div>
        </div>
    </div>
</div>
<?php 
$script = <<< JS
   $(document).ready(function(){
        $("#btnSave").on('click', function(){
            $("#$formName").submit();
        });
   });
JS;
$this->registerJs($script, $this::POS_READY);
?>

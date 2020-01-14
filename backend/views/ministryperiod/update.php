<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryperiods */

$this->title = 'Actualizar Periodo Ministerio: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Ministerios', 'url' => ['ministry/index']];
$this->params['breadcrumbs'][] = ['label' => $model->ministryServiceCentre->ministry->Name, 'url' => ['ministry/view', 'id'=> $model->ministryServiceCentre->IdMinistry]];
$this->params['breadcrumbs'][] = ['label' => $model->ministryServiceCentre->serviceCentreName, 'url' => ['ministryservicecentre/view', 'id' => $model->IdMinistryServiceCentre]];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->Id]];
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
                    'label' => 'Votaciones ',
                    'content' => $this->render('_forms/_detail',[
                        'model'=>$model, 'searchModel'=>$searchModel, 'modelDetail'=>$modelDetail, 'dataProvider' => $dataProvider
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
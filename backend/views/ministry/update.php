<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use backend\models\Ministries;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministries */
/* @var $searchModel backend\models\MinistriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $modelDetail backend\models\Ministryservicecentres */

$this->title = 'Actualizar Ministerio: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Ministerio', 'url' => ['index']];
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
                    'label' => 'Cargos ',
                    'content' => $this->render('_form/_profiles',[
                        'model'=>$model, 'searchModel'=>$searchModelProfile, 'modelDetail'=>$modelDetailProfile, 'dataProvider' => $dataProviderProfile
                        ]),
                ],
                [
                    'label' => 'Filiales ',
                    'content' => $this->render('_form/_detail',[
                        'model'=>$model, 'searchModel'=>$searchModel, 'modelDetail'=>$modelDetail, 'dataProvider' => $dataProvider
                        ]),
                    'visible' => $model->IdEnvironmentType ? $model->environmentType->Code != Ministries::TYPE_ENVIRONMENT_GLOBAL: true,
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
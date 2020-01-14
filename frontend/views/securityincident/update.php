<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use backend\models\Securityincident;

/* @var $this yii\web\View */
/* @var $model backend\models\Securityincident */

$this->title = 'Actualizar Incidencia de Seguridad: ' . $model->Ticket;
$this->params['breadcrumbs'][] = ['label' => 'Incidencias de Seguridad', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Ticket, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Actualizar';

$admin = Yii::$app->customFunctions->userCan(Securityincident::tableName().'Admin');
$create = Yii::$app->customFunctions->userCan(Securityincident::tableName().'Create');
$update = Yii::$app->customFunctions->userCan(Securityincident::tableName().'Update');
$close = Yii::$app->customFunctions->userCan(Securityincident::tableName().'Close');
$view = Yii::$app->customFunctions->userCan(Securityincident::tableName().'View');
$delete = Yii::$app->customFunctions->userCan(Securityincident::tableName().'Delete');

$save = FALSE;
switch ($model->state->Code){
    case Securityincident::STATE_REGISTRED:
        $save = $model->isNewRecord ? $create:$update;
        $delete = $delete && TRUE;
        break;
    case Securityincident::STATE_INPROCESS:
        $save = $update || $close;
        $delete = $delete && TRUE;
        break;
    default :
        $delete = $delete && FALSE;
        break;
}

$permissions = [
    'create' => $create, 'update' => $update, 'view'=> $view,
    'admin'=> $admin,'close'=> $close, 'save' => $save,
    'delete'=> $delete
];

?>
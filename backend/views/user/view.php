<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->completeName;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$user = Yii::$app->user->getIdentity();
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::$app->customFunctions->userCan('userUpdate') ? Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']):""; ?>
        <?= Yii::$app->customFunctions->userCan('userDelete') && $model->username != $user->username ? Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Está seguro que desea eliminar este registro?',
                'method' => 'post',
            ],
        ]):""; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'email:email',
            [
                'attribute'=>'IdProfile',
                'value'=>$model->IdProfile ? $model->idProfile->Name:"",
            ],
            'CodEmployee',
            [
                'attribute'=>'IdServiceCentre',
                'value'=>$model->IdServiceCentre ? $model->idServiceCentre->Name:"",
            ],
            [
                'attribute'=>'IdState',
                'value'=>$model->IdState ? $model->idState->Name:"",
            ],
            'created_at',
            'updated_at',
            'PasswordExpirationDate',
        ],
    ]) ?>
    <?= Html::a('Cancelar', ['index'], ['class'=>'btn btn-danger'])?>

</div>

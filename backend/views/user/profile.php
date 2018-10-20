<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Perfil de Usuario: ' . $model->completeName;
$this->params['breadcrumbs'][] = 'Perfil';
?>
<div class="user-update">

    <div class="panel panel-default">
        <?= $this->render('_form/_userProfileForm', [
            'model' => $model,
        ]) ?>
    </div>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Citizen */

$this->title = 'Datos Ciudadano: ' . $model->CompleteName;
$this->params['breadcrumbs'][] = ['label' => 'Ciudadano', 'url' => ['profile']];
$this->params['breadcrumbs'][] = 'Perfil';
?>
<div class="citizen-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<?php
$script = <<< JS
    $('input').not('#shortcode').attr('disabled', true);
    $(document).ready(function(){
    
    });
JS;

$this->registerJs($script, \yii\web\VIEW::POS_END);

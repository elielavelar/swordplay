<?php
use yii\helpers\Html;
use kartik\widgets\SwitchInput;
/* @var $this yii\web\View */
/* @var $model common\models\User */

?>
<form action="#" method="post">
<div class="panel panel-default">
    <div class="panel-body">
        <?php 
        echo '<pre>';
        print_r($model->settings);
        echo '</pre>';
            #foreach ($model->settings as $setting){
                
            #}
        ?>
    </div>
</div>
</form>
    

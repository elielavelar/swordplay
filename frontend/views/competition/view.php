<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Competitions */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Competitions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$colors = ['red','yellow','aqua','blue','green','navy','teal','olive','lime','orange','fuchsia','purple','maroon','light-blue','black','red-active','yellow-active'];
?>
<div class="competitions-view">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="panel-body">
            <div class="row-fluid">
                <?php
                foreach ($model->competitionrounds as $round) {
                    $option = "<div class='col-md-4'>"
                            . "<div class='info-box'>"
                            . "<span class='info-box-icon bg-".$colors[$round->Sort]."'>"
                            . "<i class='$round->Icon'></i></span>"
                            . "<div class='info-box-content'>"
                            . "<span class='info-box-text'>".$round->Name."</span>"
                            . "<span class='info-box-number'>".$round->Sort."</span>"
                            . "<span class='info-box-text'>".$round->state->Name."</span>"
                            . "</div>"
                            . "</div>"
                            . "</div>";
                    $url = Url::to(['competitionround/view', 'id' => $round->Id]);
                    echo Html::a($option,$url);;
                }
                ?>
            </div>
        </div>
    </div>
    
    
</div>

<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model backend\models\Ministryperiodvoting */
/* @var $modelDetail backend\models\Ministryvotingballot */
frontend\assets\AppAsset::register($this);

$this->title = 'Conteo Votación: ' .($model->IdMinistryPeriod ? $model->ministryPeriod->ministryServiceCentre->ministry->Name." ".$model->ministryPeriod->Name : '');
$this->params['breadcrumbs'][] = ['label' => 'Votaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->Id;
$this->params['breadcrumbs'][] = 'Conteo';
$controller = Yii::$app->controller->id;
$url = \Yii::$app->getUrlManager()->createUrl('ministryvotingballotvote');
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-lg-2">
                 <div class="info-box-white">
                    <span class="info-box-text">Hojas Procesadas</span>
                    <span class="info-box-number">
                        <label id="lbl_processedballot"></label>
                        <strong>/</strong> 
                        <label class="totalballot"><?=$model->TotalVotingBallot?></label>
                    </span>
                </div>
            </div>
            <div class="col-lg-2">
                 <div class="info-box-white">
                    <span class="info-box-text">Hojas Pendientes</span>
                    <span class="info-box-number">
                        <label id="lbl_pendentballot"></label>
                        <strong>/</strong> 
                        <label class="totalballot"><?=$model->TotalVotingBallot?></label>
                    </span>
                </div>
            </div>
            <div class="col-lg-1">
                <div class="small-info-box-white">
                    <span class="info-box-text">Anuladas</span>
                    <span class="info-box-number" id="lbl_annulledballot">0</span>
                </div>
            </div>
            <div class="col-lg-5">
                <span>&Uacute;ltima Actualizaci&oacute;n: <label class="small-info-box-icon" id="lbl_timelastupdate"></label></span>
                <div class="overlay" id="divRefresh" style="display: none">
                    <i class="fas fa-spinner"></i>
                    <span>Procesando Datos...</span>
                </div>
            </div>
            <div class="col-lg-2">
                <span class="pull-right">
                    <?= Html::button('<i class="fas fa-sync-alt"></i> Actualizar',['type' => 'button', 'id' => 'btnRefresh', 'class' => 'btn btn-primary btn-lg']);?>
                </span>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-bordered table-hover dataTable">
                    <thead role="row">
                        <tr>
                        <?php 
                            $len = count($modelDetail->profiles);
                            $value  = $len > 0 ? (100/$len) : 100;
                            $width = round($value, 2);
                            $columns = "";
                            foreach($modelDetail->profiles as $profile){ ?>
                            <th class="sorting_asc" tabindex="0" aria-controls="resultados" rowspan="1" colspan="1" aria-sort="descending" aria-label="" style="width: <?=$width?>%">
                                <?=$profile->CustomName ? $profile->CustomName : $profile->profile->Name; ?>
                            </th>
                            <?php 
                                $columns .= "<td class='profiles' id='td_profile_$profile->Id'></td>";
                            } 
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?=$columns; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<< JS
   $(document).ready(function(){
        
        $('#btnRefresh').on('click', function(){
            getData();
        });
   });
JS;
$this->registerJs($js, $this::POS_READY);

$jsHead = <<< JS
   var getData = function(){
        $("#divRefresh").show();
        var data = {Id: $model->Id};
        var params = {};
        params.URL = '$url/getvotes';
        params.DATA = {'data': JSON.stringify(data)};
        params.METHOD = 'POST';
        params.DATATYPE = 'json';
        params.SUCCESS = function(data){
            $('td.profiles').empty();
            var result = data.result;
            var ballots = result.ballots;
            $.each(result.table, function(i, values){
                var td = $('#td_profile_'+i);
                $.each(values, function(j, val){
                    td.append(val);
                });
            });
            var lastupdate = moment().format('DD-MM-YYYY HH:mm:ss');
            $('#lbl_processedballot').html(ballots.processed);
            $('#lbl_pendentballot').html(ballots.pendent);
            $('#lbl_annulledballot').html(ballots.annulled);
            $('#lbl_timelastupdate').html(lastupdate);
            if(parseInt(ballots.pendent) === 0){
                clearInterval(refreshData);
            }
            $('.totalballot').html(ballots.total);
            $("#divRefresh").hide();
        };
        params.ERROR = function(data){
            console.log(data);
            $("#divRefresh").hide();
        };
        AjaxRequest(params);
   };
   
   refreshData = setInterval(getData, $model->refreshTime);
   setTimeout(getData, 1000);
JS;
$this->registerJs($jsHead, $this::POS_HEAD);
?>
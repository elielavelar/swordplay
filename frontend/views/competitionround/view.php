<?php

use kartik\helpers\Html;
use yii\helpers\Url;
use yii2mod\alert\Alert;
use frontend\assets\AppAsset;

AppAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\Competitionrounds */
/* @var $modelSearch \backend\models\RoundquestionsSearch */
/* @var $params \backend\models\Settingsdetail */

$urlQuestion = Yii::$app->getUrlManager()->createUrl('roundquestion');
$urlAnswer = Yii::$app->getUrlManager()->createUrl('questionanswer');

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Competencia', 'url' => Url::to(['competition/view', 'id' => $model->IdCompetition])];
$this->params['breadcrumbs'][] = ['label' => 'Rondas'];
$this->params['breadcrumbs'][] = $this->title;

$value = $model->QuestionTime;
$time = explode(':', $value);
$_hour = (int) $time[0];
$_minutes = (int) $time[1];
$_seconds = (int) $time[2];
?>
<div class="box box-solid">
    <div class="box-header">
        <div class="row-fluid">
            <div class="col-xs-9"></div>
            <div class="col-xs-1">
                <a href="javascript:void(0);"class="btn btn-app bg-olive">
                    <i class="fa" id="box-pendientes">-</i>
                    Pendientes
                </a>
            </div>
            <div class="col-xs-1">
                <a href="javascript:void(0);"class="btn btn-app bg-red">
                    <i class="fa" id="box-anuladas">-</i>
                    Anuladas
                </a>
            </div>
            <div class="col-xs-1">
                <a href="javascript:void(0);"class="btn btn-app bg-black">
                    <i class="fa" id="box-resueltas">-</i>
                    Resueltas
                </a>
            </div>
        </div>
        <div class="box box-body">
            <div class="row">
                <div class="col-lg-3">
                    <div class="box box-solid">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class='small-box bg-navy' style='text-align:center; height: auto'>
                                        <span style='font-size:130pt; font-weight:bolder;' id='showbox'>-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="height: 80pt">
                                    <a href="javascript:void(0)" class="btn btn-primary" id="btn_search" style="width: 100%">
                                        <i class="fa fa-play-circle" style="font-size: 50pt; height: 50pt"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="javascript:void(0)" class="btn btn-warning disabled" id="btn_show-qt" style="width: 100%; padding: 20px 20px" title="Mostrar Pregunta">
                                        <i class="fa fa-eye" style="font-size: 20pt; "></i> 
                                        <label style="font-size: 20pt; "> Mostrar Pregunta</label>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9" style="height: 750px; display: block; overflow-y: auto">
                    <div class="box box-solid">
                        <div class="box-body">
                            <div class="row-fluid" style="">
                                <?php
                                $bg = ['PND' => 'olive', 'RST' => 'black', 'ANU' => 'red'];
                                foreach ($modelSearch as $q) {
                                    $class = strtolower($q->state->Name);
                                    $option = "<div class='col-xs-2 item-qt $class' id='" . $q->Id . "'>"
                                            . "<div class='small-box bg-" . ($bg[$q->state->Code]) . " item-box' style='text-align:center'>"
                                            . "<span style='font-size:4em; font-weight:bolder;'>" . $q->Sort . "</span>"
                                            . "</div>"
                                            . "</div>";
                                    echo $option;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade in" id="modal-question" tabindex="-1" role="modal" aria-labeledby="#Label">
    <div class="modal-dialog modal-title modal-lg" role="document">
        <div class="modal-content ">
            <?= Html::hiddenInput('IdRoundQuestion', '', ['id' => 'IdQuestion']); ?>
            <div class="modal-header bg-blue" >
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><a class="glyphicon glyphicon-remove" style="color: white"></a></span></button>
                <h3 class="modal-title" id="Label"><strong>Pregunta <div class="inline" id="OrderQuestion"></div></strong></h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box bg-gray" style="text-align: center">
                            <div class="box-header">
                                <section style="display: block">
                                    <span style='font-size:40pt; font-weight:bold;' >Pregunta </span>
                                    <span style='font-size:50pt; font-weight:bolder;' id='nquestion'>-</span>
                                </section>
                            </div>
                            <div class="box-body">
                                <span id="cover-layer">
                                    <i class="fa fa-question" style="font-size: 150pt; height: 150pt"></i>
                                </span>
                                <section id="question-layer" style="display: none">
                                    <div class="box box-default">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p style='font-size:40pt; font-weight:bold;' id="question-text">Mi Pregunta</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box box-primary" id="answer-box" style="display: none">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p style='font-size:40pt; font-weight:bolder;' id="answer-text">Mi Respuesta</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p style='font-size:25pt; font-weight: bold' id="answer-quote">Mi Cita...</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-lg-4">
                        <div id="time-counter" class="callout callout-info">
                            <i class="fa fa-clock-o fa-2x"></i>&nbsp;
                            <span id="timer-time" class="h1 bold">-</span>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <?= Html::button('<i class="fas fa-check"></i> Ver Pregunta',['type' => 'button','id' => 'btn_show','class' => 'btn btn-primary btn-lg','style' => 'font-size: 30pt']);?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <span class="pull-left">
                            <?= Html::button('<i class="fas fa-times"></i> Cerrar',['type' => 'button','id' => 'btn_close','class' => 'btn btn-danger']);?>
                            <?= Html::button('<i class="fas fa-ban"></i> Anular',['type' => 'button', 'id' => 'btn_null','class' => 'btn btn-warning', 'style' => 'display: none; margin-left: 35px']);?>
                        </span>
                        <span class="pull-right">
                            <?= Html::button('<i class="fas fa-lightbulb"></i> Ver Respuesta',['type' => 'button', 'id' => 'btn_answer','class' => 'btn btn-success', 'style' => 'display: none;']);?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$date = date('Y-m-d');
$script = <<< JS
    randomInterval = null;
	initTime = null;
	loopTime = null;
    $(document).ready(function(){
		
		cont_time = $("#timer-time");
		i_hour = $_hour;
		i_min = $_minutes;
		i_sec = $_seconds;
		i_millsec = 0;
		initTime = moment({hour: i_hour, minute: i_min, seconds: i_sec, milliseconds: i_millsec});
                //initTime = moment('$value','HH:mm:ss');
		loopTime = initTime.clone();
		cont_time.html(loopTime.format('HH:mm:ss'));
		
		timerCount = moment.duration(1,'seconds').timer({loop: true ,start: false}, function(){
			loopTime.subtract(1,'second');
			if(loopTime.format('HH') !== initTime.format('HH')){
				cont_time.parent('div.callout').removeClass('callout-info').addClass('callout-danger');
				timerCount.stop();
			} else {
				cont_time.html(loopTime.format('HH:mm:ss'));
			}
		});
		
        countQuestions();
        
        $('.item-qt').click(function(){
            selectBox($(this));
        });
        
        $("#btn_search").on("click keypress",function(){
            var state = $(this).hasClass('btn-primary');
            if(state){
                $('.pendiente div.small-box').removeClass('bg-yellow').addClass('bg-olive');
                $(this).removeClass('btn-primary')
                    .addClass('btn-danger');
                $(this).find('i').removeClass('fa-play-circle').addClass('fa-pause');
				$("#btn_show-qt").addClass('disabled');
                selectRandom();
            } else {
                $(this).removeClass('btn-danger')
                    .addClass('btn-primary');
                $(this).find('i').removeClass('fa-pause').addClass('fa-play-circle');
				$("#btn_show-qt").removeClass('disabled');
                clearInterval(randomInterval);
            }
            $("#btn_search").blur();
        });
        
        $(".pendiente").click(function(){
            var id = $(this).attr('id');
            $("#IdQuestion").val(id);
            viewQuestion(id);
        });
        
        $('#modal-question').on('hidden.bs.modal', function () {
            limpiarModal();
        });
        
        $("#btn_show").on("click",function(){
            showQuestion();
        });
        
        $("#btn_close").on("click",function(){
            $("#modal-question").modal("toggle");
        });
        
        $("#btn_answer").on("click",function(){
            showAnswer();
        });
        
        $("#btn_null").on("click",function(){
            invalidateQuestion();
        });
		
		$("#btn_show-qt").on('click', function(){
			var state = $(this).hasClass("disabled");
			if(state){
				
			} else {
				var parent = $("div.bg-yellow").parent('div');
				var id = parent.attr('id');
				$("#IdQuestion").val(id);
				viewQuestion(id);
			}
		});
		
    }).on('keypress',function(e){
        if(e.which in {'13':13, '32':32}) {
            $("#btn_search").click();
        }
        
   });
    
    var selectBox = function(item){
        if(item.hasClass('pendiente')){
            var id = item.attr('id');
            var text = $('#'+id+' span').html();
            $("#showbox").html(text);
        }
    };
        
    var selectRandom = function(){
        var obj = $('.pendiente').get();
        var ant = null;
        randomInterval = setInterval(function(){
            var item = obj[Math.floor(Math.random()*obj.length)];
            if(ant != null){
                $(ant).find('div.small-box').removeClass('bg-yellow').addClass('bg-olive');
                ant = null;
            }
            $(item).find('div.small-box').removeClass('bg-olive').addClass('bg-yellow');
            selectBox($(item));
            ant = item;
        }, 50);
        
    };
    
    var viewQuestion = function(id){
        var n = $("#"+id+" div.small-box").find('span').html();
        $("#nquestion").html(n);
        $("#modal-question").modal();
		setTimeCounter();
    };
   
   var getQuestion = function(){
        var id = $("#IdQuestion").val();
        var data = {'Id':id};
        var params = {};
        params.URL = "$urlQuestion/get";
        params.DATA = {'data':JSON.stringify(data)};
        params.METHOD = 'POST';
        params.DATATYPE = 'json';
        params.SUCCESS = function(data){
            $("#question-text").html(data.Name);
            $("#answer-quote").html(data.QuoteReference);
			setTimeCounter();
			timerCount.start();
        };
        params.ERROR = function(data){
            alert(data.message);
        };
        AjaxRequest(params);
   };
        
   var updateQuestion = function(code){
        var clase = "";
        var claseRemove = "";
        var classBox = "";
        switch(code){
            case 'RST':
                clase = "resuelta";
                claseRemove = "pendiente";
                claseBox= "bg-black";
                break;
            case 'ANU':
                clase = "anulada";
                claseRemove = "pendiente";
                claseBox= "bg-red";
                break;
            case 'PND':
                clase = "pendiente";
                claseRemove = "resuelta";
                claseBox= "bg-green";
                break;
            default:
                break;
        }
        var id = $("#IdQuestion").val();
        var data = {'id':id,'code':code};
        var params = {};
        params.URL = "$urlQuestion/save";
        params.DATA = {'data':JSON.stringify(data)},
        params.METHOD = 'POST';
        params.DATATYPE = 'json';
        params.SUCCESS = function(data){
            $("#"+id)
                .removeClass('pendiente')
                .removeClass('resuelta')
                .removeClass('anulada')
                .addClass(clase);
            $("#"+id).find('.item-box')
                .removeClass('bg-olive')
                .removeClass('bg-yellow')
                .removeClass('bg-red')
                .removeClass('bg-black')
                .addClass(claseBox);
            if(data.close === true){
                $("#modal-question").modal("toggle");
            }
            countQuestions();
        };
        params.ERROR = function(data){
            alert(data.message);
        };
        AjaxRequest(params);
   };
        
   var showQuestion = function(){
        getQuestion();
        $("#cover-layer").hide("slow");
        $("#question-layer").show("slow");
        $("#btn_show").hide();
        $("#btn_answer").show();
        $("#btn_null").show();
    };
        
    var getAnswer = function(){
        var id = $("#IdQuestion").val();
        var data = {'IdQuestion':id};
        var params = {};
        params.URL = "$urlAnswer/get";
        params.DATA = {'data':JSON.stringify(data)},
        params.METHOD = 'POST';
        params.DATATYPE = 'json';
        params.SUCCESS = function(data){
            $("#answer-text").html(data.Value);
            updateQuestion('RST');
        };
        params.ERROR = function(data){
            sweetAlert('Error..',data.message,"error");
        };
        AjaxRequest(params);
   };
    
    var showAnswer = function(){
        getAnswer();
        $("#btn_answer").hide();
        $("#answer-box").show("slow");
		timerCount.stop();
		$("#btn_show-qt").addClass('disabled');
    };
        
    var invalidateQuestion = function(){
        updateQuestion('ANU');
   };
        
    var limpiarModal = function(){
        $("#nquestion").html("-");
        $("#cover-layer").show();
        $("#question-layer").hide();
        $("#question-text").html('-');
        
        $("#answer-box").hide();
        $("#answer-text").html('-');
        $("#answer-quote").html('-');
        
        $("#btn_show").show();
        $("#btn_answer").hide();
        $("#btn_null").hide();
		cont_time.parent('div.callout').removeClass('callout-danger').addClass('callout-info');
        timerCount.stop();
		setTimeCounter();
        countQuestions();
    };
      
   var countQuestions = function(){
        var pendientes = $(".pendiente");
        var anuladas = $(".anulada");
        var resueltas = $(".resuelta");
        $("#box-pendientes").html(pendientes.length);
        $("#box-anuladas").html(anuladas.length);
        $("#box-resueltas").html(resueltas.length);
   };
    
   var setTimeCounter = function(){
		i_hour = $_hour;
		i_min = $_minutes;
		i_sec = $_seconds;
		i_millsec = 0;
		initTime = moment({hour: i_hour, minute: i_min, seconds: i_sec, milliseconds: i_millsec});
		loopTime = initTime.clone();
		cont_time.parent('div.callout').removeClass('callout-danger').addClass('callout-info');
		cont_time.html(loopTime.format('HH:mm:ss'));
   };
JS;

$this->registerJs($script);
?>
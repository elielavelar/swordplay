<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Ministryvotingballot */

$this->title = 'Registrar Boleta: ' . $model->Number;
$this->params['breadcrumbs'][] = ['label' => 'Votacion', 'url' => ['ministryperiodvoting/index']];
$this->params['breadcrumbs'][] = ['label' => 'Registro Boletas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->Number;
$this->params['breadcrumbs'][] = 'Actualizar';

$url = Yii::$app->getUrlManager()->createUrl(['ministryvotingballot']);

$tableName = $model->tableName();
$formName = $tableName . '-form';

$defaultImg = Url::to('@web/img/avatar.png');
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <?=$this->render('_form', [
                    'model' => $model, 'formName' => $formName, 'tableName' => $tableName,
            ]);?>
            <div class="col-md-8">
                <span class="pull-left">
                    <?= Html::button('<i class="fas fa-ban"></i> Anular', ['type' => 'button', 'class' => 'btn btn-warning btn-lg btnVoid']); ?>
                </span>
                <span class="pull-right">
                    <?= Html::button('<i class="fas fa-check"></i> Procesar', ['type' => 'button', 'class' => 'btn btn-success btn-lg btnProcess']); ?>
                    <?= Html::button('<i class="fas fa-trash"></i> Limpiar', ['type' => 'button', 'class' => 'btn btn-default btn-lg btnClean']); ?>
                </span>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-9 margin-none">
                <div class="row-fluid candidates">
                    <?php foreach ($model->candidates as $candidate) { ?>
                        <div class="col-md-3 margin-none candidate" id="candidate_<?=$candidate->Id;?>" idcandidate="<?=$candidate->Id;?>">
                            <div class="box box-default">
                                <div class="box-body">
                                    <?= Html::img($candidate->member->IdAttachmentPicture ? $candidate->member->path : '@web/img/avatar.png', ['alt' => 'Miembro', 'class' => 'img-fluid', 'style' => 'width: 100%','id' => 'img-candidate-'.$candidate->Id]); ?>
                                </div>
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12 candidate-name">
                                            <?= $candidate->member->displayName; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">Cargos</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row profiles">
                        <?php foreach ($model->profiles as $profile) { ?>
                            <div class="col-md-12 profile" id="profile_<?=$profile->Id ?>" idprofile="<?= $profile->Id ?>" idcandidate=''>
                                <div class="box box-primary">
                                    <div class="box-header">
                                        <h4 class="box-title"><?= $profile->CustomName ? $profile->CustomName : $profile->profile->Name; ?></h4>
                                    </div>
                                    <div class="box-body">
                                        <?= Html::img('@web/img/avatar.png',['style' => 'width: 50px;', 'id' => 'img-profile-'.$profile->Id])?>
                                        <?= Html::tag('span','', ['id' => 'lbl-profile-name-'.$profile->Id])?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <div class="row">
            <div class="col-md-12">
                <span class="pull-right">
                    <?= Html::button('<i class="fas fa-check"></i> Procesar', ['type' => 'button', 'class' => 'btn btn-success btn-lg btnProcess']); ?>
                    <?= Html::button('<i class="fas fa-trash"></i> Limpiar', ['type' => 'button', 'class' => 'btn btn-default btn-lg btnClean']); ?>
                </span>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<< JS
    var profileSelectedClass = 'profile-selected';
    var profileAssignedClass = 'profile-assigned';
    var candidateSelectedClass = 'candidate-selected';
    var candidateAssignedClass = 'candidate-assigned';
        
   $(document).ready(function(){
        countPendentProfiles();
        
        $(".btnProcess").on('click', function(){
            swal({
                title: "Confirmación?",
                text: "¿Está seguro que desesa Procesar la Boleta $model->Number?",
                type: "success",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "¡Sí, Procesar!",
                cancelButtonText: "Cancelar",
                closeOnConfirm: true
            },
              function(){
                  processBallot();
              });
        });
        
        $(".btnVoid").on('click', function(){
            swal({
                title: "Confirmación?",
                text: "¿Está seguro que desesa Anular la Boleta $model->Number?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e08e0b",
                confirmButtonText: "¡Sí, Anular!",
                cancelButtonText: "Cancelar",
                closeOnConfirm: true
            },
              function(){
                  voidBallot();
            });
        });
        
        $(".btnClean").on('click', function(){
            swal({
                title: "Confirmación?",
                text: "¿Está seguro que desesa Limpiar la Boleta $model->Number?",
                type: "error",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "¡Sí, Limpiar!",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false
            },
              function(){
                    window.location = '$url/create/'+$model->IdVoting;
              });
        });
        
        $(".candidate").on('click', function(){
            var c = $(this);
            var id = c.attr('idcandidate');
            if( c.hasClass(candidateAssignedClass) ){
                leaveAssigned(id);
            } else if(c.hasClass(candidateSelectedClass)){
                c.removeClass(candidateSelectedClass);
            } else {
                $('.candidates > div.candidate').removeClass(candidateSelectedClass);
                c.addClass(candidateSelectedClass);
                assignedCandidateProfile();
            }
        });
        
        $(".profile").on('click', function(){
            var c = $(this);
            var id = c.attr('idcandidate');
            if( c.hasClass(profileAssignedClass) ){
                leaveAssigned(id);
            } else if(c.hasClass(profileSelectedClass)){
                c.removeClass(profileSelectedClass);
            } else {
                $('.profiles > div.profile').removeClass(profileSelectedClass);
                c.addClass(profileSelectedClass);
                assignedCandidateProfile();
            }
        });
        
   });
JS;
$this->registerJs($js, $this::POS_READY);

$jsHead = <<< JS
    var defaultImg = '$defaultImg';
    var profileSelectedClass = 'profile-selected';
    var profileAssignedClass = 'profile-assigned';
    var candidateSelectedClass = 'candidate-selected';
    var candidateAssignedClass = 'candidate-assigned';
    
    var countPendentProfiles = function(){
        var profiles = $('.profile');
        var assignedProfiles = $('.'+profileAssignedClass);
        var l = parseInt(profiles.length) - parseInt(assignedProfiles.length);
        $('#lbl_pendent_profiles').html(l);
    };
    
    var processBallot = function(){
        var data = {};
        data.IdVoting = $model->IdVoting;
        data.Id = $model->Id;
        var votes = countVotes();
        $.extend(data,{votes: votes});
        
        var params = {};
        params.URL = '$url/register';
        params.DATA = {'data':JSON.stringify(data)};
        params.METHOD = 'POST';
        params.DATATYPE = 'json';
        params.SUCCESS = function(data){
            swal({
                title: "Boleta Registrada",
                text: data.message,
                type: "success",
                showCancelButton: false,
                confirmButtonColor: "#00a65a",
                confirmButtonText: "Aceptar",
                closeOnConfirm: true
            },
              function(){
                    window.location = '$url/create/'+$model->IdVoting;
              },
              function(){
                    window.location = '$url/create/'+$model->IdVoting;
              });
        };
        params.ERROR = function(data){
            swal({html:true,title:"Error: "+data.code, text: data.message, type:"error"});
            if(data.errors){
                var errors = {};
                errors.ID = "$formName";
                errors.PREFIX = "$tableName-";
                errors.ERRORS = data.errors;
                errors.EXTRA = function(){};
                setErrorsModel(errors);
            }
        };
        AjaxRequest(params);
    };
    
    var leaveAssigned = function(id){
        var profile = $('div.profiles').find('div.profile[idcandidate='+id+']');
        var candidate = $('#candidate_'+id);
        var idProfile = profile.attr('idprofile');
        
        candidate.removeClass(candidateAssignedClass);
        profile.attr('idcandidate','');
        profile.removeClass(profileAssignedClass);
        $("#img-profile-"+idProfile).attr('src','$defaultImg');
        $('#lbl-profile-name-'+idProfile).html('');
        countPendentProfiles();
    };
    
    var assignedCandidateProfile = function(){
        var profile = $('.'+profileSelectedClass);
        var candidate = $('.'+candidateSelectedClass);
        if(candidate.length === 1 && profile.length === 1){
            profile.removeClass(profileSelectedClass).addClass(profileAssignedClass);
            candidate.removeClass(candidateSelectedClass).addClass(candidateAssignedClass);
            /*VALUES*/
            var idCandidate = candidate.attr('idcandidate');
            var nameCandidate = candidate.find('.candidate-name').html();
            var imgCandidate = $('#img-candidate-'+idCandidate).attr('src');
            var idProfile = profile.attr('idprofile');
            /*VALUES TO PROFILE*/
            profile.attr('idcandidate',idCandidate);
            $('#img-profile-'+idProfile).attr('src',imgCandidate);
            $('#lbl-profile-name-'+idProfile).html(nameCandidate);
        }
        countPendentProfiles();
    };
    
    var countVotes = function(){
        var votes = [];
        var profiles = $('.'+profileAssignedClass);
        $.each(profiles, function(i, val){
            var vote = {};
            vote.IdProfile = $(val).attr('idprofile');
            vote.IdCandidate = $(val).attr('idcandidate');
            votes.push(vote);
        });
        return votes;
    };
        
    var voidBallot = function(){
        window.location = '$url/voidballot/'+$model->Id;
    };
    
JS;
$this->registerJs($jsHead, $this::POS_HEAD);
?>
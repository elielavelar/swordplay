<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model common\models\Competitionrounds */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(); ?>
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'IdType')->dropDownList($model->getTypes(),[])?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'IdState')->dropDownList($model->getStates(),[]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'Icon')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'QuestionTime')->widget(MaskedInput::className(),[
                        'model'=> $model,
                        'attribute'=> 'QuestionTime',
                        'mask'=> '99:99:99',
                        'options'=> ['id'=>'competitionround-questiontime','class'=>'form-control']
                    ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Description')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <?= $form->field($model, 'IdCompetition')->hiddenInput([])->label(false)?>
</div>
<div class="panel-footer">
    <div class="row">
        <div class="col-md-12">
            <span class="pull-right">
                <?= Html::submitButton('<i class="fas fa-save"></i> Guardar', ['class' => 'btn btn-success']) ?>
                <?= Html::a('<i class="fas fa-times"></i> Cancelar',['competition/update','id' => $model->IdCompetition],['class' => 'btn btn-danger']);?>
            </span>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

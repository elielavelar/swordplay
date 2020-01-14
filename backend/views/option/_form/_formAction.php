<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Type;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use backend\models\Options;
/* @var $this yii\web\View */
/* @var $model backend\models\Options */
/* @var $form yii\widgets\ActiveForm */
$frmName = $model->tableName()."_action-form";
$fieldPrefix = 'action-'.$model->tableName()."-";
$types = ArrayHelper::map(Type::find()
        ->where(['KeyWord'=>  StringHelper::basename($model->className())])
        ->andFilterWhere(['in','Code',[Options::TYPE_ACTION,  Options::TYPE_PERMISSION]])
        ->select(['Id','Name'])
        ->all(),'Id','Name');
?>

<?php $form = ActiveForm::begin([
    'id'=>$frmName,
]); ?>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Name')->textInput(['maxlength' => true,'id'=>$fieldPrefix.'name']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'KeyWord')->textInput(['maxlength' => true,'id'=>$fieldPrefix.'keyword']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'Icon')->textInput(['maxlength' => true,'id'=>$fieldPrefix.'icon']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'IdState')->dropDownList($model->getStates(),['id'=>$fieldPrefix.'idstate']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'Sort')->input('number', ['id'=>$fieldPrefix.'sort']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'IdType')->dropDownList($types,['id'=>$fieldPrefix.'idtype']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'RequireAuth')->dropDownList(['0'=>'NO','1'=>'SI'],['id'=>$fieldPrefix.'requireauth']); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'SaveLog')->dropDownList(['0'=>'NO','1'=>'SI'],['id'=>$fieldPrefix.'savelog'])?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'Url')->textInput(['maxlength' => true,'id'=>$fieldPrefix.'url']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'IdUrlType')->dropDownList($model->getUrlTypes(),['id'=>$fieldPrefix.'idurltype'])?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'SaveTransaction')->dropDownList(['0'=>'NO','1'=>'SI'],['id'=>$fieldPrefix.'savetransaction'])?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Description')->textarea(['rows' => 6,'id'=>$fieldPrefix.'description']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'Optionenvironment')->checkboxList($model->getEnvTypes(),[
                'class'=>'form-control checkbox-list',
                'tag'=>"span",
                'item'=> function($index, $label, $name, $checked, $value){
                    return Html::checkbox($name, $checked, [
                        'value' => $value,
                        'label' => $label,
                        'class' => 'checkbox-list',
                     ]);
                },
                'id'=>$fieldPrefix.'Optionenvironment'
            ]); ?>
        </div>
    </div>
    <?= $form->field($model, 'Id')->hiddenInput(['id'=>$fieldPrefix.'id'])->label(FALSE); ?>
    <?= $form->field($model, 'IdParent')->hiddenInput(['id'=>$fieldPrefix.'idparent'])->label(FALSE); ?>
    <?= $form->field($model, 'ItemMenu')->hiddenInput(['id'=>$fieldPrefix.'itemmenu'])->label(FALSE); ?>
<?php ActiveForm::end(); ?>


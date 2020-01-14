<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use common\models\Member;
use kartik\widgets\FileInput;
use yii\web\JsExpression;
use backend\models\Attachments;
use yii\helpers\StringHelper;

$url = Yii::$app->getUrlManager()->createUrl('member');
/* @var $this yii\web\View */
/* @var $model common\models\Member */
/* @var $form yii\widgets\ActiveForm */

$parentModel = StringHelper::basename(Member::class);
$attachModel = StringHelper::basename(Attachments::class);
?>
 <?php $form = ActiveForm::begin([
        'id' => $formName,
        'options'=>['enctype'=>'multipart/form-data'],
    ]);
?>
<div class="panel-body">
    <div class="row">
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'Code')->textInput(['maxlength' => true, 'readonly' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'FirstName')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'SecondName')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'ThirdName')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'FirstLastName')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'SecondLastName')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
<?= $form->field($model, 'Gender')->dropDownList($model->getGenders(), ['prompt' => '--GENERO--']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <?=
                    $form->field($model, 'IdServiceCentre')->widget(Select2::class, [
                        'size' => Select2::MEDIUM,
                        'data' => $model->getServiceCentres(),
                        'initValueText' => ($model->IdServiceCentre ? $model->serviceCentre->Name : ''),
                        'options' => [
                            'placeholder' => '--SELECCION FILIAL--',
                        ],
                    ])
                    ?>
                </div>
                <div class="col-md-3">
                    <?=
                    $form->field($model, 'BirthDate')->widget(DatePicker::className(), [
                        'language' => 'es',
                        'readonly' => TRUE,
                        'options' => ['placeholder' => 'Fecha de Nacimiento'],
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                            'autoclose' => true,
                        #'daysOfWeekDisabled' => [0],
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-3">
                    <?=
                    $form->field($model, 'ConversionDate')->widget(DatePicker::className(), [
                        'language' => 'es',
                        'readonly' => TRUE,
                        'options' => ['placeholder' => 'Fecha de Conversión'],
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                            'autoclose' => true,
                        #'daysOfWeekDisabled' => [0],
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-3">
                    <?=
                    $form->field($model, 'BaptismDate')->widget(DatePicker::className(), [
                        'language' => 'es',
                        'readonly' => TRUE,
                        'options' => ['placeholder' => 'Fecha de Bautizo'],
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                            'autoclose' => true,
                        #'daysOfWeekDisabled' => [0],
                        ],
                    ]);
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                <?= $form->field($model, 'IdState')->dropDownList($model->getStates(), []); ?>
                </div>

                <div class="col-md-3" id="deceasedate" style="<?= (!($model->isNewRecord ? $model->IdState ? $model->state->Code == Member::STATUS_DECESASE : false : false) ? 'display:none' : '') ?>">
                    <?=
                    $form->field($model, 'DeceaseDate')->widget(DatePicker::className(), [
                        'language' => 'es',
                        'readonly' => TRUE,
                        'options' => ['placeholder' => 'Fecha de Bautizo'],
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                            'autoclose' => true,
                        #'daysOfWeekDisabled' => [0],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-2">
                <?php if ($model->isNewRecord): ?>
                <div class="box box-default" style="width: 175px;float: right; ">
                <?= Html::img('@web/img/avatar.png', ['alt' => 'Miembro', 'class' => 'user-image', 'id' => 'member-picture', 'style' => 'width: 175px']); ?>
                </div>
                <?php else: ?>
                <div class="box box-default" style="width: 175px;float: right; ">
                    <?= Html::img($model->path ? $model->path : '@web/img/avatar.png', ['id' => 'member-picture', 'alt' => 'Miembro', 'class' => 'user-image', 'style' => 'width: 175px']); ?>
                </div>
                <?= (!$model->isNewRecord) ? $form->field($model, 'photo')->widget(FileInput::class, [
                    'id'=> 'member-photo',
                    'options'=> [
                        'multiple'=> false,
                    ],
                    'pluginOptions' => [
                        'previewFileType' => 'any',
                        'uploadUrl' => $url."/upload"
                        , 'uploadExtraData' => new JsExpression("function(){"
                                . "return {"
                                    . "'".$parentModel."[$attachModel][KeyWord]':'".$modelDetail->KeyWord."',"
                                    . "'".$parentModel."[$attachModel][AttributeName]':'".$modelDetail->AttributeName."',"
                                    . "'".$parentModel."[$attachModel][AttributeValue]':'".$modelDetail->AttributeValue."',"
                                    . "'".$parentModel."[$attachModel][Description]': ''"
                                    . "}"
                                . "}"),
                        'browseLabel' => '',
                        'uploadLabel' => '',
                        'removeLabel' => '',
                        'showPreview' => false,
                    ],
                    'pluginEvents' => [
                        'fileuploaded'=>"function(){ "
                        . " $(this).fileinput('disable').fileinput('enable').fileinput('clear').fileinput('refresh');"
                        . " refreshImage(); "
                        . " }",
                    ],
                ]) : '';?>
        <?php endif; ?>
        </div>
    </div>
</div>
<?= $form->field($model, 'IdAttachmentPicture')->hiddenInput()->label(false); ?>
<?php ActiveForm::end(); ?>

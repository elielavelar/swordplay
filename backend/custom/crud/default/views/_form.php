<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>
<?= "<?php " ?>$form = ActiveForm::begin(); ?>
<div class="panel-body">
<?php foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
        echo "  <div class='row'>\n";
        echo "      <div class='col-md-6'>\n";
        echo "          <?= " . $generator->generateActiveField($attribute) . " ?>\n";
        echo "      </div>\n";
        echo "  </div>\n";
    
    }
} ?>
</div>
<div class="panel-footer">
    <div class="row">
        <div class="col-md-6">
            <span class="pull-right">
                <?= "<?= " ?>Html::submitButton(<?= $generator->generateString('Save') ?>, ['class' => 'btn btn-success']) ?>
            </span>
        </div>
    </div>
</div>
<?= "<?php " ?>ActiveForm::end(); ?>

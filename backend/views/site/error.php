<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        El error detallado ha ocurrido mientras el Sistema estaba procesando su solicitud.
    </p>
    <p>
        Favor, contactar al Administrador si cree que &eacute;ste es un error del Sistema.
    </p>

</div>

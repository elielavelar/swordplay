<?php

use yii\bootstrap\Nav;
use yii\bootstrap\Html;
use webtoolsnz\AdminLte;
use yii\helpers\Url;

$urlHome = Url::home();

$menuItems = [
    ['label' =>'<i class="fa fa-home"></i> Inicio', 'url' => Url::home(true),'encode'=>FALSE],
];
if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => '<i class="fa fa-user"></i>', 'url' => ['/site/login'],'encode'=>FALSE];
} else {
    $menuItems[] = [
        'label'=>'<i class="fa fa-user"></i> '.Yii::$app->user->identity->Username,
        'items'=>[
            ['label'=>'<i class="fa fa-user"></i> Ver Perfil','url' => ['/user/profile'], 'encode'=> FALSE],
            ['label'=>'<i class="fa fa-sign-out"></i> Cerrar Sesión','url' => ['/site/logout'], 'encode'=> FALSE],
        ],
        'encode'=> FALSE,
    ];
}

$user = [];
if(Yii::$app->user->isGuest){
    $user['username'] = 'Invitado';
    $user['user-image'] = Html::img('@web/img/avatar.png', ['alt' => 'Invitado','class'=>'user-image']);
    $user['user-img-circle'] = Html::img('@web/img/avatar.png', ['alt' => 'Invitado','class'=>'img-circle']);
    $user['profile'] = '';
    $url = Url::to('@web/site/login');
    $user['access'] = "<a href='$url'class='btn btn-default btn-flat'>Login</a>";                
} else {
    $user['username'] = Yii::$app->user->getIdentity()->Username;
    $user['user-image'] = Html::img('@web/img/avatar.png', ['alt' => 'Invitado','class'=>'user-image']);
    $user['user-img-circle'] = Html::img('@web/img/avatar.png', ['alt' => 'Invitado','class'=>'img-circle']);
    $user['profile'] = "<a href='#' class='btn btn-default btn-flat'>Profile</a>";
    $user['access'] = "<a href='".Url::to('@web/site/logout')."' class='btn btn-default btn-flat'>Logout (".Yii::$app->user->getIdentity()->Username.")</a>";
}

$this->theme->topMenuItems = $menuItems;

?>

<header class="main-header">
    <?php if ($this->theme->layout == AdminLte\Theme::LAYOUT_SIDEBAR_MINI) { ?>
        <!-- Logo -->
        <a href="<?=$urlHome?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><?= Html::img('@web/img/logo-alt.png', ['alt' => \Yii::$app->name]) ?></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><?= Html::img('@web/img/smalllogo.png', ['alt' => \Yii::$app->name]) ?></span>
        </a>
    <?php } ?>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <?php if ($this->theme->layout == AdminLte\Theme::LAYOUT_SIDEBAR_MINI) { ?>
            <a href="<?=$urlHome?>" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
        <?php } ?>

        <?php if ($this->theme->layout == AdminLte\Theme::LAYOUT_TOP_NAV) { ?>
            <a href="<?=$urlHome?>" class="navbar-brand">
                <?= Html::img('/img/logo.png', ['alt' => \Yii::$app->name]) ?>
            </a>
        <?php } ?>

        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <?= Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => $this->theme->topMenuItems
            ]); ?>
        </div>

    </nav>
</header>
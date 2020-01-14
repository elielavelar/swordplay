<?php

use yii\helpers\Url;
use yii\helpers\Html;
use backend\components\CustomMenu;
use backend\models\Settingsdetail;
use backend\models\Options;
use kartik\widgets\SwitchInput;

if ($this->theme->layout == \webtoolsnz\AdminLte\Theme::LAYOUT_SIDEBAR_MINI) {

    $menuItems = [];
    $submenu = [];
    if (!empty(Yii::$app->session->get('itemsMenu'))) {
        $menuItems = array_merge($menuItems, Yii::$app->session->get('itemsMenu'));
    }
    
    if(!empty(Yii::$app->session->get('subMenu'))){
        $submenu = Yii::$app->session->get('subMenu');
    }
    $this->theme->mainMenuItems = $menuItems;
    $_controller = Yii::$app->controller->id;

    if(isset($submenu[$_controller])){
        $_controller = $submenu[$_controller];
    }
    $itemActive = "@web/" . $_controller;
    ?>
    <aside class="main-sidebar">

        <section class="sidebar">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <?=CustomMenu::widget([
                'options' => ['class' => 'sidebar-menu'],
                'items' => $this->theme->mainMenuItems,
                'route' => $itemActive
            ])
            ?>
        </section>
        <!-- /.sidebar -->
    </aside>

<?php } ?>

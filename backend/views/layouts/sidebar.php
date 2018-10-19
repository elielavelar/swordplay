<?php 
use yii\helpers\Url;
use backend\components\CustomMenu;
use backend\models\Settingsdetail;
use backend\models\Options;

    if ($this->theme->layout == \webtoolsnz\AdminLte\Theme::LAYOUT_SIDEBAR_MINI) { 

    $menuItems = [];

    if(!empty(Yii::$app->session->get('itemsMenu'))){
        $menuItems = array_merge($menuItems, Yii::$app->session->get('itemsMenu'));
    }
    #echo "<pre>";    print_r($menuItems); #die();
    $this->theme->mainMenuItems = $menuItems;
    
    $_controller = Yii::$app->controller->id;
    if($_controller == 'site'){
        $setting = Settingsdetail::find()
                ->joinWith('setting b')
                ->where(['b.KeyWord' => 'General','b.Code'=> Options::DEFAULT_OPTION,'settingsdetail.Code' => Options::DEFAULT_OPTION])
                ->one();
        if(!empty($setting)){
            $_controller = $setting->Value;
        }
    }
    $itemActive = "@web/".$_controller;
    
    #echo $itemActive; #die();
    
?>
<aside class="main-sidebar">

    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <?= CustomMenu::widget([
            'options' => ['class' => 'sidebar-menu'],
            'items' => $this->theme->mainMenuItems,
            'route' => $itemActive
        ]) ?>
    </section>
    <!-- /.sidebar -->
</aside>

<?php } ?>

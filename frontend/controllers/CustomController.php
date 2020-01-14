<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace frontend\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
/**
 * Description of CustomController
 *
 * @author Eliel Avelar <ElielAbisai.AvelarJaimes@muehlbauer.de>
 */
class CustomController extends \yii\web\Controller {
    //put your code here
    public $customactions = [];
    private $auth;
    private $_actionname;
    
    public function actions() {
        $actions = parent::actions();
        return $actions;
    }
    
    public function __construct($id, $module, $config = array()) {
        
        return parent::__construct($id, $module, $config);
    }

    public function setCustomActions($customactions = []){
        $this->customactions = array_merge($this->customactions, $customactions);
    }
    

    public function beforeAction($action) {
        
        if(\Yii::$app->user->isGuest){
            $this->goHome();
        } else {
            if(!in_array($this->action->id, $this->customactions)){
            
            }
            return parent::beforeAction($action);
        }
    }
    
    public function setMessageErrors($errors){
        $message = '';
        if(!empty($errors)){
            foreach ($errors as $error){
                $message  .= (implode("- ", $error)).'<br/>';
            }
        }
        return $message;
    }
    
    public function viewErrors($errors){
        if(!empty($errors)){
            foreach ($errors as $error){
                $message = (implode("- ", $error));
                Yii::$app->session->setFlash('error', $message);
            }
        }
    }
    
}

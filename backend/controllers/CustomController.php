<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace backend\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
/**
 * Description of CustomController
 *
 * @author Eliel Avelar <elielavelar@gmail.com>
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
        
        $this->auth = \Yii::$app->authManager;
        return parent::__construct($id, $module, $config);
    }

    public function setCustomActions($customactions = []){
        $this->customactions = array_merge($this->customactions, $customactions);
    }
    

    public function beforeAction($action) {
        
        if(\Yii::$app->user->isGuest){
            $this->goHome();
        } else {
            $user = \Yii::$app->user->getIdentity();
            if($user->expired){
                return $this->redirect(['site/changepassword']);
            }
            
            if(!in_array($this->action->id, $this->customactions)){
            
            $_controller = $this->auth->getPermission($this->id);
            if(empty($_controller)){
                $_controller = $this->auth->createPermission($this->id);
                $_controller->description = 'Permiso para Controlador '.  $this->id;
                $this->auth->add($_controller);
            } 
            $this->_actionname = $this->id.ucfirst($this->action->id);
            
            $_action = $this->auth->getPermission($this->_actionname);
            if(empty($_action)){
                $_action = $this->auth->createPermission($this->_actionname);
                $_action->description = 'Permiso para Accion '.$this->_actionname;
                $this->auth->add($_action);
                $this->auth->addChild($_controller, $_action);
            }

            #if(!\Yii::$app->user->can($this->_actionname)){
            if(!\Yii::$app->customFunctions->userCan($this->_actionname)){
                $message = 'No posee permisos para la acción: '.$_controller->name.' / '.$this->_actionname;
                throw new ForbiddenHttpException($message);
            } 
//            else {
//                echo "Tiene Acceso a ".$_controller->name.' / '.$_actionname; die();
//            }
        }
            return parent::beforeAction($action);
        }
    }
    
    public function _getActionName(){
        return $this->_actionname;
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

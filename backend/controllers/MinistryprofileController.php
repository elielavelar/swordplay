<?php

namespace backend\controllers;

use Yii;
use backend\models\Ministryprofiles;
use backend\models\MinistryprofilesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use Exception;

/**
 * MinistryprofileController implements the CRUD actions for Ministryprofiles model.
 */
class MinistryprofileController extends Controller
{
    
    public function actionGet(){
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Ministryprofiles();
        try {
            if(\Yii::$app->request->isAjax){
                $data = \Yii::$app->request->post('data');
                $criteria = Json::decode($data, TRUE);
                $model = Ministryprofiles::findOne($criteria);
                if(empty($model)){
                    $message = 'Registro no encontrado';
                    $model->addError('Id', $message);
                    throw new Exception($message, 90001);
                }
                $response = array_merge(['success' =>true], $model->attributes);
            } else {
                $message = 'Formato de Petición incorrecto';
                $model->addError('Id', $message);
                throw new Exception($message, 90000);
            }
        } catch (Exception $ex) {
            $response = [
                'success' => false,
                'message' => $ex->getMessage(),
                'code' => $ex->getCode(),
                'errors' => $model->hasErrors() ? $model->errors : [],
            ];
        }
        return $response;
    }
    
    public function actionSave(){
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Ministryprofiles();
        try {
            if (Yii::$app->request->isAjax) {
                $data = Yii::$app->request->post(StringHelper::basename(Ministryprofiles::class));
                $dttitle = 'Registrado';
                if(!empty($data['Id'])){
                    $model = $this->findModel($data['Id']);
                    $dttitle = 'Actualizado';
                    if($model == NULL){
                        throw new Exception('No se encontró registro', 90001);
                    }
                } 
                $model->attributes = $data;
                if($model->save()){
                    $model->refresh();
                    $response = array_merge(['success'=>true,'title'=>'Cargo '.$dttitle],$model->attributes);
                } else {
                    $message = \Yii::$app->customFunctions->getErrors($model->errors);
                    throw new Exception($message, 90002);
                }
            }
        } catch (Exception $ex) {
            $response = [
                'success' => false,
                'message' => $ex->getMessage(),
                'code' => $ex->getCode(),
                'errors' => $model->hasErrors() ? $model->errors : [],
            ];
        }
        return $response;
    }

    /**
     * Deletes an existing Ministryprofiles model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            $model = $this->findModel($id);
            if(empty($model)){
                throw new Exception('No se encontró registro', 90001);
            }
            $title = 'Cargo';
            $name = !empty($model->CustomName) ? $model->CustomName : ($model->IdProfile ? $model->profile->Name: '');
            $dttitle = 'Eliminado';
            if($model->delete()){
                $response = [
                    'success'=>TRUE,
                    'message'=>$title.' '.$name.' '.$dttitle,
                    'title'=>$title.' '.$dttitle,
                ];
            } else {
                $message = Yii::$app->customFunctions->getErrors($model->errors);
                throw new Exception($message, 90003);
            }
        } catch (Exception $ex){
            $response = [
                'success'=>FALSE,
                'code'=>$ex->getCode(),
                'message'=>$ex->getMessage(),
                'errors'=>$model->errors,
            ];
        }
        return $response;
    }

    /**
     * Finds the Ministryprofiles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ministryprofiles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ministryprofiles::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

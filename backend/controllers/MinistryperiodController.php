<?php

namespace backend\controllers;

use Yii;
use backend\models\Ministryperiods;
use backend\models\MinistryperiodsSearch;
use backend\models\Ministryperiodvoting;
use backend\models\MinistryperiodvotingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\helpers\Json;
use Exception;

/**
 * MinistryperiodController implements the CRUD actions for Ministryperiods model.
 */
class MinistryperiodController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Ministryperiods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MinistryperiodsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ministryperiods model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Ministryperiods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ministryperiods();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Ministryperiods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelDetail = new Ministryperiodvoting();
        $modelDetail->IdMinistryPeriod = $model->Id;
        
        $searchModel = new MinistryperiodvotingSearch();
        $searchModel->IdMinistryPeriod = $model->Id;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('update', [
            'model' => $model, 'modelDetail' => $modelDetail, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing Ministryperiods model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Ministryperiods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ministryperiods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ministryperiods::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionGet(){
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Ministryperiods();
        try {
            if(\Yii::$app->request->isAjax){
                $data = \Yii::$app->request->post('data');
                $criteria = Json::decode($data, TRUE);
                $model = Ministryperiods::findOne($criteria);
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
        $model = new Ministryperiods();
        try {
            if (Yii::$app->request->isAjax) {
                $data = Yii::$app->request->post(StringHelper::basename(Ministryperiods::class));
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
                    $response = array_merge(['success'=>true,'title'=>'Periodo '.$dttitle],$model->attributes);
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
    
    public function actionClose(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $model = new Ministryperiods();
        try {
            if(\Yii::$app->request->isAjax){
                $input = \Yii::$app->request->post('data');
                $criteria = Json::decode($input, true);
                $model = Ministryperiods::findOne($criteria);
                if(!empty($model)){
                    $response = $model->closePeriod();
                    $response['success'] = true;
                } else {
                    throw new Exception('Periodo no encontrado',90001);
                }
            }
        } catch (Exception $ex) {
            $response = [
                'success' => false,
                'message' => $ex->getMessage(),
                'code' => $ex->getCode(),
                'errors' => $model->hasError() ? $model->errors : [],
            ];
        }
        return $response;
    }
}

<?php

namespace backend\controllers;

use Yii;
use backend\models\Ministryperiodvoting;
use backend\models\MinistryperiodvotingSearch;
use backend\models\Ministryperiodvotingcandidates;
use backend\models\MinistryperiodvotingcandidatesSearch;
use backend\models\Ministryvotingballot;
use backend\models\MinistryvotingballotSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use Exception;
/**
 * MinistryperiodvotingController implements the CRUD actions for Ministryperiodvoting model.
 */
class MinistryperiodvotingController extends Controller
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
     * Lists all Ministryperiodvoting models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MinistryperiodvotingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $model  = new Ministryperiodvoting();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Ministryperiodvoting model.
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
     * Creates a new Ministryperiodvoting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ministryperiodvoting();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Ministryperiodvoting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $searchModel = new MinistryperiodvotingcandidatesSearch();
        $searchModel->IdVoting = $model->Id;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        $modelDetail = new Ministryperiodvotingcandidates();
        $modelDetail->IdVoting = $model->Id;

        $searchModelBallot = new MinistryvotingballotSearch();
        $searchModelBallot->IdVoting = $model->Id;
        $dataProviderBallot = $searchModelBallot->search(\Yii::$app->request->queryParams);
        $modelDetailBallot = new Ministryvotingballot();
        $modelDetailBallot->IdVoting = $model->Id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('update', [
            'model' => $model,
            'modelDetail' => $modelDetail, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
            'modelDetailBallot' => $modelDetailBallot, 'searchModelBallot' => $searchModelBallot, 'dataProviderBallot' => $dataProviderBallot,
        ]);
    }

    /**
     * Deletes an existing Ministryperiodvoting model.
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
     * Finds the Ministryperiodvoting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ministryperiodvoting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ministryperiodvoting::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionReport($id){
        $model = $this->findModel($id);
        return $this->render('report', [
            'model' => $model,
        ]);
    }
    
    public function actionGetreportdata(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $model = new Ministryperiodvoting();
        try {
            $data = Yii::$app->request->post(StringHelper::basename(Ministryperiodvoting::class));
            $model = Ministryperiodvoting::findOne($data);
            $result = $model->getElectedCandidates();
        } catch (Exception $ex) {
            $response = [
                'success' => false,
                'message' => $ex->getMessage(),
                'code' => $ex->getCode(),
                'errors' => $model->errors,
            ];
        }
        return $response;
    }
    
    public function actionGet(){
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Ministryperiodvoting();
        try {
            if(\Yii::$app->request->isAjax){
                $data = \Yii::$app->request->post('data');
                $criteria = Json::decode($data, TRUE);
                $model = Ministryperiodvoting::findOne($criteria);
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
        $model = new Ministryperiodvoting();
        try {
            if (Yii::$app->request->isAjax) {
                $data = Yii::$app->request->post(StringHelper::basename(Ministryperiodvoting::class));
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
}

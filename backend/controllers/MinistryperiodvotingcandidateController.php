<?php

namespace backend\controllers;

use Yii;
use backend\models\Ministryperiodvotingcandidates;
use backend\models\MinistryperiodvotingcandidatesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\helpers\Json;
use Exception;

/**
 * MinistryperiodvotingcandidateController implements the CRUD actions for Ministryperiodvotingcandidates model.
 */
class MinistryperiodvotingcandidateController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
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
     * Lists all Ministryperiodvotingcandidates models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new MinistryperiodvotingcandidatesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ministryperiodvotingcandidates model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Ministryperiodvotingcandidates model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Ministryperiodvotingcandidates();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Ministryperiodvotingcandidates model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Ministryperiodvotingcandidates model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Ministryperiodvotingcandidates model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ministryperiodvotingcandidates the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Ministryperiodvotingcandidates::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGet() {
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Ministryperiodvotingcandidates();
        try {
            if(Yii::$app->request->isAjax){
                $input = \Yii::$app->request->post('data');
                $data = Json::decode($input, true);
                $model = Ministryperiodvotingcandidates::findOne(['Id' => $data['Id']]);
                if(empty($model)) {
                    $message = 'Registro no encontrado';
                    throw new Exception($message, 90001);
                } 
                $response = array_merge(['success' => true], $model->attributes);
            } else {
                throw new Exception('Formato de peticion Erróneo', 90000);
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
        $model = new Ministryperiodvotingcandidates();
        try {
            if (Yii::$app->request->isAjax) {
                $data = Yii::$app->request->post(StringHelper::basename(Ministryperiodvotingcandidates::class));
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
                    $response = array_merge(['success'=>true,'title'=>'Candidato '.$dttitle],$model->attributes);
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

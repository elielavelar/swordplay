<?php

namespace backend\controllers;

use Yii;
use common\models\Extendedmodels;
use backend\models\ExtendedmodelSearch;
use common\models\Extendedmodelkeys;
use backend\models\ExtendedmodelkeySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\helpers\Json;
use Exception;

/**
 * ExtendedmodelController implements the CRUD actions for Extendedmodels model.
 */
class ExtendedmodelController extends Controller
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
     * Lists all Extendedmodels models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExtendedmodelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new Extendedmodels();

        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Extendedmodels model.
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
     * Creates a new Extendedmodels model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Extendedmodels();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Extendedmodels model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $modelDetail = new Extendedmodelkeys();
        $modelDetail->IdExtendedModel = $model->Id;
        $searchModel = new ExtendedmodelkeySearch();
        $searchModel->IdExtendedModel = $model->Id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('update', [
            'model' => $model,
            'modelDetail' => $modelDetail,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing Extendedmodels model.
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
     * Finds the Extendedmodels model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Extendedmodels the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Extendedmodels::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionGet(){
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Extendedmodels();
        try {
            if(\Yii::$app->request->isAjax){
                $data = \Yii::$app->request->post('data');
                $criteria = Json::decode($data, TRUE);
                $model = Extendedmodels::findOne($criteria);
                $response = array_merge(['success'=>true],$model->attributes);
            }
        } catch (Exception $exc) {
            $response = [
                'success'=>false,
                'message'=>$exc->getMessage(),
                'code'=>$exc->getCode(),
                'errors' => $model->errors,
            ];
        }
        return $response;
    }
    
    public function actionGetmodels(){
        $response = [
            'results'=> ['id'=> '','text'=> '']
        ];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Extendedmodels();
        try {
            if(\Yii::$app->request->isAjax){
                $data = \Yii::$app->request->get();
                $model->attributes = $data;
                $model->term = $data['q'];
                $response = $model->getModels();
            }
        } catch (Exception $exc) {
            print_r($exc->getMessage());
        }
        return $response;
    }
}

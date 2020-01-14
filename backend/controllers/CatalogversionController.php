<?php

namespace backend\controllers;

use Yii;
use common\models\Catalogversions;
use backend\models\CatalogversionSearch;
use common\models\Catalogdetails;
use backend\models\CatalogdetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\helpers\Json;

/**
 * CatalogversionController implements the CRUD actions for Catalogversions model.
 */
class CatalogversionController extends Controller
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
     * Lists all Catalogversions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CatalogversionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Catalogversions model.
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
     * Creates a new Catalogversions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Catalogversions();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Catalogversions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $searchModel = new CatalogdetailSearch();
        $searchModel->IdCatalogVersion = $model->Id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $modelDetail = new Catalogdetails();
        $modelDetail->IdCatalogVersion = $model->Id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('update', [
            'model' => $model, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider
                , 'modelDetail' => $modelDetail
        ]);
    }

    /**
     * Deletes an existing Catalogversions model.
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
     * Finds the Catalogversions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Catalogversions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Catalogversions::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionSave(){
        $model = new Catalogversions();
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            if (Yii::$app->request->isAjax) {
                $data = Yii::$app->request->post(StringHelper::basename(Catalogversions::class));
                $dttitle = 'Agregado';
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
                    $title = 'Versión';
                    $response = array_merge(['success'=>true,'title'=>$title.' '.$dttitle],$model->attributes);
                } else {
                    $message = Yii::$app->customFunctions->getErrors($model->errors);
                    throw new Exception($message, 90002);
                }
            }
            
        } catch (Exception $ex) {
            $response = [
                'success'=>FALSE,
                'code'=>$ex->getCode(),
                'message'=>$ex->getMessage(),
                'errors'=>$model->errors,
            ];
        }
        return $response;
        #echo json_encode($response);
    }
    
    public function actionGet(){
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            if(\Yii::$app->request->isAjax){
                $data = \Yii::$app->request->post('data');
                $criteria = Json::decode($data, TRUE);
                $option = Catalogversions::findOne($criteria);
                $response = array_merge(['success'=>TRUE], $option->attributes);
            }
        } catch (Exception $exc) {
            $response = [
                'success'=>FALSE,
                'message'=>$exc->getMessage(),
                'code'=>$exc->getCode(),
            ];
        }
        return $response;
    }
}

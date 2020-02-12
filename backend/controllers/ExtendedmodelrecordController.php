<?php

namespace backend\controllers;

use Yii;
use common\models\Extendedmodelrecords;
use backend\models\ExtendedmodelrecordSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\helpers\Json;
use Exception;

/**
 * ExtendedmodelrecordController implements the CRUD actions for Extendedmodelrecords model.
 */
class ExtendedmodelrecordController extends Controller
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
     * Lists all Extendedmodelrecords models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExtendedmodelrecordSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Extendedmodelrecords model.
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
     * Creates a new Extendedmodelrecords model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Extendedmodelrecords();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Extendedmodelrecords model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Extendedmodelrecords model.
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
     * Finds the Extendedmodelrecords model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Extendedmodelrecords the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Extendedmodelrecords::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionGet(){
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Extendedmodelrecords();
        try {
            if(\Yii::$app->request->isAjax){
                $data = \Yii::$app->request->post('data');
                $criteria = Json::decode($data, TRUE);
                $model = Extendedmodelrecords::findOne($criteria);
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
}

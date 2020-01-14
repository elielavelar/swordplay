<?php

namespace backend\controllers;

use Yii;
use backend\models\Ministryservicecentres;
use backend\models\MinistryservicecentresSearch;
use backend\models\Ministryperiods;
use backend\models\MinistryperiodsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use Exception;

/**
 * MinistryservicecentreController implements the CRUD actions for Ministryservicecentres model.
 */
class MinistryservicecentreController extends Controller
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
     * Lists all Ministryservicecentres models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MinistryservicecentresSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ministryservicecentres model.
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
     * Creates a new Ministryservicecentres model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new Ministryservicecentres();
        $model->IdMinistry = $id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->Id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Ministryservicecentres model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $searchModel = new MinistryperiodsSearch();
        $searchModel->IdMinistryServiceCentre = $model->Id;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        
        $modelDetail = new Ministryperiods();
        $modelDetail->IdMinistryServiceCentre = $model->Id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('update', [
            'model' => $model, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'modelDetail' => $modelDetail,
        ]);
    }

    /**
     * Deletes an existing Ministryservicecentres model.
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
     * Finds the Ministryservicecentres model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ministryservicecentres the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ministryservicecentres::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    
    public function actionGetperiodvalues(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $result = ['results'=> ['id'=> '','text'=> '']];
        try {
            $data = \Yii::$app->request->get();
            $model = new Ministryservicecentres();
            $model->attributes = $data;
            $result['results'] = $model->getPeriodValuesArrayList();
        } catch (Exception $ex) {
            #print_r($ex); die();
        }
        return $result;
    }
}

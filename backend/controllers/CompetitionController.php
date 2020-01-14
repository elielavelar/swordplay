<?php

namespace backend\controllers;

use Yii;
use common\models\Competitions;
use backend\models\CompetitionsSearch;
use common\models\Competitionrounds;
use backend\models\CompetitionroundsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use Exception;
/**
 * CompetitionController implements the CRUD actions for Competitions model.
 */
class CompetitionController extends Controller
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
     * Lists all Competitions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompetitionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $model = new Competitions();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Competitions model.
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
     * Creates a new Competitions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Competitions();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Competitions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelDetails = new Competitionrounds();
        $modelDetails->IdCompetition = $model->Id;
        $searchModel = new CompetitionroundsSearch();
        $searchModel->IdCompetition = $model->Id;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('update', [
            'model' => $model,
            'searchModel' => $searchModel,
            'modelDetail' => $modelDetails,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing Competitions model.
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
     * Finds the Competitions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Competitions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Competitions::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionUpload(){
        try {
            $model = new Competitions();
            $model->scenario = Competitions::SCENARIO_UPLOAD;
            if (Yii::$app->request->isPost) {
                $model->uploadFile = UploadedFile::getInstance($model, 'uploadFile');
                if($model->upload()){
                    Yii::$app->session->setFlash('success', 'Datos Cargados Exitosamente');
                } else {
                    $message = \Yii::$app->customFunctions->getErrors($model->errors);
                    throw new Exception($message, 90000);
                }
            }
            $this->redirect(['index']);
        } catch (Exception $exc) {
            echo false;
            print_r($model->errors);
            echo $exc->getMessage();
        }
    }
}

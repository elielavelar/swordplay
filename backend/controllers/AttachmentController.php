<?php

namespace backend\controllers;

use Yii;
use backend\models\Attachments;
use backend\models\AttachmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use Exception;
use yii\helpers\Json;
use yii\web\Response;
use yii\helpers\StringHelper;

/**
 * AttachmentController implements the CRUD actions for Attachments model.
 */
class AttachmentController extends Controller
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
     * Lists all Attachments models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AttachmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Attachments model.
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
     * Creates a new Attachments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Attachments();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Attachments model.
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
     * Deletes an existing Attachments model.
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
            $title = 'Adjunto';
            $name = $model->FileName;
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
     * Finds the Attachments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Attachments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Attachments::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionUpload(){
        try {
            $model = new Attachments();
            if(Yii::$app->request->isAjax){
                $data = Yii::$app->request->post();
                $overwrite = isset($data[StringHelper::basename(Attachments::class)]['overwrite']) ? $data[StringHelper::basename(Attachments::class)]['overwrite']:FALSE;
                $model->load($data);
                if(isset($data['renameFile']) && !empty($data['newName'])){
                    $model->renameFile = TRUE;
                    $model->newName = $data['newName'];
                }
                $model->overwriteFile = $overwrite;
                $model->fileattachment = UploadedFile::getInstance($model, 'fileattachment');
                if($model->save()){
                    Yii::$app->session->setFlash('success', 'Datos Cargados Exitosamente');
                    return TRUE;
                } else {
                    $message = Yii::$app->customFunctions->getErrors($model->errors);
                    throw new Exception($message, 90099);
                }
            }
            
        } catch (Exception $ex) {
             echo $ex->getMessage();
        }
    }
}

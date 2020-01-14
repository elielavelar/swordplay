<?php

namespace backend\controllers;

use Yii;
use common\models\Member;
use backend\models\MemberSearch;
use backend\models\Attachments;
use backend\models\AttachmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\helpers\Json;
use yii\web\UploadedFile;
use Exception;

/**
 * MemberController implements the CRUD actions for Member model.
 */
class MemberController extends Controller
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
     * Lists all Member models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Member();
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Member model.
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
     * Creates a new Member model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Member();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Member model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $searchAttachmentModel = new AttachmentSearch();
        $searchAttachmentModel->KeyWord = StringHelper::basename(Member::class);
        $searchAttachmentModel->AttributeName = 'Id';
        $searchAttachmentModel->AttributeValue = $model->Id;
        $attachmentDataProvider = $searchAttachmentModel->search(Yii::$app->request->queryParams);
        
        $attachmentModel = new Attachments();
        $attachmentModel->KeyWord = StringHelper::basename(Member::class);
        $attachmentModel->AttributeName = 'Id';
        $attachmentModel->AttributeValue = $model->Id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('update', [
            'model' => $model, 'searchAttachmentModel' => $searchAttachmentModel, 'attachmentDataProvider' => $attachmentDataProvider,
            'attachmentModel' => $attachmentModel,
        ]);
    }

    /**
     * Deletes an existing Member model.
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
     * Finds the Member model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Member the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionUpload(){
        try {
            $model = new Attachments();
            if(Yii::$app->request->isAjax){
                $data = Yii::$app->request->post(StringHelper::basename(Member::class));
                $overwrite = isset($data[StringHelper::basename(Attachments::class)]['overwrite']) ? $data[StringHelper::basename(Attachments::class)]['overwrite']:FALSE;
                $model->load($data);
                if(isset($data['renameFile']) && !empty($data['newName'])){
                    $model->renameFile = TRUE;
                    $model->newName = $data['newName'];
                }
                $member = $this->findModel($model->AttributeValue);
                $model->overwriteFile = $overwrite;
                $model->fileattachment = UploadedFile::getInstance($member, 'photo');
                if($model->save()){
                    $model->refresh();
                    $member->IdAttachmentPicture = $model->Id;
                    $member->save();
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
    
    public function actionGet(){
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Member();
        try {
            if(\Yii::$app->request->isAjax){
                $data = \Yii::$app->request->post('data');
                $criteria = Json::decode($data, TRUE);
                $model = Member::findOne($criteria);
                if(empty($model)){
                    $message = 'Registro no encontrado';
                    $model->addError('Id', $message);
                    throw new Exception($message, 90001);
                }
                $response = array_merge(['success' =>true], $model->attributes);
                $response['path'] = $model->path;
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
}

<?php

namespace frontend\controllers;

use Yii;
use backend\models\Securityincident;
use backend\models\SecurityincidentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\State;
use common\models\Type;
use backend\models\Incident;
use backend\models\Securityincidentdetails;
use backend\models\SecurityincidentdetailSearch;
use backend\models\Attachments;
use backend\models\AttachmentSearch;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;

/**
 * SecurityincidentController implements the CRUD actions for Securityincident model.
 */
class SecurityincidentController extends Controller
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
     * Lists all Securityincident models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SecurityincidentSearch();
        $filterDepartment = Yii::$app->customFunctions->userCan(Yii::$app->controller->id.'FilterServicecentre');
        if(!$filterDepartment){
            $searchModel->IdServiceCentre = Yii::$app->getUser()->getIdentity()->IdServiceCentre;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filterDepartment' => $filterDepartment,
        ]);
    }

    /**
     * Displays a single Securityincident model.
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
     * Updates an existing Securityincident model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $searchModel = new SecurityincidentdetailSearch();
        $searchModel->IdSecurityIncident = $model->Id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $modelDetail = new Securityincidentdetails();
        $modelDetail->IdSecurityIncident = $model->Id;
        
        $searchAttachmentModel = new AttachmentSearch();
        $searchAttachmentModel->KeyWord = StringHelper::basename(Securityincident::class);
        $searchAttachmentModel->AttributeName = 'Id';
        $searchAttachmentModel->AttributeValue = $model->Id;
        $attachmentDataProvider = $searchAttachmentModel->search(Yii::$app->request->queryParams);
        
        $attachmentModel = new Attachments();
        $attachmentModel->KeyWord = StringHelper::basename(Securityincident::class);
        $attachmentModel->AttributeName = 'Id';
        $attachmentModel->AttributeValue = $model->Id;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('update', [
            'model' => $model, 'searchModel' => $searchModel
                , 'dataProvider' => $dataProvider, 'modelDetail' => $modelDetail
                , 'searchAttachmentModel' => $searchAttachmentModel
                , 'attachmentDataProvider' => $attachmentDataProvider
                , 'attachmentModel' => $attachmentModel,
        ]);
    }

    /**
     * Finds the Securityincident model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Securityincident the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Securityincident::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

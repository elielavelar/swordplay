<?php

namespace backend\controllers;

use Yii;
use backend\models\Ministries;
use backend\models\MinistriesSearch;
use backend\models\Ministryservicecentres;
use backend\models\MinistryservicecentresSearch;
use backend\models\Ministryprofiles;
use backend\models\MinistryprofilesSearch;
use backend\controllers\CustomController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
/**
 * MinistryController implements the CRUD actions for Ministries model.
 */
class MinistryController extends CustomController
{
    public $customactions = [
        'get', 'getperiodvalues',
    ];
    
    public function getCustomActions(){
        return $this->customactions;
    }
    
    public function setCustomActions($customactions = []) {
        return parent::setCustomActions($this->getCustomActions());
    }
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
     * Lists all Ministries models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Ministries();
        $searchModel = new MinistriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ministries model.
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
     * Creates a new Ministries model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ministries();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Ministries model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $searchModel = new MinistryservicecentresSearch();
        $searchModel->IdMinistry = $model->Id;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        
        $modelDetail = new Ministryservicecentres();
        $modelDetail->IdMinistry = $model->Id;

        $searchModelProfile = new MinistryprofilesSearch();
        $searchModelProfile->IdMinistry = $model->Id;
        $dataProviderProfile = $searchModelProfile->search(\Yii::$app->request->queryParams);
        
        $modelDetailProfile = new Ministryprofiles();
        $modelDetailProfile->IdMinistry = $model->Id;
        $modelDetailProfile->Sort = Ministryprofiles::DEFAULT_SORT_VALUE;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('update', [
            'model' => $model, 'modelDetail' => $modelDetail,
            'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
            'searchModelProfile' => $searchModelProfile, 'dataProviderProfile' => $dataProviderProfile,
            'modelDetailProfile' => $modelDetailProfile,
        ]);
    }

    /**
     * Deletes an existing Ministries model.
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
     * Finds the Ministries model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ministries the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ministries::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

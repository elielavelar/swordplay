<?php

namespace backend\controllers;

use Yii;
use common\models\Catalogdetails;
use backend\models\CatalogdetailSearch;
use common\models\Catalogdetailvalues;
use backend\models\CatalogdetailvaluesSearch;
use backend\controllers\CustomController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CatalogdetailController implements the CRUD actions for Catalogdetails model.
 */
class CatalogdetailController extends CustomController
{
    
    public $customactions = [
        'get',
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
     * Lists all Catalogdetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CatalogdetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Catalogdetails model.
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
     * Creates a new Catalogdetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        if(!$id){
            return $this->goBack();
        }
        $model = new Catalogdetails();
        $model->IdCatalogVersion = $id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->Id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Catalogdetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $searchModel = new CatalogdetailvaluesSearch();
        $searchModel->IdCatalogDetail = $model->Id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $modelDetail = new Catalogdetailvalues();
        $modelDetail->IdCatalogDetail = $model->Id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('update', [
            'model' => $model, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider
                , 'modelDetail' => $modelDetail
        ]);
    }

    /**
     * Deletes an existing Catalogdetails model.
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
     * Finds the Catalogdetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Catalogdetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Catalogdetails::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

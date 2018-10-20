<?php

namespace backend\controllers;

use Yii;
use backend\models\Options;
use backend\models\OptionSearch;
#use backend\controllers\CustomController;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use Exception;

use common\models\Types;

/**
 * OptionController implements the CRUD actions for Options model.
 */
class OptionController extends Controller
{
    /*
    public $customactions = [
        'get','getlist','gethtmllist','save'
    ];
    
    public function getCustomActions(){
        return $this->customactions;
    }
    
    public function setCustomActions($customactions = []) {
        return parent::setCustomActions($this->getCustomActions());
    }
    */
    /**
     * @inheritdoc
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
     * Lists all Options models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Options();
        $model->IdType = Types::findOne(['Code'=>Options::TYPE_MODULE])->Id;
        $model->IdUrlType = Types::findOne(['Code'=>Options::URL_INSIDE])->Id;
        $model->IdParent = NULL;
        $model->RequireAuth = Options::REQUIRE_AUTH_TRUE;
        
        $modelGroup = new Options();
        $modelGroup->IdType = Types::findOne(['Code'=>Options::TYPE_GROUP])->Id;
        $modelGroup->IdUrlType = $model->IdUrlType;
        $modelGroup->IdParent = NULL;
        $modelGroup->RequireAuth = Options::REQUIRE_AUTH_TRUE;
        
        $modelController = new Options();
        $modelController->IdType = Types::findOne(['Code'=>Options::TYPE_CONTROLLER])->Id;
        $modelController->IdUrlType = $model->IdUrlType;
        $modelController->IdParent = NULL;
        $modelController->RequireAuth = Options::REQUIRE_AUTH_TRUE;
        
        $modelAction = new Options();
        $modelAction->IdType = Types::findOne(['Code'=>Options::TYPE_ACTION])->Id;
        $modelAction->IdUrlType = $model->IdUrlType;
        $modelAction->IdParent = NULL;
        $modelAction->ItemMenu = 0;
        $modelAction->RequireAuth = Options::REQUIRE_AUTH_TRUE;
        
        $modelPermission = new Options();
        $modelPermission->IdType = Types::findOne(['Code'=>Options::TYPE_PERMISSION])->Id;
        $modelPermission->IdUrlType = $model->IdUrlType;
        $modelPermission->IdParent = NULL;
        $modelPermission->ItemMenu = 0;
        $modelPermission->RequireAuth = Options::REQUIRE_AUTH_TRUE;
        
        $searchModel = new OptionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'modelGroup' => $modelGroup,
            'modelController' => $modelController,
            'modelAction' => $modelAction,
            'modelPermission' => $modelPermission,
        ]);
    }

    /**
     * Displays a single Options model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Options model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Options();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Options model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Options model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            $model = $this->findModel($id);
            $title = $model->IdType ? $model->type->Name:'Opción';
            $name = $model->Name;
            $dttitle = 'Eliminado';
            if($model->delete()){
                $response = [
                    'success'=>TRUE,
                    'message'=>$title.' '.$name.' '.$dttitle,
                    'title'=>$title.' '.$dttitle,
                ];
            } else {
                $message = $this->setMessageErrors($model->errors);
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
     * Finds the Options model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Options the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Options::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    public function actionSave(){
        $model = new Options();
        $response = [];
        #\Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            if (Yii::$app->request->isAjax) {
                $data = Yii::$app->request->post('Options');
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
                    $title = $model->IdType ? $model->type->Name:'Opción';
                    $response = array_merge(['success'=>true,'title'=>$title.' '.$dttitle],$model->attributes);
                } else {
                    #$message = $this->setMessageErrors($model->errors);
                    $message = $this->_getErrors($model->errors);
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
        #return $response;
        echo json_encode($response);
    }
    
    public function actionGet(){
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            if(\Yii::$app->request->isAjax){
                $data = \Yii::$app->request->post('data');
                $criteria = Json::decode($data, TRUE);
                $option = Options::findOne($criteria);
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
    
    public function actionGetlist(){
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            if(\Yii::$app->request->isAjax){
                $data = \Yii::$app->request->get('data');
                $criteria = Json::decode($data, TRUE);
                $term = $criteria['term'];
                unset($criteria["term"]);
                $options = Options::find()->where($criteria)
                        ->andFilterWhere(['like','Name',$term])
                        ->select(['Id as id','Name as label'])
                        ->asArray()
                        ->all();
                $response = [
                    'success'=>TRUE,
                    'list'=>$options,
                ];
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
    
    public function actionGethtmllist(){
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            $list = Options::getHtmlList();
                $response = [
                    'success'=>TRUE,
                    'list'=>$list,
                ];
        } catch (Exception $exc) {
            $response = [
                'success'=>FALSE,
                'message'=>$exc->getMessage(),
                'code'=>$exc->getCode(),
            ];
        }
        return $response;
    }
    
    private function _getErrors($errors){
        $errorMessage = "";
        if(!empty($errors)){
            foreach ($errors as $error){
                $message = (implode("- ", $error));
                #Yii::$app->session->setFlash('error', $message);
                $errorMessage .= $message."<br/>";
            }
        }
        return $errorMessage;
    }
    
}

<?php

namespace frontend\controllers;

use Yii;
use backend\models\Ministryvotingballot;
use backend\models\Ministryperiodvoting;
use backend\models\MinistryvotingballotSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use webtoolsnz\AdminLte\FlashMessage;
use yii\web\Response;
use yii\helpers\Json;
use Exception;

/**
 * MinistryvotingballotController implements the CRUD actions for Ministryvotingballot model.
 */
class MinistryvotingballotController extends Controller
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
     * Lists all Ministryvotingballot models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MinistryvotingballotSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ministryvotingballot model.
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
     * Creates a new Ministryvotingballot model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $this->_validateUser();
        
        $voting = Ministryperiodvoting::findOne(['Id' => $id]);
        if(empty($voting)){
            $this->setMessage('Error!', 'Proceso de Votación no Encontrado', FlashMessage::TYPE_ERROR);
            $this->redirect(['ministryperiodvoting/index']);
        }
        $model = new Ministryvotingballot();
        $model->IdVoting = $id;
        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Ministryvotingballot model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        try {
            if(empty($model)){
                $message = 'Registro no encontrado';
                #$model->addError('Id', $message);
                throw new Exception($message, 90001);
            } elseif($model->IdState ? $model->state->Code == Ministryvotingballot::STATUS_PROCESSED : false){
                $message = 'Boleta '.$model->Number.' ya fue Procesada Anteriormente';
                $model->addError('Number', $message);
                throw new Exception($message, 90002);
            } elseif($model->IdState ? $model->state->Code == Ministryvotingballot::STATUS_ANNULED : false){
                $message = 'Boleta '.$model->Number.' se encuentra '.$model->state->Name;
                $model->addError('Number', $message);
                throw new Exception($message, 90002);
            }
            $model->getCandidates();
            $model->getProfiles();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->Id]);
            }
        } catch (Exception $ex) {
            $route = $ex->getCode() == 90001 ? ['ministryperiodvoting/index'] : ['create','id' => $model->IdVoting];
            $this->redirect($route);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Ministryvotingballot model.
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
     * Finds the Ministryvotingballot model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ministryvotingballot the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ministryvotingballot::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    
    public function actionGet(){
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Ministryvotingballot();
        try {
            
                $data = \Yii::$app->request->post(StringHelper::basename(Ministryvotingballot::class));
                $_model = Ministryvotingballot::findOne($data);
                if(empty($_model)){
                    $message = 'Registro no encontrado';
                    $model->addError('Id', $message);
                    throw new Exception($message, 90001);
                } elseif($_model->IdState ? $_model->state->Code == Ministryvotingballot::STATUS_PROCESSED : false){
                    $message = 'Boleta '.$model->Number.' ya fue Procesada Anteriormente';
                    $model->addError('Number', $message);
                    throw new Exception($message, 90002);
                } elseif($_model->IdState ? $_model->state->Code == Ministryvotingballot::STATUS_ANNULED : false){
                    $message = 'Boleta '.$model->Number.' se encuentra '.$_model->state->Name;
                    $model->addError('Number', $message);
                    throw new Exception($message, 90002);
                }
                $response = array_merge(['success' =>true], $_model->attributes);
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
    
    public function actionRegister(){
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Ministryvotingballot();
        try {
            if (Yii::$app->request->isAjax) {
                $input= Yii::$app->request->post('data');
                $data = Json::decode($input, true);
                $dttitle = 'Registrado';
                if(!empty($data['Id'])){
                    $model = $this->findModel($data['Id']);
                    $dttitle = 'Actualizado';
                    if($model == NULL){
                        throw new Exception('No se encontró registro', 90001);
                    } elseif($model->IdState ? $model->state->Code == Ministryvotingballot::STATUS_PROCESSED : false){
                        $message = 'Boleta '.$model->Number.' ya fue Procesada Anteriormente';
                        $model->addError('Number', $message);
                        throw new Exception($message, 90002);
                    } elseif($model->IdState ? $model->state->Code == Ministryvotingballot::STATUS_ANNULED : false){
                        $message = 'Boleta '.$model->Number.' se encuentra '.$model->state->Name;
                        $model->addError('Number', $message);
                        throw new Exception($message, 90002);
                    }
                } else {
                    $message = 'No se Definio Boleta';
                    $model->addError('Number',$message);
                    throw new Exception($message, 90002);
                }
                $model->votes = isset($data['votes']) ? $data['votes'] : [];
                unset($data['votes']);
                $model->attributes = $data;
                if($model->save()){
                    $model->refresh();
                    $response = array_merge(['success'=>true,'title'=>'Boleta '.$dttitle],$model->attributes);
                } else {
                    $message = \Yii::$app->customFunctions->getErrors($model->errors);
                    throw new Exception($message, 90002);
                }
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
    
    public function actionVoidballot($id){
        try {
            $model = $this->findModel($id);
            $response = $model->voidBallot();
            Yii::$app->session->setFlash('success', $response['message']);
        } catch (Exception $ex) {
            Yii::$app->session->setFlash('error', $ex->getMessage());
        }
        $this->redirect(['create','id' => $model->IdVoting ]);
    }
    
    private function setMessage($title = 'Alerta', $message = '', $type = null){
        $flassMessage = new FlashMessage();
        $flassMessage->title = $title;
        $flassMessage->type = $type ? $type : FlashMessage::TYPE_INFO;
        $flassMessage->message = $message;
        Yii::$app->session->setFlash($title, $flassMessage);
    }
    
    private function _validateUser(){
        if(Yii::$app->user->isGuest){
            $this->redirect(['site/login']);
        }
    }
}

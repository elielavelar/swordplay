<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use backend\models\UserSearch;
use backend\models\Useroptions;
use common\models\Userpreferences;
#use yii\web\Controller;
use backend\controllers\CustomController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use Exception;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends CustomController
{
    public $customactions = [
        'profile','getrandompass','getfilteruser'
    ];
    
    public function getCustomActions(){
        return $this->customactions;
    }
    
    public function setCustomActions($customactions = []) {
        return parent::setCustomActions($this->getCustomActions());
    }
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionProfile()
    {
        if($this->validateUser()){
            $model = $this->findModel(\Yii::$app->user->getIdentity()->getId());
            $model->getSettings();
            $model->setExpirationDate();
            if ($model->load(Yii::$app->request->post())) {
                $post = Yii::$app->request->post(StringHelper::basename($model->className()));
                $model->_password = !empty($post['_password']) ? $post['_password']:NULL;
                $model->_passwordconfirm = !empty($post['_passwordconfirm']) ? $post['_passwordconfirm']:NULL;
                
                if($model->save()){
                    return $this->redirect(['profile']);
                } else {
                    $message = \Yii::$app->customFunctions->getErrors($model->errors);
                    Yii::$app->getSession()->setFlash('warning',$message);
                }
            } else {
                return $this->render('profile', [
                    'model' => $model,
                ]);
            }
        } else {
            $this->goHome();
        }
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->setExpirationDate();
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = User::SCENARIO_CREATE;
        $model->_password = \Yii::$app->customFunctions->getRandomPass();
        $model->_passwordconfirm = $model->_password;

        if ($model->load(Yii::$app->request->post())) {
            $model->generateAuthKey();
            if(!$model->save()){
                $this->viewErrors($model->errors);
                return $this->render('create', [
                    'model' => $model,
                ]);
            } else {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $useroptions = new Useroptions();
        $useroptions->list = Useroptions::getHtmlList(['IdUser' => $model->id]);
        
        $modelDetail = new Useroptions();
        $modelDetail->IdUser= $model->Id;
        
        $set = \Yii::$app->customFunctions->userCan('general');
        
        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post(StringHelper::basename($model->className()));
            $model->_password = !empty($post['_password']) ? $post['_password']:NULL;
            $model->_passwordconfirm = !empty($post['_passwordconfirm']) ? $post['_passwordconfirm']:NULL;
            if(isset($post[StringHelper::basename(Useroptions::className())])){
                $model->usersetting = $post[StringHelper::basename(Useroptions::className())];
            } else {
                $model->usersetting = [];
                $model->_emptyUserOptions = TRUE;
            }
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $message = \Yii::$app->customFunctions->getErrors($model->errors);
                Yii::$app->getSession()->setFlash('warning',$message);
            }
            
        } else {
            $model->setExpirationDate();
            return $this->render('update', [
                'model' => $model, 'searchModel'=> $useroptions, 'modelDetail'=> $modelDetail,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionGetrandompass(){
        $response = [
            'success'=> FALSE,
        ];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            $response = [
                'success'=> TRUE,
                #'password'=> \Yii::$app->customFunctions->getRandomPass(NULL, 12),
                'password'=> \Yii::$app->getSecurity()->generateRandomString(),
            ];
        } catch (Exception $ex) {
            $response = [
                'success'=>FALSE,
                'message'=>$ex->getMessage(),
                'code'=>$ex->getCode(),
            ];
        }
        return $response;
    }
    
    private function validateUser($id = NULL){
        if(Yii::$app->user->isGuest){
            Yii::$app->getSession()->setFlash('error','Usuario sin Sesión');
            $this->redirect(['site/login']);
        } else {
            $user = Yii::$app->user->getIdentity();
            if($id != NULL && $id != $user->getId()){
                Yii::$app->getSession()->setFlash('error','Credenciales de usuario no coinciden!');
                $this->goHome();
            } else {
                return TRUE;
            }
        }
    }
    
    public function actionGetfilteruser($q = NULL, $idservicecentre = NULL){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $result = [
            'results'=> ['id'=> '','text'=> '']
        ];
        if(!empty($q)){
            $idservicecentre = empty($idservicecentre) ? NULL: $idservicecentre;
            $users = User::find()
                    ->select(["id","CONCAT(FirstName,' ',LastName) as text"])
                    ->where(['like',"CONCAT(FirstName,' ',LastName)", $q])
                    ->orWhere(['like',"username", $q])
                    ->andWhere("(:service IS NULL OR :service = idservicecentre )", [':service'=> $idservicecentre])
                    ->asArray()
                    ->all();
            $result['results']= $users;
        }
        return $result;
    }
}

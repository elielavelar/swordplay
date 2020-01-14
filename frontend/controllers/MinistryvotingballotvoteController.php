<?php

namespace frontend\controllers;
use Yii;
use backend\models\Ministryvotingballotvote;
use backend\models\MinistryvotingballotvoteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use Exception;

/**
 * MinistryvotingballotvoteController implements the CRUD actions for Ministryvotingballotvote model.
 */
class MinistryvotingballotvoteController extends Controller
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
     * Finds the Ministryvotingballotvote model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ministryvotingballotvote the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ministryvotingballotvote::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionGetvotes(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        try {
            if(Yii::$app->request->isAjax){
                $input = Yii::$app->request->post('data');
                $data = Json::decode($input, true);
                $model = new Ministryvotingballotvote();
                $model->IdVoting = $data['Id'];
                $result = $model->getTableVotes();
                $response = [
                    'success' => true,
                    'result' => $result,
                ];
            }
        } catch (Exception $ex) {
            $response = [
                'success' => false, 
                'message' => $ex->getMessage(),
                'code' => $ex->getCode(),
                'errors' => [],
            ];
        }
        return $response;
    }
}

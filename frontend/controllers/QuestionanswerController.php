<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Questionanswers;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use Exception;
use yii\web\Response;
use yii\helpers\Json;


/**
 * Description of QuestionanswerController
 *
 * @author avelare
 */
class QuestionanswerController extends Controller {
    
    public function actionGet() {
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            if (\Yii::$app->request->isAjax) {
                $data = \Yii::$app->request->post('data');
                $criteria = Json::decode($data, TRUE);
                $option = Questionanswers::findOne($criteria);
                $response = array_merge(['success' => TRUE], $option->attributes);
            }
        } catch (Exception $exc) {
            $response = [
                'success' => FALSE,
                'message' => $exc->getMessage(),
                'code' => $exc->getCode(),
            ];
        }
        return $response;
    }
    
}

<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Roundquestions;
use common\models\State;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use Exception;
use yii\web\Response;
use yii\helpers\Json;

/**
 * Description of RoundquestionController
 *
 * @author avelare
 */
class RoundquestionController extends Controller {

    public function actionSave() {
        $model = new Roundquestions();
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            if (Yii::$app->request->isAjax) {
                $post = Yii::$app->request->post('data');
                $data = Json::decode($post, true);
                if (empty($data['id'])) {
                    throw new Exception('No se recibió Número de Pregunta', 90001);
                }
                $model = $this->findModel($data['id']);
                if (empty($model)) {
                    throw new Exception('No se encontró Pregunta', 90002);
                }
                $state = State::findOne(['KeyWord' => StringHelper::basename(Roundquestions::class), 'Code' => $data['code']]);
                $model->IdState = $state->Id;
                if ($model->save()) {
                    $message = NULL;
                    $close = FALSE;
                    if (isset($data['code'])) {
                        switch ($data['code']) {
                            case 'ANU':
                                $message = "Pregunta Anulada Exitosamente";
                                $close = TRUE;
                                break;
                            case 'RST':
                                $message = NULL;
                                $close = FALSE;
                                break;
                            default :
                                $message = NULL;
                                $close = FALSE;
                                break;
                        }
                    }
                    $rs = array_merge(['success' => true], $model->attributes);
                    $response = $message != NULL ? array_merge(['message' => $message, 'close' => $close], $rs) : $rs;
                } else {
                    $message = Yii::$app->customFunctions->getErrors($model->errors);
                    throw new Exception($message, 90002);
                }
            }
        } catch (Exception $ex) {
            $response = [
                'success' => FALSE,
                'code' => $ex->getCode(),
                'message' => $ex->getMessage(),
                'errors' => $model->errors,
            ];
        }
        return $response;
    }

    public function actionGet() {
        $response = [];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            if (\Yii::$app->request->isAjax) {
                $data = \Yii::$app->request->post('data');
                $criteria = Json::decode($data, TRUE);
                $option = Roundquestions::findOne($criteria);
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

    /**
     * Finds the Competitions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Competitions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Roundquestions::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}

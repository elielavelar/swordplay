<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\Competitionrounds;
use common\models\Roundquestions;
use backend\models\RoundquestionsSearch;

use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use Exception;
use yii\web\Response;
use yii\helpers\Json;

/**
 * Description of CompetitionroundController
 *
 * @author avelare
 */

class CompetitionroundController extends Controller {
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
     * Displays a single Rounds model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        #$query = new Query;
        $model = $this->findModel($id);
        $query = Roundquestions::find()->where(['IdRound'=>$model->Id])
                ->orderBy(['Sort' => SORT_ASC])
                ->all();
        return $this->render('view', [
            'model' => $model,
            'modelSearch'=>$query,
        ]);
    }
    
    /**
     * Finds the Competitions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Competitions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Competitionrounds::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

<?php

namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\CadastralForm;

use common\components\CadastralNumbers;
use common\models\Plot;
use common\models\CommonParser;
use yii\data\ArrayDataProvider;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * Displays homepage.
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->post('CadastralForm');
        $dataCadastralNumbers = array();
        $dataProvider = array();
        if (!empty($params)) {

            $componentCadastralNumbers = \Yii::createObject([
                'class' => CadastralNumbers::class
            ], [new Plot(), 'http://pkk.bigland.ru/api/test/plots']);

            $modelParser = \Yii::createObject([
                'class' => CommonParser::class
            ], [$componentCadastralNumbers]);

            $dataCadastralNumbers = $modelParser->parse($params['cadastralNumber']);

            $dataProvider = new ArrayDataProvider([
                'allModels' => $dataCadastralNumbers,
            ]);
        }

        return $this->render('index', ['modelForm' => new CadastralForm, 'dataCadastralNumbers' => $dataCadastralNumbers, 'dataProvider' => $dataProvider]);
    }
}

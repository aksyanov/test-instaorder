<?php

namespace app\controllers;

use app\components\api\instaorder\exceptions\ResponseErrorExceptions;
use app\components\api\instaorder\exceptions\ResponseNotSuccessExceptions;
use app\models\forms\CreateOrderForm;
use app\models\forms\InstaProfileForm;
use app\models\searches\OrdersSearch;
use app\models\Services;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'order' => ['post'],
                    'create-order' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $instaProfileForm = new InstaProfileForm();

        return $this->render('index', [
            'instaProfileForm' => $instaProfileForm
        ]);
    }

    /**
     * @return array
     */
    public function actionOrderValidate(): array
    {
        $model = new InstaProfileForm();
        if($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        // TODO: доделать кейс когда форма не заполнена
        return [];
    }

    /**
     * @return string
     * @throws \app\components\api\instagram\exceptions\ResponseErrorExceptions
     * @throws \yii\httpclient\Exception
     */
    public function actionOrder()
    {
        $model = new InstaProfileForm();
        if($model->load(Yii::$app->request->post()) && $model->validate()) {
            $profile = $model->getProfile();
            if (!$profile->isEmpty) {

                $services = Services::find()->all();
                $servicesForm = [];
                foreach ($services as $service) {
                    $servicesForm[$service->id] = "{$service->name} ({$service->rate} руб. за 1000)";
                }
                $createOrderFrom = new CreateOrderForm();
                $createOrderFrom->profileURL = $model->profileURL;
                $createOrderFrom->avatar_base64 = $profile->avatarBase64;

                return $this->renderAjax('order', [
                    'createOrderForm' => $createOrderFrom,
                    'services' => $servicesForm,
                    'name' => $profile->name,
                    'username' => $profile->username,
                    'avatar_base64' => $profile->avatarBase64,
                ]);
            }
        }

        return $this->renderPartial('not_found_profile');
    }

    /**
     * @return array
     * @throws ResponseErrorExceptions
     * @throws ResponseNotSuccessExceptions
     */
    public function actionCreateOrderValidate(): array
    {
        $model = new CreateOrderForm();
        if($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    /**
     * @return Response
     * @throws ResponseErrorExceptions
     * @throws ResponseNotSuccessExceptions
     * @throws \yii\base\Exception
     */
    public function actionCreateOrder(): Response
    {
        $model = new CreateOrderForm();
        if($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;

            if ($model->validate() && $model->createOrder()) {
                Yii::$app->session->setFlash('success', Yii::t("app","success_create_order"));
                return $this->redirect(Url::to(['site/orders']));
            }
            else {
                // TODO: стоит сделать более красивую обработку валидации
                Yii::$app->session->setFlash('error', Yii::t("app","error_create_order"));
                return $this->goHome();
            }
        }
    }

    public function actionOrders(): string
    {
        $sessionOrderIDs = Yii::$app->session->get('orders', []);

        $searchModel = new OrdersSearch();
        $searchModel->ids = $sessionOrderIDs;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('orders', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}

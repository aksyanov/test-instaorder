<?php

namespace app\models\forms;

use app\components\api;
use app\components\services\GetInstagramProfileService;
use app\models\Orders;
use app\models\Services;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\httpclient\Exception;

class CreateOrderForm extends Model
{
    /* @var int */
    public $serviceId;
    /* @var string */
    public $profileURL;
    /* @var string */
    public $avatar_base64;
    /* @var int */
    public $quantity;

    public function rules(): array
    {
        return [
            [['serviceId', 'profileURL', 'quantity'], 'required'],
            [['serviceId', 'quantity'], 'integer'],
            [['profileURL', 'avatar_base64'], 'string'],
            ['quantity', 'validateQuantity']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'serviceId' => Yii::t("app","services"),
            'quantity' => Yii::t("app","quantity"),
        ];
    }

    public function validateQuantity($attribute, $params): bool
    {
        if (empty($this->serviceId)) {
            $this->addError($attribute, Yii::t("app","choose_service"));
            return false;
        }

        $service = Services::findOne($this->serviceId);
        if ($service == null) {
            $this->addError($attribute, Yii::t("app","choose_service"));
            return false;
        }

        if ($this->quantity < $service->min || $this->quantity > $service->max) {
            $this->addError($attribute, Yii::t("app","input_quantity_until", [$service->min, $service->max]));
            return false;
        }

        return true;
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     * @throws api\instaorder\exceptions\ResponseErrorExceptions
     * @throws api\instaorder\exceptions\ResponseNotSuccessExceptions
     */
    public function createOrder(): bool
    {
        $instaOrderApi = new api\instaorder\InstaOrderApi();
        $service = Services::findOne($this->serviceId);
        try {
            $order = $instaOrderApi->createOrder(
                $service->service_id,
                $this->profileURL,
                $this->quantity,
                Yii::$app->security->generateRandomString(20)
            );

        } catch (Exception $e) {
            return false;
        }

        $orderModel = new Orders();
        $orderModel->quantity = $this->quantity;
        $orderModel->service_id = $this->serviceId;
        $orderModel->ext_id = $order->orderId;
        $orderModel->currency = $order->currency;
        $orderModel->link = $this->profileURL;
        $orderModel->avatar_base64 = $this->avatar_base64;

        try {
            $extOrderEntity = $instaOrderApi->getOrder($order->orderId);
            $orderModel->remains = $extOrderEntity->remains;
            $orderModel->charge = $extOrderEntity->charge;
            $orderModel->status = $extOrderEntity->status;
        } catch (\Exception $exception) {
            $orderModel->remains = $this->quantity;
            $orderModel->charge = 0;
            $orderModel->status = 'new';
        }

        $orderModel->save();

        $session = Yii::$app->session;
        $orders = $session->get('orders', []);
        $orders[] = $orderModel->id;
        $session->set('orders', $orders);

        return true;
    }
}

<?php

namespace app\components\api\instaorder;

use app\components\api\instaorder\entities\Order;
use app\components\api\instaorder\entities\Service;
use app\components\enums\HttpEnum;
use app\components\helpers\HttpHelper;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

/**
 * Class InstaOrderApi
 * @package app\components\api\instaorder
 */
class InstaOrderApi
{
    const BASE_URL = "http://45.147.176.76:8910/v1/just";
    /* @var string */
    public string $apiKey = '911863916d19759ccc873d427e563f223f0ee099de9b47997784eb9aeb03e06d';
    /* @var string */
    public string $lastErrorMessage = "";
    /* @var null|array|string */
    public $lastResponse = null;

    /**
     * InstaOrderApi constructor.
     * @param null $apiKey
     */
    public function __construct($apiKey = null)
    {
        $this->apiKey = $apiKey ?? $this->apiKey;
    }

    /**
     * @param string $action
     * @param string $method
     * @param array $data
     * @return array|string
     * @throws \yii\httpclient\Exception
     * @throws exceptions\ResponseErrorExceptions
     * @throws exceptions\ResponseNotSuccessExceptions
     */
    public function request(string $action, string $method = HttpEnum::METHOD_GET, array $data = [], ?string $format = null) {

        $url = self::BASE_URL.$action;
        $requiredUrlData = [
            'key' => $this->apiKey,
        ];
        $url.= strpos($url, '?') === false ? '?' : '';
        $url.= '&' . http_build_query($requiredUrlData);

        // Здесь не делаем кеширования, т.к. я не знаю на сколько часто меняется список товаров, цены и прочее
        $response = HttpHelper::request(
            $url,
            $method,
            $data,
            [],
            [],
            $format
        );
        $this->lastResponse = $response;

        if(!is_array($response)) {
            throw new exceptions\ResponseErrorExceptions('Response is not array');
        }

        if(isset($response['error'])) {
            throw new exceptions\ResponseNotSuccessExceptions($this->lastErrorMessage);
        }

       return $response;
    }

    /**
     * @return Service[]
     * @throws \yii\httpclient\Exception
     * @throws exceptions\ResponseErrorExceptions
     * @throws exceptions\ResponseNotSuccessExceptions
     */
    public function getServices(): array
    {
        $services = $this->request('?action=services');

        $serviceEntities = [];
        foreach ($services as $service) {
            $serviceEntity = new Service();
            $serviceEntity->service = ArrayHelper::getValue($service, 'service');
            $serviceEntity->name = ArrayHelper::getValue($service, 'name');
            $serviceEntity->type = ArrayHelper::getValue($service, 'type');
            $serviceEntity->category = ArrayHelper::getValue($service, 'category');
            $serviceEntity->rate = ArrayHelper::getValue($service, 'rate');
            $serviceEntity->min = ArrayHelper::getValue($service, 'min');
            $serviceEntity->max = ArrayHelper::getValue($service, 'max');
            $serviceEntity->dripfeed = ArrayHelper::getValue($service, 'dripfeed');
            $serviceEntity->average_time = ArrayHelper::getValue($service, 'average_time');
            $serviceEntities[] = $serviceEntity;
        }

        return $serviceEntities;
    }

    /**
     * @param int $serviceId
     * @param string $profileURL
     * @param int $quantity
     * @param string $operationId
     * @return Order
     * @throws \yii\httpclient\Exception
     * @throws exceptions\ResponseErrorExceptions
     * @throws exceptions\ResponseNotSuccessExceptions
     */
    public function createOrder(int $serviceId, string $profileURL, int $quantity, string $operationId): Order
    {
        $order = $this->request(
            '/add?action=add',
            HttpEnum::METHOD_POST,
            [
                'link' => $profileURL,
                'quantity' => $quantity,
                'service' => $serviceId,
                'operation_id' => $operationId,
            ],
            Client::FORMAT_JSON
        );

        $orderEntity = new Order();
        $orderEntity->balance = $order['balance'];
        $orderEntity->currency = $order['currency'];
        $orderEntity->orderId = $order['order'];

        return $orderEntity;
    }

    /**
     * @param int $id
     * @return Order
     * @throws \yii\httpclient\Exception
     * @throws exceptions\ResponseErrorExceptions
     * @throws exceptions\ResponseNotSuccessExceptions
     */
    public function getOrder(int $id): Order
    {
        $order = $this->request('', HttpEnum::METHOD_GET, [
            'action' => 'status',
            'order' => $id,
        ]);

        $orderEntity = new Order();
        $orderEntity->orderId = $id;
        $orderEntity->status = $order['status'];
        $orderEntity->remains = $order['remains'];
        $orderEntity->charge = $order['charge'];
        $orderEntity->currency = $order['currency'];

        return $orderEntity;
    }
}
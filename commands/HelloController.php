<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\components\api\instaorder\InstaOrderApi;
use app\models\Services;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use yii\httpclient\Exception;

class HelloController extends Controller
{
    /**
     * Загружает сервисы
     *
     * @return int
     * @throws \app\components\api\instaorder\exceptions\ResponseErrorExceptions
     * @throws \app\components\api\instaorder\exceptions\ResponseNotSuccessExceptions
     */
    public function actionLoadServices(): int
    {
        try {
            $services = (new InstaOrderApi())->getServices();
        } catch (Exception $e) {
            return ExitCode::UNAVAILABLE;
        }

        $serviceModels = Services::find()->indexBy('service_id')->all();
        foreach ($services as $service) {

            Console::output("Проверка {$service->name}");

            if (!isset($serviceModels[$service->service])) {
                Console::output("Создаем");
                $serviceModel = new Services();
                $serviceModel->service_id = $service->service;
            }
            else {
                $serviceModel = $serviceModels[$service->service];
            }

            $serviceModel->name = $serviceModel->name == $service->name ? $serviceModel->name : $service->name;
            $serviceModel->type = $serviceModel->type == $service->type ? $serviceModel->type : $service->type;
            $serviceModel->category = $serviceModel->category == $service->category ? $serviceModel->category : $service->category;
            $serviceModel->rate = $serviceModel->rate == $service->rate ? $serviceModel->rate : $service->rate;
            $serviceModel->min = $serviceModel->min == $service->min ? $serviceModel->min : $service->min;
            $serviceModel->max = $serviceModel->max == $service->max ? $serviceModel->max : $service->max;
            $serviceModel->dripfeed = $serviceModel->dripfeed == $service->dripfeed ? $serviceModel->dripfeed : $service->dripfeed;
            $serviceModel->average_time = $serviceModel->average_time == $service->average_time ? $serviceModel->average_time : $service->average_time;

            $serviceModel->save();
        }

        return ExitCode::OK;
    }
}

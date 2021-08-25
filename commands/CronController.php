<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\components\api\instaorder\InstaOrderApi;
use app\models\Orders;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Expression;
use yii\helpers\Console;
use yii\httpclient\Exception;

class CronController extends Controller
{
    /**
     * @return int
     * @throws \app\components\api\instaorder\exceptions\ResponseErrorExceptions
     * @throws \app\components\api\instaorder\exceptions\ResponseNotSuccessExceptions
     */
    public function actionUpdateOrders(): int
    {
        $orders = Orders::find()
            ->where([
                'status' => Orders::STATUS_PENDING
            ])
            ->all();

        foreach ($orders as $order) {
            Console::output("Check {$order->id}");

            try {
                $orderStatus = (new InstaOrderApi())->getOrder($order->ext_id);
            } catch (Exception $e) {
                continue;
            }

            $order->status = $order->status == $orderStatus->status ? $order->status : $orderStatus->status;
            $order->remains = $order->remains == $orderStatus->remains ? $order->remains : $orderStatus->remains;
            $order->charge = $order->charge == $orderStatus->charge ? $order->charge : $orderStatus->charge;
            $order->updated_at = new Expression('NOW()');
            $order->save();
        }

        return ExitCode::OK;
    }
}

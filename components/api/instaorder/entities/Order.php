<?php

namespace app\components\api\instaorder\entities;

class Order
{
    public int $orderId;
    public float $balance;
    public string $currency;
    public bool $isEmpty = false;
    public $startCount = null;
    public string $status;
    public int $remains;
    public float $charge;
}
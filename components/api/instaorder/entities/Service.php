<?php

namespace app\components\api\instaorder\entities;

class Service
{
    public int $service;
    public string $name;
    public string $type;
    public string $category;
    public float $rate;
    public int $min;
    public int $max;
    public bool $dripfeed;
    public int $average_time;
}
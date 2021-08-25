<?php

namespace app\components\api\instagram;

use app\components\api\instagram\entities\Profile;
use app\components\api\instagram\exceptions\NotFoundExceptions;
use app\components\enums\HttpEnum;
use app\components\helpers\HttpHelper;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Cookie;
use yii\web\CookieCollection;

/**
 * Class InstagramApi
 * @package app\components\api\instagram
 */
class InstagramApi
{
    const BASE_URL = "https://www.instagram.com/";

    private string $sessionsid = '';
    public string $lastErrorMessage = "";
    /* @var null|array|string */
    public $lastResponse = null;

    public function __construct()
    {
        $this->sessionsid = Yii::$app->params['instagram.cookies.sessionid'];
    }

    /**
     * @param string $action
     * @param string $method
     * @param array $data
     * @return array|string
     * @throws \yii\httpclient\Exception
     * @throws exceptions\NotFoundExceptions
     * @throws exceptions\ResponseErrorExceptions
     */
    public function request(string $action, string $method = HttpEnum::METHOD_GET, array $data = []) {
        $url = self::BASE_URL.$action;
        $requiredData = [
            '__a' => 1,
        ];
        $data = array_merge($requiredData, $data);

        $cookieCollection = new CookieCollection();
        $cookieCollection->add(new Cookie([
            'name' => 'sessionid',
            'value' => $this->sessionsid,
        ]));
        $headers = [
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36',
        ];

        // TODO: здесь еще нужно учесть method, data. Но для примера не нужно
        // Так же можно проверять ответ, и если не успешный, не сохранять в кеш
        $response = Yii::$app->cache->getOrSet($url, function () use ($url, $method, $data, $headers, $cookieCollection) {
            return HttpHelper::request(
                $url,
                $method,
                $data,
                $headers,
                [], null, [], $cookieCollection
            );
        }, 120);
        $this->lastResponse = $response;

        if(!is_array($response)) {
            throw new exceptions\ResponseErrorExceptions('Response is not array');
        }

        if(!count($response)) {
            throw new exceptions\NotFoundExceptions($this->lastErrorMessage);
        }

       return $response;
    }

    /**
     * @param string $profile
     * @return Profile
     */
    public function getProfile(string $profile): Profile
    {
        try {
            $data = $this->request(
                $profile
            );

            $profile = new Profile();
            $profile->avatarURL = ArrayHelper::getValue($data, 'graphql.user.profile_pic_url');
            $profile->name = ArrayHelper::getValue($data, 'graphql.user.full_name');
            $profile->username = ArrayHelper::getValue($data, 'graphql.user.username');
            $profile->avatarBase64 = Yii::$app->cache->getOrSet($profile->avatarURL, function () use ($profile) {
                return base64_encode(HttpHelper::get($profile->avatarURL));
            }, 120);
        }
        catch (\Exception $exception) {
            if (!($exception instanceof NotFoundExceptions)) {
                // TODO: сообщение об ошибке в ТГ
                Yii::error("Не удалось распарить {$profile}. Error: {$exception->getMessage()}");
            }
            $profile = new Profile();
            $profile->isEmpty = true;
        }

        return $profile;
    }
}
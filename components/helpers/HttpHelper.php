<?php
namespace app\components\helpers;

use app\components\enums\HttpEnum;
use Exception;
use yii;
use yii\httpclient\Client;

class HttpHelper
{
    /* @var Exception $lastException */
    static Exception $lastException;
    /* @var yii\httpclient\Response $lastResponse*/
    static yii\httpclient\Response $lastResponse;
    /* @var String*/
    static ?string $lastStatusCode = null;
 
    /**
     * @return Client
     */
    static function getClient(): Client
    {
        return Yii::$app->httpClient;
    }

    /**
     * @param $url
     * @return yii\httpclient\Request
     * @throws yii\base\InvalidConfigException
     */
    static function createRequest($url): yii\httpclient\Request
    {
        return
            self::getClient()
            ->createRequest()
            ->setUrl($url) ;
    }

    static function createUrlWithData($url,$data): string
    {
        $url.= strpos($url,"?") === false ? '?' : '&';
        $url.= http_build_query($data);
        return $url;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $data
     * @param array $headers
     * @param array $options
     * @param string|null $format
     * @param array $files
     * @param yii\web\CookieCollection|null $cookiesCollection
     * @return mixed|string|null
     * @throws yii\httpclient\Exception
     */
    static function request(string $url, string $method, array $data = [], array $headers = [], array $options = [], ?string $format = null, array $files = [], ?yii\web\CookieCollection $cookiesCollection = null)
    {
        if($method == HttpEnum::METHOD_GET && count($data)) {
            $url = self::createUrlWithData($url,$data);
        }
        try {
            $request = self::createRequest($url);
            if($format != null) {
                $request->setFormat($format);
            }
            $request
                ->setMethod($method)
                ->addHeaders($headers)
                ->addOptions($options);
            if ($method != HttpEnum::METHOD_GET) {
                $request->setData($data);
            }
            foreach ($files as $name => $path) {
                $request->addFile($name,$path);
            }
            if ($cookiesCollection != null) {
                $request->setCookies($cookiesCollection);
            }

            $response = $request->send();
        } catch (Exception $exception) {
            self::$lastException = $exception;
            return null;
        }
        self::$lastResponse = $response;
        self::$lastStatusCode = $response->getStatusCode();

        try {
            $data = $response->getData();
        } catch (Exception $e) {
            $data = $response->getContent();
        }

        return $data;
    }

    /**
     * @param string $url
     * @param array $data
     * @return array|string|null
     * @throws yii\httpclient\Exception
     */
    static function get(string $url, array $data = [])
    {
        return self::request($url,HttpEnum::METHOD_GET, $data);
    }

    static function getLastErrorMessage(): string
    {
        if(self::$lastException != null) {
            return self::$lastException->getMessage();
        }
        else {
            return "";
        }
    }

    static function getLastStatusCode(): ?string
    {
        return self::$lastStatusCode;
    }
}
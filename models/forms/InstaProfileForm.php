<?php

namespace app\models\forms;

use app\components\api;
use app\components\services\GetInstagramProfileService;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class InstaProfileForm extends Model
{
    /* @var string */
    public $profileURL;

    public function rules(): array
    {
        return [
            [['profileURL'], 'required'],
            [['profileURL'], 'filter', 'filter' => 'trim'],
            ['profileURL', 'validateProfileURL'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'profileURL' => Yii::t("app","profile_url"),
        ];
    }

    public function validateProfileURL($attribute, $params)
    {
        $parsedUrlParts = parse_url($this->profileURL);
        if ($parsedUrlParts === false) {
            $this->addError($attribute, Yii::t("app","invalid_profile_url"));
        }

        if (
            ArrayHelper::getValue($parsedUrlParts, 'scheme') != 'https'
            || empty(ArrayHelper::getValue($parsedUrlParts, 'path'))
            || ArrayHelper::getValue($parsedUrlParts, 'path') == '/'
            || (ArrayHelper::getValue($parsedUrlParts, 'host') != 'instagram.com' && ArrayHelper::getValue($parsedUrlParts, 'host') != 'www.instagram.com')
        ) {
            $this->addError($attribute, Yii::t("app","invalid_profile_url"));
        }
    }

    /**
     * @return api\instagram\entities\Profile
     * @throws \yii\httpclient\Exception
     * @throws api\instagram\exceptions\ResponseErrorExceptions
     */
    public function getProfile(): api\instagram\entities\Profile
    {
        return (new GetInstagramProfileService($this->getProfileName()))->run();
    }

    public function getProfileName(): string
    {
        $parsedUrlParts = parse_url($this->profileURL);
        $path = ArrayHelper::getValue($parsedUrlParts, 'path');
        return explode('/', $path)[1];
    }
}

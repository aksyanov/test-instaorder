<?php

namespace app\components\services;

use app\components\api\instagram\entities\Profile;
use app\components\api\instagram\InstagramApi;

class GetInstagramProfileService
{
    public string $profile;

    public function __construct(string $profile)
    {
        $this->profile = $profile;
    }

    /**
     * @return Profile
     * @throws \app\components\api\instagram\exceptions\ResponseErrorExceptions
     * @throws \yii\httpclient\Exception
     */
    public function run(): Profile
    {
        $instagramApi = new InstagramApi();
        return $instagramApi->getProfile($this->profile);
    }
}

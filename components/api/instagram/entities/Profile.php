<?php


namespace app\components\api\instagram\entities;


class Profile
{
    public string $avatarURL;

    public string $name;

    public string $username;

    public string $avatarBase64;

    public bool $isEmpty = false;
}
<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int|null $ext_id
 * @property int|null $service_id
 * @property int|null $quantity
 * @property string|null $status
 * @property int|null $remains
 * @property float|null $charge
 * @property string|null $currency
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $link
 * @property string|null $avatar_base64
 *
 * @property Services $service
 */
class Orders extends \yii\db\ActiveRecord
{
    const STATUS_PENDING = 'pending';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ext_id', 'service_id', 'quantity', 'remains'], 'integer'],
            [['charge'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string', 'max' => 100],
            [['link'], 'string', 'max' => 2000],
            [['currency'], 'string', 'max' => 255],
            [['avatar_base64'], 'string'],
            [['ext_id'], 'unique'],
            [['service_id'], 'exist', 'skipOnError' => true, 'targetClass' => Services::className(), 'targetAttribute' => ['service_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ext_id' => 'Ext ID',
            'service_id' => Yii::t("app","service"),
            'quantity' => Yii::t("app","quantity"),
            'status' => Yii::t("app","status"),
            'remains' => Yii::t("app","remains"),
            'charge' => Yii::t("app","sum"),
            'currency' => Yii::t("app","currency"),
            'created_at' => Yii::t("app","created_at"),
            'updated_at' => Yii::t("app","updated_at"),
            'link' => Yii::t("app","link"),
            'avatar_base64' => Yii::t("app","avatar"),
        ];
    }

    /**
     * Gets query for [[Service]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Services::className(), ['id' => 'service_id']);
    }
}

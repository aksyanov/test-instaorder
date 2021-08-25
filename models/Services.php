<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "services".
 *
 * @property int $id
 * @property int|null $service_id
 * @property string|null $name
 * @property string|null $type
 * @property string|null $category
 * @property float|null $rate
 * @property int|null $min
 * @property int|null $max
 * @property int|null $dripfeed
 * @property int|null $average_time
 *
 * @property Orders[] $orders
 */
class Services extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_id', 'min', 'max', 'dripfeed', 'average_time'], 'integer'],
            [['rate'], 'number'],
            [['name'], 'string', 'max' => 1000],
            [['type', 'category'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'service_id' => 'Service ID',
            'name' => Yii::t("app","name"),
            'type' => 'Type',
            'category' => 'Category',
            'rate' => 'Rate',
            'min' => 'Min',
            'max' => 'Max',
            'dripfeed' => 'Dripfeed',
            'average_time' => 'Average Time',
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['service_id' => 'id']);
    }
}

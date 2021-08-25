<?php

namespace app\models\searches;

use app\models\Orders;
use yii\data\ActiveDataProvider;

/**
 * Class OrdersSearch
 * @package app\models\searches
 */
class OrdersSearch extends Orders
{

    /* @var string $serviceName */
    public $serviceName;

    public array $ids = [];

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find()
            ->alias('o')
            ->andWhere([
                'o.id' => $this->ids,
            ])
            ->joinWith([
                'service service',
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ],
        ]);

        $this->load($params);

        /*if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }*/

        $query->andFilterWhere([
            'o.ext_id' => $this->ext_id,
            'o.service_id' => $this->service_id,
            'o.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'service.name', $this->serviceName]);

        $query->orderBy([
            'o.created_at' => SORT_DESC,
        ]);

        return $dataProvider;
    }
}

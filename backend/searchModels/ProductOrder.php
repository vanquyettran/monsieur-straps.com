<?php

namespace backend\searchModels;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ProductOrder as ProductOrderModel;

/**
 * ProductOrder represents the model behind the search form of `backend\models\ProductOrder`.
 */
class ProductOrder extends ProductOrderModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'total_value', 'delivery_fee', 'customer_place_t1_id', 'customer_place_t2_id', 'customer_place_t3_id', 'status', 'updated_user_id'], 'integer'],
            [['customer_name', 'customer_phone', 'customer_email', 'customer_address', 'created_time', 'updated_time', 'user_note'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ProductOrderModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => [
                    'created_time' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'total_value' => $this->total_value,
            'delivery_fee' => $this->delivery_fee,
            'customer_place_t1_id' => $this->customer_place_t1_id,
            'customer_place_t2_id' => $this->customer_place_t2_id,
            'customer_place_t3_id' => $this->customer_place_t3_id,
            'status' => $this->status,
            'created_time' => $this->created_time,
            'updated_time' => $this->updated_time,
            'updated_user_id' => $this->updated_user_id,
        ]);

        $query->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'customer_phone', $this->customer_phone])
            ->andFilterWhere(['like', 'customer_email', $this->customer_email])
            ->andFilterWhere(['like', 'customer_address', $this->customer_address]);

        return $dataProvider;
    }
}

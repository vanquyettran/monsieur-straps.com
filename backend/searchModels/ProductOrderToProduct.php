<?php

namespace backend\searchModels;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ProductOrderToProduct as ProductOrderToProductModel;

/**
 * ProductOrderToProduct represents the model behind the search form of `backend\models\ProductOrderToProduct`.
 */
class ProductOrderToProduct extends ProductOrderToProductModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_order_id', 'product_id', 'product_quantity', 'product_price', 'product_discounted_price'], 'integer'],
            [['product_code', 'product_name'], 'safe'],
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
        $query = ProductOrderToProductModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'product_order_id' => $this->product_order_id,
            'product_id' => $this->product_id,
            'product_quantity' => $this->product_quantity,
            'product_price' => $this->product_price,
            'product_discounted_price' => $this->product_discounted_price,
        ]);

        $query->andFilterWhere(['like', 'product_code', $this->product_code])
            ->andFilterWhere(['like', 'product_name', $this->product_name]);

        return $dataProvider;
    }
}

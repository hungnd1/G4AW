<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Transaction;

/**
 * TransactionSearch represents the model behind the search form about `common\models\Transaction`.
 */
class TransactionSearch extends Transaction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'campaign_id', 'user_id', 'payment_type', 'type', 'transaction_time', 'status', 'telco', 'created_at', 'updated_at'], 'integer'],
            [['username', 'scratch_card_code', 'scratch_card_serial', 'shortcode', 'sms_mesage', 'bank_transaction_id', 'bank_transaction_detail', 'description', 'error_code'], 'safe'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Transaction::find();

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
            'id' => $this->id,
            'campaign_id' => $this->campaign_id,
            'user_id' => $this->user_id,
            'payment_type' => $this->payment_type,
            'type' => $this->type,
            'amount' => $this->amount,
            'transaction_time' => $this->transaction_time,
            'status' => $this->status,
            'telco' => $this->telco,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'scratch_card_code', $this->scratch_card_code])
            ->andFilterWhere(['like', 'scratch_card_serial', $this->scratch_card_serial])
            ->andFilterWhere(['like', 'shortcode', $this->shortcode])
            ->andFilterWhere(['like', 'sms_mesage', $this->sms_mesage])
            ->andFilterWhere(['like', 'bank_transaction_id', $this->bank_transaction_id])
            ->andFilterWhere(['like', 'bank_transaction_detail', $this->bank_transaction_detail])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'error_code', $this->error_code]);

        return $dataProvider;
    }
}

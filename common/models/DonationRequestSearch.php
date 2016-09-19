<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DonationRequest;

/**
 * DonationRequestSearch represents the model behind the search form about `common\models\DonationRequest`.
 */
class DonationRequestSearch extends DonationRequest
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'status', 'approved_at', 'created_by', 'created_at', 'updated_at', 'village_id', 'organization_id'], 'integer'],
            [['title', 'short_description', 'content', 'admin_note', 'currency'], 'safe'],
            [['expected_amount', 'current_amount'], 'number'],
            [['expected_items'],'string']
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
        $query = DonationRequest::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        $query->andWhere("status != :status")->addParams([':status' => self::STATUS_DELETED]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'expected_amount' => $this->expected_amount,
            'status' => $this->status,
            'current_amount' => $this->current_amount,
            'approved_at' => $this->approved_at,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'organization_id' => $this->organization_id,
            'village_id' => $this->village_id,
            'expected_items' => $this->expected_items,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'short_description', $this->short_description])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'admin_note', $this->admin_note])
            ->andFilterWhere(['like', 'currency', $this->currency]);

        return $dataProvider;
    }
}

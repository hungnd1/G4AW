<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CampaignSearch represents the model behind the search form about `common\models\Campaign`.
 */
class CampaignSearch extends Campaign
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'donation_request_id', 'type', 'view_count', 'like_count',
                'comment_count', 'follower_count', 'status', 'donor_count', 'honor',
                'approved_at', 'created_by', 'created_for_user', 'created_at',
                'updated_at', 'donation_status','village_id','lead_donor_id'], 'integer'],
            [['name', 'ascii_name', 'short_description', 'thumbnail', 'campaign_code',
                'tags', 'description', 'content', 'admin_note', 'currency'], 'safe'],
            [['expected_amount', 'current_amount'], 'number'],
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
        $query = Campaign::find();



        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'donation_request_id' => $this->donation_request_id,
            'type' => $this->type,
            'view_count' => $this->view_count,
            'like_count' => $this->like_count,
            'comment_count' => $this->comment_count,
            'follower_count' => $this->follower_count,
            'status' => $this->status,
            'expected_amount' => $this->expected_amount,
            'current_amount' => $this->current_amount,
            'donor_count' => $this->donor_count,
            'honor' => $this->honor,
            'approved_at' => $this->approved_at,
            'created_by' => $this->created_by,
            'created_for_user' => $this->created_for_user,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'donation_status' => $this->donation_status,
//            'campaign_code' => $this->campaign_code,
            'village_id' => $this->village_id,
            'lead_donor_id' => $this->lead_donor_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'ascii_name', $this->ascii_name])
            ->andFilterWhere(['like', 'short_description', $this->short_description])
            ->andFilterWhere(['like', 'thumbnail', $this->thumbnail])
            ->andFilterWhere(['like', 'campaign_code', $this->campaign_code])
            ->andFilterWhere(['like', 'tags', $this->tags])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'admin_note', $this->admin_note])
            ->andFilterWhere(['like', 'currency', $this->currency]);

        return $dataProvider;
    }
}

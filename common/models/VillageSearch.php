<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Village;

/**
 * VillageSearch represents the model behind the search form about `\common\models\Village`.
 */
class VillageSearch extends Village
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'district_id', 'status', 'population', 'poor_family', 'no_house_family',
                'missing_classes', 'created_at', 'updated_at','lead_donor_id'], 'integer'],
            [['name', 'province_name', 'image', 'description', 'main_industry', 'main_product', 'lighting_condition', 'water_condition', 'missing_playground'], 'safe'],
            [['natural_area', 'arable_area'], 'number'],
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
        $query = Village::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);

        $query->andWhere("status != :status")->addParams([':status'=>self::STATUS_DELETE]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'district_id' => $this->district_id,
            'status' => $this->status,
            'natural_area' => $this->natural_area,
            'arable_area' => $this->arable_area,
            'population' => $this->population,
            'poor_family' => $this->poor_family,
            'lead_donor_id' => $this->lead_donor_id,
            'no_house_family' => $this->no_house_family,
            'missing_classes' => $this->missing_classes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'province_name', $this->province_name])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'main_industry', $this->main_industry])
            ->andFilterWhere(['like', 'main_product', $this->main_product])
            ->andFilterWhere(['like', 'lighting_condition', $this->lighting_condition])
            ->andFilterWhere(['like', 'water_condition', $this->water_condition])
            ->andFilterWhere(['like', 'missing_playground', $this->missing_playground]);

        return $dataProvider;
    }
}

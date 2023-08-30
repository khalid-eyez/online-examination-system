<?php

namespace frontend\modules\assessments\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Module;

/**
 * ModuleSearch represents the model behind the search form of `common\models\Module`.
 */
class ModuleSearch extends Module
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['moduleID', 'instructorID'], 'integer'],
            [['moduleName'], 'safe'],
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
        $query = Module::find();

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
            'moduleID' => $this->moduleID,
            'instructorID' => $this->instructorID,
        ]);

        $query->andFilterWhere(['like', 'moduleName', $this->moduleName]);

        return $dataProvider;
    }
}

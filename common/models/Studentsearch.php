<?php

namespace frontend\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Student;
use yii;

/**
 * Studentsearch represents the model behind the search form of `common\models\Student`.
 */
class Studentsearch extends Student
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reg_no','fname', 'mname', 'lname', 'email', 'gender', 'DOR', 'phone'], 'safe'],
            [['userID'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Student::find();
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
            'userID' => $this->userID,
            'DOR' => $this->DOR,
        ]);

        $query->andFilterWhere(['like', 'reg_no', $this->reg_no])
            ->andFilterWhere(['like', 'fname', $this->fname])
            ->andFilterWhere(['like', 'mname', $this->mname])
            ->andFilterWhere(['like', 'lname', $this->lname])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }
}

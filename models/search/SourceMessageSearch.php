<?php

namespace Zelenin\yii\modules\I18n\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use Zelenin\yii\modules\I18n\models\SourceMessage;

class SourceMessageSearch extends SourceMessage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['category', 'safe'],
            ['message', 'safe']
        ];
    }

    /**
     * @param array|null $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SourceMessage::find();
        $dataProvider = new ActiveDataProvider(['query' => $query]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'message', $this->message]);
        return $dataProvider;
    }
}

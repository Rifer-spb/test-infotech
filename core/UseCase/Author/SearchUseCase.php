<?php

namespace Core\UseCase\Author;

use Core\Entity\Author\Author;
use Core\Form\Author\SearchForm;
use yii\data\ActiveDataProvider;

class SearchUseCase
{
    public function execute(SearchForm $form): ActiveDataProvider
    {
        $query = Author::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$form->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $form->id,
        ]);

        $query->andFilterWhere(['like', 'name', $form->name]);

        return $dataProvider;
    }
}

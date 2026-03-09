<?php

namespace Core\UseCase\Book;

use Core\Entity\Book\Book;
use Core\Form\Book\SearchForm;
use yii\data\ActiveDataProvider;

class SearchUseCase
{
    public function execute(SearchForm $form): ActiveDataProvider
    {
        $query = Book::find()
            ->with('authors');

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
            'author_id' => $form->authorId,
        ]);

        $query->andFilterWhere(['like', 'title', $form->title])
            ->andFilterWhere(['like', 'description', $form->description])
            ->andFilterWhere(['like', 'year', $form->year])
            ->andFilterWhere(['like', 'isbn', $form->isbn]);

        return $dataProvider;
    }
}

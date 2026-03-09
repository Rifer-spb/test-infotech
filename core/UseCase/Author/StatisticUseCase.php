<?php

namespace Core\UseCase\Author;

use Core\Entity\Author\Author;
use Core\Form\Author\StatisticForm;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class StatisticUseCase
{
    public function execute(StatisticForm $form): ActiveDataProvider
    {
        $query = Author::find()
            ->alias('a')
            ->select(['a.*', 'COUNT(b.id) as booksCount'])
            ->joinWith(['books b' => function(ActiveQuery $query) use ($form) {
                $query->andFilterWhere(['b.year' => $form->year]);
            }])
            ->groupBy('a.id')
            ->having(['>', 'COUNT(b.id)', 0])
            ->orderBy(['booksCount' => SORT_DESC])
            ->limit($form->limit)
            ->asArray();

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}

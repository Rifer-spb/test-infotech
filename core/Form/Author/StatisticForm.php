<?php

namespace Core\Form\Author;

use yii\base\Model;
use Core\Helper\YearHelper;

class StatisticForm extends Model
{
    public $year;
    public $limit = 10;

    public function rules(): array
    {
        return [
            [['year'], 'in', 'range' => array_keys(YearHelper::getList())],
        ];
    }
}

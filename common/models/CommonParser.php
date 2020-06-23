<?php

namespace common\models;

use yii\base\Model;
use yii\base\Component;

class CommonParser extends Model
{
    private $componentCadastralNumbers;

    public function __construct(Component $componentCadastralNumbers, $config = [])
    {
        $this->componentCadastralNumbers = $componentCadastralNumbers;
        parent::__construct($config);
    }

    /**
     * Разбирает кадастровые номера
     * @param array $cadastralNumbers
     * @return array информация о кадастровых номерах
     */
    public function parse($cadastralNumbers)
    {
        if (!is_array($cadastralNumbers)) {
            $cadastralNumbers = explode(',', str_replace(' ', '', $cadastralNumbers));
        }
        $model = $this->componentCadastralNumbers->getData($cadastralNumbers);
        return $this->getData($model);

    }

    /**
     * Возвращает массив информации о кадастровых номерах
     * @param Model $model
     * @return array
     */
    private function getData($model)
    {
        $result = array();
        foreach ($model as $item) {
            $result[] = $item->attributes;
        }
        return $result;
    }

}
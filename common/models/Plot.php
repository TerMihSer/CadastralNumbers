<?php

namespace common\models;

use yii\db\ActiveRecord;

class Plot extends ActiveRecord
{

    public function rules()
    {
        return [
            [['cadastralNumber', 'address', 'price', 'area'], 'required'],
        ];
    }

    /**
     * Возращает информацию о кадастровых номерах
     * @param array $cadastralNumbers кадастровые номера
     * @return array|ActiveRecord[]
     */
    public function getDataByCadastralNumbers($cadastralNumbers)
    {
        $query = Plot::find();
        $query->where(['in', 'cadastralNumber', $cadastralNumbers]);
        return $query->all();
    }

    /**
     * Сохраняет информацию о кадастровых номерах
     * @param array $data данные кадастровых номеров.
     */
    public function saveCadastralData($data)
    {
        foreach ($data as $item) {
            $this->attributes = $item;
            $this->save();
            $this->isNewRecord = TRUE;
            $this->id = FALSE;
        }
    }
}
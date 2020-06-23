<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class CadastralForm extends Model
{
    public $cadastralNumber;

    public function rules()
    {
        return [
            ['cadastralNumber', 'required'],
        ];
    }
}

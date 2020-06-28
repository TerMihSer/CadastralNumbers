<?php

namespace common\tests\unit\models;

use common\components\CadastralNumbers;
use common\models\Plot;
use common\models\CommonParser;
use common\fixtures\PlotFixture;

/**
 * Login form test
 */
class CommonParserTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;


    /**
     * @return array
     */
    public function _fixtures()
    {
        return [
            'plot' => [
                'class' => PlotFixture::className(),
                'dataFile' => codecept_data_dir() . 'plot.php'
            ]
        ];
    }

    public function testTwoExistingElements()
    {
        $params['cadastralNumber'] = '69:27:0000022:1306, 69:27:0000022:1307';
        $componentCadastralNumbers = \Yii::createObject([
            'class' => CadastralNumbers::class
        ], [new Plot(), 'http://pkk.bigland.ru/api/test/plots']);

        $modelParser = \Yii::createObject([
            'class' => CommonParser::class
        ], [$componentCadastralNumbers]);


        $dataCadastralNumbers = $modelParser->parse($params['cadastralNumber']);
        $this->assertEquals([
            [
                'id' => 1,
                'cadastralNumber' => '69:27:0000022:1306',
                'address' => 'Тверская область, р-н Ржевский, с/пос "Успенское", северо-западнее д. Горшково из земель СПКколхоз "Мирный"',
                'price' => '36126.0000',
                'area' => '10035.0000'
            ],
            [
                'id' => 2,
                'cadastralNumber' => '69:27:0000022:1307',
                'address' => 'Тверская область, р-н Ржевский, с/пос "Успенское", северо-западнее д. Горшково из земель СПКколхоз "Мирный"',
                'price' => '36633.6000',
                'area' => '10176.0000'
            ]
        ], $dataCadastralNumbers);
    }

    public function testNoExistingElements()
    {
        $params['cadastralNumber'] = '69:27:0000022:1322, 69:27:0000022:1323';
        $componentCadastralNumbers = \Yii::createObject([
            'class' => CadastralNumbers::class
        ], [new Plot(), 'http://pkk.bigland.ru/api/test/plots']);

        $modelParser = \Yii::createObject([
            'class' => CommonParser::class
        ], [$componentCadastralNumbers]);

        $dataCadastralNumbers = $modelParser->parse($params['cadastralNumber']);
        $this->assertEquals([], $dataCadastralNumbers);
    }

    public function testOneExistingElements()
    {
        $params['cadastralNumber'] = '69:27:0000022:1306, 69:27:0000022:1323';
        $componentCadastralNumbers = \Yii::createObject([
            'class' => CadastralNumbers::class
        ], [new Plot(), 'http://pkk.bigland.ru/api/test/plots']);

        $modelParser = \Yii::createObject([
            'class' => CommonParser::class
        ], [$componentCadastralNumbers]);

        $dataCadastralNumbers = $modelParser->parse($params['cadastralNumber']);
        $this->assertEquals([[
            'id' => 1,
            'cadastralNumber' => '69:27:0000022:1306',
            'address' => 'Тверская область, р-н Ржевский, с/пос "Успенское", северо-западнее д. Горшково из земель СПКколхоз "Мирный"',
            'price' => '36126.0000',
            'area' => '10035.0000'
        ]], $dataCadastralNumbers);
    }
}

<?

namespace console\controllers;

use common\components\CadastralNumbers;
use common\models\CommonParser;
use common\models\Plot;
use yii\console\Controller;
use yii\console\ExitCode;

class ParserController extends Controller
{
    public function actionParse($cadastralNumbers)
    {
        if (!empty($cadastralNumbers)) {
            $componentCadastralNumbers = \Yii::createObject([
                'class' => CadastralNumbers::class
            ], [new Plot(), 'http://pkk.bigland.ru/api/test/plots']);

            $modelParser = \Yii::createObject([
                'class' => CommonParser::class
            ], [$componentCadastralNumbers]);

            $dataCadastralNumbers = $modelParser->parse($cadastralNumbers);
            foreach ($dataCadastralNumbers as $item) {
                print_r($item);
            }
            return ExitCode::OK;
        }
    }
}
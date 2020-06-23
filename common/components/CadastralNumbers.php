<?php

namespace common\components;

use yii\base\Component;
use yii\base\Model;
use yii\httpclient\Client;

class CadastralNumbers extends Component
{
    private $urlRequest;
    private $modelCadastralRepository;

    public function __construct(Model $modelCadastralRepository, $urlRequest, $config = [])
    {
        $this->modelCadastralRepository = $modelCadastralRepository;
        $this->urlRequest = $urlRequest;
        parent::__construct($config);
    }

    /**
     * Получает информацию о кадастровых номерах с сервера по api
     * @param array $cadastralNumbers кадастровые номера
     * @return array|bool информация о кадастровых номерах или false, если информации нет
     * @throws \yii\base\InvalidConfigException
     */
    private function getDataByApi($cadastralNumbers)
    {
        $client = new Client();
        $responseData = false;

        $response = $client->createRequest()
            ->setMethod('get')
            ->setUrl($this->urlRequest)
            ->setFormat(Client::FORMAT_JSON)
            ->setData(['collection' => ['plots' => $cadastralNumbers]])
            ->send();
        if ($response->isOk) {
            $responseData = $response->getData();
        }
        return $responseData;
    }

    /**
     * Получает данные о кадастровых номерах и добавляет ее в таблицу если ее там не было.
     * @param array $cadastralNumbers кадастровые номера
     * @return array объекты
     * @throws \yii\base\InvalidConfigException
     */
    public function getData($cadastralNumbers)
    {
        $model = $this->modelCadastralRepository->getDataByCadastralNumbers($cadastralNumbers);
        $undefinedCadastralNumbers = $this->getUndefinedCadastralNumbers($model, $cadastralNumbers);
        if (!empty($undefinedCadastralNumbers)) {
            $dataByApi = $this->getDataByApi($undefinedCadastralNumbers);
            if (!empty($dataByApi)) {
                $data = $this->getCommonData($dataByApi);
                $this->modelCadastralRepository->saveCadastralData($data);
                $model = array_merge($model, $this->modelCadastralRepository->getDataByCadastralNumbers($undefinedCadastralNumbers));
            }
        }
        return $model;
    }

    /**
     * Определяет какие кадастровые номера не находятся в модели
     * @param array $model объекты кадастровых номеров
     * @param array $cadastralNumbers кадастровые номера
     * @return mixed
     */
    private function getUndefinedCadastralNumbers($model, $cadastralNumbers)
    {
        $result = $cadastralNumbers;
        foreach ($model as $itemModel) {
            foreach ($cadastralNumbers as $key => $cadastralNumber) {
                if ($cadastralNumber == $itemModel->cadastralNumber) {
                    unset($result[$key]);
                }
            }
        }
        return $result;
    }

    /**
     * Возвращает основные данные
     * @param array $cadastralData кадастровые данные
     * @return array
     */
    private function getCommonData($cadastralData)
    {
        $result = array();
        foreach ($cadastralData as $index => $cadastralNumber) {
            $result[$index]['cadastralNumber'] = $cadastralNumber['data']['attrs']['cn'];
            $result[$index]['address'] = $cadastralNumber['data']['attrs']['address'];
            $result[$index]['price'] = $cadastralNumber['data']['attrs']['cad_cost'];
            $result[$index]['area'] = $cadastralNumber['data']['attrs']['area_value'];
        }
        return $result;
    }


}
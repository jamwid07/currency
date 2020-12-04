<?php
/**
 * Author: J.Namazov
 * email:  <jamwid07@mail.ru>
 * date:   05.12.2020
 */

namespace console\controllers;

use common\models\Currency;
use yii\console\Controller;
use yii\console\Exception;
use yii\httpclient\Client;

class CurrencyController extends Controller
{
    public function actionParse()
    {
        echo "Sending requests to currency provider\n";
        $client = new Client();
        $client = $client->get(getenv('CURRENCY_PROVIDER'));
        $client->addHeaders([
            'Accept' => 'text/html,application/xhtml+xml,application/xml',
            'Accept-Encoding' => 'gzip, deflate',
            'Connection' => 'keep-alive',
        ]);
        $response = $client->send();
        if (!$response->getIsOk()) {
            throw new Exception("can't get currency list");
        }
        echo "Recieved successful response\n";

        $data = $response->getData()['Valute'];
        echo "Started parsing currencies\n";

        $updateCount = 0;
        foreach ($data as $item) {
            $item['Value'] = str_replace(',', '.', $item['Value']);
            $currency = Currency::findOne(['code' => $item['CharCode']]);
            if ($currency === null) {
                $currency = new Currency();
            }
            if ($currency->isNewRecord || $currency->rate !== $item['Value']/$item['Nominal']) {
                $currency->code = $item['CharCode'];
                $currency->name = $item['Name'];
                $currency->insert_dt = (new \DateTime())->format('Y-m-d H:i:s');
                $currency->rate = $item['Value'] / $item['Nominal'];
                $currency->save();
                $updateCount++;
            }
        }

        echo "End parsing\n";
        echo "Updated $updateCount currencies\n";
    }
}

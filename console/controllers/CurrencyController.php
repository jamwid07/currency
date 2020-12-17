<?php
/**
 * Author: J.Namazov
 * email:  <jamwid07@mail.ru>
 * date:   05.12.2020
 */

namespace console\controllers;

use common\models\Currency;
use yii\console\Controller;
use yii\httpclient\Client;

class CurrencyController extends Controller
{
    public function actionParse()
    {
        echo "Sending requests to currency provider\n";
        $data = $this->getData(getenv('CURRENCY_PROVIDER'));
        if (null === $data) {
            return 1;
        }
        echo "Recieved success response\n";

        if (false === isset($data['Valute']) || true === empty($data['Valute'])) {
            echo "Currency list not provided\n";
            return 1;
        }
        $data = $data['Valute'];
        echo "Started parsing currencies\n";

        $updateCount = 0;
        foreach ($data as $item) {
            $item['Value'] = str_replace(',', '.', $item['Value']);
            $currency = Currency::findOrCreateOne(['code' => $item['CharCode']]);

            // Prevent division by zero error
            if (0 == $item['Nominal']) {
                echo "Can't calculate currency rate, incorrect nominal\n";
                echo "Skipping currency: " . $item['CharCode'] . "\n";
                continue;
            }

            if ($currency->isNewRecord || $currency->rate !== $item['Value'] / $item['Nominal']) {
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
        return 0;
    }

    private function getData($url)
    {
        $client = new Client();
        $client = $client->get($url);
        $client->addHeaders([
            'Accept' => 'text/html,application/xhtml+xml,application/xml',
            'Accept-Encoding' => 'gzip, deflate',
            'Connection' => 'keep-alive',
        ]);
        try {
            $response = $client->send();
            $response->getIsOk();
        } catch (\yii\httpclient\Exception $e) {
            echo "Error! Can't connect to currency provider url\n";
            echo "Error code: " . $e->getCode() . "\n";
            echo "Message: " . $e->getMessage() . "\n";
            return null;
        }
        return $response->getData();
    }
}

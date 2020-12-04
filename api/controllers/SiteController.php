<?php
namespace api\controllers;

use common\models\Currency;
use common\models\CurrencySearch;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    /**
     * Returns list of currencies with pagination
     * @return ActiveDataProvider
     */
    public function actionCurrencies(): ActiveDataProvider
    {
        $searchModel = new CurrencySearch();

        return $searchModel->search(\Yii::$app->request->queryParams);
    }

    /**
     * Returns found currency via its code
     * @param $code string Standart charCode of currency
     * @return Currency
     * @throws NotFoundHttpException if currency code not found
     */
    public function actionCurrency(string $code): Currency
    {
        $currency = Currency::findOne(['code' => strtoupper($code)]);

        if ($currency === null) {
            throw new NotFoundHttpException("Currency not found");
        }

        return $currency;
    }
}

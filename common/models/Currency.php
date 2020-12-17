<?php
/**
 * Author: J.Namazov
 * email:  <jamwid07@mail.ru>
 * date:   05.12.2020
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "currency".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property float $rate
 * @property string $insert_dt
 */
class Currency extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'rate', 'insert_dt'], 'required'],
            [['rate'], 'number'],
            [['insert_dt'], 'safe'],
            [['code'], 'string', 'max' => 8],
            ['code', 'unique'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'rate' => 'Rate',
            'insert_dt' => 'Insert Dt',
        ];
    }

    public static function findOrCreateOne($condition)
    {
        $currency = static::findOne($condition);
        if ($currency === null) {
            $currency = new Currency();
        }
        return $currency;
    }
}
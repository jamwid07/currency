<?php

use yii\db\Migration;

/**
 * Class m201204_191416_create_currency_table
 */
class m201204_191416_create_currency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("{{%currency}}", [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'rate' => $this->float(),
            'insert_dt' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable("{{%currency}}");
    }
}

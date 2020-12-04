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
            'code' => $this->string(8),
            'name' => $this->string(255),
            'rate' => $this->float(),
            'insert_dt' => $this->dateTime(),
        ]);

        $this->createIndex('idx-currency-code', "{{%currency}}", 'code');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-currency-code', "{{%currency}}");
        $this->dropTable("{{%currency}}");
    }
}

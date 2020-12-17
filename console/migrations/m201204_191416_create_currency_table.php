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
            'code' => $this->string(8)->notNull()->unique(),
            'name' => $this->string(255)->notNull(),
            'rate' => $this->decimal()->notNull(),
            'insert_dt' => $this->dateTime()->notNull(),
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

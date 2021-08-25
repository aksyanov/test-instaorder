<?php

use yii\db\Migration;

class m210825_152235_create_orders_tbl extends Migration
{
    public $tableName = 'orders';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            $this->tableName,
            [
                'id' => $this->primaryKey(),
                'ext_id' => $this->integer(),
                'service_id' => $this->integer(),
                'link' => $this->string(2000),
                'avatar_base64' => $this->text(),
                'quantity' => $this->integer(),
                'status' => $this->string(100),
                'remains' => $this->integer(),
                'charge' => $this->decimal(10,2),
                'currency' => $this->string(),
                'created_at' => $this->dateTime()->notNull()->defaultExpression('NOW()'),
                'updated_at' => $this->dateTime(),
            ],
            $tableOptions
        );

        $this->addForeignKey("fk-{$this->tableName}-service_id",$this->tableName,'service_id','services','id','NO ACTION','NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

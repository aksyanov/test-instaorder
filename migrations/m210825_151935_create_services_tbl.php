<?php

use yii\db\Migration;

class m210825_151935_create_services_tbl extends Migration
{
    public $tableName = 'services';
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
                'service_id' => $this->integer(),
                'name' => $this->string(1000),
                'type' => $this->string(100),
                'category' => $this->string(100),
                'rate' => $this->decimal(10,2),
                'min' => $this->integer(),
                'max' => $this->integer(),
                'dripfeed' => $this->boolean(),
                'average_time' => $this->integer(),
            ],
            $tableOptions
        );
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

<?php

use yii\db\Migration;

/**
 * Class m190326_135310_add_orders
 */
class m190326_135310_add_orders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('orders',[
            'id'=>$this->primaryKey(),
            'agency_id' => $this->integer(),
            'agent_id' => $this->integer(),
            'user_id' => $this->integer(),
            'start_coordinates' => $this->string(),
            'end_coordinates' => $this->string(),
            'price' => $this->integer(),
            'start_time' => $this->string(),
            'end_time' => $this->string(),
            'status' => $this->integer(),
            'comment' => $this->text(),
            'rating' => $this->integer(),
            'created_at' => $this->integer(),
        ],$tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190326_135310_add_orders cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190326_135310_add_orders cannot be reverted.\n";

        return false;
    }
    */
}

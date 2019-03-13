<?php

use yii\db\Migration;

/**
 * Class m190313_130949_add_user_agency_assn_table
 */
class m190313_130949_add_user_agency_assn_table extends Migration
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
        $this->createTable('user_agency_assn',[
            'id'=>$this->primaryKey(),
            'user_id' => $this->integer(),
            'agency_id' => $this->integer(),
            'created_at' => $this->integer(),
            'role' => $this->integer()->defaultValue(0),
        ],$tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190313_130949_add_user_agency_assn_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190313_130949_add_user_agency_assn_table cannot be reverted.\n";

        return false;
    }
    */
}

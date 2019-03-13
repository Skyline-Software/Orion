<?php

use yii\db\Migration;

/**
 * Class m190313_134014_add_companies
 */
class m190313_134014_add_companies extends Migration
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
        $this->createTable('agency',[
            'id'=>$this->primaryKey(),
            'name' => $this->string()->notNull(),
            'logo' => $this->string(),
            'web_site' => $this->string(),
            'status' => $this->integer(),
            'payed_for'=>$this->integer(),
            'created_at' => $this->integer(),
        ],$tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190313_134014_add_companies cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190313_134014_add_companies cannot be reverted.\n";

        return false;
    }
    */
}

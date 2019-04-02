<?php

use yii\db\Migration;

/**
 * Class m190402_090526_add_fields
 */
class m190402_090526_add_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user_agency_assn','agent_price',$this->integer());
        $this->addColumn('user_agency_assn','agent_metrik',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190402_090526_add_fields cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190402_090526_add_fields cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m190401_122026_add_fileds
 */
class m190401_122026_add_fileds extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('agency','agent_price',$this->integer());
        $this->addColumn('agency','agent_metrik',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190401_122026_add_fileds cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190401_122026_add_fileds cannot be reverted.\n";

        return false;
    }
    */
}

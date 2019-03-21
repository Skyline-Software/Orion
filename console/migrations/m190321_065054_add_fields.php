<?php

use yii\db\Migration;

/**
 * Class m190321_065054_add_fields
 */
class m190321_065054_add_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(\core\entities\user\User::tableName(),'working_status',$this->integer());
        $this->addColumn(\core\entities\user\User::tableName(),'price',$this->integer());
        $this->addColumn(\core\entities\user\User::tableName(),'coordinates',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190321_065054_add_fields cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190321_065054_add_fields cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m190313_125519_add_users
 */
class m190313_125519_add_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
        CREATE TABLE `users` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `password_hash` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
            `type` INT(11) NULL DEFAULT '0',
            `email` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
            `created_at` INT(11) NOT NULL,
            `vk_token` VARCHAR(255) NULL DEFAULT NULL,
            `fb_token` VARCHAR(255) NULL DEFAULT NULL,
            `reset_token` VARCHAR(255) NULL DEFAULT NULL,
            `status` INT(9) NULL DEFAULT NULL,
            `name` VARCHAR(255) NULL DEFAULT NULL,
            `phone` VARCHAR(255) NULL DEFAULT NULL,
            `sex` INT(1) NULL DEFAULT NULL,
            `birthday` VARCHAR(8) NULL DEFAULT NULL,
            `photo` VARCHAR(255) NULL DEFAULT NULL,
            `language` VARCHAR(2) NULL DEFAULT 'ru',
            PRIMARY KEY (`id`),
            UNIQUE INDEX `email` (`email`)
        )
        COLLATE='utf8_general_ci'
        ENGINE=InnoDB
        AUTO_INCREMENT=71
        ;
        ");

        $this->execute("
            CREATE TABLE `user_auth` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) NOT NULL DEFAULT '0',
                `token` VARCHAR(255) NOT NULL DEFAULT '0',
                `push_token` VARCHAR(255) NOT NULL DEFAULT '0',
                `ip` VARCHAR(255) NOT NULL DEFAULT '0',
                `lastact` INT(11) NOT NULL DEFAULT '0',
                `device` VARCHAR(7) NULL DEFAULT 'desktop',
                PRIMARY KEY (`id`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB
            AUTO_INCREMENT=37
            ;
        ");
        $this->execute("
            INSERT INTO `users` (`id`, `password_hash`, `type`, `email`, `created_at`, `vk_token`, `fb_token`, `reset_token`, `status`, `name`, `phone`, `sex`, `birthday`, `photo`, `language`) VALUES (1, '$2y$13$8Olxeb/0EU5ioWGJ2JbuVOxFai0iBCyc892tIgU.fHKZHRzLHtlf6', 2, 'asd@asd.ru', 1533322493, NULL, NULL, NULL, 0, 'Филипп', '+380635907512', 1, '12.04.91', '35/7c/f8/209dbe912755e32d2d2c37bba8.png', 'ru');
        ");

    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190313_125519_add_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190313_125519_add_users cannot be reverted.\n";

        return false;
    }
    */
}

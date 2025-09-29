<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%subscribers}}`.
 */
class m250928_181045_create_subscribers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%subscribers}}', [
            'id' => $this->primaryKey(),
            'phone' => $this->string(20)->notNull()->unique(),
            'active' => $this->boolean()->notNull()->defaultValue(true),
            'user_id' => $this->integer()->null(),
        ]);

        $this->addForeignKey(
            'fk_subscribers_user',
            '{{%subscribers}}',
            'user_id',
            '{{%users}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_subscribers_user', '{{%subscribers}}');
        $this->dropTable('{{%subscribers}}');
    }
}

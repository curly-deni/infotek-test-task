<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%author_subscribers}}`.
 */
class m250928_181122_create_author_subscribers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%author_subscribers}}', [
            'author_id' => $this->integer()->notNull(),
            'subscriber_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('pk_author_subscribers', '{{%author_subscribers}}', ['author_id', 'subscriber_id']);
        $this->addForeignKey('fk_author_subscribers_author', '{{%author_subscribers}}', 'author_id', '{{%authors}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_author_subscribers_subscriber', '{{%author_subscribers}}', 'subscriber_id', '{{%subscribers}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_author_subscribers_author', '{{%author_subscribers}}');
        $this->dropForeignKey('fk_author_subscribers_subscriber', '{{%author_subscribers}}');
        $this->dropTable('{{%author_subscribers}}');
    }
}

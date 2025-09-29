<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m250928_174046_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(255)->notNull(),
            'last_name' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull()->unique(),
            'username' => $this->string(50)->notNull()->unique(),
            'password_hash' => $this->string(255)->notNull(),
            'password_reset_token' => $this->string(255)->unique()->defaultValue(null),
            'auth_key' => $this->string(255)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-users-email', '{{%users}}', 'email');
        $this->createIndex('idx-users-username', '{{%users}}', 'username');
        $this->createIndex('idx-users-auth_key', '{{%users}}', 'auth_key');
        $this->createIndex('idx-users-password_reset_token', '{{%users}}', 'password_reset_token');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-users-email', '{{%users}}');
        $this->dropIndex('idx-users-username', '{{%users}}');
        $this->dropIndex('idx-users-auth_key', '{{%users}}');
        $this->dropIndex('idx-users-password_reset_token', '{{%users}}');
        $this->dropTable('{{%users}}');
    }
}

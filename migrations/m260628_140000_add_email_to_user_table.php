<?php

use yii\db\Migration;

class m260628_140000_add_email_to_user_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'email', $this->string(255)->notNull());
        $this->createIndex('idx-user-email', '{{%user}}', 'email', true);
    }

    public function safeDown()
    {
        $this->dropIndex('idx-user-email', '{{%user}}');
        $this->dropColumn('{{%user}}', 'email');
    }
}

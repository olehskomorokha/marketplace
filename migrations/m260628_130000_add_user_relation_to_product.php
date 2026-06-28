<?php

use yii\db\Migration;

class m260628_130000_add_user_relation_to_product extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'user_id', $this->integer()->null());
        $this->createIndex('idx-product-user_id', '{{%product}}', 'user_id');
        $this->addForeignKey(
            'fk-product-user',
            '{{%product}}',
            'user_id',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-product-user', '{{%product}}');
        $this->dropIndex('idx-product-user_id', '{{%product}}');
        $this->dropColumn('{{%product}}', 'user_id');
    }
}

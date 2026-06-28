<?php

use yii\db\Migration;

class m260628_080945_add_category_relation_to_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
        ]);

        $this->createIndex('idx-category-name', '{{%category}}', 'name', true);

        $this->execute("
            INSERT INTO {{%category}} (name)
            SELECT DISTINCT category
            FROM {{%product}}
            WHERE category IS NOT NULL AND category <> ''
        ");

        $this->addColumn('{{%product}}', 'category_id', $this->integer()->null());

        $this->execute("
            UPDATE {{%product}} p
            SET category_id = c.id
            FROM {{%category}} c
            WHERE p.category = c.name
        ");

        $this->alterColumn('{{%product}}', 'category_id', $this->integer()->notNull());

        $this->createIndex('idx-product-category_id', '{{%product}}', 'category_id');
        $this->addForeignKey(
            'fk-product-category',
            '{{%product}}',
            'category_id',
            '{{%category}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->dropIndex('idx-product-category', '{{%product}}');
        $this->dropColumn('{{%product}}', 'category');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%product}}', 'category', $this->string(255)->null());

        $this->execute("
            UPDATE {{%product}} p
            SET category = c.name
            FROM {{%category}} c
            WHERE p.category_id = c.id
        ");

        $this->alterColumn('{{%product}}', 'category', $this->string(255)->notNull());
        $this->createIndex('idx-product-category', '{{%product}}', 'category');

        $this->dropForeignKey('fk-product-category', '{{%product}}');
        $this->dropIndex('idx-product-category_id', '{{%product}}');
        $this->dropColumn('{{%product}}', 'category_id');

        $this->dropIndex('idx-category-name', '{{%category}}');
        $this->dropTable('{{%category}}');
    }

}

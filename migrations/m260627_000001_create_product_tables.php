<?php

use yii\db\Migration;

class m260627_000001_create_product_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text()->null(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text()->notNull(),
            'price' => $this->decimal(12, 2)->notNull()->defaultValue(0),
            'category_id' => $this->integer()->notNull(),
            'attributes_json' => $this->json()->null(),
            'status' => $this->string(20)->notNull()->defaultValue('draft'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('idx-product-status', '{{%product}}', 'status');
        $this->createIndex('idx-product-category', '{{%product}}', 'category_id');
        $this->createIndex('idx-product-name', '{{%product}}', 'name');

        $this->createTable('{{%product_price_history}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'old_price' => $this->decimal(12, 2)->notNull(),
            'new_price' => $this->decimal(12, 2)->notNull(),
            'changed_by' => $this->integer()->null(),
            'changed_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('idx-product-price-history-product', '{{%product_price_history}}', 'product_id');
        $this->addForeignKey(
            'fk-product-category',
            '{{%product}}',
            'category_id',
            '{{%category}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-product-price-history-product',
            '{{%product_price_history}}',
            'product_id',
            '{{%product}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-product-price-history-product', '{{%product_price_history}}');
        $this->dropTable('{{%product_price_history}}');
        $this->dropTable('{{%product}}');
        $this->dropTable('{{%category}}');
    }
}
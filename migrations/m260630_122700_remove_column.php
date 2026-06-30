<?php

use yii\db\Migration;

class m260630_122700_remove_column extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('{{%product_price_history}}', 'changed_by');
    }

    public function safeDown()
    {
        $this->addColumn('{{%product_price_history}}', 'changed_by', $this->integer()->null());
    }
}

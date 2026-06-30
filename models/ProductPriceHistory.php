<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class ProductPriceHistory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%product_price_history}}';
    }

    public function rules()
    {
        return [
            [['product_id', 'old_price', 'new_price'], 'required'],
            [['product_id', 'changed_by'], 'integer'],
            [['old_price', 'new_price'], 'number'],
            [['changed_at'], 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => false,
                'updatedAtAttribute' => 'changed_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

}

<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use app\models\Category;
use yii\db\Transaction;
use app\models\ProductPriceHistory;

class Product extends ActiveRecord
{
    public const STATUS_DRAFT = 'записаний';
    public const STATUS_ACTIVE = 'активний';
    public const STATUS_ARCHIVED = 'архівований';

    public $attributes_data = [];
    public $category_name;

    public static function tableName()
    {
        return '{{%product}}';  
    }

    public function rules()
    {
        return [
            [['name', 'description', 'price', 'category_id', 'status'], 'required'],
            [['description'], 'string'],
            [['price'], 'number', 'min' => 0],
            [['category_id', 'user_id'], 'integer'],
            [['name', 'category_name'], 'string', 'max' => 255],
            [['status'], 'in', 'range' => [self::STATUS_DRAFT, self::STATUS_ACTIVE, self::STATUS_ARCHIVED]],
            [['attributes_json'], 'safe'],
            [['attributes_data'], 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'description' => 'Description',
            'price' => 'Price',
            'category_id' => 'Category',
            'category_name' => 'Category',
            'user_id' => 'User',
            'status' => 'Status',
            'attributes_json' => 'Attributes',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->attributes_data = $this->decodeAttributesJson();
    }

    public function beforeSave($insert)
    {
        $this->attributes_json = $this->encodeAttributesData();

        return parent::beforeSave($insert);
    }

    public function deleteProduct()
    {
        $transaction = static::getDb()->beginTransaction();

        try {
            // Delete related price histories
            ProductPriceHistory::deleteAll(['product_id' => $this->id]);

            // Delete the product itself
            $this->delete();

            $transaction->commit();
            return true;
        } catch (\Throwable $exception) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }

            throw $exception;
        }
    }
    
    public function updatePrice($newPrice, $changedBy = null)
    {
        $oldPrice = $this->price;
        $newPrice = (float) $newPrice;

        if ((float) $oldPrice === $newPrice) {
            return true;
        }

        $transaction = static::getDb()->beginTransaction();

        try {
            $history = new ProductPriceHistory();
            $history->product_id = $this->id;
            $history->old_price = $oldPrice;
            $history->new_price = $newPrice;
            $history->changed_by = $changedBy;
            $history->save(false);

            $this->price = $newPrice;
            $this->save(false, ['price', 'updated_at']);

            $transaction->commit();
            return true;
        } catch (\Throwable $exception) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }

            throw $exception;
        }
    }

    private function encodeAttributesData()
    {
        if (empty($this->attributes_data)) {
            return null;
        }

        return json_encode($this->attributes_data, JSON_UNESCAPED_UNICODE);
    }

    private function decodeAttributesJson()
    {
        if (empty($this->attributes_json)) {
            return [];
        }

        $decoded = json_decode($this->attributes_json, true);

        return is_array($decoded) ? $decoded : [];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }
}

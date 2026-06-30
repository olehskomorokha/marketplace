<?php

namespace app\services;

use Yii;
use app\models\Product;
use app\models\ProductPriceHistory;


class ProductService
{
    public function deleteWithPriceHistories(Product $product)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            // Delete price histories
            ProductPriceHistory::deleteAll(['product_id' => $product->id]);

            // Delete the product
            $product->delete();

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function updateProduct(Product $updatedProduct, $id)
    {
        $model = Product::findOne($id);

        if ($model === null) {
            return false;
        }

        $model->name = $updatedProduct->name;
        $model->description = $updatedProduct->description;
        $model->category_id = $updatedProduct->category_id;
        $model->status = $updatedProduct->status;

        if ($model->price !== $updatedProduct->price)
        {
            $this->updatePrice($model, $updatedProduct->price);
        }

        return $model;
    }

    public function updatePrice(Product $product, $newPrice)
    {
        $oldPrice = $product->price;
        $newPrice = (float) $newPrice;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $productPricehistory = new ProductPriceHistory();
            $productPricehistory->product_id = $product->id;
            $productPricehistory->old_price = $oldPrice;
            $productPricehistory->new_price = $newPrice;
            $productPricehistory->save(false);

            $product->price = $newPrice;
            $product->save(false, ['price', 'updated_at']);

            $transaction->commit();
            return $newPrice;
        } catch (\Throwable $exception) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }

            throw $exception;
        }
    }
}

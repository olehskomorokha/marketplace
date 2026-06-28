<?php

namespace app\controllers;

use app\models\Category;
use app\models\Product;
use Yii;
use yii\db\Expression;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\ArrayHelper;

class ProductController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['post'],
                    'update-price' => ['post'],
                    'update-attributes' => ['post'],
                    'create-page' => ['get', 'post'],
                    'products' => ['get'],
                    'search' => ['get'],
                    'view' => ['get'],
                ],
            ],
        ];
    }

    public function actionCreatePage()
    {
        $model = new Product([
            'status' => Product::STATUS_DRAFT,
        ]);

        $categories = Category::getAllCategories();
        
        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->isGuest ? null : Yii::$app->user->id;

            if (!$model->save()) {
                return $this->render('create', [
                    'model' => $model,
                    'categories' => $categories,
                ]);
            }

            Yii::$app->session->setFlash('success', 'Product created.');

            return $this->redirect(['/site/index']);
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
        ]);
    }

    public function actionProducts()
    {
        return $this->render('products');
    }

    public function actionView($id)
    {
        $product = $this->findProduct($id);

        return $this->successResponse($this->serializeProduct($product));
    }

    public function actionUpdatePrice($id)
    {
        $product = $this->findProduct($id);
        $data = $this->requestData();

        if (!array_key_exists('price', $data)) {
            throw new BadRequestHttpException('Field "price" is required.');
        }

        $changedBy = $data['changed_by'] ?? null;
        $product->updatePrice($data['price'], $changedBy);

        return $this->successResponse($this->serializeProduct($product));
    }

    public function actionUpdateAttributes($id)
    {
        $product = $this->findProduct($id);
        $data = $this->requestData();
        $attributes = $this->extractAttributes($data);

        if (empty($attributes)) {
            throw new BadRequestHttpException('Field "attributes" must contain at least one value.');
        }

        $product->attributes_data = array_merge($product->attributes_data, $attributes);
        $product->save(false, ['attributes_json', 'updated_at']);

        return $this->successResponse($this->serializeProduct($product));
    }

    public function actionSearch()
    {
        $query = trim((string) Yii::$app->request->get('q', ''));
        $category = trim((string) Yii::$app->request->get('category', ''));
        $brand = trim((string) Yii::$app->request->get('brand', ''));
        $status = trim((string) Yii::$app->request->get('status', ''));

        $products = Product::find()
            ->alias('p')
            ->joinWith('category c')
            ->orderBy(['p.id' => SORT_DESC]);

        if ($query !== '') {
            $products->andWhere([
                'or',
                ['ilike', 'p.name', $query],
                ['ilike', 'p.description', $query],
                ['ilike', 'c.name', $query],
                new Expression('p.attributes_json::text ILIKE :query', [':query' => '%' . $query . '%']),
            ]);
        }

        if ($category !== '') {
            $products->andWhere(['ilike', 'c.name', $category]);
        }

        if ($brand !== '') {
            $products->andWhere(new Expression('p.attributes_json::text ILIKE :brand', [':brand' => '%' . $brand . '%']));
        }

        if ($status !== '') {
            $products->andWhere(['p.status' => $status]);
        }

        $items = [];
        foreach ($products->limit(20)->all() as $product) {
            $items[] = $this->serializeProduct($product);
        }

        return $this->successResponse([
            'items' => $items,
            'count' => count($items),
        ]);
    }

    private function findProduct($id)
    {
        $product = Product::findOne((int) $id);

        if ($product === null) {
            throw new NotFoundHttpException('Product not found.');
        }

        return $product;
    }

    private function requestData()
    {
        return Yii::$app->request->bodyParams + Yii::$app->request->queryParams;
    }

    private function extractAttributes(array $data)
    {
        if (isset($data['attributes']) && is_array($data['attributes'])) {
            return $data['attributes'];
        }

        unset($data['name'], $data['description'], $data['price'], $data['category_id'], $data['status'], $data['changed_by']);

        return $data;
    }

    private function serializeProduct(Product $product)
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => (float) $product->price,
            'category' => $product->category ? $product->category->name : null,
            'category_id' => $product->category_id,
            'status' => $product->status,
            'attributes' => $product->attributes_data,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ];
    }

    private function successResponse(array $data, $statusCode = 200)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->statusCode = $statusCode;

        return $data;
    }

    private function errorResponse($message, array $errors = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->statusCode = 422;

        return [
            'message' => $message,
            'errors' => $errors,
        ];
    }
}

<?php

namespace backend\controllers;

use Yii;
use backend\models\ProductOrderToProduct;
use backend\searchModels\ProductOrderToProduct as ProductOrderToProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductOrderToProductController implements the CRUD actions for ProductOrderToProduct model.
 */
class ProductOrderToProductController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ProductOrderToProduct models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductOrderToProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductOrderToProduct model.
     * @param integer $product_order_id
     * @param integer $product_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($product_order_id, $product_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($product_order_id, $product_id),
        ]);
    }

    /**
     * Creates a new ProductOrderToProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductOrderToProduct();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'product_order_id' => $model->product_order_id, 'product_id' => $model->product_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProductOrderToProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $product_order_id
     * @param integer $product_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($product_order_id, $product_id)
    {
        $model = $this->findModel($product_order_id, $product_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'product_order_id' => $model->product_order_id, 'product_id' => $model->product_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProductOrderToProduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $product_order_id
     * @param integer $product_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($product_order_id, $product_id)
    {
        $this->findModel($product_order_id, $product_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProductOrderToProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $product_order_id
     * @param integer $product_id
     * @return ProductOrderToProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($product_order_id, $product_id)
    {
        if (($model = ProductOrderToProduct::findOne(['product_order_id' => $product_order_id, 'product_id' => $product_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

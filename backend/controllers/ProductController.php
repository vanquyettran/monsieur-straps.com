<?php

namespace backend\controllers;

use Yii;
use backend\models\Product;
use backend\models\ProductSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        // Default value
        $model->active = 1;
        $model->visible = 1;
        $model->allow_indexing = 1;
        $model->allow_following = 1;


        if (null === $model->product_attribute_ids) {
            $model->product_attribute_ids = [];
        }
        if (null === $model->detailed_image_ids) {
            $model->detailed_image_ids = [];
        }
        if (null === $model->tag_ids) {
            $model->tag_ids = [];
        }
        if (null === $model->related_product_ids) {
            $model->related_product_ids = [];
        }
        if (null === $model->product_discount_ids) {
            $model->product_discount_ids = [];
        }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $model->saveProductToProductAttributes();
            $model->saveProductToDetailedImages();
            $model->saveProductToTags();
            $model->saveProductToRelatedProducts();
            $model->saveProductToProductDiscounts();

            return $this->redirect(['view', 'id' => $model->id]);
        }


        if ($model->hasErrors()) {
            Yii::$app->session->addFlash('error', VarDumper::dumpAsString($model->getErrors()));
        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if (null === $model->product_attribute_ids) {
            $model->product_attribute_ids = ArrayHelper::getColumn($model->productAttributes, 'id');
        }
        if (null === $model->detailed_image_ids) {
            $model->detailed_image_ids = ArrayHelper::getColumn($model->productToDetailedImages, 'detailed_image_id');
        }
        if (null === $model->tag_ids) {
            $model->tag_ids = ArrayHelper::getColumn($model->tags, 'id');
        }
        if (null === $model->related_product_ids) {
            $model->related_product_ids = ArrayHelper::getColumn($model->relatedProducts, 'id');
        }
        if (null === $model->product_discount_ids) {
            $model->product_discount_ids = ArrayHelper::getColumn($model->productDiscounts, 'id');
        }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $model->saveProductToProductAttributes();
            $model->saveProductToDetailedImages();
            $model->saveProductToTags();
            $model->saveProductToRelatedProducts();
            $model->saveProductToProductDiscounts();

            return $this->redirect(['view', 'id' => $model->id]);
        }


        if ($model->hasErrors()) {
            Yii::$app->session->addFlash('error', VarDumper::dumpAsString($model->getErrors()));
        }


        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionReorderDetailedImages($id)
    {
        $model = $this->findModel($id);

        return $this->render('reorderDetailedImages', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

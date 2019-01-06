<?php

namespace backend\controllers;

use backend\models\Product;
use Yii;
use backend\models\ProductCustomization;
use backend\models\ProductCustomizationSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductCustomizationController implements the CRUD actions for ProductCustomization model.
 */
class ProductCustomizationController extends Controller
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
     * Lists all ProductCustomization models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductCustomizationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $product_id = Yii::$app->request->get('product_id');
        if ($product = Product::findOne($product_id)) {
            $dataProvider->query->andFilterWhere(['product_id' => $product->id]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'product' => $product,
        ]);
    }

    /**
     * Displays a single ProductCustomization model.
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
     * Creates a new ProductCustomization model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductCustomization();

        // default values
        $model->sort_order = 0;
        $model->available = 1;

        $product_id = Yii::$app->request->get('product_id');
        if ($product = Product::findOne($product_id)) {
            $model->product_id = $product->id;
            $model->price = $product->price;
        }

        if (null === $model->product_attribute_ids) {
            $model->product_attribute_ids = [];
        }
        if (null === $model->detailed_image_ids) {
            $model->detailed_image_ids = [];
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $model->saveProductCustomizationToProductAttributes();
            $model->saveProductCustomizationToDetailedImages();

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
     * Updates an existing ProductCustomization model.
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
            $model->detailed_image_ids = ArrayHelper::getColumn($model->detailedImages, 'id');
        }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $model->saveProductCustomizationToProductAttributes();
            $model->saveProductCustomizationToDetailedImages();

            return $this->redirect(['view', 'id' => $model->id]);
        }


        if ($model->hasErrors()) {
            Yii::$app->session->addFlash('error', VarDumper::dumpAsString($model->getErrors()));
        }


        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProductCustomization model.
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
     * Finds the ProductCustomization model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductCustomization the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductCustomization::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

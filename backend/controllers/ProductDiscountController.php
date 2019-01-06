<?php

namespace backend\controllers;

use Yii;
use backend\models\ProductDiscount;
use backend\models\ProductDiscountSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductDiscountController implements the CRUD actions for ProductDiscount model.
 */
class ProductDiscountController extends Controller
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
     * Lists all ProductDiscount models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductDiscountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductDiscount model.
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
     * Creates a new ProductDiscount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductDiscount();

        if (null === $model->product_category_ids) {
            $model->product_category_ids = [];
        }
        if (null === $model->product_ids) {
            $model->product_ids = [];
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->saveProductToProductDiscounts();
            $model->saveProductCategoryToProductDiscounts();

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
     * Updates an existing ProductDiscount model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (null === $model->product_ids) {
            $model->product_ids = ArrayHelper::getColumn($model->products, 'id');
        }
        if (null === $model->product_category_ids) {
            $model->product_category_ids = ArrayHelper::getColumn($model->productCategories, 'id');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->saveProductToProductDiscounts();
            $model->saveProductCategoryToProductDiscounts();

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
     * Deletes an existing ProductDiscount model.
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
     * Finds the ProductDiscount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductDiscount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductDiscount::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

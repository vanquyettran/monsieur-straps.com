<?php

namespace backend\controllers;

use Yii;
use backend\models\ProductCategory;
use backend\models\ProductCategorySearch;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductCategoryController implements the CRUD actions for ProductCategory model.
 */
class ProductCategoryController extends Controller
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
     * Lists all ProductCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductCategory model.
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
     * Creates a new ProductCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductCategory();


        // Default value
        $model->active = 1;
        $model->visible = 1;
        $model->allow_indexing = 1;
        $model->allow_following = 1;


        if (null === $model->banner_image_ids) {
            $model->banner_image_ids = [];
        }
        if (null === $model->product_attribute_group_ids) {
            $model->product_attribute_group_ids = [];
        }



        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->displaying_areas = json_encode($model->displaying_areas);

            if ($model->save(false)) {
                $model->saveProductCategoryToBannerImages();
                $model->saveProductCategoryToProductAttributeGroups();

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }


        if ($model->hasErrors()) {
            Yii::$app->session->addFlash('error', VarDumper::dumpAsString($model->getErrors()));
        }

        $model->displaying_areas = json_decode($model->displaying_areas);


        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProductCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if (null === $model->banner_image_ids) {
            $model->banner_image_ids = ArrayHelper::getColumn($model->bannerImages, 'id');
        }
        if (null === $model->product_attribute_group_ids) {
            $model->product_attribute_group_ids = ArrayHelper::getColumn($model->productAttributeGroups, 'id');
        }
        


        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->displaying_areas = json_encode($model->displaying_areas);

            if ($model->save(false)) {
                $model->saveProductCategoryToBannerImages();
                $model->saveProductCategoryToProductAttributeGroups();

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        if ($model->hasErrors()) {
            Yii::$app->session->addFlash('error', VarDumper::dumpAsString($model->getErrors()));
        }

        $model->displaying_areas = json_decode($model->displaying_areas);

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProductCategory model.
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
     * Finds the ProductCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductCategory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

<?php

namespace backend\controllers;

use Yii;
use backend\models\Article;
use backend\searchModels\Article as ArticleSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
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
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
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
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();

        // Default value
        $model->active = 1;
        $model->visible = 1;
        $model->allow_indexing = 1;
        $model->allow_following = 1;

        if (null === $model->related_article_ids) {
            $model->related_article_ids = [];
        }
        if (null === $model->tag_ids) {
            $model->tag_ids = [];
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->saveArticleToRelatedArticles();
            $model->saveArticleToTags();
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
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (null === $model->related_article_ids) {
            $model->related_article_ids = ArrayHelper::getColumn($model->relatedArticles, 'id');
        }
        if (null === $model->tag_ids) {
            $model->tag_ids = ArrayHelper::getColumn($model->tags, 'id');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->saveArticleToRelatedArticles();
            $model->saveArticleToTags();
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
     * Deletes an existing Article model.
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
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

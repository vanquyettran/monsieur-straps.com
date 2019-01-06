<?php
use backend\models\User;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);
$commonMain = require(__DIR__ . '/../../common/config/main.php');

return [
    'language' => 'en-US',
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'image' => [
            'class' => 'common\modules\image\Module',
        ]
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'response' => [
            // ...
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                    // ...
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => $commonMain['components']['backendUrlManager'],
    ],
    'as access' => [
        'class' => \yii\filters\AccessControl::className(),
        'rules' => [
            [
                'allow' => true,
                'matchCallback' => function ($rule, $action) {
                    /**
                     * @var $rule \yii\filters\AccessRule
                     * @var $action \yii\base\Action
                     */

                    /**
                     * @var $controller \yii\base\Controller
                     */
                    $controller = $action->controller;

                    // Allows site/login and site/error by default
                    if (in_array($controller->id, ['site']) && in_array($action->id, ['login', 'error'])) {
                        return true;
                    }

                    $allowed = false;

                    // Checks user role
                    if (!Yii::$app->user->isGuest) {
                        // Allows these for all of the logged users
                        if (in_array($controller->id, ['site', 'api'])) {
                            return true;
                        }

                        /**
                         * @var $user \common\models\User
                         */
                        $user = Yii::$app->user->identity;
                        switch ($user->role) {
                            case \common\models\User::ROLE_DEVELOPER:
                                $allowed = true;
                                break;
                            case \common\models\User::ROLE_ADMINISTRATOR:
                                // Admin cannot update or delete Developer
                                if ('user' == $controller->id && in_array($action->id, ['update', 'delete'])) {
                                    $requestedUserId = Yii::$app->request->get('id');
                                    if ($requestedUserId != $user->id && $requestedUser = User::findOne($requestedUserId)) {
                                        if (\common\models\User::ROLE_DEVELOPER != $requestedUser->role) {
                                            $allowed = true;
                                        }
                                    } else {
                                        $allowed = true;
                                    }
                                } else {
                                    $allowed = true;
                                }
                                break;
                            case \common\models\User::ROLE_MODERATOR:
                                switch (true) {
                                    case $controller->module && in_array($controller->module->id, ['image']):
                                    case in_array($controller->id, ['game', 'article', 'article-category']):
                                        $allowed = true;
                                }
                                break;
                            case \common\models\User::ROLE_WRITER:
                                switch (true) {
                                    case $controller->module && in_array($controller->module->id, ['image']):
                                    case in_array($controller->id, ['game', 'article', 'article-category']):
                                        $allowed = true;
                                    default:
                                }
                                break;
                            case \common\models\User::ROLE_GUESS:
                            default:
                        }

                        // Allows update themselves profile only
                        if (!$allowed && 'user' == $controller->id && 'update' == $action->id) {
                            $requestedUserId = Yii::$app->request->get('id');
                            if ($user->id == $requestedUserId) {
                                $allowed = true;
                            }
                        }
                    }

                    return $allowed;
                }
            ]
        ]
    ],
    'on afterRequest' => function () {
         Yii::$app->cache->flush();
    },
    'params' => $params,
];

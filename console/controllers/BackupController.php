<?php

namespace console\controllers;

use yii\console\Controller;
use yii\helpers\FileHelper;
use yii\helpers\Console;

class BackupController extends Controller
{
    public function actionDatabase()
    {
        // Configuration
        if (!isset(\Yii::$app->params['backupDB'],
            \Yii::$app->params['backupDB']['host'],
            \Yii::$app->params['backupDB']['port'],
            \Yii::$app->params['backupDB']['username'],
            \Yii::$app->params['backupDB']['database'],
            \Yii::$app->params['backupDB']['password']
        )) {
            echo "Cannot find enough configuration\n";
            return false;
        }
        $host = \Yii::$app->params['backupDB']['host'];
        $port = \Yii::$app->params['backupDB']['port'];
        $username = \Yii::$app->params['backupDB']['username'];
        $database = \Yii::$app->params['backupDB']['database'];
        $password = \Yii::$app->params['backupDB']['password'];

        $destination = \Yii::getAlias('@backups/') . date('Ymd');
        if (!file_exists($destination)) {
            FileHelper::createDirectory($destination);
        }
        $filename = $destination . '/' . date('Ymd_His') . '_database.sql';
        exec(
            "mysqldump -P \"{$port}\" -h \"{$host}\" -u \"{$username}\" -p \"{$database}\" --password=\"{$password}\" >$filename",
            $output,
            $return
        );
        if (!$return) {
            echo "$filename\n";
        } else {
            var_dump($output);
        }
    }

    public function actionImages()
    {
        $destination = \Yii::getAlias('@backups/') . date('Ymd');
        if (!file_exists($destination)) {
            FileHelper::createDirectory($destination);
        }
        $filename = $destination . '/' . date('Ymd_His') . '_images.tar.gz';
        $source = \Yii::getAlias('@images');
        $source_info = pathinfo($source);
        $source_dir = $source_info['dirname'];
        $source_f = $source_info['basename'];
        exec(
            "tar -czf \"{$filename}\" -C \"{$source_dir}\" \"{$source_f}\"",
            $output,
            $return
        );
        if (!$return) {
            echo "$filename\n";
        } else {
            var_dump($output);
        }
    }

    public function actionEmpty()
    {
        $backups_folder = \Yii::getAlias('@backups');
        if (Console::confirm("Are you sure want to remove folder: \"$backups_folder\"?")) {
            echo "Removing: $backups_folder\n";
            FileHelper::removeDirectory($backups_folder);
        } else {
            echo "Don't remove\n";
        }
    }
}
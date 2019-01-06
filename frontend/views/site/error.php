<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;

if ($exception instanceof \yii\web\HttpException) {
    $code = $exception->statusCode;
} else {
    $code = $exception->getCode();
}
?>
<style>
    .site-error {
        width: 100%;
        max-width: 600px;
        margin: auto;
    }
    .site-error .title {
        text-align: center;
        font-size: 1.4em;
        font-weight: bold;
    }
    .site-error .alert {
        margin-top: 1rem;
    }
    .site-error .search-box {
        width: 100%;
        border: 1px solid #eee;
        margin-top: 1rem;
    }
    .site-error .search-box * {
        box-sizing: content-box;
        -webkit-box-sizing: content-box;
    }
    .site-error .icon-404 {
        width: 50%;
        margin: auto;
        display: block;
    }
</style>
<div class="site-error">
    <?php
    switch ($code) {
        case 404:
            ?>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="icon-404">
                <path d="M504 48H8a8 8 0 0 0-8 8v400a8 8 0 0 0 8 8h496a8 8 0 0 0 8-8V56a8 8 0 0 0-8-8z" fill="#eceef1"></path>
                <path d="M24 456V56a8 8 0 0 1 8-8H8a8 8 0 0 0-8 8v400a8 8 0 0 0 8 8h24a8 8 0 0 1-8-8z" fill="#d9dde2"></path>
                <path d="M504 48H8a8 8 0 0 0-8 8v88h512V56a8 8 0 0 0-8-8z" fill="#69788d"></path>
                <path d="M32 48H8a8 8 0 0 0-8 8v88h24V56a8 8 0 0 1 8-8z" fill="#56677e"></path>
                <path d="M464 72c-13.233 0-24 10.766-24 24s10.767 24 24 24 24-10.766 24-24-10.767-24-24-24z" fill="#db6b5e"></path>
                <path d="M464 80c-8.822 0-16 7.177-16 16s7.178 16 16 16 16-7.177 16-16-7.178-16-16-16z" fill="#ff8c78"></path>
                <path d="M400 72c-13.233 0-24 10.766-24 24s10.767 24 24 24 24-10.766 24-24-10.767-24-24-24z" fill="#f7c14d"></path>
                <path d="M400 80c-8.822 0-16 7.177-16 16s7.178 16 16 16 16-7.177 16-16-7.178-16-16-16z" fill="#ffdb66"></path>
                <path d="M336 72c-13.233 0-24 10.766-24 24s10.767 24 24 24 24-10.766 24-24-10.767-24-24-24z" fill="#59ad57"></path>
                <path d="M336 80c-8.822 0-16 7.177-16 16s7.178 16 16 16 16-7.177 16-16-7.178-16-16-16z" fill="#7ec97d"></path>
                <path d="M48 104h-8a8 8 0 0 1 0-16h8a8 8 0 0 1 0 16zm40 0H72a8 8 0 0 1 0-16h16a8 8 0 0 1 0 16zm32 0h-8a8 8 0 0 1 0-16h8a8 8 0 0 1 0 16z" fill="#435670"></path>
                <path d="M172 312h-12v-84c0-4.954-3.16-9.508-7.801-11.241-4.932-1.841-10.64-.172-13.799 4.04l-72 96c-2.826 3.768-3.166 8.985-.856 13.089C67.649 333.628 71.709 336 76 336h60v12c0 6.627 5.373 12 12 12s12-5.373 12-12v-12h12c6.627 0 12-5.373 12-12s-5.373-12-12-12zm-36 0h-36l36-48v48zm300 0h-12v-84a12 12 0 0 0-21.6-7.2l-72 96A12 12 0 0 0 340 336h60v12c0 6.627 5.373 12 12 12s12-5.373 12-12v-12h12c6.627 0 12-5.373 12-12s-5.373-12-12-12zm-36 0h-36l36-48v48z" fill="#ff8c78"></path>
                <path d="M260 216h-8c-24.262 0-44 19.738-44 44v56c0 24.262 19.738 44 44 44h8c24.262 0 44-19.738 44-44v-56c0-24.262-19.738-44-44-44zm20 100c0 11.028-8.972 20-20 20h-8c-11.028 0-20-8.972-20-20v-56c0-11.028 8.972-20 20-20h8c11.028 0 20 8.972 20 20v56z" fill="#db6b5e"></path>
            </svg>

            <h2 class="title"><?= Html::encode($this->title) ?></h2>

            <div class="alert">
                <p>Rất tiếc!</p>
                <p>URL bạn yêu cầu không tồn tại hoặc đã được di chuyển đến địa chỉ khác.</p>
                <p>Bạn có thể quay lại trang chủ hoặc sử dụng chức năng tìm kiếm:</p>
            </div>

            <div class="search-box">
                <gcse:search></gcse:search>
            </div>
            <?php
            break;
        default:
            ?>
            <h2 class="title"><?= Html::encode($this->title) ?></h2>

            <div class="alert">
                <?= nl2br(Html::encode($message)) ?>
            </div>

            <div class="alert">
                <p>The above error occurred while the Web server was processing your request.</p>
                <p>Please contact us if you think this is a server error. Thank you.</p>
            </div>
            <?php
    }
    ?>

</div>

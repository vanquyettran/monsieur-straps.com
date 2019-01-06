<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/26/2018
 * Time: 12:12 AM
 */
use common\models\Image;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * @var \backend\models\Product $model
 */

?>
<style>
    #detailedImageList {
        padding: 0;
        margin-top: 2rem;
    }
    #detailedImageList li {
        cursor: grab;
        padding: 1rem;
        border: 1px solid #eee;
        list-style: none;
    }
    #detailedImageList li:not(:first-child) {
        margin-top: -1px;
    }
    #detailedImageList li > :not(:last-child) {
        margin-right: 1rem;
    }
    #detailedImageList li.sortable-ghost {
        opacity: 0;
    }
    #detailedImageList li.sortable-chosen {
        color: #f42;
    }

    #saveMessage {
        font-weight: bold;
    }
</style>

<?php
/**
 * @var \backend\models\ProductToDetailedImage[] $detailedImages
 */
$detailedImages = $model->getProductToDetailedImages()
    ->orderBy('sort_order ASC')
    ->all();

?>

<h1><?= $model->name ?></h1>

<div>
    <span>Drag-drop to change the order.</span>
    <span id="saveMessage"></span>
</div>

<ul id="detailedImageList">
<?php
foreach ($detailedImages as $item) {
    ?>
    <li data-detailed-image-id="<?= $item->detailed_image_id ?>">
        <?= $item->detailedImage->img(null, ['width' => '50']) ?>
        <span><?= $item->detailedImage->name ?></span>
    </li>
    <?php
}
?>
</ul>

<script>
    var detailedImageList = document.getElementById('detailedImageList');
    var saveMessage = document.getElementById('saveMessage');

    // Simple list
    Sortable.create(detailedImageList, {
        animation: 150,
        onUpdate: function (event) {
            saveChange();
        }
    });

    function saveChange() {
        var detailed_image_ids = [].map.call(detailedImageList.children, function (item) {
            return +item.getAttribute('data-detailed-image-id');
        });
        var formData = new FormData();
        formData.append('product_id', <?= $model->id ?>);
        formData.append('detailed_image_ids', JSON.stringify(detailed_image_ids));
        xhr = new XMLHttpRequest();
        xhr.open('POST', '<?= \yii\helpers\Url::to(['/api/product-reorder-detailed-images']) ?>');
        xhr.onload = function () {
            saveMessage.innerHTML = '<span class="text-success">Saved!</span>';
        };
        xhr.onerror = function () {
            saveMessage.innerHTML = '<span class="text-error">Failed to save :(</span>';
        };
        xhr.onloadend = function () {
            setTimeout(function () {
                saveMessage.textContent = '';
            }, 1000);
        };
        xhr.send(formData);
        saveMessage.innerHTML = '<span class="text-info">Saving...</span>';
    }
</script>
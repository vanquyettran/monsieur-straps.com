<?php
/**
 * Created by PhpStorm.
 * User: VanQuyet
 * Date: 4/4/2019
 * Time: 1:15 PM
 */

/**
 * @var $funcNum
 */
?>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="image-select" class="control-label">Select an image:</label>
            <select id="image-select" class="form-control"></select>
        </div>
        <div class="form-group">
            <button type="button" id="apply-btn" class="btn btn-success">Use this image</button>
        </div>
    </div>
</div>
<script>
    var applyBtn = document.querySelector('#apply-btn');
    applyBtn.addEventListener('click', function () {
        var imageSelect = document.querySelector('#image-select');
        var previewImage = document.querySelector('#image-preview-wrapper--image-select img');
        var imageId = imageSelect.value;
        var imageSrc = previewImage ? previewImage.src : undefined;
        if (imageId && imageSrc) {
            var fileUrl = imageSrc + '?image_id=' + imageId;
            /**
             * https://ckeditor.com/docs/ckeditor4/latest/guide/dev_file_browser_api.html
             */
            window.opener.CKEDITOR.tools.callFunction(<?php echo json_encode($funcNum); ?>, fileUrl, '');
            window.close();
        } else {
            alert('Please select an image ' + imageId + ' / ' + imageSrc);
        }
    });
</script>
<?php
$this->registerJs(
    '
    initPortableImageUploader("image-select");
    ',
    \yii\web\View::POS_READY
);

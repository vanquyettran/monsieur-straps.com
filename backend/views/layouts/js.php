<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var View $this
 */
?>

<!-- CKEditor -->
<script src="<?= Yii::getAlias('@web/libs/ckeditor/ckeditor.js') ?>"></script>
<script>
    function initCKEditor(id, use_basic) {
        /**
         * Documentation:
         * http://sdk.ckeditor.com/samples/fileupload.html
         * http://docs.cksource.com/CKEditor_3.x/Developers_Guide/File_Browser_(Uploader)
         */
        CKEDITOR.timestamp = Math.floor(new Date() / 1800000);
        var editor;

        if (use_basic) {
            editor = CKEDITOR.replace(id, {
                toolbar: [
                    ['Bold', 'Italic', 'NumberedList', 'BulletedList'],
                    ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                    ['Styles', 'Format', 'Font', 'FontSize'],
                    ['Link', 'TextColor', 'BGColor']
                ],

                // Configure your file manager integration. This example uses CKFinder 3 for PHP.
                filebrowserImageBrowseUrl: '<?= Yii::getAlias('@web/libs/ckfinder/ckfinder.html?type=Images') ?>',
                filebrowserUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                filebrowserImageUploadUrl: '<?php echo Url::to([
                    '/api/ckeditor-upload-image',
                    Yii::$app->request->csrfParam => Yii::$app->request->csrfToken
                ]) ?>'
            });
        } else {
            editor = CKEDITOR.replace(id, {
                extraPlugins: 'uploadimage',
                height: 300,

                // Configure your file manager integration. This example uses CKFinder 3 for PHP.
                filebrowserImageBrowseUrl: '<?= Yii::getAlias('@web/libs/ckfinder/ckfinder.html?type=Images') ?>',
                filebrowserUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                filebrowserImageUploadUrl: '<?php echo Url::to([
                    '/api/ckeditor-upload-image',
                    Yii::$app->request->csrfParam => Yii::$app->request->csrfToken
                ]) ?>',

                // The following options are not necessary and are used here for presentation purposes only.
                // They configure the Styles drop-down list and widgets to use classes.

                stylesSet: [
                    {name: 'Narrow image', type: 'widget', widget: 'image', attributes: {'class': 'image-narrow'}},
                    {name: 'Wide image', type: 'widget', widget: 'image', attributes: {'class': 'image-wide'}}
                ],

                // Load the default contents.css file plus customizations for this sample.
                contentsCss: [CKEDITOR.basePath + 'contents.css', 'http://sdk.ckeditor.com/samples/assets/css/widgetstyles.css'],

                // Configure the Enhanced Image plugin to use classes instead of styles and to disable the
                // resizer (because image size is controlled by widget styles or the image takes maximum
                // 100% of the editor width).
                image2_alignClasses: ['image-align-left', 'image-align-center', 'image-align-right'],
                image2_disableResizer: true
            });
        }

        for(var instanceName in CKEDITOR.instances) {
            if (CKEDITOR.instances.hasOwnProperty(instanceName)) {
                CKEDITOR.instances[instanceName].on('change', function () {
                    this.updateElement();
                });
            }
        }

        return editor;
    }
</script>

<!-- Auto Fill Values -->
<script>
    window.addEventListener("load", initInputValueAutoFillers);
    window.addEventListener("load", initInputCharacterCounters);
    function initInputValueAutoFillers() {
        !function (slug, name, heading, page_title, meta_title, desc, meta_desc) {
            ["change"].forEach(function (event) {
                if (name && slug && !slug.classList.contains("disable-auto-fill")) {
                    name.addEventListener(event, function () {
                        slug.value || (slug.value = vi_slugify(name.value));
                    });
                }
                if (name && heading && !heading.classList.contains("disable-auto-fill")) {
                    name.addEventListener(event, function () {
                        heading.value || (heading.value = name.value);
                    });
                }
                if (name && page_title && !page_title.classList.contains("disable-auto-fill")) {
                    name.addEventListener(event, function () {
                        page_title.value || (page_title.value = name.value);
                    });
                }
                if (name && meta_title && !meta_title.classList.contains("disable-auto-fill")) {
                    name.addEventListener(event, function () {
                        meta_title.value || (meta_title.value = name.value);
                    });
                }
                if (desc && meta_desc && !meta_desc.classList.contains("disable-auto-fill")) {
                    desc.addEventListener(event, function () {
                        meta_desc.value || (meta_desc.value = desc.value);
                    });
                }
            });

        }(
            document.querySelector("[name$='[slug]']"),
            document.querySelector("[name$='[name]']"),
            document.querySelector("[name$='[heading]']"),
            document.querySelector("[name$='[page_title]']"),
            document.querySelector("[name$='[meta_title]']"),
            document.querySelector("[name$='[description]']"),
            document.querySelector("[name$='[meta_description]']")
        );
    }
    function initInputCharacterCounters() {
        [].forEach.call(document.querySelectorAll("form input[type='text'].form-control, form textarea.form-control"), function (elem) {
            if (elem && !elem.classList.contains("disable-counter") && elem.getAttribute('data-counter') !== "false") {
                var counter = document.createElement("sup");
                elem.parentNode.insertBefore(counter, elem);
                ["keyup", "keydown", "change", "propertychange", "click", "input", "paste"].forEach(function (event) {
                    elem.addEventListener(event, function () {
                        counter.innerHTML = elem.value.length + "/"
                            + vi_slugify(elem.value).split("-")
                                .filter(function (item) { return !!item; }).length;
                    });
                });
            }
        });
    }
    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/(\w)\'/g, '$1')           // Special case for apostrophes
            .replace(/[^a-z0-9_\-]+/g, '-')     // Replace all non-word chars with -
            .replace(/\-\-+/g, '-')             // Replace multiple - with single -
            .replace(/^-+/, '')                 // Trim - from start of text
            .replace(/-+$/, '');                // Trim - from end of text
    }
    function lowercase_vi_filter(text) {
        return text.toString().toLowerCase()
            .replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a")
            .replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e")
            .replace(/ì|í|ị|ỉ|ĩ/g, "i")
            .replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o")
            .replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u")
            .replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y")
            .replace(/đ/g, "d");
    }
    function vi_slugify(text) {
        return slugify(lowercase_vi_filter(text));
    }
</script>

<!-- Datetime picker -->
<link href="<?= Yii::getAlias('@web/libs/datetimepicker/datetimepicker.css') ?>" rel="stylesheet">
<script src="<?= Yii::getAlias('@web/libs/datetimepicker/datetimepicker.js') ?>"></script>
<style>
    .datetimePicker__widget {
        display: none;
        position: absolute;
        z-index: 999;
        background: #fff;
    }
    .datetimePicker__widget.active {
        display: table;
    }
</style>
<script>
    function initDatetimePicker(datetimeInput, dateOnly) {
        if (!datetimeInput) {
            throw Error("`initDateTimePicker()`: Missing argument `datetimeInput`.");
        }

        if ("string" === typeof datetimeInput) {
            datetimeInput = document.getElementById(datetimeInput);
        }

        var visibleDatetimeInput = document.createElement("input");
        visibleDatetimeInput.setAttribute("type", "text");
        visibleDatetimeInput.setAttribute("class", "form-control");

        datetimeInput.setAttribute("type", "hidden");

        var picker = new DatetimePicker(
            datetimeInput.value ? new Date(datetimeInput.value.replace(' ', 'T')) : new Date(),
            {
                "weekdays": ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
                "months": ["Giêng", "Hai", "Ba", "Tư", "Năm", "Sáu", "Bảy", "Tám", "Chín", "Mười", "Mười Một", "Mười Hai"],
                "onChange": function (current) {
                    exportValue();
                },
                "classNamePrefix": "datetimePicker__"
            }
        );

        var widget = picker.widget(
            {
                "yearMonthBlock": {
                    "items": ["yearCell", "monthCell"]
                },
                "dateBlock": {
                    "onClick": function (current) {

                    }
                },
                "timeBlock": {
                    "items": ["hoursCell", "minutesCell", "secondsCell"]
                },
                "controlBlock": {
                    "items": ["set2nowCell", "resetCell", "submitCell"],
                    "onSubmit": function (current) {
                        widget.classList.remove("active");
                    }
                },
                "items": dateOnly
                    ? ["yearMonthBlock", "dateBlock", "controlBlock"]
                    : ["yearMonthBlock", "dateBlock", "timeBlock", "controlBlock"]
            }
        );

        datetimeInput.addEventListener("change", function () {
            var time = (new Date(datetimeInput.value.replace(' ', 'T'))).getTime();
            if (!isNaN(time)) {
                picker.current.time = time;
            } else {
                exportValue();
            }
        });

        visibleDatetimeInput.addEventListener("change", function () {
            var time = (new Date(visibleDatetimeInput.value.replace(' ', 'T'))).getTime();
            if (!isNaN(time)) {
                picker.current.time = time;
            } else {
                exportValue();
            }
        });

        visibleDatetimeInput.addEventListener("focusin", function () {
            picker.current.time = (new Date(datetimeInput.value.replace(' ', 'T'))).getTime();
            widget.classList.add("active");
        });

        document.addEventListener("click", function (event) {
            if (event.target !== visibleDatetimeInput &&
                event.target !== widget &&
                !checkIsContains(widget, event.target) &&
                checkIsContains(document.body, event.target)
            ) {
                widget.classList.remove("active");
            }
        });

        datetimeInput.parentNode.insertBefore(visibleDatetimeInput, datetimeInput);
        datetimeInput.parentNode.insertBefore(widget, datetimeInput);

        // some functions:

        exportValue();

        function exportValue() {
            var current = picker.current;
            datetimeInput.value
                = visibleDatetimeInput.value
                = (dateOnly ? "Y-m-d" : "Y-m-d H:i:s")
                    .replace(/Y/g, current.year)
                    .replace(/m/g, pad(current.month + 1))
                    .replace(/d/g, pad(current.date))
                    .replace(/H/g, pad(current.hours))
                    .replace(/i/g, pad(current.minutes))
                    .replace(/s/g, pad(current.seconds));
        }

        function pad(number) {
            return (number < 10 ? "0" : "") + number;
        }

        function checkIsContains(root, elem) {
            if (root.contains(elem)) {
                return true;
            } else {
                return [].some.call(root.children, function (child) {
                    return checkIsContains(child, elem);
                });
            }
        }
    }
</script>

<!-- Sortable -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.6.1/Sortable.min.js"></script>

<!-- Select2 -->
<?php

$this->registerCssFile(
    'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css',
    ['depends' => \yii\web\YiiAsset::className()]
);
$this->registerJsFile(
    'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.js',
    ['depends' => \yii\web\JqueryAsset::className()]
);
$this->registerJsFile(
    'http://select2.github.io/select2/js/jquery-ui-1.8.20.custom.min.js',
    ['depends' => \yii\web\JqueryAsset::className()]
);

?>
<!-- Portable Image Uploader -->
<script>
    <?php $this->beginBlock('portable_image_uploader') ?>

    function initPortableImageUploader(img_select_id, multiple) {
        multiple = !!multiple;
        var img_select = $("#" + img_select_id);
        var formatRepo = function (repo) {
            if (repo.loading) {
                return repo.text;
            }
            var markup =
                '<div class="row">' +
                    '<div class="col-md-2">' +
                        '<img src="' + repo.source + '" style="max-width: 100%; max-height: 50px" />' +
                    '</div>' +
                    '<div class="col-md-6"><b>' + repo.name + '</b></div>' +
                    '<div class="col-md-2">' + repo.width + 'x' + repo.height + '</div>' +
                    '<div class="col-md-2">' + repo.aspect_ratio + '</div>' +
                '</div>';
            return '<div style="overflow: hidden;">' + markup + '</div>';
        };
        var formatRepoSelection = function (repo) {
            return repo.name || repo.text;
        };
        img_select.select2({
            allowClear: true,
            placeholder: {
                id: "",
                placeholder: multiple ? "" : "Select One"
            },
            multiple: multiple,
            ajax: {
                url: "<?= Url::to(['/api/find-images']) ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 0,
            templateResult: function(item) {
                /* FIX */
                if (item.placeholder) return item.placeholder;
                return formatRepo(item);
            },
            templateSelection: function (item) {
                /* FIX */
                if (item.placeholder) return item.placeholder;
                return formatRepoSelection(item);
            }
        });

        var img_select_elm = document.getElementById(img_select_id);
        var img_preview_wrapper = document.createElement("div");
        if (multiple) {
            Sortable.create(img_preview_wrapper, {
                animation: 150,
                onUpdate: function (event) {
                    img_select.empty();
                    [].forEach.call(img_preview_wrapper.children, function (img_preview) {
                        var image_id = img_preview.getAttribute('data-image-id');
                        var image_name = img_preview.getAttribute('data-image-name');
                        img_select
                            .append('<option value="' + image_id + '">' + image_name + '</option>')
                            .val(img_select.val().concat(image_id))
                    });
                    img_select.trigger("change");
                    console.log(img_select);
                }
            });
        }
        var error_msg = document.createElement("div");
        var process_bar = document.createElement("div");
        var img_input = document.createElement("input");
        img_select_elm.parentNode.insertBefore(img_preview_wrapper, img_select_elm);
        img_select_elm.parentNode.insertBefore(error_msg, img_select_elm);
        img_select_elm.parentNode.insertBefore(process_bar, img_select_elm);
        img_select_elm.parentNode.insertBefore(img_input, img_select_elm);
        img_preview_wrapper.className = "image-preview-wrapper";
        img_input.className = "image-file-input";
        img_input.type = "file";
        img_input.accept = "image/*";
        img_input.multiple = multiple;
        img_input.addEventListener("change", function (event) {
            [].forEach.call(this.files, function (file) {
                console.log(file);
                var fd = new FormData();
                fd.append("image_file", file);
                fd.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->csrfToken ?>');
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '<?= Url::to(['/api/upload-image'], true) ?>', true);
                xhr.upload.onprogress = function(event) {
                    if (event.lengthComputable) {
                        var percentComplete = (event.loaded / event.total) * 100;
                        process_bar.innerHTML = percentComplete + '% uploaded';
                    }
                };
                xhr.onloadend = function() {
                    process_bar.innerHTML = '';
                };
                xhr.onload = function() {
                    if (this.status == 200) {
                        console.log("img_select.val", img_select.val());
                        var resp = JSON.parse(this.response);
                        console.log('Server got:', resp);
                        if (resp.success) {
                            img_select
                                .append('<option value="' + resp.image.id + '">' + resp.image.name + '</option>')
                                .val(multiple ? img_select.val().concat(resp.image.id) : resp.image.id)
                                .trigger("change");

                        } else {
                            error_msg.innerHTML += '<div class="text-danger">' + JSON.stringify(resp.errors) + '</div>';
                        }
                    } else {
                        error_msg.innerHTML += '<div class="text-danger">Upload failed! Please try again</div>';
                    }
                };
                xhr.send(fd);
            });

        });

        var loadPreview = function () {
            var img_select_val = img_select.val();
            var ids = multiple ? img_select_val : (img_select_val !== null ? [img_select_val] : null);
            if (ids === null) {
                setTimeout(function () {
                    img_preview_wrapper.innerHTML = "";
                }, 500);
            } else {
                if (ids.length > 0) {
                    var img_preview_list = [];
                    var res_count = 0;
                    [].forEach.call(ids, function (id) {
                        var fd = new FormData();
                        fd.append("id", id);
                        fd.append("<?= Yii::$app->request->csrfParam ?>", "<?= Yii::$app->request->csrfToken ?>");
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "<?= Url::to(['/api/find-image'], true) ?>", true);
                        xhr.onloadend = function() {
                            res_count++;
                            if (res_count === ids.length) {
                                img_preview_wrapper.innerHTML = "";
                                [].forEach.call(ids, function (id) {
                                    if (img_preview_list["@" + id]) {
                                        img_preview_wrapper.appendChild(img_preview_list["@" + id]);
                                    }
                                });
                            }
                        };
                        xhr.onload = function() {
                            if (this.status == 200) {
                                if (this.responseText) {
                                    var resp = JSON.parse(this.responseText);
                                    console.log("RES", resp);
                                    if (resp) {
                                        var img_preview = document.createElement("div");
                                        img_preview.className = "image-preview-item";
                                        img_preview.setAttribute('data-image-id', resp.id);
                                        img_preview.setAttribute('data-image-name', resp.name);
                                        var image = new Image();
                                        image.src = resp.source;
                                        img_preview.appendChild(image);
                                        var info = document.createElement("div");
                                        info.className = "image-info";
                                        info.innerHTML = "<div><b>" + resp.name + "</b></div>"
                                            + "<div><i>" + resp.width + "x" + resp.height + " | " + resp.aspect_ratio + "</i></div>";
                                        img_preview.appendChild(info);
                                        img_preview_list["@" + id] = img_preview;
                                    } else {
                                        error_msg.innerHTML += '<div class="text-danger">Image#' + id + ' was not found!</div>';
                                    }
                                } else {
                                    error_msg.innerHTML += '<div class="text-danger">No response from Image#' + id + '!</div>';
                                }
                            } else {
                                error_msg.innerHTML += '<div class="text-danger">Failed to get Image#' + id + '!</div>';
                            }
                        };
                        xhr.send(fd);
                    });
                } else {
                    setTimeout(function () {
                        img_preview_wrapper.innerHTML = "";
                    }, 500);
                }
            }
        };

        // Load preview when init
        loadPreview();

        // On change
        img_select.on("change", function (event) {
            loadPreview();
        });
    }

    <?php $this->endBlock() ?>
</script>
<?php
$this->registerJs($this->blocks['portable_image_uploader'], View::POS_READY);
?>


<!-- Remote Drop Down List -->
<script>
    <?php $this->beginBlock('remote_drop_down_list') ?>
    function initRemoteDropDownList(select_id, search_url, multiple) {
        multiple = !!multiple;
        var formatRepo = function (repo) {
            if (repo.loading) {
                return repo.text;
            }
            var markup =
                repo.hasOwnProperty("avatar")
                    ? '<div class="row">' +
                        '<div class="col-md-2">' +
                            '<img src="' + repo.avatar + '" style="max-width: 100%; max-height: 50px" />' +
                        '</div>' +
                        '<div class="col-md-10"><b>' + repo.name + '</b></div>' +
                        '</div>'
                    : repo.name;
            return '<div style="overflow: hidden;">' + markup + '</div>';
        };
        var formatRepoSelection = function (repo) {
            return repo.name || repo.text;
        };
        var select = $("#" + select_id);
        select.select2({
            allowClear: true,
            placeholder: {
                id: "",
                placeholder: "Select One"
            },
            multiple: multiple,
            ajax: {
                url: search_url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 0,
            templateResult: function(item) {
                /* FIX */
                if (item.placeholder) return item.placeholder;
                return formatRepo(item);
            },
            templateSelection: function (item) {
                /* FIX */
                if (item.placeholder) return item.placeholder;
                return formatRepoSelection(item);
            }
        });
    }
    <?php $this->endBlock() ?>
</script>
<?php
$this->registerJs($this->blocks['remote_drop_down_list'], View::POS_READY);
?>
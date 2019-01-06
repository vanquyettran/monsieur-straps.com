<?php
/**
 * @var $this \yii\web\View
 */
use common\models\SiteParam;
use common\models\UrlParam;
use yii\helpers\Url;
?>
<script>
    function getResponsiveMode() {

        // for sync with CSS

        if (window.innerWidth > 780) {
            return 'desktop';
        }

        if (window.innerWidth > 640) {
            return 'tablet';
        }

        return 'mobile';
    }

    /**
     *
     * @param {HTMLElement} container
     * @param {HTMLElement} button
     * @param {string} viewId
     * @param {object} jsonParams
     * @param {int} page
     * @param {function|undefined} onSuccess
     * @param {function|undefined} onError
     */
    function loadMoreArticles(container, button, viewId, jsonParams, page, onSuccess, onError) {
        button.disabled = true;
        button.classList.add("loading");

        var xhttp = new XMLHttpRequest();

        xhttp.onload = function () {
            var data = JSON.parse(xhttp.responseText);
            container.innerHTML += data.content;
            if (!data.hasMore) {
                button.parentNode.removeChild(button);
            }
            if ("function" === typeof onSuccess) {
                onSuccess();
            }
        };
        xhttp.onloadend = function () {
            button.disabled = false;
            button.classList.remove("loading");

            initFocusable();
            window.TextWrapper.elementTextLinesLimit('[data-max-line-count]');
        };
        xhttp.onerror = function () {
            if ("function" === typeof onError) {
                onError();
            }
        };

        xhttp.open("POST", "<?= Url::to(['article/ajax-get-items'], true) ?>");
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("<?=
            Yii::$app->request->csrfParam . '=' . Yii::$app->request->csrfToken
            . '&' . UrlParam::VIEW_ID . '=" + viewId + "'
            . '&' . UrlParam::JSON_PARAMS . '=" + JSON.stringify(jsonParams) + "'
            . '&' . UrlParam::PAGE . '=" + page + "'
            ?>");
    }

    /**
     *
     * @param {int} id
     * @param {string} field
     * @param {int} value
     */
    function updateArticleCounter(id, field, value) {
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "<?= Url::to(['article/ajax-update-counter']) ?>");
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("<?=
            Yii::$app->request->csrfParam . '=' . Yii::$app->request->csrfToken
            . '&' . UrlParam::FIELD . '=" + field + "'
            . '&' . UrlParam::VALUE . '=" + value + "'
            . '&' . UrlParam::ID . '=" + id + "'
            ?>");
    }

    /**
     *
     * @param {HTMLElement} container
     * @param {HTMLElement} button
     * @param {string} viewId
     * @param {object} jsonParams
     * @param {int} page
     * @param {function|undefined} onSuccess
     * @param {function|undefined} onError
     */
    function loadMoreProducts(container, button, viewId, jsonParams, page, onSuccess, onError) {
        button.disabled = true;
        button.classList.add("loading");

        var xhttp = new XMLHttpRequest();

        xhttp.onload = function () {
            var data = JSON.parse(xhttp.responseText);
            container.innerHTML += data.content;
            if (!data.hasMore) {
                button.parentNode.removeChild(button);
            }
            if ("function" === typeof onSuccess) {
                onSuccess();
            }
        };
        xhttp.onloadend = function () {
            button.disabled = false;
            button.classList.remove("loading");
            initFocusable();
        };
        xhttp.onerror = function () {
            if ("function" === typeof onError) {
                onError();
            }
        };

        xhttp.open("POST", "<?= Url::to(['product/ajax-get-items'], true) ?>");
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("<?=
            Yii::$app->request->csrfParam . '=' . Yii::$app->request->csrfToken
            . '&' . UrlParam::VIEW_ID . '=" + viewId + "'
            . '&' . UrlParam::JSON_PARAMS . '=" + JSON.stringify(jsonParams) + "'
            . '&' . UrlParam::PAGE . '=" + page + "'
            ?>");
    }

    /**
     *
     * @param {int} id
     * @param {string} field
     * @param {int} value
     */
    function updateProductCounter(id, field, value) {
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "<?= Url::to(['product/ajax-update-counter']) ?>");
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("<?=
            Yii::$app->request->csrfParam . '=' . Yii::$app->request->csrfToken
            . '&' . UrlParam::FIELD . '=" + field + "'
            . '&' . UrlParam::VALUE . '=" + value + "'
            . '&' . UrlParam::ID . '=" + id + "'
            ?>");
    }



    /**
     * Determine the mobile operating system.
     * This function returns one of 'iOS', 'Android', 'Windows Phone', or 'unknown'.
     *
     * @returns {String}
     */
    function getMobileOperatingSystem() {
        var userAgent = navigator.userAgent || navigator.vendor || window.opera;

        // Windows Phone must come first because its UA also contains "Android"
        if (/windows phone/i.test(userAgent)) {
            return "Windows Phone";
        }

        if (/android/i.test(userAgent)) {
            return "Android";
        }

        // iOS detection from: http://stackoverflow.com/a/9039885/177710
        if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
            return "iOS";
        }

        return "unknown";
    }
</script>

<script>
    /**
     *
     * @param {Node|string} content
     * @param {object?} options
     */
    function popup(content, options) {
        options = options || {};

        var html = document.querySelector('html');

        var closeBtn = elm(
            "button",
            {html: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">\
                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>\
            </svg>'},
//            elm('i', null, {
//                'class': 'icon close-icon',
//                style: style({
//                    display: 'block'
//                })
//            }),
            {
                'class': 'popup-close-button',
                type: "button",
                style: style({
                    color: "white",
                    position: "absolute",
                    top: '5px',
                    right: '5px',
                    border: "none",
                    boxShadow: "none",
                    zIndex: 9999,
                    display: "block",
                    fontSize: "2rem"
                }),
                title: 'Đóng'
            }
        );

        var image = content instanceof HTMLElement ? content : elm(
            'div',
            content,
            {
                style: style({
                    padding: "30px"
                })
            }
        );

        var container = elm(
            "div",
            elm(
                "div",
                [image, closeBtn],
                {
                    'class': 'popup-overlay',
                    style: style({
                        display: "block"//,
//                        marginTop: "10vh",
//                        marginBottom: "10vh"
                    })
                }
            ),
            {
                'class': options.className ? 'popup ' + options.className : 'popup',
                style: style({
                    position: "fixed",
                    top: 0,
                    left: 0,
                    bottom: 0,
                    right: 0,
                    display: "flex",
                    flexDirection: "column",
                    alignItems: "center",
                    justifyContent: "flex-start",
                    zIndex: 9999,
                    backgroundColor: "rgba(0, 0, 0, 0.5)",
                    overflowY: "auto",
                    "-webkit-overflow-scrolling": "touch"
                }),
                open: function () {
                    html && html.classList.add('has-popup');
                    container.parentNode || document.body.appendChild(container);
                },
                close: function () {
                    html && html.classList.remove('has-popup');
                    container.parentNode && container.parentNode.removeChild(container);
                }
            }
        );

        document.addEventListener("click", function (event) {
            container.contains(event.target)
            && image !== event.target
            && !image.contains(event.target)
            && container.close();
        });

        closeBtn.addEventListener("click", container.close);

        container.open();

        return container;
    }

    /**
     * creates an element with certain content and attributes
     *
     * @param {string} elementName
     * @param {string|Number|Node|Node[]|undefined ?} content
     * @param {object|undefined ?} attributes
     * @return {Element}
     */
    function elm(elementName, content, attributes) {
        var element = document.createElement(elementName);
        appendChildren(element, content);
        setAttributes(element, attributes);
        return element;
    }

    /**
     * appends children into a node
     *
     * @param {HTMLElement} element
     * @param {string|Node|Node[]} content
     */
    function appendChildren(element, content) {
        var append = function (t) {
            if (/string|number/.test(typeof t)) {
                var textNode = document.createTextNode(t);
                element.appendChild(textNode);
            } else if (t instanceof Node) {
                element.appendChild(t);
            } else if (t && t.html) {
                element.innerHTML += t.html;
            }
        };
        if (content instanceof Array) {
            content.forEach(function (item) {
                append(item);
            });
        } else {
            append(content);
        }
    }

    /**
     * sets attributes for a node
     *
     * @param {HTMLElement} element
     * @param {object} attributes
     */
    function setAttributes(element, attributes) {
        if (attributes) {
            var attrName;
            for (attrName in attributes) {
                if (attributes.hasOwnProperty(attrName)) {
                    var attrValue = attributes[attrName];
                    switch (typeof attrValue) {
                        case "string":
                        case "number":
                            element.setAttribute(attrName, attrValue);
                            break;
                        case "function":
                        case "boolean":
                            element[attrName] = attrValue;
                            break;
                        default:
                    }
                }
            }
        }
    }

    function empty(element) {
        while (element.firstChild) {
            element.removeChild(element.firstChild);
        }
    }

    /**
     *
     * @param {string} str
     * @return {string}
     */
    function camelCaseToDash(str) {
        return str.replace( /([a-z])([A-Z])/g, '$1-$2' ).toLowerCase();
    }

    /**
     *
     * @param {object} obj
     * @return {string}
     */
    function style(obj) {
        var result_array = [];
        var attrName;
        for (attrName in obj) {
            if (obj.hasOwnProperty(attrName)) {
                result_array.push(camelCaseToDash(attrName) + ": " + obj[attrName]);
            }
        }
        return result_array.join("; ");
    }

    /**
     *
     * @param {number} number
     * @param {number?} n: length of decimal
     * @param {string?}   s: sections delimiter
     * @param {string?}   c: decimal delimiter
     * @return {string}
     */
    formatNumber = function(number, n, s, c) {
        var re = '\\d(?=(\\d{3})+' + (n > 0 ? '\\D' : '$') + ')';
        var num = number.toFixed(Math.max(0, ~~n));

        return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
    };

    /**
     * @param {number} number
     * @return {string}
     */
    formatVnd = function (number) {
        return formatNumber(number, 0, '.', ',') + 'đ';
    };

</script>

<script>
    // handle when the user hovers (PC) or touches (mobile)
    function initFocusable() {
        var focusableList = document.querySelectorAll('[data-focusable]');
        [].forEach.call(focusableList, function (focusableEl) {
            focusableEl.addEventListener('mouseover', function () {
                this.classList.add('focused');
            });
            focusableEl.addEventListener('touchstart', function () {
                this.classList.add('focused');
            });
            focusableEl.addEventListener('mouseout', function () {
                this.classList.remove('focused');
            });
            focusableEl.addEventListener('touchend', function () {
                this.classList.remove('focused');
            });
            focusableEl.addEventListener('touchcancel', function () {
                this.classList.remove('focused');
            });
        });
    }

    !function (window) {
        var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) {
            return typeof obj;
        } : function (obj) {
            return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
        };
        /**
         *
         * @param {string} text
         * @param {string|object} font
         * @param {number} maxWidth
         * @param {string?} longWordContinue
         * @param {number?} maxLineCount
         * @param {string?} ellipsis
         * @return {Array} an array of lines after wrapped
         */
        var wrapText = function wrapText(text, font, maxWidth, longWordContinue, maxLineCount, ellipsis) {
            if ('string' !== typeof text) {
                throw new Error('text must be a string. Got: ' + (typeof text === 'undefined' ? 'undefined' : _typeof(text)));
            }

            var lines = text.split('\n');

            var canvas = window.document.createElement('canvas');
            var ctx = canvas.getContext('2d');

            if ('string' === typeof font) {
                ctx.font = font;
            } else if ('object' === (typeof font === 'undefined' ? 'undefined' : _typeof(font))) {
                ctx.font = [font['style'], font['weight'], font['size'], font['family']].filter(function (item) {
                    return 'undefined' !== typeof item;
                }).join(' ');
            } else {
                throw new Error('font must be a string or an object. Got: ' + (typeof font === 'undefined' ? 'undefined' : _typeof(font)));
            }

            if ('number' !== typeof maxWidth) {
                throw new Error('maxWidth must be a number. Got: ' + (typeof maxWidth === 'undefined' ? 'undefined' : _typeof(maxWidth)));
            }

            if ('number' !== typeof maxLineCount) {
                maxLineCount = 0;
            }

            if ('string' !== typeof ellipsis) {
                ellipsis = '...';
            }

            if ('string' !== typeof longWordContinue) {
                longWordContinue = '-';
            }

            var newLines = [];
            lines.forEach(function (line) {
                if (line.trim().length > 0) {
                    var words = line.split(/(\s+)/);
                    // console.log('words', words);

                    var newWords = [];
                    words.forEach(function (word) {
                        var newWord = '';
                        word.split('').forEach(function (char) {
                            var tryNewWord = newWord + char;
                            var tryNewWordWidth = ctx.measureText(tryNewWord).width;
                            if (tryNewWordWidth > maxWidth && newWord.length > 0) {
                                newWords.push(newWord);
                                newWord = longWordContinue + char;
                            } else {
                                newWord = tryNewWord;
                            }
                        });
                        if (newWord.length > 0) {
                            newWords.push(newWord);
                        }
                    });

                    // console.log('new words', newWords);
                    var newLine = '';
                    newWords.forEach(function (word) {
                        var tryNewLine = newLine.trim().length > 0 ? newLine + word : word;
                        var tryNewLineWidth = ctx.measureText(tryNewLine.trim()).width;
                        if (tryNewLineWidth > maxWidth && newLine.trim().length > 0) {
                            newLines.push(newLine.trim());
                            newLine = word;
                        } else {
                            newLine = tryNewLine;
                        }
                    });
                    if (newLine.trim().length > 0) {
                        newLines.push(newLine.trim());
                    }
                } else {
                    newLines.push('');
                }
            });

            // console.log('new lines', newLines);
            var maxLineWidth = 0;
            newLines.forEach(function (line) {
                var lineWidth = ctx.measureText(line).width;
                if (lineWidth > maxLineWidth) {
                    maxLineWidth = lineWidth;
                }
            });

            var newLineTrackers = [];
            if (maxLineCount > 0) {
                newLines.forEach(function (line, index) {
                    if (index + 1 <= maxLineCount) {
                        if (newLines[index + 1] && index + 2 > maxLineCount) {
                            while (line !== '' && ctx.measureText(line + ellipsis).width > maxWidth) {
                                line = line.slice(0, -1);
                                // console.log(line);
                            }
                            line += ellipsis;
                        }
                        newLineTrackers.push({
                            content: line,
                            width: ctx.measureText(line).width
                        });
                    }
                });
            } else {
                newLineTrackers = newLines.map(function (line) {
                    return {
                        content: line,
                        width: ctx.measureText(line).width
                    };
                });
            }

            return newLineTrackers;
        };

        /**
         *
         * @param {HTMLElement|HTMLElement[]|NodeList|string} wrappedEls
         */
        var elementTextLinesLimit = function elementTextLinesLimit(wrappedEls) {
            if (wrappedEls instanceof HTMLElement) {
                wrappedEls = [wrappedEls];
            } else if (wrappedEls instanceof Array || wrappedEls instanceof NodeList) {
                [].forEach.call(wrappedEls, function (wrappedEl) {
                    if (!(wrappedEl instanceof HTMLElement)) {
                        throw new Error('Element is not a HTMLElement: ' + wrappedEl.toString());
                    }
                });
            } else if ('string' === typeof wrappedEls) {
                wrappedEls = document.querySelectorAll(wrappedEls);
            } else {
                throw new Error('wrappedEls is not a query string, a HTMLElement or a List of HTMLElement');
            }

            var updateText = function updateText(wrappedEls) {
                [].forEach.call(wrappedEls, function (wrappedEl) {
                    var textToWrap = wrappedEl.getAttribute('data-text-to-wrap');
                    if (null === textToWrap) {
                        textToWrap = wrappedEl.textContent;
                        textToWrap = textToWrap.split('\n').join(' ');
                        while (textToWrap.indexOf('  ') > -1) {
                            textToWrap = textToWrap.split('  ').join(' ');
                        }
                        textToWrap = textToWrap.trim();
                        wrappedEl.setAttribute('data-text-to-wrap', textToWrap);
                    }

                    var max_line_count = Number.parseInt(wrappedEl.getAttribute('data-max-line-count'));
                    var max_width = wrappedEl.clientWidth;
                    var styles = window.getComputedStyle(wrappedEl);

                    var args = [textToWrap, {
                        family: styles['font-family'],
                        style: styles['font-style'],
                        weight: styles['font-weight'],
                        size: styles['font-size']
                    }, max_width, '-', max_line_count, '...'];

                    var wrappedLines = wrapText.apply(undefined, args);

                    wrappedEl.innerHTML = wrappedLines.map(function (line) {
                        return line.content;
                    }).join('\n');
                });
            };

            updateText(wrappedEls);

            window.addEventListener('resize', function () {
                updateText(wrappedEls);
            });
        };

        window.TextWrapper = {
            wrapText: wrapText,
            elementTextLinesLimit: elementTextLinesLimit
        };
    }(window);
</script>
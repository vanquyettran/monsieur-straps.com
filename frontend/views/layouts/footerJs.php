<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/30/2018
 * Time: 2:05 PM
 */
use common\models\SiteParam;

?>
<script>
    // init focus handler
    initFocusable();
    window.TextWrapper.elementTextLinesLimit('[data-max-line-count]');
    window.addEventListener('resize', function () {
        window.TextWrapper.elementTextLinesLimit('[data-max-line-count]');
    });

    // Youtube video
    !function (paras) {
        [].forEach.call(paras, function (para) {
            var iFrames = para.querySelectorAll('iframe[src^="https://www.youtube.com/embed/"]');
            [].forEach.call(iFrames, function (iFrame) {
                if (!iFrame.getAttribute('width') && !iFrame.getAttribute('height')) {
                    var wrapperInner = elm('div', null);
                    var wrapper = elm('div', wrapperInner, {'class': 'video aspect-ratio __16x9'});
                    iFrame.parentNode.insertBefore(wrapper, iFrame);
                    wrapperInner.appendChild(iFrame);
                }
            });
        });
    }(document.querySelectorAll('.paragraph'));

    // Sticky
    function updateAllStickies() {
        var containers = document.querySelectorAll('[data-sticky-container]');

        [].forEach.call(containers, function (container) {
            var startY = container.getAttribute('data-sticky-start');
            var stickyId = container.getAttribute('data-sticky-container');
            var stickyElms = container.querySelectorAll('[data-sticky-in="' + stickyId + '"]:not([data-sticky-copy])');
            [].forEach.call(stickyElms, function (el) {
                if (el.getAttribute('data-sticky-responsive').split('|').indexOf(getResponsiveMode()) > -1) {
                    if (!el.hasAttribute('data-sticky')) {
                        initSticky(el, container, startY);
                    }
                } else if (el.hasAttribute('data-sticky')) {
                    destroySticky(el);
                }
            });
        });
    }

    updateAllStickies();
    window.addEventListener('resize', updateAllStickies);

    /**
     *
     * @param {HTMLElement} el
     * @param {HTMLElement} ctn
     * @param {string} startY
     */
    function initSticky(el, ctn, startY) {
        var copy = el.cloneNode(true);
        copy.style.visibility = 'hidden';
        copy.style.pointerEvents = 'none';
        copy.setAttribute('data-sticky-copy', '');
        el.stickyCopy = copy;

        el.setAttribute('data-sticky', '');
        el.setAttribute('data-style-position', el.style.position);
        el.setAttribute('data-style-top', el.style.top);
        el.setAttribute('data-style-width', el.style.width);

        el.onWindowScroll = function () {
            var elRect = el.getBoundingClientRect();
            var copyRect = copy.getBoundingClientRect();
            var ctnRect = ctn.getBoundingClientRect();
            var y0;
            var fix = function () {
                el.parentNode.insertBefore(copy, el);
                el.style.position = 'fixed';
                el.style.width = copy.offsetWidth + 'px';

                if (ctnRect.top + ctn.offsetHeight > el.offsetHeight + y0) {
                    el.style.top = y0 + 'px';
                } else {
                    el.style.top = (ctnRect.top + ctn.offsetHeight) - el.offsetHeight + 'px';
                }

                el.setAttribute('data-sticky', 'fixed');
            };
            var release = function () {
                el.style.top = el.getAttribute('data-style-top');
                el.style.position = el.getAttribute('data-style-position');
                el.style.width = el.getAttribute('data-style-width');
                if (copy.parentNode) {
                    copy.parentNode.removeChild(copy);
                }

                el.setAttribute('data-sticky', 'released');
            };

            if (elRect.height < ctnRect.height) {
                if (startY && isNaN(startY)) {
                    var topEl = document.querySelector(startY + ':not([data-sticky-copy])');
                    if (topEl) {
                        y0 = topEl.getBoundingClientRect().bottom;
                    } else {
                        y0 = 0;
                    }
                } else {
                    y0 = Number(startY) || 0;
                }

                if (elRect.top + copyRect.top < y0) {
                    fix();
                } else if (elRect.top + copyRect.top > y0 * 2) {
                    release();
                }
            } else {
                release();
            }
        };

        window.addEventListener('scroll', el.onWindowScroll);
    }

    /**
     *
     * @param {HTMLElement} el
     */
    function destroySticky(el) {
        // rollback style attributes
        el.style.top = el.getAttribute('data-style-top');
        el.style.position = el.getAttribute('data-style-position');
        el.style.width = el.getAttribute('data-style-width');

        // remove copy and event listener
        if (el.stickyCopy.parentNode) {
            el.stickyCopy.parentNode.removeChild(el.stickyCopy);
        }
        window.removeEventListener('scroll', el.onWindowScroll);

        // remove attributes
        el.removeAttribute('data-sticky');
        el.removeAttribute('data-style-top');
        el.removeAttribute('data-style-position');
        el.removeAttribute('data-style-width');
        el.stickyCopy = undefined;
        el.onWindowScroll = undefined;
        delete el.stickyCopy;
        delete el.onWindowScroll;
    }
</script>
<script src="<?= Yii::getAlias('@web/js/hammer_slider.min.js') ?>" type="text/javascript"></script>
<script>
    // Init sliders
    [].forEach.call(document.querySelectorAll(".slider"), initSlider);
</script>
<script>
    /**
     *
     * @param {string} query
     * @param {int} pageSize
     * @param {int} pageIndex 0-based
     * @return {string}
     */
    function getSearchUrl(query, pageSize, pageIndex) {
        var apiKey = '<?= ($apiKey = SiteParam::findOneByName(SiteParam::GOOGLE_CUSTOM_SEARCH_API_KEY)) ? $apiKey->value : '' ?>';
        var customId = '<?= ($cx = SiteParam::findOneByName(SiteParam::GOOGLE_CUSTOM_SEARCH_ID)) ? $cx->value : '' ?>';
        var searchUrlTemplate = 'https://www.googleapis.com/customsearch/v1?key={apiKey}&cx={customId}&q={query}&num={pageSize}&start={startIndex}';
        var startIndex = pageIndex * pageSize + 1; // startIndex is 1-based
        return searchUrlTemplate
            .replace('{apiKey}', apiKey)
            .replace('{customId}', customId)
            .replace('{query}', query)
            .replace('{pageSize}', pageSize)
            .replace('{startIndex}', startIndex);
    }

    function popupSearch() {
        var searchOverlay, searchInput, submitButton, resultContainer;

        var requestSearch = function (pageIndex) {
            var query = searchInput.value;
            if (!query) return;

            submitButton.disabled = true;
            searchOverlay.classList.add('loading-opacity');

            while (resultContainer.firstChild) {
                resultContainer.removeChild(resultContainer.firstChild);
            }

            var pageSize = 10; // pageSize is not allowed to greater than 10 by google

            var xhr = new XMLHttpRequest();

            xhr.onload = function () {
                submitButton.disabled = false;
                searchOverlay.classList.remove('loading-opacity');

                var res = JSON.parse(xhr.responseText);

                console.log(res);

                var info = res.searchInformation;
                var items = res.items;
                var queries = res.queries;

                if (!info && !items && !queries) {
                    resultContainer.appendChild(elm(
                        'div',
                        {html: 'Rất xin lỗi! Chúng tôi không thể tìm kiếm vào lúc này<br>Hãy thử tìm kiếm theo danh mục trên trang web'},
                        {
                            'class': 'alert-block'
                        }
                    ));
                    return;
                }

                if (!info || !items || !queries) {
                    resultContainer.appendChild(elm(
                        'div',
                        {html: 'Không có kết quả nào phù hợp với từ khóa <b>' + query + '</b>'},
                        {
                            'class': 'alert-block'
                        }
                    ));
                    return;
                }

                var itemsBlock = elm('ul', items.map(function (item) {
                    return elm(
                        'li',
                        elm(
                            'a',
                            [
                                elm(
                                    'div',
                                    elm(
                                        'span',
                                        item.pagemap
                                        && item.pagemap.cse_image
                                        && item.pagemap.cse_image[0]
                                        && elm(
                                            'img',
                                            null,
                                            {
                                                src: item.pagemap.cse_image[0].src
                                            }
                                        )
                                    ),
                                    {
                                        'class': 'image aspect-ratio __3x2'
                                    }
                                ),
                                elm(
                                    'div',
                                    item.title,
                                    {
                                        'class': 'name'
                                    }
                                ),
                                elm(
                                    'div',
                                    item.snippet,
                                    {
                                        'class': 'desc'
                                    }
                                )
                            ],
                            {
                                href: item.link,
                                title: item.title,
                                'class': 'clr'
                            }
                        )
                    );
                }), {
                    'class': 'items-block'
                });

                var paginationBlock = elm(
                    'div',
                    [
                        queries.previousPage && elm('a', 'Previous', {
                            'class': 'prev-button',
                            onclick: function () {
                                requestSearch(pageIndex - 1);
                            }
                        }),
                        queries.previousPage
                        && queries.nextPage
                        && elm('span', '|', {
                            'class': 'divider'
                        }),
                        queries.nextPage && elm('a', 'Next', {
                            'class': 'next-button',
                            onclick: function () {
                                requestSearch(pageIndex + 1);
                            }
                        })
                    ],
                    {
                        'class': 'nav-block clr'
                    }
                );

                resultContainer.appendChild(itemsBlock);
                resultContainer.appendChild(paginationBlock);
            };

            xhr.onerror = function () {
                submitButton.disabled = false;
                searchOverlay.classList.remove('loading-opacity');
                console.log(xhr.status, xhr.statusText);
            };

            xhr.open('GET', getSearchUrl(query, pageSize, pageIndex), true);
            xhr.setRequestHeader('Content-type', 'application/json; charset=UTF-8');
            xhr.send();
        };

        searchOverlay = elm(
            'div',
            [
                elm(
                    'form',
                    [
                        searchInput = elm('input', null, {
                            type: 'text',
                            placeholder: 'What are you looking for?',
                            'class': 'search-input'
                        }),
                        submitButton = elm('button', 'Search', {
                            type: 'submit',
                            'class': 'submit-button'
                        })
                    ],
                    {
                        'class': 'search-form clr',
                        onsubmit: function (event) {
                            event.preventDefault();
                            requestSearch(0);
                        }
                    }
                ),
                resultContainer = elm(
                    'div',
                    null,
                    {
                        'class': 'result-container'
                    }
                )
            ],
            {
                'class': 'search-wrapper'
            }
        );

        var searchPopup = popup(searchOverlay, {
            className: 'search-popup'
        });

        searchInput.focus();
    }
</script>

<script>
    function refreshCartCounter() {
        var cartCounter = document.querySelector('#shopping-cart-button .count');
        var cartItems = getCacheData('shoppingCartItems', []);

        cartCounter.innerHTML = cartItems.reduce(function (sum, item) {
            return sum + item.quantity;
        }, 0);
    }

    refreshCartCounter();

    function setCartButtonActivity(active) {
        var cartButton = document.querySelector('#shopping-cart-button');
        if (active) {
            cartButton.classList.add('active');
        } else {
            cartButton.classList.remove('active');
        }
    }


    
    function scrollToTop() {
        window.scrollTo(0, 0);
    }
</script>
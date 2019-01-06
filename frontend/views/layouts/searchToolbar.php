<div id="search-toolbar" class="search-toolbar">
    <div class="container">
        <gcse:search></gcse:search>
    </div>
</div>
<script>
    (function() {
        var cx = '018283733847891945836:ya8azoeuwbg';
        var gcse = document.createElement('script');
        gcse.type = 'text/javascript';
        gcse.async = true;
        gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(gcse, s);
    })();

    // Search Toolbar
    function toggleSearchToolbar() {
        document.getElementById('search-toolbar').classList.toggle('active');
        document.querySelector('.gsc-search-box-tools .gsc-search-box input.gsc-input').focus();
    }
</script>
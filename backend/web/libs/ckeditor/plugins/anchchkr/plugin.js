/**
 * Created by Quyet on 7/19/2017.
 */
CKEDITOR.plugins.add( 'anchchkr', {
    icons: 'anchchkr',
    init: function( editor ) {
        // editor.addCommand( 'anchchkr', {
        //     exec: function( editor ) {
        //         main();
        //     }
        // });
        editor.addCommand( 'anchchkr', new CKEDITOR.dialogCommand( 'anchchkrDialog' ) );
        CKEDITOR.dialog.add( 'anchchkrDialog', this.path + 'dialogs/anchchkr.js' );
        editor.ui.addButton( 'anchchkr', {
            label: 'Anchor Checker',
            command: 'anchchkr',
            toolbar: 'tools'
        });
        editor.on('change', function (event) {
            // change event in CKEditor 4.x
            main();
        });
        editor.on('instanceReady', function (event) {
            // change event in CKEditor 4.x
            main();
        });
        function main() {
            var dom = editor.window.$.document;
            var aTags = dom.querySelectorAll("a");
            // console.log(aTags);
            var errNum = 0;
            [].forEach.call(aTags, function (aTag) {
                var text = aTag.textContent || aTag.innerText || "";
                var img = aTag.querySelector("img");
                if (!text.trim() && !img) {
                    aTag.classList.add("empty-text");
                    errNum++;
                } else {
                    aTag.classList.remove("empty-text");
                }
                var title = aTag.getAttribute("title");
                if (!title || !title.trim()) {
                    aTag.classList.add("empty-title");
                    errNum++;
                } else {
                    aTag.classList.remove("empty-title");
                }
                var href = aTag.getAttribute("href");
                if (!href || !href.trim()) {
                    aTag.classList.add("empty-href");
                    errNum++;
                } else {
                    aTag.classList.remove("empty-href");
                    if (
                        href.indexOf("http://") !== 0
                        && href.indexOf("https://") !== 0
                        && href.indexOf("sms:") !== 0
                    ) {
                        aTag.classList.add("invalid-href");
                        errNum++;
                    } else {
                        aTag.classList.remove("invalid-href");
                    }
                }
                // console.log(title, href);
            });
            // console.log(errNum);
            // var errDiv = document.createElement("div");
            // errDiv.innerHTML = errNum;
            // errDiv.className = "anchor-checker-err";
        }
    }
});

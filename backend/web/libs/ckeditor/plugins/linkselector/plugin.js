CKEDITOR.plugins.add( 'linkselector', {
    icons: 'linkselector',
    init: function( editor ) {
        // Plugin logic goes here...
        editor.addCommand( 'linkselector', new CKEDITOR.dialogCommand( 'linkselectorDialog' ) );

        editor.ui.addButton( 'LinkSelector', {
            label: 'Insert Link',
            command: 'linkselector',
            toolbar: 'insert'
        });

        CKEDITOR.dialog.add( 'linkselectorDialog', this.path + 'dialogs/linkselector.js' );

        /**
         * Enable dialog in context menu
         */
        if ( editor.contextMenu ) {
            editor.addMenuGroup( 'linkselectorGroup' );
            editor.addMenuItem( 'linkselectorItem', {
                label: 'Edit Link',
                icon: this.path + 'icons/linkselector.png',
                command: 'linkselector',
                group: 'linkselectorGroup'
            });
            editor.contextMenu.addListener( function( element ) {
                var ascendant = element.getAscendant( 'a', true );
                if ( ascendant && isSelectable(ascendant) && !ascendant.isReadOnly() ) {
                    return { linkselectorItem: CKEDITOR.TRISTATE_OFF };
                }
            });
        }

        /**
         * Open dialog on double click
         */
        editor.on( 'doubleclick', function( event ) {
            var ascendant = event.data.element.getAscendant( 'a', true );
            if ( ascendant && isSelectable(ascendant) && !ascendant.isReadOnly() ) {
                event.data.dialog = 'linkselectorDialog';
            }
        }, null, null, 100 );

        function isSelectable(element) {
            return (
                element.getAttribute("data-model-type") && element.getAttribute("data-model-id")
            );
        }
    }

});

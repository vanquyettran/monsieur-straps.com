CKEDITOR.plugins.add( 'internallink', {
    icons: 'internallink',
    init: function( editor ) {
        // Plugin logic goes here...
        editor.addCommand( 'internallink', new CKEDITOR.dialogCommand( 'internallinkDialog' ) );

        editor.ui.addButton( 'Internallink', {
            label: 'Insert Internallink',
            command: 'internallink',
            toolbar: 'insert'
        });

        CKEDITOR.dialog.add( 'internallinkDialog', this.path + 'dialogs/internallink.js' );

        /**
         * Enable dialog in context menu
         */
        if ( editor.contextMenu ) {
            editor.addMenuGroup( 'internallinkGroup' );
            editor.addMenuItem( 'internallinkItem', {
                label: 'Edit Internallink',
                icon: this.path + 'icons/internallink.png',
                command: 'internallink',
                group: 'internallinkGroup'
            });
            editor.contextMenu.addListener( function( element ) {
                var ascendant = element.getAscendant( 'a', true );
                if ( checkIsInternallink(ascendant) && !ascendant.isReadOnly() ) {
                    return { internallinkItem: CKEDITOR.TRISTATE_OFF };
                }
            });
        }

        /**
         * Open dialog on double click
         */
        editor.on( 'doubleclick', function( event ) {
            var ascendant = event.data.element.getAscendant( 'a', true );
            if ( checkIsInternallink(ascendant) && !ascendant.isReadOnly() ) {
                event.data.dialog = 'internallinkDialog';
            }
        }, null, null, 100 );
    }
});

function checkIsInternallink(a) {
    return (
        a
        && a.is('a')
        && a.getAttribute( 'data-model-id' )
        && a.getAttribute('data-model-type')
        && !a.getAttribute( 'href' )
    );
}
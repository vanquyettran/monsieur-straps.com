/**
 * Created by Quyet on 7/20/2017.
 */
CKEDITOR.dialog.add( 'anchchkrDialog', function( editor ) {
    return {
        title:          'Test Dialog',
        resizable:      CKEDITOR.DIALOG_RESIZE_BOTH,
        minWidth:       500,
        minHeight:      400,
        contents: [
            {
                id:         'tab1',
                label:      'First Tab',
                title:      'First Tab Title',
                accessKey:  'Q',
                elements: [
                    {
                        type:           'text',
                        label:          'Test Text 1',
                        id:             'testText1',
                        'default':      'hello world!'
                    }
                ]
            }
        ]
    };
});

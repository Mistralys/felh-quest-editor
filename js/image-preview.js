"use strict";

class ImagePreview
{
    /**
     * @param {String} elementID
     */
    Refresh(elementID)
    {
        var selectEl = $('#'+elementID);
        var value = selectEl.val();
        var container = $('#'+elementID+'-container');

        if(value === '') {
            container.hide();
            return;
        }

        var file = selectEl.attr('data-folder') + '/' + value;

        container.show();
        $('#'+elementID+'-image').attr('src', APP_URL+'/?page=view-graphic-file&target='+file);
    }
}

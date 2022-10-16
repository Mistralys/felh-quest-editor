"use strict";

/**
 * Handles previewing image files in graphics attributes,
 * by loading the image using the `ViewGraphicFile` page.
 */
class ImagePreview
{
    constructor()
    {
        /**
         * The maximum width to use for the preview image.
         * If the source image is smaller, it will be shown
         * in its native size.
         *
         * @type {number}
         */
        this.maxWidth = 128;
    }

    /**
     * Refreshes the image preview when a value has changed
     * in a graphics attribute list.
     *
     * @param {String} elementID
     */
    Refresh(elementID)
    {
        var selectEl = $('#'+elementID);
        var selectedOption = selectEl.find(":selected");
        var value = selectedOption.val();
        var container = $('#'+elementID+'-container');
        var img = $('#'+elementID+'-image');
        var sourceWidth = parseInt(selectedOption.attr('data-source-width'));
        var relativePath = selectEl.attr('data-folder') + '/' + value;

        var width = this.maxWidth;
        if(width > sourceWidth) {
            width = sourceWidth;
        }

        if(value === '') {
            container.hide();
            return;
        }

        container.show();

        img
            .attr('src', APP_URL+'/?page=view-graphic-file&target='+relativePath)
            .width(width);
    }
}

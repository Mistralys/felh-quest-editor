"use strict";

/**
 * Handles an enum attribute form element:
 * Finds all possible values, and switches the
 * value descriptions when the value is changed.
 *
 * @class
 * @access public
 */
class EnumAttribute
{
    /**
     * @param {String} elementID
     * @constructor
     */
    constructor(elementID)
    {
        this.elementID = elementID;
        this.ready = false;
    }

    /**
     *
     * @param {jQuery} optionElement
     * @constructor
     */
    RegisterItem(optionElement)
    {
        const item = new EnumItem(optionElement);

        if(item.GetID() !== '') {
            this.items.push(item);
        }
    }

    Init()
    {
        if(this.ready) {
            return;
        }

        this.ready = true;
        this.element = $('#'+this.elementID);
        this.items = [];

        const attribute = this;
        this.element.find('option').each(function () {
            attribute.RegisterItem($(this));
        });
    }

    Change()
    {
        this.Init();

        this.ResetDescriptions();

        const item = this.GetItemByValue(this.element.val());

        if(item !== null) {
            item.ShowDescription();
        }
    }

    ResetDescriptions()
    {
        this.Init();

        $.each(
            this.items,
            /**
             * @param {Number} idx
             * @param {EnumItem} item
             */
            function(idx, item)
            {
                item.HideDescription();
            }
        );
    }

    /**
     *
     * @param {String} value
     * @return {EnumItem|null}
     */
    GetItemByValue(value)
    {
        this.Init();

        let result = null;

        $.each(
            this.items,
            /**
             * @param {Number} idx
             * @param {EnumItem} item
             */
            function (idx, item)
            {
                if(item.GetValue() === value)
                {
                    result = item;
                    return false;
                }
            }
        );

        return result;
    }
}

class EnumItem
{
    /**
     * @param {jQuery} optionElement
     * @constructor
     */
    constructor(optionElement)
    {
        this.element = optionElement;
    }

    /**
     * @returns {String}
     */
    GetID()
    {
        return String(this.element.attr('data-item-id'));
    }

    GetValue()
    {
        return this.element.val();
    }

    ShowDescription()
    {
        $('#'+this.GetID()).show();
    }

    HideDescription()
    {
        $('#'+this.GetID()).hide();
    }
}

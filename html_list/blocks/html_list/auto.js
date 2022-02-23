// noinspection JSUnusedGlobalSymbols,JSUnresolvedFunction,GrazieInspection

const list_block_delimiter = "\n";

function list_block_init() {

    // Override on "Enter" keypress while adding a new item.
    // Stops block from being saved. Instead adds new item to the list.
    $("input#add").bind("keyup", function (e) {
        const form = $("#ccm-block-form");
        const keyCode = e.which;
        if (keyCode === 13) {
            form.submit(function () {
                return false;
            });
            list_block_add();
        }
    });

}

function list_block_add() {

    // get value to add
    const value = $('input[name="add"]').val();

    // if the value is not an empty string, add it to the list
    let $example;
    if (0 < value.length) {

        // clear "add" text field
        $('input#add').val("");

        // clone the example item div
        $example = $('#item-example').clone();
        $example.removeAttr("id");

        // update example div with "add" content
        $('input#item', $example).val(value);
        $('input#text', $example).val(value);

        // append the new item
        $('#items').append($example);

        // update the list input value
        list_block_update();

        // ensure all item(s) in the items div are visible
        $('#items .item').each(function () {
            $(this).fadeIn();
        });

        // return focus to "add" text field
        $('#add').focus();

    } else {
        // if the value is an empty string, tell the user that's not allowed
        return confirm("Cannot add an empty value.");
    }

}

function list_block_update() {

    // initialize the list array
    const list = [];

    // add each item value (if set)
    $('input#item').each(function () {
        // get the item value
        let val = $(this).val();
        // strip any instances of the delimiter from the item value
        val = val.replace(list_block_delimiter, "");
        // if the value is not an empty string, push it to the list array
        if (0 < val.length) list.push(val);
    });

    // set the list value, delimited by new line
    let value = list.join(list_block_delimiter);

    // store value in hidden #list input field
    $('input#list').val(value);

}

function list_block_edit(obj) {

    // show and hide appropriate buttons for action
    $(obj).hide();
    $(obj).siblings('input.remove').hide();
    $(obj).siblings('input.save').fadeIn();
    $(obj).siblings('input.cancel').fadeIn();

    // get text input field to edit
    let $item = $(obj).siblings('input#text');
    // enable the text field for editing
    $item.removeAttr("disabled");

    // Override on "Enter" keypress while editing an existing item.
    // Stops block from being saved. Instead saves item currently being edited.
    $item.bind("keyup", function (e) {
        const form = $("#ccm-block-form");
        const keyCode = e.which;
        let $save;
        if (keyCode === 13) {
            // block form submission.
            form.submit(function () {
                return false;
            });
            // grab appropriate save button to imitate save button click.
            $save = $(this).siblings('input.save');
            // "click" save button
            $save.click();
        }
    });


}

function list_block_remove(obj) {

    // confirm removal of list item
    const message = "Are you sure?";
    if (!confirm(message)) return;

    // clear hidden #item input associated with item
    const $item = $(obj).siblings('input[name="item"]');
    $item.val("");

    // update the value of the hidden #list form element
    list_block_update();

    // get the parent container of the item marked for removal and remove it.
    const $element = $(obj).parent();
    $element.fadeOut(200, function () {
        $(this).remove();
    });

}

function list_block_save(obj) {

    // grab the input text field of the item being saved
    let $item = $(obj).siblings('input#text');
    // get the updated value
    let value = $item.val();

    // if the value is not an empty string save it.
    if (0 < value.length) {

        // show and hide appropriate buttons for action
        $(obj).hide();
        $(obj).siblings('input.cancel').hide();
        $(obj).siblings('input.edit').fadeIn();
        $(obj).siblings('input.remove').fadeIn();

        // remove bindings of the text field to avoid duplicate event handlers
        $item.unbind("keyup");
        // disable the text field again
        $item.attr('disabled', 'disabled');
        // update the hidden #item input that stores the actual value of the field
        $(obj).siblings('input#item').val(value);

        // update the value of the hidden #list form element
        list_block_update();

    } else {
        // if the item is empty, prompt the user and tell them they can't save an empty value.
        return confirm("Cannot save an empty value.");
    }

}

function list_block_cancel(obj) {

    // get the text field being edited
    let $item = $(obj).siblings('input#text');
    // remove keyup binding to avoid duplicate event handlers
    $item.unbind("keyup");
    // reset the text field to the original value
    $item.val($item.siblings('input#item').val());
    // disable the text field from editing
    $item.attr('disabled', 'disabled');

    // show and hide appropriate buttons for action
    $(obj).hide();
    $(obj).siblings('input.save').hide();
    $(obj).siblings('input.edit').fadeIn();
    $(obj).siblings('input.remove').fadeIn();

}

$(document).ready(function () {
    // initialize the list block form
    list_block_init();
});
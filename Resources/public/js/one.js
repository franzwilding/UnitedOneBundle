/**
 * A small wrapper object for custom functions.
 */
var UnitedOne = {

    /**
     * Holds all modules
     */
    modules: {},

    /**
     * Call init function on all modules.
     */
    init: function () {
        for (var module in this.modules) {
            if (this.modules[module].hasOwnProperty('init')) {
                this.modules[module].init();
            }
        }
    },

    /**
     * Call ready functions on all modules.
     */
    ready: function (document) {
        for (var module in this.modules) {
            if (this.modules[module].hasOwnProperty('ready')) {
                this.modules[module].ready(document);
            }
        }
    }
};

// Call init function
UnitedOne.init();

// Call ready function
$(document).ready(function () {
    UnitedOne.ready(document);
});


/**
 * Toggle sidebar navigation.
 */
UnitedOne.modules.toggleSidebar = {

    sidebar: $('.sidebar'),
    toggler: $('.toggle-sidebar'),
    header: $('.united-one-header'),

    /**
     * Returns semantic-ui sidebar settings
     */
    settings: function () {
        var t = this;
        return {
            transition: 'push',
            dimPage: false,
            pushable: true,
            onChange: function () {
                t.header.toggleClass('open');
            }
        }
    },

    ready: function () {
        var t = this;

        t.toggler.click(function () {
            t.sidebar.sidebar('setting', t.settings()).sidebar('toggle');
        });
    }
};

/**
 * Transforms all .ui.dropdown elements.
 */
UnitedOne.modules.select = {
    ready: function (context) {
        $('.ui.dropdown', context)
            .dropdown()
        ;
    }
};

/**
 * Transforms all checkbox and radio elements.
 */
UnitedOne.modules.checkbox = {
    ready: function (context) {
        $('.ui.checkbox', context).
            checkbox()
        ;
    }
};

/**
 * Handles modals.
 */
UnitedOne.modules.modal = {
    open: function(header, content, ok_cb, cancel_cb) {

        // add modal dom element
        var modal = $('<div />', {class: 'ui fullscreen modal'});
        modal.append($('<i />', {class: 'close icon'}));
        modal.append($('<div />', {class: 'header', text: header}));

        var m_content = $('<div />', {class: 'content'});
        modal.append(m_content);

        var m_actions = $('<div />', {class: 'actions'});

        if (cancel_cb != undefined) {
            m_actions.append($('<button />', {class: 'ui cancel button', text: 'Cancel'}));
        }

        if (ok_cb != undefined) {
            m_actions.append($('<button />', {class: 'ui positive button', text: 'Ok'}));
        }

        modal.append(m_actions);

        // add content
        if(typeof(content) == "function") {

            var dimmer = $('<div />', {class: 'ui active inverted dimmer'});
            dimmer.append($('<div />', {class: 'ui loader'}));
            m_content.append(dimmer);

            content(function(data){
                m_content.html(data);
            });
        } else {
            m_content.html(content);
        }

        modal.modal({
            onDeny    : cancel_cb,
            onApprove : ok_cb
        }).modal('show');
    }
};

/**
 * Connect form preview buttons.
 */
UnitedOne.modules.preview = {
    ready: function(context) {
        $('form button.preview.button', context).click(function(){

            var embed = $(this).data('preview-embed');
            var url = $(this).data('preview-url');
            var data = $(this).closest('form').serialize();

            // Show the preview as embedded in a modal.
            if(embed) {
                UnitedOne.modules.modal.open('Preview', function (success) {
                    $.post(url + '?embed=true', data, success);

                }, function (modal) {
                });

            // Show the preview as full html in a new window.
            } else {
                $.post(url, data, function(result){
                    var preview_window = window.open("", "Preview","menubar=1,resizable=1,width=1200,height=800");
                    preview_window.document.write(result);
                });
            }

            return false;
        });
    }
};

/**
 * Create dimmer functionality for image cards.
 */
UnitedOne.modules.cards = {
    ready: function (context) {
        $('.card .dimmable.image', context).dimmer({
            on: 'hover'
        });
    }
};

/**
 * Make collection container sticky.
 */
UnitedOne.modules.stickyCollections = {
    ready: function (context) {
        $('.united-one-collection-container', context).each(function () {
            $('.ui.sticky', $(this)).sticky({context: $(this)});
        });
    }
};

/**
 * Transforms an textarea to an wysiwyg editor.
 */
UnitedOne.modules.editor = {

    /**
     * @returns {{}} - editor options.
     */
    editorOptions: function () {
        return {
            imageDragging: false
        }
    },

    /**
     *
     * @param textarea - jQuery DOM Element
     * @param container - jQuery DOM Element
     */
    onInput: function (textarea, container) {
        textarea.val(container.html());
    },

    ready: function (context) {

        var t = this;

        $('.united-editor', context).each(function () {
            var textarea = $(this);
            var container = $('<div />', {class: 'united-editor-container ui textarea'});
            container.html(textarea.val());
            container.insertAfter(textarea);
            textarea.hide();
            var editor = new MediumEditor(container, t.editorOptions());

            container.on('input', function () {
                t.onInput(textarea, container);
            });
        });
    }
};

/**
 * Functionality for tagging form type.
 */
UnitedOne.modules.tags = {

    lastItem: null,
    startIndex: 0,
    currentItemIndex: 0,

    /**
     * Callback, when an user selects a tag. Creates a tag element with hidden input for id and name.
     *
     * @param value
     * @param text
     * @param $choice
     * @param select
     * @param full_name
     * @param labels
     */
    onChange: function (value, text, $choice, select, full_name, labels) {

        var t = this;

        this.lastItem = {
            value: value,
            text: text,
            $choice: $choice
        };

        var $label = $('<div />', {class: 'ui label', text: this.lastItem.text});
        $label.append($('<input />', {
            type: 'hidden',
            name: full_name + '[' + ( this.startIndex + this.currentItemIndex ) + '][id]',
            value: this.lastItem.value
        }));
        $label.append($('<input />', {
            type: 'hidden',
            name: full_name + '[' + ( this.startIndex + this.currentItemIndex ) + '][name]',
            value: this.lastItem.text
        }));
        var $del = $('<i />', {class: 'delete icon'});
        $label.append($del);
        labels.append($label);

        // add label delete event
        $del.on('click', function () {
            $label.remove();
            t.currentItemIndex = t.currentItemIndex - 1;
        });

        this.currentItemIndex = this.currentItemIndex + 1;

        // clear
        this.lastItem = null;
        select.siblings('input.search').val('');
        select.parent().dropdown('clear');
    },

    /**
     * Callback, when an user creates a new tag. Callback, when an user selects a tag. Creates a tag element with hidden input name.
     * @param select
     * @param labels
     * @param full_name
     * @param value
     */
    createElement: function (select, labels, full_name, value) {

        var t = this;

        // if we create an new element
        if (this.lastItem == null) {

            // if prototype is defined for this tags field
            if (full_name && value.length > 0) {

                var $label = $('<div />', {class: 'ui label', text: value});
                $label.append($('<input />', {
                    type: 'hidden',
                    name: full_name + '[' + ( this.startIndex + this.currentItemIndex ) + '][name]',
                    value: value
                }));
                var $del = $('<i />', {class: 'delete icon'});
                $label.append($del);
                labels.append($label);

                // add label delete event
                $del.on('click', function () {
                    $label.remove();
                    t.currentItemIndex = t.currentItemIndex - 1;
                });

                this.currentItemIndex = this.currentItemIndex + 1;
            }
        }

        // clear
        this.lastItem = null;
        select.siblings('input.search').val('');
        select.parent().dropdown('clear');

    },

    ready: function (context) {

        var t = this;

        $('.united-tags', context).each(function () {

            var full_name = $(this).data('full-name');
            var select = $('> select', $(this));
            var labels = $('> .labels', $(this));
            t.startIndex = labels.children().length;
            t.currentItemIndex = 1;

            // init dropdown
            select.dropdown({
                forceSelection: false,
                onChange: function (value, text, $choice) {
                    t.onChange(value, text, $choice, select, full_name, labels);
                }
            });

            // init labels delete
            labels.find('i.delete.icon').on('click', function () {
                $(this).parent().remove();
            });

            $('input.search', select.parent()).keypress(function (e) {
                if (e.which == 13) {
                    t.createElement(select, labels, full_name, $(this).val());
                    return false;
                }
            });
        });
    }
};

/**
 * Renders button to add and remove prototype rows
 */
UnitedOne.modules.collectionPrototype = {

    onAdd: function ($container, prototype) {
        var item = $(prototype.replace(/__name__/g, $container.data('child-count')));
        $container.data('child-count', $container.data('child-count') + 1);
        $container.append(item);

        // add delete button
        this.addDeleteButtonToField(item);

        // if there is a united sort field, we need to register the field
        if(item.find('.united-sort').length > 0) {

            if ($container.data('sortable-initialized')) {
                var items = $container.data('sort-items');
                items.push(item.find('.united-sort'));
                $container.data('sort-items', items);
                UnitedOne.modules.sort.updateAllForList($container);
            } else {
                UnitedOne.modules.sort.initializeList($container);
                UnitedOne.modules.sort.updateIndex(item.find('.united-sort'));
            }
        }

        // transform checkbox & radio buttons
        UnitedOne.ready(item);
    },

    addDeleteButtonToField: function(field, $container) {
        var $del_button = $('<button />', {class: 'united-collection-delete-button circular ui red basic icon button'});
        var $del_icon = $('<i />', {class: 'delete icon'});
        $del_button.append($del_icon);
        $del_button.click(function(){
            //$container.data('child-count', $container.data('child-count') - 1);
            field.remove();
        });
        field.append($del_button);
    },

    ready: function (context) {

        var t = this;

        $('.united-prototype-widget', context).each(function () {
            var $button = $('<button />', {class: 'ui positive button', text: 'Add'});
            var $container = $(this);
            var prototype = $(this).data('prototype');
            $container.data('child-count', $(this).children('.field').length);

            // add delete buttons
            $(this).children('.field').each(function(){
                t.addDeleteButtonToField($(this), $container);
            });

            $(this).prepend($button);
            $(this).append($container);


            // on add
            $button.click(function () {
                t.onAdd($container, prototype);
                return false;
            });
        });
    }
};

/**
 * Transforms united-sort fields into drag&drop sort components.
 */
UnitedOne.modules.sort = {

    updateAllForList: function(list) {
        var t = this;
        $.each(list.data('sort-items'), function(id, item){
            t.updateIndex(item);
        });
    },

    updateIndex: function(field){
        var row = field.parent().closest('.field');
        field.find('input').val(row.index());
    },

    onChange: function(e, ui, list) {
        var t = this;
        setTimeout(function(){
            t.updateAllForList(list);
        }, 10);
    },

    initializeList: function(list) {

        var t = this;

        // enable sortable
        if(!list.data('sortable-initialized')) {

            list.sortable({
                items: '> .field',
                handle: '.united-sort-handler',
                placeholder: 'field united-sort-placeholder',
                forcePlaceholderSize: true
            }).on('sortable:update', function(e, ui){
                t.onChange(e, ui, list);
            });

            list.data('sort-items', []);
            list.data('sortable-initialized', true);
            list.addClass('with-sort-fields');
        }
    },

    ready: function(context){

        var t = this;

        $('.united-sort', context).each(function(){

            var list = $(this).parent().parent().parent();

            // init list
            t.initializeList(list);

            // update index
            var items = list.data('sort-items');
            items.push($(this));
            list.data('sort-items', items);
            t.updateIndex($(this));
        });
    }
};

/*UnitedOne.modules.entityBrowser = {
 ready: function() {
 $('.united-entity-browser').each(function(){

 var container = $(this);

 // add modal dom element
 var modal       = $('<div />', {class: 'ui fullscreen modal'});
 modal.append($('<i />', {class: 'close icon'}));
 modal.append($('<div />', {class: 'header', text: 'Select Entity'}));

 var m_content   = $('<div />', {class: 'content'});
 modal.append(m_content);

 var m_actions   = $('<div />', {class: 'actions'});
 m_actions.append($('<button />', {class: 'ui cancel button', text: 'Cancel'}));
 m_actions.append($('<button />', {class: 'ui positive button', text: 'Ok'}));
 modal.append(m_actions);

 // remove item action
 $('.ui.label .delete', container).click(function(){
 $(this).parent().remove();
 });

 // open select browser action
 $('.select-entity', container).click(function(){
 container.addClass('loading');

 // TODO: get browser via ajax request
 $.get('http://food.local/app_dev.php/admin/categories/?select=true', function(data){
 container.removeClass('loading');
 m_content.html(data);

 modal.modal({
 onDeny    : function(){

 // do nothing, since we don't want to save the changes.

 },
 onApprove : function() {

 // TODO: render the selected entities as hidden input fields
 }
 }).modal('show');
 });

 return false;
 });
 });
 }
 };*/
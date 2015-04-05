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
    ready: function () {
        for (var module in this.modules) {
            if (this.modules[module].hasOwnProperty('ready')) {
                this.modules[module].ready();
            }
        }
    }
};

// Call init function
UnitedOne.init();

// Call ready function
$(document).ready(function () {
    UnitedOne.ready();
});


/**
 * Toggle sidebar navigation.
 */
UnitedOne.modules.toggleSidebar = {

    sidebar: $('.sidebar'),
    toggler: $('.toggle-sidebar'),
    header: $('.united-one-header'),

    ready: function () {
        var t = this;

        t.toggler.click(function () {
            t.sidebar.sidebar('setting', {
                transition: 'overlay',
                dimPage: false,
                pushable: false,
                onChange: function () {
                    t.header.toggleClass('open');
                }

            }).sidebar('toggle');
        });
    }
};

UnitedOne.modules.select = {
    ready: function () {
        $('.ui.dropdown')
            .dropdown()
        ;
    }
};

UnitedOne.modules.cards = {
    ready: function () {
        $('.card .dimmable.image').dimmer({
            on: 'hover'
        });
    }
};

UnitedOne.modules.stickyCollections = {
    ready: function () {
        $('.united-one-collection-container').each(function () {
            $('.ui.sticky', $(this)).sticky({context: $(this)});
        });
    }
};

UnitedOne.modules.editor = {
    ready: function() {

        $('.united-editor').each(function(){
            var textarea = $(this);
            var container = $('<div />', {class: 'united-editor-container ui textarea'});
            container.html(textarea.val());
            container.insertAfter(textarea);
            textarea.hide();
            var editor = new MediumEditor(container);

            container.on('input', function() {
                textarea.val(container.html());
            });
        });
    }
};

UnitedOne.modules.entityBrowser = {
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
                setTimeout(function(){
                    container.removeClass('loading');
                    modal.modal({
                        onDeny    : function(){

                            // do nothing, since we don't want to save the changes.

                        },
                        onApprove : function() {

                            // TODO: render the selected entities as hidden input fields
                        }
                    }).modal('show');
                }, 300);

                return false;
            });
        });
    }
};
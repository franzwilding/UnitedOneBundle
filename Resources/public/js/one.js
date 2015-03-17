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
  init: function(){
    for(var module in this.modules) {
      if(this.modules[module].hasOwnProperty('init')) {
        this.modules[module].init();
      }
    }
  },

  /**
   * Call ready functions on all modules.
   */
  ready: function(){
    for(var module in this.modules) {
      if(this.modules[module].hasOwnProperty('ready')) {
        this.modules[module].ready();
      }
    }
  }
};

// Call init function
UnitedOne.init();

// Call ready function
$(document).ready(function(){
  UnitedOne.ready();
});


/**
 * Toggle sidebar navigation.
 */
UnitedOne.modules.toggleSidebar = {

  sidebar: $('.sidebar'),
  toggler: $('.toggle-sidebar'),
  header:  $('.united-one-header'),

  ready: function(){
    var t = this;

    t.toggler.click(function(){
      t.sidebar.sidebar('setting', {
        transition:     'overlay',
        dimPage:        false,
        pushable:       false,
        onChange:       function(){
          t.header.toggleClass('open');
        }

      }).sidebar('toggle');
    });
  }
};

UnitedOne.modules.select = {
  ready: function(){
    $('.ui.dropdown')
      .dropdown()
    ;
  }
};

UnitedOne.modules.cards = {
  ready: function(){
    $('.card .dimmable.image').dimmer({
      on: 'hover'
    });
  }
};

UnitedOne.modules.stickyCollections = {
    ready: function(){
        $('.united-one-collection-container').each(function(){
            $('.ui.sticky', $(this)).sticky({ context: $(this)});
        });
    }
};
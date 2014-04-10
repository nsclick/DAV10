/*
 * jQuery treeTable Plugin 2.3.0
 * http://ludo.cubicphuse.nl/jQuery-plugins/treeTable/
 *
 * Copyright 2010, Ludo van den Boom
 * Dual licensed under the MIT or GPL Version 2 licenses.
 */
(function(jQuery) {
  // Helps to make options available to all functions
  // TODO: This gives problems when there are both expandable and non-expandable
  // trees on a page. The options shouldn't be global to all these instances!
  var options;
  var defaultPaddingLeft;
  
  jQuery.fn.treeTable = function(opts) {
    options = jQuery.extend({}, jQuery.fn.treeTable.defaults, opts);
    
    return this.each(function() {
							  							  
      jQuery(this).addClass("treeTable").find("tbody tr").each(function() {
        // Initialize root nodes only if possible
	  
        if(!options.expandable || jQuery(this)[0].className.search(options.childPrefix) == -1) {
          // To optimize performance of indentation, I retrieve the padding-left
          // value of the first root node. This way I only have to call +css+ 
          // once.
          if (isNaN(defaultPaddingLeft)) {
            defaultPaddingLeft = parseInt(jQuery(jQuery(this).children("td")[options.treeColumn]).css('padding-left'), 10);
          }
		  
          initialize(jQuery(this));
			 
	 
        } else if(options.initialState == "collapsed") {
          this.style.display = "none"; // Performance! jQuery(this).hide() is slow...
        }
      });
    });
  };
  
  jQuery.fn.treeTable.defaults = {
    childPrefix: "child-of-",
    clickableNodeNames: false,
    expandable: true,
    indent: 19,
    initialState: "collapsed",
    treeColumn: 0,
    idTabla: "",
    idTablaD: ""
  };
  
  // Recursively hide all node's children in a tree
  jQuery.fn.collapse = function() {
    jQuery(this).addClass("collapsed");
	//jQuery( options.idTabla+' #'+jQuery(this).attr('id') ).removeClass("expanded").addClass("collapsed");
    
    childrenOf(jQuery(this)).each(function() {
      if(!jQuery(this).hasClass("collapsed")) {
        	jQuery(this).collapse();
      }
      this.style.display = "none"; // Performance! jQuery(this).hide() //is slow...
	});
	/* */
	jQuery( options.idTabla+' #'+jQuery(this).attr('id') ).removeClass("expanded").addClass("collapsed");
	jQuery( options.idTablaD+' #'+jQuery(this).attr('id') ).removeClass("expanded").addClass("collapsed");
	jQuery( options.idTabla ).find( '#'+jQuery(this).attr('id') ).removeClass("expanded").addClass("collapsed");
	jQuery( options.idTablaD ).find( '#'+jQuery(this).attr('id') ).removeClass("expanded").addClass("collapsed");


    return this;
  };
  
  // Recursively show all node's children in a tree
  jQuery.fn.expand = function() {
	
    jQuery(this).removeClass("collapsed").addClass("expanded");

    childrenOf(jQuery(this)).each(function() {
      initialize(jQuery(this));
      
      if(jQuery(this).is(".expanded.parent")) {
		//jQuery( options.idTabla+' #'+jQuery(this).attr('id') ).expand();
		//jQuery( options.idTablaD+' #'+jQuery(this).attr('id') ).expand();
        jQuery(this).expand();
      }

		// this.style.display = "table-row"; // Unfortunately this is not possible with IE :-(
	 	// jQuery( options.idTablaD+' #'+jQuery(this).attr('id') ).show();
	  	// jQuery( options.idTabla+' #'+jQuery(this).attr('id') ).show();
      jQuery(this).show();
	  //this.style.display = "block";
    });
	;
	jQuery( options.idTablaD+' #'+jQuery(this).attr('id') ).removeClass("collapsed").addClass("expanded");
	jQuery( options.idTabla+' #'+jQuery(this).attr('id') ).removeClass("collapsed").addClass("expanded");
	
	jQuery( options.idTabla ).find( '#'+jQuery(this).attr('id') ).removeClass("collapsed").addClass("expanded");
	jQuery( options.idTablaD ).find( '#'+jQuery(this).attr('id') ).removeClass("collapsed").addClass("expanded");

	/*
	alert( jQuery(this).attr('id') );
	alert( jQuery( options.idTabla+' tbody' ).html() );
	alert( jQuery( '#'+jQuery(this).attr('id') ).attr('class') );
	alert( jQuery( options.idTablaD+' #'+jQuery(this).attr('id') ).attr('class') );
    */
	return this;
  };

  // Reveal a node by expanding all ancestors
  jQuery.fn.reveal = function() {
    jQuery(ancestorsOf(jQuery(this)).reverse()).each(function() {
      initialize(jQuery(this));
      jQuery(this).expand().show();
    });
    
    return this;
  };

  // Add an entire branch to +destination+
  jQuery.fn.appendBranchTo = function(destination) {
    var node = jQuery(this);
    var parent = parentOf(node);
    
    var ancestorNames = jQuery.map(ancestorsOf(jQuery(destination)), function(a) { return a.id; });
    
    // Conditions:
    // 1: +node+ should not be inserted in a location in a branch if this would
    //    result in +node+ being an ancestor of itself.
    // 2: +node+ should not have a parent OR the destination should not be the
    //    same as +node+'s current parent (this last condition prevents +node+
    //    from being moved to the same location where it already is).
    // 3: +node+ should not be inserted as a child of +node+ itself.
    if(jQuery.inArray(node[0].id, ancestorNames) == -1 && (!parent || (destination.id != parent[0].id)) && destination.id != node[0].id) {
      indent(node, ancestorsOf(node).length * options.indent * -1); // Remove indentation
      
      if(parent) { node.removeClass(options.childPrefix + parent[0].id); }
      
      node.addClass(options.childPrefix + destination.id);
      move(node, destination); // Recursively move nodes to new location
      indent(node, ancestorsOf(node).length * options.indent);
    }
    
    return this;
  };
  
  // Add reverse() function from JS Arrays
  jQuery.fn.reverse = function() {
    return this.pushStack(this.get().reverse(), arguments);
  };
  
  // Toggle an entire branch
  jQuery.fn.toggleBranch = function() {
	if(jQuery(this).hasClass("collapsed")) 
	{		//jQuery( options.idTabla+' #'+jQuery(this).attr('id') ).expand();
     	jQuery(this).expand();
    } else {
    	jQuery(this).removeClass("expanded").collapse();
    }

	return this;
  };
  
  // === Private functions
  
  function ancestorsOf(node) {
    var ancestors = [];
    while(node = parentOf(node)) {
      ancestors[ancestors.length] = node[0];
    }
    return ancestors;
  };
  
  function childrenOf(node) {
	return jQuery("table.treeTable tbody tr." + options.childPrefix + node[0].id);
  };
  
  function getPaddingLeft(node) {
    var paddingLeft = parseInt(node[0].style.paddingLeft, 10);
    return (isNaN(paddingLeft)) ? defaultPaddingLeft : paddingLeft;
  }
  
  function indent(node, value) {
    var cell = jQuery(node.children("td")[options.treeColumn]);
    cell[0].style.paddingLeft = getPaddingLeft(cell) + value + "px";
    
    childrenOf(node).each(function() {
		//gpti_reporte_fechas						   
      indent(jQuery(this), value);
	  
    });
  };
  
  function initialize(node) {

    if(!node.hasClass("initialized")) {
      node.addClass("initialized");
      
      var childNodes = childrenOf(node);
      
      if(!node.hasClass("parent") && childNodes.length > 0) {
        node.addClass("parent");
      }
      
      if(node.hasClass("parent")) {
        var cell = jQuery(node.children("td")[options.treeColumn]);
		
        var padding = getPaddingLeft(cell) + options.indent;
        
        childNodes.each(function() {
          jQuery(this).children("td")[options.treeColumn].style.paddingLeft = padding + "px";
        });
        
        if(options.expandable) {

          cell.prepend('<span style="margin-left: -' + options.indent + 'px; padding-left: ' + options.indent + 'px" class="expander"></span>');
          jQuery(cell[0].firstChild).click(function() { node.toggleBranch(); });
          
          if(options.clickableNodeNames) {
            cell[0].style.cursor = "pointer";
            jQuery(cell).click(function(e) {
              // Don't double-toggle if the click is on the existing expander icon
              if (e.target.className != 'expander') {
                node.toggleBranch();
              }
            });
          }
          
          // Check for a class set explicitly by the user, otherwise set the default class
          if(!(node.hasClass("expanded") || node.hasClass("collapsed"))) {
            node.addClass(options.initialState);
          }

          if(node.hasClass("expanded")) {
            node.expand();
          }
        }
      }
    }

			 jQuery('#gpti_reporte_fechas span.expander').remove();
			 jQuery('#gpti_reporte_fechas td.tdfecha').css('padding-left', 0);

	};
  
  function move(node, destination) {
    node.insertAfter(destination);
    childrenOf(node).reverse().each(function() { move(jQuery(this), node[0]); });
  };
  
  function parentOf(node) {
    var classNames = node[0].className.split(' ');
    
    for(key in classNames) {
      if(classNames[key].match(options.childPrefix)) {
        return jQuery("#" + classNames[key].substring(9));
      }
    }
  };
})(jQuery);

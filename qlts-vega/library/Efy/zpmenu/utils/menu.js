/**
 * The Zapatec DHTML Menu
 *
 * Copyright (c) 2004-2005 by Zapatec, Inc.
 * http://www.zapatec.com
 * 1700 MLK Way, Berkeley, California,
 * 94709, U.S.A.
 * All rights reserved.
 *
 * Menu Widget
 * $Id: menu.js 826 2005-09-21 18:31:40Z ken $
 * This file contains two classes. The menu and the tree that it uses.
 *
 */

/* =============================================================== */
/* ===================== Tree Class ============================== */
/* =============================================================== */

/**
 * The Zapatec DHTML Tree, as used by the menu
 *
 * The Zapatec.MenuTree object constructor.  Pass to it the ID of an UL element (or
 * a reference to the element should you have it already) and an optional
 * configuration object.  This function creates and initializes the tree widget
 * according to data existent in the nested list, and applies the configuration
 * specified.
 *
 * The configuration object may contain the following options (the following
 * shows default values):
 *
 * \code
 * {
 *    hiliteSelectedNode : true,     // boolean
 *    compact            : false,    // boolean
 *    dynamic            : false,    // boolean
 *    initLevel          : false,    // false or number
 *    defaultIcons       : null      // null or string
 * }
 * \endcode
 *
 * - hiliteSelectedNode -- if \b false is passed, the tree will not highlight
 *   the currently selected node.
 * - compact -- if \b true is passed the tree will work in a "compact" mode; in
 *   this mode it automatically closes sections not relevant to the current
 *   one.
 * - dynamic -- if \b true is passed the tree will use the "dynamic initialization"
 *   technique which greatly improves generation time.  Some functionality is
 *   not available in this mode until all the tree was generated.  In "dynamic"
 *   mode the tree is initially collapsed and levels are generated "on the fly"
 *   as the end user expands them.  You can't retrieve nodes by ID (which
 *   implies you can't synchronize to certain nodes) until they have been
 *   generated.
 * - initLevel -- when this is a numeric value, it specifies the maximum
 *   "expand level" that the tree will use initially.  Therefore, if for
 *   instance you specify 1 then the tree will be initially expanded one level.
 *   Pass here 0 to have the tree fully collapsed, or leave it \b false to have
 *   the tree fully expanded.
 * - defaultIcons -- you can pass here a string.  If so, all tree items will
 *   get an additional TD element containing that string in the \b class
 *   attribute.  This helps you to include custom default icons without
 *   specifying them as IMG tags in the tree.  See our examples.
 *
 * @param el [string or HTMLElement] -- the UL element
 * @param config [Object, optional] -- the configuration options
 * @param noInit [boolean, optional] -- if true, don't configure/init tree.
 *
 * @return
 */
Zapatec.MenuTree = function(el, config, noInit) {
	if (typeof config == "undefined")
		config = {};
	this._el = el;
	this._config = config;
	if (!noInit) this.initTree();
}
/**
 * \internal Function that initialises a tree based on informatino stored in
 * the constructor function above. Separated so that other scripts can inherit
 * from this script and run their own postponed setup routine.
 */
Zapatec.MenuTree.prototype.initTree = function() {
    var el = this._el;
    var config = this._config;
	// Now we have the stored parameters, run the rest of the init code.
	function param_default(name, value) {
    	if (typeof config[name] == "undefined") config[name] = value;
	};
	param_default('d_profile', false);
	param_default('hiliteSelectedNode', true);
	param_default('compact', false);
	param_default('dynamic', false);
	param_default('initLevel', false);
	if (config.dynamic)
		config.initLevel = 0;
	this.config = config;
	// <PROFILE>
	if (this.config.d_profile) {
		var T1 = new Date().getTime();
		profile = {
			items : 0,
			trees : 0,
			icons : 0
		};
	}
	// </PROFILE>
	if (typeof el == "string")
		el = document.getElementById(el);
	this.list = el;
	this.items = {};
	this.trees = {};
	this.selectedItem = null;

	// Check for valid ID, if none found then alert user
	this.id=null;
	if (el)
		this.id = el.id || Zapatec.Utils.generateID("tree");
	else
		alert("Can not find Menu id=\"" + this._el + "\"")
	var top = this.top_parent = Zapatec.Utils.createElement("div");
	top.__zp_menu = Zapatec.Utils.createElement("div", top);
	top.__zp_menu.className = 'zpMenu';
	top.className = "zpMenuContainer zpMenu-top";
	if (this.config.vertical)
		// vertical:true, set top div container class to
		// NOTE:zpMenu-vertical-mode and zpMenu-level-1 defines the top vertical menu
		Zapatec.Utils.addClass(top, "zpMenu-vertical-mode");
	else
		Zapatec.Utils.addClass(top, "zpMenu-horizontal-mode");
	this.createTree(el, top, 0);
	if (el) {
		el.parentNode.insertBefore(top, el);
		el.parentNode.removeChild(el);
	}
	Zapatec.MenuTree.all[this.id] = this;
	// check if we have an initially selected node and sync. the tree if so
	if (this.selectedItem)
		this.sync(this.selectedItem.__zp_item);
	// <PROFILE>
	if (this.config.d_profile) {
		alert("Generated in " + (new Date().getTime() - T1) + " milliseconds\n" +
		      profile.items + " total tree items\n" +
		      profile.trees + " total (sub)trees\n" +
		      profile.icons + " total icons");
	}
	// </PROFILE>
	if (window.opera && !top.__zp_menu.style.width) {
		// Opera bug workaround
		var menu = top.__zp_menu;
		var width = 0;
		if (menu.childNodes) {
			for (var i = 0; i < menu.childNodes.length; i++) {
				var child = menu.childNodes[i];
				if (this.config.vertical) {
					if (child.offsetWidth > width) {
						width = child.offsetWidth;
					}
				} else {
					width += child.offsetWidth;
				}
			}
		}
		if (width > 0) {
			menu.style.height = menu.childNodes[0].offsetHeight + 'px'
			width += menu.offsetWidth - menu.clientWidth;
			if (menu.offsetWidth > width) {
				menu.style.width = menu.offsetWidth + 'px';
			} else {
				menu.style.width = width + 'px';
			}
		}
	}
};

/**
 * This global variable keeps a "hash table" (that is, a plain JavaScript
 * object) mapping ID-s to references to Zapatec.MenuTree objects.  It's helpful if
 * you want to operate on a tree but you don't want to keep a reference to it.
 * Example:
 *
 * \code
 *   // the following makes a tree for the <ul id="tree-id"> element
 *   var tree = new Zapatec.MenuTree("tree-id");
 *   // ... later
 *   var existing_tree = Zapatec.MenuTree.all("tree-id");
 *   // and now we can use \b existing_tree the same as we can use \b tree
 *   // the following displays \b true
 *   alert(existing_tree == tree);
 * \endcode
 *
 * So in short, this variable remembers values returned by "new
 * Zapatec.MenuTree(...)" in case you didn't.
 */
Zapatec.MenuTree.all = {};

/**
 * \internal Function that creates a (sub)tree.  This function walks the UL
 * element, computes and assigns CSS class names and creates HTML elements for
 * a subtree.  Each time a LI element is encountered, createItem() is called
 * which effectively creates the item.  Beware that createItem() might call
 * back this function in order to create the item's subtree. (so createTree and
 * createItem form an indirect recursion).
 *
 * @param list [HTMLElement] -- reference to the UL element
 * @param parent [HTMLElement] -- reference to the parent element that should hold the (sub)tree
 * @param level [integer] -- the level of this (sub)tree in the main tree.
 *
 * @return id -- the (sub)tree ID; might be automatically generated.
 */
Zapatec.MenuTree.prototype.createTree = function(list, parent, level) {
	if (this.config.d_profile) // PROFILE
		++profile.trees; // PROFILE
	var id;
	var intItem=1, bFirst=true;

	if (list) id=list.id; // list can be null
	if (!id)  id=Zapatec.Utils.generateID("tree.sub");
	var
		self = this;
	function _makeIt() {
		self.creating_now = true;
		var
			last_li = null, //previous <li>
			next_li, //next <li>
			i = (list ? list.firstChild : null),
			items = parent.__zp_items = [];
		self.trees[id] = parent;
		parent.__zp_level = level;
		parent.__zp_treeid = id;
		while (i) {
			if (last_li)
				last_li.className += " zpMenu-lines-c";
			if (i.nodeType != 1)
				i = i.nextSibling;
			else {
				next_li = Zapatec.Utils.getNextSibling(i, 'li');
				if (i.tagName.toLowerCase() == 'li') {
					last_li = self.createItem(i, parent, next_li, level, intItem++);
					if (last_li) { //false when webmaster creates malformed tree
						if (bFirst)
						{
							// First li for this sub-menu
							bFirst=false;
							Zapatec.Utils.addClass(last_li, "zpMenu-item-first");
						}
						//adds it to the parent's array of items
						items[items.length] = last_li.__zp_item;
					}
				}
				i = next_li;
			}
		}

		// Last li for this sub-menu
		if (last_li) Zapatec.Utils.addClass(last_li, "zpMenu-item-last");

		i = parent.firstChild;
		if (i && !level) {
			i.className = i.className.replace(/ zpMenu-lines-./g, "");
			i.className += (i === last_li) ? " zpMenu-lines-s" : " zpMenu-lines-t";
		}
		if (last_li && (level || last_li !=  i)) {
			last_li.className = last_li.className.replace(/ zpMenu-lines-./g, "");
			last_li.className += " zpMenu-lines-b";
		}
		self.creating_now = false;
	};
	if (this.config.dynamic && level > 0)
		this.trees[id] = _makeIt;
	else
		_makeIt();
	return id;
};

/**
 * \internal This function walks through a LI element and creates the HTML
 * elements associated with that tree item.  When it encounters an UL element
 * it calls createTree() in order to create the item's subtree.  This function
 * may also call item_addIcon() in order to add the +/- buttons or icons
 * present in the item definition as IMG tags, or item_addDefaultIcon() if the
 * tree configuration specifies "defaultIcons" and no IMG tag was present.
 *
 * @param li [HTMLElement] -- reference to the LI element
 * @param parent [HTMLElement] -- reference to the parent element where the HTML elements should be created
 * @param next_li [HTMLLiElement] -- reference to the next LI element, if this is not the last one
 * @param level [integer] -- the level of this item in the main tree
 * @param intItem [integer] -- Nth item for this sub-tree
 *
 * @return [HTMLElement] -- a reference to a DIV element holding the HTML elements of the created item
 */
Zapatec.MenuTree.prototype.createItem = function(li, parent, next_li, level, intItem) {
	if (this.config.d_profile) // PROFILE
		++profile.items; // PROFILE
	if (!li.firstChild)
		return;
	var
		id = li.id || Zapatec.Utils.generateID("tree.item"),
		item = this.items[id] = Zapatec.Utils.createElement("div", parent.__zp_menu),
		t = Zapatec.Utils.createElement("table", item),
		tb = Zapatec.Utils.createElement("tbody", t),
		tr = Zapatec.Utils.createElement("tr", tb),
		td = Zapatec.Utils.createElement("td", tr),
		is_list,
		tmp,
		i = li.firstChild,
		has_icon = false;
	t.className = "zpMenu-table";
	t.cellSpacing = 0;
	t.cellPadding = 0;
	td.className = "zpMenu-label"

	//If there's a title attribute to the LI
	if (li.getAttribute('title')) {
		//apply it to the menu item
		td.setAttribute('title', li.getAttribute('title'));
	}
	// add the LI's classname to the
	item.className = "zpMenu-item" + (li.className ? ' ' + li.className : '');
	Zapatec.Utils.addClass(item, "zpMenu-level-" + (level+1));	// Define the Nth level of a sub-menu, 1 based
	item.__zp_item = id;
	item.__zp_tree = this.id;
	item.__zp_parent = parent.__zp_treeid;
	item.onmouseover = Zapatec.Menu.onItemMouseOver;
	item.onmouseout = Zapatec.Menu.onItemMouseOut;
	item.onclick = Zapatec.Menu.onItemClick;
	Zapatec.Utils.addClass(item, "zpMenu-item-" + (intItem % 2==1 ? "odd" : "even"));
	while (i) {
		is_list = i.nodeType == 1 && /^[ou]l$/i.test(i.tagName);
		if (i.nodeType != 1 || !is_list) {
			if (i.nodeType == 3) {
				// remove whitespace, it seems to cause layout trouble
				tmp = i.data.replace(/^\s+/, '');
				tmp = tmp.replace(/\s+$/, '');
				li.removeChild(i);
				if (tmp) {
					i = Zapatec.Utils.createElement("span");
					i.innerHTML = tmp;
					td.appendChild(i);
				}
			} else if (i.tagName.toLowerCase() == 'img') {
				this.item_addIcon(item, i);
				has_icon = true;
			} else {
				if ((this._menuMode) && (i.tagName.toLowerCase() == 'hr')) {
					Zapatec.Utils.addClass(item, "zpMenu-item-hr");
				}
				td.appendChild(i);
			}
			i = li.firstChild;
			continue;
		}
		if (window.opera) {
			td.style.whiteSpace = 'nowrap'; // Opera bug workaround
		}
		if (is_list) {
			this.item_addIcon(item, null);
			var np = Zapatec.Utils.createElement("div", parent);
			np.__zp_item = id;
			np.__zp_menu = Zapatec.Utils.createElement("div", np);
			np.__zp_menu.className = 'zpMenu' + (i.className ? ' ' + i.className : '');
			np.className = 'zpMenuContainer';
			np.__zp_menu.onmouseover = Zapatec.Menu.onItemMouseOver;
			np.__zp_menu.onmouseout = Zapatec.Menu.onItemMouseOut;
			if (next_li)
				np.__zp_menu.className += " zpMenu-lined";
			item.__zp_subtree = this.createTree(i, np, level+1);
			if ((this.config.initLevel !=  false && this.config.initLevel <= level) ||
			    (this.config.compact && !/(^|\s)expanded(\s|$)/i.test(li.className))
			    || /(^|\s)collapsed(\s|$)/i.test(li.className)) {
				item.className += " zpMenu-item-collapsed";
				this.toggleItem(id);
			} else
				item.className += " zpMenu-item-expanded";
			if (/(^|\s)selected(\s|$)/i.test(li.className))
				this.selectedItem = item;
			break;
		}
	}

	if (!has_icon && !/zpMenu-item-hr/i.test(item.className))
		// No icons for this non-HR menu item
		if (this.config.defaultIcons)
			// Use user config setting defaultIcons className
			this.item_addDefaultIcon(item, this.config.defaultIcons);
		else
			// No icons default className
			this.item_addDefaultIcon(item, "zpMenu-noicon");
			
	return item;
};

/**
 * \internal This function adds a TD element having a certain class attribute
 * which helps having a tree containing icons without defining IMG tags for
 * each item.  The class name will be "tgb icon className" (where "className"
 * is the specified parameter).  Further, in order to customize the icons, one
 * should add some CSS lines like this:
 *
 * \code
 *  div.tree-item td.customIcon {
 *    background: url("themes/img/fs/document2.png") no-repeat 0 50%;
 *  }
 *  div.tree-item-expanded td.customIcon {
 *    background: url("themes/img/fs/folder-open.png") no-repeat 0 50%;
 *  }
 *  div.tree-item-collapsed td.customIcon {
 *    background: url("themes/img/fs/folder.png") no-repeat 0 50%;
 *  }
 * \endcode
 *
 * As you can see, it's very easy to customize the default icons for a normal
 * tree item (that has no subtrees) or for expanded or collapsed items.  For
 * the above example to work, one has to pass { defaultIcons: "customIcon" } in
 * the tree configuration object.
 *
 * This function does nothing if the \b className parameter has a false logical
 * value (i.e. is null).
 *
 * @param item [HTMLElement] -- reference to the DIV element holding the item
 * @param className -- a string containing the additional class name
 */
Zapatec.MenuTree.prototype.item_addDefaultIcon = function(item, className) {
	if (!className)
		return;
	var last_td = item.firstChild.firstChild.firstChild.lastChild, td;
	var td = Zapatec.Utils.createElement("td");
	td.className = "tgb icon " + className;

	last_td.parentNode.insertBefore(td, last_td);
};

/**
 * \internal This function does different things, depending on whether the \b
 * img parameter is passed or not.  If the \b img is passed, then this function
 * adds it as an icon for the given item.  If not passed, this function creates
 * a "+/-" button for the given item.
 *
 * @param item [HTMLElement] -- reference to the DIV holding the item elements
 * @param img [HTMLImgElement, optional] -- reference to an IMG element; normally one found in the <LI>
 */
Zapatec.MenuTree.prototype.item_addIcon = function(item, img) {
	if (this.config.d_profile) // PROFILE
		++profile.icons; // PROFILE
	var last_td = item.firstChild.firstChild.firstChild, td;
	last_td = img ? last_td.lastChild : last_td.firstChild;
	if (!img || !item.__zp_icon) {
		td = Zapatec.Utils.createElement("td");
		td.className = "tgb " + (img ? "icon" : "minus");
		last_td.parentNode.insertBefore(td, last_td);
	} else {
		td = item.__zp_icon;
		img.style.display = "none";
	}
	if (!img) {
		td.innerHTML = "&nbsp;";
		item.className += " zpMenu-item-more";
		item.__zp_state = true; // expanded
		item.__zp_expand = td;
	} else {
		td.appendChild(img);
		item.__zp_icon = td;
	}
};

/**
 * This function gets called from a global event handler when some item was
 * clicked.  It selects the item and toggles it if it has a subtree (expands or
 * collapses it).
 *
 * @param item_id [string] -- the item ID
 */
Zapatec.MenuTree.prototype.itemClicked = function(item_id) {
	this.selectedItem = this.toggleItem(item_id);
	if (this.config.hiliteSelectedNode && this.selectedItem)
		Zapatec.Utils.addClass(this.selectedItem, "zpMenu-item-selected");
	this.onItemSelect(item_id);
};

/**
 * This function toggles an item if the \b state parameter is not specified.
 * If \b state is \b true then it expands the item, and if \b state is \b false
 * then it collapses the item.
 *
 * @param item_id [string] -- the item ID
 * @param state [boolean, optional] -- the desired item state
 *
 * @return a reference to the item element if found, null otherwise
 */
Zapatec.MenuTree.prototype.toggleItem = function(item_id, state) {
	if (item_id) {
		if (this.config.hiliteSelectedNode && this.selectedItem)
			Zapatec.Utils.removeClass(this.selectedItem, "zpMenu-item-selected");
		var item = this.items[item_id];
		if (typeof state == "undefined")
			state = !item.__zp_state;
		if (state != item.__zp_state) {
			var subtree = this._getTree(item.__zp_subtree, this.creating_now);
			if (subtree) {
				this.treeSetDisplay(subtree, state);
				Zapatec.Utils.removeClass(item, "zpMenu-item-expanded");
				Zapatec.Utils.removeClass(item, "zpMenu-item-collapsed");
				Zapatec.Utils.addClass(item, state ? "zpMenu-item-expanded" : "zpMenu-item-collapsed");
			}
			var img = item.__zp_expand;
			if (img)
				img.className = "tgb " + (state ? "minus" : "plus");
			item.__zp_state = state;
			img = item.__zp_icon;
			if (img) {
				img.firstChild.style.display = "none";
				img.appendChild(img.firstChild);
				img.firstChild.style.display = "block";
			}
			if (this.config.compact && state) {
				var hideItems = this._getTree(item.__zp_parent).__zp_items;
				for (var i = hideItems.length; --i >= 0;) {
					if (hideItems[i] != item_id && hideItems[i].__zp_state) {
						this.toggleItem(hideItems[i], false);
						if (hideItems[i].__zp_subtree) {
							// Recursively hide all visible children of non-parent items.
							var subtree = this._getTree(hideItems[i].__zp_subtree);
							this.toggleItem(subtree.firstChild, false);
						}
					}
				}
			}
		}
		return item;
	}
	return null;
};

/**
 * Call this function to collapse all items in the tree.
 */
Zapatec.MenuTree.prototype.collapseAll = function() {
	for (var i in this.trees)
		this.toggleItem(this._getTree(i).__zp_item, false);
};

/**
 * Call this function to expand all items in the tree.
 */
Zapatec.MenuTree.prototype.expandAll = function() {
	for (var i in this.trees)
		this.toggleItem(this._getTree(i).__zp_item, true);
};

/**
 * Call this function to toggle all items in the tree.
 */
Zapatec.MenuTree.prototype.toggleAll = function() {
	for (var i in this.trees)
		this.toggleItem(this._getTree(i).__zp_item);
};

/**
 * Call this function to synchronize the tree to a given item.  This means that
 * all items will be collapsed, except that item and the full path to it.
 *
 * @param item_id [string] -- the ID of the item to sync to.
 */
Zapatec.MenuTree.prototype.sync = function(item_id) {
	var item = this.items[item_id];
	if (item) {
		this.collapseAll();
		this.selectedItem = item;
		var a = [];
		while (item.__zp_parent) {
			a[a.length] = item;
			var pt = this._getTree(item.__zp_parent);
			if (pt.__zp_item)
				item = this.items[pt.__zp_item];
			else
				break;
		}
		for (var i = a.length; --i >= 0;)
			this.toggleItem(a[i].__zp_item, true);
		Zapatec.Utils.addClass(this.selectedItem, "zpMenu-item-selected");
	}
};

/**
 * Destroys the tree.  Removes all elements.  Does not destroy the Zapatec.MenuTree
 * object itself (actually there's no proper way in JavaScript to do that).
 */
Zapatec.MenuTree.prototype.destroy = function() {
	var p = this.top_parent;
	p.parentNode.removeChild(p);
};

/**
 * \internal This function is used when "dynamic initialization" is on.  It
 * retrieves a reference to a subtree if already created, or creates it if it
 * wasn't yet and \b dont_call is \b false (returns null in that case).
 *
 * @param tree_id [string] the ID of the subtree
 * @param dont_call [boolean] pass true here if you don't want the subtree to be created
 *
 * @return reference to the tree if it was found or created, null otherwise.
 */
Zapatec.MenuTree.prototype._getTree = function(tree_id, dont_call) {
	var tree = this.trees[tree_id];
	if (typeof tree == "function") {
		if (dont_call)
			tree = null;
		else {
			tree();
			tree = this.trees[tree_id];
		}
	}
	return tree;
};

// CUSTOMIZABLE EVENT HANDLERS; default action is "do nothing"

/**
 * Third party code can override this member in order to add an event handler
 * that gets called each time a tree item is selected.  It receives a single
 * string parameter containing the item ID.
 */
Zapatec.MenuTree.prototype.onItemSelect = function() {};

// GLOBAL EVENT HANDLERS (to workaround the stupid Microsoft memory leak)

/**
 * \internal This is a global event handler that gets called when a tree item
 * is clicked.  Don't override! ;-)
 */
Zapatec.MenuTree.onItemToggle = function() {
	var item = this;
	var body = document.body;
	while (item && item !=  body && !/zpMenu-item/.test(item.className))
		item = item.parentNode;
	Zapatec.MenuTree.all[item.__zp_tree].itemClicked(item.__zp_item);
};

/* =============================================================== */
/* ==================== Menu Class =============================== */
/* =============================================================== */

/**
 * The Zapatec.Menu object constructor. This inherits from Zapatec.MenuTree,
 * and accepts the same parameters. The main differences are that the tree's
 * "compact" mode is always on, and additional config options are available:
 *
 * - showDelay -- The delay before a submenu is shown, in milliseconds
 *   (default of 0).
 * - hideDelay -- The delay before a submenus is hidden, in milliseconds
 *   (default of 500).
 *
 * @param el [string or HTMLElement] -- the UL element the menu is built on
 * @param config [Object, optional] -- the configuration options
 * @param showDelay [int, delay before showing the menu]
 * @param hideDelay [int, delay before hiding the menu]
 * @param vertical [boolean, make it a vertical menu. Default false]
 * @param onClick [boolean, top menu drops on click not on hover. Default false]
 */

Zapatec.Menu = function(el, config_user) {
	// Arguments are made optional here to be able to inherit from this class
	if (arguments.length > 0) {
		this.init(el, config_user);
	}
};

Zapatec.Menu.prototype = new Zapatec.MenuTree('', null, true);

Zapatec.Menu.prototype.init = function(el, config_user) {
	this._el = el;
	this._config={};
	this.setOption(this._config, 'showDelay', 0);
	this.setOption(this._config, 'hideDelay', 500);
	this.setOption(this._config, 'onClick', false);
	this.setOption(this._config, 'vertical', false);
	this.setOption(this._config, 'scrollWithWindow', false);
	this.setOption(this._config, 'dropShadow', 0);
	this.setOption(this._config, 'drag', false);
	this.setOption(this._config, 'slide', false);
	this.setOption(this._config, 'glide', false);
	this.setOption(this._config, 'fade', false);
	this.setOption(this._config, 'wipe', false);
	this.setOption(this._config, 'unfurl', false);
	this.setOption(this._config, 'animSpeed', 10); // percentage animation per frame.
	this.setOption(this._config, 'compact', true); //always true in a menu
	this.setOption(this._config, 'initLevel', 0); //always 0 in a menu
	this.setOption(this._config, 'defaultIcons', null);

	// User Option overrides
	if (typeof config_user != "undefined")
		for (var i in config_user) {
			//defaults are defined above
			if (typeof this._config[i] == "undefined") {
				//unknown config parameter. Issue an error.
				alert("Error:Menu " + this._el + " has invalid parameter --" + i + ":" + config_user[i]);
			} else {
				//Set the option
			this.setOption(this._config, i, config_user[i]);
			}
		}

	this.animations = [];
	this._menuMode = true;
	this.initTree();
	this.openMenus = [];
	this.clickDone = false;

	if (this.config.scrollWithWindow) {
		Zapatec.ScrollWithWindow.register(this.trees[this._el]);
	}

	if (this.config.drag) {
		var self = this;
		self.dragging = false;
		Zapatec.Utils.addEvent(window.document, "mousedown",
			function(ev) { return Zapatec.Menu.dragStart(ev, self) });
		Zapatec.Utils.addEvent(window.document, "mousemove",
			function(ev) { return Zapatec.Menu.dragMove(ev, self) });
		Zapatec.Utils.addEvent(window.document, "mouseup",
			function(ev) { return Zapatec.Menu.dragEnd(ev, self) });
	}

	// Enforce animation mixing rules: fade + any 1 other.
	if (this._config.fade)
		this.addAnimation('fade');

	if (this._config.slide)
		this.addAnimation('slide');
	else if (this._config.glide)
		this.addAnimation('glide');
	else if (this._config.wipe)
		this.addAnimation('wipe');
	else if (this._config.unfurl)
		this.addAnimation('unfurl');
};

//Constants
Zapatec.Menu.MOUSEOUT = 0;
Zapatec.Menu.MOUSEOVER = 1;
Zapatec.Menu.CLICK = 2;

// Function to set an option.  All new options should be added here
Zapatec.Menu.prototype.setOption = function(config, name, val) {
   config[name] = val;
};

/**
 * Zapatec.Menu.animations is a collection of function references.
 * These are called to progressively style the DOM elements as menus show
 * and hide. They do not have to set item visibility, but may want to set DOM
 * properties like clipping, opacity and position to create custom effects.
 *
 * @param ref [HTMLElement] -- the DOM element that contains the menu items.
 * @param counter [number] -- an animation progress value, from 0 (start) to 100 (end).
 */
Zapatec.Menu.animations = {};

Zapatec.Menu.animations.fade = function(ref, counter) {
	var f = ref.filters, done = (counter==100);
	if (f) {
		if (!done && ref.style.filter.indexOf("alpha") == -1) {
			ref.style.filter += ' alpha(opacity=' + counter + ')';
		}
		else if (f.length && f.alpha) with (f.alpha) {
			if (done) enabled = false;
			else { opacity = counter; enabled=true }
		}
	}
	else {
		ref.style.opacity = ref.style.MozOpacity = counter/100.1;
	}
};

Zapatec.Menu.animations.slide = function(ref, counter) {
	var cP = Math.pow(Math.sin(Math.PI*counter/200),0.75);
	var noClip = ((window.opera || navigator.userAgent.indexOf('KHTML') > -1) ?
		'' : 'rect(auto, auto, auto, auto)');
	if (typeof ref.__zp_origmargintop == 'undefined') {
		ref.__zp_origmargintop = ref.style.marginTop;
	}
	ref.style.marginTop = (counter==100) ?
		ref.__zp_origmargintop : '-' + (ref.offsetHeight*(1-cP)) + 'px';
	ref.style.clip = (counter==100) ? noClip :
		'rect(' + (ref.offsetHeight*(1-cP)) + ', ' + ref.offsetWidth +
		'px, ' + ref.offsetHeight + 'px, 0)';
};

Zapatec.Menu.animations.glide = function(ref, counter) {
	var cP = Math.pow(Math.sin(Math.PI*counter/200),0.75);
	var noClip = ((window.opera || navigator.userAgent.indexOf('KHTML') > -1) ?
		'' : 'rect(auto, auto, auto, auto)');
	ref.style.clip = (counter==100) ? noClip :
		'rect(0, ' + ref.offsetWidth + 'px, ' + (ref.offsetHeight*cP) + 'px, 0)';
};

Zapatec.Menu.animations.wipe = function(ref, counter) {
	var noClip = ((window.opera || navigator.userAgent.indexOf('KHTML') > -1) ?
		'' : 'rect(auto, auto, auto, auto)');
	ref.style.clip = (counter==100) ? noClip :
		'rect(0, ' + (ref.offsetWidth*(counter/100)) + 'px, ' +
		(ref.offsetHeight*(counter/100)) + 'px, 0)';
};

Zapatec.Menu.animations.unfurl = function(ref, counter) {
	var noClip = ((window.opera || navigator.userAgent.indexOf('KHTML') > -1) ?
		'' : 'rect(auto, auto, auto, auto)');
	if (counter <= 50) {
		ref.style.clip = 'rect(0, ' + (ref.offsetWidth*(counter/50)) +
			'px, 10px, 0)';
	}
	else if (counter < 100) {
		ref.style.clip =  'rect(0, ' + ref.offsetWidth + 'px, ' +
			(ref.offsetHeight*((counter-50)/50)) + 'px, 0)';

	}
	else {
		ref.style.clip = noClip;
	}
};

/**
 * Called with the name of an animation (in the Zapatec.Menu.animations[] array)
 * to apply that animation to this menu object.
 *
 * @param animaation [string] -- the name of the animation.
 */
Zapatec.Menu.prototype.addAnimation = function(animation) {
 this.animations[this.animations.length] = Zapatec.Menu.animations[animation];
};

/**
 * \internal Sets the display/visibility of a specified menu, calling
 * defined animation functions and repeatedly calling itself.
 *
 * @param menu [HTMLElement] -- A reference to a menu DOM element.
 * @param show [boolean] -- true shows, false hides.
 */
Zapatec.Menu.prototype.treeSetDisplay = function(menu, show) {
	// First pass on menu creation: just hide.
	if (!menu.__zp_initialised) {
		menu.style.visibility = 'hidden';
		menu.__zp_initialised = true;
		return;
	}

	// Otherwise animate.
	menu.__zp_anim_timer |= 0;
	menu.__zp_anim_counter |= 0;
	var tree, t_id = menu.__zp_tree || menu.__zp_menu.firstChild.__zp_tree;
	if (t_id) tree = Zapatec.MenuTree.all[t_id];
	if (!tree) return;

	clearTimeout(menu.__zp_anim_timer);
	if (show && !menu.__zp_anim_counter) menu.style.visibility = 'inherit';

	var speed = !tree.animations.length ? 100 : tree.config.animSpeed;

	if (speed < 100) {
		for (var a = 0; a < tree.animations.length; a++) {
			tree.animations[a](menu, menu.__zp_anim_counter);
		}
	}

	menu.__zp_anim_counter += speed * (show ? 1 : -1);
	if (menu.__zp_anim_counter > 100) {
		menu.__zp_anim_counter = 100;
	}
	else if (menu.__zp_anim_counter <= 0) {
		menu.__zp_anim_counter = 0;
		menu.style.visibility = 'hidden';
	}
	else {
		menu.__zp_anim_timer = setTimeout(function() {
			tree.treeSetDisplay(menu, show);
		}, 50);
	}
};

// GLOBAL EVENT HANDLERS (to workaround the stupid Microsoft memory leak)

/**
 * \internal This is a global event handler that gets called when a tree item
 * is moused over.
 */
Zapatec.Menu.onItemMouseOver = function() {
	// Loop up the DOM, dispatch event to correct source item.
	var item = this,
		tree = null,
		body = document.body;
	while (item && item !=  body) {
		var t_id = item.__zp_tree || item.firstChild.__zp_tree;
		if (t_id) tree = Zapatec.MenuTree.all[t_id];
		if (/zpMenu-item/.test(item.className) && !/zpMenu-item-hr/.test(item.className)) {
			tree.itemMouseHandler(item.__zp_item, Zapatec.Menu.MOUSEOVER);
		}
		item = tree && item.__zp_treeid ?
			tree.items[item.__zp_item] : item.parentNode;
	}
};

/**
 * \internal This is a global event handler that gets called when a tree item
 * is moused out.
 */
Zapatec.Menu.onItemMouseOut = function() {
	var item = this,
		tree = null,
		body = document.body;
	while (item && item !=  body) {
		var t_id = item.__zp_tree || item.firstChild.__zp_tree;
		if (t_id) tree = Zapatec.MenuTree.all[t_id];
		if (/zpMenu-item/.test(item.className) && !/zpMenu-item-hr/.test(item.className)) {
			tree.itemMouseHandler(item.__zp_item, Zapatec.Menu.MOUSEOUT);
		}
		item = tree && item.__zp_treeid ?
			tree.items[item.__zp_item] : item.parentNode;
	}
};

/**
 * \internal This is a global event handler that gets called when a tree item
 * is clicked, to make the whole item clickable.
 */
Zapatec.Menu.onItemClick = function(e) {
	var item = this,
		tree = null;
		body = document.body;
	while (item && item !=  body) {
		if (item.nodeName && item.nodeName.toLowerCase() == 'a') {
			return true;
		}
		if (/zpMenu-item/.test(item.className)) {
			tree = Zapatec.MenuTree.all[item.__zp_tree];
			// Show-on-click mode test.
			if (tree.config.onClick && item.__zp_subtree &&
				(/zpMenu-top/.test(tree.trees[item.__zp_parent].className))) {
					tree.itemMouseHandler(item.__zp_item, Zapatec.Menu.CLICK);
					e = e || window.event || {};
					if (e.preventDefault) e.preventDefault();
					e.returnValue = false;
					return false;
			}
			// Otherwise navigate the page.
			var itemLink = item.getElementsByTagName('a');
			if (!itemLink || !itemLink.item(0)) return;
			var href = itemLink.item(0).getAttribute('href');
			if (!(/javascript:/).test(href)) {
				window.location.href = href;
				return;
			}
		}
		item = item.parentNode;
	}
};

/**
 * \internal Called from the mouse over/out event handlers to process the
 * mouse event and correctly manage timers.
 *
 * @param item_id [string] -- the item ID
 * @param type [integer] -- 0 = mouseout, 1 = mouseover, 2 = click.
 */
Zapatec.Menu.prototype.itemMouseHandler = function(item_id, type) {
	var item = this.items[item_id];
	if (!item) return;
	//alert(this + '\n' + this.items + '\n' + item_id + '\n' + item);
	var menu = this._getTree(item.__zp_parent);

	// Record an item as lit/shown, and dim/hide any previously lit items.
	if (menu && menu.__zp_activeitem != item_id) {
		if (menu.__zp_activeitem) {
			var lastItem = this.items[menu.__zp_activeitem];
			Zapatec.Utils.removeClass(lastItem, "zpMenu-item-selected");
			// Threading bugfix for some menus remaining visible.
			if (lastItem.__zp_state) this.toggleItem(lastItem.__zp_item, false);
		}
		menu.__zp_activeitem = item_id;
		Zapatec.Utils.addClass(item, "zpMenu-item-selected");
	}
	// Set a timer to dim this item when the whole menu hides.
	clearTimeout(item.__zp_dimtimer);
	if (type == Zapatec.Menu.MOUSEOUT) {
		item.__zp_dimtimer = setTimeout(function() {
			Zapatec.Utils.removeClass(item, "zpMenu-item-selected");
			if (menu.__zp_activeitem == item_id) menu.__zp_activeitem = '';
		}, this.config.hideDelay);
	}

	// Stop any pending show/hide action.
	clearTimeout(item.__zp_mousetimer);
	// Check if this is a click on a first-level menu item.
	if (this.config.onClick && !this.clickDone) {
		if (/zpMenu-top/.test(this.trees[item.__zp_parent].className) &&
			(type == Zapatec.Menu.MOUSEOVER)) return;
		// Set the flag that enables further onmouseover activity.
		if (type == Zapatec.Menu.CLICK) this.clickDone = true;
	}

	// Setup show/hide timers.
	if (!item.__zp_state && type)
	{
		item.__zp_mousetimer = setTimeout('Zapatec.MenuTree.all["' +
			item.__zp_tree + '"].itemShow("' + item.__zp_item + '")',
			(this.config.showDelay || 1));
	}
	else if (item.__zp_state && !type)
	{
		item.__zp_mousetimer = setTimeout('Zapatec.MenuTree.all["' +
			item.__zp_tree + '"].itemHide("' + item.__zp_item + '")',
			(this.config.hideDelay || 1));
	}
};

/**
 * \internal Called from the itemMouseHandler() after a timeout;
 * positions and shows a designated item's branch of the tree.
 *
 * @param item_id [string] -- the item ID
 */
Zapatec.Menu.prototype.itemShow = function(item_id) {
	var item = this.items[item_id];
	var subMenu = this._getTree(item.__zp_subtree);
	var parMenu = this._getTree(item.__zp_parent);
	if (subMenu) {
		// Setting visible here works around MSIE bug where
		// offsetWidth/Height are initially zero.
		if (!subMenu.offsetHeight) {
			subMenu.style.left = '-9999px';
			subMenu.style.visibility = 'visible';
		}

		// Calculate new menu position & check document boundaries.
		var newLeft = 0, newTop = 0;
		if ((/zpMenu-top/.test(this.trees[item.__zp_parent].className))
			&& (!(this.config.vertical))) {
			// Drop Down menus
			newLeft = item.offsetLeft;
			newTop = item.offsetHeight;
		}
		else {
			// Vertical menus.
			newLeft = item.offsetWidth;
			newTop = item.offsetTop;
			// Acquire browser and menu dimensions/positions.
			var scrollX = window.pageXOffset || document.body.scrollLeft ||
				document.documentElement.scrollLeft || 0;
			var scrollY = window.pageYOffset || document.body.scrollTop ||
				document.documentElement.scrollTop || 0;
			var winW = window.innerWidth || document.body.clientWidth ||
				document.documentElement.clientWidth || 0;
			var winH = window.innerHeight || document.body.clientHeight ||
				document.documentElement.clientHeight || 0;
			var menuPos = Zapatec.Utils.getAbsolutePos(parMenu);
			// Adjust menu direction if it will display outside visible area.
			if (menuPos.x + newLeft + subMenu.offsetWidth > scrollX + winW) {
				newLeft = (0 - subMenu.offsetWidth);
			}
			if (menuPos.y + newTop + subMenu.offsetHeight > scrollY + winH) {
				newTop -= subMenu.offsetHeight;
			}
			if (menuPos.x + newLeft < 0) {
				newLeft = 0 - menuPos.x;
			}
			if (menuPos.y + newTop < 0) {
				newTop = 0 - menuPos.y;
			}
		}

		if (!this._config.dropShadow || (this._config.dropShadow && !subMenu.__zp_dropshadow)) {
			// Adjust sub-menu width and height to display borders correctly because
			// browsers use different Box Models
			var fc = subMenu.firstChild;
			subMenu.style.width =
			 (fc.offsetWidth + subMenu.offsetWidth - subMenu.clientWidth) + 'px';
			if (fc.offsetWidth != subMenu.clientWidth) {
				// Browser uses W3C Box Model
				subMenu.style.width = fc.offsetWidth + 'px';
			}
			subMenu.style.height =
			 (fc.offsetHeight + subMenu.offsetHeight - subMenu.clientHeight) + 'px';
			if (fc.offsetHeight != subMenu.clientHeight) {
				// Browser uses W3C Box Model
				subMenu.style.height = fc.offsetHeight + 'px';
			}
			fc.style.position = 'absolute';
			fc.style.left = 0;
			fc.style.top = 0;
			fc.style.visibility = 'inherit';
		}

		if (this._config.dropShadow && !subMenu.__zp_dropshadow) {
			// Apply dropshadow once the menu position has been calculated.
			var ds = subMenu.__zp_dropshadow = Zapatec.Utils.createElement('div');
			subMenu.insertBefore(ds, subMenu.firstChild);
			ds.style.position = 'absolute';
			ds.style.left = '5px';
			ds.style.top = '5px';
			ds.style.width = subMenu.offsetWidth + 'px';
			ds.style.height = subMenu.offsetHeight + 'px';
			ds.style.visibility = 'inherit';
			ds.style.backgroundColor = '#000';
			if (window.opera) {
				ds.style.backgroundColor = '#666'; // opacity doesn't work in Opera
			} else {
				ds.style.filter = 'alpha(opacity=' + this._config.dropShadow + ')';
			}
			ds.style.opacity = this._config.dropShadow / 100;
		}

		// Apply MSIE 5.5+ Select Box fix last, so it corrects the dropshadow.
		if (Zapatec.is_ie && !Zapatec.is_ie5) {
			if (!subMenu.__zp_wch) {
				subMenu.__zp_wch = Zapatec.Utils.createWCH(subMenu);
			}
			subMenu.__zp_wch.style.zIndex = -1;
			Zapatec.Utils.setupWCH(subMenu.__zp_wch, -1, 0,
				subMenu.offsetWidth + 6, subMenu.offsetHeight + 5);
		}

		// Position and show the menu.
		subMenu.style.left = newLeft + 'px';
		subMenu.style.top = newTop + 'px';
		this.toggleItem(item_id, true);
	}
};

/**
 * \internal Called from the itemMouseHandler() after a timeout;
 * hides a designated item's branch of the tree.
 *
 * @param item_id [string] -- the item ID
 */
Zapatec.Menu.prototype.itemHide = function(item_id) {
	var item = this.items[item_id];
	var subMenu = this._getTree(item.__zp_subtree);
	var parMenu = this._getTree(item.__zp_parent);
	if (subMenu) {
		this.toggleItem(item_id, false);
		parMenu.__zp_activeitem = '';
		subMenu.__zp_activeitem = '';
		// Go no further if some items are still expanded.
		for (var i in this.items) {
			if (this.items[i].__zp_state) return;
		}
		// Another click is necessary to activate menu again.
		this.clickDone = false;
	}
};


/**
 * \defgroup dndmove Drag'n'drop (move menu) functions
 *
 * Contains some functions that implement menu "drag'n'drop" facility which
 * allows one to move the menu around the browser's view.
 *
 * @param ev [Event] The Event object
 * @param menu [Object] the Zapatec.Menu object
 */
//@{

/** \internal Starts dragging the element. */
Zapatec.Menu.dragStart = function (ev, menu) {
	ev || (ev = window.event);
	if (menu.dragging) {
		return true;
	}
	var rootMenu = menu.trees[menu._el];
	if (!(/(absolute|fixed)/).test(rootMenu.style.position)) {
		rootMenu.style.position = 'absolute';
		rootMenu.style.left = Zapatec.Utils.getAbsolutePos(rootMenu).x + 'px';
		rootMenu.style.top = Zapatec.Utils.getAbsolutePos(rootMenu).y + 'px';
	}
	var testElm = ev.srcElement || ev.target;
	while (1) {
		if (testElm == rootMenu) break;
		else testElm = testElm.parentNode;
		if (!testElm) return true;
	}
	menu.dragging = true;
	var posX = ev.pageX || ev.clientX + window.document.body.scrollLeft || 0;
	var posY = ev.pageY || ev.clientY + window.document.body.scrollTop || 0;
	var L = parseInt(rootMenu.style.left) || 0;
	var T = parseInt(rootMenu.style.top) || 0;
	menu.xOffs = (posX - L);
	menu.yOffs = (posY - T);
};

/**
 * Called at mouseover and/or mousemove on document, this function repositions
 * the menu according to the current mouse position.
 *
 * @param ev [Event] The Event object
 * @param menu [Object] the Zapatec.Menu object
 * @return false
 */
Zapatec.Menu.dragMove = function (ev, menu) {
	ev || (ev = window.event);
	var rootMenu = menu.trees[menu._el];
	if (!(menu && menu.dragging)) {
		return false;
	}
	var posX = ev.pageX || ev.clientX + window.document.body.scrollLeft || 0;
	var posY = ev.pageY || ev.clientY + window.document.body.scrollTop || 0;
	var st = rootMenu.style, L = posX - menu.xOffs, T = posY - menu.yOffs;
	st.left = L + "px";
	st.top = T + "px";
	//Zapatec.Utils.setupWCH(cal.WCH, L, T);
	return Zapatec.Utils.stopEvent(ev);
};

/**
 * Gets called when the drag and drop operation is finished; thus, at
 * "onmouseup".
 *
 * @param ev [Event] the event object
 * @param menu [Object] the Zapatec.Menu object
 */
Zapatec.Menu.dragEnd = function (ev, menu) {
	if (!menu) {
		return false;
	}
	menu.dragging = false;
};

if (Zapatec.isLite)	
	Zapatec.Utils.addEvent(window, "load", Zapatec.Utils.checkActivation);

//@}

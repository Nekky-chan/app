/*!
 * VisualEditor user interface WikiaSingleMediaDialog class.
 */

/**
 * Dialog for inserting MediaWiki media objects.
 *
 * @class
 * @extends ve.ui.Dialog
 *
 * @constructor
 * @param {Object} [config] Config options
 */
ve.ui.WikiaSingleMediaDialog = function VeUiWikiaSingleMediaDialog( config ) {
	config =  $.extend( config, {
		width: '712px'
	} );

	// Parent constructor
	ve.ui.WikiaSingleMediaDialog.super.call( this, config );
};

/* Inheritance */

OO.inheritClass( ve.ui.WikiaSingleMediaDialog, ve.ui.Dialog );

/* Static Properties */

ve.ui.WikiaSingleMediaDialog.static.name = 'wikiaSingleMedia';

ve.ui.WikiaSingleMediaDialog.static.title = OO.ui.deferMsg( 'visualeditor-dialog-wikiasinglemedia-title' );

ve.ui.WikiaSingleMediaDialog.static.icon = 'gallery';

/* Methods */

ve.ui.WikiaSingleMediaDialog.prototype.initialize = function () {
	// Parent method
	ve.ui.WikiaSingleMediaDialog.super.prototype.initialize.call( this );

	// Properties
	this.query = new ve.ui.WikiaSingleMediaQueryWidget( {
		'$': this.$,
		'placeholder': ve.msg( 'visualeditor-dialog-wikiasinglemedia-search' )
	} );
	this.queryInput = this.query.getInput();
	this.search = new ve.ui.WikiaMediaResultsWidget( { '$': this.$ } );
	this.results = this.search.getResults();

	// Main panels
	this.$main = this.$( '<div>' )
		.addClass( 've-ui-wikiaSingleMediaDialog-main' );
	this.$leftSide = this.$( '<div>' )
		.addClass( 've-ui-wikiaSingleMediaDialog-leftSide' )
		.append( this.search.$element );
	this.cartModel = new ve.dm.WikiaCart();
	this.cart = new ve.ui.WikiaSingleMediaCartWidget( this.cartModel, this );

	// Foot elements
	this.$policy = this.$( '<div>' )
		.addClass( 've-ui-wikiaSingleMediaDialog-policy' );
	this.$policyInner = this.$( '<div>' )
		.addClass( 've-ui-wikiaSingleMediaDialog-policyInner' )
		.html('This is the image policy that has been decided on by this commmunity. Do not upload any photos of kitties or puppies because that is not what this wiki is about and it is played out anyway.');
	this.insertButton = new OO.ui.ButtonWidget( {
		'$': this.$,
		'label': ve.msg( 'wikia-visualeditor-dialog-done-button' ),
		'flags': ['primary']
	} );
	this.cancelButton = new OO.ui.ButtonWidget( {
		'$': this.$,
		'label': ve.msg( 'wikia-visualeditor-dialog-cancel-button' ),
		'flags': ['secondary']
	} );

	// Events
	this.cart.connect( this, { 'layout': 'setLayout' } );
	this.query.connect( this, {
		'requestMediaDone': 'onQueryRequestMediaDone'
	} );
	this.search.connect( this, {
		'nearingEnd': 'onSearchNearingEnd',
		'check': 'onSearchCheck'
	} );
	this.queryInput.connect( this, {
		'change': 'onQueryInputChange'
	} );
	this.insertButton.connect( this, { 'click': [ 'close', { 'action': 'insert' } ] } );
	this.queryInput.$input.on( 'keydown', ve.bind( this.onQueryInputKeydown, this ) );

	// Initialization
	this.frame.$content.addClass( 've-ui-wikiaSingleMediaDialog' );
	this.$main.append( this.$leftSide, this.cart.$element );

	this.$policy.append( this.$policyInner );
	this.$body.append( this.query.$element, this.$main );
	this.$foot.append( this.insertButton.$element, this.cancelButton.$element, this.$policy );

	this.setLayout( 'grid' );
};

ve.ui.WikiaSingleMediaDialog.prototype.onQueryInputKeydown =
	OO.ui.SearchWidget.prototype.onQueryKeydown;

/**
 * Handle closing the dialog.
 *
 * @method
 * @param {string} action Which action is being performed on close.
 */
ve.ui.WikiaSingleMediaDialog.prototype.getTeardownProcess = function ( data ) {
	return ve.ui.WikiaSingleMediaDialog.super.prototype.getTeardownProcess.call( this, data )
		.first( function () {
			if ( data.action === 'insert' ) {
				this.insertMedia();
			}
			this.cartModel.clearItems();
			this.queryInput.setValue( '' );
		}, this );
};

ve.ui.WikiaSingleMediaDialog.prototype.insertMedia = function () {
	var i, linmod = [];

	// Gallery opening
	linmod.push( {
		'type': 'wikiaGallery',
		'attributes': {
			'expand': false,
			'mw': {
				'name': 'gallery'
			}
		}
	} );

	linmod.push( {
		'type': 'alien',
		'attributes': {
			'domElements': $( '<meta typeof="mw:Placeholder" data-parsoid="{&quot;src&quot;:&quot;&quot;}" />' ).toArray()
		}
	} );
	linmod.push( { 'type': '/alien' } );

	// Gallery items
	for ( i = 0; i < this.cartModel.items.length; i++ ) {
		linmod.push( {
			'type': 'wikiaGalleryItem',
			'attributes': {
				'type': 'thumb',
				'align': 'none',
				'href': './' + 'File:' + this.cartModel.items[i].title,
				'src': this.cartModel.items[i].url,
				'resource': './' + 'File:' + this.cartModel.items[i].title,
				'defaultSize': true
			}
		} );
		linmod.push( {
			'type': '/wikiaGalleryItem'
		} );
	}

	linmod.push( {
		'type': 'alien',
		'attributes': {
			'domElements': $( '<meta typeof="mw:Placeholder" data-parsoid="{&quot;src&quot;:&quot;&quot;}" />' ).toArray()
		}
	} );
	linmod.push( { 'type': '/alien' } );

	// Gallery closing
	linmod.push( {
		'type': '/wikiaGallery'
	} );

	this.fragment.collapseRangeToEnd().insertContent( linmod );
};

/*
 * Sets layout between list and grid.
 *
 * @param {string} layout Either 'list' or 'grid'.
 */
ve.ui.WikiaSingleMediaDialog.prototype.setLayout = function ( layout ) {
	if ( layout === 'grid' ) {
		this.$main.css( 'left', 0 );
	} else if ( layout === 'list' ) {
		this.$main.css( 'left', -552 );
	}
	this.emit( 'layout', layout );
};

/**
 * Handle the resulting data from a query media request.
 *
 * @method
 * @param {Object} items An object containing items to add to the search results
 */
ve.ui.WikiaSingleMediaDialog.prototype.onQueryRequestMediaDone = function ( items ) {
	this.search.addItems( items );
};

/**
 * Handle nearing the end of search results.
 *
 * @method
 */
ve.ui.WikiaSingleMediaDialog.prototype.onSearchNearingEnd = function () {
	if ( !this.queryInput.isPending() ) {
		this.query.requestMedia();
	}
};

/**
 * Handle check/uncheck of items in search results.
 *
 * @method
 * @param {Object} item The search result item data.
 */
ve.ui.WikiaSingleMediaDialog.prototype.onSearchCheck = function ( item ) {
	// TODO: Only model should be used here, so no cart nor cartSelect
	var cartItem = this.cart.cartSelect.getItemFromData( item.title );
	if ( cartItem ) {
		this.cartModel.removeItems( [ cartItem.getModel() ] );
	} else {
		this.cartModel.addItems( [ new ve.dm.WikiaImageCartItem(
			item.title,
			item.url
		) ] );
	}
};

/**
 * Handle query input changes.
 *
 * @method
 */
ve.ui.WikiaSingleMediaDialog.prototype.onQueryInputChange = function () {
	this.results.clearItems();
};

/* Registration */

ve.ui.windowFactory.register( ve.ui.WikiaSingleMediaDialog );

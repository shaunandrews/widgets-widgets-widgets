var w3Widgets;
(function($) {

w3Widgets = {
	init: function() {
		$( '.w3-available-widgets .w3-widget' ).draggable({
			helper: 'clone',
			connectToSortable: '.w3-sidebar-widgets .w3-widgets'
		});

		$( '.w3-sidebar-widgets .w3-widgets' ).droppable({
			accept: '.w3-available-widgets .w3-widget',
		});	

		$( '.w3-sidebars' ).droppable({

		});	

		$( '.w3-sidebar-widgets .w3-widgets' ).sortable({
			handle: '.w3-widget-header',
			revert: true
		});

		$( '.w3-tab' ).click( function() {
			$( '.w3-tabs .active' ).removeClass( 'active' );
			$( this ).toggleClass( 'active' );
			var sidebar = $( this ).data( 'sidebar' );

			$( '.w3-sidebars .active' ).removeClass( 'active' );
			$( '.w3-sidebars #sb-' + sidebar ).addClass( 'active' );
		});

		$( '.w3-widget-edit' ).click( function() {
			$( this ).parent().parent().toggleClass( 'editing' );
			$( this ).parent().next().slideToggle( 'fast' );

			if ( $( this ).parent().parent().hasClass( 'editing' ) ) {
				$( this ).html( 'Cancel' );
			}
			else {
				$( this ).html( 'Edit' );
			}
		});
	}
}

$(document).ready(function($){ w3Widgets.init(); });

})(jQuery);

jQuery(document).ready(function($) {

});
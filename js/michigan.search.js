( function ( $, mw ) {
	if ( mw.config.get( 'wgMichiganSearch' ) ) {
		$( function () {

			function isTouchDevice() {
				return !!( 'ontouchstart' in window );
			}

			function isMobileUserAgent() {
				return !!( /mobi|alcatel|Android|android|webOS|webos|iPhone|iPod|Wii|Silk|BlackBerry|playstation|phone|nintendo|htc[-_]|IEMobile|CriOS|Opera Mini|opera.m|palm|panasonic|philips|samsung|Mobile|mobile/i.test( navigator.userAgent ) );
			}

			/* This is here to fix js issue with iPad (all models) */
			$( function () {
				if ( isTouchDevice() && isMobileUserAgent() ) {
					$( '#p-search' ).hide();
					$( 'img.searchbar' ).click( function ( e ) {
						$( '.editbutton' ).hide();
						$( '#echoNotifications' ).hide();
						$( '#usermenu' ).hide();
						$( '.siteLogoBarContainer' ).hide();
						$( '.searchbar' ).hide();
						$( '.cancelsearchbutton' ).fadeIn( 150 );
						$( '#p-search' ).fadeIn( 150 );
						$( '.clicker' ).addClass( 'active' );
						e.stopPropagation();
					} );
					$( 'img.cancelsearchbutton' ).click( function ( e ) {
						$( '.cancelsearchbutton' ).fadeOut(150);
						$( '#p-search' ).fadeOut( 150, function () {
							$( '.searchbar' ).show();
							$( '.editbutton' ).show();
							$( '#echoNotifications' ).show();
							$( '#usermenu' ).show();
							$( '.siteLogoBarContainer' ).show();
							$( '.clicker' ).removeClass( 'active' );
						});
						e.stopPropagation();
					} );
					$( 'img.cancelsearchbutton' ).click( function () {
						if ( $( '#p-search' ).is( ':visible' ) ) {
							$( '.cancelsearchbutton' ).fadeOut(150);
							$( '#p-search' ).fadeOut( 150, function () {
								$( '.searchbar' ).show();
								$( '.editbutton' ).show();
								$( '#echoNotifications' ).show();
								$( '#usermenu' ).show();
								$( '.siteLogoBarContainer' ).show();
								$( '.clicker' ).removeClass( 'active' );
							});
						}
					} );
				}else{
					if ( isTouchDevice() ) {
						$( '#p-search' ).show();						
					}
				}

				/* Fix search bar not showing on iPad */
				if ( /kindle|iPad|PlayBook|Tablet/i.test( navigator.userAgent ) ) {
					$( '#p-search' ).show();
				}
			} );
		} );
	}
}( jQuery, mediaWiki ) );

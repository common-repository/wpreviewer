jQuery( document ).ready( function( $ ) {

    /**************************************************************
     * 1. Review
     * 2. Collapsible
     * 3. Extensions
     *************************************************************/

    /**************************************************************
     * 1. Review
     *************************************************************/

        // Tabs/panel switch
    $( '.reviewer-review-details' ).on( 'click', '.tabs a', function() {

        if ( $( this ).data( 'target' ) !== undefined ) {

            // Tabs
            var tabs = $( this ).parents( '.tabs' );
            tabs.find( 'li' ).removeClass( 'active' );
            $( this ).parent( 'li' ).addClass( 'active' );

            // Panel
            var panels = tabs.parent().find( '.panels' );
            panels.find( '.panel' ).removeClass( 'active' ).hide();
            panels.find( '.panel#' + $( this ).data( 'target' ) ).addClass( 'active' ).show();

            // Set url parameter
            var href = window.location.href;
            href = href.replace( /\&tab=[a-zA-Z0-9_]+/g, '' );
            href = href + '&tab=' + $( this ).data( 'key' );
            window.history.replaceState( 'Object', 'Title', href );
        }

    });

    // Add attribute
    $( '#add-attribute' ).on( 'click', function() {

        var attrName = $( '#add-attribute-name' ).val();
        var attrValue = $( '#add-attribute-value' ).val();

        if ( attrName === '' && attrValue === '' ) {
            return false;
        }

        var newIndex = $( '.attribute-row' ).length++;
        var clone = $( '.attribute-row.template' ).clone().removeClass( 'hidden template' );

        clone.find( '.add-attribute-name' ).attr( 'name', '_attributes[' + newIndex + '][name]' ).val( attrName );
        clone.find( '.add-attribute-value' ).attr( 'name', '_attributes[' + newIndex + '][value]' ).val( attrValue );

        $( '.no-attributes' ).hide();

        $( clone ).appendTo( '.review-attributes' ).hide().slideDown( 'fast' );

        // Reset add row
        $( '#add-attribute-name' ).val( '' ).focus();
        $( '#add-attribute-value' ).val( '' );

    });

    // Delete attribute
    $( document.body ).on( 'click', '.delete-attribute', function() {
        $( this ).parents( '.attribute-row' ).slideUp( 'fast', function() {
            $( this ).remove();
        });
    });

    // Prevent saving the post when hitting enter on 'add attribute' input fields
    $( '#add-attribute-name, #add-attribute-value' ).on( 'keydown', function( e ) {
        if ( (e.keyCode || e.which) == 13 ) {
            $( '#add-attribute' ).trigger( 'click' );
            return false;
        }
    });

    // Move tags meta box
    // Needs to be placed here so everything keeps functional
    $( '#tagsdiv-review_tag' ).appendTo( '#review-tags-panel' ).show();


    /**************************************************************
     * 2. Collapsible
     *
     * Multi-purpose collapsible helpers.
     *************************************************************/

    // Toggle collapsible row
    $( document.body ).on( 'click', '.reviewer-collapsible-top', function(e) {
        var preset = $(this).closest('.reviewer-collapsible'),
            inside = preset.children('.reviewer-collapsible-inside');

        if ( inside.is(':hidden') ) {
            preset.addClass( 'open' );
            inside.slideDown('fast', function() { preset.css({'z-index': 100}) });
        } else {
            inside.slideUp('fast', function() { preset.attr( 'style', '' ).removeClass( 'open' ); });
        }
        e.preventDefault();
    });

    // Sortable collapsible rows
    $('.reviewer-collapsibles').sortable({
        placeholder: 'reviewer-collapsible-placeholder',
        items: '> .reviewer-collapsible',
        handle: '> .reviewer-collapsible-top > .reviewer-collapsible-title',
        cursor: 'move',
        distance: 2,
        containment: '#wpwrap',
        tolerance: 'pointer',
        refreshPositions: true,
    });

    // Dynamic heading changing
    jQuery( document.body ).on( 'change keyup', '.collapsible-dynamic-title', function() {
        $( this ).parents( '.reviewer-collapsible' ).first().find( '> .reviewer-collapsible-top .in-reviewer-collapsible-title' ).html( $( this ).val() );
    });


    /**************************************************************
     * 3. Extensions
     *
     * JS for the 'Extensions' page.
     *************************************************************/
    $( document.body ).on( 'click', '.extensor-box .notify', function() {

        var box = $( this ).parents( '.extensor-box' );

        var data = {
            action:	'extensor_notify_me',
            email: 	'',
            extension: 	box.find( '.extensor-title' ).html(),
            nonce:  rv.nonce
        };
        $.post( ajaxurl, data );

        var notify_email = rv.admin_email;
        var verify_html =
        '<div class="will-be-notified">' +
            '<div class="notified-close"><i class="dashicons dashicons-no-alt"></i></div>' +
            '<div class="notified-text">' +
                '<h3 class="notified-title">Thank you!</h3>' +
                '<p style="font-size: 16px; margin-bottom: 0;"><strong>' + notify_email + '</strong> will be notified when the extension is available.</p>' +
                '<a href="#" class="extensor-update-email">update email</a>' +
            '</div>' +
        '</div>';


        // Slidedown thank you
        $( verify_html ).appendTo( box ).hide().slideDown();

        // Close verification
        box.on( 'click', '.notified-close', function() {
            $( this ).parents( '.will-be-notified' ).slideUp( 'normal', function() {
                $( this ).remove();
            });
        });

        // Show update email form
        box.on( 'click', '.extensor-update-email', function() {
            box.find( '.extensor-update-email-form' ).remove();
            $( this ).after(
                '<div class="extensor-update-email-form" style="margin-top: 10px;">' +
                    '<input type="email" value="' + notify_email + '"> ' +
                    '<a href="#" class="button-primary update">Update</a>' +
                '</div>'
            );
            box.find( '[type=email]' ).focus();
        });

        // Submit email update
        box.on( 'click', '.extensor-update-email-form .update', function() {
            var email = $( this ).parents( 'div' ).find( '[type=email]' ).val();
            $.post( ajaxurl, {
                action:	    'extensor_notify_me',
                email: 	    email,
                extension: 	box.find( '.extensor-title' ).html(),
                nonce:      rv.nonce
            });

            $( '.notified-text' ).html(
                '<h3 class="notified-title">Thank you!</h3>' +
                '<p style="font-size: 16px; margin-bottom: 0;"><strong>' + email + '</strong> will be notified when the extension is available.</p>' +
                '<a href="#" class="extensor-update-email">update email</a>'
            );
        });

    });



});
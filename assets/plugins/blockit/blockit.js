/**************************************************************
 * BlockIt.js
 *
 * @version 0.1.0
 * @author Jeroen Sormani
 * jQuery 1.8+ recommended
 *************************************************************/
;(function( $ )  {

    $.fn.blockIt = function( options ) {
        var settings = $.extend( true, $.fn.blockIt.defaults, options );

        return this.each( function() {
            this.style.position = 'relative';
            blockElement( this, settings );
        });

    };

    $.fn.unBlockIt = function( options ) {
        var settings = $.extend( true, $.fn.blockIt.defaults, options );

        return this.each( function() {
            unBlockElement( this, settings );
        });
    };

    function unBlockElement( el, opts ) {
        $( el ).find( '.blockit, .blockit-message' ).remove();
    }

    function blockElement( el, opts ) {
        var blockElem = $( el );
        var blockHTML = $( '<div />' ).attr( opts.blockAttr ).css( opts.overlayCSS ).appendTo( blockElem );
        var messageHTML = '';
        var iconHTML = '';

        if ( opts.showLoadingIcon ) {
            iconHTML = loadIconTemplate();
        }

        if ( opts.message ) {
            messageHTML = $( '<div />' ).css({ fontWeight: 'bold', textAlign: 'center' }).append( opts.message );
        }

        //blockHTML.after( messageTemplate().append(  ) );
        blockHTML.after( messageTemplate( opts ).append( iconHTML ).append( messageHTML ) );

        return el;
    };

    function messageTemplate( opts ) {
        var messageAttr = {
            class: 'blockit-message',
        };
        return $( '<div />' ).attr( messageAttr ).css( opts.messageCSS );
    }

    function loadIconTemplate() {
        return '<div class="bounce-spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>';
    };

    $.fn.blockIt.defaults = {
        message: '',
        showLoadingIcon: true,
        messageCSS: {
            overflow: 'auto',
            margin: 'auto',
            position: 'absolute',
            left: 0,
            'z-index': 10001,
            width: '100%',
            top: '50%',
            transform: 'translateY(-50%)',
            color: '#333',
            padding: '10px',
            'box-sizing': 'border-box',
            'line-height': '1.4em',
        },
        overlayCSS: {
            margin: 0,
            padding: 15,
            width: '100%',
            height: '100%',
            top: 0,
            left: 0,
            opacity: 0.85,
            position: 'absolute',
            'z-index': 1000,
            backgroundColor: '#FFF',
            'box-sizing': 'border-box'
        },
        blockAttr: {
            class: 'blockit',
        },
    };

})( jQuery );
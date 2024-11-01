(function($) {

    $.fn.repeater = function( options ) {

        var defaults = {
            addTrigger: '.repeater-add',
            removeTrigger: '.repeater-remove',
            template: '.repeater-template',
            elementWrap: '.repeater-row', // Used to identify what is one element within the container.
            elementsContainer: '.repeater-container', // Used to identify what is one element within the container.
            onAddElement: function() {},
            onRemoveElement: function() {},
        };
        options = $.extend( defaults, options );

        return $( this ).filter( ':not(.repeater-active)' ).each( function(i, e) {
            $( this ).addClass( 'repeater-active' );

            var $self;
            var $container;

            var init = function() {
                $container = $self.find( options.elementsContainer );

                $self.on( 'click', options.addTrigger, function(e) {
                    e.preventDefault();
                    addElement();
                });
                $self.on( 'click', options.removeTrigger, function() {
                    removeElement( $(this).parents( options.elementWrap ).first() );
                });
            };

            var getTemplate = function() {
                return $self.find( options.template ).first().clone();
            };

            var addElement = function() {
                var template = getTemplate();

                $container.append( template );
                options.onAddElement.call( $self, template, $container );
            };

            var removeElement = function( el ) {
                el.slideUp( 'fast', function() { $(this).remove() });
                options.onRemoveElement.call( $self, el );
            };

            $self = $(this);
            init();
        });

    }

})(jQuery);
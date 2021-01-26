var resizeRoutinesCounter = 10;

jQuery(document).ready(function ($) {

    var resizeRoutines = function() {      
        $('.better_tilt_outer_resize').each(function() {
			var self = $(this);
			var inner = self.find('.better_tilt_inner_resize');
			// fallback for resize
			//self.removeAttr('style');
			var outerWidth = self.outerHeight();
			var innerWidth = inner.outerHeight();
			if(innerWidth > outerWidth){
			    self.height(innerWidth);
			}
        });
    }
    
    setInterval(function() {
        if(resizeRoutinesCounter) {
            resizeRoutinesCounter--;
            resizeRoutines();
        }
    }, 500);
    
    $(window).resize(function(){
        resizeRoutinesCounter = 10;
    });

    // elementor.hooks.addAction( 'frontend/element_ready/better-tilt', function($scope,$) {
    //     alert($);
    //     console.log($scope);
    //     $('.better_tilt_inner_resize').css({ position: 'relative' });
    // } );
});
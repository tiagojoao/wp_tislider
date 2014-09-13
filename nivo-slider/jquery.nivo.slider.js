/*
 * jQuery Nivo Slider v3.2
 * http://nivo.dev7studios.com
 *
 * Copyright 2012, Dev7studios
 * Free to use and abuse under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */

(function($) {
    var NivoSlider = function(element, options){
        // Defaults are below
        var settings = $.extend({}, $.fn.nivoSlider.defaults, options);

        // Useful variables. Play carefully.
        var vars = {
            currentSlide: 0,
            currentImage: '',
            totalSlides: 0,
            running: true,
            paused: false,
            stop: false,
            controlNavEl: false
        };

        // Get this slider
        var slider = $(element);
        slider.data('nivo:vars', vars).addClass('nivoSlider');
        var images = slider.children();
        // Find our slider children
        //slider.children();
        var kids = $('.nivoSlider').find('.aboveImages');
        var images = kids;
        console.log(kids);
        var div = $('.tislideshow .nivoSlider'), position = 0, last = kids.length - 1, index = 0;
        var interval = setInterval(function() {
        position = position - 2;
        if ( position <= -310 )
        {
          console.log("appending at index + " + index);
          console.log( $(images[index]).find('img').attr('src') );
          $('.nivoSlider').append('<div class="aboveImages"><img src="' + $(images[index]).find('img').attr('src') + '" /></div>');         
          if ( index == 3 ) index = 0; else index++;
          position = 0;
          $('.nivoSlider .aboveImages:first-child').remove();
          kids = $('.nivoSlider').find('.aboveImages');
        }
        $(kids).css('margin-left', position + 'px');
        }, 50);
    } 
    $.fn.nivoSlider = function(options) {
        return this.each(function(key, value){
            var element = $(this);
            // Return early if this element already has a plugin instance
            if (element.data('nivoslider')) { return element.data('nivoslider'); }
            // Pass options to plugin constructor
            var nivoslider = new NivoSlider(this, options);
            // Store plugin object in this element's data
            element.data('nivoslider', nivoslider);
        });
    }
    
 
})(jQuery);

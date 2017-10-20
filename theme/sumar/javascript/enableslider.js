$(function(){
			$('#slides').slides({
				preload: true,
				preloadImage: 'http://www.capacitacionsumar.msal.gov.ar/theme/sumar/pix/loading.gif',
				play: 10000,
				pause: 8000,
				hoverPause: false,
				effect: 'fade',
				fadeEasing: "easeOutQuad",
				crossfade: true,
				fadeSpeed: 650,
				pagination: false,
				generatePagination: false,

				animationStart: function(current){
					$('.caption').animate({
						bottom:-55
					},100);
					if (window.console && console.log) {
						// example return of current slide number
						console.log('animationStart on slide: ', current);
					};
				},
				animationComplete: function(current){
					$('.caption').animate({
						bottom:0
					},200);
					if (window.console && console.log) {
						// example return of current slide number
						console.log('animationComplete on slide: ', current);
					};
				},
				slidesLoaded: function() {
					$('.caption').animate({
						bottom:0
					},200);
				}
			});
		});
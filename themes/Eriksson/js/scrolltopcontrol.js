/**
 * Smooth scroll to top script with
 * stop on mouse clicks or mousewheel
 * Author: JoiNNN
 * 
 * Includes: jQuery mousewheel plugin
 */
/*! Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)
 * Licensed under the MIT License (LICENSE.txt).
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 * Thanks to: Seamus Leahy for adding deltaX and deltaY
 * Version: 3.0.6
 * Requires: 1.2.2+
 */
(function(a){function d(b){var c=b||window.event,d=[].slice.call(arguments,1),e=0,f=!0,g=0,h=0;return b=a.event.fix(c),b.type="mousewheel",c.wheelDelta&&(e=c.wheelDelta/120),c.detail&&(e=-c.detail/3),h=e,c.axis!==undefined&&c.axis===c.HORIZONTAL_AXIS&&(h=0,g=-1*e),c.wheelDeltaY!==undefined&&(h=c.wheelDeltaY/120),c.wheelDeltaX!==undefined&&(g=-1*c.wheelDeltaX/120),d.unshift(b,e,g,h),(a.event.dispatch||a.event.handle).apply(this,d)}var b=["DOMMouseScroll","mousewheel"];if(a.event.fixHooks)for(var c=b.length;c;)a.event.fixHooks[b[--c]]=a.event.mouseHooks;a.event.special.mousewheel={setup:function(){if(this.addEventListener)for(var a=b.length;a;)this.addEventListener(b[--a],d,!1);else this.onmousewheel=d},teardown:function(){if(this.removeEventListener)for(var a=b.length;a;)this.removeEventListener(b[--a],d,!1);else this.onmousewheel=null}},a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})})(jQuery);

//smoothscroll to top on click
jQuery(document).ready(function() {
	$(".scroll").click(function(e){
		//prevent the default action for the click event
		e.preventDefault();
		//this element
		var el = this;
		//get the full url - like mysitecom/index.htm#home
		var full_url = this.href;
		//split the url by # and get the anchor target name - home in mysitecom/index.htm#home
		var parts = full_url.split("#");
		var trgt = parts[1];
		//get the top offset of the target anchor
		var target_offset = $("#"+trgt).offset();
		var target_top = target_offset.top;
		//goto that anchor by setting the body scroll top to anchor top
		$("html, body").animate({scrollTop:target_top}, 500, function() {
			//add the hash in url if scrolling is complete
			if(!$(el).hasClass("clean")) {
				window.location.hash = trgt;
			}
		});
		//stop scrolling if mousewheel or clicks are hit
		$(document).bind("mousewheel mousedown", function(ev) {
			$("html, body").stop()
		});
	});
});

//if hash found in url scroll to coresponding anchor
jQuery(window).load(function() {
	var hash = window.location.hash;
	var target_offset = $(hash).offset();
	var clean = $("a[href=" + hash + "]").hasClass("clean");
	if (target_offset && !clean) {
		var target_top = target_offset.top;
		//scroll
		$("html, body").animate({scrollTop:target_top}, 500);
		//stop scrolling if mousewheel or clicks are hit
		$(document).bind("mousewheel mousedown", function(ev) {
			$("html, body").stop()
		});
	}
});
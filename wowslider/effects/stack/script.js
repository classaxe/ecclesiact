// -----------------------------------------------------------------------------------
// http://wowslider.com/
// JavaScript Wow Slider is a free software that helps you easily generate delicious 
// slideshows with gorgeous transition effects, in a few clicks without writing a single line of code.
// Generated by $AppName$ $AppVersion$
//
//***********************************************
// Obfuscated by Javascript Obfuscator
// http://javascript-source.com
//***********************************************
function ws_stack(e,a,b){var f=jQuery;var h=f(this);var d=f("li",b);var g=f("<div>").addClass("ws_effect").css({position:"absolute",top:0,left:0,width:"100%",height:"100%",overflow:"hidden"}).appendTo(b.parent());function c(m,j,k,i,l){if(e.support.transform&&e.support.transition){if(!j.transform){j.transform=""}if(j.left){j.transform+=" translate3d("+(j.left?j.left:0)+"px,0,0)"}delete j.left;j.transition=k+"ms all "+i+"ms cubic-bezier(0.770, 0.000, 0.175, 1.000)";m.css(j);if(l){m.on("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd",l)}}else{if(l){m.animate(j,k,"easeInOutExpo",l)}else{m.css(j)}}}this.go=function(n,k,q,p){h.trigger("effectStart",g);var j=d.length>2?(n-k+1)%d.length:1;if(Math.abs(q)>=1){j=(q>0)?0:1}j=!!j^!!e.revers;var l=(e.revers?1:-1)*b.width();d.each(function(r){if(j&&r!=k){this.style.zIndex=(Math.max(0,this.style.zIndex-1))}});var m=f("ul",b);var i=f(d.get(j?n:k)).find("img").clone().css({position:"absolute","z-index":4,width:"100%",top:0}),o=f(d.get(j?k:n)).find("img").clone().css({position:"absolute","z-index":4,width:"100%",top:0});c(i,{left:(j?l:0)},e.duration,0);o.css("transform","translate3d("+(j?0:-l*0.5)+"px,0,0)");if(j){o.appendTo(g);i.appendTo(g)}else{i.insertAfter(m);o.insertAfter(m)}if(!j){m.stop(true,true).hide().css({left:-n+"00%"});if(e.fadeOut){m.fadeIn(e.duration)}else{m.show()}}else{if(e.fadeOut){m.fadeOut(e.duration)}}setTimeout(function(){c(i,{left:(j?0:l)},e.duration,e.duration*(j?0:0.1),function(){h.trigger("effectEnd",n);i.remove();o.remove()});c(o,{left:(j?2:0)*b.height()*0.5},e.duration,e.duration*(j?0.1:0))},0);return n}};
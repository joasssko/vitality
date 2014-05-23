/*
* printThis v1.3
* @desc Printing plug-in for jQuery
* @author Jason Day
* 
* Resources (based on) :
*              jPrintArea: http://plugins.jquery.com/project/jPrintArea
*              jqPrint: https://github.com/permanenttourist/jquery.jqprint
*              Ben Nadal: http://www.bennadel.com/blog/1591-Ask-Ben-Print-Part-Of-A-Web-Page-With-jQuery.htm
*
* Dual licensed under the MIT and GPL licenses:
*              http://www.opensource.org/licenses/mit-license.php
*              http://www.gnu.org/licenses/gpl.html
*
* (c) Jason Day 2013
*
* Usage:
*
*  $("#mySelector").printThis({
*      debug: false,              * show the iframe for debugging
*      importCSS: true,           * import page CSS
*      printContainer: true,      * grab outer container as well as the contents of the selector
*      loadCSS: "path/to/my.css", * path to additional css file
*      pageTitle: "",             * add title to print page
*      removeInline: false,       * remove all inline styles from print elements
*      printDelay: 333,           * variable print delay
*      header: null               * prefix to html
*  });
*
* Notes:
*  - the loadCSS will load additional css (with or without @media print) into the iframe, adjusting layout
*/
;(function ($) {
    var opt;
    $.fn.printThis = function (options) {
        opt = $.extend({}, $.fn.printThis.defaults, options);
        var $element = this instanceof jQuery ? this : $(this);
        
            var strFrameName = "printThis-" + (new Date()).getTime();

            if(window.location.hostname !== document.domain && navigator.userAgent.match(/msie/i)){
                // Ugly IE hacks due to IE not inheriting document.domain from parent
                // checks if document.domain is set by comparing the host name against document.domain
                var iframeSrc = "javascript:document.write(\"<head><script>document.domain=\\\"" + document.domain + "\\\";</script></head><body></body>\")";
                var printI= document.createElement('iframe');
                printI.name = "printIframe";
                printI.id = strFrameName;
                printI.className = "MSIE";
                document.body.appendChild(printI);
                printI.src = iframeSrc;
                
            } else {
                 // other browsers inherit document.domain, and IE works if document.domain is not explicitly set
                var $frame = $("<iframe id='" + strFrameName +"' name='printIframe' />");
                $frame.appendTo("body");
            }
            
                       
            var $iframe = $("#" + strFrameName);
            
            // show frame if in debug mode
            if (!opt.debug) $iframe.css({
                position: "absolute",
                width: "0px",
                height: "0px",
                left: "-600px",
                top: "-600px"
            });
            
            
        // $iframe.ready() and $iframe.load were inconsistent between browsers    
        setTimeout ( function () {
            
            var $doc = $iframe.contents();
            
            // import page stylesheets
            if (opt.importCSS) $("link[rel=stylesheet]").each(function () {
                var href = $(this).attr("href");
                if (href) {
                    var media = $(this).attr("media") || "all";
                    $doc.find("head").append("<link type='text/css' rel='stylesheet' href='" + href + "' media='" + media + "'>")
                }
            });
            
            //add title of the page
            if (opt.pageTitle) $doc.find("head").append("<title>" + opt.pageTitle + "</title>");
            
            // import additional stylesheet
            if (opt.loadCSS) $doc.find("head").append("<link type='text/css' rel='stylesheet' href='" + opt.loadCSS + "'>");
            
            // print header
            if (opt.header) $doc.find("body").append(opt.header);

            // grab $.selector as container
            if (opt.printContainer) $doc.find("body").append($element.outer());
                
            // otherwise just print interior elements of container
            else $element.each(function () {
                $doc.find("body").append($(this).html());
            });
            
            // remove inline styles
            if (opt.removeInline) {
                // $.removeAttr available jQuery 1.7+
                if ($.isFunction($.removeAttr)) {
                    $doc.find("body *").removeAttr("style");
                } else {
                    $doc.find("body *").attr("style", "");
                }
            } 
            
            setTimeout(function () {
                if($iframe.hasClass("MSIE")){
                    // check if the iframe was created with the ugly hack
                    // and perform another ugly hack out of neccessity
                    window.frames["printIframe"].focus();
                    $doc.find("head").append("<script>  window.print(); </script>");
                } else {
                    // proper method
                    $iframe[0].contentWindow.focus();
                    $iframe[0].contentWindow.print();  
                }
                
                 $element.trigger( "done");
                 //remove iframe after print
                if (!opt.debug) {
                    setTimeout(function () {
                        $iframe.remove();
                    }, 1000);
                }
                
            }, opt.printDelay);
             
        }, 333 );
        
    };
    
    // defaults
    $.fn.printThis.defaults = {
        debug: false,           // show the iframe for debugging
        importCSS: true,        // import parent page css
        printContainer: true,   // print outer container/$.selector
        loadCSS: "",            // load an additional css file
        pageTitle: "",          // add title to print page
        removeInline: false,    // remove all inline styles
        printDelay: 333,        // variable print delay
        header: null            // prefix to html
    };
    
    // $.selector container
    jQuery.fn.outer = function () {
        return $($("<div></div>").html(this.clone())).html()
    }
})(jQuery);


/*jshint eqnull:true */
/*!
* jQuery Cookie Plugin v1.1
* https://github.com/carhartl/jquery-cookie
*
 * Copyright 2011, Klaus Hartl
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/GPL-2.0
 */
(function($, document) {

var pluses = /\+/g;
function raw(s) {
    return s;
}
function decoded(s) {
    return decodeURIComponent(s.replace(pluses, ' '));
}

$.cookie = function(key, value, options) {

    // key and at least value given, set cookie...
    if (arguments.length > 1 && (!/Object/.test(Object.prototype.toString.call(value)) || value == null)) {
        options = $.extend({}, $.cookie.defaults, options);

        if (value == null) {
            options.expires = -1;
        }

        if (typeof options.expires === 'number') {
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }

        value = String(value);

        return (document.cookie = [
            encodeURIComponent(key), '=', options.raw ? value : encodeURIComponent(value),
            options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
            options.path    ? '; path=' + options.path : '',
            options.domain  ? '; domain=' + options.domain : '',
            options.secure  ? '; secure' : ''
        ].join(''));
    }

    // key and possibly options given, get cookie...
    options = value || $.cookie.defaults || {};
    var decode = options.raw ? raw : decoded;
    var cookies = document.cookie.split('; ');
    for (var i = 0, parts; (parts = cookies[i] && cookies[i].split('=')); i++) {
        if (decode(parts.shift()) === key) {
            return decode(parts.join('='));
        }
    }
    return null;
};

  $.cookie.defaults = {};

   })(jQuery, document);


jQuery(document).ready(function($) {	
	jQuery(".fontSizeSmall").click(function(){
		jQuery(".post p").css("font-size","13px") 
	});

	jQuery(".fontSizeMedium").click(function(){
		jQuery(".post p").css("font-size","15px") 
	});

	jQuery(".fontSizeLarge").click(function(){
		jQuery(".post p").css("font-size","17px") 
	});
	
	
});


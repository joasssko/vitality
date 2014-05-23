/**
 * Image Desaturate - jQuery plugin
 * Desaturate (convert) all types of images on web page
 * 
 * (c) 2010 Dmitry Kelmi <miksir@maker.ru>
 * Version: 0.7 (09 Nov 2010)
 * Requires: jQuery v1.3+
 * 
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 * 
 * How to use:
 *  $(selector).desaturate(options);
 *  options = {
 *    'iefix': true or false  - autofix images for IE(6-8)
 *  }
 *
 *  IE fix need in following cases:
 *    - image is png with transparency - it will apply IE fix for png
 *    - opacity of desaturated image will be changed with fadeIn/fadeOut - jQuery can reset filters after
 *      fadeIn / fadeOut and lost desaturate effect
 *  'iefix' apply fixes only to target of desaturate() and not to other png images!
 *  If you set 'iefix' to false, you can fix images with .desaturateImgFix(), note: all other kind of scripts
 *  for PNG IE fix wont work with .desaturate().
 *
 *  Note: desaturate will replace current image and return new node as result (for all browsers)
 */
jQuery.desaturate = {
  defaults: {
    'onswitch': null,
    'iefix': true, // autofix png for IE
    'ie9canvas': true,
    'level': 1,    // level of desaturation, ignored in IE
    'rgb': [0.3333, 0.3333, 0.3333], // levels of RGB for compose grayscale, ignored in IE
    'colorize': [0, 0, 0] // color [r, g, b] for replace black point
  },
  customClass: 'js-desaturate-fixed' // usually no need to change this
};

jQuery.desaturate.Image = function(obj) {
    this.image = obj;
    this.canvas = null;
    this.options = {};
    this.jImage = jQuery(this.image);
    var self = this;

    this.jImage.bind("load.desaturate", function() { self.imageLoaded.call(self); });
    this.loaded = this.image.complete;

    this.src = this.jImage.attr('src');
    this.isPNG = this.jImage.is("IMG[src$=.png]");

    var styleWidth  = new String(this.jImage.css('width')); styleWidth = styleWidth.replace(/px/, '');
    var styleHeight = new String(this.jImage.css('height')); styleHeight = styleHeight.replace(/px/, '');

    this.width = this.jImage.width() ? this.jImage.width() : (styleWidth ? styleWidth : this.jImage.attr('width'));
    this.height = this.jImage.height() ? this.jImage.height() : (styleHeight ? styleHeight : this.jImage.attr('height'));

//      var styles = ['padding', 'margin', 'border'];
//      for (var i in styles) {
//        this.imgCustomStyles += styles[i] + ':' + this.image.style[styles[i]]+';';
//        this.image.style[styles[i]] = '';
//      }

    this.imgFilter = '';
    if (this.image.style.filter) {
      this.imgFilter = 'filter:'+this.image.style.filter+';';
      this.image.style.filter = '';
    }

    this.image.style.width = '';
    this.image.style.height = '';

    this.imgId    = this.jImage.attr('id') ? 'id="' + this.jImage.attr('id') + '" ' : '';
    this.imgClass = 'class="' + this.jImage.attr('class') + ' ' + jQuery.desaturate.customClass + '" ';
    this.imgTitle = this.jImage.attr('title') ? 'title="' + this.jImage.attr('title') + '" ' : '';
    this.imgAlt   = this.jImage.attr('alt') ? 'alt="' + this.jImage.attr('alt') + '" ' : '';

    this.imgStyles  = this.image.style.cssText;
    this.imgStyles += this.jImage.attr('align') ? 'float:' + this.jImage.attr('align') + ';' : '';
    this.imgStyles += this.jImage.parent().attr('href') ? 'cursor:hand;' : '';

    // nulled filter present as FILTER: in cssText
    this.imgStyles = this.imgStyles.replace(/filter:/i,'');


    this.imgCssSize = (this.width && this.height) ? 'width:' + this.width + 'px;' + 'height:' + this.height + 'px;' : '';
};

jQuery.desaturate.Image.prototype.imageLoaded = function() {
  this.loaded = true;
  this.jImage.unbind("load.desaturate");
  if (this.canvas)
  {
    this.replaceImageWithCanvas();
    this.canvas = null;
  }
};

jQuery.desaturate.Image.prototype.replace = function(html) {
    return jQuery(html).replaceAll(this.image).get(0);
};

jQuery.desaturate.Image.prototype.prepareCanvas = function() {
  var canvasStr = '<canvas style="display:inline-block;' + this.imgStyles + this.imgCssSize + '" ';
  canvasStr += this.imgId + this.imgClass + this.imgTitle + this.imgAlt + '></canvas>';

  this.canvas = jQuery(canvasStr).get(0);
  return this.canvas;
};

jQuery.desaturate.Image.prototype.switchToCanvas = function() {
  if (this.loaded && this.canvas) {
    this.replaceImageWithCanvas();
    this.canvas = null;
  }
};

jQuery.desaturate.Image.prototype.replaceImageWithCanvas = function() {
    var el = this.replace(this.getCanvas());
    if (typeof(this.options.onswitch) == 'function')
       this.options.onswitch.call(el);
};

jQuery.desaturate.Image.prototype.getCanvas = function() {
    var options = this.options;
    var canvas = this.canvas;
    var canvasContext = canvas.getContext('2d');

    var imgW = this.width;
    var imgH = this.height;
    canvas.width = imgW;
    canvas.height = imgH;

    canvasContext.drawImage(this.image, 0, 0);

    var imgPixels = canvasContext.getImageData(0, 0, imgW, imgH);
    var optionsLevelN = 1-options.level;

    for(var y = 0; y < imgPixels.height; y++){
      for(var x = 0; x < imgPixels.width; x++){
        var i = y * 4 * imgPixels.width + x * 4;
        var avg = imgPixels.data[i] * options.rgb[0] +
                  imgPixels.data[i + 1] * options.rgb[1] +
                  imgPixels.data[i + 2] * options.rgb[2];
        avg = avg * options.level;
        var avgN = 255 - avg;
        imgPixels.data[i]     = avg + options.colorize[0] - Math.round(avg*options.colorize[0]/255) + Math.round(imgPixels.data[i] * optionsLevelN);
        imgPixels.data[i + 1] = avg + options.colorize[1] - Math.round(avg*options.colorize[1]/255) + Math.round(imgPixels.data[i + 1] * optionsLevelN);
        imgPixels.data[i + 2] = avg + options.colorize[2] - Math.round(avg*options.colorize[2]/255) + Math.round(imgPixels.data[i + 2] * optionsLevelN);
      }
    }

    canvasContext.putImageData(imgPixels, 0, 0, 0, 0, imgPixels.width, imgPixels.height);
    return canvas;
};

jQuery.desaturate.Image.prototype.getIeFix = function() {
    /* Some jQuery operations like fadeIn/Out can reset filter atribute, so we need 3 SPAN's: 1st for styles and
     * correct work with jQuery's animation, 2rd for grayScale filter and last one for alpha image filter.
     * Combined 2 filters in one span won't work too.
     */
    var blockInit = 'display:block;background:transparent;padding:0;margin:0;';
    var strNewHTML = '<span style="display:inline-block;' + this.imgStyles + this.imgCssSize + '" ';
    strNewHTML += this.imgId + this.imgClass + this.imgTitle + this.imgAlt + '>';
      strNewHTML += '<span style="' + blockInit + this.imgCssSize + this.imgFilter + '">';
      if (this.isPNG) {
        strNewHTML += '<span style="' + blockInit + this.imgCssSize;
        strNewHTML += 'filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'' + this.src + '\', sizingMethod=\'crop\');">';
        strNewHTML += '</span>';
      } else {
        strNewHTML += '<img style="' + blockInit + this.imgCssSize + '" ' + this.imgTitle + this.imgAlt;
        strNewHTML += ' src="' + this.src + '">';
      }
      strNewHTML += '</span>';
    strNewHTML += '</span>';

    return jQuery(strNewHTML).get(0);
};

jQuery.fn.desaturate = function(options) {

  var ret = [];
  var _opt = jQuery.extend(true, {}, jQuery.desaturate.defaults, options);

  this.each(function() {
    var el = this;
    var image = null;
    var $opt = jQuery.extend(true, {}, _opt, jQuery.metadata ? jQuery(el).metadata() : {}, jQuery(el).data('desaturate'));

    var bVer = parseInt($.browser.version, 10);
    var oldIE = jQuery.browser.msie && (bVer < 9 || !$opt.ie9canvas) ;

    if (oldIE && jQuery(el).is("IMG") && $opt.iefix) {
      // autofix IE images
      image = new jQuery.desaturate.Image(el);
      image.options = $opt;
      el = image.replace(image.getIeFix());
    }

    if (oldIE && (jQuery(el).is("IMG") || jQuery(el).hasClass(jQuery.desaturate.customClass))) {
      // apply filter for IE
        var el1 = el;
        if (jQuery(el).hasClass(jQuery.desaturate.customClass))
        {
          // if this element is our imgage fixed by pngIE - set grayscale filter to child span
          el1 = jQuery("SPAN", el).get(0);
        }
        el1.style.filter = (el1.style.filter ? el1.style.filter+' ' : '') +
                            'progid:DXImageTransform.Microsoft.BasicImage(grayScale=1)';

        if (typeof($opt.onswitch) == 'function')
          jQuery.proxy($opt.onswitch, el);
    }

    if (!oldIE && (jQuery(el).is("IMG"))) {
      // convert image to canvas
      image = new jQuery.desaturate.Image(el);
      image.options = $opt;
      el = image.prepareCanvas();
      image.switchToCanvas();
    }

    ret.push(el);
  });

  return this.pushStack(ret, "desaturate", "");
};

jQuery.fn.desaturateImgFix = function(options) {
  if (!jQuery.browser.msie) {
    return this;
  }

  var _opt = jQuery.extend(true, {}, jQuery.desaturate.defaults, options);
  var ret = [];

  this.each(function() {
    var $opt = jQuery.extend(true, {}, _opt, jQuery.metadata ? jQuery(this).metadata() : {}, jQuery(this).data('desaturate'));
    if (!jQuery(this).is("IMG")) {
      ret.push(this);
    } else {
      var image = new jQuery.desaturate.Image(this);
      image.options = $opt;
      ret.push(image.replace(image.getIeFix()));
    }
  });

  return this.pushStack(ret, "desaturateImgFix", "");
};


/*
 Shadow animation jQuery-plugin 1.7
 http://www.bitstorm.org/jquery/shadow-animation/
 Copyright 2011 Edwin Martin <edwin@bitstorm.org>
 Contributors: Mark Carver, Xavier Lepretre
 Released under the MIT and GPL licenses.
*/
jQuery(function(e,i){function j(){var a=e("script:first"),b=a.css("color"),c=false;if(/^rgba/.test(b))c=true;else try{c=b!=a.css("color","rgba(0, 0, 0, 0.5)").css("color");a.css("color",b)}catch(d){}return c}function k(a,b,c){var d=[];a.c&&d.push("inset");typeof b.left!="undefined"&&d.push(parseInt(a.left+c*(b.left-a.left),10)+"px "+parseInt(a.top+c*(b.top-a.top),10)+"px");typeof b.blur!="undefined"&&d.push(parseInt(a.blur+c*(b.blur-a.blur),10)+"px");typeof b.a!="undefined"&&d.push(parseInt(a.a+c*
(b.a-a.a),10)+"px");if(typeof b.color!="undefined"){var g="rgb"+(e.support.rgba?"a":"")+"("+parseInt(a.color[0]+c*(b.color[0]-a.color[0]),10)+","+parseInt(a.color[1]+c*(b.color[1]-a.color[1]),10)+","+parseInt(a.color[2]+c*(b.color[2]-a.color[2]),10);if(e.support.rgba)g+=","+parseFloat(a.color[3]+c*(b.color[3]-a.color[3]));g+=")";d.push(g)}return d.join(" ")}function h(a){var b,c,d={};if(b=/#([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/.exec(a))c=[parseInt(b[1],16),parseInt(b[2],16),parseInt(b[3],
16),1];else if(b=/#([0-9a-fA-F])([0-9a-fA-F])([0-9a-fA-F])/.exec(a))c=[parseInt(b[1],16)*17,parseInt(b[2],16)*17,parseInt(b[3],16)*17,1];else if(b=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(a))c=[parseInt(b[1],10),parseInt(b[2],10),parseInt(b[3],10),1];else if(b=/rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9\.]*)\s*\)/.exec(a))c=[parseInt(b[1],10),parseInt(b[2],10),parseInt(b[3],10),parseFloat(b[4])];d=(b=/(-?[0-9]+)(?:px)?\s+(-?[0-9]+)(?:px)?(?:\s+(-?[0-9]+)(?:px)?)?(?:\s+(-?[0-9]+)(?:px)?)?/.exec(a))?
{left:parseInt(b[1],10),top:parseInt(b[2],10),blur:b[3]?parseInt(b[3],10):0,a:b[4]?parseInt(b[4],10):0}:{left:0,top:0,blur:0,a:0};d.c=/inset/.test(a);d.color=c;return d}e.extend(true,e,{support:{rgba:j()}});var f;e.each(["boxShadow","MozBoxShadow","WebkitBoxShadow"],function(a,b){a=e("html").css(b);if(typeof a=="string"&&a!=""){f=b;return false}});if(f)e.fx.step.boxShadow=function(a){if(!a.init){a.b=h(e(a.elem).get(0).style[f]||e(a.elem).css(f));a.end=e.extend({},a.b,h(a.end));if(a.b.color==i)a.b.color=
a.end.color||[0,0,0];a.init=true}a.elem.style[f]=k(a.b,a.end,a.pos)}});
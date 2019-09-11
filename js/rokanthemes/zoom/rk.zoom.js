//////////////////////////////////////////////////////////////////////////////////
// Cloud Zoom V1.0.2
// (c) 2010 by R Cecco. <http://www.professorcloud.com>
// MIT License
//
// Please retain this copyright header in all versions of the software
//////////////////////////////////////////////////////////////////////////////////
(function ($jq) {

    $jq(document).ready(function () {
        $jq('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();
		$jq(".rokanthemes-more-img li a").click(function(){
			$jq("a.ma-a-lighbox").attr("href",$jq(this).attr("name"));
		});
    });

    function format(str) {
        for (var i = 1; i < arguments.length; i++) {
            str = str.replace('%' + (i - 1), arguments[i]);
        }
        return str;
    }

    function CloudZoom(jWin, opts) {
        var sImg = $jq('img', jWin);
		var	img1;
		var	img2;
        var zoomDiv = null;
		var	$jqmouseTrap = null;
		var	lens = null;
		var	$jqtint = null;
		var	softFocus = null;
		var	$jqie6Fix = null;
		var	zoomImage;
        var controlTimer = 0;      
        var cw, ch;
        var destU = 0;
		var	destV = 0;
        var currV = 0;
        var currU = 0;      
        var filesLoaded = 0;
        var mx,
            my; 
        var ctx = this, zw;
        // Display an image loading message. This message gets deleted when the images have loaded and the zoom init function is called.
        // We add a small delay before the message is displayed to avoid the message flicking on then off again virtually immediately if the
        // images load really fast, e.g. from the cache. 
        //var	ctx = this;
        setTimeout(function () {
            //						 <img src="/images/loading.gif"/>
            if ($jqmouseTrap === null) {
                var w = jWin.width();
                jWin.parent().append(format('<div style="width:%0px;position:absolute;top:75%;left:%1px;text-align:center" class="cloud-zoom-loading" >Loading...</div>', w / 3, (w / 2) - (w / 6))).find(':last').css('opacity', 0.5);
            }
        }, 200);


        var ie6FixRemove = function () {

            if ($jqie6Fix !== null) {
                $jqie6Fix.remove();
                $jqie6Fix = null;
            }
        };

        // Removes cursor, tint layer, blur layer etc.
        this.removeBits = function () {
            //$jqmouseTrap.unbind();
            if (lens) {
                lens.remove();
                lens = null;             
            }
            if ($jqtint) {
                $jqtint.remove();
                $jqtint = null;
            }
            if (softFocus) {
                softFocus.remove();
                softFocus = null;
            }
            ie6FixRemove();

            $jq('.cloud-zoom-loading', jWin.parent()).remove();
        };


        this.destroy = function () {
            jWin.data('zoom', null);

            if ($jqmouseTrap) {
                $jqmouseTrap.unbind();
                $jqmouseTrap.remove();
                $jqmouseTrap = null;
            }
            if (zoomDiv) {
                zoomDiv.remove();
                zoomDiv = null;
            }
            //ie6FixRemove();
            this.removeBits();
            // DON'T FORGET TO REMOVE JQUERY 'DATA' VALUES
        };


        // This is called when the zoom window has faded out so it can be removed.
        this.fadedOut = function () {
            
			if (zoomDiv) {
                zoomDiv.remove();
                zoomDiv = null;
            }
			 this.removeBits();
            //ie6FixRemove();
        };

        this.controlLoop = function () {
            if (lens) {
                var x = (mx - sImg.offset().left - (cw * 0.5)) >> 0;
                var y = (my - sImg.offset().top - (ch * 0.5)) >> 0;
               
                if (x < 0) {
                    x = 0;
                }
                else if (x > (sImg.outerWidth() - cw)) {
                    x = (sImg.outerWidth() - cw);
                }
                if (y < 0) {
                    y = 0;
                }
                else if (y > (sImg.outerHeight() - ch)) {
                    y = (sImg.outerHeight() - ch);
                }

                lens.css({
                    left: x,
                    top: y
                });
                lens.css('background-position', (-x) + 'px ' + (-y) + 'px');

                destU = (((x) / sImg.outerWidth()) * zoomImage.width) >> 0;
                destV = (((y) / sImg.outerHeight()) * zoomImage.height) >> 0;
                currU += (destU - currU) / opts.smoothMove;
                currV += (destV - currV) / opts.smoothMove;

                zoomDiv.css('background-position', (-(currU >> 0) + 'px ') + (-(currV >> 0) + 'px'));              
            }
            controlTimer = setTimeout(function () {
                ctx.controlLoop();
            }, 30);
        };

        this.init2 = function (img, id) {

            filesLoaded++;
            //console.log(img.src + ' ' + id + ' ' + img.width);	
            if (id === 1) {
                zoomImage = img;
            }
            //this.images[id] = img;
            if (filesLoaded === 2) {
                this.init();
            }
        };

        /* Init function start.  */
        this.init = function () {
            // Remove loading message (if present);
            $jq('.cloud-zoom-loading', jWin.parent()).remove();


/* Add a box (mouseTrap) over the small image to trap mouse events.
		It has priority over zoom window to avoid issues with inner zoom.
		We need the dummy background image as IE does not trap mouse events on
		transparent parts of a div.
		*/
            $jqmouseTrap = jWin.parent().append(format("<div class='mousetrap' style='background:#fff;opacity:0;filter:alpha(opacity=0);z-index:99;position:absolute;width:%0px;height:%1px;left:%2px;top:%3px;\'></div>", sImg.outerWidth(), sImg.outerHeight(), 0, 0)).find(':last');

            //////////////////////////////////////////////////////////////////////			
            /* Do as little as possible in mousemove event to prevent slowdown. */
            $jqmouseTrap.bind('mousemove', this, function (event) {
                // Just update the mouse position
                mx = event.pageX;
                my = event.pageY;
            });
            //////////////////////////////////////////////////////////////////////					
            $jqmouseTrap.bind('mouseleave', this, function (event) {
                clearTimeout(controlTimer);
                //event.data.removeBits();                
				if(lens) { lens.fadeOut(299); }
				if($jqtint) { $jqtint.fadeOut(299); }
				if(softFocus) { softFocus.fadeOut(299); }
				zoomDiv.fadeOut(300, function () {
                    ctx.fadedOut();
                });																
                return false;
            });
            //////////////////////////////////////////////////////////////////////			
            $jqmouseTrap.bind('mouseenter', this, function (event) {
				mx = event.pageX;
                my = event.pageY;
                zw = event.data;
                if (zoomDiv) {
                    zoomDiv.stop(true, false);
                    zoomDiv.remove();
                }

                var xPos = opts.adjustX,
                    yPos = opts.adjustY;
                             
                var siw = sImg.outerWidth();
                var sih = sImg.outerHeight();

                var w = opts.zoomWidth;
                var h = opts.zoomHeight;
                if (opts.zoomWidth == 'auto') {
                    w = siw;
                }
                if (opts.zoomHeight == 'auto') {
                    h = sih;
                }
                //$jq('#info').text( xPos + ' ' + yPos + ' ' + siw + ' ' + sih );
                var appendTo = jWin.parent(); // attach to the wrapper			
                switch (opts.position) {
                case 'top':
                    yPos -= h; // + opts.adjustY;
                    break;
                case 'right':
                    xPos += siw; // + opts.adjustX;					
                    break;
                case 'bottom':
                    yPos += sih; // + opts.adjustY;
                    break;
                case 'left':
                    xPos -= w; // + opts.adjustX;					
                    break;
                case 'inside':
                    w = siw;
                    h = sih;
                    break;
                    // All other values, try and find an id in the dom to attach to.
                default:
                    appendTo = $jq('#' + opts.position);
                    // If dom element doesn't exit, just use 'right' position as default.
                    if (!appendTo.length) {
                        appendTo = jWin;
                        xPos += siw; //+ opts.adjustX;
                        yPos += sih; // + opts.adjustY;	
                    } else {
                        w = appendTo.innerWidth();
                        h = appendTo.innerHeight();
                    }
                }

                zoomDiv = appendTo.append(format('<div id="cloud-zoom-big" class="cloud-zoom-big" style="display:none;position:absolute;left:%0px;top:%1px;width:%2px;height:%3px;background-image:url(\'%4\');z-index:99;"></div>', xPos, yPos, w, h, zoomImage.src)).find(':last');

                // Add the title from title tag.
                if (sImg.attr('title') && opts.showTitle) {
                    zoomDiv.append(format('<div class="cloud-zoom-title">%0</div>', sImg.attr('title'))).find(':last').css('opacity', opts.titleOpacity);
                }

                // Fix ie6 select elements wrong z-index bug. Placing an iFrame over the select element solves the issue...		
                if ($jq.browser.msie && $jq.browser.version < 7) {
                    $jqie6Fix = $jq('<iframe frameborder="0" src="#"></iframe>').css({
                        position: "absolute",
                        left: xPos,
                        top: yPos,
                        zIndex: 99,
                        width: w,
                        height: h
                    }).insertBefore(zoomDiv);
                }

                zoomDiv.fadeIn(500);

                if (lens) {
                    lens.remove();
                    lens = null;
                } /* Work out size of cursor */
                
                
                if(typeof event.data.zoomDivWidth == 'undefined'){
                	event.data.zoomDivWidth = zoomDiv.get(0).offsetWidth - (zoomDiv.css('border-left-width').replace(/\D/g, '')*1) - (zoomDiv.css('border-right-width').replace(/\D/g, '')*1);
                }
                if(typeof event.data.zoomDivHeight == 'undefined'){
                	event.data.zoomDivHeight = zoomDiv.get(0).offsetHeight - (zoomDiv.css('border-top-width').replace(/\D/g, '')*1) - (zoomDiv.css('border-bottom-width').replace(/\D/g, '')*1);
                }
                
                cw = (sImg.outerWidth() / zoomImage.width) * event.data.zoomDivWidth;
                
                ch = (sImg.outerHeight() / zoomImage.height) * event.data.zoomDivHeight;


                // Attach mouse, initially invisible to prevent first frame glitch
                lens = jWin.append(format("<div class = 'cloud-zoom-lens' style='display:none;z-index:98;position:absolute;width:%0px;height:%1px;'></div>", cw, ch)).find(':last');

                $jqmouseTrap.css('cursor', lens.css('cursor'));

                var noTrans = false;

                // Init tint layer if needed. (Not relevant if using inside mode)			
                if (opts.tint) {
                    lens.css('background', 'url("' + sImg.attr('src') + '")');
                    $jqtint = jWin.append(format('<div style="display:none;position:absolute; left:0px; top:0px; width:%0px; height:%1px; background-color:%2;" />', sImg.outerWidth(), sImg.outerHeight(), opts.tint)).find(':last');
                    $jqtint.css('opacity', opts.tintOpacity);                    
					noTrans = true;
					$jqtint.fadeIn(500);

                }
                if (opts.softFocus) {
                    lens.css('background', 'url("' + sImg.attr('src') + '")');
                    softFocus = jWin.append(format('<div style="position:absolute;display:none;top:2px; left:2px; width:%0px; height:%1px;" />', sImg.outerWidth() - 2, sImg.outerHeight() - 2, opts.tint)).find(':last');
                    softFocus.css('background', 'url("' + sImg.attr('src') + '")');
                    softFocus.css('opacity', 0.5);
                    noTrans = true;
                    softFocus.fadeIn(500);
                }

                if (!noTrans) {
                    lens.css('opacity', opts.lensOpacity);										
                }
				if ( opts.position !== 'inside' ) { lens.fadeIn(500); }

                // Start processing. 
                zw.controlLoop();

                return; // Don't return false here otherwise opera will not detect change of the mouse pointer type.
            });
        };

        img1 = new Image();
        $jq(img1).load(function () {
            ctx.init2(this, 0);
        });
        img1.src = sImg.attr('src');

        img2 = new Image();
        $jq(img2).load(function () {
            ctx.init2(this, 1);
        });
        img2.src = jWin.attr('href');
    }

    $jq.fn.CloudZoom = function (options) {
        // IE6 background image flicker fix
        try {
            document.execCommand("BackgroundImageCache", false, true);
        } catch (e) {}
        this.each(function () {
			var	relOpts, opts;
			// Hmm...eval...slap on wrist.
			eval('var	a = {' + $jq(this).attr('rel') + '}');
			relOpts = a;
            if ($jq(this).is('.cloud-zoom')) {
                $jq(this).css({
                    'position': 'relative',
                    'display': 'block'
                });
                $jq('img', $jq(this)).css({
                    'display': 'block'
                });
                // Wrap an outer div around the link so we can attach things without them becoming part of the link.
                // But not if wrap already exists.
                if ($jq(this).parent().attr('id') != 'wrap') {
                    $jq(this).wrap('<div id="wrap" style="top:0px;z-index:99;position:relative;"></div>');
                }
                opts = $jq.extend({}, $jq.fn.CloudZoom.defaults, options);
                opts = $jq.extend({}, opts, relOpts);
                $jq(this).data('zoom', new CloudZoom($jq(this), opts));

            } else if ($jq(this).is('.cloud-zoom-gallery')) {
                opts = $jq.extend({}, relOpts, options);
                $jq(this).data('relOpts', opts);
                $jq(this).bind('click', $jq(this), function (event) {
                    var data = event.data.data('relOpts');
                    // Destroy the previous zoom
                    $jq('#' + data.useZoom).data('zoom').destroy();
                    // Change the biglink to point to the new big image.
                    $jq('#' + data.useZoom).attr('href', event.data.attr('href'));
                    // Change the small image to point to the new small image.
                    $jq('#' + data.useZoom + ' img').attr('src', event.data.data('relOpts').smallImage);
                    // Init a new zoom with the new images.				
                    $jq('#' + event.data.data('relOpts').useZoom).CloudZoom();
                    return false;
                });
            }
        });
        return this;
    };

    $jq.fn.CloudZoom.defaults = {
        zoomWidth: 'auto',
        zoomHeight: 'auto',
        position: 'right',
        tint: false,
        tintOpacity: 0.5,
        lensOpacity: 0.5,
        softFocus: false,
        smoothMove: 3,
        showTitle: false,
        titleOpacity: 0.5,
        adjustX: 0,
        adjustY: 0
    };

})(jQuery);
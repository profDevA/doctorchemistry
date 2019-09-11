/* ===========================================================
 * bootstrap-tooltip.js v2.1.1
 * http://twitter.github.com/bootstrap/javascript.html#tooltips
 * Inspired by the original jQuery.tipsy by Jason Frame
 * ===========================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */


!function ($jq) {

  "use strict"; // jshint ;_;


 /* TOOLTIP PUBLIC CLASS DEFINITION
  * =============================== */

  var Tooltip = function (element, options) {
    this.init('tooltip', element, options)
  }

  Tooltip.prototype = {

    constructor: Tooltip

  , init: function (type, element, options) {
      var eventIn
        , eventOut

      this.type = type
      this.$jqelement = $jq(element)
      this.options = this.getOptions(options)
      this.enabled = true

      if (this.options.trigger == 'click') {
        this.$jqelement.on('click.' + this.type, this.options.selector, $jq.proxy(this.toggle, this))
      } else if (this.options.trigger != 'manual') {
        eventIn = this.options.trigger == 'hover' ? 'mouseenter' : 'focus'
        eventOut = this.options.trigger == 'hover' ? 'mouseleave' : 'blur'
        this.$jqelement.on(eventIn + '.' + this.type, this.options.selector, $jq.proxy(this.enter, this))
        this.$jqelement.on(eventOut + '.' + this.type, this.options.selector, $jq.proxy(this.leave, this))
      }

      this.options.selector ?
        (this._options = $jq.extend({}, this.options, { trigger: 'manual', selector: '' })) :
        this.fixTitle()
    }

  , getOptions: function (options) {
      options = $jq.extend({}, $jq.fn[this.type].defaults, options, this.$jqelement.data())

      if (options.delay && typeof options.delay == 'number') {
        options.delay = {
          show: options.delay
        , hide: options.delay
        }
      }

      return options
    }

  , enter: function (e) {
      var self = $jq(e.currentTarget)[this.type](this._options).data(this.type)

      if (!self.options.delay || !self.options.delay.show) return self.show()

      clearTimeout(this.timeout)
      self.hoverState = 'in'
      this.timeout = setTimeout(function() {
        if (self.hoverState == 'in') self.show()
      }, self.options.delay.show)
    }

  , leave: function (e) {
      var self = $jq(e.currentTarget)[this.type](this._options).data(this.type)

      if (this.timeout) clearTimeout(this.timeout)
      if (!self.options.delay || !self.options.delay.hide) return self.hide()

      self.hoverState = 'out'
      this.timeout = setTimeout(function() {
        if (self.hoverState == 'out') self.hide()
      }, self.options.delay.hide)
    }

  , show: function () {
      var $jqtip
        , inside
        , pos
        , actualWidth
        , actualHeight
        , placement
        , tp

      if (this.hasContent() && this.enabled) {
        $jqtip = this.tip()
        this.setContent()

        if (this.options.animation) {
          $jqtip.addClass('fade')
        }

        placement = typeof this.options.placement == 'function' ?
          this.options.placement.call(this, $jqtip[0], this.$jqelement[0]) :
          this.options.placement

        inside = /in/.test(placement)

        $jqtip
          .remove()
          .css({ top: 0, left: 0, display: 'block' })
          .appendTo(inside ? this.$jqelement : document.body)

        pos = this.getPosition(inside)

        actualWidth = $jqtip[0].offsetWidth
        actualHeight = $jqtip[0].offsetHeight

        switch (inside ? placement.split(' ')[1] : placement) {
          case 'bottom':
            tp = {top: pos.top + pos.height, left: pos.left + pos.width / 2 - actualWidth / 2}
            break
          case 'top':
            tp = {top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2}
            break
          case 'left':
            tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth}
            break
          case 'right':
            tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width}
            break
        }

        $jqtip
          .css(tp)
          .addClass(placement)
          .addClass('in')
      }
    }

  , setContent: function () {
      var $jqtip = this.tip()
        , title = this.getTitle()

      $jqtip.find('.tooltip-inner')[this.options.html ? 'html' : 'text'](title)
      $jqtip.removeClass('fade in top bottom left right')
    }

  , hide: function () {
      var that = this
        , $jqtip = this.tip()

      $jqtip.removeClass('in')

      function removeWithAnimation() {
        var timeout = setTimeout(function () {
          $jqtip.off($jq.support.transition.end).remove()
        }, 500)

        $jqtip.one($jq.support.transition.end, function () {
          clearTimeout(timeout)
          $jqtip.remove()
        })
      }

      $jq.support.transition && this.$jqtip.hasClass('fade') ?
        removeWithAnimation() :
        $jqtip.remove()

      return this
    }

  , fixTitle: function () {
      var $jqe = this.$jqelement
      if ($jqe.attr('title') || typeof($jqe.attr('data-original-title')) != 'string') {
        $jqe.attr('data-original-title', $jqe.attr('title') || '').removeAttr('title')
      }
    }

  , hasContent: function () {
      return this.getTitle()
    }

  , getPosition: function (inside) {
      return $jq.extend({}, (inside ? {top: 0, left: 0} : this.$jqelement.offset()), {
        width: this.$jqelement[0].offsetWidth
      , height: this.$jqelement[0].offsetHeight
      })
    }

  , getTitle: function () {
      var title
        , $jqe = this.$jqelement
        , o = this.options

      title = $jqe.attr('data-original-title')
        || (typeof o.title == 'function' ? o.title.call($jqe[0]) :  o.title)

      return title
    }

  , tip: function () {
      return this.$jqtip = this.$jqtip || $jq(this.options.template)
    }

  , validate: function () {
      if (!this.$jqelement[0].parentNode) {
        this.hide()
        this.$jqelement = null
        this.options = null
      }
    }

  , enable: function () {
      this.enabled = true
    }

  , disable: function () {
      this.enabled = false
    }

  , toggleEnabled: function () {
      this.enabled = !this.enabled
    }

  , toggle: function () {
      this[this.tip().hasClass('in') ? 'hide' : 'show']()
    }

  , destroy: function () {
      this.hide().$jqelement.off('.' + this.type).removeData(this.type)
    }

  }


 /* TOOLTIP PLUGIN DEFINITION
  * ========================= */

  $jq.fn.tooltip = function ( option ) {
    return this.each(function () {
      var $jqthis = $jq(this)
        , data = $jqthis.data('tooltip')
        , options = typeof option == 'object' && option
      if (!data) $jqthis.data('tooltip', (data = new Tooltip(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  $jq.fn.tooltip.Constructor = Tooltip

  $jq.fn.tooltip.defaults = {
    animation: true
  , placement: 'top'
  , selector: false
  , template: '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
  , trigger: 'hover'
  , title: ''
  , delay: 0
  , html: true
  }

}(window.jQuery);

$jq(document).ready(function () {
	 //$jq('.button.btn-cart').attr('data-original-title',$jq('button.btn-cart').attr('title'));
	 $jq('.link-wishlist').attr('data-original-title',$jq('.link-wishlist').attr('title'));
	 $jq('.link-compare').attr('data-original-title',$jq('.link-compare').attr('title'));
	 
	 $jq('.email-friend').attr('data-original-title',$jq('.email-friend').attr('title'));
	 //$jq('.brand_item').attr('data-original-title',$jq('.brand_item').attr('title'));
	 $jq('.link-wishlist, .link-compare,.email-friend,.brand_item').attr('rel', 'tooltip');
	 $jq("[rel=tooltip]").tooltip(); 
  });
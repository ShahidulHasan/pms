/* ==========================================================
 * bootstrap-maxlength.js v1.4.2
 * 
 * Copyright (c) 2013 Maurizio Napoleoni; 
 *
 * Licensed under the terms of the MIT license.
 * See: https://github.com/mimo84/bootstrap-maxlength/blob/master/LICENSE
 * ========================================================== */
(function (e) {
    e.fn.extend({maxlength: function (d, p) {
        function l(b) {
            var c = b.val().match(/\n/g), c = c ? c.length : 0;
            return b.val().length + c
        }

        function q(b, c) {
            var a = "";
            d.preText && (a += d.preText);
            a = d.showCharsTyped ? a + c : a + (b - l(c));
            d.showMaxLength && (a += d.separator + b);
            d.postText && (a += d.postText);
            return a
        }

        function r(b, c, a, f) {
            f.html(q(a, a - b));
            if (0 < b) {
                b = d.threshold;
                var g = !0;
                !d.alwaysShow && a - l(c) > b && (g = !1);
                g ? f.removeClass(d.limitReachedClass).addClass(d.warningClass).css({display: "block"}) : f.css({display: "none"})
            } else f.removeClass(d.warningClass).addClass(d.limitReachedClass).css({display: "block"})
        }

        function m(b, c) {
            var a;
            a = b[0];
            a = e.extend({}, "function" === typeof a.getBoundingClientRect ? a.getBoundingClientRect() : {width: a.offsetWidth, height: a.offsetHeight}, b.offset());
            var f = b.outerWidth(), g = c.outerWidth(), n = c.width(), h = c.height();
            switch (d.placement) {
                case "bottom":
                    c.css({top: a.top + a.height, left: a.left + a.width / 2 - n / 2});
                    break;
                case "top":
                    c.css({top: a.top - h, left: a.left + a.width / 2 - n / 2});
                    break;
                case "left":
                    c.css({top: a.top + a.height / 2 - h / 2, left: a.left - n});
                    break;
                case "right":
                    c.css({top: a.top + a.height / 2 - h / 2,
                        left: a.left + a.width});
                    break;
                case "bottom-right":
                    c.css({top: a.top + a.height, left: a.left + a.width});
                    break;
                case "top-right":
                    c.css({top: a.top - h, left: a.left + f});
                    break;
                case "top-left":
                    c.css({top: a.top - h, left: a.left - g});
                    break;
                case "bottom-left":
                    c.css({top: a.top + b.outerHeight(), left: a.left - g});
                    break;
                case "centered-right":
                    c.css({top: a.top + h / 2, left: a.left + f - g - 3})
            }
        }

        function k(b) {
            return b.attr("maxlength") || b.attr("size")
        }

        var s = e("body");
        e.isFunction(d) && !p && (p = d, d = {});
        d = e.extend({alwaysShow: !1, threshold: 10, warningClass: "label label-success",
            limitReachedClass: "label label-important", separator: " / ", preText: "", postText: "", showMaxLength: !0, placement: "bottom", showCharsTyped: !0, validate: !1}, d);
        return this.each(function () {
            var b = e(this), c = k(b), a = e("<span></span>").css({display: "none", position: "absolute", whiteSpace: "nowrap", zIndex: 1099}).html(q(c, "0"));
            b.is("textarea") && (b.data("maxlenghtsizex", b.outerWidth()), b.data("maxlenghtsizey", b.outerHeight()), b.mouseup(function () {
                b.outerWidth() === b.data("maxlenghtsizex") && b.outerHeight() === b.data("maxlenghtsizey") ||
                m(b, a);
                b.data("maxlenghtsizex", b.outerWidth());
                b.data("maxlenghtsizey", b.outerHeight())
            }));
            s.append(a);
            b.focus(function () {
                var d;
                d = k(b) - l(b);
                r(d, b, c, a);
                m(b, a)
            });
            e(window).resize(function () {
                m(b, a)
            });
            b.blur(function () {
                a.css("display", "none")
            });
            b.keyup(function (f) {
                var g;
                g = k(b) - l(b);
                var e = !0, h = f.keyCode || f.which;
                0 !== g || (9 !== h || f.shiftKey) || b.attr("maxlength", k(b) + 1).trigger({type: "keypress", which: 9}).attr("maxlength", k(b) - 1);
                d.validate && 0 > g ? e = !1 : r(g, b, c, a);
                return e
            })
        })
    }})
})(jQuery);
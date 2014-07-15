/*
 Copyright 2012 Igor Vaynberg
 Modifed by keenthemes for Metronic theme integration.

 Version: 3.4.3 Timestamp: Tue Sep 17 06:47:14 PDT 2013

 This software is licensed under the Apache License, Version 2.0 (the "Apache License") or the GNU
 General Public License version 2 (the "GPL License"). You may choose either license to govern your
 use of this software only upon the condition that you accept all of the terms of either the Apache
 License or the GPL License.

 You may obtain a copy of the Apache License and the GPL License at:

 http://www.apache.org/licenses/LICENSE-2.0
 http://www.gnu.org/licenses/gpl-2.0.html

 Unless required by applicable law or agreed to in writing, software distributed under the Apache License
 or the GPL Licesnse is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
 either express or implied. See the Apache License and the GPL License for the specific language governing
 permissions and limitations under the Apache License and the GPL License.
 */
!function (e) {
    "undefined" == typeof e.fn.each2 && e.extend(e.fn, {each2: function (t) {
        for (var i = e([0]), n = -1, a = this.length; ++n < a && (i.context = i[0] = this[n]) && t.call(i[0], n, i) !== !1;);
        return this
    }})
}(jQuery), function (e, t) {
    "use strict";
    function i(e) {
        var t, i, n, a;
        if (!e || e.length < 1)return e;
        for (t = "", i = 0, n = e.length; n > i; i++)a = e.charAt(i), t += B[a] || a;
        return t
    }

    function n(e, t) {
        for (var i = 0, n = t.length; n > i; i += 1)if (r(e, t[i]))return i;
        return-1
    }

    function a() {
        var t = e(H);
        t.appendTo("body");
        var i = {width: t.width() - t[0].clientWidth, height: t.height() - t[0].clientHeight};
        return t.remove(), i
    }

    function r(e, i) {
        return e === i ? !0 : e === t || i === t ? !1 : null === e || null === i ? !1 : e.constructor === String ? e + "" == i + "" : i.constructor === String ? i + "" == e + "" : !1
    }

    function o(t, i) {
        var n, a, r;
        if (null === t || t.length < 1)return[];
        for (n = t.split(i), a = 0, r = n.length; r > a; a += 1)n[a] = e.trim(n[a]);
        return n
    }

    function s(e) {
        return e.outerWidth(!1) - e.width()
    }

    function l(i) {
        var n = "keyup-change-value";
        i.on("keydown", function () {
            e.data(i, n) === t && e.data(i, n, i.val())
        }), i.on("keyup", function () {
            var a = e.data(i, n);
            a !== t && i.val() !== a && (e.removeData(i, n), i.trigger("keyup-change"))
        })
    }

    function d(i) {
        i.on("mousemove", function (i) {
            var n = O;
            (n === t || n.x !== i.pageX || n.y !== i.pageY) && e(i.target).trigger("mousemove-filtered", i)
        })
    }

    function h(e, i, n) {
        n = n || t;
        var a;
        return function () {
            var t = arguments;
            window.clearTimeout(a), a = window.setTimeout(function () {
                i.apply(n, t)
            }, e)
        }
    }

    function c(e) {
        var t, i = !1;
        return function () {
            return i === !1 && (t = e(), i = !0), t
        }
    }

    function u(e, t) {
        var i = h(e, function (e) {
            t.trigger("scroll-debounced", e)
        });
        t.on("scroll", function (e) {
            n(e.target, t.get()) >= 0 && i(e)
        })
    }

    function p(e) {
        e[0] !== document.activeElement && window.setTimeout(function () {
            var t, i = e[0], n = e.val().length;
            e.focus(), e.is(":visible") && i === document.activeElement && (i.setSelectionRange ? i.setSelectionRange(n, n) : i.createTextRange && (t = i.createTextRange(), t.collapse(!1), t.select()))
        }, 0)
    }

    function g(t) {
        t = e(t)[0];
        var i = 0, n = 0;
        if ("selectionStart"in t)i = t.selectionStart, n = t.selectionEnd - i; else if ("selection"in document) {
            t.focus();
            var a = document.selection.createRange();
            n = document.selection.createRange().text.length, a.moveStart("character", -t.value.length), i = a.text.length - n
        }
        return{offset: i, length: n}
    }

    function f(e) {
        e.preventDefault(), e.stopPropagation()
    }

    function m(e) {
        e.preventDefault(), e.stopImmediatePropagation()
    }

    function v(t) {
        if (!j) {
            var i = t[0].currentStyle || window.getComputedStyle(t[0], null);
            j = e(document.createElement("div")).css({position: "absolute", left: "-10000px", top: "-10000px", display: "none", fontSize: i.fontSize, fontFamily: i.fontFamily, fontStyle: i.fontStyle, fontWeight: i.fontWeight, letterSpacing: i.letterSpacing, textTransform: i.textTransform, whiteSpace: "nowrap"}), j.attr("class", "select2-sizer"), e("body").append(j)
        }
        return j.text(t.val()), j.width()
    }

    function y(t, i, n) {
        var a, r, o = [];
        a = t.attr("class"), a && (a = "" + a, e(a.split(" ")).each2(function () {
            0 === this.indexOf("select2-") && o.push(this)
        })), a = i.attr("class"), a && (a = "" + a, e(a.split(" ")).each2(function () {
            0 !== this.indexOf("select2-") && (r = n(this), r && o.push(this))
        })), t.attr("class", o.join(" "))
    }

    function b(e, t, n, a) {
        var r = i(e.toUpperCase()).indexOf(i(t.toUpperCase())), o = t.length;
        return 0 > r ? (n.push(a(e)), void 0) : (n.push(a(e.substring(0, r))), n.push("<span class='select2-match'>"), n.push(a(e.substring(r, r + o))), n.push("</span>"), n.push(a(e.substring(r + o, e.length))), void 0)
    }

    function x(e) {
        var t = {"\\": "&#92;", "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;", "/": "&#47;"};
        return String(e).replace(/[&<>"'\/\\]/g, function (e) {
            return t[e]
        })
    }

    function w(i) {
        var n, a = null, r = i.quietMillis || 100, o = i.url, s = this;
        return function (l) {
            window.clearTimeout(n), n = window.setTimeout(function () {
                var n = i.data, r = o, d = i.transport || e.fn.select2.ajaxDefaults.transport, h = {type: i.type || "GET", cache: i.cache || !1, jsonpCallback: i.jsonpCallback || t, dataType: i.dataType || "json"}, c = e.extend({}, e.fn.select2.ajaxDefaults.params, h);
                n = n ? n.call(s, l.term, l.page, l.context) : null, r = "function" == typeof r ? r.call(s, l.term, l.page, l.context) : r, a && a.abort(), i.params && (e.isFunction(i.params) ? e.extend(c, i.params.call(s)) : e.extend(c, i.params)), e.extend(c, {url: r, dataType: i.dataType, data: n, success: function (e) {
                    var t = i.results(e, l.page);
                    l.callback(t)
                }}), a = d.call(s, c)
            }, r)
        }
    }

    function C(t) {
        var i, n, a = t, r = function (e) {
            return"" + e.text
        };
        e.isArray(a) && (n = a, a = {results: n}), e.isFunction(a) === !1 && (n = a, a = function () {
            return n
        });
        var o = a();
        return o.text && (r = o.text, e.isFunction(r) || (i = o.text, r = function (e) {
            return e[i]
        })), function (t) {
            var i, n = t.term, o = {results: []};
            return"" === n ? (t.callback(a()), void 0) : (i = function (a, o) {
                var s, l;
                if (a = a[0], a.children) {
                    s = {};
                    for (l in a)a.hasOwnProperty(l) && (s[l] = a[l]);
                    s.children = [], e(a.children).each2(function (e, t) {
                        i(t, s.children)
                    }), (s.children.length || t.matcher(n, r(s), a)) && o.push(s)
                } else t.matcher(n, r(a), a) && o.push(a)
            }, e(a().results).each2(function (e, t) {
                i(t, o.results)
            }), t.callback(o), void 0)
        }
    }

    function _(i) {
        var n = e.isFunction(i);
        return function (a) {
            var r = a.term, o = {results: []};
            e(n ? i() : i).each(function () {
                var e = this.text !== t, i = e ? this.text : this;
                ("" === r || a.matcher(r, i)) && o.results.push(e ? this : {id: this, text: this})
            }), a.callback(o)
        }
    }

    function T(t, i) {
        if (e.isFunction(t))return!0;
        if (!t)return!1;
        throw new Error(i + " must be a function or a falsy value")
    }

    function k(t) {
        return e.isFunction(t) ? t() : t
    }

    function M(t) {
        var i = 0;
        return e.each(t, function (e, t) {
            t.children ? i += M(t.children) : i++
        }), i
    }

    function S(e, i, n, a) {
        var o, s, l, d, h, c = e, u = !1;
        if (!a.createSearchChoice || !a.tokenSeparators || a.tokenSeparators.length < 1)return t;
        for (; ;) {
            for (s = -1, l = 0, d = a.tokenSeparators.length; d > l && (h = a.tokenSeparators[l], s = e.indexOf(h), !(s >= 0)); l++);
            if (0 > s)break;
            if (o = e.substring(0, s), e = e.substring(s + h.length), o.length > 0 && (o = a.createSearchChoice.call(this, o, i), o !== t && null !== o && a.id(o) !== t && null !== a.id(o))) {
                for (u = !1, l = 0, d = i.length; d > l; l++)if (r(a.id(o), a.id(i[l]))) {
                    u = !0;
                    break
                }
                u || n(o)
            }
        }
        return c !== e ? e : void 0
    }

    function E(t, i) {
        var n = function () {
        };
        return n.prototype = new t, n.prototype.constructor = n, n.prototype.parent = t.prototype, n.prototype = e.extend(n.prototype, i), n
    }

    if (window.Select2 === t) {
        var A, I, R, N, D, j, P, L, O = {x: 0, y: 0}, A = {TAB: 9, ENTER: 13, ESC: 27, SPACE: 32, LEFT: 37, UP: 38, RIGHT: 39, DOWN: 40, SHIFT: 16, CTRL: 17, ALT: 18, PAGE_UP: 33, PAGE_DOWN: 34, HOME: 36, END: 35, BACKSPACE: 8, DELETE: 46, isArrow: function (e) {
            switch (e = e.which ? e.which : e) {
                case A.LEFT:
                case A.RIGHT:
                case A.UP:
                case A.DOWN:
                    return!0
            }
            return!1
        }, isControl: function (e) {
            var t = e.which;
            switch (t) {
                case A.SHIFT:
                case A.CTRL:
                case A.ALT:
                    return!0
            }
            return e.metaKey ? !0 : !1
        }, isFunctionKey: function (e) {
            return e = e.which ? e.which : e, e >= 112 && 123 >= e
        }}, H = "<div class='select2-measure-scrollbar'></div>", B = {"Ⓐ": "A", "Ａ": "A", "À": "A", "Á": "A", "Â": "A", "Ầ": "A", "Ấ": "A", "Ẫ": "A", "Ẩ": "A", "Ã": "A", "Ā": "A", "Ă": "A", "Ằ": "A", "Ắ": "A", "Ẵ": "A", "Ẳ": "A", "Ȧ": "A", "Ǡ": "A", "Ä": "A", "Ǟ": "A", "Ả": "A", "Å": "A", "Ǻ": "A", "Ǎ": "A", "Ȁ": "A", "Ȃ": "A", "Ạ": "A", "Ậ": "A", "Ặ": "A", "Ḁ": "A", "Ą": "A", "Ⱥ": "A", "Ɐ": "A", "Ꜳ": "AA", "Æ": "AE", "Ǽ": "AE", "Ǣ": "AE", "Ꜵ": "AO", "Ꜷ": "AU", "Ꜹ": "AV", "Ꜻ": "AV", "Ꜽ": "AY", "Ⓑ": "B", "Ｂ": "B", "Ḃ": "B", "Ḅ": "B", "Ḇ": "B", "Ƀ": "B", "Ƃ": "B", "Ɓ": "B", "Ⓒ": "C", "Ｃ": "C", "Ć": "C", "Ĉ": "C", "Ċ": "C", "Č": "C", "Ç": "C", "Ḉ": "C", "Ƈ": "C", "Ȼ": "C", "Ꜿ": "C", "Ⓓ": "D", "Ｄ": "D", "Ḋ": "D", "Ď": "D", "Ḍ": "D", "Ḑ": "D", "Ḓ": "D", "Ḏ": "D", "Đ": "D", "Ƌ": "D", "Ɗ": "D", "Ɖ": "D", "Ꝺ": "D", "Ǳ": "DZ", "Ǆ": "DZ", "ǲ": "Dz", "ǅ": "Dz", "Ⓔ": "E", "Ｅ": "E", "È": "E", "É": "E", "Ê": "E", "Ề": "E", "Ế": "E", "Ễ": "E", "Ể": "E", "Ẽ": "E", "Ē": "E", "Ḕ": "E", "Ḗ": "E", "Ĕ": "E", "Ė": "E", "Ë": "E", "Ẻ": "E", "Ě": "E", "Ȅ": "E", "Ȇ": "E", "Ẹ": "E", "Ệ": "E", "Ȩ": "E", "Ḝ": "E", "Ę": "E", "Ḙ": "E", "Ḛ": "E", "Ɛ": "E", "Ǝ": "E", "Ⓕ": "F", "Ｆ": "F", "Ḟ": "F", "Ƒ": "F", "Ꝼ": "F", "Ⓖ": "G", "Ｇ": "G", "Ǵ": "G", "Ĝ": "G", "Ḡ": "G", "Ğ": "G", "Ġ": "G", "Ǧ": "G", "Ģ": "G", "Ǥ": "G", "Ɠ": "G", "Ꞡ": "G", "Ᵹ": "G", "Ꝿ": "G", "Ⓗ": "H", "Ｈ": "H", "Ĥ": "H", "Ḣ": "H", "Ḧ": "H", "Ȟ": "H", "Ḥ": "H", "Ḩ": "H", "Ḫ": "H", "Ħ": "H", "Ⱨ": "H", "Ⱶ": "H", "Ɥ": "H", "Ⓘ": "I", "Ｉ": "I", "Ì": "I", "Í": "I", "Î": "I", "Ĩ": "I", "Ī": "I", "Ĭ": "I", "İ": "I", "Ï": "I", "Ḯ": "I", "Ỉ": "I", "Ǐ": "I", "Ȉ": "I", "Ȋ": "I", "Ị": "I", "Į": "I", "Ḭ": "I", "Ɨ": "I", "Ⓙ": "J", "Ｊ": "J", "Ĵ": "J", "Ɉ": "J", "Ⓚ": "K", "Ｋ": "K", "Ḱ": "K", "Ǩ": "K", "Ḳ": "K", "Ķ": "K", "Ḵ": "K", "Ƙ": "K", "Ⱪ": "K", "Ꝁ": "K", "Ꝃ": "K", "Ꝅ": "K", "Ꞣ": "K", "Ⓛ": "L", "Ｌ": "L", "Ŀ": "L", "Ĺ": "L", "Ľ": "L", "Ḷ": "L", "Ḹ": "L", "Ļ": "L", "Ḽ": "L", "Ḻ": "L", "Ł": "L", "Ƚ": "L", "Ɫ": "L", "Ⱡ": "L", "Ꝉ": "L", "Ꝇ": "L", "Ꞁ": "L", "Ǉ": "LJ", "ǈ": "Lj", "Ⓜ": "M", "Ｍ": "M", "Ḿ": "M", "Ṁ": "M", "Ṃ": "M", "Ɱ": "M", "Ɯ": "M", "Ⓝ": "N", "Ｎ": "N", "Ǹ": "N", "Ń": "N", "Ñ": "N", "Ṅ": "N", "Ň": "N", "Ṇ": "N", "Ņ": "N", "Ṋ": "N", "Ṉ": "N", "Ƞ": "N", "Ɲ": "N", "Ꞑ": "N", "Ꞥ": "N", "Ǌ": "NJ", "ǋ": "Nj", "Ⓞ": "O", "Ｏ": "O", "Ò": "O", "Ó": "O", "Ô": "O", "Ồ": "O", "Ố": "O", "Ỗ": "O", "Ổ": "O", "Õ": "O", "Ṍ": "O", "Ȭ": "O", "Ṏ": "O", "Ō": "O", "Ṑ": "O", "Ṓ": "O", "Ŏ": "O", "Ȯ": "O", "Ȱ": "O", "Ö": "O", "Ȫ": "O", "Ỏ": "O", "Ő": "O", "Ǒ": "O", "Ȍ": "O", "Ȏ": "O", "Ơ": "O", "Ờ": "O", "Ớ": "O", "Ỡ": "O", "Ở": "O", "Ợ": "O", "Ọ": "O", "Ộ": "O", "Ǫ": "O", "Ǭ": "O", "Ø": "O", "Ǿ": "O", "Ɔ": "O", "Ɵ": "O", "Ꝋ": "O", "Ꝍ": "O", "Ƣ": "OI", "Ꝏ": "OO", "Ȣ": "OU", "Ⓟ": "P", "Ｐ": "P", "Ṕ": "P", "Ṗ": "P", "Ƥ": "P", "Ᵽ": "P", "Ꝑ": "P", "Ꝓ": "P", "Ꝕ": "P", "Ⓠ": "Q", "Ｑ": "Q", "Ꝗ": "Q", "Ꝙ": "Q", "Ɋ": "Q", "Ⓡ": "R", "Ｒ": "R", "Ŕ": "R", "Ṙ": "R", "Ř": "R", "Ȑ": "R", "Ȓ": "R", "Ṛ": "R", "Ṝ": "R", "Ŗ": "R", "Ṟ": "R", "Ɍ": "R", "Ɽ": "R", "Ꝛ": "R", "Ꞧ": "R", "Ꞃ": "R", "Ⓢ": "S", "Ｓ": "S", "ẞ": "S", "Ś": "S", "Ṥ": "S", "Ŝ": "S", "Ṡ": "S", "Š": "S", "Ṧ": "S", "Ṣ": "S", "Ṩ": "S", "Ș": "S", "Ş": "S", "Ȿ": "S", "Ꞩ": "S", "Ꞅ": "S", "Ⓣ": "T", "Ｔ": "T", "Ṫ": "T", "Ť": "T", "Ṭ": "T", "Ț": "T", "Ţ": "T", "Ṱ": "T", "Ṯ": "T", "Ŧ": "T", "Ƭ": "T", "Ʈ": "T", "Ⱦ": "T", "Ꞇ": "T", "Ꜩ": "TZ", "Ⓤ": "U", "Ｕ": "U", "Ù": "U", "Ú": "U", "Û": "U", "Ũ": "U", "Ṹ": "U", "Ū": "U", "Ṻ": "U", "Ŭ": "U", "Ü": "U", "Ǜ": "U", "Ǘ": "U", "Ǖ": "U", "Ǚ": "U", "Ủ": "U", "Ů": "U", "Ű": "U", "Ǔ": "U", "Ȕ": "U", "Ȗ": "U", "Ư": "U", "Ừ": "U", "Ứ": "U", "Ữ": "U", "Ử": "U", "Ự": "U", "Ụ": "U", "Ṳ": "U", "Ų": "U", "Ṷ": "U", "Ṵ": "U", "Ʉ": "U", "Ⓥ": "V", "Ｖ": "V", "Ṽ": "V", "Ṿ": "V", "Ʋ": "V", "Ꝟ": "V", "Ʌ": "V", "Ꝡ": "VY", "Ⓦ": "W", "Ｗ": "W", "Ẁ": "W", "Ẃ": "W", "Ŵ": "W", "Ẇ": "W", "Ẅ": "W", "Ẉ": "W", "Ⱳ": "W", "Ⓧ": "X", "Ｘ": "X", "Ẋ": "X", "Ẍ": "X", "Ⓨ": "Y", "Ｙ": "Y", "Ỳ": "Y", "Ý": "Y", "Ŷ": "Y", "Ỹ": "Y", "Ȳ": "Y", "Ẏ": "Y", "Ÿ": "Y", "Ỷ": "Y", "Ỵ": "Y", "Ƴ": "Y", "Ɏ": "Y", "Ỿ": "Y", "Ⓩ": "Z", "Ｚ": "Z", "Ź": "Z", "Ẑ": "Z", "Ż": "Z", "Ž": "Z", "Ẓ": "Z", "Ẕ": "Z", "Ƶ": "Z", "Ȥ": "Z", "Ɀ": "Z", "Ⱬ": "Z", "Ꝣ": "Z", "ⓐ": "a", "ａ": "a", "ẚ": "a", "à": "a", "á": "a", "â": "a", "ầ": "a", "ấ": "a", "ẫ": "a", "ẩ": "a", "ã": "a", "ā": "a", "ă": "a", "ằ": "a", "ắ": "a", "ẵ": "a", "ẳ": "a", "ȧ": "a", "ǡ": "a", "ä": "a", "ǟ": "a", "ả": "a", "å": "a", "ǻ": "a", "ǎ": "a", "ȁ": "a", "ȃ": "a", "ạ": "a", "ậ": "a", "ặ": "a", "ḁ": "a", "ą": "a", "ⱥ": "a", "ɐ": "a", "ꜳ": "aa", "æ": "ae", "ǽ": "ae", "ǣ": "ae", "ꜵ": "ao", "ꜷ": "au", "ꜹ": "av", "ꜻ": "av", "ꜽ": "ay", "ⓑ": "b", "ｂ": "b", "ḃ": "b", "ḅ": "b", "ḇ": "b", "ƀ": "b", "ƃ": "b", "ɓ": "b", "ⓒ": "c", "ｃ": "c", "ć": "c", "ĉ": "c", "ċ": "c", "č": "c", "ç": "c", "ḉ": "c", "ƈ": "c", "ȼ": "c", "ꜿ": "c", "ↄ": "c", "ⓓ": "d", "ｄ": "d", "ḋ": "d", "ď": "d", "ḍ": "d", "ḑ": "d", "ḓ": "d", "ḏ": "d", "đ": "d", "ƌ": "d", "ɖ": "d", "ɗ": "d", "ꝺ": "d", "ǳ": "dz", "ǆ": "dz", "ⓔ": "e", "ｅ": "e", "è": "e", "é": "e", "ê": "e", "ề": "e", "ế": "e", "ễ": "e", "ể": "e", "ẽ": "e", "ē": "e", "ḕ": "e", "ḗ": "e", "ĕ": "e", "ė": "e", "ë": "e", "ẻ": "e", "ě": "e", "ȅ": "e", "ȇ": "e", "ẹ": "e", "ệ": "e", "ȩ": "e", "ḝ": "e", "ę": "e", "ḙ": "e", "ḛ": "e", "ɇ": "e", "ɛ": "e", "ǝ": "e", "ⓕ": "f", "ｆ": "f", "ḟ": "f", "ƒ": "f", "ꝼ": "f", "ⓖ": "g", "ｇ": "g", "ǵ": "g", "ĝ": "g", "ḡ": "g", "ğ": "g", "ġ": "g", "ǧ": "g", "ģ": "g", "ǥ": "g", "ɠ": "g", "ꞡ": "g", "ᵹ": "g", "ꝿ": "g", "ⓗ": "h", "ｈ": "h", "ĥ": "h", "ḣ": "h", "ḧ": "h", "ȟ": "h", "ḥ": "h", "ḩ": "h", "ḫ": "h", "ẖ": "h", "ħ": "h", "ⱨ": "h", "ⱶ": "h", "ɥ": "h", "ƕ": "hv", "ⓘ": "i", "ｉ": "i", "ì": "i", "í": "i", "î": "i", "ĩ": "i", "ī": "i", "ĭ": "i", "ï": "i", "ḯ": "i", "ỉ": "i", "ǐ": "i", "ȉ": "i", "ȋ": "i", "ị": "i", "į": "i", "ḭ": "i", "ɨ": "i", "ı": "i", "ⓙ": "j", "ｊ": "j", "ĵ": "j", "ǰ": "j", "ɉ": "j", "ⓚ": "k", "ｋ": "k", "ḱ": "k", "ǩ": "k", "ḳ": "k", "ķ": "k", "ḵ": "k", "ƙ": "k", "ⱪ": "k", "ꝁ": "k", "ꝃ": "k", "ꝅ": "k", "ꞣ": "k", "ⓛ": "l", "ｌ": "l", "ŀ": "l", "ĺ": "l", "ľ": "l", "ḷ": "l", "ḹ": "l", "ļ": "l", "ḽ": "l", "ḻ": "l", "ſ": "l", "ł": "l", "ƚ": "l", "ɫ": "l", "ⱡ": "l", "ꝉ": "l", "ꞁ": "l", "ꝇ": "l", "ǉ": "lj", "ⓜ": "m", "ｍ": "m", "ḿ": "m", "ṁ": "m", "ṃ": "m", "ɱ": "m", "ɯ": "m", "ⓝ": "n", "ｎ": "n", "ǹ": "n", "ń": "n", "ñ": "n", "ṅ": "n", "ň": "n", "ṇ": "n", "ņ": "n", "ṋ": "n", "ṉ": "n", "ƞ": "n", "ɲ": "n", "ŉ": "n", "ꞑ": "n", "ꞥ": "n", "ǌ": "nj", "ⓞ": "o", "ｏ": "o", "ò": "o", "ó": "o", "ô": "o", "ồ": "o", "ố": "o", "ỗ": "o", "ổ": "o", "õ": "o", "ṍ": "o", "ȭ": "o", "ṏ": "o", "ō": "o", "ṑ": "o", "ṓ": "o", "ŏ": "o", "ȯ": "o", "ȱ": "o", "ö": "o", "ȫ": "o", "ỏ": "o", "ő": "o", "ǒ": "o", "ȍ": "o", "ȏ": "o", "ơ": "o", "ờ": "o", "ớ": "o", "ỡ": "o", "ở": "o", "ợ": "o", "ọ": "o", "ộ": "o", "ǫ": "o", "ǭ": "o", "ø": "o", "ǿ": "o", "ɔ": "o", "ꝋ": "o", "ꝍ": "o", "ɵ": "o", "ƣ": "oi", "ȣ": "ou", "ꝏ": "oo", "ⓟ": "p", "ｐ": "p", "ṕ": "p", "ṗ": "p", "ƥ": "p", "ᵽ": "p", "ꝑ": "p", "ꝓ": "p", "ꝕ": "p", "ⓠ": "q", "ｑ": "q", "ɋ": "q", "ꝗ": "q", "ꝙ": "q", "ⓡ": "r", "ｒ": "r", "ŕ": "r", "ṙ": "r", "ř": "r", "ȑ": "r", "ȓ": "r", "ṛ": "r", "ṝ": "r", "ŗ": "r", "ṟ": "r", "ɍ": "r", "ɽ": "r", "ꝛ": "r", "ꞧ": "r", "ꞃ": "r", "ⓢ": "s", "ｓ": "s", "ß": "s", "ś": "s", "ṥ": "s", "ŝ": "s", "ṡ": "s", "š": "s", "ṧ": "s", "ṣ": "s", "ṩ": "s", "ș": "s", "ş": "s", "ȿ": "s", "ꞩ": "s", "ꞅ": "s", "ẛ": "s", "ⓣ": "t", "ｔ": "t", "ṫ": "t", "ẗ": "t", "ť": "t", "ṭ": "t", "ț": "t", "ţ": "t", "ṱ": "t", "ṯ": "t", "ŧ": "t", "ƭ": "t", "ʈ": "t", "ⱦ": "t", "ꞇ": "t", "ꜩ": "tz", "ⓤ": "u", "ｕ": "u", "ù": "u", "ú": "u", "û": "u", "ũ": "u", "ṹ": "u", "ū": "u", "ṻ": "u", "ŭ": "u", "ü": "u", "ǜ": "u", "ǘ": "u", "ǖ": "u", "ǚ": "u", "ủ": "u", "ů": "u", "ű": "u", "ǔ": "u", "ȕ": "u", "ȗ": "u", "ư": "u", "ừ": "u", "ứ": "u", "ữ": "u", "ử": "u", "ự": "u", "ụ": "u", "ṳ": "u", "ų": "u", "ṷ": "u", "ṵ": "u", "ʉ": "u", "ⓥ": "v", "ｖ": "v", "ṽ": "v", "ṿ": "v", "ʋ": "v", "ꝟ": "v", "ʌ": "v", "ꝡ": "vy", "ⓦ": "w", "ｗ": "w", "ẁ": "w", "ẃ": "w", "ŵ": "w", "ẇ": "w", "ẅ": "w", "ẘ": "w", "ẉ": "w", "ⱳ": "w", "ⓧ": "x", "ｘ": "x", "ẋ": "x", "ẍ": "x", "ⓨ": "y", "ｙ": "y", "ỳ": "y", "ý": "y", "ŷ": "y", "ỹ": "y", "ȳ": "y", "ẏ": "y", "ÿ": "y", "ỷ": "y", "ẙ": "y", "ỵ": "y", "ƴ": "y", "ɏ": "y", "ỿ": "y", "ⓩ": "z", "ｚ": "z", "ź": "z", "ẑ": "z", "ż": "z", "ž": "z", "ẓ": "z", "ẕ": "z", "ƶ": "z", "ȥ": "z", "ɀ": "z", "ⱬ": "z", "ꝣ": "z"};
        P = e(document), D = function () {
            var e = 1;
            return function () {
                return e++
            }
        }(), P.on("mousemove", function (e) {
            O.x = e.pageX, O.y = e.pageY
        }), I = E(Object, {bind: function (e) {
            var t = this;
            return function () {
                e.apply(t, arguments)
            }
        }, init: function (i) {
            var n, r, o, s, h = ".select2-results";
            this.opts = i = this.prepareOpts(i), this.id = i.id, i.element.data("select2") !== t && null !== i.element.data("select2") && i.element.data("select2").destroy(), this.container = this.createContainer(), this.containerId = "s2id_" + (i.element.attr("id") || "autogen" + D()), this.containerSelector = "#" + this.containerId.replace(/([;&,\.\+\*\~':"\!\^#$%@\[\]\(\)=>\|])/g, "\\$1"), this.container.attr("id", this.containerId), this.body = c(function () {
                return i.element.closest("body")
            }), y(this.container, this.opts.element, this.opts.adaptContainerCssClass), this.container.attr("style", i.element.attr("style")), this.container.css(k(i.containerCss)), this.container.addClass(k(i.containerCssClass)), this.elementTabIndex = this.opts.element.attr("tabindex"), this.opts.element.data("select2", this).attr("tabindex", "-1").before(this.container).on("click.select2", f), this.container.data("select2", this), this.dropdown = this.container.find(".select2-drop"), y(this.dropdown, this.opts.element, this.opts.adaptDropdownCssClass), this.dropdown.addClass(k(i.dropdownCssClass)), this.dropdown.data("select2", this), this.dropdown.on("click", f), this.results = n = this.container.find(h), this.search = r = this.container.find("input.select2-input"), this.queryCount = 0, this.resultsPage = 0, this.context = null, this.initContainer(), this.container.on("click", f), d(this.results), this.dropdown.on("mousemove-filtered touchstart touchmove touchend", h, this.bind(this.highlightUnderEvent)), u(80, this.results), this.dropdown.on("scroll-debounced", h, this.bind(this.loadMoreIfNeeded)), e(this.container).on("change", ".select2-input", function (e) {
                e.stopPropagation()
            }), e(this.dropdown).on("change", ".select2-input", function (e) {
                e.stopPropagation()
            }), e.fn.mousewheel && n.mousewheel(function (e, t, i, a) {
                var r = n.scrollTop();
                a > 0 && 0 >= r - a ? (n.scrollTop(0), f(e)) : 0 > a && n.get(0).scrollHeight - n.scrollTop() + a <= n.height() && (n.scrollTop(n.get(0).scrollHeight - n.height()), f(e))
            }), l(r), r.on("keyup-change input paste", this.bind(this.updateResults)), r.on("focus", function () {
                r.addClass("select2-focused")
            }), r.on("blur", function () {
                r.removeClass("select2-focused")
            }), this.dropdown.on("mouseup", h, this.bind(function (t) {
                e(t.target).closest(".select2-result-selectable").length > 0 && (this.highlightUnderEvent(t), this.selectHighlighted(t))
            })), this.dropdown.on("click mouseup mousedown", function (e) {
                e.stopPropagation()
            }), e.isFunction(this.opts.initSelection) && (this.initSelection(), this.monitorSource()), null !== i.maximumInputLength && this.search.attr("maxlength", i.maximumInputLength);
            var o = i.element.prop("disabled");
            o === t && (o = !1), this.enable(!o);
            var s = i.element.prop("readonly");
            s === t && (s = !1), this.readonly(s), L = L || a(), this.autofocus = i.element.prop("autofocus"), i.element.prop("autofocus", !1), this.autofocus && this.focus(), this.nextSearchTerm = t
        }, destroy: function () {
            var e = this.opts.element, i = e.data("select2");
            this.close(), this.propertyObserver && (delete this.propertyObserver, this.propertyObserver = null), i !== t && (i.container.remove(), i.dropdown.remove(), e.removeClass("select2-offscreen").removeData("select2").off(".select2").prop("autofocus", this.autofocus || !1), this.elementTabIndex ? e.attr({tabindex: this.elementTabIndex}) : e.removeAttr("tabindex"), e.show())
        }, optionToData: function (e) {
            return e.is("option") ? {id: e.prop("value"), text: e.text(), element: e.get(), css: e.attr("class"), disabled: e.prop("disabled"), locked: r(e.attr("locked"), "locked") || r(e.data("locked"), !0)} : e.is("optgroup") ? {text: e.attr("label"), children: [], element: e.get(), css: e.attr("class")} : void 0
        }, prepareOpts: function (i) {
            var n, a, s, l, d = this;
            if (n = i.element, "select" === n.get(0).tagName.toLowerCase() && (this.select = a = i.element), a && e.each(["id", "multiple", "ajax", "query", "createSearchChoice", "initSelection", "data", "tags"], function () {
                if (this in i)throw new Error("Option '" + this + "' is not allowed for Select2 when attached to a <select> element.")
            }), i = e.extend({}, {populateResults: function (n, a, r) {
                var o, s = this.opts.id;
                o = function (n, a, l) {
                    var h, c, u, p, g, f, m, v, y, b;
                    for (n = i.sortResults(n, a, r), h = 0, c = n.length; c > h; h += 1)u = n[h], g = u.disabled === !0, p = !g && s(u) !== t, f = u.children && u.children.length > 0, m = e("<li></li>"), m.addClass("select2-results-dept-" + l), m.addClass("select2-result"), m.addClass(p ? "select2-result-selectable" : "select2-result-unselectable"), g && m.addClass("select2-disabled"), f && m.addClass("select2-result-with-children"), m.addClass(d.opts.formatResultCssClass(u)), v = e(document.createElement("div")), v.addClass("select2-result-label"), b = i.formatResult(u, v, r, d.opts.escapeMarkup), b !== t && v.html(b), m.append(v), f && (y = e("<ul></ul>"), y.addClass("select2-result-sub"), o(u.children, y, l + 1), m.append(y)), m.data("select2-data", u), a.append(m)
                }, o(a, n, 0)
            }}, e.fn.select2.defaults, i), "function" != typeof i.id && (s = i.id, i.id = function (e) {
                return e[s]
            }), e.isArray(i.element.data("select2Tags"))) {
                if ("tags"in i)throw"tags specified as both an attribute 'data-select2-tags' and in options of Select2 " + i.element.attr("id");
                i.tags = i.element.data("select2Tags")
            }
            if (a ? (i.query = this.bind(function (e) {
                var i, a, r, o = {results: [], more: !1}, s = e.term;
                r = function (t, i) {
                    var n;
                    t.is("option") ? e.matcher(s, t.text(), t) && i.push(d.optionToData(t)) : t.is("optgroup") && (n = d.optionToData(t), t.children().each2(function (e, t) {
                        r(t, n.children)
                    }), n.children.length > 0 && i.push(n))
                }, i = n.children(), this.getPlaceholder() !== t && i.length > 0 && (a = this.getPlaceholderOption(), a && (i = i.not(a))), i.each2(function (e, t) {
                    r(t, o.results)
                }), e.callback(o)
            }), i.id = function (e) {
                return e.id
            }, i.formatResultCssClass = function (e) {
                return e.css
            }) : "query"in i || ("ajax"in i ? (l = i.element.data("ajax-url"), l && l.length > 0 && (i.ajax.url = l), i.query = w.call(i.element, i.ajax)) : "data"in i ? i.query = C(i.data) : "tags"in i && (i.query = _(i.tags), i.createSearchChoice === t && (i.createSearchChoice = function (t) {
                return{id: e.trim(t), text: e.trim(t)}
            }), i.initSelection === t && (i.initSelection = function (t, n) {
                var a = [];
                e(o(t.val(), i.separator)).each(function () {
                    var t = {id: this, text: this}, n = i.tags;
                    e.isFunction(n) && (n = n()), e(n).each(function () {
                        return r(this.id, t.id) ? (t = this, !1) : void 0
                    }), a.push(t)
                }), n(a)
            }))), "function" != typeof i.query)throw"query function not defined for Select2 " + i.element.attr("id");
            return i
        }, monitorSource: function () {
            var e, i = this.opts.element;
            i.on("change.select2", this.bind(function () {
                this.opts.element.data("select2-change-triggered") !== !0 && this.initSelection()
            })), e = this.bind(function () {
                var e, n = i.prop("disabled");
                n === t && (n = !1), this.enable(!n);
                var e = i.prop("readonly");
                e === t && (e = !1), this.readonly(e), y(this.container, this.opts.element, this.opts.adaptContainerCssClass), this.container.addClass(k(this.opts.containerCssClass)), y(this.dropdown, this.opts.element, this.opts.adaptDropdownCssClass), this.dropdown.addClass(k(this.opts.dropdownCssClass))
            }), i.on("propertychange.select2 DOMAttrModified.select2", e), this.mutationCallback === t && (this.mutationCallback = function (t) {
                t.forEach(e)
            }), "undefined" != typeof WebKitMutationObserver && (this.propertyObserver && (delete this.propertyObserver, this.propertyObserver = null), this.propertyObserver = new WebKitMutationObserver(this.mutationCallback), this.propertyObserver.observe(i.get(0), {attributes: !0, subtree: !1}))
        }, triggerSelect: function (t) {
            var i = e.Event("select2-selecting", {val: this.id(t), object: t});
            return this.opts.element.trigger(i), !i.isDefaultPrevented()
        }, triggerChange: function (t) {
            t = t || {}, t = e.extend({}, t, {type: "change", val: this.val()}), this.opts.element.data("select2-change-triggered", !0), this.opts.element.trigger(t), this.opts.element.data("select2-change-triggered", !1), this.opts.element.click(), this.opts.blurOnChange && this.opts.element.blur()
        }, isInterfaceEnabled: function () {
            return this.enabledInterface === !0
        }, enableInterface: function () {
            var e = this._enabled && !this._readonly, t = !e;
            return e === this.enabledInterface ? !1 : (this.container.toggleClass("select2-container-disabled", t), this.close(), this.enabledInterface = e, !0)
        }, enable: function (e) {
            e === t && (e = !0), this._enabled !== e && (this._enabled = e, this.opts.element.prop("disabled", !e), this.enableInterface())
        }, disable: function () {
            this.enable(!1)
        }, readonly: function (e) {
            return e === t && (e = !1), this._readonly === e ? !1 : (this._readonly = e, this.opts.element.prop("readonly", e), this.enableInterface(), !0)
        }, opened: function () {
            return this.container.hasClass("select2-dropdown-open")
        }, positionDropdown: function () {
            var t, i, n, a, r = this.dropdown, o = this.container.offset(), s = this.container.outerHeight(!1), l = r.outerHeight(!1), d = e(window).scrollLeft() + e(window).width(), h = e(window).scrollTop() + e(window).height(), c = o.top + s, u = o.left, p = h >= c + l, g = o.top - l >= this.body().scrollTop(), f = r.outerWidth(!1), m = d >= u + f, v = r.hasClass("select2-drop-above");
            if (App.isIE8() || App.isIE9())var y = this.container.outerWidth(!1); else var y = window.getComputedStyle(this.container[0]).width;
            this.opts.dropdownAutoWidth ? (a = e(".select2-results", r)[0], r.addClass("select2-drop-auto-width"), r.css("width", ""), f = r.outerWidth(!1) + (a.scrollHeight === a.clientHeight ? 0 : L.width), f > y ? y = f : f = y, m = d >= u + f) : this.container.removeClass("select2-drop-auto-width"), "static" !== this.body().css("position") && (t = this.body().offset(), c -= t.top, u -= t.left), v ? (i = !0, !g && p && (i = !1)) : (i = !1, !p && g && (i = !0)), m || (u = o.left + y - f), i ? (c = o.top - l, this.container.addClass("select2-drop-above"), r.addClass("select2-drop-above")) : (this.container.removeClass("select2-drop-above"), r.removeClass("select2-drop-above")), n = e.extend({top: c, left: u, width: y}, k(this.opts.dropdownCss)), r.css(n)
        }, shouldOpen: function () {
            var t;
            return this.opened() ? !1 : this._enabled === !1 || this._readonly === !0 ? !1 : (t = e.Event("select2-opening"), this.opts.element.trigger(t), !t.isDefaultPrevented())
        }, clearDropdownAlignmentPreference: function () {
            this.container.removeClass("select2-drop-above"), this.dropdown.removeClass("select2-drop-above")
        }, open: function () {
            return this.shouldOpen() ? (this.opening(), !0) : !1
        }, opening: function () {
            var t, i = this.containerId, n = "scroll." + i, a = "resize." + i, r = "orientationchange." + i;
            this.container.addClass("select2-dropdown-open").addClass("select2-container-active"), this.clearDropdownAlignmentPreference(), this.dropdown[0] !== this.body().children().last()[0] && this.dropdown.detach().appendTo(this.body()), t = e("#select2-drop-mask"), 0 == t.length && (t = e(document.createElement("div")), t.attr("id", "select2-drop-mask").attr("class", "select2-drop-mask"), t.hide(), t.appendTo(this.body()), t.on("mousedown touchstart click", function (t) {
                var i, n = e("#select2-drop");
                n.length > 0 && (i = n.data("select2"), i.opts.selectOnBlur && i.selectHighlighted({noFocus: !0}), i.close({focus: !1}), t.preventDefault(), t.stopPropagation())
            })), this.dropdown.prev()[0] !== t[0] && this.dropdown.before(t), e("#select2-drop").removeAttr("id"), this.dropdown.attr("id", "select2-drop"), t.show(), this.positionDropdown(), this.dropdown.show(), this.positionDropdown(), this.dropdown.addClass("select2-drop-active");
            var o = this;
            this.container.parents().add(window).each(function () {
                e(this).on(a + " " + n + " " + r, function () {
                    o.positionDropdown()
                })
            })
        }, close: function () {
            if (this.opened()) {
                var t = this.containerId, i = "scroll." + t, n = "resize." + t, a = "orientationchange." + t;
                this.container.parents().add(window).each(function () {
                    e(this).off(i).off(n).off(a)
                }), this.clearDropdownAlignmentPreference(), e("#select2-drop-mask").hide(), this.dropdown.removeAttr("id"), this.dropdown.hide(), this.container.removeClass("select2-dropdown-open").removeClass("select2-container-active"), this.results.empty(), this.clearSearch(), this.search.removeClass("select2-active"), this.opts.element.trigger(e.Event("select2-close"))
            }
        }, externalSearch: function (e) {
            this.open(), this.search.val(e), this.updateResults(!1)
        }, clearSearch: function () {
        }, getMaximumSelectionSize: function () {
            return k(this.opts.maximumSelectionSize)
        }, ensureHighlightVisible: function () {
            var t, i, n, a, r, o, s, l = this.results;
            if (i = this.highlight(), !(0 > i)) {
                if (0 == i)return l.scrollTop(0), void 0;
                t = this.findHighlightableChoices().find(".select2-result-label"), n = e(t[i]), a = n.offset().top + n.outerHeight(!0), i === t.length - 1 && (s = l.find("li.select2-more-results"), s.length > 0 && (a = s.offset().top + s.outerHeight(!0))), r = l.offset().top + l.outerHeight(!0), a > r && l.scrollTop(l.scrollTop() + (a - r)), o = n.offset().top - l.offset().top, 0 > o && "none" != n.css("display") && l.scrollTop(l.scrollTop() + o)
            }
        }, findHighlightableChoices: function () {
            return this.results.find(".select2-result-selectable:not(.select2-disabled)")
        }, moveHighlight: function (t) {
            for (var i = this.findHighlightableChoices(), n = this.highlight(); n > -1 && n < i.length;) {
                n += t;
                var a = e(i[n]);
                if (a.hasClass("select2-result-selectable") && !a.hasClass("select2-disabled") && !a.hasClass("select2-selected")) {
                    this.highlight(n);
                    break
                }
            }
        }, highlight: function (t) {
            var i, a, r = this.findHighlightableChoices();
            return 0 === arguments.length ? n(r.filter(".select2-highlighted")[0], r.get()) : (t >= r.length && (t = r.length - 1), 0 > t && (t = 0), this.removeHighlight(), i = e(r[t]), i.addClass("select2-highlighted"), this.ensureHighlightVisible(), a = i.data("select2-data"), a && this.opts.element.trigger({type: "select2-highlight", val: this.id(a), choice: a}), void 0)
        }, removeHighlight: function () {
            this.results.find(".select2-highlighted").removeClass("select2-highlighted")
        }, countSelectableResults: function () {
            return this.findHighlightableChoices().length
        }, highlightUnderEvent: function (t) {
            var i = e(t.target).closest(".select2-result-selectable");
            if (i.length > 0 && !i.is(".select2-highlighted")) {
                var n = this.findHighlightableChoices();
                this.highlight(n.index(i))
            } else 0 == i.length && this.removeHighlight()
        }, loadMoreIfNeeded: function () {
            var e, t = this.results, i = t.find("li.select2-more-results"), n = this.resultsPage + 1, a = this, r = this.search.val(), o = this.context;
            0 !== i.length && (e = i.offset().top - t.offset().top - t.height(), e <= this.opts.loadMorePadding && (i.addClass("select2-active"), this.opts.query({element: this.opts.element, term: r, page: n, context: o, matcher: this.opts.matcher, callback: this.bind(function (e) {
                a.opened() && (a.opts.populateResults.call(this, t, e.results, {term: r, page: n, context: o}), a.postprocessResults(e, !1, !1), e.more === !0 ? (i.detach().appendTo(t).text(a.opts.formatLoadMore(n + 1)), window.setTimeout(function () {
                    a.loadMoreIfNeeded()
                }, 10)) : i.remove(), a.positionDropdown(), a.resultsPage = n, a.context = e.context, this.opts.element.trigger({type: "select2-loaded", items: e}))
            })})))
        }, tokenize: function () {
        }, updateResults: function (i) {
            function n() {
                d.removeClass("select2-active"), u.positionDropdown()
            }

            function a(e) {
                h.html(e), n()
            }

            var o, s, l, d = this.search, h = this.results, c = this.opts, u = this, p = d.val(), g = e.data(this.container, "select2-last-term");
            if ((i === !0 || !g || !r(p, g)) && (e.data(this.container, "select2-last-term", p), i === !0 || this.showSearchInput !== !1 && this.opened())) {
                l = ++this.queryCount;
                var f = this.getMaximumSelectionSize();
                if (f >= 1 && (o = this.data(), e.isArray(o) && o.length >= f && T(c.formatSelectionTooBig, "formatSelectionTooBig")))return a("<li class='select2-selection-limit'>" + c.formatSelectionTooBig(f) + "</li>"), void 0;
                if (d.val().length < c.minimumInputLength)return T(c.formatInputTooShort, "formatInputTooShort") ? a("<li class='select2-no-results'>" + c.formatInputTooShort(d.val(), c.minimumInputLength) + "</li>") : a(""), i && this.showSearch && this.showSearch(!0), void 0;
                if (c.maximumInputLength && d.val().length > c.maximumInputLength)return T(c.formatInputTooLong, "formatInputTooLong") ? a("<li class='select2-no-results'>" + c.formatInputTooLong(d.val(), c.maximumInputLength) + "</li>") : a(""), void 0;
                c.formatSearching && 0 === this.findHighlightableChoices().length && a("<li class='select2-searching'>" + c.formatSearching() + "</li>"), d.addClass("select2-active"), this.removeHighlight(), s = this.tokenize(), s != t && null != s && d.val(s), this.resultsPage = 1, c.query({element: c.element, term: d.val(), page: this.resultsPage, context: null, matcher: c.matcher, callback: this.bind(function (o) {
                    var s;
                    if (l == this.queryCount) {
                        if (!this.opened())return this.search.removeClass("select2-active"), void 0;
                        if (this.context = o.context === t ? null : o.context, this.opts.createSearchChoice && "" !== d.val() && (s = this.opts.createSearchChoice.call(u, d.val(), o.results), s !== t && null !== s && u.id(s) !== t && null !== u.id(s) && 0 === e(o.results).filter(function () {
                            return r(u.id(this), u.id(s))
                        }).length && o.results.unshift(s)), 0 === o.results.length && T(c.formatNoMatches, "formatNoMatches"))return a("<li class='select2-no-results'>" + c.formatNoMatches(d.val()) + "</li>"), void 0;
                        h.empty(), u.opts.populateResults.call(this, h, o.results, {term: d.val(), page: this.resultsPage, context: null}), o.more === !0 && T(c.formatLoadMore, "formatLoadMore") && (h.append("<li class='select2-more-results'>" + u.opts.escapeMarkup(c.formatLoadMore(this.resultsPage)) + "</li>"), window.setTimeout(function () {
                            u.loadMoreIfNeeded()
                        }, 10)), this.postprocessResults(o, i), n(), this.opts.element.trigger({type: "select2-loaded", items: o})
                    }
                })})
            }
        }, cancel: function () {
            this.close()
        }, blur: function () {
            this.opts.selectOnBlur && this.selectHighlighted({noFocus: !0}), this.close(), this.container.removeClass("select2-container-active"), this.search[0] === document.activeElement && this.search.blur(), this.clearSearch(), this.selection.find(".select2-search-choice-focus").removeClass("select2-search-choice-focus")
        }, focusSearch: function () {
            p(this.search)
        }, selectHighlighted: function (e) {
            var t = this.highlight(), i = this.results.find(".select2-highlighted"), n = i.closest(".select2-result").data("select2-data");
            n ? (this.highlight(t), this.onSelect(n, e)) : e && e.noFocus && this.close()
        }, getPlaceholder: function () {
            var e;
            return this.opts.element.attr("placeholder") || this.opts.element.attr("data-placeholder") || this.opts.element.data("placeholder") || this.opts.placeholder || ((e = this.getPlaceholderOption()) !== t ? e.text() : t)
        }, getPlaceholderOption: function () {
            if (this.select) {
                var e = this.select.children().first();
                if (this.opts.placeholderOption !== t)return"first" === this.opts.placeholderOption && e || "function" == typeof this.opts.placeholderOption && this.opts.placeholderOption(this.select);
                if ("" === e.text() && "" === e.val())return e
            }
        }, initContainerWidth: function () {
            function i() {
                var i, n, a, r, o;
                if ("off" === this.opts.width)return null;
                if ("element" === this.opts.width)return 0 === this.opts.element.outerWidth(!1) ? "auto" : this.opts.element.outerWidth(!1) + "px";
                if ("copy" === this.opts.width || "resolve" === this.opts.width) {
                    if (i = this.opts.element.attr("style"), i !== t)for (n = i.split(";"), r = 0, o = n.length; o > r; r += 1)if (a = n[r].replace(/\s/g, "").match(/[^-]width:(([-+]?([0-9]*\.)?[0-9]+)(px|em|ex|%|in|cm|mm|pt|pc))/i), null !== a && a.length >= 1)return a[1];
                    return"resolve" === this.opts.width ? (i = this.opts.element.css("width"), i.indexOf("%") > 0 ? i : 0 === this.opts.element.outerWidth(!1) ? "auto" : this.opts.element.outerWidth(!1) + "px") : null
                }
                return e.isFunction(this.opts.width) ? this.opts.width() : this.opts.width
            }

            var n = i.call(this);
            null !== n && this.container.css("width", n)
        }}), R = E(I, {createContainer: function () {
            var t = e(document.createElement("div")).attr({"class": "select2-container"}).html(["<a href='javascript:void(0)' onclick='return false;' class='select2-choice' tabindex='-1'>", "   <span class='select2-chosen'>&nbsp;</span><abbr class='select2-search-choice-close'></abbr>", "   <span class='select2-arrow'><b></b></span>", "</a>", "<input class='select2-focusser select2-offscreen' type='text'/>", "<div class='select2-drop select2-display-none'>", "   <div class='select2-search'>", "       <input type='text' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' class='select2-input'/>", "   </div>", "   <ul class='select2-results'>", "   </ul>", "</div>"].join(""));
            return t
        }, enableInterface: function () {
            this.parent.enableInterface.apply(this, arguments) && this.focusser.prop("disabled", !this.isInterfaceEnabled())
        }, opening: function () {
            var i, n, a;
            this.opts.minimumResultsForSearch >= 0 && this.showSearch(!0), this.parent.opening.apply(this, arguments), this.showSearchInput !== !1 && this.search.val(this.focusser.val()), this.search.focus(), i = this.search.get(0), i.createTextRange ? (n = i.createTextRange(), n.collapse(!1), n.select()) : i.setSelectionRange && (a = this.search.val().length, i.setSelectionRange(a, a)), "" === this.search.val() && this.nextSearchTerm != t && (this.search.val(this.nextSearchTerm), this.search.select()), this.focusser.prop("disabled", !0).val(""), this.updateResults(!0), this.opts.element.trigger(e.Event("select2-open"))
        }, close: function (e) {
            this.opened() && (this.parent.close.apply(this, arguments), e = e || {focus: !0}, this.focusser.removeAttr("disabled"), e.focus && this.focusser.focus())
        }, focus: function () {
            this.opened() ? this.close() : (this.focusser.removeAttr("disabled"), this.focusser.focus())
        }, isFocused: function () {
            return this.container.hasClass("select2-container-active")
        }, cancel: function () {
            this.parent.cancel.apply(this, arguments), this.focusser.removeAttr("disabled"), this.focusser.focus()
        }, destroy: function () {
            e("label[for='" + this.focusser.attr("id") + "']").attr("for", this.opts.element.attr("id")), this.parent.destroy.apply(this, arguments)
        }, initContainer: function () {
            var t, i = this.container, n = this.dropdown;
            this.opts.minimumResultsForSearch < 0 ? this.showSearch(!1) : this.showSearch(!0), this.selection = t = i.find(".select2-choice"), this.focusser = i.find(".select2-focusser"), this.focusser.attr("id", "s2id_autogen" + D()), e("label[for='" + this.opts.element.attr("id") + "']").attr("for", this.focusser.attr("id")), this.focusser.attr("tabindex", this.elementTabIndex), this.search.on("keydown", this.bind(function (e) {
                if (this.isInterfaceEnabled()) {
                    if (e.which === A.PAGE_UP || e.which === A.PAGE_DOWN)return f(e), void 0;
                    switch (e.which) {
                        case A.UP:
                        case A.DOWN:
                            return this.moveHighlight(e.which === A.UP ? -1 : 1), f(e), void 0;
                        case A.ENTER:
                            return this.selectHighlighted(), f(e), void 0;
                        case A.TAB:
                            return this.selectHighlighted({noFocus: !0}), void 0;
                        case A.ESC:
                            return this.cancel(e), f(e), void 0
                    }
                }
            })), this.search.on("blur", this.bind(function () {
                document.activeElement === this.body().get(0) && window.setTimeout(this.bind(function () {
                    this.search.focus()
                }), 0)
            })), this.focusser.on("keydown", this.bind(function (e) {
                if (this.isInterfaceEnabled() && e.which !== A.TAB && !A.isControl(e) && !A.isFunctionKey(e) && e.which !== A.ESC) {
                    if (this.opts.openOnEnter === !1 && e.which === A.ENTER)return f(e), void 0;
                    if (e.which == A.DOWN || e.which == A.UP || e.which == A.ENTER && this.opts.openOnEnter) {
                        if (e.altKey || e.ctrlKey || e.shiftKey || e.metaKey)return;
                        return this.open(), f(e), void 0
                    }
                    return e.which == A.DELETE || e.which == A.BACKSPACE ? (this.opts.allowClear && this.clear(), f(e), void 0) : void 0
                }
            })), l(this.focusser), this.focusser.on("keyup-change input", this.bind(function (e) {
                if (this.opts.minimumResultsForSearch >= 0) {
                    if (e.stopPropagation(), this.opened())return;
                    this.open()
                }
            })), t.on("mousedown", "abbr", this.bind(function (e) {
                this.isInterfaceEnabled() && (this.clear(), m(e), this.close(), this.selection.focus())
            })), t.on("mousedown", this.bind(function (t) {
                this.container.hasClass("select2-container-active") || this.opts.element.trigger(e.Event("select2-focus")), this.opened() ? this.close() : this.isInterfaceEnabled() && this.open(), f(t)
            })), n.on("mousedown", this.bind(function () {
                this.search.focus()
            })), t.on("focus", this.bind(function (e) {
                f(e)
            })), this.focusser.on("focus", this.bind(function () {
                this.container.hasClass("select2-container-active") || this.opts.element.trigger(e.Event("select2-focus")), this.container.addClass("select2-container-active")
            })).on("blur", this.bind(function () {
                this.opened() || (this.container.removeClass("select2-container-active"), this.opts.element.trigger(e.Event("select2-blur")))
            })), this.search.on("focus", this.bind(function () {
                this.container.hasClass("select2-container-active") || this.opts.element.trigger(e.Event("select2-focus")), this.container.addClass("select2-container-active")
            })), this.initContainerWidth(), this.opts.element.addClass("select2-offscreen"), this.setPlaceholder()
        }, clear: function (t) {
            var i = this.selection.data("select2-data");
            if (i) {
                var n = e.Event("select2-clearing");
                if (this.opts.element.trigger(n), n.isDefaultPrevented())return;
                var a = this.getPlaceholderOption();
                this.opts.element.val(a ? a.val() : ""), this.selection.find(".select2-chosen").empty(), this.selection.removeData("select2-data"), this.setPlaceholder(), t !== !1 && (this.opts.element.trigger({type: "select2-removed", val: this.id(i), choice: i}), this.triggerChange({removed: i}))
            }
        }, initSelection: function () {
            if (this.isPlaceholderOptionSelected())this.updateSelection(null), this.close(), this.setPlaceholder(); else {
                var e = this;
                this.opts.initSelection.call(null, this.opts.element, function (i) {
                    i !== t && null !== i && (e.updateSelection(i), e.close(), e.setPlaceholder())
                })
            }
        }, isPlaceholderOptionSelected: function () {
            var e;
            return this.getPlaceholder() ? (e = this.getPlaceholderOption()) !== t && e.is(":selected") || "" === this.opts.element.val() || this.opts.element.val() === t || null === this.opts.element.val() : !1
        }, prepareOpts: function () {
            var t = this.parent.prepareOpts.apply(this, arguments), i = this;
            return"select" === t.element.get(0).tagName.toLowerCase() ? t.initSelection = function (e, t) {
                var n = e.find(":selected");
                t(i.optionToData(n))
            } : "data"in t && (t.initSelection = t.initSelection || function (i, n) {
                var a = i.val(), o = null;
                t.query({matcher: function (e, i, n) {
                    var s = r(a, t.id(n));
                    return s && (o = n), s
                }, callback: e.isFunction(n) ? function () {
                    n(o)
                } : e.noop})
            }), t
        }, getPlaceholder: function () {
            return this.select && this.getPlaceholderOption() === t ? t : this.parent.getPlaceholder.apply(this, arguments)
        }, setPlaceholder: function () {
            var e = this.getPlaceholder();
            if (this.isPlaceholderOptionSelected() && e !== t) {
                if (this.select && this.getPlaceholderOption() === t)return;
                this.selection.find(".select2-chosen").html(this.opts.escapeMarkup(e)), this.selection.addClass("select2-default"), this.container.removeClass("select2-allowclear")
            }
        }, postprocessResults: function (e, t, i) {
            var n = 0, a = this;
            if (this.findHighlightableChoices().each2(function (e, t) {
                return r(a.id(t.data("select2-data")), a.opts.element.val()) ? (n = e, !1) : void 0
            }), i !== !1 && (t === !0 && n >= 0 ? this.highlight(n) : this.highlight(0)), t === !0) {
                var o = this.opts.minimumResultsForSearch;
                o >= 0 && this.showSearch(M(e.results) >= o)
            }
        }, showSearch: function (t) {
            this.showSearchInput !== t && (this.showSearchInput = t, this.dropdown.find(".select2-search").toggleClass("select2-search-hidden", !t), this.dropdown.find(".select2-search").toggleClass("select2-offscreen", !t), e(this.dropdown, this.container).toggleClass("select2-with-searchbox", t))
        }, onSelect: function (e, t) {
            if (this.triggerSelect(e)) {
                var i = this.opts.element.val(), n = this.data();
                this.opts.element.val(this.id(e)), this.updateSelection(e), this.opts.element.trigger({type: "select2-selected", val: this.id(e), choice: e}), this.nextSearchTerm = this.opts.nextSearchTerm(e, this.search.val()), this.close(), t && t.noFocus || this.focusser.focus(), r(i, this.id(e)) || this.triggerChange({added: e, removed: n})
            }
        }, updateSelection: function (e) {
            var i, n, a = this.selection.find(".select2-chosen");
            this.selection.data("select2-data", e), a.empty(), null !== e && (i = this.opts.formatSelection(e, a, this.opts.escapeMarkup)), i !== t && a.append(i), n = this.opts.formatSelectionCssClass(e, a), n !== t && a.addClass(n), this.selection.removeClass("select2-default"), this.opts.allowClear && this.getPlaceholder() !== t && this.container.addClass("select2-allowclear")
        }, val: function () {
            var e, i = !1, n = null, a = this, r = this.data();
            if (0 === arguments.length)return this.opts.element.val();
            if (e = arguments[0], arguments.length > 1 && (i = arguments[1]), this.select)this.select.val(e).find(":selected").each2(function (e, t) {
                return n = a.optionToData(t), !1
            }), this.updateSelection(n), this.setPlaceholder(), i && this.triggerChange({added: n, removed: r}); else {
                if (!e && 0 !== e)return this.clear(i), void 0;
                if (this.opts.initSelection === t)throw new Error("cannot call val() if initSelection() is not defined");
                this.opts.element.val(e), this.opts.initSelection(this.opts.element, function (e) {
                    a.opts.element.val(e ? a.id(e) : ""), a.updateSelection(e), a.setPlaceholder(), i && a.triggerChange({added: e, removed: r})
                })
            }
        }, clearSearch: function () {
            this.search.val(""), this.focusser.val("")
        }, data: function (e) {
            var i, n = !1;
            return 0 === arguments.length ? (i = this.selection.data("select2-data"), i == t && (i = null), i) : (arguments.length > 1 && (n = arguments[1]), e ? (i = this.data(), this.opts.element.val(e ? this.id(e) : ""), this.updateSelection(e), n && this.triggerChange({added: e, removed: i})) : this.clear(n), void 0)
        }}), N = E(I, {createContainer: function () {
            var t = e(document.createElement("div")).attr({"class": "select2-container select2-container-multi"}).html(["<ul class='select2-choices'>", "  <li class='select2-search-field'>", "    <input type='text' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' class='select2-input'>", "  </li>", "</ul>", "<div class='select2-drop select2-drop-multi select2-display-none'>", "   <ul class='select2-results'>", "   </ul>", "</div>"].join(""));
            return t
        }, prepareOpts: function () {
            var t = this.parent.prepareOpts.apply(this, arguments), i = this;
            return"select" === t.element.get(0).tagName.toLowerCase() ? t.initSelection = function (e, t) {
                var n = [];
                e.find(":selected").each2(function (e, t) {
                    n.push(i.optionToData(t))
                }), t(n)
            } : "data"in t && (t.initSelection = t.initSelection || function (i, n) {
                var a = o(i.val(), t.separator), s = [];
                t.query({matcher: function (i, n, o) {
                    var l = e.grep(a,function (e) {
                        return r(e, t.id(o))
                    }).length;
                    return l && s.push(o), l
                }, callback: e.isFunction(n) ? function () {
                    for (var e = [], i = 0; i < a.length; i++)for (var o = a[i], l = 0; l < s.length; l++) {
                        var d = s[l];
                        if (r(o, t.id(d))) {
                            e.push(d), s.splice(l, 1);
                            break
                        }
                    }
                    n(e)
                } : e.noop})
            }), t
        }, selectChoice: function (e) {
            var t = this.container.find(".select2-search-choice-focus");
            t.length && e && e[0] == t[0] || (t.length && this.opts.element.trigger("choice-deselected", t), t.removeClass("select2-search-choice-focus"), e && e.length && (this.close(), e.addClass("select2-search-choice-focus"), this.opts.element.trigger("choice-selected", e)))
        }, destroy: function () {
            e("label[for='" + this.search.attr("id") + "']").attr("for", this.opts.element.attr("id")), this.parent.destroy.apply(this, arguments)
        }, initContainer: function () {
            var t, i = ".select2-choices";
            this.searchContainer = this.container.find(".select2-search-field"), this.selection = t = this.container.find(i);
            var n = this;
            this.selection.on("click", ".select2-search-choice:not(.select2-locked)", function () {
                n.search[0].focus(), n.selectChoice(e(this))
            }), this.search.attr("id", "s2id_autogen" + D()), e("label[for='" + this.opts.element.attr("id") + "']").attr("for", this.search.attr("id")), this.search.on("input paste", this.bind(function () {
                this.isInterfaceEnabled() && (this.opened() || this.open())
            })), this.search.attr("tabindex", this.elementTabIndex), this.keydowns = 0, this.search.on("keydown", this.bind(function (e) {
                if (this.isInterfaceEnabled()) {
                    ++this.keydowns;
                    var i = t.find(".select2-search-choice-focus"), n = i.prev(".select2-search-choice:not(.select2-locked)"), a = i.next(".select2-search-choice:not(.select2-locked)"), r = g(this.search);
                    if (i.length && (e.which == A.LEFT || e.which == A.RIGHT || e.which == A.BACKSPACE || e.which == A.DELETE || e.which == A.ENTER)) {
                        var o = i;
                        return e.which == A.LEFT && n.length ? o = n : e.which == A.RIGHT ? o = a.length ? a : null : e.which === A.BACKSPACE ? (this.unselect(i.first()), this.search.width(10), o = n.length ? n : a) : e.which == A.DELETE ? (this.unselect(i.first()), this.search.width(10), o = a.length ? a : null) : e.which == A.ENTER && (o = null), this.selectChoice(o), f(e), o && o.length || this.open(), void 0
                    }
                    if ((e.which === A.BACKSPACE && 1 == this.keydowns || e.which == A.LEFT) && 0 == r.offset && !r.length)return this.selectChoice(t.find(".select2-search-choice:not(.select2-locked)").last()), f(e), void 0;
                    if (this.selectChoice(null), this.opened())switch (e.which) {
                        case A.UP:
                        case A.DOWN:
                            return this.moveHighlight(e.which === A.UP ? -1 : 1), f(e), void 0;
                        case A.ENTER:
                            return this.selectHighlighted(), f(e), void 0;
                        case A.TAB:
                            return this.selectHighlighted({noFocus: !0}), this.close(), void 0;
                        case A.ESC:
                            return this.cancel(e), f(e), void 0
                    }
                    if (e.which !== A.TAB && !A.isControl(e) && !A.isFunctionKey(e) && e.which !== A.BACKSPACE && e.which !== A.ESC) {
                        if (e.which === A.ENTER) {
                            if (this.opts.openOnEnter === !1)return;
                            if (e.altKey || e.ctrlKey || e.shiftKey || e.metaKey)return
                        }
                        this.open(), (e.which === A.PAGE_UP || e.which === A.PAGE_DOWN) && f(e), e.which === A.ENTER && f(e)
                    }
                }
            })), this.search.on("keyup", this.bind(function () {
                this.keydowns = 0, this.resizeSearch()
            })), this.search.on("blur", this.bind(function (t) {
                this.container.removeClass("select2-container-active"), this.search.removeClass("select2-focused"), this.selectChoice(null), this.opened() || this.clearSearch(), t.stopImmediatePropagation(), this.opts.element.trigger(e.Event("select2-blur"))
            })), this.container.on("click", i, this.bind(function (t) {
                this.isInterfaceEnabled() && (e(t.target).closest(".select2-search-choice").length > 0 || (this.selectChoice(null), this.clearPlaceholder(), this.container.hasClass("select2-container-active") || this.opts.element.trigger(e.Event("select2-focus")), this.open(), this.focusSearch(), t.preventDefault()))
            })), this.container.on("focus", i, this.bind(function () {
                this.isInterfaceEnabled() && (this.container.hasClass("select2-container-active") || this.opts.element.trigger(e.Event("select2-focus")), this.container.addClass("select2-container-active"), this.dropdown.addClass("select2-drop-active"), this.clearPlaceholder())
            })), this.initContainerWidth(), this.opts.element.addClass("select2-offscreen"), this.clearSearch()
        }, enableInterface: function () {
            this.parent.enableInterface.apply(this, arguments) && this.search.prop("disabled", !this.isInterfaceEnabled())
        }, initSelection: function () {
            if ("" === this.opts.element.val() && "" === this.opts.element.text() && (this.updateSelection([]), this.close(), this.clearSearch()), this.select || "" !== this.opts.element.val()) {
                var e = this;
                this.opts.initSelection.call(null, this.opts.element, function (i) {
                    i !== t && null !== i && (e.updateSelection(i), e.close(), e.clearSearch())
                })
            }
        }, clearSearch: function () {
            var e = this.getPlaceholder(), i = this.getMaxSearchWidth();
            e !== t && 0 === this.getVal().length && this.search.hasClass("select2-focused") === !1 ? (this.search.val(e).addClass("select2-default"), this.search.width(i > 0 ? i : this.container.css("width"))) : this.search.val("").width(10)
        }, clearPlaceholder: function () {
            this.search.hasClass("select2-default") && this.search.val("").removeClass("select2-default")
        }, opening: function () {
            this.clearPlaceholder(), this.resizeSearch(), this.parent.opening.apply(this, arguments), this.focusSearch(), this.updateResults(!0), this.search.focus(), this.opts.element.trigger(e.Event("select2-open"))
        }, close: function () {
            this.opened() && this.parent.close.apply(this, arguments)
        }, focus: function () {
            this.close(), this.search.focus()
        }, isFocused: function () {
            return this.search.hasClass("select2-focused")
        }, updateSelection: function (t) {
            var i = [], a = [], r = this;
            e(t).each(function () {
                n(r.id(this), i) < 0 && (i.push(r.id(this)), a.push(this))
            }), t = a, this.selection.find(".select2-search-choice").remove(), e(t).each(function () {
                r.addSelectedChoice(this)
            }), r.postprocessResults()
        }, tokenize: function () {
            var e = this.search.val();
            e = this.opts.tokenizer.call(this, e, this.data(), this.bind(this.onSelect), this.opts), null != e && e != t && (this.search.val(e), e.length > 0 && this.open())
        }, onSelect: function (e, t) {
            this.triggerSelect(e) && (this.addSelectedChoice(e), this.opts.element.trigger({type: "selected", val: this.id(e), choice: e}), (this.select || !this.opts.closeOnSelect) && this.postprocessResults(e, !1, this.opts.closeOnSelect === !0), this.opts.closeOnSelect ? (this.close(), this.search.width(10)) : this.countSelectableResults() > 0 ? (this.search.width(10), this.resizeSearch(), this.getMaximumSelectionSize() > 0 && this.val().length >= this.getMaximumSelectionSize() && this.updateResults(!0), this.positionDropdown()) : (this.close(), this.search.width(10)), this.triggerChange({added: e}), t && t.noFocus || this.focusSearch())
        }, cancel: function () {
            this.close(), this.focusSearch()
        }, addSelectedChoice: function (i) {
            var n, a, r = !i.locked, o = e("<li class='select2-search-choice'>    <div></div>    <a href='#' onclick='return false;' class='select2-search-choice-close' tabindex='-1'></a></li>"), s = e("<li class='select2-search-choice select2-locked'><div></div></li>"), l = r ? o : s, d = this.id(i), h = this.getVal();
            n = this.opts.formatSelection(i, l.find("div"), this.opts.escapeMarkup), n != t && l.find("div").replaceWith("<div>" + n + "</div>"), a = this.opts.formatSelectionCssClass(i, l.find("div")), a != t && l.addClass(a), r && l.find(".select2-search-choice-close").on("mousedown", f).on("click dblclick", this.bind(function (t) {
                this.isInterfaceEnabled() && (e(t.target).closest(".select2-search-choice").fadeOut("fast", this.bind(function () {
                    this.unselect(e(t.target)), this.selection.find(".select2-search-choice-focus").removeClass("select2-search-choice-focus"), this.close(), this.focusSearch()
                })).dequeue(), f(t))
            })).on("focus", this.bind(function () {
                this.isInterfaceEnabled() && (this.container.addClass("select2-container-active"), this.dropdown.addClass("select2-drop-active"))
            })), l.data("select2-data", i), l.insertBefore(this.searchContainer), h.push(d), this.setVal(h)
        }, unselect: function (e) {
            var t, i, a = this.getVal();
            if (e = e.closest(".select2-search-choice"), 0 === e.length)throw"Invalid argument: " + e + ". Must be .select2-search-choice";
            if (t = e.data("select2-data")) {
                for (; (i = n(this.id(t), a)) >= 0;)a.splice(i, 1), this.setVal(a), this.select && this.postprocessResults();
                e.remove(), this.opts.element.trigger({type: "removed", val: this.id(t), choice: t}), this.triggerChange({removed: t})
            }
        }, postprocessResults: function (e, t, i) {
            var a = this.getVal(), r = this.results.find(".select2-result"), o = this.results.find(".select2-result-with-children"), s = this;
            r.each2(function (e, t) {
                var i = s.id(t.data("select2-data"));
                n(i, a) >= 0 && (t.addClass("select2-selected"), t.find(".select2-result-selectable").addClass("select2-selected"))
            }), o.each2(function (e, t) {
                t.is(".select2-result-selectable") || 0 !== t.find(".select2-result-selectable:not(.select2-selected)").length || t.addClass("select2-selected")
            }), -1 == this.highlight() && i !== !1 && s.highlight(0), !this.opts.createSearchChoice && !r.filter(".select2-result:not(.select2-selected)").length > 0 && (!e || e && !e.more && 0 === this.results.find(".select2-no-results").length) && T(s.opts.formatNoMatches, "formatNoMatches") && this.results.append("<li class='select2-no-results'>" + s.opts.formatNoMatches(s.search.val()) + "</li>")
        }, getMaxSearchWidth: function () {
            return this.selection.width() - s(this.search)
        }, resizeSearch: function () {
            var e, t, i, n, a, r = s(this.search);
            e = v(this.search) + 10, t = this.search.offset().left, i = this.selection.width(), n = this.selection.offset().left, a = i - (t - n) - r, e > a && (a = i - r), 40 > a && (a = i - r), 0 >= a && (a = e), this.search.width(Math.floor(a))
        }, getVal: function () {
            var e;
            return this.select ? (e = this.select.val(), null === e ? [] : e) : (e = this.opts.element.val(), o(e, this.opts.separator))
        }, setVal: function (t) {
            var i;
            this.select ? this.select.val(t) : (i = [], e(t).each(function () {
                n(this, i) < 0 && i.push(this)
            }), this.opts.element.val(0 === i.length ? "" : i.join(this.opts.separator)))
        }, buildChangeDetails: function (e, t) {
            for (var t = t.slice(0), e = e.slice(0), i = 0; i < t.length; i++)for (var n = 0; n < e.length; n++)r(this.opts.id(t[i]), this.opts.id(e[n])) && (t.splice(i, 1), i--, e.splice(n, 1), n--);
            return{added: t, removed: e}
        }, val: function (i, n) {
            var a, r = this;
            if (0 === arguments.length)return this.getVal();
            if (a = this.data(), a.length || (a = []), !i && 0 !== i)return this.opts.element.val(""), this.updateSelection([]), this.clearSearch(), n && this.triggerChange({added: this.data(), removed: a}), void 0;
            if (this.setVal(i), this.select)this.opts.initSelection(this.select, this.bind(this.updateSelection)), n && this.triggerChange(this.buildChangeDetails(a, this.data())); else {
                if (this.opts.initSelection === t)throw new Error("val() cannot be called if initSelection() is not defined");
                this.opts.initSelection(this.opts.element, function (t) {
                    var i = e.map(t, r.id);
                    r.setVal(i), r.updateSelection(t), r.clearSearch(), n && r.triggerChange(r.buildChangeDetails(a, this.data()))
                })
            }
            this.clearSearch()
        }, onSortStart: function () {
            if (this.select)throw new Error("Sorting of elements is not supported when attached to <select>. Attach to <input type='hidden'/> instead.");
            this.search.width(0), this.searchContainer.hide()
        }, onSortEnd: function () {
            var t = [], i = this;
            this.searchContainer.show(), this.searchContainer.appendTo(this.searchContainer.parent()), this.resizeSearch(), this.selection.find(".select2-search-choice").each(function () {
                t.push(i.opts.id(e(this).data("select2-data")))
            }), this.setVal(t), this.triggerChange()
        }, data: function (t, i) {
            var n, a, r = this;
            return 0 === arguments.length ? this.selection.find(".select2-search-choice").map(function () {
                return e(this).data("select2-data")
            }).get() : (a = this.data(), t || (t = []), n = e.map(t, function (e) {
                return r.opts.id(e)
            }), this.setVal(n), this.updateSelection(t), this.clearSearch(), i && this.triggerChange(this.buildChangeDetails(a, this.data())), void 0)
        }}), e.fn.select2 = function () {
            var i, a, r, o, s, l = Array.prototype.slice.call(arguments, 0), d = ["val", "destroy", "opened", "open", "close", "focus", "isFocused", "container", "dropdown", "onSortStart", "onSortEnd", "enable", "disable", "readonly", "positionDropdown", "data", "search"], h = ["opened", "isFocused", "container", "dropdown"], c = ["val", "data"], u = {search: "externalSearch"};
            return this.each(function () {
                if (0 === l.length || "object" == typeof l[0])i = 0 === l.length ? {} : e.extend({}, l[0]), i.element = e(this), "select" === i.element.get(0).tagName.toLowerCase() ? s = i.element.prop("multiple") : (s = i.multiple || !1, "tags"in i && (i.multiple = s = !0)), a = s ? new N : new R, a.init(i); else {
                    if ("string" != typeof l[0])throw"Invalid arguments to select2 plugin: " + l;
                    if (n(l[0], d) < 0)throw"Unknown method: " + l[0];
                    if (o = t, a = e(this).data("select2"), a === t)return;
                    if (r = l[0], "container" === r ? o = a.container : "dropdown" === r ? o = a.dropdown : (u[r] && (r = u[r]), o = a[r].apply(a, l.slice(1))), n(l[0], h) >= 0 || n(l[0], c) && 1 == l.length)return!1
                }
            }), o === t ? this : o
        }, e.fn.select2.defaults = {width: "copy", loadMorePadding: 0, closeOnSelect: !0, openOnEnter: !0, containerCss: {}, dropdownCss: {}, containerCssClass: "", dropdownCssClass: "", formatResult: function (e, t, i, n) {
            var a = [];
            return b(e.text, i.term, a, n), a.join("")
        }, formatSelection: function (e, i, n) {
            return e ? n(e.text) : t
        }, sortResults: function (e) {
            return e
        }, formatResultCssClass: function () {
            return t
        }, formatSelectionCssClass: function () {
            return t
        }, formatNoMatches: function () {
            return"No matches found"
        }, formatInputTooShort: function (e, t) {
            var i = t - e.length;
            return"Please enter " + i + " more character" + (1 == i ? "" : "s")
        }, formatInputTooLong: function (e, t) {
            var i = e.length - t;
            return"Please delete " + i + " character" + (1 == i ? "" : "s")
        }, formatSelectionTooBig: function (e) {
            return"You can only select " + e + " item" + (1 == e ? "" : "s")
        }, formatLoadMore: function () {
            return"Loading more results..."
        }, formatSearching: function () {
            return"Searching..."
        }, minimumResultsForSearch: 0, minimumInputLength: 0, maximumInputLength: null, maximumSelectionSize: 0, id: function (e) {
            return e.id
        }, matcher: function (e, t) {
            return i("" + t).toUpperCase().indexOf(i("" + e).toUpperCase()) >= 0
        }, separator: ",", tokenSeparators: [], tokenizer: S, escapeMarkup: x, blurOnChange: !1, selectOnBlur: !1, adaptContainerCssClass: function (e) {
            return e
        }, adaptDropdownCssClass: function () {
            return null
        }, nextSearchTerm: function () {
            return t
        }}, e.fn.select2.ajaxDefaults = {transport: e.ajax, params: {type: "GET", cache: !1, dataType: "json"}}, window.Select2 = {query: {ajax: w, local: C, tags: _}, util: {debounce: h, markMatch: b, escapeMarkup: x, stripDiacritics: i}, "class": {"abstract": I, single: R, multi: N}}
    }
}(jQuery);
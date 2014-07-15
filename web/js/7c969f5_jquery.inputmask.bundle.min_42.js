/*
 Input Mask plugin for jquery
 http://github.com/RobinHerbots/jquery.inputmask
 Copyright (c) 2010 - 2013 Robin Herbots
 Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php)
 Version: 2.2.2
 */
(function (f) {
    void 0 == f.fn.inputmask && (f.inputmask = {defaults: {placeholder: "_", optionalmarker: {start: "[", end: "]"}, escapeChar: "\\", mask: null, oncomplete: f.noop, onincomplete: f.noop, oncleared: f.noop, repeat: 0, greedy: !0, autoUnmask: !1, clearMaskOnLostFocus: !0, insertMode: !0, clearIncomplete: !1, aliases: {}, onKeyUp: f.noop, onKeyDown: f.noop, showMaskOnFocus: !0, showMaskOnHover: !0, onKeyValidation: f.noop, skipOptionalPartCharacter: " ", numericInput: !1, radixPoint: "", rightAlignNumerics: !0, definitions: {9: {validator: "[0-9]",
        cardinality: 1}, a: {validator: "[A-Za-z\u0410-\u044f\u0401\u0451]", cardinality: 1}, "*": {validator: "[A-Za-z\u0410-\u044f\u0401\u04510-9]", cardinality: 1}}, keyCode: {ALT: 18, BACKSPACE: 8, CAPS_LOCK: 20, COMMA: 188, COMMAND: 91, COMMAND_LEFT: 91, COMMAND_RIGHT: 93, CONTROL: 17, DELETE: 46, DOWN: 40, END: 35, ENTER: 13, ESCAPE: 27, HOME: 36, INSERT: 45, LEFT: 37, MENU: 93, NUMPAD_ADD: 107, NUMPAD_DECIMAL: 110, NUMPAD_DIVIDE: 111, NUMPAD_ENTER: 108, NUMPAD_MULTIPLY: 106, NUMPAD_SUBTRACT: 109, PAGE_DOWN: 34, PAGE_UP: 33, PERIOD: 190, RIGHT: 39, SHIFT: 16, SPACE: 32,
        TAB: 9, UP: 38, WINDOWS: 91}, ignorables: [9, 13, 19, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 46, 93, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123], getMaskLength: function (a, d, c) {
        var e = a.length;
        !d && 1 < c && (e += a.length * (c - 1));
        return e
    }}, val: f.fn.val, escapeRegex: function (a) {
        return a.replace(RegExp("(\\/|\\.|\\*|\\+|\\?|\\||\\(|\\)|\\[|\\]|\\{|\\}|\\\\)", "gim"), "\\$1")
    }}, f.fn.inputmask = function (a, d) {
        var c, e;

        function b(a, E) {
            var c = h.aliases[a];
            return c ? (c.alias && b(c.alias), f.extend(!0, h, c), f.extend(!0, h, E), !0) : !1
        }

        function j(a) {
            var b =
                !1, c = 0, d = h.greedy, e = h.repeat;
            1 == a.length && !1 == d && (h.placeholder = "");
            for (var a = f.map(a.split(""), function (a) {
                var s = [];
                if (a == h.escapeChar)b = true; else if (a != h.optionalmarker.start && a != h.optionalmarker.end || b) {
                    var d = h.definitions[a];
                    if (d && !b)for (a = 0; a < d.cardinality; a++)s.push(G(c + a)); else {
                        s.push(a);
                        b = false
                    }
                    c = c + s.length;
                    return s
                }
            }), j = a.slice(), y = 1; y < e && d; y++)j = j.concat(a.slice());
            return{mask: j, repeat: e, greedy: d}
        }

        function l(a) {
            var b = !1, c = !1, d = !1;
            return f.map(a.split(""), function (a) {
                var s = [];
                if (a == h.escapeChar)c = !0; else if (a == h.optionalmarker.start && !c)d = b = !0; else if (a == h.optionalmarker.end && !c)b = !1, d = !0; else {
                    var e = h.definitions[a];
                    if (e && !c) {
                        for (var f = e.prevalidator, j = f ? f.length : 0, l = 1; l < e.cardinality; l++) {
                            var g = j >= l ? f[l - 1] : [], k = g.validator, g = g.cardinality;
                            s.push({fn: k ? "string" == typeof k ? RegExp(k) : new function () {
                                this.test = k
                            } : /./, cardinality: g ? g : 1, optionality: b, newBlockMarker: !0 == b ? d : !1, offset: 0, casing: e.casing, def: e.definitionSymbol | a});
                            !0 == b && (d = !1)
                        }
                        s.push({fn: e.validator ? "string" == typeof e.validator ? RegExp(e.validator) :
                            new function () {
                                this.test = e.validator
                            } : /./, cardinality: e.cardinality, optionality: b, newBlockMarker: d, offset: 0, casing: e.casing, def: e.definitionSymbol | a})
                    } else s.push({fn: null, cardinality: 0, optionality: b, newBlockMarker: d, offset: 0, casing: null, def: a}), c = !1;
                    d = !1;
                    return s
                }
            })
        }

        function u() {
            function a(c, d) {
                var e = 0, f = 0, y = d.length;
                for (i = 0; i < y && !(d.charAt(i) == h.optionalmarker.start && e++, d.charAt(i) == h.optionalmarker.end && f++, 0 < e && e == f); i++);
                e = [d.substring(0, i)];
                i < y && e.push(d.substring(i + 1, y));
                var m, f = e[0];
                m = f.length;
                for (i = 0; i < m && f.charAt(i) != h.optionalmarker.start; i++);
                y = [f.substring(0, i)];
                i < m && y.push(f.substring(i + 1, m));
                1 < y.length ? (f = c + y[0] + (h.optionalmarker.start + y[1] + h.optionalmarker.end) + (1 < e.length ? e[1] : ""), m = j(f), b.push({mask: f, _buffer: m.mask, tests: l(f), lastValidPosition: void 0, greedy: m.greedy, repeat: m.repeat}), f = c + y[0] + (1 < e.length ? e[1] : ""), m = j(f), b.push({mask: f, _buffer: m.mask, tests: l(f), lastValidPosition: void 0, greedy: m.greedy, repeat: m.repeat}), 1 < e.length && 1 < e[1].split(h.optionalmarker.start).length &&
                    (a(c + y[0] + (h.optionalmarker.start + y[1] + h.optionalmarker.end), e[1]), a(c + y[0], e[1]))) : (f = c + e, m = j(f), b.push({mask: f, _buffer: m.mask, tests: l(f), lastValidPosition: void 0, greedy: m.greedy, repeat: m.repeat}))
            }

            var b = [];
            f.isArray(h.mask) ? f.each(h.mask, function (b, c) {
                a("", c.toString())
            }) : a("", h.mask.toString());
            return b
        }

        function z() {
            return q[o].tests
        }

        function k() {
            return q[o]._buffer
        }

        function I(a, b, c, e, d) {
            function j(a, g) {
                for (var d = F(a), f = b ? 1 : 0, s = "", l = g.tests[d].cardinality; l > f; l--)s += D(c, d - (l - 1));
                b && (s += b);
                return null !=
                    g.tests[d].fn ? g.tests[d].fn.test(s, c, a, e, h) : !1
            }

            if (e)return j(a, q[o]);
            var l = [], m = !1, k = o;
            f.each(q, function (e) {
                o = e;
                var g = a;
                if (k != o && !B(a)) {
                    if (b == this._buffer[g] || b == h.skipOptionalPartCharacter)return l[e] = {refresh: !0}, this.lastValidPosition = g, !1;
                    g = d ? M(c, a) : A(c, a)
                }
                if ((void 0 == this.lastValidPosition || d || h.numericInput ? this.lastValidPosition <= h.numericInput ? r(c) : A(c, g) : this.lastValidPosition >= M(c, g)) && 0 <= g && g < r(c))l[e] = j(g, this), !1 !== l[e] ? (!0 === l[e] && (l[e] = {pos: g}), this.lastValidPosition = l[e].pos || g) : this.lastValidPosition =
                    d ? a == r(c) ? void 0 : A(c, a) : 0 == a ? void 0 : M(c, a)
            });
            o = k;
            T(c, a, k, d);
            m = l[o] || m;
            setTimeout(function () {
                h.onKeyValidation.call(this, m, h)
            }, 0);
            return m
        }

        function T(a, b, c, e) {
            f.each(q, function (d) {
                if (this.lastValidPosition && (e || h.numericInput) ? this.lastValidPosition <= b : this.lastValidPosition >= b) {
                    o = d;
                    if (o != c) {
                        var d = r(a), f = k();
                        if (e || h.numericInput)a.reverse(), f.reverse();
                        for (var j = a.length = b; j < d; j++) {
                            var l = F(j);
                            J(a, j, D(f, l))
                        }
                        e && a.reverse()
                    }
                    return!1
                }
            })
        }

        function B(a) {
            a = F(a);
            a = z()[a];
            return void 0 != a ? a.fn : !1
        }

        function F(a) {
            return a %
                z().length
        }

        function G(a) {
            return h.placeholder.charAt(a % h.placeholder.length)
        }

        function r(a) {
            return h.getMaskLength(k(), q[o].greedy, q[o].repeat, a, h)
        }

        function A(a, b) {
            var c = r(a);
            if (b >= c)return c;
            for (var e = b; ++e < c && !B(e););
            return e
        }

        function M(a, b) {
            var c = b;
            if (0 >= c)return 0;
            for (; 0 < --c && !B(c););
            return c
        }

        function J(a, b, c) {
            var e = z()[F(b)], d = c;
            if (void 0 != d)switch (e.casing) {
                case "upper":
                    d = c.toUpperCase();
                    break;
                case "lower":
                    d = c.toLowerCase()
            }
            a[b] = d
        }

        function D(a, b, c) {
            c && (b = W(a, b));
            return a[b]
        }

        function W(a, b, c) {
            if (c)for (; 0 >
                             b && a.length < r(a);) {
                c = k().length - 1;
                for (b = k().length; void 0 !== k()[c];)a.unshift(k()[c--])
            } else for (; void 0 == a[b] && a.length < r(a);)for (c = 0; void 0 !== k()[c];)a.push(k()[c++]);
            return b
        }

        function H(a, b, c) {
            a._valueSet(b.join(""));
            void 0 != c && p(a, c)
        }

        function X(a, b, c) {
            for (var e = r(a); b < c && b < e; b++)J(a, b, D(k().slice(), b))
        }

        function O(a, b) {
            var c = F(b);
            J(a, b, D(k(), c))
        }

        function C(a, b, c, e) {
            var d = f(a).data("inputmask").isRTL, j = U(a._valueGet(), d).split(""), l = r(b);
            if (d) {
                var m = j.reverse();
                m.length = l;
                for (var x = 0; x < l; x++) {
                    var t =
                        F(l - (x + 1));
                    null == z()[t].fn && m[x] != D(k(), t) ? (m.splice(x, 0, D(k(), t)), m.length = l) : m[x] = m[x] || D(k(), t)
                }
                j = m.reverse()
            }
            X(b, 0, b.length);
            b.length = k().length;
            for (var g = m = -1, p, C = j.length, t = 0 == C ? l : -1, x = 0; x < C; x++)for (var v = g + 1; v < l; v++)if (B(v)) {
                var u = j[x];
                !1 !== (p = I(v, u, b, !c, d)) ? (!0 !== p && (v = void 0 != p.pos ? p.pos : v, u = void 0 != p.c ? p.c : u), J(b, v, u), m = g = v) : (O(b, v), u == G(v) && (t = g = v));
                break
            } else if (O(b, v), m == g && (m = v), g = v, j[x] == D(b, v))break;
            if (!1 == q[o].greedy) {
                x = U(b.join(""), d).split("");
                j = x.length;
                for (g = 0; g < j; g++)b[g] = x[g];
                b.length = x.length
            }
            c && H(a, b);
            return d ? h.numericInput ? "" != h.radixPoint && -1 != f.inArray(h.radixPoint, b) && !0 !== e ? f.inArray(h.radixPoint, b) : A(b, l) : A(b, t) : A(b, m)
        }

        function R(a) {
            return f.inputmask.escapeRegex.call(this, a)
        }

        function U(a, b) {
            return b ? a.replace(RegExp("^(" + R(k().join("")) + ")*"), "") : a.replace(RegExp("(" + R(k().join("")) + ")*$"), "")
        }

        function Y(a, b) {
            C(a, b, !1);
            var c = b.slice(), e, d;
            if (f(a).data("inputmask").isRTL)for (d = 0; d <= c.length - 1; d++)if (e = F(d), z()[e].optionality)if (!B(d) || !I(d, b[d], b, !0))c.splice(0,
                1); else break; else break; else for (d = c.length - 1; 0 <= d; d--)if (e = F(d), z()[e].optionality)if (!B(d) || !I(d, b[d], b, !0))c.pop(); else break; else break;
            H(a, c)
        }

        function Z(a, b) {
            var c = a[0];
            if (z() && (!0 === b || !a.hasClass("hasDatepicker"))) {
                var d = k().slice();
                C(c, d);
                return f.map(d,function (a, b) {
                    return B(b) && I(b, a, d, !0) ? a : null
                }).join("")
            }
            return c._valueGet()
        }

        function p(a, b, d) {
            var j = a.jquery && 0 < a.length ? a[0] : a;
            if ("number" == typeof b)f(a).is(":visible") && (d = "number" == typeof d ? d : b, !1 == h.insertMode && b == d && d++, j.setSelectionRange ?
                V ? (setTimeout(function () {
                    j.selectionStart = b;
                    j.selectionEnd = V ? b : d
                }, 10), c = b, e = d) : (j.selectionStart = b, j.selectionEnd = d) : j.createTextRange && (range = j.createTextRange(), range.collapse(!0), range.moveEnd("character", d), range.moveStart("character", b), range.select())); else {
                if (!f(a).is(":visible"))return{begin: 0, end: 0};
                j.setSelectionRange ? (b = j.selectionStart, d = j.selectionEnd) : document.selection && document.selection.createRange && (range = document.selection.createRange(), b = 0 - range.duplicate().moveStart("character",
                    -1E5), d = b + range.text.length);
                return{begin: b, end: d}
            }
        }

        function S(a) {
            var b = !1, c = 0, d = o;
            f.each(q, function (d, e) {
                o = d;
                var f = r(a);
                if (e.lastValidPosition && e.lastValidPosition >= c && e.lastValidPosition == f - 1) {
                    for (var j = !0, h = 0; h < f; h++) {
                        var l = B(h);
                        if (l && a[h] == G(h) || !l && a[h] != k()[h]) {
                            j = !1;
                            break
                        }
                    }
                    if (b = b || j)return!1
                }
                c = e.lastValidPosition
            });
            o = d;
            return b
        }

        function P(a) {
            function b(a) {
                a = f._data(a).events;
                f.each(a, function (a, b) {
                    f.each(b, function (a, b) {
                        if ("inputmask" == b.namespace) {
                            var c = b.handler;
                            b.handler = function () {
                                return this.readOnly ||
                                    this.disabled ? !1 : c.apply(this, arguments)
                            }
                        }
                    })
                })
            }

            function d(a) {
                var b;
                Object.getOwnPropertyDescriptor && (b = Object.getOwnPropertyDescriptor(a, "value"));
                if (b && b.get)a._valueGet || (a._valueGet = b.get, a._valueSet = b.set, Object.defineProperty(a, "value", {get: function () {
                    var a = f(this), b = f(this).data("inputmask"), c = b.masksets, d = b.activeMasksetIndex;
                    return b && b.autoUnmask ? a.inputmask("unmaskedvalue") : this._valueGet() != c[d]._buffer.join("") ? this._valueGet() : ""
                }, set: function (a) {
                    this._valueSet(a);
                    f(this).triggerHandler("setvalue.inputmask")
                }}));
                else if (document.__lookupGetter__ && a.__lookupGetter__("value"))a._valueGet || (a._valueGet = a.__lookupGetter__("value"), a._valueSet = a.__lookupSetter__("value"), a.__defineGetter__("value", function () {
                    var a = f(this), b = f(this).data("inputmask"), c = b.masksets, d = b.activeMasksetIndex;
                    return b && b.autoUnmask ? a.inputmask("unmaskedvalue") : this._valueGet() != c[d]._buffer.join("") ? this._valueGet() : ""
                }), a.__defineSetter__("value", function (a) {
                    this._valueSet(a);
                    f(this).triggerHandler("setvalue.inputmask")
                })); else if (a._valueGet ||
                    (a._valueGet = function () {
                        return this.value
                    }, a._valueSet = function (a) {
                        this.value = a
                    }), !0 != f.fn.val.inputmaskpatch)f.fn.val = function () {
                    if (arguments.length == 0) {
                        var a = f(this);
                        if (a.data("inputmask")) {
                            if (a.data("inputmask").autoUnmask)return a.inputmask("unmaskedvalue");
                            var a = f.inputmask.val.apply(a), b = f(this).data("inputmask");
                            return a != b.masksets[b.activeMasksetIndex]._buffer.join("") ? a : ""
                        }
                        return f.inputmask.val.apply(a)
                    }
                    var c = arguments;
                    return this.each(function () {
                        var a = f(this), b = f.inputmask.val.apply(a,
                            c);
                        a.data("inputmask") && a.triggerHandler("setvalue.inputmask");
                        return b
                    })
                }, f.extend(f.fn.val, {inputmaskpatch: !0})
            }

            function j(a, b) {
                if (h.numericInput && "" != h.radixPoint) {
                    var c = a._valueGet().indexOf(h.radixPoint);
                    w = b.begin <= c || b.end <= c || -1 == c
                }
            }

            function l(a, b, c) {
                for (; !B(a) && 0 <= a - 1;)a--;
                for (var d = a; d < b && d < r(g); d++)if (B(d)) {
                    O(g, d);
                    var e = A(g, d), j = D(g, e);
                    if (j != G(e))if (e < r(g) && !1 !== I(d, j, g, !0, w) && z()[F(d)].def == z()[F(e)].def)J(g, d, D(g, e)), O(g, e); else {
                        if (B(d))break
                    } else if (void 0 == c)break
                } else O(g, d);
                void 0 !=
                    c && J(g, w ? b : M(g, b), c);
                g = U(g.join(""), w).split("");
                0 == g.length && (g = k().slice());
                return a
            }

            function u(a, b, c, d) {
                for (; a <= b && a < r(g); a++)if (B(a)) {
                    var e = D(g, a);
                    J(g, a, c);
                    if (e != G(a))if (c = A(g, a), c < r(g))if (!1 !== I(c, e, g, !0, w) && z()[F(a)].def == z()[F(c)].def)c = e; else if (B(c))break; else c = e; else break; else if (!0 !== d)break
                } else O(g, a);
                d = g.length;
                g = U(g.join(""), w).split("");
                0 == g.length && (g = k().slice());
                return b - (d - g.length)
            }

            function y(a) {
                v = !1;
                var b = this, c = a.keyCode, d = p(b);
                j(b, d);
                if (c == h.keyCode.BACKSPACE || c == h.keyCode.DELETE ||
                    aa && 127 == c) {
                    var e = r(g);
                    if (0 == d.begin && d.end == e)o = 0, g = k().slice(), H(b, g), p(b, C(b, g, !1)); else if (1 < d.end - d.begin || 1 == d.end - d.begin && h.insertMode)X(g, d.begin, d.end), T(g, d.begin, o), H(b, g, w ? C(b, g, !1) : d.begin); else {
                        var n = $ ? d.end : d.begin;
                        c == h.keyCode.DELETE ? (n < K && (n = K), n < e && (h.numericInput && "" != h.radixPoint && g[n] == h.radixPoint ? (n = g.length - 1 == n ? n : A(g, n), n = l(n, e)) : w ? (n = u(K, n, G(n), !0), n = A(g, n)) : n = l(n, e), T(g, n, o), H(b, g, n))) : c == h.keyCode.BACKSPACE && n > K && (n -= 1, h.numericInput && "" != h.radixPoint && g[n] == h.radixPoint ?
                            (n = u(K, g.length - 1 == n ? n : n - 1, G(n), !0), n++) : w ? (n = u(K, n, G(n), !0), n = g[n + 1] == h.radixPoint ? n + 1 : A(g, n)) : n = l(n, e), T(g, n, o), H(b, g, n))
                    }
                    b._valueGet() == k().join("") && f(b).trigger("cleared");
                    a.preventDefault()
                } else c == h.keyCode.END || c == h.keyCode.PAGE_DOWN ? setTimeout(function () {
                    var c = C(b, g, !1, !0);
                    !h.insertMode && (c == r(g) && !a.shiftKey) && c--;
                    p(b, a.shiftKey ? d.begin : c, c)
                }, 0) : c == h.keyCode.HOME && !a.shiftKey || c == h.keyCode.PAGE_UP ? p(b, 0, a.shiftKey ? d.begin : 0) : c == h.keyCode.ESCAPE ? (b._valueSet(Q), p(b, 0, C(b, g))) : c == h.keyCode.INSERT ?
                    (h.insertMode = !h.insertMode, p(b, !h.insertMode && d.begin == r(g) ? d.begin - 1 : d.begin)) : a.ctrlKey && 88 == c ? setTimeout(function () {
                    p(b, C(b, g, !0))
                }, 0) : h.insertMode || (c == h.keyCode.RIGHT ? (e = d.begin == d.end ? d.end + 1 : d.end, e = e < r(g) ? e : d.end, p(b, a.shiftKey ? d.begin : e, a.shiftKey ? e + 1 : e)) : c == h.keyCode.LEFT && (e = d.begin - 1, e = 0 < e ? e : 0, p(b, e, a.shiftKey ? d.end : e)));
                h.onKeyDown.call(this, a, g, h);
                P = -1 != f.inArray(c, h.ignorables)
            }

            function m(a) {
                if (v)return!1;
                v = !0;
                var b = f(this), a = a || window.event, d = a.which || a.charCode || a.keyCode, j = String.fromCharCode(d);
                if (h.numericInput && j == h.radixPoint) {
                    var k = this._valueGet().indexOf(h.radixPoint);
                    p(this, A(g, -1 != k ? k : r(g)))
                }
                if (a.metaKey || P)return!0;
                if (d) {
                    var n = p(this), s = r(g), d = !0;
                    X(g, n.begin, n.end);
                    if (w) {
                        var k = M(g, n.end), m;
                        if (!1 !== (m = I(k == s || D(g, k) == h.radixPoint ? M(g, k) : k, j, g, !1, w))) {
                            var E = !1;
                            !0 !== m && (E = m.refresh, k = void 0 != m.pos ? m.pos : k, j = void 0 != m.c ? m.c : j);
                            if (!0 !== E)if (s = r(g), m = K, !0 == h.insertMode) {
                                if (!0 == q[o].greedy)for (E = g.slice(); D(E, m, !0) != G(m) && m <= k;)m = m == s ? s + 1 : A(g, m);
                                m <= k && (q[o].greedy || g.length < s) ? (g[K] !=
                                    G(K) && g.length < s && (E = W(g, -1, w), 0 != n.end && (k += E), s = g.length), l(m, k, j)) : d = !1
                            } else J(g, k, j);
                            d && (H(this, g, h.numericInput ? k + 1 : k), setTimeout(function () {
                                S(g) && b.trigger("complete")
                            }, 0))
                        }
                    } else if (k = A(g, n.begin - 1), W(g, k, w), !1 !== (m = I(k, j, g, !1, w))) {
                        E = !1;
                        !0 !== m && (E = m.refresh, k = void 0 != m.pos ? m.pos : k, j = void 0 != m.c ? m.c : j);
                        if (!0 !== E)if (!0 == h.insertMode) {
                            n = r(g);
                            for (E = g.slice(); D(E, n, !0) != G(n) && n >= k;)n = 0 == n ? -1 : M(g, n);
                            n >= k ? u(k, g.length, j) : d = !1
                        } else J(g, k, j);
                        d && (j = A(g, k), H(this, g, j), setTimeout(function () {
                                S(g) && b.trigger("complete")
                            },
                            0))
                    }
                    V && p(this, c, e);
                    a.preventDefault()
                }
            }

            function x(a) {
                var b = f(this), c = a.keyCode;
                h.onKeyUp.call(this, a, g, h);
                c == h.keyCode.TAB && (b.hasClass("focus.inputmask") && 0 == this._valueGet().length && h.showMaskOnFocus) && (g = k().slice(), H(this, g), w || p(this, 0), Q = this._valueGet())
            }

            var t = f(a);
            if (t.is(":input")) {
                var g = k().slice();
                q[o].greedy = q[o].greedy ? q[o].greedy : 0 == q[o].repeat;
                var L = t.prop("maxLength");
                r(g) > L && -1 < L && (L < k().length && (k().length = L), !1 == q[o].greedy && (q[o].repeat = Math.round(L / k().length)), t.prop("maxLength",
                    2 * r(g)));
                t.data("inputmask", {masksets: q, activeMasksetIndex: o, autoUnmask: h.autoUnmask, definitions: h.definitions, isRTL: !1});
                d(a);
                var g = k().slice(), Q = a._valueGet(), v = !1, P = !1, N = -1, K = A(g, -1);
                M(g, r(g));
                var w = !1;
                if ("rtl" == a.dir || h.numericInput)("rtl" == a.dir || h.numericInput && h.rightAlignNumerics) && t.css("text-align", "right"), a.dir = "ltr", t.removeAttr("dir"), L = t.data("inputmask"), L.isRTL = !0, t.data("inputmask", L), w = !0;
                t.unbind(".inputmask");
                t.removeClass("focus.inputmask");
                t.bind("mouseenter.inputmask",function () {
                    if (!f(this).hasClass("focus.inputmask") &&
                        h.showMaskOnHover) {
                        var a = this._valueGet().length;
                        if (a < g.length) {
                            a == 0 && (g = k().slice());
                            H(this, g)
                        }
                    }
                }).bind("blur.inputmask",function () {
                        var a = f(this), b = this._valueGet();
                        a.removeClass("focus.inputmask");
                        b != Q && a.change();
                        h.clearMaskOnLostFocus && b != "" && (b == k().join("") ? this._valueSet("") : Y(this, g));
                        if (!S(g)) {
                            a.trigger("incomplete");
                            if (h.clearIncomplete)if (h.clearMaskOnLostFocus)this._valueSet(""); else {
                                g = k().slice();
                                H(this, g)
                            }
                        }
                    }).bind("focus.inputmask",function () {
                        var a = f(this), b = this._valueGet();
                        if (h.showMaskOnFocus && !a.hasClass("focus.inputmask") && (!h.showMaskOnHover || h.showMaskOnHover && b == "")) {
                            b = b.length;
                            if (b < g.length) {
                                b == 0 && (g = k().slice());
                                p(this, C(this, g, true))
                            }
                        }
                        a.addClass("focus.inputmask");
                        Q = this._valueGet()
                    }).bind("mouseleave.inputmask",function () {
                        var a = f(this);
                        h.clearMaskOnLostFocus && (a.hasClass("focus.inputmask") || (this._valueGet() == k().join("") || this._valueGet() == "" ? this._valueSet("") : Y(this, g)))
                    }).bind("click.inputmask",function () {
                        var a = this;
                        setTimeout(function () {
                            var b = p(a);
                            if (b.begin == b.end) {
                                var c =
                                    b.begin;
                                N = C(a, g, false);
                                j(a, b);
                                w ? p(a, c > N && (I(c, g[c], g, true, w) !== false || !B(c)) ? c : N) : p(a, c < N && (I(c, g[c], g, true, w) !== false || !B(c)) ? c : N)
                            }
                        }, 0)
                    }).bind("dblclick.inputmask",function () {
                        var a = this;
                        setTimeout(function () {
                            p(a, 0, N)
                        }, 0)
                    }).bind("keydown.inputmask", y).bind("keypress.inputmask", m).bind("keyup.inputmask", x).bind(ba + ".inputmask dragdrop.inputmask drop.inputmask",function () {
                        var a = this;
                        setTimeout(function () {
                            p(a, C(a, g, true));
                            S(g) && t.trigger("complete")
                        }, 0)
                    }).bind("setvalue.inputmask",function () {
                        Q = this._valueGet();
                        C(this, g, true);
                        this._valueGet() == k().join("") && this._valueSet("")
                    }).bind("complete.inputmask", h.oncomplete).bind("incomplete.inputmask", h.onincomplete).bind("cleared.inputmask", h.oncleared);
                var N = C(a, g, !0), R;
                try {
                    R = document.activeElement
                } catch (Z) {
                }
                R === a ? (t.addClass("focus.inputmask"), p(a, N)) : h.clearMaskOnLostFocus && (a._valueGet() == k().join("") ? a._valueSet("") : Y(a, g));
                b(a)
            }
        }

        var h = f.extend(!0, {}, f.inputmask.defaults, d), ba = function (a) {
            var b = document.createElement("input"), a = "on" + a, c = a in b;
            c || (b.setAttribute(a,
                "return;"), c = "function" == typeof b[a]);
            return c
        }("paste") ? "paste" : "input", aa = null != navigator.userAgent.match(/iphone/i), V = null != navigator.userAgent.match(/android.*safari.*/i), $;
        if (V) {
            var ca = navigator.userAgent.match(/safari.*/i);
            $ = 533 >= parseInt(RegExp(/[0-9]+/).exec(ca))
        }
        var q, o = 0;
        if ("string" == typeof a)switch (a) {
            case "mask":
                return b(h.alias, d), q = u(), this.each(function () {
                    P(this)
                });
            case "unmaskedvalue":
                return q = this.data("inputmask").masksets, o = this.data("inputmask").activeMasksetIndex, h.definitions =
                    this.data("inputmask").definitions, Z(this);
            case "remove":
                return this.each(function () {
                    var a = f(this), b = this;
                    setTimeout(function () {
                        if (a.data("inputmask")) {
                            q = a.data("inputmask").masksets;
                            o = a.data("inputmask").activeMasksetIndex;
                            h.definitions = a.data("inputmask").definitions;
                            b._valueSet(Z(a, !0));
                            a.removeData("inputmask");
                            a.unbind(".inputmask");
                            a.removeClass("focus.inputmask");
                            var c;
                            Object.getOwnPropertyDescriptor && (c = Object.getOwnPropertyDescriptor(b, "value"));
                            c && c.get ? b._valueGet && Object.defineProperty(b,
                                "value", {get: b._valueGet, set: b._valueSet}) : document.__lookupGetter__ && b.__lookupGetter__("value") && b._valueGet && (b.__defineGetter__("value", b._valueGet), b.__defineSetter__("value", b._valueSet));
                            delete b._valueGet;
                            delete b._valueSet
                        }
                    }, 0)
                });
            case "getemptymask":
                return this.data("inputmask") ? (q = this.data("inputmask").masksets, o = this.data("inputmask").activeMasksetIndex, q[o]._buffer.join("")) : "";
            case "hasMaskedValue":
                return this.data("inputmask") ? !this.data("inputmask").autoUnmask : !1;
            case "isComplete":
                return q =
                    this.data("inputmask").masksets, o = this.data("inputmask").activeMasksetIndex, h.definitions = this.data("inputmask").definitions, S(this[0].split(""));
            default:
                return b(a, d) || (h.mask = a), q = u(), this.each(function () {
                    P(this)
                })
        } else {
            if ("object" == typeof a)return h = f.extend(!0, {}, f.inputmask.defaults, a), b(h.alias, a), q = u(), this.each(function () {
                P(this)
            });
            if (void 0 == a)return this.each(function () {
                var a = f(this).attr("data-inputmask");
                if (a && "" != a)try {
                    var a = a.replace(RegExp("'", "g"), '"'), c = f.parseJSON("{" + a + "}");
                    h = f.extend(!0,
                        {}, f.inputmask.defaults, c);
                    b(h.alias, c);
                    h.alias = void 0;
                    f(this).inputmask(h)
                } catch (d) {
                }
            })
        }
        return this
    })
})(jQuery);
(function (f) {
    f.extend(f.inputmask.defaults.definitions, {A: {validator: "[A-Za-z]", cardinality: 1, casing: "upper"}, "#": {validator: "[A-Za-z\u0410-\u044f\u0401\u04510-9]", cardinality: 1, casing: "upper"}});
    f.extend(f.inputmask.defaults.aliases, {url: {mask: "ir", placeholder: "", separator: "", defaultPrefix: "http://", regex: {urlpre1: /[fh]/, urlpre2: /(ft|ht)/, urlpre3: /(ftp|htt)/, urlpre4: /(ftp:|http|ftps)/, urlpre5: /(ftp:\/|ftps:|http:|https)/, urlpre6: /(ftp:\/\/|ftps:\/|http:\/|https:)/, urlpre7: /(ftp:\/\/|ftps:\/\/|http:\/\/|https:\/)/,
        urlpre8: /(ftp:\/\/|ftps:\/\/|http:\/\/|https:\/\/)/}, definitions: {i: {validator: function () {
        return!0
    }, cardinality: 8, prevalidator: function () {
        for (var a = [], d = 0; 8 > d; d++)a[d] = function () {
            var a = d;
            return{validator: function (d, b, j, f, u) {
                if (u.regex["urlpre" + (a + 1)]) {
                    var z = d;
                    0 < a + 1 - d.length && (z = b.join("").substring(0, a + 1 - d.length) + "" + z);
                    d = u.regex["urlpre" + (a + 1)].test(z);
                    if (!f && !d) {
                        j -= a;
                        for (f = 0; f < u.defaultPrefix.length; f++)b[j] = u.defaultPrefix[f], j++;
                        for (f = 0; f < z.length - 1; f++)b[j] = z[f], j++;
                        return{pos: j}
                    }
                    return d
                }
                return!1
            },
                cardinality: a}
        }();
        return a
    }()}}, insertMode: !1, autoUnmask: !1}, ip: {mask: "i.i.i.i", definitions: {i: {validator: "25[0-5]|2[0-4][0-9]|[01][0-9][0-9]", cardinality: 3, prevalidator: [
        {validator: "[0-2]", cardinality: 1},
        {validator: "2[0-5]|[01][0-9]", cardinality: 2}
    ]}}}})
})(jQuery);
(function (f) {
    f.extend(f.inputmask.defaults.definitions, {h: {validator: "[01][0-9]|2[0-3]", cardinality: 2, prevalidator: [
        {validator: "[0-2]", cardinality: 1}
    ]}, s: {validator: "[0-5][0-9]", cardinality: 2, prevalidator: [
        {validator: "[0-5]", cardinality: 1}
    ]}, d: {validator: "0[1-9]|[12][0-9]|3[01]", cardinality: 2, prevalidator: [
        {validator: "[0-3]", cardinality: 1}
    ]}, m: {validator: "0[1-9]|1[012]", cardinality: 2, prevalidator: [
        {validator: "[01]", cardinality: 1}
    ]}, y: {validator: "(19|20)\\d{2}", cardinality: 4, prevalidator: [
        {validator: "[12]",
            cardinality: 1},
        {validator: "(19|20)", cardinality: 2},
        {validator: "(19|20)\\d", cardinality: 3}
    ]}});
    f.extend(f.inputmask.defaults.aliases, {"dd/mm/yyyy": {mask: "1/2/y", placeholder: "dd/mm/yyyy", regex: {val1pre: /[0-3]/, val1: /0[1-9]|[12][0-9]|3[01]/, val2pre: function (a) {
        a = f.inputmask.escapeRegex.call(this, a);
        return RegExp("((0[1-9]|[12][0-9]|3[01])" + a + "[01])")
    }, val2: function (a) {
        a = f.inputmask.escapeRegex.call(this, a);
        return RegExp("((0[1-9]|[12][0-9])" + a + "(0[1-9]|1[012]))|(30" + a + "(0[13-9]|1[012]))|(31" + a + "(0[13578]|1[02]))")
    }},
        leapday: "29/02/", separator: "/", yearrange: {minyear: 1900, maxyear: 2099}, isInYearRange: function (a, d, c) {
            var e = parseInt(a.concat(d.toString().slice(a.length))), a = parseInt(a.concat(c.toString().slice(a.length)));
            return(NaN != e ? d <= e && e <= c : !1) || (NaN != a ? d <= a && a <= c : !1)
        }, determinebaseyear: function (a, d) {
            var c = (new Date).getFullYear();
            return a > c ? a : d < c ? d : c
        }, onKeyUp: function (a, d, c) {
            d = f(this);
            a.ctrlKey && a.keyCode == c.keyCode.RIGHT && (a = new Date, d.val(a.getDate().toString() + (a.getMonth() + 1).toString() + a.getFullYear().toString()))
        },
        definitions: {1: {validator: function (a, d, c, e, b) {
            var j = b.regex.val1.test(a);
            if (!e && !j && (a.charAt(1) == b.separator || -1 != "-./".indexOf(a.charAt(1))))if (j = b.regex.val1.test("0" + a.charAt(0)))return d[c - 1] = "0", {pos: c, c: a.charAt(0)};
            return j
        }, cardinality: 2, prevalidator: [
            {validator: function (a, d, c, e, b) {
                var j = b.regex.val1pre.test(a);
                return!e && !j && (j = b.regex.val1.test("0" + a)) ? (d[c] = "0", c++, {pos: c}) : j
            }, cardinality: 1}
        ]}, 2: {validator: function (a, d, c, e, b) {
            var j = d.join("").substr(0, 3), f = b.regex.val2(b.separator).test(j +
                a);
            if (!e && !f && (a.charAt(1) == b.separator || -1 != "-./".indexOf(a.charAt(1))))if (f = b.regex.val2(b.separator).test(j + "0" + a.charAt(0)))return d[c - 1] = "0", {pos: c, c: a.charAt(0)};
            return f
        }, cardinality: 2, prevalidator: [
            {validator: function (a, d, c, e, b) {
                var j = d.join("").substr(0, 3), f = b.regex.val2pre(b.separator).test(j + a);
                return!e && !f && (f = b.regex.val2(b.separator).test(j + "0" + a)) ? (d[c] = "0", c++, {pos: c}) : f
            }, cardinality: 1}
        ]}, y: {validator: function (a, d, c, e, b) {
            if (b.isInYearRange(a, b.yearrange.minyear, b.yearrange.maxyear)) {
                if (d.join("").substr(0,
                    6) != b.leapday)return!0;
                a = parseInt(a, 10);
                return 0 === a % 4 ? 0 === a % 100 ? 0 === a % 400 ? !0 : !1 : !0 : !1
            }
            return!1
        }, cardinality: 4, prevalidator: [
            {validator: function (a, d, c, e, b) {
                var f = b.isInYearRange(a, b.yearrange.minyear, b.yearrange.maxyear);
                if (!e && !f) {
                    e = b.determinebaseyear(b.yearrange.minyear, b.yearrange.maxyear).toString().slice(0, 1);
                    if (f = b.isInYearRange(e + a, b.yearrange.minyear, b.yearrange.maxyear))return d[c++] = e[0], {pos: c};
                    e = b.determinebaseyear(b.yearrange.minyear, b.yearrange.maxyear).toString().slice(0, 2);
                    if (f = b.isInYearRange(e +
                        a, b.yearrange.minyear, b.yearrange.maxyear))return d[c++] = e[0], d[c++] = e[1], {pos: c}
                }
                return f
            }, cardinality: 1},
            {validator: function (a, d, c, e, b) {
                var f = b.isInYearRange(a, b.yearrange.minyear, b.yearrange.maxyear);
                if (!e && !f) {
                    e = b.determinebaseyear(b.yearrange.minyear, b.yearrange.maxyear).toString().slice(0, 2);
                    if (f = b.isInYearRange(a[0] + e[1] + a[1], b.yearrange.minyear, b.yearrange.maxyear))return d[c++] = e[1], {pos: c};
                    e = b.determinebaseyear(b.yearrange.minyear, b.yearrange.maxyear).toString().slice(0, 2);
                    b.isInYearRange(e +
                        a, b.yearrange.minyear, b.yearrange.maxyear) ? d.join("").substr(0, 6) != b.leapday ? f = !0 : (b = parseInt(a, 10), f = 0 === b % 4 ? 0 === b % 100 ? 0 === b % 400 ? !0 : !1 : !0 : !1) : f = !1;
                    if (f)return d[c - 1] = e[0], d[c++] = e[1], d[c++] = a[0], {pos: c}
                }
                return f
            }, cardinality: 2},
            {validator: function (a, d, c, e, b) {
                return b.isInYearRange(a, b.yearrange.minyear, b.yearrange.maxyear)
            }, cardinality: 3}
        ]}}, insertMode: !1, autoUnmask: !1}, "mm/dd/yyyy": {placeholder: "mm/dd/yyyy", alias: "dd/mm/yyyy", regex: {val2pre: function (a) {
        a = f.inputmask.escapeRegex.call(this, a);
        return RegExp("((0[13-9]|1[012])" +
            a + "[0-3])|(02" + a + "[0-2])")
    }, val2: function (a) {
        a = f.inputmask.escapeRegex.call(this, a);
        return RegExp("((0[1-9]|1[012])" + a + "(0[1-9]|[12][0-9]))|((0[13-9]|1[012])" + a + "30)|((0[13578]|1[02])" + a + "31)")
    }, val1pre: /[01]/, val1: /0[1-9]|1[012]/}, leapday: "02/29/", onKeyUp: function (a, d, c) {
        d = f(this);
        a.ctrlKey && a.keyCode == c.keyCode.RIGHT && (a = new Date, d.val((a.getMonth() + 1).toString() + a.getDate().toString() + a.getFullYear().toString()))
    }}, "yyyy/mm/dd": {mask: "y/1/2", placeholder: "yyyy/mm/dd", alias: "mm/dd/yyyy", leapday: "/02/29",
        onKeyUp: function (a, d, c) {
            d = f(this);
            a.ctrlKey && a.keyCode == c.keyCode.RIGHT && (a = new Date, d.val(a.getFullYear().toString() + (a.getMonth() + 1).toString() + a.getDate().toString()))
        }, definitions: {2: {validator: function (a, d, c, e, b) {
            var f = d.join("").substr(5, 3), l = b.regex.val2(b.separator).test(f + a);
            if (!e && !l && (a.charAt(1) == b.separator || -1 != "-./".indexOf(a.charAt(1))))if (l = b.regex.val2(b.separator).test(f + "0" + a.charAt(0)))return d[c - 1] = "0", {pos: c, c: a.charAt(0)};
            if (l) {
                if (d.join("").substr(4, 4) + a != b.leapday)return!0;
                a = parseInt(d.join("").substr(0, 4), 10);
                return 0 === a % 4 ? 0 === a % 100 ? 0 === a % 400 ? !0 : !1 : !0 : !1
            }
            return l
        }, cardinality: 2, prevalidator: [
            {validator: function (a, d, c, e, b) {
                var f = d.join("").substr(5, 3), l = b.regex.val2pre(b.separator).test(f + a);
                return!e && !l && (l = b.regex.val2(b.separator).test(f + "0" + a)) ? (d[c] = "0", c++, {pos: c}) : l
            }, cardinality: 1}
        ]}}}, "dd.mm.yyyy": {mask: "1.2.y", placeholder: "dd.mm.yyyy", leapday: "29.02.", separator: ".", alias: "dd/mm/yyyy"}, "dd-mm-yyyy": {mask: "1-2-y", placeholder: "dd-mm-yyyy", leapday: "29-02-", separator: "-",
        alias: "dd/mm/yyyy"}, "mm.dd.yyyy": {mask: "1.2.y", placeholder: "mm.dd.yyyy", leapday: "02.29.", separator: ".", alias: "mm/dd/yyyy"}, "mm-dd-yyyy": {mask: "1-2-y", placeholder: "mm-dd-yyyy", leapday: "02-29-", separator: "-", alias: "mm/dd/yyyy"}, "yyyy.mm.dd": {mask: "y.1.2", placeholder: "yyyy.mm.dd", leapday: ".02.29", separator: ".", alias: "yyyy/mm/dd"}, "yyyy-mm-dd": {mask: "y-1-2", placeholder: "yyyy-mm-dd", leapday: "-02-29", separator: "-", alias: "yyyy/mm/dd"}, datetime: {mask: "1/2/y h:s", placeholder: "dd/mm/yyyy hh:mm", alias: "dd/mm/yyyy",
        regex: {hrspre: /[012]/, hrs24: /2[0-9]|1[3-9]/, hrs: /[01][0-9]|2[0-3]/, ampmpre: /[apAP]/, ampm: /^[a|p|A|P][m|M]/}, timeseparator: ":", hourFormat: "24", definitions: {h: {validator: function (a, d, c, e, b) {
            var f = b.regex.hrs.test(a);
            if (!e && !f && (a.charAt(1) == b.timeseparator || -1 != "-.:".indexOf(a.charAt(1))))if (f = b.regex.hrs.test("0" + a.charAt(0)))return d[c - 1] = "0", d[c] = a.charAt(0), c++, {pos: c};
            return f && "24" !== b.hourFormat && b.regex.hrs24.test(a) ? (a = parseInt(a, 10), d[c + 5] = 24 == a ? "a" : "p", d[c + 6] = "m", a -= 12, 10 > a ? (d[c] = a.toString(),
                d[c - 1] = "0") : (d[c] = a.toString().charAt(1), d[c - 1] = a.toString().charAt(0)), {pos: c, c: d[c]}) : f
        }, cardinality: 2, prevalidator: [
            {validator: function (a, d, c, e, b) {
                var f = b.regex.hrspre.test(a);
                return!e && !f && (f = b.regex.hrs.test("0" + a)) ? (d[c] = "0", c++, {pos: c}) : f
            }, cardinality: 1}
        ]}, t: {validator: function (a, d, c, e, b) {
            var f = b.regex.ampm.test(a);
            return!e && !f && (f = b.regex.ampm.test(a + "m")) ? (d[c - 1] = a.charAt(0), d[c] = "m", c++, c) : f
        }, casing: "lower", cardinality: 2, prevalidator: [
            {validator: function (a, d, c, e, b) {
                if (e = b.regex.ampmpre.test(a))if (e =
                    b.regex.ampm.test(a + "m"))return d[c] = a, d[c + 1] = "m", c;
                return e
            }, cardinality: 1}
        ]}}, insertMode: !1, autoUnmask: !1}, datetime12: {mask: "1/2/y h:s t", placeholder: "dd/mm/yyyy hh:mm xm", alias: "datetime", hourFormat: "12"}, "hh:mm t": {mask: "h:s t", placeholder: "hh:mm xm", alias: "datetime", hourFormat: "12"}, "h:s t": {mask: "h:s t", placeholder: "hh:mm xm", alias: "datetime", hourFormat: "12"}, "hh:mm:ss": {mask: "h:s:s", autoUnmask: !1}, "hh:mm": {mask: "h:s", autoUnmask: !1}, date: {alias: "dd/mm/yyyy"}})
})(jQuery);
(function (f) {
    f.extend(f.inputmask.defaults.aliases, {decimal: {mask: "~", placeholder: "", repeat: 10, greedy: !1, numericInput: !0, digits: "*", groupSeparator: ",", radixPoint: ".", groupSize: 3, autoGroup: !1, getMaskLength: function (a, d, c, e, b) {
        var j = a.length;
        !d && 1 < c && (j += a.length * (c - 1));
        a = f.inputmask.escapeRegex.call(this, b.groupSeparator);
        b = f.inputmask.escapeRegex.call(this, b.radixPoint);
        e = e.join("");
        b = e.replace(RegExp(a, "g"), "").replace(RegExp(b), "");
        return j + (e.length - b.length)
    }, postFormat: function (a, d, c, e) {
        var b =
            a.slice();
        c || b.splice(d, 0, "?");
        b = b.join("");
        if (e.autoGroup || c && -1 != b.indexOf(e.groupSeparator)) {
            for (var b = b.replace(RegExp("\\" + e.groupSeparator, "g"), ""), j = b.split(e.radixPoint), b = j[0], l = RegExp("([-+]?[\\d?]+)([\\d?]{" + e.groupSize + "})"); l.test(b);)b = b.replace(l, "$1" + e.groupSeparator + "$2"), b = b.replace(e.groupSeparator + e.groupSeparator, e.groupSeparator);
            1 < j.length && (b += e.radixPoint + j[1])
        }
        a.length = b.length;
        e = 0;
        for (j = b.length; e < j; e++)a[e] = b.charAt(e);
        d = c ? d : f.inArray("?", a);
        c || a.splice(d, 1);
        return d
    },
        regex: {number: function (a, d, c, e) {
            a = f.inputmask.escapeRegex.call(this, a);
            c = f.inputmask.escapeRegex.call(this, c);
            e = isNaN(e) ? e : "{0," + e + "}";
            return RegExp("^[+-]?(\\d+|\\d{1," + d + "}((" + a + "\\d{" + d + "})?)+)(" + c + "\\d" + e + ")?$")
        }}, onKeyDown: function (a, d, c) {
            var e = f(this);
            if (a.keyCode == c.keyCode.TAB) {
                if (a = f.inArray(c.radixPoint, d), -1 != a) {
                    for (var b = e.data("inputmask").masksets, e = e.data("inputmask").activeMasksetIndex, j = 1; j <= c.digits && j < c.getMaskLength(b[e]._buffer, b[e].greedy, b[e].repeat, d, c); j++)void 0 == d[a + j] &&
                    (d[a + j] = "0");
                    this._valueSet(d.join(""))
                }
            } else if (a.keyCode == c.keyCode.DELETE || a.keyCode == c.keyCode.BACKSPACE)c.postFormat(d, 0, !0, c), this._valueSet(d.join(""))
        }, definitions: {"~": {validator: function (a, d, c, e, b) {
            if ("" == a)return!1;
            if (1 == c && "0" === d[0] && /[\d-]/.test(a))return d[0] = "", {pos: 0};
            var f = e ? d.slice(0, c) : d.slice();
            f.splice(c + 1, 0, a);
            f = f.join("");
            b.autoGroup && !e && (f = f.replace(RegExp("\\" + b.groupSeparator, "g"), ""));
            var l = b.regex.number(b.groupSeparator, b.groupSize, b.radixPoint, b.digits).test(f);
            if (!l &&
                (f += "0", l = b.regex.number(b.groupSeparator, b.groupSize, b.radixPoint, b.digits).test(f), !l)) {
                l = f.lastIndexOf(b.groupSeparator);
                for (i = f.length - l; 3 >= i; i++)f += "0";
                l = b.regex.number(b.groupSeparator, b.groupSize, b.radixPoint, b.digits).test(f);
                if (!l && !e && a == b.radixPoint && (l = b.regex.number(b.groupSeparator, b.groupSize, b.radixPoint, b.digits).test("0" + f + "0")))return d[c] = "0", c++, {pos: c}
            }
            return!1 != l && !e && a != b.radixPoint ? {pos: b.postFormat(d, c + 1, !1, b)} : l
        }, cardinality: 1, prevalidator: null}}, insertMode: !0, autoUnmask: !1},
        "non-negative-decimal": {regex: {number: function (a, d, c, e) {
            a = f.inputmask.escapeRegex.call(this, a);
            c = f.inputmask.escapeRegex.call(this, c);
            e = isNaN(e) ? e : "{0," + e + "}";
            return RegExp("^[+]?(\\d+|\\d{1," + d + "}((" + a + "\\d{" + d + "})?)+)(" + c + "\\d" + e + ")?$")
        }}, alias: "decimal"}, integer: {regex: {number: function (a, d) {
            var c = f.inputmask.escapeRegex.call(this, a);
            return RegExp("^[+-]?(\\d+|\\d{1," + d + "}((" + c + "\\d{" + d + "})?)+)$")
        }}, alias: "decimal"}})
})(jQuery);

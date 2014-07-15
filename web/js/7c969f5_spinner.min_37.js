/*
 * Fuel UX Spinner
 * https://github.com/ExactTarget/fuelux
 *
 * Copyright (c) 2012 ExactTarget
 * Licensed under the MIT license.
 */

!function (e) {
    var t = function (t, i) {
        this.$element = e(t), this.options = e.extend({}, e.fn.spinner.defaults, i), this.$input = this.$element.find(".spinner-input"), this.$element.on("keyup", this.$input, e.proxy(this.change, this)), this.options.hold ? (this.$element.on("mousedown", ".spinner-up", e.proxy(function () {
            this.startSpin(!0)
        }, this)), this.$element.on("mouseup", ".spinner-up, .spinner-down", e.proxy(this.stopSpin, this)), this.$element.on("mouseout", ".spinner-up, .spinner-down", e.proxy(this.stopSpin, this)), this.$element.on("mousedown", ".spinner-down", e.proxy(function () {
            this.startSpin(!1)
        }, this))) : (this.$element.on("click", ".spinner-up", e.proxy(function () {
            this.step(!0)
        }, this)), this.$element.on("click", ".spinner-down", e.proxy(function () {
            this.step(!1)
        }, this))), this.switches = {count: 1, enabled: !0}, this.switches.speed = "medium" === this.options.speed ? 300 : "fast" === this.options.speed ? 100 : 500, this.lastValue = null, this.render(), this.options.disabled && this.disable()
    };
    t.prototype = {constructor: t, render: function () {
        var e = this.$input.val();
        e ? this.value(e) : this.$input.val(this.options.value), this.$input.attr("maxlength", (this.options.max + "").split("").length)
    }, change: function () {
        var e = this.$input.val();
        e / 1 ? this.options.value = e / 1 : (e = e.replace(/[^0-9]/g, ""), this.$input.val(e), this.options.value = e / 1), this.triggerChangedEvent()
    }, stopSpin: function () {
        clearTimeout(this.switches.timeout), this.switches.count = 1, this.triggerChangedEvent()
    }, triggerChangedEvent: function () {
        var e = this.value();
        e !== this.lastValue && (this.lastValue = e, this.$element.trigger("changed", e), this.$element.trigger("change"))
    }, startSpin: function (t) {
        if (!this.options.disabled) {
            var i = this.switches.count;
            1 === i ? (this.step(t), i = 1) : i = 3 > i ? 1.5 : 8 > i ? 2.5 : 4, this.switches.timeout = setTimeout(e.proxy(function () {
                this.iterator(t)
            }, this), this.switches.speed / i), this.switches.count++
        }
    }, iterator: function (e) {
        this.step(e), this.startSpin(e)
    }, step: function (e) {
        var t = this.options.value, i = e ? this.options.max : this.options.min;
        if (e ? i > t : t > i) {
            var s = t + (e ? 1 : -1) * this.options.step;
            (e ? s > i : i > s) ? this.value(i) : this.value(s)
        } else if (this.options.cycle) {
            var n = e ? this.options.min : this.options.max;
            this.value(n)
        }
    }, value: function (e) {
        return!isNaN(parseFloat(e)) && isFinite(e) ? (e = parseFloat(e), this.options.value = e, this.$input.val(e), this) : this.options.value
    }, disable: function () {
        this.options.disabled = !0, this.$input.attr("disabled", ""), this.$element.find("button").addClass("disabled")
    }, enable: function () {
        this.options.disabled = !1, this.$input.removeAttr("disabled"), this.$element.find("button").removeClass("disabled")
    }}, e.fn.spinner = function (i, s) {
        var n, a = this.each(function () {
            var a = e(this), o = a.data("spinner"), r = "object" == typeof i && i;
            o || a.data("spinner", o = new t(this, r)), "string" == typeof i && (n = o[i](s))
        });
        return void 0 === n ? a : n
    }, e.fn.spinner.defaults = {value: 1, min: 1, max: 999, step: 1, hold: !0, speed: "medium", disabled: !1}, e.fn.spinner.Constructor = t, e(function () {
        e("body").on("mousedown.spinner.data-api", ".spinner", function () {
            var t = e(this);
            t.data("spinner") || t.spinner(t.data())
        })
    })
}(window.jQuery);
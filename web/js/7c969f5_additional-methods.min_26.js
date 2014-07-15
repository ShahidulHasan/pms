/*!
 * jQuery Validation Plugin 1.11.1
 *
 * http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 * http://docs.jquery.com/Plugins/Validation
 *
 * Copyright 2013 Jörn Zaefferer
 * Released under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 */
(function () {
    function a(b) {
        return b.replace(/<.[^<>]*?>/g, " ").replace(/ | /gi, " ").replace(/[.(),;:!?%#$'"_+=\/\-]*/g, "")
    }

    jQuery.validator.addMethod("maxWords", function (c, b, d) {
        return this.optional(b) || a(c).match(/\b\w+\b/g).length <= d
    }, jQuery.validator.format("Please enter {0} words or less."));
    jQuery.validator.addMethod("minWords", function (c, b, d) {
        return this.optional(b) || a(c).match(/\b\w+\b/g).length >= d
    }, jQuery.validator.format("Please enter at least {0} words."));
    jQuery.validator.addMethod("rangeWords", function (e, b, f) {
        var d = a(e);
        var c = /\b\w+\b/g;
        return this.optional(b) || d.match(c).length >= f[0] && d.match(c).length <= f[1]
    }, jQuery.validator.format("Please enter between {0} and {1} words."))
}());
jQuery.validator.addMethod("letterswithbasicpunc", function (b, a) {
    return this.optional(a) || /^[a-z\-.,()'"\s]+$/i.test(b)
}, "Letters or punctuation only please");
jQuery.validator.addMethod("alphanumeric", function (b, a) {
    return this.optional(a) || /^\w+$/i.test(b)
}, "Letters, numbers, and underscores only please");
jQuery.validator.addMethod("lettersonly", function (b, a) {
    return this.optional(a) || /^[a-z]+$/i.test(b)
}, "Letters only please");
jQuery.validator.addMethod("nowhitespace", function (b, a) {
    return this.optional(a) || /^\S+$/i.test(b)
}, "No white space please");
jQuery.validator.addMethod("ziprange", function (b, a) {
    return this.optional(a) || /^90[2-5]\d\{2\}-\d{4}$/.test(b)
}, "Your ZIP-code must be in the range 902xx-xxxx to 905-xx-xxxx");
jQuery.validator.addMethod("zipcodeUS", function (b, a) {
    return this.optional(a) || /\d{5}-\d{4}$|^\d{5}$/.test(b)
}, "The specified US ZIP Code is invalid");
jQuery.validator.addMethod("integer", function (b, a) {
    return this.optional(a) || /^-?\d+$/.test(b)
}, "A positive or negative non-decimal number please");
jQuery.validator.addMethod("vinUS", function (o) {
    if (o.length !== 17) {
        return false
    }
    var h, a, l, j, b, k;
    var c = ["A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M", "N", "P", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
    var m = [1, 2, 3, 4, 5, 6, 7, 8, 1, 2, 3, 4, 5, 7, 9, 2, 3, 4, 5, 6, 7, 8, 9];
    var g = [8, 7, 6, 5, 4, 3, 2, 10, 0, 9, 8, 7, 6, 5, 4, 3, 2];
    var e = 0;
    for (h = 0; h < 17; h++) {
        j = g[h];
        l = o.slice(h, h + 1);
        if (h === 8) {
            k = l
        }
        if (!isNaN(l)) {
            l *= j
        } else {
            for (a = 0; a < c.length; a++) {
                if (l.toUpperCase() === c[a]) {
                    l = m[a];
                    l *= j;
                    if (isNaN(k) && a === 8) {
                        k = c[a]
                    }
                    break
                }
            }
        }
        e += l
    }
    b = e % 11;
    if (b === 10) {
        b = "X"
    }
    if (b === k) {
        return true
    }
    return false
}, "The specified vehicle identification number (VIN) is invalid.");
jQuery.validator.addMethod("dateITA", function (e, c) {
    var a = false;
    var g = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
    if (g.test(e)) {
        var i = e.split("/");
        var d = parseInt(i[0], 10);
        var b = parseInt(i[1], 10);
        var f = parseInt(i[2], 10);
        var h = new Date(f, b - 1, d);
        if ((h.getFullYear() === f) && (h.getMonth() === b - 1) && (h.getDate() === d)) {
            a = true
        } else {
            a = false
        }
    } else {
        a = false
    }
    return this.optional(c) || a
}, "Please enter a correct date");
jQuery.validator.addMethod("iban", function (q, j) {
    if (this.optional(j)) {
        return true
    }
    if (!(/^([a-zA-Z0-9]{4} ){2,8}[a-zA-Z0-9]{1,4}|[a-zA-Z0-9]{12,34}$/.test(q))) {
        return false
    }
    var g = q.replace(/ /g, "").toUpperCase();
    var c = g.substring(0, 2);
    var a = {AL: "\\d{8}[\\dA-Z]{16}", AD: "\\d{8}[\\dA-Z]{12}", AT: "\\d{16}", AZ: "[\\dA-Z]{4}\\d{20}", BE: "\\d{12}", BH: "[A-Z]{4}[\\dA-Z]{14}", BA: "\\d{16}", BR: "\\d{23}[A-Z][\\dA-Z]", BG: "[A-Z]{4}\\d{6}[\\dA-Z]{8}", CR: "\\d{17}", HR: "\\d{17}", CY: "\\d{8}[\\dA-Z]{16}", CZ: "\\d{20}", DK: "\\d{14}", DO: "[A-Z]{4}\\d{20}", EE: "\\d{16}", FO: "\\d{14}", FI: "\\d{14}", FR: "\\d{10}[\\dA-Z]{11}\\d{2}", GE: "[\\dA-Z]{2}\\d{16}", DE: "\\d{18}", GI: "[A-Z]{4}[\\dA-Z]{15}", GR: "\\d{7}[\\dA-Z]{16}", GL: "\\d{14}", GT: "[\\dA-Z]{4}[\\dA-Z]{20}", HU: "\\d{24}", IS: "\\d{22}", IE: "[\\dA-Z]{4}\\d{14}", IL: "\\d{19}", IT: "[A-Z]\\d{10}[\\dA-Z]{12}", KZ: "\\d{3}[\\dA-Z]{13}", KW: "[A-Z]{4}[\\dA-Z]{22}", LV: "[A-Z]{4}[\\dA-Z]{13}", LB: "\\d{4}[\\dA-Z]{20}", LI: "\\d{5}[\\dA-Z]{12}", LT: "\\d{16}", LU: "\\d{3}[\\dA-Z]{13}", MK: "\\d{3}[\\dA-Z]{10}\\d{2}", MT: "[A-Z]{4}\\d{5}[\\dA-Z]{18}", MR: "\\d{23}", MU: "[A-Z]{4}\\d{19}[A-Z]{3}", MC: "\\d{10}[\\dA-Z]{11}\\d{2}", MD: "[\\dA-Z]{2}\\d{18}", ME: "\\d{18}", NL: "[A-Z]{4}\\d{10}", NO: "\\d{11}", PK: "[\\dA-Z]{4}\\d{16}", PS: "[\\dA-Z]{4}\\d{21}", PL: "\\d{24}", PT: "\\d{21}", RO: "[A-Z]{4}[\\dA-Z]{16}", SM: "[A-Z]\\d{10}[\\dA-Z]{12}", SA: "\\d{2}[\\dA-Z]{18}", RS: "\\d{18}", SK: "\\d{20}", SI: "\\d{15}", ES: "\\d{20}", SE: "\\d{20}", CH: "\\d{5}[\\dA-Z]{12}", TN: "\\d{20}", TR: "\\d{5}[\\dA-Z]{17}", AE: "\\d{3}\\d{16}", GB: "[A-Z]{4}\\d{14}", VG: "[\\dA-Z]{4}\\d{16}"};
    var m = a[c];
    if (typeof m !== "undefined") {
        var k = new RegExp("^[A-Z]{2}\\d{2}" + m + "$", "");
        if (!(k.test(g))) {
            return false
        }
    }
    var e = g.substring(4, g.length) + g.substring(0, 4);
    var f = "";
    var l = true;
    var d;
    for (var h = 0; h < e.length; h++) {
        d = e.charAt(h);
        if (d !== "0") {
            l = false
        }
        if (!l) {
            f += "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ".indexOf(d)
        }
    }
    var r = "";
    var o = "";
    for (var b = 0; b < f.length; b++) {
        var n = f.charAt(b);
        o = "" + r + "" + n;
        r = o % 97
    }
    return r === 1
}, "Please specify a valid IBAN");
jQuery.validator.addMethod("dateNL", function (b, a) {
    return this.optional(a) || /^(0?[1-9]|[12]\d|3[01])[\.\/\-](0?[1-9]|1[012])[\.\/\-]([12]\d)?(\d\d)$/.test(b)
}, "Please enter a correct date");
jQuery.validator.addMethod("phoneNL", function (b, a) {
    return this.optional(a) || /^((\+|00(\s|\s?\-\s?)?)31(\s|\s?\-\s?)?(\(0\)[\-\s]?)?|0)[1-9]((\s|\s?\-\s?)?[0-9]){8}$/.test(b)
}, "Please specify a valid phone number.");
jQuery.validator.addMethod("mobileNL", function (b, a) {
    return this.optional(a) || /^((\+|00(\s|\s?\-\s?)?)31(\s|\s?\-\s?)?(\(0\)[\-\s]?)?|0)6((\s|\s?\-\s?)?[0-9]){8}$/.test(b)
}, "Please specify a valid mobile number");
jQuery.validator.addMethod("postalcodeNL", function (b, a) {
    return this.optional(a) || /^[1-9][0-9]{3}\s?[a-zA-Z]{2}$/.test(b)
}, "Please specify a valid postal code");
jQuery.validator.addMethod("bankaccountNL", function (f, c) {
    if (this.optional(c)) {
        return true
    }
    if (!(/^[0-9]{9}|([0-9]{2} ){3}[0-9]{3}$/.test(f))) {
        return false
    }
    var e = f.replace(/ /g, "");
    var d = 0;
    var a = e.length;
    for (var h = 0; h < a; h++) {
        var b = a - h;
        var g = e.substring(h, h + 1);
        d = d + b * g
    }
    return d % 11 === 0
}, "Please specify a valid bank account number");
jQuery.validator.addMethod("giroaccountNL", function (b, a) {
    return this.optional(a) || /^[0-9]{1,7}$/.test(b)
}, "Please specify a valid giro account number");
jQuery.validator.addMethod("bankorgiroaccountNL", function (b, a) {
    return this.optional(a) || ($.validator.methods.bankaccountNL.call(this, b, a)) || ($.validator.methods.giroaccountNL.call(this, b, a))
}, "Please specify a valid bank or giro account number");
jQuery.validator.addMethod("time", function (b, a) {
    return this.optional(a) || /^([01]\d|2[0-3])(:[0-5]\d){1,2}$/.test(b)
}, "Please enter a valid time, between 00:00 and 23:59");
jQuery.validator.addMethod("time12h", function (b, a) {
    return this.optional(a) || /^((0?[1-9]|1[012])(:[0-5]\d){1,2}(\ ?[AP]M))$/i.test(b)
}, "Please enter a valid time in 12-hour am/pm format");
jQuery.validator.addMethod("phoneUS", function (a, b) {
    a = a.replace(/\s+/g, "");
    return this.optional(b) || a.length > 9 && a.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/)
}, "Please specify a valid phone number");
jQuery.validator.addMethod("phoneUK", function (a, b) {
    a = a.replace(/\(|\)|\s+|-/g, "");
    return this.optional(b) || a.length > 9 && a.match(/^(?:(?:(?:00\s?|\+)44\s?)|(?:\(?0))(?:\d{2}\)?\s?\d{4}\s?\d{4}|\d{3}\)?\s?\d{3}\s?\d{3,4}|\d{4}\)?\s?(?:\d{5}|\d{3}\s?\d{3})|\d{5}\)?\s?\d{4,5})$/)
}, "Please specify a valid phone number");
jQuery.validator.addMethod("mobileUK", function (a, b) {
    a = a.replace(/\(|\)|\s+|-/g, "");
    return this.optional(b) || a.length > 9 && a.match(/^(?:(?:(?:00\s?|\+)44\s?|0)7(?:[45789]\d{2}|624)\s?\d{3}\s?\d{3})$/)
}, "Please specify a valid mobile number");
jQuery.validator.addMethod("phonesUK", function (a, b) {
    a = a.replace(/\(|\)|\s+|-/g, "");
    return this.optional(b) || a.length > 9 && a.match(/^(?:(?:(?:00\s?|\+)44\s?|0)(?:1\d{8,9}|[23]\d{9}|7(?:[45789]\d{8}|624\d{6})))$/)
}, "Please specify a valid uk phone number");
jQuery.validator.addMethod("postcodeUK", function (b, a) {
    return this.optional(a) || /^((([A-PR-UWYZ][0-9])|([A-PR-UWYZ][0-9][0-9])|([A-PR-UWYZ][A-HK-Y][0-9])|([A-PR-UWYZ][A-HK-Y][0-9][0-9])|([A-PR-UWYZ][0-9][A-HJKSTUW])|([A-PR-UWYZ][A-HK-Y][0-9][ABEHMNPRVWXY]))\s?([0-9][ABD-HJLNP-UW-Z]{2})|(GIR)\s?(0AA))$/i.test(b)
}, "Please specify a valid UK postcode");
jQuery.validator.addMethod("strippedminlength", function (b, a, c) {
    return jQuery(b).text().length >= c
}, jQuery.validator.format("Please enter at least {0} characters"));
jQuery.validator.addMethod("email2", function (b, a, c) {
    return this.optional(a) || /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)*(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(b)
}, jQuery.validator.messages.email);
jQuery.validator.addMethod("url2", function (b, a, c) {
    return this.optional(a) || /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)*(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(b)
}, jQuery.validator.messages.url);
jQuery.validator.addMethod("creditcardtypes", function (b, a, c) {
    if (/[^0-9\-]+/.test(b)) {
        return false
    }
    b = b.replace(/\D/g, "");
    var d = 0;
    if (c.mastercard) {
        d |= 1
    }
    if (c.visa) {
        d |= 2
    }
    if (c.amex) {
        d |= 4
    }
    if (c.dinersclub) {
        d |= 8
    }
    if (c.enroute) {
        d |= 16
    }
    if (c.discover) {
        d |= 32
    }
    if (c.jcb) {
        d |= 64
    }
    if (c.unknown) {
        d |= 128
    }
    if (c.all) {
        d = 1 | 2 | 4 | 8 | 16 | 32 | 64 | 128
    }
    if (d & 1 && /^(5[12345])/.test(b)) {
        return b.length === 16
    }
    if (d & 2 && /^(4)/.test(b)) {
        return b.length === 16
    }
    if (d & 4 && /^(3[47])/.test(b)) {
        return b.length === 15
    }
    if (d & 8 && /^(3(0[012345]|[68]))/.test(b)) {
        return b.length === 14
    }
    if (d & 16 && /^(2(014|149))/.test(b)) {
        return b.length === 15
    }
    if (d & 32 && /^(6011)/.test(b)) {
        return b.length === 16
    }
    if (d & 64 && /^(3)/.test(b)) {
        return b.length === 16
    }
    if (d & 64 && /^(2131|1800)/.test(b)) {
        return b.length === 15
    }
    if (d & 128) {
        return true
    }
    return false
}, "Please enter a valid credit card number.");
jQuery.validator.addMethod("ipv4", function (b, a, c) {
    return this.optional(a) || /^(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)$/i.test(b)
}, "Please enter a valid IP v4 address.");
jQuery.validator.addMethod("ipv6", function (b, a, c) {
    return this.optional(a) || /^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/i.test(b)
}, "Please enter a valid IP v6 address.");
jQuery.validator.addMethod("pattern", function (b, a, c) {
    if (this.optional(a)) {
        return true
    }
    if (typeof c === "string") {
        c = new RegExp("^(?:" + c + ")$")
    }
    return c.test(b)
}, "Invalid format.");
jQuery.validator.addMethod("require_from_group", function (g, f, d) {
    var e = this;
    var b = d[1];
    var c = $(b, f.form).filter(function () {
        return e.elementValue(this)
    }).length >= d[0];
    if (!$(f).data("being_validated")) {
        var a = $(b, f.form);
        a.data("being_validated", true);
        a.valid();
        a.data("being_validated", false)
    }
    return c
}, jQuery.format("Please fill at least {0} of these fields."));
jQuery.validator.addMethod("skip_or_fill_minimum", function (h, e, i) {
    var b = this, c = i[0], d = i[1];
    var g = $(d, e.form).filter(function () {
        return b.elementValue(this)
    }).length;
    var a = g >= c || g === 0;
    if (!$(e).data("being_validated")) {
        var f = $(d, e.form);
        f.data("being_validated", true);
        f.valid();
        f.data("being_validated", false)
    }
    return a
}, jQuery.format("Please either skip these fields or fill at least {0} of them."));
jQuery.validator.addMethod("accept", function (e, c, g) {
    var f = typeof g === "string" ? g.replace(/\s/g, "").replace(/,/g, "|") : "image/*", d = this.optional(c), b, a;
    if (d) {
        return d
    }
    if ($(c).attr("type") === "file") {
        f = f.replace(/\*/g, ".*");
        if (c.files && c.files.length) {
            for (b = 0; b < c.files.length; b++) {
                a = c.files[b];
                if (!a.type.match(new RegExp(".?(" + f + ")$", "i"))) {
                    return false
                }
            }
        }
    }
    return true
}, jQuery.format("Please enter a value with a valid mimetype."));
jQuery.validator.addMethod("extension", function (b, a, c) {
    c = typeof c === "string" ? c.replace(/,/g, "|") : "png|jpe?g|gif";
    return this.optional(a) || b.match(new RegExp(".(" + c + ")$", "i"))
}, jQuery.format("Please enter a value with a valid extension."));
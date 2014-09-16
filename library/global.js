function tableHeightSize() {
    var tableHeight = $(window).height() - 212;
    $('.table-wrap').css('height', tableHeight + 'px');
}
function PopUpCenterWindow(URLStr, width, height, newWin, scrollbars) {
    var popUpWin = 0;
    if (typeof (newWin) === "undefined") {
        newWin = false;
    }
    if (typeof (scrollbars) === "undefined") {
        scrollbars = 0;
    }
    if (typeof (width) === "undefined") {
        width = 800;
    }
    if (typeof (height) === "undefined") {
        height = 600;
    }
    var left = 0;
    var top = 0;
    if (screen.width >= width) {
        left = Math.floor((screen.width - width) / 2);
    }
    if (screen.height >= height) {
        top = Math.floor((screen.height - height) / 2);
    }
    if (newWin) {
        open(URLStr, '', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=' + scrollbars + ',resizable=yes,copyhistory=yes,width=' + width + ',height=' + height + ',left=' + left + ', top=' + top + ',screenX=' + left + ',screenY=' + top + '');
        return;
    }

    if (popUpWin) {
        if (!popUpWin.closed)
            popUpWin.close();
    }
    popUpWin = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=' + scrollbars + ',resizable=yes,copyhistory=yes,width=' + width + ',height=' + height + ',left=' + left + ', top=' + top + ',screenX=' + left + ',screenY=' + top + '');
    popUpWin.focus();
}

function OpenModelWindow(url, option) {
    var fun;
    try {
        if (parent !== null && parent.$ !== null && parent.$.ShowIfrmDailog !== undefined) {
            fun = parent.$.ShowIfrmDailog;
        } else {
            fun = $.ShowIfrmDailog;
        }
    } catch (e) {
        fun = $.ShowIfrmDailog;
    }
    fun(url, option);
}
function CloseModelWindow(callback, dooptioncallback) {
    parent.$.closeIfrm(callback, dooptioncallback);
}

function StrFormat(temp, dataarry) {
    return temp.replace(/\{([\d]+)\}/g, function(s1, s2) {
        var s = dataarry[s2];
        if (typeof (s) !== "undefined") {
            if (s instanceof(Date)) {
                return s.getTimezoneOffset();
            } else {
                return encodeURIComponent(s);
            }
        } else {
            return "";
        }
    });
}
function StrFormatNoEncode(temp, dataarry) {
    return temp.replace(/\{([\d]+)\}/g, function(s1, s2) {
        var s = dataarry[s2];
        if (typeof (s) !== "undefined") {
            if (s instanceof(Date)) {
                return s.getTimezoneOffset();
            } else {
                return (s);
            }
        } else {
            return "";
        }
    });
}
function getiev() {
    var userAgent = window.navigator.userAgent.toLowerCase();
    $.browser.msie8 = $.browser.msie && /msie 8\.0/i.test(userAgent);
    $.browser.msie7 = $.browser.msie && /msie 7\.0/i.test(userAgent);
    $.browser.msie6 = !$.browser.msie8 && !$.browser.msie7 && $.browser.msie && /msie 6\.0/i.test(userAgent);
    var v;
    if ($.browser.msie8) {
        v = 8;
    } else if ($.browser.msie7) {
        v = 7;
    } else if ($.browser.msie6) {
        v = 6;
    } else {
        v = -1;
    }
    return v;
}
function toggleForm(value, size) {
    if (typeof size === "undefined") {
        size = '';
    }
    var css = $('#' + value).attr('class');
    if (css === "disabled") {
        $('#' + value)
                .removeAttr("disabled", "disabled")
                .removeClass();
        if (size === '') {
            $('#' + value + "Form")
                    .removeClass().addClass("form-group");
        } else {
            $('#' + value + "Form")
                    .removeClass().addClass("col-lg-" + size + " form-group");
        }
        $('#' + value + "HelpMe")
                .html('').empty();
    } else {
        $('#' + value)
                .attr("disabled", "disabled")
                .addClass("disabled");
        $('#' + value + "Form")
                .removeClass().addClass("col-lg-" + size + " form-group");
        $('#' + value + "HelpMe")
                .html('').empty();
    }
}
function toggleChecked(status) {
	//alert("status"+status);
	console.log("status"+status); // don't delete it..
    $(":checkbox").each(function() {
		if(status==true) {
		    $(this).val(1);
			$(this).checked=true;
			$(this).attr("checked",true);
			$(this).prop("checked", true);
		} else {
			$(this).val('5');
			$(this).checked=false;
			$(this).attr("checked",false);
			$(this).prop("checked", false);
			$(this).removeAttr("checked");
		}
		
		
    });
}
function toggle(value) {
    if ($("#" + value).is(':hidden')) {
        showMeDiv(value, 1);
    } else {
        showMeDiv(value, 0);
    }
}
function ajaxQuery(page, type, offset, limit, params) {
    $.ajax({
        type: 'POST',
        url: page,
        data: {
            start: offset,
            perPage: limit,
            params: params
        },
        beforeSend: function() {
            // this is where we append a loading image
            $('#infoPanel')
                    .html('').empty()
                    .html('<div class="progress"><img src="./images/loading.gif" alt="Loading..." /></div>');
        },
        success: function(data) {
            // successful request; do something with the data
            $('#infoPanel')
                    .html('').empty()
                    .html('<div class=\'alert alert-info\'>Loading Complete</div>');
            if (type === 'new') {
                $('#tableBody').append(data.tableString);
                $('#pagingHtml').append(data.pagingString);
            }
            else {
                $('#tableBody').empty().append(data.tableString);
                $('#pagingHtml').empty().append(data.pagingString);
            }
        },
        error: function() {
            // failed request; give feedback to user
            $('#infoPanel')
                    .html('').empty()
                    .html('<div class=\'alert alert-error\'>Error Could Load The Request Page</div>');
        }
    });
}

function showMeModal(id, toggle) {
    $(".chzn-select").chosen({search_contains: true});
    $(".chzn-select-deselect").chosen({allow_single_deselect: true});
    var activity = '';
    if (toggle === 1) {
        activity = 'show';
    } else {
        activity = 'hide';
    }
    $("#" + id).modal(activity).on('shown.bs.modal', function() {
        $('.chzn-select', this).chosen();
        $('.chzn-select', this).chosen('destroy').chosen();
        $('.chosen-select', this).chosen();
        $('.chosen-select', this).chosen('destroy').chosen();
    });
    $(".chzn-select").chosen({search_contains: true});
    $(".chzn-select-deselect").chosen({allow_single_deselect: true});
}
function showMeDiv(id, toggle) {
    if (toggle === 1) {
        $("#" + id)
                .show('fast', function() {
                }).slideDown('fast').fadeIn();
    } else {
        $("#" + id).hide('fast', function() {
            //  $('#infoPanel').html('<div class=\'alert alert-info\'></div>');
        });
    }
}
function showMeSideBar(counter, total) {
    for (i = 1; i < total + 1; i++) {
        if ("#hoverMe" + counter !== "#hoverMe" + i) {
            $("#hoverMe" + i).removeClass();
        } else {
            $("#hoverMe" + i)
                    .removeClass().addClass("active");
        }
    }
    for (i = 1; i < total + 1; i++) {
        if ("#common" + counter !== "#common" + i) {
            $("#imageFolder" + i)
                    .attr("src", "./images/icons/folder-horizontal.png")
                    .attr("alt", "Close Folder");
            $("#common" + i).each(function() {
                $(this).addClass("hide");
            });
        } else {
            $("#common" + i).each(function() {
                if ($(this).attr('class') === 'hide') {
                    $(this).removeClass();
                    $("#imageFolder" + i).attr("src", "./images/icons/folding-fan.png");
                } else {
                    $(this)
                            .removeClass().addClass("hide");
                    $("#imageFolder" + i).attr("src", "./images/icons/folder-horizontal.png");
                }
            });

        }
    }
}

function removeMeError(id, formFieldSpan) {
    // default is 6 
    if (typeof formFieldSpan === "undefined") {
        formFieldSpan = '';
    }
    if (formFieldSpan === '') {
        formFieldSpan = 6;
    }
    var len = $("#" + id).val();
    if (len.length > 0) {
		if(formFieldSpan=='9999') {
			 $("#" + id + "Form")
                    .removeClass().addClass('form-group');
      
		} else { 
      $("#" + id + "Form")
                    .removeClass().addClass('col-xs-' + formFieldSpan + ' col-sm-' + formFieldSpan + ' col-md-' + formFieldSpan + 'col-lg-' + formFieldSpan + ' form-group');
        }
        $("#" + id + "HelpMe")
                .html('').empty();
    } else  {
			if(formFieldSpan=='9999') {
				$("#" + id + "Form")
                    .removeClass().addClass('form-group has-error');
			} else {
				$("#" + id + "Form")
                    .removeClass().addClass('col-xs-' + formFieldSpan + ' col-sm-' + formFieldSpan + ' col-md-' + formFieldSpan + 'col-lg-' + formFieldSpan + ' form-group has-error');
			}
    }
}
function removeMeErrorDetail(id) {
    var len = $("#" + id).val();
    if (len.length > 0) {
        $("#" + id + "Detail")
                .removeClass().addClass('form-group');
        $("#" + id + "HelpMe")
                .html('').empty();
    } else if (len.length === 0) {
        $("#" + id + "Detail")
                .removeClass().addClass('form-group has-error');
    }
}
function removeMeDiv(id) {
    $('#' + id).fadeOut('slow', function() {
        $(this).remove();
    });
    return false;
}
function removeMeTr(id) {
    $('#' + id).fadeOut('slow', function() {
        $(this).remove();
    });
    return false;
}

function looseFocusMenu() {
    // menu should be less then 20 record
    for (i = 1; i < 20; i++) {
        $("#common" + i).each(function() {
            $(this).removeClass().addClass("hide");
        });
        $("#hoverMe" + i).each(function() {
            $(this).removeClass();
        });
    }
}
function validateMePassword(id) {
    validateMeColor(id, 0, '', '');
    $('#' + id).keyup(function() {
        var password = $('#' + id).val();
        if (password.length === 0) {
            validateMeColor(id, 1, 'error', '<img src=\'./images/icons/smiley-sad-blue.png\'> Please fill password field');
        } else if (password.length < 6) {
            validateMeColor(id, 1, 'warning', '<img src=\'./images/icons/smiley-sad-blue.png\'> Weak Password');
        } else {
            var regex_simple = /^[a-z]$/;
            var regex_capital = /^[A-Z]$/;
            var regex_numbers = /^[0-9]$/;

            var simple_status = '0';
            var capital_status = '0';
            var number_status = '0';
            var status_count = '0';
            for (i = 0; i < password.length; i++) {
                var check_character = password.charAt(i);
                if (regex_simple.test(check_character) && simple_status === '0') {
                    simple_status = '1';
                    status_count++;
                }
                if (regex_capital.test(check_character) && capital_status === '0') {
                    capital_status = '1';
                    status_count++;
                }
                if (regex_numbers.test(check_character) && number_status === '0') {
                    number_status = '1';
                    status_count++;
                }
            }
            switch (status_count) {
                case 0:
                    validateMeColor(id, 1, 'has-warning', '<img src=\'./images/icons/smiley-sad-blue.png\'> Weak Password');
                    break;
                case 1:
                    validateMeColor(id, 1, 'has-success', '<img src=\'./images/icons/smiley-neutral.png\'> Good Password');
                    break;
                case 2:
                    validateMeColor(id, 1, 'has-success', '<img src=\'./images/icons/smiley-wink.png\'> Strong Password');
                    break;
                case 3:
                    validateMeColor(id, 1, 'has-success', '<img src=\'./images/icons/smiley-wink.png\'> Superb Password');
                    break;
            }
        }
    });
}
function validateMeEmail(id) {
    // reset first any old validation color
    validateMeColor(id, 0, '', '');
    // start validate
    $("#" + id).blur(function() {
        var email = $('#' + id).val();
        var reEmail = /^[A-Za-z0-9][a-zA-Z0-9._-][A-Za-z0-9]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
        if (email === '') {
            validateMeColor(id, 1, 'has-warning', 'Field cannot be empty');
        } else if (email.length > 60) {
            validateMeColor(id, 1, 'has-warning', 'Email cannot exceed 60 characters');
        } else if (!reEmail.test(email)) {
            validateMeColor(id, 1, 'has-error', 'Invalid Email');
        } else {
            validateMeColor(id, 0, '', '');
        }
    });
}
function validateMeFilename(id) {
    $("#" + id).keyup(function() {
        $(this).val($(this).val().replace(/(?:^|\/|\\)((?:[a-z0-9])*\.(?:php))$/i, "").replace(/^\./, ""));
    }).blur(function() {
        $(this).val($(this).val().replace(/(?:^|\/|\\)((?:[a-z0-9])*\.(?:php))$/i, "").replace(/^/, ""));
    });
}
function validateMeAlphaNumeric(id) {
    $("#" + id).keyup(function() {
        $(this).val($(this).val().replace(/[^0-9a-zA-Z\,\.\s\x20]/g, "").replace(/^\./, ""));
    }).blur(function() {
        $(this).val($(this).val().replace(/[^0-9a-zA-Z\,\.\s\x20]/g, "").replace(/^/, ""));
    });
}
function validateMeAlphaNumericRange(name) {
    // give coma dot space and email
    $("input:text[name='" + name + "[]']").keyup(function() {
        $(this).val($(this).val().replace(/[^0-9a-zA-Z\,\.\s\x20]/g, "").replace(/^\./, ""));
    }).blur(function() {
        $(this).val($(this).val().replace(/[^0-9a-zA-Z\,\.\s\x20]/g, "").replace(/^/, ""));
    });
}
function validateMeAlphaNumericKeyUp(id) {
    $("#" + id).val($("#" + id).val().replace(/[^0-9a-zA-Z\,\.\s\x20]/g, "").replace(/^\./, ""));
}
function validateMeAlphaNumericBlur(id) {
    $("#" + id).val($("#" + id).val().replace(/[^0-9a-zA-Z\,\.\s\x20]/g, "").replace(/^\./, ""));
}
function validateMeNumeric(id) {
    $("#" + id).keyup(function() {
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    }).blur(function() {
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });
}
function validateMeNumericRange(name) {
    $("input:text[name='" + name + "[]']").keyup(function() {
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    }).blur(function() {
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });
}
function validateMeNumericKeyUp(id) {
    $("#" + id).val($("#" + id).val().replace(/[^0-9]/g, ''));
}
function validateMeNumericBlur(id) {
    $("#" + id).val($("#" + id).val().replace(/[^0-9]/g, ''));
}
function validateMeCurrency(id) {
    $("#" + id).keyup(function() {
        $(this).val($(this).val().replace(/[^\,\-0-9\.]/g, "").replace(/^\.\,/, ""));
    }).blur(function() {
        $(this).val($(this).val().replace(/[^\.\-0-9\.]/g, "").replace(/^\.\,/, ""));
    });
}
function validateMeCurrencyRange(name) {
    $("input:text[name='" + name + "[]']").keyup(function() {
        $(this).val($(this).val().replace(/[^\,\-0-9\.\-\,]/g, "").replace(/^\.\,/, ""));
    }).blur(function() {
        $(this).val($(this).val().replace(/[^\,\-0-9\.\-\,]/g, "").replace(/^\.\,/, ""));
    });
}
function validateMeCurrencyBlur(id) {
    $("#" + id).val($("#" + id).val().replace(/[^\,\-0-9\.\-\,]/g, "").replace(/^\.\,/, ""));
}
function validateMeCurrencyKeyUp(id) {
    $("#" + id).val($("#" + id).val().replace(/[^\,\-0-9\.\-\,]/g, "").replace(/^\.\,/, ""));
}
function validateMeDate(id) {
    $("#" + id).focus();
}
function validateMeColor(id, toggle, type, text) {
    cssClass = '';
    if (toggle === 1) {
        $("#" + id).focus();
        if (type === 'warning') {
            cssClass = "form-group has-warning";
        } else if (type === 'error') {
            cssClass = "form-group has-error";
        } else if (type === 'success') {
            cssClass = "form-group has-success";
        }
        $("#" + id + "Div")
                .removeClass().addClass(cssClass);
        $("#" + id + "HelpMe")
                .empty().html(text);
    } else {
        cssClass = "control-group";
        $("#" + id + "Div")
                .removeClass().addClass(cssClass);
        $("#" + id + "HelpMe").empty();
    }
}
function hideButton() {
    $("#query").val('');
    $("#clearSearch")
            .removeClass().addClass('btn hide');
}
function sleep(milliseconds) {
    setTimeout(function() {
        var start = new Date().getTime();
        while ((new Date().getTime() - start) < milliseconds) {
            // Do nothing
        }
    }, 0);
}
function topPage(value) {
    if (typeof value === "undefined") {
        value = 0;
    }
    $(document).scrollTop(value);
    //some browser have problem
    window.scroll(0, value);
    window.scrollTo(0, value);
}
function mktime() {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: baris ozdil
    // +      input by: gabriel paderni
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: FGFEmperor
    // +      input by: Yannoo
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: jakes
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Marc Palau
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +      input by: 3D-GRAF
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Chris
    // +    revised by: Theriault
    // %        note 1: The return values of the following examples are
    // %        note 1: received only if your system's timezone is UTC.
    // *     example 1: mktime(14, 10, 2, 2, 1, 2008);
    // *     returns 1: 1201875002
    // *     example 2: mktime(0, 0, 0, 0, 1, 2008);
    // *     returns 2: 1196467200
    // *     example 3: make = mktime();
    // *     example 3: td = new Date();
    // *     example 3: real = Math.floor(td.getTime() / 1000);
    // *     example 3: diff = (real - make);
    // *     results 3: diff < 5
    // *     example 4: mktime(0, 0, 0, 13, 1, 1997)
    // *     returns 4: 883612800 
    // *     example 5: mktime(0, 0, 0, 1, 1, 1998)
    // *     returns 5: 883612800 
    // *     example 6: mktime(0, 0, 0, 1, 1, 98)
    // *     returns 6: 883612800 
    // *     example 7: mktime(23, 59, 59, 13, 0, 2010)
    // *     returns 7: 1293839999
    // *     example 8: mktime(0, 0, -1, 1, 1, 1970)
    // *     returns 8: -1
    var d = new Date(),
            r = arguments,
            i = 0,
            e = ['Hours', 'Minutes', 'Seconds', 'Month', 'Date', 'FullYear'];

    for (i = 0; i < e.length; i++) {
        if (typeof r[i] === 'undefined') {
            r[i] = d['get' + e[i]]();
            r[i] += (i === 3); // +1 to fix JS months.
        } else {
            r[i] = parseInt(r[i], 10);
            if (isNaN(r[i])) {
                return false;
            }
        }
    }

    // Map years 0-69 to 2000-2069 and years 70-100 to 1970-2000.
    r[5] += (r[5] >= 0 ? (r[5] <= 69 ? 2e3 : (r[5] <= 100 ? 1900 : 0)) : 0);

    // Set year, month (-1 to fix JS months), and date.
    // !This must come before the call to setHours!
    d.setFullYear(r[5], r[3] - 1, r[4]);

    // Set hours, minutes, and seconds.
    d.setHours(r[0], r[1], r[2]);

    // Divide milliseconds by 1000 to return seconds and drop decimal.
    // Add 1 second if negative or it'll be off from PHP by 1 second.
    return (d.getTime() / 1e3 >> 0) - (d.getTime() < 0);
}
function print_r(array, return_val) {
    // http://kevin.vanzonneveld.net
    // +   original by: Michael White (http://getsprink.com)
    // +   improved by: Ben Bryan
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +      improved by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // -    depends on: echo
    // *     example 1: print_r(1, true);
    // *     returns 1: 1
    var output = '',
            pad_char = ' ',
            pad_val = 4,
            d = this.window.document,
            getFuncName = function(fn) {
                var name = (/\W*function\s+([\w\$]+)\s*\(/).exec(fn);
                if (!name) {
                    return '(Anonymous)';
                }
                return name[1];
            },
            repeat_char = function(len, pad_char) {
                var str = '';
                for (var i = 0; i < len; i++) {
                    str += pad_char;
                }
                return str;
            },
            formatArray = function(obj, cur_depth, pad_val, pad_char) {
                if (cur_depth > 0) {
                    cur_depth++;
                }

                var base_pad = repeat_char(pad_val * cur_depth, pad_char);
                var thick_pad = repeat_char(pad_val * (cur_depth + 1), pad_char);
                var str = '';

                if (typeof obj === 'object' && obj !== null && obj.constructor && getFuncName(obj.constructor) !== 'PHPJS_Resource') {
                    str += 'Array\n' + base_pad + '(\n';
                    for (var key in obj) {
                        if (Object.prototype.toString.call(obj[key]) === '[object Array]') {
                            str += thick_pad + '[' + key + '] => ' + formatArray(obj[key], cur_depth + 1, pad_val, pad_char);
                        }
                        else {
                            str += thick_pad + '[' + key + '] => ' + obj[key] + '\n';
                        }
                    }
                    str += base_pad + ')\n';
                }
                else if (obj === null || obj === undefined) {
                    str = '';
                }
                else { // for our "resource" class
                    str = obj.toString();
                }

                return str;
            };

    output = formatArray(array, 0, pad_val, pad_char);

    if (return_val !== true) {
        if (d.body) {
            this.echo(output);
        }
        else {
            try {
                d = XULDocument; // We're in XUL, so appending as plain text won't work; trigger an error out of XUL
                this.echo('<pre xmlns="http://www.w3.org/1999/xhtml" style="white-space:pre;">' + output + '</pre>');
            } catch (e) {
                this.echo(output); // Outputting as plain text may work in some plain XML
            }
        }
        return true;
    }
    return output;
}
function hideSpotlight() {
    $("#searchDiv").hide();
    $("#spotlightText")
            .empty().val('');
}
/**
 * This javascript file checks for the brower/browser tab action.
 * It is based on the file menstioned by Daniel Melo.
 * Reference: http://stackoverflow.com/questions/1921941/close-kill-the-session-when-the-browser-or-tab-is-closed
 */
var validNavigation = false;
function wireUpEvents() {
}
function logout() {
    //location.href = "logout.php";
}
function setZero(value) {
    value = parseInt(value);
    var str = value.toString();
    if (str.length === 1) {
        returnValue = "0" + value;
    } else {
        returnValue = value;
    }
    return returnValue;
}
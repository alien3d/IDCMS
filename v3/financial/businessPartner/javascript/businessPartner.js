function getBusinessPartnerCategory(leafId, url, securityToken) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'businessPartnerCategory'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#businessPartnerCategoryId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function getBusinessPartnerOfficeCountry(leafId, url, securityToken) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'businessPartnerOfficeCountry'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#businessPartnerOfficeCountryId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function getBusinessPartnerOfficeState(leafId, url, securityToken) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'businessPartnerOfficeState'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#businessPartnerOfficeStateId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function getBusinessPartnerOfficeCity(leafId, url, securityToken) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'businessPartnerOfficeCity'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#businessPartnerOfficeCityId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function getBusinessPartnerShippingCountry(leafId, url, securityToken) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'businessPartnerShippingCountry'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#businessPartnerShippingCountryId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function getBusinessPartnerShippingState(leafId, url, securityToken) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'businessPartnerShippingState'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#businessPartnerShippingStateId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function getBusinessPartnerShippingCity(leafId, url, securityToken) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'businessPartnerShippingCity'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#businessPartnerShippingCityId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function checkDuplicate(leafId, page, securityToken) {
    var $businessPartnerCode = $("#businessPartnerCode");
    if ($businessPartnerCode.val().length === 0) {
        alert(t['oddTextLabel']);
        return false;
    }
    $.ajax({
        type: 'GET',
        url: page,
        data: {
            businessPartnerCode: $businessPartnerCode.val(),
            method: 'duplicate',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            var total = data.total;
            if (success === true) {
                if (total !== 0) {
                    $("#businessPartnerCode").val('').focus();
                    $("#businessPartnerCodeForm").removeClass().addClass("form-group has-error");
                    $infoPanel.html('').empty().html("<img src='" + smileyRoll + "'> " + t['codeDuplicateTextLabel']).delay(5000).fadeOut();
                } else {
                    $infoPanel.html('').empty().html("<img src='" + smileyLol + "'> " + t['codeAvailableTextLabel']).delay(5000).fadeOut();
                }
            } else {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
                $("#businessPartnerForm").removeClass().addClass("form-group has-error");
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function showGrid(leafId, page, securityToken, offset, limit, type) {
    $.ajax({
        type: 'POST',
        url: page,
        data: {
            offset: offset,
            limit: limit,
            method: 'read',
            type: 'list',
            detail: 'body',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $centerViewPort = $('#centerViewport');
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $centerViewPort.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort.html('').empty().append(data);
            }
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty();
            if (type === 1) {
                $infoPanel.html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
            } else if (type === 2) {
                $infoPanel.html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['deleteRecordTextLabel']) + "</span>").delay(1000).fadeOut();
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $(document).scrollTop();
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function ajaxQuerySearchAll(leafId, url, securityToken) {
    $('#clearSearch').removeClass().addClass('btn');
    var queryGrid = $('#query').val();
    var queryWidget = $('#queryWidget').val();
    var queryText;
    if (queryGrid !== undefined) {
        if (queryGrid.length > 0) {
            queryText = queryGrid;
        } else {
            queryText = queryWidget;
        }
    } else {
        queryText = queryWidget;
    }
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'list',
            detail: 'body',
            query: queryText,
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $centerViewPort = $('#centerViewport');
            var smileyRoll = './images/icons/smiley-roll.png';
            var zoomIcon = './images/icons/magnifier-zoom-actual-equal.png';
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $centerViewPort.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort.html('').empty().append(data);
            }
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("&nbsp;<img src='" + zoomIcon + "'> <b>" + decodeURIComponent(t['filterTextLabel']) + '</b>: ' + queryText + "");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $(document).scrollTop();
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-md-12 col-sm-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function ajaxQuerySearchAllCharacter(leafId, url, securityToken, character) {
    $('#clearSearch').removeClass().addClass('btn btn-primary');
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'list',
            detail: 'body',
            securityToken: securityToken,
            leafId: leafId,
            character: character
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $centerViewPort = $('#centerViewport');
            var smileyRoll = './images/icons/smiley-roll.png';
            var zoomIcon = './images/icons/magnifier-zoom-actual-equal.png';
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $centerViewPort.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort.html('').empty().append(data);
            }
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("&nbsp;<img src='" + zoomIcon + "'> <b>" + decodeURIComponent(t['filterTextLabel']) + "</b>: " + character + " ");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $(document).scrollTop();
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html('').html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function ajaxQuerySearchAllDate(leafId, url, securityToken, dateRangeStart, dateRangeEnd, dateRangeType) {
    Date.prototype.getMonthName = function() {
        var m = [t['januaryTextLabel'], t['februaryTextLabel'], t['marchTextLabel'], t['aprilTextLabel'], t['mayTextLabel'], t['juneTextLabel'], t['julyTextLabel'], t['augustTextLabel'], t['septemberTextLabel'], t['octoberTextLabel'], t['novemberTextLabel'], t['decemberTextLabel']];
        return m[this.getMonth()];
    };
    Date.prototype.getDayName = function() {
        var d = [t['sundayTextLabel'], t['mondayTextLabel'], t['tuesdayTextLabel'], t['wednesdayTextLabel'], t['thursdayTextLabel'], t['fridayTextLabel'], t['saturdayTextLabel']];
        return d[this.getDay()];
    };
    var calendarPng;
    var strDate;
    var dateStart = new Date();
    var partsStart = String(dateRangeStart).split('-');
    dateStart.setFullYear(partsStart[2]);
    dateStart.setMonth(partsStart[1] - 1);
    dateStart.setDate(partsStart[0]);
    var dateEnd = new Date();
    if (dateRangeEnd.length > 0) {
        var partsEnd = String(dateRangeEnd).split('-');
        dateEnd.setFullYear(partsEnd[2]);
        dateEnd.setMonth(partsEnd[1] - 1);
        dateEnd.setDate(partsEnd[0]);
    }
    if (dateRangeStart.length === 0) {
        dateRangeStart = $('#dateRangeStart').val();
    }
    if (dateRangeEnd.length === 0) {
        dateRangeEnd = $('#dateRangeEnd').val();
    }
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'list',
            detail: 'body',
            query: $('#query').val(),
            securityToken: securityToken,
            leafId: leafId,
            dateRangeStart: dateRangeStart,
            dateRangeEnd: dateRangeEnd,
            dateRangeType: dateRangeType
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $centerViewPort = $('#centerViewport');
            var betweenIcon = './images/icons/arrow-curve-000-left.png';
            var smileyRoll = './images/icons/smiley-roll.png';
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $centerViewPort.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</span>");
            } else {
                $centerViewPort.html('').empty().append(data);
            }
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty();
            if (dateRangeType === 'day') {
                calendarPng = 'calendar-select-days.png';
            } else if (dateRangeType === 'week' || dateRangeType === 'between') {
                calendarPng = 'calendar-select-week.png';
            } else if (dateRangeType === 'month') {
                calendarPng = 'calendar-select-month.png';
            } else if (dateRangeType === 'year') {
                calendarPng = 'calendar-select.png';
            } else {
                calendarPng = 'calendar-select.png';
            }
            switch (dateRangeType) {
                case 'day':
                    strDate = "<b>" + t['dayTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear();
                    break;
                case 'month':
                    strDate = "<b>" + t['monthTextLabel'] + "</b> : " + dateStart.getMonthName() + ", " + dateStart.getFullYear();
                    break;
                case 'year':
                    strDate = "<b>" + t['yearTextLabel'] + "</b> : " + dateStart.getFullYear();
                    break;
                case 'week':
                    if (dateRangeEnd.length === 0) {
                        strDate = "<b>" + t['dayTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear();
                    } else {
                        strDate = "<b>" + t['betweenTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear() + "&nbsp;<img src='" + betweenIcon + "'>&nbsp;" + dateEnd.getDayName() + ", " + dateEnd.getMonthName() + ", " + dateEnd.getDate() + ", " + dateEnd.getFullYear();
                    }
                    break;
                case 'between':
                    if (dateRangeEnd.length === 0) {
                        strDate = "<b>" + t['dayTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ', ' + dateStart.getFullYear();
                    } else {
                        strDate = "<b>" + t['betweenTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear() + "&nbsp;<img src='" + betweenIcon + "'>&nbsp;" + dateEnd.getDayName() + ", " + dateEnd.getMonthName() + ", " + dateEnd.getDate() + ", " + dateEnd.getFullYear();
                    }
                    break;
            }
            var imageCalendarPath = "./images/icons/" + calendarPng;
            $infoPanel.html('').empty().html("<img src='" + imageCalendarPath + "'> " + strDate + " ");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $(document).scrollTop();
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function ajaxQuerySearchAllDateRange(leafId, url, securityToken) {
    ajaxQuerySearchAllDate(leafId, url, securityToken, $('#dateRangeStart').val(), $('#dateRangeEnd').val(), 'between');
}
function showForm(leafId, url, securityToken) {
    sleep(500);
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'new',
            type: 'form',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $centerViewPort = $('#centerViewport');
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $centerViewPort.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort.html('').empty().append(data);
                var $infoPanel = $('#infoPanel');
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
                $(document).scrollTop();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function showFormUpdate(leafId, url, urlList, securityToken, businessPartnerId, updateAccess, deleteAccess) {
    sleep(500);
    $('a[rel=tooltip]').tooltip('hide');
    $.ajax({
        type: 'POST',
        url: urlList,
        data: {
            method: 'read',
            type: 'form',
            businessPartnerId: businessPartnerId,
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $infoPanel = $('#infoPanel');
            var $centerViewPort = $('#centerViewport');
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $centerViewPort.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort.html('').empty().append(data);
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
                $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                $('#newRecordButton2').removeClass().addClass('btn  dropdown-toggle btn-success disabled');
                $('#newRecordButton3').attr('onClick', '');
                $('#newRecordButton4').attr('onClick', '');
                $('#newRecordButton5').attr('onClick', '');
                $('#newRecordButton6').attr('onClick', '');
                $('#newRecordButton7').attr('onClick', '');
                if (updateAccess === 1) {
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info');
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                    $('#updateRecordButton3').attr('onClick', "updateRecord(" + leafId + ",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",1," + deleteAccess + ")");
                    $('#updateRecordButton4').attr('onClick', "updateRecord(" + leafId + ",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",2," + deleteAccess + ")");
                    $('#updateRecordButton5').attr('onClick', "updateRecord(" + leafId + ",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",3," + deleteAccess + ")");
                } else {
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                    $('#updateRecordButton3').attr('onClick', '');
                    $('#updateRecordButton4').attr('onClick', '');
                    $('#updateRecordButton5').attr('onClick', '');
                }
                if (deleteAccess === 1) {
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(" + leafId + ",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\"," + deleteAccess + ")");
                } else {
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', '');
                }
                $(document).scrollTop();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-md-12 col-sm-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function showModalDelete(businessPartnerId, businessPartnerCategoryId, businessPartnerOfficeCountryId, businessPartnerOfficeStateId, businessPartnerOfficeCityId, businessPartnerShippingCountryId, businessPartnerShippingStateId, businessPartnerShippingCityId, businessPartnerCode, businessPartnerRegistrationNumber, businessPartnerTaxNumber, businessPartnerCompany, businessPartnerPicture, businessPartnerBusinessPhone, businessPartnerMobilePhone, businessPartnerFaxNumber, businessPartnerOfficeAddress, businessPartnerShippingAddress, businessPartnerOfficePostCode, businessPartnerShippingPostCode, businessPartnerEmail, businessPartnerWebPage, businessPartnerFacebook, businessPartnerTwitter, businessPartnerNotes, businessPartnerDate, businessPartnerChequePrinting, businessPartnerCreditTerm, businessPartnerCreditLimit, businessPartnerMaps) {
    $('#businessPartnerIdPreview').val('').val(decodeURIComponent(businessPartnerId));
    $('#businessPartnerCategoryIdPreview').val('').val(decodeURIComponent(businessPartnerCategoryId));
    $('#businessPartnerOfficeCountryIdPreview').val('').val(decodeURIComponent(businessPartnerOfficeCountryId));
    $('#businessPartnerOfficeStateIdPreview').val('').val(decodeURIComponent(businessPartnerOfficeStateId));
    $('#businessPartnerOfficeCityIdPreview').val('').val(decodeURIComponent(businessPartnerOfficeCityId));
    $('#businessPartnerShippingCountryIdPreview').val('').val(decodeURIComponent(businessPartnerShippingCountryId));
    $('#businessPartnerShippingStateIdPreview').val('').val(decodeURIComponent(businessPartnerShippingStateId));
    $('#businessPartnerShippingCityIdPreview').val('').val(decodeURIComponent(businessPartnerShippingCityId));
    $('#businessPartnerCodePreview').val('').val(decodeURIComponent(businessPartnerCode));
    $('#businessPartnerRegistrationNumberPreview').val('').val(decodeURIComponent(businessPartnerRegistrationNumber));
    $('#businessPartnerTaxNumberPreview').val('').val(decodeURIComponent(businessPartnerTaxNumber));
    $('#businessPartnerCompanyPreview').val('').val(decodeURIComponent(businessPartnerCompany));
    $('#businessPartnerPicturePreview').val('').val(decodeURIComponent(businessPartnerPicture));
    $('#businessPartnerBusinessPhonePreview').val('').val(decodeURIComponent(businessPartnerBusinessPhone));
    $('#businessPartnerMobilePhonePreview').val('').val(decodeURIComponent(businessPartnerMobilePhone));
    $('#businessPartnerFaxNumberPreview').val('').val(decodeURIComponent(businessPartnerFaxNumber));
    $('#businessPartnerOfficeAddressPreview').val('').val(decodeURIComponent(businessPartnerOfficeAddress));
    $('#businessPartnerShippingAddressPreview').val('').val(decodeURIComponent(businessPartnerShippingAddress));
    $('#businessPartnerOfficePostCodePreview').val('').val(decodeURIComponent(businessPartnerOfficePostCode));
    $('#businessPartnerShippingPostCodePreview').val('').val(decodeURIComponent(businessPartnerShippingPostCode));
    $('#businessPartnerEmailPreview').val('').val(decodeURIComponent(businessPartnerEmail));
    $('#businessPartnerWebPagePreview').val('').val(decodeURIComponent(businessPartnerWebPage));
    $('#businessPartnerFacebookPreview').val('').val(decodeURIComponent(businessPartnerFacebook));
    $('#businessPartnerTwitterPreview').val('').val(decodeURIComponent(businessPartnerTwitter));
    $('#businessPartnerNotesPreview').val('').val(decodeURIComponent(businessPartnerNotes));
    $('#businessPartnerDatePreview').val('').val(decodeURIComponent(businessPartnerDate));
    $('#businessPartnerChequePrintingPreview').val('').val(decodeURIComponent(businessPartnerChequePrinting));
    $('#businessPartnerCreditTermPreview').val('').val(decodeURIComponent(businessPartnerCreditTerm));
    $('#businessPartnerCreditLimitPreview').val('').val(decodeURIComponent(businessPartnerCreditLimit));
    $('#businessPartnerMapsPreview').val('').val(decodeURIComponent(businessPartnerMaps));
    showMeModal('deletePreview', 1);
}
function deleteGridRecord(leafId, url, urlList, securityToken) {
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'delete',
            output: 'json',
            businessPartnerId: $('#businessPartnerIdPreview').val(),
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === true) {
                showMeModal('deletePreview', 0);
                showGrid(leafId, urlList, securityToken, 0, 10, 2);
            } else if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function deleteGridRecordCheckbox(leafId, url, urlList, securityToken) {
    var stringText = '';
    var counter = 0;
    $('input:checkbox[name="businessPartnerId[]"]').each(function() {
        stringText = stringText + "&businessPartnerId[]=" + $(this).val();
    });
    $('input:checkbox[name="isDelete[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&isDelete[]=true";
        } else {
            stringText = stringText + "&isDelete[]=false";
        }
        if ($(this).is(':checked')) {
            counter++;
        }
    });
    if (counter === 0) {
        alert(decodeURIComponent(t['deleteCheckboxTextLabel']));
        return false;
    } else {
        url = url + "?" + stringText;
    }
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            method: 'updateStatus',
            output: 'json',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === true) {
                showGrid(leafId, urlList, securityToken, 0, 10, 2);
            } else if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
            } else {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function reportRequest(leafId, url, securityToken, mode) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            method: 'report',
            mode: mode,
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $infoPanel = $('#infoPanel');
            var folder = data.folder;
            var filename = data.filename;
            var success = data.success;
            var message = data.message;
            if (success === true) {
                var path="./v3/financial/businessPartner/document/" + folder + "/" + filename;
                $infoPanel.html('').empty().html("<span class='label label-success'>" + decodeURIComponent(t['requestFileTextLabel']) + "</span>");
                window.open(path);
            } else {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function auditRecord() {
    var css = $('#auditRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        return false;
    }
}
function newRecord(leafId, url, urlList, securityToken, type, createAccess, updateAccess, deleteAccess) {
    var css = $('#newRecordButton2').attr('class');
    var $businessPartnerCategoryId = $('#businessPartnerCategoryId');
    var $businessPartnerOfficeCountryId = $('#businessPartnerOfficeCountryId');
    var $businessPartnerOfficeStateId = $('#businessPartnerOfficeStateId');
    var $businessPartnerOfficeCityId = $('#businessPartnerOfficeCityId');
    var $businessPartnerShippingCountryId = $('#businessPartnerShippingCountryId');
    var $businessPartnerShippingStateId = $('#businessPartnerShippingStateId');
    var $businessPartnerShippingCityId = $('#businessPartnerShippingCityId');
    var $businessPartnerCode = $('#businessPartnerCode');
    var $businessPartnerRegistrationNumber = $('#businessPartnerRegistrationNumber');
    var $businessPartnerTaxNumber = $('#businessPartnerTaxNumber');
    var $businessPartnerCompany = $('#businessPartnerCompany');
    var $businessPartnerPicture = $('#businessPartnerPicture');
    var $businessPartnerBusinessPhone = $('#businessPartnerBusinessPhone');
    var $businessPartnerMobilePhone = $('#businessPartnerMobilePhone');
    var $businessPartnerFaxNumber = $('#businessPartnerFaxNumber');
    var $businessPartnerOfficeAddress = $('#businessPartnerOfficeAddress');
    var $businessPartnerShippingAddress = $('#businessPartnerShippingAddress');
    var $businessPartnerOfficePostCode = $('#businessPartnerOfficePostCode');
    var $businessPartnerShippingPostCode = $('#businessPartnerShippingPostCode');
    var $businessPartnerEmail = $('#businessPartnerEmail');
    var $businessPartnerWebPage = $('#businessPartnerWebPage');
    var $businessPartnerFacebook = $('#businessPartnerFacebook');
    var $businessPartnerTwitter = $('#businessPartnerTwitter');
    var $businessPartnerNotes = $('#businessPartnerNotes');
    var $businessPartnerDate = $('#businessPartnerDate');
    var $businessPartnerChequePrinting = $('#businessPartnerChequePrinting');
    var $businessPartnerCreditTerm = $('#businessPartnerCreditTerm');
    var $businessPartnerCreditLimit = $('#businessPartnerCreditLimit');
    var $businessPartnerMaps = $('#businessPartnerMaps');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (type === 1) {
            if ($businessPartnerCategoryId.val().length === 0) {
                $('#businessPartnerCategoryIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerCategoryIdLabel'] + " </span>");
                $businessPartnerCategoryId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerRegistrationNumber.val().length === 0) {
                $('#businessPartnerRegistrationNumberHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerRegistrationNumberLabel'] + " </span>");
                $('#businessPartnerRegistrationNumberForm').removeClass().addClass('form-group has-error');
                $businessPartnerRegistrationNumber.focus();
                return false;
            }
            if ($businessPartnerCompany.val().length === 0) {
                $('#businessPartnerCompanyHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerCompanyLabel'] + " </span>");
                $('#businessPartnerCompanyForm').removeClass().addClass('form-group has-error');
                $businessPartnerCompany.focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    businessPartnerCategoryId: $businessPartnerCategoryId.val(),
                    businessPartnerOfficeCountryId: $businessPartnerOfficeCountryId.val(),
                    businessPartnerOfficeStateId: $businessPartnerOfficeStateId.val(),
                    businessPartnerOfficeCityId: $businessPartnerOfficeCityId.val(),
                    businessPartnerShippingCountryId: $businessPartnerShippingCountryId.val(),
                    businessPartnerShippingStateId: $businessPartnerShippingStateId.val(),
                    businessPartnerShippingCityId: $businessPartnerShippingCityId.val(),
                    businessPartnerCode: $businessPartnerCode.val(),
                    businessPartnerRegistrationNumber: $businessPartnerRegistrationNumber.val(),
                    businessPartnerTaxNumber: $businessPartnerTaxNumber.val(),
                    businessPartnerCompany: $businessPartnerCompany.val(),
                    businessPartnerPicture: $businessPartnerPicture.val(),
                    businessPartnerBusinessPhone: $businessPartnerBusinessPhone.val(),
                    businessPartnerMobilePhone: $businessPartnerMobilePhone.val(),
                    businessPartnerFaxNumber: $businessPartnerFaxNumber.val(),
                    businessPartnerOfficeAddress: $businessPartnerOfficeAddress.val(),
                    businessPartnerShippingAddress: $businessPartnerShippingAddress.val(),
                    businessPartnerOfficePostCode: $businessPartnerOfficePostCode.val(),
                    businessPartnerShippingPostCode: $businessPartnerShippingPostCode.val(),
                    businessPartnerEmail: $businessPartnerEmail.val(),
                    businessPartnerWebPage: $businessPartnerWebPage.val(),
                    businessPartnerFacebook: $businessPartnerFacebook.val(),
                    businessPartnerTwitter: $businessPartnerTwitter.val(),
                    businessPartnerNotes: $businessPartnerNotes.val(),
                    businessPartnerDate: $businessPartnerDate.val(),
                    businessPartnerChequePrinting: $businessPartnerChequePrinting.val(),
                    businessPartnerCreditTerm: $businessPartnerCreditTerm.val(),
                    businessPartnerCreditLimit: $businessPartnerCreditLimit.val(),
                    businessPartnerMaps: $businessPartnerMaps.val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    var $infoPanel = $('#infoPanel');
                    var success = data.success;
                    var message = data.message;
                    var smileyLol = './images/icons/smiley-lol.png';
                    if (success === true) {
                        $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>").delay(1000).fadeOut();
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                        $businessPartnerCategoryId.val('');
                        $businessPartnerCategoryId.trigger("chosen:updated");
                        $('#businessPartnerCategoryIdHelpMe').html('').empty();
                        $businessPartnerOfficeCountryId.val('');
                        $businessPartnerOfficeCountryId.trigger("chosen:updated");
                        $('#businessPartnerOfficeCountryIdHelpMe').html('').empty();
                        $businessPartnerOfficeStateId.val('');
                        $businessPartnerOfficeStateId.trigger("chosen:updated");
                        $('#businessPartnerOfficeStateIdHelpMe').html('').empty();
                        $businessPartnerOfficeCityId.val('');
                        $businessPartnerOfficeCityId.trigger("chosen:updated");
                        $('#businessPartnerOfficeCityIdHelpMe').html('').empty();
                        $businessPartnerShippingCountryId.val('');
                        $businessPartnerShippingCountryId.trigger("chosen:updated");
                        $('#businessPartnerShippingCountryIdHelpMe').html('').empty();
                        $businessPartnerShippingStateId.val('');
                        $businessPartnerShippingStateId.trigger("chosen:updated");
                        $('#businessPartnerShippingStateIdHelpMe').html('').empty();
                        $businessPartnerShippingCityId.val('');
                        $businessPartnerShippingCityId.trigger("chosen:updated");
                        $('#businessPartnerShippingCityIdHelpMe').html('').empty();
                        $businessPartnerCode.val('');
                        $('#businessPartnerCodeHelpMe').html('').empty();
                        $businessPartnerRegistrationNumber.val('');
                        $('#businessPartnerRegistrationNumberHelpMe').html('').empty();
                        $businessPartnerTaxNumber.val('');
                        $('#businessPartnerTaxNumberHelpMe').html('').empty();
                        $businessPartnerCompany.val('');
                        $('#businessPartnerCompanyHelpMe').html('').empty();
                        $businessPartnerPicture.val('');
                        $('#businessPartnerPictureHelpMe').html('').empty();
                        $businessPartnerBusinessPhone.val('');
                        $('#businessPartnerBusinessPhoneHelpMe').html('').empty();
                        $businessPartnerMobilePhone.val('');
                        $('#businessPartnerMobilePhoneHelpMe').html('').empty();
                        $businessPartnerFaxNumber.val('');
                        $('#businessPartnerFaxNumberHelpMe').html('').empty();
                        $businessPartnerOfficeAddress.val('');
                        $('#businessPartnerOfficeAddressForm').removeClass().addClass('form-group');
                        $('#businessPartnerOfficeAddressHelpMe').html('').empty();
                        $businessPartnerShippingAddress.val('');
                        $('#businessPartnerShippingAddressForm').removeClass().addClass('form-group');
                        $('#businessPartnerShippingAddressHelpMe').html('').empty();
                        $businessPartnerOfficePostCode.val('');
                        $('#businessPartnerOfficePostCodeHelpMe').html('').empty();
                        $businessPartnerShippingPostCode.val('');
                        $('#businessPartnerShippingPostCodeHelpMe').html('').empty();
                        $businessPartnerEmail.val('');
                        $('#businessPartnerEmailHelpMe').html('').empty();
                        $businessPartnerWebPage.val('');
                        $('#businessPartnerWebPageHelpMe').html('').empty();
                        $businessPartnerFacebook.val('');
                        $('#businessPartnerFacebookHelpMe').html('').empty();
                        $businessPartnerTwitter.val('');
                        $('#businessPartnerTwitterHelpMe').html('').empty();
                        $businessPartnerNotes.val('');
                        $('#businessPartnerNotesForm').removeClass().addClass('form-group');
                        $('#businessPartnerNotesHelpMe').html('').empty();
                        $businessPartnerDate.val('');
                        $('#businessPartnerDateHelpMe').html('').empty();
                        $businessPartnerChequePrinting.val('');
                        $('#businessPartnerChequePrintingHelpMe').html('').empty();
                        $businessPartnerCreditTerm.val('');
                        $('#businessPartnerCreditTermHelpMe').html('').empty();
                        $businessPartnerCreditLimit.val('');
                        $('#businessPartnerCreditLimitHelpMe').html('').empty();
                        $businessPartnerMaps.val('');
                        $('#businessPartnerMapsHelpMe').html('').empty();
                    } else if (success === false) {
                        $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }
            });
        } else if (type === 2) {
            if ($businessPartnerCategoryId.val().length === 0) {
                $('#businessPartnerCategoryIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerCategoryIdLabel'] + " </span>");
                $businessPartnerCategoryId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerRegistrationNumber.val().length === 0) {
                $('#businessPartnerRegistrationNumberHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerRegistrationNumberLabel'] + " </span>");
                $('#businessPartnerRegistrationNumberForm').removeClass().addClass('form-group has-error');
                $businessPartnerRegistrationNumber.focus();
                return false;
            }
            if ($businessPartnerCompany.val().length === 0) {
                $('#businessPartnerCompanyHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerCompanyLabel'] + " </span>");
                $('#businessPartnerCompanyForm').removeClass().addClass('form-group has-error');
                $businessPartnerCompany.focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    businessPartnerCategoryId: $businessPartnerCategoryId.val(),
                    businessPartnerOfficeCountryId: $businessPartnerOfficeCountryId.val(),
                    businessPartnerOfficeStateId: $businessPartnerOfficeStateId.val(),
                    businessPartnerOfficeCityId: $businessPartnerOfficeCityId.val(),
                    businessPartnerShippingCountryId: $businessPartnerShippingCountryId.val(),
                    businessPartnerShippingStateId: $businessPartnerShippingStateId.val(),
                    businessPartnerShippingCityId: $businessPartnerShippingCityId.val(),
                    businessPartnerCode: $businessPartnerCode.val(),
                    businessPartnerRegistrationNumber: $businessPartnerRegistrationNumber.val(),
                    businessPartnerTaxNumber: $businessPartnerTaxNumber.val(),
                    businessPartnerCompany: $businessPartnerCompany.val(),
                    businessPartnerPicture: $businessPartnerPicture.val(),
                    businessPartnerBusinessPhone: $businessPartnerBusinessPhone.val(),
                    businessPartnerMobilePhone: $businessPartnerMobilePhone.val(),
                    businessPartnerFaxNumber: $businessPartnerFaxNumber.val(),
                    businessPartnerOfficeAddress: $businessPartnerOfficeAddress.val(),
                    businessPartnerShippingAddress: $businessPartnerShippingAddress.val(),
                    businessPartnerOfficePostCode: $businessPartnerOfficePostCode.val(),
                    businessPartnerShippingPostCode: $businessPartnerShippingPostCode.val(),
                    businessPartnerEmail: $businessPartnerEmail.val(),
                    businessPartnerWebPage: $businessPartnerWebPage.val(),
                    businessPartnerFacebook: $businessPartnerFacebook.val(),
                    businessPartnerTwitter: $businessPartnerTwitter.val(),
                    businessPartnerNotes: $businessPartnerNotes.val(),
                    businessPartnerDate: $businessPartnerDate.val(),
                    businessPartnerChequePrinting: $businessPartnerChequePrinting.val(),
                    businessPartnerCreditTerm: $businessPartnerCreditTerm.val(),
                    businessPartnerCreditLimit: $businessPartnerCreditLimit.val(),
                    businessPartnerMaps: $businessPartnerMaps.val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    var $infoPanel = $('#infoPanel');
                    var success = data.success;
                    var smileyLol = './images/icons/smiley-lol.png';
                    if (success === true) {
                        $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>");
                        $('#businessPartnerId').val(data.businessPartnerId);
                        $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                        $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                        $('#newRecordButton3').attr('onClick', '');
                        $('#newRecordButton4').attr('onClick', '');
                        $('#newRecordButton5').attr('onClick', '');
                        $('#newRecordButton6').attr('onClick', '');
                        $('#newRecordButton7').attr('onClick', '');
                        if (updateAccess === 1) {
                            $('#updateRecordButton1').removeClass().addClass('btn btn-info');
                            $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                            $('#updateRecordButton3').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1)");
                            $('#updateRecordButton4').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',2)");
                            $('#updateRecordButton5').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',3)");
                        } else {
                            $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                            $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                            $('#updateRecordButton3').attr('onClick', '');
                            $('#updateRecordButton4').attr('onClick', '');
                            $('#updateRecordButton5').attr('onClick', '');
                        }
                        if (deleteAccess === 1) {
                            $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "')");
                        } else {
                            $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', '');
                        }
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }
            });
        } else if (type === 5) {
            if ($businessPartnerCategoryId.val().length === 0) {
                $('#businessPartnerCategoryIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerCategoryIdLabel'] + " </span>");
                $businessPartnerCategoryId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerRegistrationNumber.val().length === 0) {
                $('#businessPartnerRegistrationNumberHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerRegistrationNumberLabel'] + " </span>");
                $('#businessPartnerRegistrationNumberForm').removeClass().addClass('form-group has-error');
                $businessPartnerRegistrationNumber.focus();
                return false;
            }
            if ($businessPartnerCompany.val().length === 0) {
                $('#businessPartnerCompanyHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerCompanyLabel'] + " </span>");
                $('#businessPartnerCompanyForm').removeClass().addClass('form-group has-error');
                $businessPartnerCompany.focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    businessPartnerCategoryId: $businessPartnerCategoryId.val(),
                    businessPartnerOfficeCountryId: $businessPartnerOfficeCountryId.val(),
                    businessPartnerOfficeStateId: $businessPartnerOfficeStateId.val(),
                    businessPartnerOfficeCityId: $businessPartnerOfficeCityId.val(),
                    businessPartnerShippingCountryId: $businessPartnerShippingCountryId.val(),
                    businessPartnerShippingStateId: $businessPartnerShippingStateId.val(),
                    businessPartnerShippingCityId: $businessPartnerShippingCityId.val(),
                    businessPartnerCode: $businessPartnerCode.val(),
                    businessPartnerRegistrationNumber: $businessPartnerRegistrationNumber.val(),
                    businessPartnerTaxNumber: $businessPartnerTaxNumber.val(),
                    businessPartnerCompany: $businessPartnerCompany.val(),
                    businessPartnerPicture: $businessPartnerPicture.val(),
                    businessPartnerBusinessPhone: $businessPartnerBusinessPhone.val(),
                    businessPartnerMobilePhone: $businessPartnerMobilePhone.val(),
                    businessPartnerFaxNumber: $businessPartnerFaxNumber.val(),
                    businessPartnerOfficeAddress: $businessPartnerOfficeAddress.val(),
                    businessPartnerShippingAddress: $businessPartnerShippingAddress.val(),
                    businessPartnerOfficePostCode: $businessPartnerOfficePostCode.val(),
                    businessPartnerShippingPostCode: $businessPartnerShippingPostCode.val(),
                    businessPartnerEmail: $businessPartnerEmail.val(),
                    businessPartnerWebPage: $businessPartnerWebPage.val(),
                    businessPartnerFacebook: $businessPartnerFacebook.val(),
                    businessPartnerTwitter: $businessPartnerTwitter.val(),
                    businessPartnerNotes: $businessPartnerNotes.val(),
                    businessPartnerDate: $businessPartnerDate.val(),
                    businessPartnerChequePrinting: $businessPartnerChequePrinting.val(),
                    businessPartnerCreditTerm: $businessPartnerCreditTerm.val(),
                    businessPartnerCreditLimit: $businessPartnerCreditLimit.val(),
                    businessPartnerMaps: $businessPartnerMaps.val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    var success = data.success;
                    var message = data.message;
                    var $infoPanel = $('#infoPanel');
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    if (success === true) {
                        showGrid(leafId, urlList, securityToken, 0, 10, 1);
                    } else {
                        $infoPanel.html('').empty().html("<span class='label label-important'> <img src='" + smileyRollSweat + "'> " + message + "</span>");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }
            });
        }
        showMeDiv('tableDate', 0);
        showMeDiv('formEntry', 1);
    }
}
function updateRecord(leafId, url, urlList, securityToken, type, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var css = $('#updateRecordButton2').attr('class');
    var $businessPartnerId = $('#businessPartnerId');
    var $businessPartnerCategoryId = $('#businessPartnerCategoryId');
    var $businessPartnerOfficeCountryId = $('#businessPartnerOfficeCountryId');
    var $businessPartnerOfficeStateId = $('#businessPartnerOfficeStateId');
    var $businessPartnerOfficeCityId = $('#businessPartnerOfficeCityId');
    var $businessPartnerShippingCountryId = $('#businessPartnerShippingCountryId');
    var $businessPartnerShippingStateId = $('#businessPartnerShippingStateId');
    var $businessPartnerShippingCityId = $('#businessPartnerShippingCityId');
    var $businessPartnerCode = $('#businessPartnerCode');
    var $businessPartnerRegistrationNumber = $('#businessPartnerRegistrationNumber');
    var $businessPartnerTaxNumber = $('#businessPartnerTaxNumber');
    var $businessPartnerCompany = $('#businessPartnerCompany');
    var $businessPartnerPicture = $('#businessPartnerPicture');
    var $businessPartnerBusinessPhone = $('#businessPartnerBusinessPhone');
    var $businessPartnerMobilePhone = $('#businessPartnerMobilePhone');
    var $businessPartnerFaxNumber = $('#businessPartnerFaxNumber');
    var $businessPartnerOfficeAddress = $('#businessPartnerOfficeAddress');
    var $businessPartnerShippingAddress = $('#businessPartnerShippingAddress');
    var $businessPartnerOfficePostCode = $('#businessPartnerOfficePostCode');
    var $businessPartnerShippingPostCode = $('#businessPartnerShippingPostCode');
    var $businessPartnerEmail = $('#businessPartnerEmail');
    var $businessPartnerWebPage = $('#businessPartnerWebPage');
    var $businessPartnerFacebook = $('#businessPartnerFacebook');
    var $businessPartnerTwitter = $('#businessPartnerTwitter');
    var $businessPartnerNotes = $('#businessPartnerNotes');
    var $businessPartnerDate = $('#businessPartnerDate');
    var $businessPartnerChequePrinting = $('#businessPartnerChequePrinting');
    var $businessPartnerCreditTerm = $('#businessPartnerCreditTerm');
    var $businessPartnerCreditLimit = $('#businessPartnerCreditLimit');
    var $businessPartnerMaps = $('#businessPartnerMaps');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $infoPanel.empty().html('');
        if (type === 1) {
            if ($businessPartnerCategoryId.val().length === 0) {
                $('#businessPartnerCategoryIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerCategoryIdLabel'] + " </span>");
                $businessPartnerCategoryId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerRegistrationNumber.val().length === 0) {
                $('#businessPartnerRegistrationNumberHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerRegistrationNumberLabel'] + " </span>");
                $('#businessPartnerRegistrationNumberForm').removeClass().addClass('form-group has-error');
                $businessPartnerRegistrationNumber.focus();
                return false;
            }
            if ($businessPartnerCompany.val().length === 0) {
                $('#businessPartnerCompanyHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerCompanyLabel'] + " </span>");
                $('#businessPartnerCompanyForm').removeClass().addClass('form-group has-error');
                $businessPartnerCompany.focus();
                return false;
            }
            $infoPanel.html('').empty();
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'save',
                    output: 'json',
                    businessPartnerId: $businessPartnerId.val(),
                    businessPartnerCategoryId: $businessPartnerCategoryId.val(),
                    businessPartnerOfficeCountryId: $businessPartnerOfficeCountryId.val(),
                    businessPartnerOfficeStateId: $businessPartnerOfficeStateId.val(),
                    businessPartnerOfficeCityId: $businessPartnerOfficeCityId.val(),
                    businessPartnerShippingCountryId: $businessPartnerShippingCountryId.val(),
                    businessPartnerShippingStateId: $businessPartnerShippingStateId.val(),
                    businessPartnerShippingCityId: $businessPartnerShippingCityId.val(),
                    businessPartnerCode: $businessPartnerCode.val(),
                    businessPartnerRegistrationNumber: $businessPartnerRegistrationNumber.val(),
                    businessPartnerTaxNumber: $businessPartnerTaxNumber.val(),
                    businessPartnerCompany: $businessPartnerCompany.val(),
                    businessPartnerPicture: $businessPartnerPicture.val(),
                    businessPartnerBusinessPhone: $businessPartnerBusinessPhone.val(),
                    businessPartnerMobilePhone: $businessPartnerMobilePhone.val(),
                    businessPartnerFaxNumber: $businessPartnerFaxNumber.val(),
                    businessPartnerOfficeAddress: $businessPartnerOfficeAddress.val(),
                    businessPartnerShippingAddress: $businessPartnerShippingAddress.val(),
                    businessPartnerOfficePostCode: $businessPartnerOfficePostCode.val(),
                    businessPartnerShippingPostCode: $businessPartnerShippingPostCode.val(),
                    businessPartnerEmail: $businessPartnerEmail.val(),
                    businessPartnerWebPage: $businessPartnerWebPage.val(),
                    businessPartnerFacebook: $businessPartnerFacebook.val(),
                    businessPartnerTwitter: $businessPartnerTwitter.val(),
                    businessPartnerNotes: $businessPartnerNotes.val(),
                    businessPartnerDate: $businessPartnerDate.val(),
                    businessPartnerChequePrinting: $businessPartnerChequePrinting.val(),
                    businessPartnerCreditTerm: $businessPartnerCreditTerm.val(),
                    businessPartnerCreditLimit: $businessPartnerCreditLimit.val(),
                    businessPartnerMaps: $businessPartnerMaps.val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    var $infoPanel = $('#infoPanel');
                    var success = data.success;
                    var message = data.message;
                    var smileyLol = './images/icons/smiley-lol.png';
                    if (success === true) {
                        $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['updateRecordTextLabel']) + "</span>");
                        if (deleteAccess === 1) {
                            $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + deleteAccess + ")");
                        } else {
                            $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', '');
                        }
                    } else if (success === false) {
                        $infoPanel.empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }
            });
        } else if (type === 3) {
            if ($businessPartnerCategoryId.val().length === 0) {
                $('#businessPartnerCategoryIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerCategoryIdLabel'] + " </span>");
                $businessPartnerCategoryId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerRegistrationNumber.val().length === 0) {
                $('#businessPartnerRegistrationNumberHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerRegistrationNumberLabel'] + " </span>");
                $('#businessPartnerRegistrationNumberForm').removeClass().addClass('form-group has-error');
                $businessPartnerRegistrationNumber.focus();
                return false;
            }
            if ($businessPartnerCompany.val().length === 0) {
                $('#businessPartnerCompanyHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerCompanyLabel'] + " </span>");
                $('#businessPartnerCompanyForm').removeClass().addClass('form-group has-error');
                $businessPartnerCompany.focus();
                return false;
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'save',
                    output: 'json',
                    businessPartnerId: $businessPartnerId.val(),
                    businessPartnerCategoryId: $businessPartnerCategoryId.val(),
                    businessPartnerOfficeCountryId: $businessPartnerOfficeCountryId.val(),
                    businessPartnerOfficeStateId: $businessPartnerOfficeStateId.val(),
                    businessPartnerOfficeCityId: $businessPartnerOfficeCityId.val(),
                    businessPartnerShippingCountryId: $businessPartnerShippingCountryId.val(),
                    businessPartnerShippingStateId: $businessPartnerShippingStateId.val(),
                    businessPartnerShippingCityId: $businessPartnerShippingCityId.val(),
                    businessPartnerCode: $businessPartnerCode.val(),
                    businessPartnerRegistrationNumber: $businessPartnerRegistrationNumber.val(),
                    businessPartnerTaxNumber: $businessPartnerTaxNumber.val(),
                    businessPartnerCompany: $businessPartnerCompany.val(),
                    businessPartnerPicture: $businessPartnerPicture.val(),
                    businessPartnerBusinessPhone: $businessPartnerBusinessPhone.val(),
                    businessPartnerMobilePhone: $businessPartnerMobilePhone.val(),
                    businessPartnerFaxNumber: $businessPartnerFaxNumber.val(),
                    businessPartnerOfficeAddress: $businessPartnerOfficeAddress.val(),
                    businessPartnerShippingAddress: $businessPartnerShippingAddress.val(),
                    businessPartnerOfficePostCode: $businessPartnerOfficePostCode.val(),
                    businessPartnerShippingPostCode: $businessPartnerShippingPostCode.val(),
                    businessPartnerEmail: $businessPartnerEmail.val(),
                    businessPartnerWebPage: $businessPartnerWebPage.val(),
                    businessPartnerFacebook: $businessPartnerFacebook.val(),
                    businessPartnerTwitter: $businessPartnerTwitter.val(),
                    businessPartnerNotes: $businessPartnerNotes.val(),
                    businessPartnerDate: $businessPartnerDate.val(),
                    businessPartnerChequePrinting: $businessPartnerChequePrinting.val(),
                    businessPartnerCreditTerm: $businessPartnerCreditTerm.val(),
                    businessPartnerCreditLimit: $businessPartnerCreditLimit.val(),
                    businessPartnerMaps: $businessPartnerMaps.val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    var $infoPanel = $('#infoPanel');
                    var success = data.success;
                    var message = data.message;
                    var smileyLol = './images/icons/smiley-lol.png';
                    if (success === true) {
                        $infoPanel.html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                        showGrid(leafId, urlList, securityToken, 0, 10, 1);
                    } else if (success === false) {
                        $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
                    }
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }
            });
        }
    }
}
function deleteRecord(leafId, url, urlList, securityToken, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var $businessPartnerId = $('#businessPartnerId');
    var css = $('#deleteRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (deleteAccess === 1) {
            if (confirm(decodeURIComponent(t['deleteRecordMessageLabel']))) {
                var value = $businessPartnerId.val();
                if (!value) {
                    $infoPanel.html('').empty().html("<span class='label label-important'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "<span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                    return false;
                } else {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'delete',
                            output: 'json',
                            businessPartnerId: $businessPartnerId.val(),
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            var $infoPanel = $('#infoPanel');
                            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        },
                        success: function(data) {
                            var $infoPanel = $('#infoPanel');
                            var success = data.success;
                            var message = data.message;
                            if (success === true) {
                                showGrid(leafId, urlList, securityToken, 0, 10, 2);
                            } else if (success === false) {
                                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
                                if ($infoPanel.is(':hidden')) {
                                    $infoPanel.show();
                                }
                            }
                        },
                        error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                        }
                    });
                }
            } else {
                return false;
            }
        }
    }
}
function resetRecord(leafId, url, urlList, securityToken, createAccess, updateAccess, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var resetIcon = './images/icons/fruit-orange.png';
    $infoPanel.html('').empty().html("<span class='label label-important'><img src='" + resetIcon + "'> " + decodeURIComponent(t['resetRecordTextLabel']) + "</span>").delay(1000).fadeOut();
    if ($infoPanel.is(':hidden')) {
        $infoPanel.show();
    }
    if (createAccess === 1) {
        $('#newRecordButton1').removeClass().addClass('btn btn-success').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1)");
        $('#newRecordButton2').attr('onClick', '').removeClass().addClass('btn dropdown-toggle btn-success');
        $('#newRecordButton3').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1)");
        $('#newRecordButton4').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',2)");
        $('#newRecordButton5').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',3)");
        $('#newRecordButton6').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',4)");
        $('#newRecordButton7').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',5)");
    } else {
        $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
        $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
        $('#newRecordButton3').attr('onClick', '');
        $('#newRecordButton4').attr('onClick', '');
        $('#newRecordButton5').attr('onClick', '');
        $('#newRecordButton6').attr('onClick', '');
        $('#newRecordButton7').attr('onClick', '');
    }
    $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '').attr('onClick', '');
    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled').attr('onClick', '');
    $('#updateRecordButton3').attr('onClick', '');
    $('#updateRecordButton4').attr('onClick', '');
    $('#updateRecordButton5').attr('onClick', '');
    $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
    $('#postRecordButton').removeClass().addClass('btn btn-info').attr('onClick', '');
    $('#firstRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "firstRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
    $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
    $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
    $('#endRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "endRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + ")");
    $("#businessPartnerId").val('');
    $("#businessPartnerIdHelpMe").empty().html('');
    
    $("#businessPartnerCategoryId").val('');
    $("#businessPartnerCategoryIdHelpMe").empty().html('');
    $('#businessPartnerCategoryId').trigger("chosen:updated");
    $("#businessPartnerOfficeCountryId").val('');
    $("#businessPartnerOfficeCountryIdHelpMe").empty().html('');
    $('#businessPartnerOfficeCountryId').trigger("chosen:updated");
    $("#businessPartnerOfficeStateId").val('');
    $("#businessPartnerOfficeStateIdHelpMe").empty().html('');
    $('#businessPartnerOfficeStateId').trigger("chosen:updated");
    $("#businessPartnerOfficeCityId").val('');
    $("#businessPartnerOfficeCityIdHelpMe").empty().html('');
    $('#businessPartnerOfficeCityId').trigger("chosen:updated");
    $("#businessPartnerShippingCountryId").val('');
    $("#businessPartnerShippingCountryIdHelpMe").empty().html('');
    $('#businessPartnerShippingCountryId').trigger("chosen:updated");
    $("#businessPartnerShippingStateId").val('');
    $("#businessPartnerShippingStateIdHelpMe").empty().html('');
    $('#businessPartnerShippingStateId').trigger("chosen:updated");
    $("#businessPartnerShippingCityId").val('');
    $("#businessPartnerShippingCityIdHelpMe").empty().html('');
    $('#businessPartnerShippingCityId').trigger("chosen:updated");
    $("#businessPartnerCode").val('');
    $("#businessPartnerCodeHelpMe").empty().html('');
    $("#businessPartnerRegistrationNumber").val('');
    $("#businessPartnerRegistrationNumberHelpMe").empty().html('');
    $("#businessPartnerTaxNumber").val('');
    $("#businessPartnerTaxNumberHelpMe").empty().html('');
    $("#businessPartnerCompany").val('');
    $("#businessPartnerCompanyHelpMe").empty().html('');
    $("#businessPartnerPicture").val('');
    $("#businessPartnerPictureHelpMe").empty().html('');
    $("#businessPartnerBusinessPhone").val('');
    $("#businessPartnerBusinessPhoneHelpMe").empty().html('');
    $("#businessPartnerMobilePhone").val('');
    $("#businessPartnerMobilePhoneHelpMe").empty().html('');
    $("#businessPartnerFaxNumber").val('');
    $("#businessPartnerFaxNumberHelpMe").empty().html('');
    $("#businessPartnerOfficeAddress").val('');
    $("#businessPartnerOfficeAddressHelpMe").empty().html('');
    $('#businessPartnerOfficeAddress').val('');
    $("#businessPartnerShippingAddress").val('');
    $("#businessPartnerShippingAddressHelpMe").empty().html('');
    $('#businessPartnerShippingAddress').val('');
    $("#businessPartnerOfficePostCode").val('');
    $("#businessPartnerOfficePostCodeHelpMe").empty().html('');
    $("#businessPartnerShippingPostCode").val('');
    $("#businessPartnerShippingPostCodeHelpMe").empty().html('');
    $("#businessPartnerEmail").val('');
    $("#businessPartnerEmailHelpMe").empty().html('');
    $("#businessPartnerWebPage").val('');
    $("#businessPartnerWebPageHelpMe").empty().html('');
    $("#businessPartnerFacebook").val('');
    $("#businessPartnerFacebookHelpMe").empty().html('');
    $("#businessPartnerTwitter").val('');
    $("#businessPartnerTwitterHelpMe").empty().html('');
    $("#businessPartnerNotes").val('');
    $("#businessPartnerNotesHelpMe").empty().html('');
    $('#businessPartnerNotes').empty().val('');
    $("#businessPartnerDate").val('');
    $("#businessPartnerDateHelpMe").empty().html('');
    $("#businessPartnerChequePrinting").val('');
    $("#businessPartnerChequePrintingHelpMe").empty().html('');
    $("#businessPartnerCreditTerm").val('');
    $("#businessPartnerCreditTermHelpMe").empty().html('');
    $("#businessPartnerCreditLimit").val('');
    $("#businessPartnerCreditLimitHelpMe").empty().html('');
    $("#businessPartnerMaps").val('');
    $("#businessPartnerMapsHelpMe").empty().html('');
}
function postRecord() {
    var css = $('#postRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        return false;
    }
}
function firstRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess) {
    var css = $('#firstRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $.ajax({
            type: 'GET',
            url: url,
            data: {
                method: 'dataNavigationRequest',
                dataNavigation: 'firstRecord',
                output: 'json',
                securityToken: securityToken,
                leafId: leafId
            },
            beforeSend: function() {
                var smileyRoll = './images/icons/smiley-roll.png';
                var $infoPanel = $('#infoPanel');
                $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            },
            success: function(data) {
                var $infoPanel = $('#infoPanel');
                var success = data.success;
                var firstRecord = data.firstRecord;
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                var smileyRoll = './images/icons/smiley-roll.png';
                if (firstRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (success === true) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'read',
                            businessPartnerId: firstRecord,
                            output: 'json',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        },
                        success: function(data) {
                            var x,
                                    output;
                            var success = data.success;
                            var $infoPanel = $('#infoPanel');
                            var lastRecord = data.lastRecord;
                            var nextRecord = data.nextRecord;
                            var previousRecord = data.previousRecord;
                            if (success === true) {
                                $('#businessPartnerId').val(data.data.businessPartnerId);
                                $('#businessPartnerCategoryId').val(data.data.businessPartnerCategoryId).trigger("chosen:updated");
                                $('#businessPartnerOfficeCountryId').val(data.data.businessPartnerOfficeCountryId).trigger("chosen:updated");
                                $('#businessPartnerOfficeStateId').val(data.data.businessPartnerOfficeStateId).trigger("chosen:updated");
                                $('#businessPartnerOfficeCityId').val(data.data.businessPartnerOfficeCityId).trigger("chosen:updated");
                                $('#businessPartnerShippingCountryId').val(data.data.businessPartnerShippingCountryId).trigger("chosen:updated");
                                $('#businessPartnerShippingStateId').val(data.data.businessPartnerShippingStateId).trigger("chosen:updated");
                                $('#businessPartnerShippingCityId').val(data.data.businessPartnerShippingCityId).trigger("chosen:updated");
                                $('#businessPartnerCode').val(data.data.businessPartnerCode);
                                $('#businessPartnerRegistrationNumber').val(data.data.businessPartnerRegistrationNumber);
                                $('#businessPartnerTaxNumber').val(data.data.businessPartnerTaxNumber);
                                $('#businessPartnerCompany').val(data.data.businessPartnerCompany);
                                $('#businessPartnerPicture').val(data.data.businessPartnerPicture);
                                $('#businessPartnerBusinessPhone').val(data.data.businessPartnerBusinessPhone);
                                $('#businessPartnerMobilePhone').val(data.data.businessPartnerMobilePhone);
                                $('#businessPartnerFaxNumber').val(data.data.businessPartnerFaxNumber);
                                $('#businessPartnerOfficeAddress').val(data.data.businessPartnerOfficeAddress);
                                $('#businessPartnerShippingAddress').val(data.data.businessPartnerShippingAddress);
                                $('#businessPartnerOfficePostCode').val(data.data.businessPartnerOfficePostCode);
                                $('#businessPartnerShippingPostCode').val(data.data.businessPartnerShippingPostCode);
                                $('#businessPartnerEmail').val(data.data.businessPartnerEmail);
                                $('#businessPartnerWebPage').val(data.data.businessPartnerWebPage);
                                $('#businessPartnerFacebook').val(data.data.businessPartnerFacebook);
                                $('#businessPartnerTwitter').val(data.data.businessPartnerTwitter);
                                $('#businessPartnerNotes').val(data.data.businessPartnerNotes);
                                x = data.data.businessPartnerDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#businessPartnerDate').val(output);
                                $('#businessPartnerChequePrinting').val(data.data.businessPartnerChequePrinting);
                                $('#businessPartnerCreditTerm').val(data.data.businessPartnerCreditTerm);
                                $('#businessPartnerCreditLimit').val(data.data.businessPartnerCreditLimit);
                                $('#businessPartnerMaps').val(data.data.businessPartnerMaps);
                                if (nextRecord > 0) {
                                    $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                                    $('#nextRecordButton').removeClass().addClass('btn btn-default').attr('onClick', '').attr('onClick', "nextRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
                                    $('#firstRecordCounter').val(firstRecord);
                                    $('#previousRecordCounter').val(previousRecord);
                                    $('#nextRecordCounter').val(nextRecord);
                                    $('#lastRecordCounter').val(lastRecord);
                                    $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                                    $('#newRecordButton3').attr('onClick', '');
                                    $('#newRecordButton4').attr('onClick', '');
                                    $('#newRecordButton5').attr('onClick', '');
                                    $('#newRecordButton6').attr('onClick', '');
                                    $('#newRecordButton7').attr('onClick', '');
                                    if (updateAccess === 1) {
                                        $('#updateRecordButton1').removeClass().addClass('btn btn-info');
                                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                                        $('#updateRecordButton3').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1," + deleteAccess + ")");
                                        $('#updateRecordButton4').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',2," + deleteAccess + ")");
                                        $('#updateRecordButton5').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',3," + deleteAccess + ")");
                                    } else {
                                        $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                                        $('#updateRecordButton3').attr('onClick', '');
                                        $('#updateRecordButton4').attr('onClick', '');
                                        $('#updateRecordButton5').attr('onClick', '');
                                    }
                                    if (deleteAccess === 1) {
                                        $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', '').attr('onClick', "deleteRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + deleteAccess + ")");
                                    } else {
                                        $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', '');
                                    }
                                }
                                var startIcon = './images/icons/control-stop.png';
                                $infoPanel.html('').empty().html("&nbsp;<img src='" + startIcon + "'> " + decodeURIComponent(t['firstButtonLabel']) + " ");
                                if ($infoPanel.is(':hidden')) {
                                    $infoPanel.show();
                                }
                            }
                        },
                        error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                        }
                    });
                } else {
                    $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRollSweat + "'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }
            },
            error: function(xhr) {
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
            }
        });
    }
}
function endRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var css = $('#endRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $.ajax({
            type: 'GET',
            url: url,
            data: {
                method: 'dataNavigationRequest',
                dataNavigation: 'lastRecord',
                output: 'json',
                securityToken: securityToken,
                leafId: leafId
            },
            beforeSend: function() {
                var smileyRoll = './images/icons/smiley-roll.png';
                var $infoPanel = $('#infoPanel');
                $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            },
            success: function(data) {
                var success = data.success;
                var message = data.message;
                var lastRecord = data.lastRecord;
                var smileyRoll = './images/icons/smiley-roll.png';
                if (lastRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (success === true) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'read',
                            businessPartnerId: lastRecord,
                            output: 'json',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        },
                        success: function(data) {
                            var x,
                                    output;
                            var success = data.success;
                            var firstRecord = data.firstRecord;
                            var lastRecord = data.lastRecord;
                            var nextRecord = data.nextRecord;
                            var previousRecord = data.previousRecord;
                            if (success === true) {
                                $('#businessPartnerId').val(data.data.businessPartnerId);
                                $('#businessPartnerCategoryId').val(data.data.businessPartnerCategoryId).trigger("chosen:updated");
                                $('#businessPartnerOfficeCountryId').val(data.data.businessPartnerOfficeCountryId).trigger("chosen:updated");
                                $('#businessPartnerOfficeStateId').val(data.data.businessPartnerOfficeStateId).trigger("chosen:updated");
                                $('#businessPartnerOfficeCityId').val(data.data.businessPartnerOfficeCityId).trigger("chosen:updated");
                                $('#businessPartnerShippingCountryId').val(data.data.businessPartnerShippingCountryId).trigger("chosen:updated");
                                $('#businessPartnerShippingStateId').val(data.data.businessPartnerShippingStateId).trigger("chosen:updated");
                                $('#businessPartnerShippingCityId').val(data.data.businessPartnerShippingCityId).trigger("chosen:updated");
                                $('#businessPartnerCode').val(data.data.businessPartnerCode);
                                $('#businessPartnerRegistrationNumber').val(data.data.businessPartnerRegistrationNumber);
                                $('#businessPartnerTaxNumber').val(data.data.businessPartnerTaxNumber);
                                $('#businessPartnerCompany').val(data.data.businessPartnerCompany);
                                $('#businessPartnerPicture').val(data.data.businessPartnerPicture);
                                $('#businessPartnerBusinessPhone').val(data.data.businessPartnerBusinessPhone);
                                $('#businessPartnerMobilePhone').val(data.data.businessPartnerMobilePhone);
                                $('#businessPartnerFaxNumber').val(data.data.businessPartnerFaxNumber);
                                $('#businessPartnerOfficeAddress').val(data.data.businessPartnerOfficeAddress);
                                ;
                                $('#businessPartnerShippingAddress').val(data.data.businessPartnerShippingAddress);
                                ;
                                $('#businessPartnerOfficePostCode').val(data.data.businessPartnerOfficePostCode);
                                $('#businessPartnerShippingPostCode').val(data.data.businessPartnerShippingPostCode);
                                $('#businessPartnerEmail').val(data.data.businessPartnerEmail);
                                $('#businessPartnerWebPage').val(data.data.businessPartnerWebPage);
                                $('#businessPartnerFacebook').val(data.data.businessPartnerFacebook);
                                $('#businessPartnerTwitter').val(data.data.businessPartnerTwitter);
                                $('#businessPartnerNotes').val(data.data.businessPartnerNotes);
                                ;
                                x = data.data.businessPartnerDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#businessPartnerDate').val(output);
                                $('#businessPartnerChequePrinting').val(data.data.businessPartnerChequePrinting);
                                $('#businessPartnerCreditTerm').val(data.data.businessPartnerCreditTerm);
                                $('#businessPartnerCreditLimit').val(data.data.businessPartnerCreditLimit);
                                $('#businessPartnerMaps').val(data.data.businessPartnerMaps);
                                if (lastRecord !== 0) {
                                    $('#previousRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "previousRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
                                    $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                                    $('#firstRecordCounter').val(firstRecord);
                                    $('#previousRecordCounter').val(previousRecord);
                                    $('#nextRecordCounter').val(nextRecord);
                                    $('#lastRecordCounter').val(lastRecord);
                                    $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                                    $('#newRecordButton3').attr('onClick', '');
                                    $('#newRecordButton4').attr('onClick', '');
                                    $('#newRecordButton5').attr('onClick', '');
                                    $('#newRecordButton6').attr('onClick', '');
                                    $('#newRecordButton7').attr('onClick', '');
                                    if (updateAccess === 1) {
                                        $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', '').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1," + deleteAccess + ")");
                                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info').attr('onClick', '');
                                        $('#updateRecordButton3').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1," + deleteAccess + ")");
                                        $('#updateRecordButton4').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',2," + deleteAccess + ")");
                                        $('#updateRecordButton5').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',3," + deleteAccess + ")");
                                    } else {
                                        $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '').attr('onClick', '');
                                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled').attr('onClick', '');
                                        $('#updateRecordButton3').attr('onClick', '');
                                        $('#updateRecordButton4').attr('onClick', '');
                                        $('#updateRecordButton5').attr('onClick', '');
                                    }
                                    if (deleteAccess === 1) {
                                        $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + deleteAccess + ")");
                                    } else {
                                        $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', '');
                                    }
                                }
                            }
                        },
                        error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                        }
                    });
                } else {
                    $infoPanel.html("<span class='label label-important'>&nbsp;" + message + "</span>");
                }
                var endIcon = './images/icons/control-stop-180.png';
                $infoPanel.html('').empty().html("&nbsp;<img src='" + endIcon + "'> " + decodeURIComponent(t['endButtonLabel']) + " ");
            },
            error: function(xhr) {
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
            }
        });
    }
}
function previousRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess) {
    var $previousRecordCounter = $('#previousRecordCounter');
    var $infoPanel = $('#infoPanel');
    var css = $('#previousRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($previousRecordCounter.val() === '' || $previousRecordCounter.val() === undefined) {
            $infoPanel.html('').empty().html("<span class='label label-important'>" + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }
        if (parseFloat($previousRecordCounter.val()) > 0 && parseFloat($previousRecordCounter.val()) < parseFloat($('#lastRecordCounter').val())) {
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'read',
                    businessPartnerId: $previousRecordCounter.val(),
                    output: 'json',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    var x,
                            output;
                    var success = data.success;
                    var firstRecord = data.firstRecord;
                    var lastRecord = data.lastRecord;
                    var nextRecord = data.nextRecord;
                    var previousRecord = data.previousRecord;
                    var $infoPanel = $('#infoPanel');
                    if (success === true) {
                        $('#businessPartnerId').val(data.data.businessPartnerId);
                        $('#businessPartnerCategoryId').val(data.data.businessPartnerCategoryId).trigger("chosen:updated");
                        $('#businessPartnerOfficeCountryId').val(data.data.businessPartnerOfficeCountryId).trigger("chosen:updated");
                        $('#businessPartnerOfficeStateId').val(data.data.businessPartnerOfficeStateId).trigger("chosen:updated");
                        $('#businessPartnerOfficeCityId').val(data.data.businessPartnerOfficeCityId).trigger("chosen:updated");
                        $('#businessPartnerShippingCountryId').val(data.data.businessPartnerShippingCountryId).trigger("chosen:updated");
                        $('#businessPartnerShippingStateId').val(data.data.businessPartnerShippingStateId).trigger("chosen:updated");
                        $('#businessPartnerShippingCityId').val(data.data.businessPartnerShippingCityId).trigger("chosen:updated");
                        $('#businessPartnerCode').val(data.data.businessPartnerCode);
                        $('#businessPartnerRegistrationNumber').val(data.data.businessPartnerRegistrationNumber);
                        $('#businessPartnerTaxNumber').val(data.data.businessPartnerTaxNumber);
                        $('#businessPartnerCompany').val(data.data.businessPartnerCompany);
                        $('#businessPartnerPicture').val(data.data.businessPartnerPicture);
                        $('#businessPartnerBusinessPhone').val(data.data.businessPartnerBusinessPhone);
                        $('#businessPartnerMobilePhone').val(data.data.businessPartnerMobilePhone);
                        $('#businessPartnerFaxNumber').val(data.data.businessPartnerFaxNumber);
                        $('#businessPartnerOfficeAddress').val(data.data.businessPartnerOfficeAddress);
                        ;
                        $('#businessPartnerShippingAddress').val(data.data.businessPartnerShippingAddress);
                        ;
                        $('#businessPartnerOfficePostCode').val(data.data.businessPartnerOfficePostCode);
                        $('#businessPartnerShippingPostCode').val(data.data.businessPartnerShippingPostCode);
                        $('#businessPartnerEmail').val(data.data.businessPartnerEmail);
                        $('#businessPartnerWebPage').val(data.data.businessPartnerWebPage);
                        $('#businessPartnerFacebook').val(data.data.businessPartnerFacebook);
                        $('#businessPartnerTwitter').val(data.data.businessPartnerTwitter);
                        $('#businessPartnerNotes').val(data.data.businessPartnerNotes);
                        ;
                        x = data.data.businessPartnerDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#businessPartnerDate').val(output);
                        $('#businessPartnerChequePrinting').val(data.data.businessPartnerChequePrinting);
                        $('#businessPartnerCreditTerm').val(data.data.businessPartnerCreditTerm);
                        $('#businessPartnerCreditLimit').val(data.data.businessPartnerCreditLimit);
                        $('#businessPartnerMaps').val(data.data.businessPartnerMaps);
                        $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                        $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled').attr('onClick', '');
                        $('#newRecordButton3').attr('onClick', '');
                        $('#newRecordButton4').attr('onClick', '');
                        $('#newRecordButton5').attr('onClick', '');
                        $('#newRecordButton6').attr('onClick', '');
                        $('#newRecordButton7').attr('onClick', '');
                        $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', '').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1," + deleteAccess + ")");
                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info').attr('onClick', '');
                        $('#updateRecordButton3').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1," + deleteAccess + ")");
                        $('#updateRecordButton4').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',2," + deleteAccess + ")");
                        $('#updateRecordButton5').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',3," + deleteAccess + ")");
                    } else {
                        $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                        $('#updateRecordButton3').attr('onClick', '');
                        $('#updateRecordButton4').attr('onClick', '');
                        $('#updateRecordButton5').attr('onClick', '');
                    }
                    if (deleteAccess === 1) {
                        $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + deleteAccess + ")");
                    } else {
                        $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', '');
                    }
                    $('#firstRecordCounter').val(firstRecord);
                    $('#previousRecordCounter').val(previousRecord);
                    $('#nextRecordCounter').val(nextRecord);
                    $('#lastRecordCounter').val(lastRecord);
                    if (parseFloat(nextRecord) <= parseFloat(lastRecord)) {
                        $('#nextRecordButton').removeClass().addClass('btn btn-default').attr('onClick', '').attr('onClick', "nextRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
                    } else {
                        $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                    }
                    if (parseFloat(previousRecord) === 0) {
                        var exclamationIcon = './images/icons/exclamation.png';
                        $infoPanel.html('').empty().html("&nbsp;<img src='" + exclamationIcon + "'> " + decodeURIComponent(t['firstButtonLabel']) + " ");
                        $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                    } else {
                        var control = './images/icons/control-180.png';
                        $infoPanel.html('').empty().html("&nbsp;<img src='" + control + "'> " + decodeURIComponent(t['previousButtonLabel']) + " ");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }
            });
        }
    }
}
function nextRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var $nextRecordCounter = $('#nextRecordCounter');
    var css = $('#nextRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($nextRecordCounter.val() === '' || $nextRecordCounter.val() === undefined) {
            $infoPanel.html('').empty().html("<span class='label label-important'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }
        if (parseFloat($nextRecordCounter.val()) <= parseFloat($('#lastRecordCounter').val())) {
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'read',
                    businessPartnerId: $nextRecordCounter.val(),
                    output: 'json',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    var $infoPanel = $('#infoPanel');
                    var x,
                            output;
                    var success = data.success;
                    var firstRecord = data.firstRecord;
                    var lastRecord = data.lastRecord;
                    var nextRecord = data.nextRecord;
                    var previousRecord = data.previousRecord;
                    if (success === true) {
                        $('#businessPartnerId').val(data.data.businessPartnerId);
                        $('#businessPartnerCategoryId').val(data.data.businessPartnerCategoryId).trigger("chosen:updated");
                        $('#businessPartnerOfficeCountryId').val(data.data.businessPartnerOfficeCountryId).trigger("chosen:updated");
                        $('#businessPartnerOfficeStateId').val(data.data.businessPartnerOfficeStateId).trigger("chosen:updated");
                        $('#businessPartnerOfficeCityId').val(data.data.businessPartnerOfficeCityId).trigger("chosen:updated");
                        $('#businessPartnerShippingCountryId').val(data.data.businessPartnerShippingCountryId).trigger("chosen:updated");
                        $('#businessPartnerShippingStateId').val(data.data.businessPartnerShippingStateId).trigger("chosen:updated");
                        $('#businessPartnerShippingCityId').val(data.data.businessPartnerShippingCityId).trigger("chosen:updated");
                        $('#businessPartnerCode').val(data.data.businessPartnerCode);
                        $('#businessPartnerRegistrationNumber').val(data.data.businessPartnerRegistrationNumber);
                        $('#businessPartnerTaxNumber').val(data.data.businessPartnerTaxNumber);
                        $('#businessPartnerCompany').val(data.data.businessPartnerCompany);
                        $('#businessPartnerPicture').val(data.data.businessPartnerPicture);
                        $('#businessPartnerBusinessPhone').val(data.data.businessPartnerBusinessPhone);
                        $('#businessPartnerMobilePhone').val(data.data.businessPartnerMobilePhone);
                        $('#businessPartnerFaxNumber').val(data.data.businessPartnerFaxNumber);
                        $('#businessPartnerOfficeAddress').val(data.data.businessPartnerOfficeAddress);
                        $('#businessPartnerShippingAddress').val(data.data.businessPartnerShippingAddress);
                        $('#businessPartnerOfficePostCode').val(data.data.businessPartnerOfficePostCode);
                        $('#businessPartnerShippingPostCode').val(data.data.businessPartnerShippingPostCode);
                        $('#businessPartnerEmail').val(data.data.businessPartnerEmail);
                        $('#businessPartnerWebPage').val(data.data.businessPartnerWebPage);
                        $('#businessPartnerFacebook').val(data.data.businessPartnerFacebook);
                        $('#businessPartnerTwitter').val(data.data.businessPartnerTwitter);
                        $('#businessPartnerNotes').val(data.data.businessPartnerNotes);
                        x = data.data.businessPartnerDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#businessPartnerDate').val(output);
                        $('#businessPartnerChequePrinting').val(data.data.businessPartnerChequePrinting);
                        $('#businessPartnerCreditTerm').val(data.data.businessPartnerCreditTerm);
                        $('#businessPartnerCreditLimit').val(data.data.businessPartnerCreditLimit);
                        $('#businessPartnerMaps').val(data.data.businessPartnerMaps);
                        $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                        $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                        $('#newRecordButton3').attr('onClick', '');
                        $('#newRecordButton4').attr('onClick', '');
                        $('#newRecordButton5').attr('onClick', '');
                        $('#newRecordButton6').attr('onClick', '');
                        $('#newRecordButton7').attr('onClick', '');
                        if (updateAccess === 1) {
                            $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', '').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1," + deleteAccess + ")");
                            $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info').attr('onClick', '');
                            $('#updateRecordButton3').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1,'" + deleteAccess + ")");
                            $('#updateRecordButton4').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',2,'" + deleteAccess + ")");
                            $('#updateRecordButton5').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',3,'" + deleteAccess + ")");
                        } else {
                            $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                            $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                            $('#updateRecordButton3').attr('onClick', '');
                            $('#updateRecordButton4').attr('onClick', '');
                            $('#updateRecordButton5').attr('onClick', '');
                        }
                        if (deleteAccess === 1) {
                            $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + deleteAccess + ")");
                        } else {
                            $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', '');
                        }
                        $('#firstRecordCounter').val(firstRecord);
                        $('#previousRecordCounter').val(previousRecord);
                        $('#nextRecordCounter').val(nextRecord);
                        $('#lastRecordCounter').val(lastRecord);
                        if (parseFloat(previousRecord) > 0) {
                            $('#previousRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "previousRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
                        } else {
                            $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                        }
                        if (parseFloat(nextRecord) === 0) {
                            var exclamationIcon = './images/icons/exclamation.png';
                            $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                            $infoPanel.html('').empty().html("&nbsp;<img src='" + exclamationIcon + "'> " + decodeURIComponent(t['endButtonLabel']) + " ");
                        } else {
                            var controlIcon = './images/icons/control.png';
                            $infoPanel.html('').empty().html("&nbsp;<img src='" + controlIcon + "'> " + decodeURIComponent(t['nextButtonLabel']) + " ");
                        }
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }
            });
        }
    }
}

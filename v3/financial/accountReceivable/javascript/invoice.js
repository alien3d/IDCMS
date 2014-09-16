function getBusinessPartner(leafId, url, securityToken) {
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
            filter: 'businessPartner'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#businessPartnerId")
                        .html('').empty()
                        .html(data.data)
                        .trigger("chosen:updated");
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
        }
    });
}
function getBusinessPartnerContact(leafId, url, securityToken) {
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
            filter: 'businessPartnerContact'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#businessPartnerContactId")
                        .html('').empty()
                        .html(data.data)
                        .trigger("chosen:updated");
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
        }
    });
}
function getCountry(leafId, url, securityToken) {
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
            filter: 'country'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#countryId")
                        .html('').empty()
                        .html(data.data)
                        .trigger("chosen:updated");
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
        }
    });
}
function getInvoiceProject(leafId, url, securityToken) {
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
            filter: 'invoiceProject'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#invoiceProjectId")
                        .html('').empty()
                        .html(data.data)
                        .trigger("chosen:updated");
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
        }
    });
}
function getPaymentTerm(leafId, url, securityToken) {
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
            filter: 'paymentTerm'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#paymentTermId")
                        .html('').empty()
                        .html(data.data)
                        .trigger("chosen:updated");
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
        }
    });
}
function getInvoiceProcess(leafId, url, securityToken) {
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
            filter: 'invoiceProcess'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#invoiceProcessId")
                        .html('').empty()
                        .html(data.data)
                        .trigger("chosen:updated");
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
        }
    });
}
function checkDuplicate(leafId, page, securityToken) {
    var $invoiceCode = $("#invoiceCode");
    if ($invoiceCode.val().length === 0) {
        alert(t['oddTextLabel']);
        return false;
    }
    $.ajax({
        type: 'GET',
        url: page,
        data: {
            invoiceCode: $invoiceCode.val(),
            method: 'duplicate',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
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
                    $("#invoiceCode")
                            .val('')
                            .focus();
                    $("#invoiceCodeForm")
                            .removeClass().addClass("form-group has-error");
                    $infoPanel
                            .html('').empty()
                            .html("<img src='" + smileyRoll + "'> " + t['codeDuplicateTextLabel']).delay(5000).fadeOut();
                } else {
                    $infoPanel
                            .html('').empty()
                            .html("<img src='" + smileyLol + "'> " + t['codeAvailableTextLabel']).delay(5000).fadeOut();
                }
            } else {
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                $("#invoiceForm")
                        .removeClass().addClass("form-group has-error");
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
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
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
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
                $centerViewPort
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort
                        .html('').empty()
                        .append(data);
            }
            var $infoPanel = $('#infoPanel');
            $infoPanel
                    .html('').empty();
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
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
        }
    });
}
function ajaxQuerySearchAll(leafId, url, securityToken) {
    $('#clearSearch')
            .removeClass().addClass('btn');
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
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                $centerViewPort
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort
                        .html('').empty()
                        .append(data);
            }
            var $infoPanel = $('#infoPanel');
            $infoPanel
                    .html('').empty()
                    .html("&nbsp;<img src='" + zoomIcon + "'> <b>" + decodeURIComponent(t['filterTextLabel']) + '</b>: ' + queryText + "");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $(document).scrollTop();
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-xs-12 col-md-12 col-sm-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
        }
    });
}
function ajaxQuerySearchAllCharacter(leafId, url, securityToken, character) {
    $('#clearSearch')
            .removeClass().addClass('btn btn-primary');
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
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                $centerViewPort
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort
                        .html('').empty()
                        .append(data);
            }
            var $infoPanel = $('#infoPanel');
            $infoPanel
                    .html('').empty()
                    .html("&nbsp;<img src='" + zoomIcon + "'> <b>" + decodeURIComponent(t['filterTextLabel']) + "</b>: " + character + " ");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $(document).scrollTop();
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html('').html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
        }
    });
}
function ajaxQuerySearchAllDate(leafId, url, securityToken, dateRangeStart, dateRangeEnd, dateRangeType) {
    // date array 
    Date.prototype.getMonthName = function() {
        var m = [t['januaryTextLabel'], t['februaryTextLabel'], t['marchTextLabel'], t['aprilTextLabel'], t['mayTextLabel'], t['juneTextLabel'], t['julyTextLabel'],
            t['augustTextLabel'], t['septemberTextLabel'], t['octoberTextLabel'], t['novemberTextLabel'], t['decemberTextLabel']];
        return m[this.getMonth()];
    };
    Date.prototype.getDayName = function() {
        var d = [t['sundayTextLabel'], t['mondayTextLabel'], t['tuesdayTextLabel'], t['wednesdayTextLabel'],
            t['thursdayTextLabel'], t['fridayTextLabel'], t['saturdayTextLabel']];
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
    // unlimited for searching because  lazy paging.
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
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                $centerViewPort
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</span>");
            } else {
                $centerViewPort
                        .html('').empty()
                        .append(data);
            }
            var $infoPanel = $('#infoPanel');
            $infoPanel
                    .html('').empty();
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
            $infoPanel
                    .html('').empty()
                    .html("<img src='" + imageCalendarPath + "'> " + strDate + " ");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $(document).scrollTop();
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
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
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                $centerViewPort
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort
                        .html('').empty()
                        .append(data);
                var $infoPanel = $('#infoPanel');
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
                $(document).scrollTop();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
        }
    });
}
function showFormUpdate(leafId, url, urlList, securityToken, invoiceId, updateAccess, deleteAccess) {
    sleep(500);
    $('a[rel=tooltip]').tooltip('hide');
    $.ajax({
        type: 'POST',
        url: urlList,
        data: {
            method: 'read',
            type: 'form',
            invoiceId: invoiceId,
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                $centerViewPort
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort
                        .html('').empty()
                        .append(data);
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
                $('#newRecordButton1')
                        .removeClass().addClass('btn btn-success disabled');
                $('#newRecordButton2')
                        .removeClass().addClass('btn  dropdown-toggle btn-success disabled');
                $('#newRecordButton3').attr('onClick', '');
                $('#newRecordButton4').attr('onClick', '');
                $('#newRecordButton5').attr('onClick', '');
                $('#newRecordButton6').attr('onClick', '');
                $('#newRecordButton7').attr('onClick', '');
                if (updateAccess === 1) {
                    $('#updateRecordButton1')
                            .removeClass().addClass('btn btn-info');
                    $('#updateRecordButton2')
                            .removeClass().addClass('btn dropdown-toggle btn-info');
                    $('#updateRecordButton3').attr('onClick', "updateRecord(" + leafId + ",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",1," + deleteAccess + ")");
                    $('#updateRecordButton4').attr('onClick', "updateRecord(" + leafId + ",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",2," + deleteAccess + ")");
                    $('#updateRecordButton5').attr('onClick', "updateRecord(" + leafId + ",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",3," + deleteAccess + ")");
                } else {
                    $('#updateRecordButton1')
                            .removeClass().addClass('btn btn-info disabled');
                    $('#updateRecordButton2')
                            .removeClass().addClass('btn dropdown-toggle btn-info disabled');
                    $('#updateRecordButton3').attr('onClick', '');
                    $('#updateRecordButton4').attr('onClick', '');
                    $('#updateRecordButton5').attr('onClick', '');
                }
                if (deleteAccess === 1) {
                    $('#deleteRecordButton')
                            .removeClass().addClass('btn btn-danger')
                            .attr('onClick', "deleteRecord(" + leafId + ",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\"," + deleteAccess + ")");
                } else {
                    $('#deleteRecordButton')
                            .removeClass().addClass('btn btn-danger')
                            .attr('onClick', '');
                }
                $(document).scrollTop();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-xs-12 col-md-12 col-sm-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
        }
    });
}
function showModalDelete(invoiceId, businessPartnerId, businessPartnerContactId, countryId, invoiceProjectId, paymentTermId, invoiceProcessId, businessPartnerAddress, invoiceQuotationNumber, invoiceNumber, referenceNumber, invoiceCode, invoiceTotalAmount, invoiceTextAmount, invoiceTaxAmount, invoiceDiscountAmount, invoiceShippingAmount, invoiceInterestRate, invoiceDate, invoiceStartDate, invoiceEndDate, invoiceDueDate, invoicePromiseDate, invoiceShippingDate, invoicePeriod, invoiceDescription, invoiceRemark) {
    // clear first old record if exist
    $('#invoiceIdPreview').val('').val(decodeURIComponent(invoiceId));
    $('#businessPartnerIdPreview').val('').val(decodeURIComponent(businessPartnerId));
    $('#businessPartnerContactIdPreview').val('').val(decodeURIComponent(businessPartnerContactId));
    $('#countryIdPreview').val('').val(decodeURIComponent(countryId));
    $('#invoiceProjectIdPreview').val('').val(decodeURIComponent(invoiceProjectId));
    $('#paymentTermIdPreview').val('').val(decodeURIComponent(paymentTermId));
    $('#invoiceProcessIdPreview').val('').val(decodeURIComponent(invoiceProcessId));
    $('#businessPartnerAddressPreview').val('').val(decodeURIComponent(businessPartnerAddress));
    $('#invoiceQuotationNumberPreview').val('').val(decodeURIComponent(invoiceQuotationNumber));
    $('#invoiceNumberPreview').val('').val(decodeURIComponent(invoiceNumber));
    $('#referenceNumberPreview').val('').val(decodeURIComponent(referenceNumber));
    $('#invoiceCodePreview').val('').val(decodeURIComponent(invoiceCode));
    $('#invoiceTotalAmountPreview').val('').val(decodeURIComponent(invoiceTotalAmount));
    $('#invoiceTextAmountPreview').val('').val(decodeURIComponent(invoiceTextAmount));
    $('#invoiceTaxAmountPreview').val('').val(decodeURIComponent(invoiceTaxAmount));
    $('#invoiceDiscountAmountPreview').val('').val(decodeURIComponent(invoiceDiscountAmount));
    $('#invoiceShippingAmountPreview').val('').val(decodeURIComponent(invoiceShippingAmount));
    $('#invoiceInterestRatePreview').val('').val(decodeURIComponent(invoiceInterestRate));
    $('#invoiceDatePreview').val('').val(decodeURIComponent(invoiceDate));
    $('#invoiceStartDatePreview').val('').val(decodeURIComponent(invoiceStartDate));
    $('#invoiceEndDatePreview').val('').val(decodeURIComponent(invoiceEndDate));
    $('#invoiceDueDatePreview').val('').val(decodeURIComponent(invoiceDueDate));
    $('#invoicePromiseDatePreview').val('').val(decodeURIComponent(invoicePromiseDate));
    $('#invoiceShippingDatePreview').val('').val(decodeURIComponent(invoiceShippingDate));
    $('#invoicePeriodPreview').val('').val(decodeURIComponent(invoicePeriod));
    $('#invoiceDescriptionPreview').val('').val(decodeURIComponent(invoiceDescription));
    $('#invoiceRemarkPreview').val('').val(decodeURIComponent(invoiceRemark));
    showMeModal('deletePreview', 1);
}
function deleteGridRecord(leafId, url, urlList, securityToken) {
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'delete',
            output: 'json',
            invoiceId: $('#invoiceIdPreview').val(),
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
        }
    });
}
function showFormCreateDetail(leafId, url, securityToken) {
    var $infoPanel = $('#infoPanel');
    if ($('#productId9999').val().length === 0) {
        $infoPanel
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productIdLabel'] + "</span>");
        $('#productId9999HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productIdLabel'] + "</span>");
        $('#productId9999').data('chosen').activate_action();
        return false;
    }
    if ($('#unitOfMeasurementId9999').val().length === 0) {
        $infoPanel
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['unitOfMeasurementIdLabel'] + "</span>");
        $('#unitOfMeasurementId9999HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['unitOfMeasurementIdLabel'] + "</span>");
        $('#unitOfMeasurementId9999').data('chosen').activate_action();
        return false;
    }
    if ($('#discountId9999').val().length === 0) {
        $infoPanel
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['discountIdLabel'] + "</span>");
        $('#discountId9999HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['discountIdLabel'] + "</span>");
        $('#discountId9999').data('chosen').activate_action();
        return false;
    }
    if ($('#taxId9999').val().length === 0) {
        $infoPanel
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['taxIdLabel'] + "</span>");
        $('#taxId9999HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['taxIdLabel'] + "</span>");
        $('#taxId9999').data('chosen').activate_action();
        return false;
    }
    if ($('#invoiceDetailLineNumber9999').val().length === 0) {
        $infoPanel
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDetailLineNumberLabel'] + "</span>");
        $('#invoiceDetailLineNumber9999HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDetailLineNumberLabel'] + "</span>");
        $('#invoiceDetailLineNumber9999').data('chosen').activate_action();
        return false;
    }
    if ($('#invoiceDetailQuantity9999').val().length === 0) {
        $infoPanel
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDetailQuantityLabel'] + "</span>");
        $('#invoiceDetailQuantity9999HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDetailQuantityLabel'] + "</span>");
        $('#invoiceDetailQuantity9999Form')
                .removeClass().addClass('form-group has-error');
        $('#invoiceDetailQuantity9999').focus();
        return false;
    }
    if ($('#invoiceDetailDescription9999').val().length === 0) {
        $infoPanel
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDetailDescriptionLabel'] + "</span>");
        $('#invoiceDetailDescription9999HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDetailDescriptionLabel'] + "</span>");
        $('#invoiceDetailDescription9999Form')
                .removeClass().addClass('form-group has-error');
        $('#invoiceDetailDescription9999').focus();
        return false;
    }
    if ($('#invoiceDetailPrice9999').val().length === 0) {
        $infoPanel
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDetailPriceLabel'] + "</span>");
        $('#invoiceDetailPrice9999HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDetailPriceLabel'] + "</span>");
        $('#invoiceDetailPrice9999Form')
                .removeClass().addClass('form-group has-error');
        $('#invoiceDetailPrice9999').focus();
        return false;
    }
    if ($('#invoiceDetailDiscount9999').val().length === 0) {
        $infoPanel
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDetailDiscountLabel'] + "</span>");
        $('#invoiceDetailDiscount9999HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDetailDiscountLabel'] + "</span>");
        $('#invoiceDetailDiscount9999Form')
                .removeClass().addClass('form-group has-error');
        $('#invoiceDetailDiscount9999').focus();
        return false;
    }
    if ($('#invoiceDetailTax9999').val().length === 0) {
        $infoPanel
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDetailTaxLabel'] + "</span>");
        $('#invoiceDetailTax9999HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDetailTaxLabel'] + "</span>");
        $('#invoiceDetailTax9999Form')
                .removeClass().addClass('form-group has-error');
        $('#invoiceDetailTax9999').focus();
        return false;
    }
    if ($('#invoiceDetailTotalPrice9999').val().length === 0) {
        $infoPanel
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDetailTotalPriceLabel'] + "</span>");
        $('#invoiceDetailTotalPrice9999HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDetailTotalPriceLabel'] + "</span>");
        $('#invoiceDetailTotalPrice9999Form')
                .removeClass().addClass('form-group has-error');
        $('#invoiceDetailTotalPrice9999').focus();
        return false;
    }
    if ($('#isRule789999').val().length === 0) {
        $infoPanel
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isRule78Label'] + "</span>");
        $('#isRule789999HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isRule78Label'] + "</span>");
        $('#isRule789999Form')
                .removeClass().addClass('form-group has-error');
        $('#isRule789999').focus();
        return false;
    }
    $infoPanel
            .html('').empty()
            .html("<span class='label label-success'>&nbsp;" + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>");
    if ($infoPanel.is(':hidden')) {
        $infoPanel.show();
    }
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'create',
            output: 'json',
            invoiceId: $('#invoiceId').val(),
            productId: $('#productId9999').val(),
            unitOfMeasurementId: $('#unitOfMeasurementId9999').val(),
            discountId: $('#discountId9999').val(),
            taxId: $('#taxId9999').val(),
            invoiceDetailLineNumber: $('#invoiceDetailLineNumber9999').val(),
            invoiceDetailQuantity: $('#invoiceDetailQuantity9999').val(),
            invoiceDetailDescription: $('#invoiceDetailDescription9999').val(),
            invoiceDetailPrice: $('#invoiceDetailPrice9999').val(),
            invoiceDetailDiscount: $('#invoiceDetailDiscount9999').val(),
            invoiceDetailTax: $('#invoiceDetailTax9999').val(),
            invoiceDetailTotalPrice: $('#invoiceDetailTotalPrice9999').val(),
            isRule78: $('#isRule789999').val(),
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            $('#miniInfoPanel9999')
                    .html('').empty()
                    .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var success = data.success;
            var message = data.message;
            if (success === true) {
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        method: 'read',
                        output: 'table',
                        offset: '0',
                        limit: '9999',
                        invoiceId: $('#invoiceId').val(),
                        securityToken: securityToken,
                        leafId: leafId
                    },
                    beforeSend: function() {
                        var smileyRoll = './images/icons/smiley-roll.png';
                        var $infoPanel = $('#infoPanel');
                        $infoPanel
                                .html('').empty()
                                .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                        $('#miniInfoPanel9999')
                                .empty().html('')
                                .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    },
                    success: function(data) {
                        var $infoPanel = $('#infoPanel');
                        var smileyLol = './images/icons/smiley-lol.png';
                        var success = data.success;
                        if (success === true) {
                            $('#tableBody')
                                    .html('').empty()
                                    .html(data.tableData);
                            $("#companyId9999")
                                    .prop("disabled", "false")
                                    .removeAttr("disabled", "")
                                    .val('')
                                    .trigger("chosen:updated");
                            $("#invoiceId9999")
                                    .prop("disabled", "false")
                                    .removeAttr("disabled", "")
                                    .val('')
                                    .trigger("chosen:updated");
                            $("#productId9999")
                                    .prop("disabled", "false")
                                    .removeAttr("disabled", "")
                                    .val('')
                                    .trigger("chosen:updated");
                            $("#unitOfMeasurementId9999")
                                    .prop("disabled", "false")
                                    .removeAttr("disabled", "")
                                    .val('')
                                    .trigger("chosen:updated");
                            $("#discountId9999")
                                    .prop("disabled", "false")
                                    .removeAttr("disabled", "")
                                    .val('')
                                    .trigger("chosen:updated");
                            $("#taxId9999")
                                    .prop("disabled", "false")
                                    .removeAttr("disabled", "")
                                    .val('')
                                    .trigger("chosen:updated");
                            $("#invoiceDetailLineNumber9999")
                                    .prop("disabled", "false")
                                    .removeAttr("disabled", "")
                                    .val('')
                                    ;
                            $("#invoiceDetailQuantity9999")
                                    .prop("disabled", "false")
                                    .removeAttr("disabled", "")
                                    .val('')
                                    ;
                            $("#invoiceDetailDescription9999")
                                    .prop("disabled", "false")
                                    .removeAttr("disabled", "")
                                    .val('')
                                    ;
                            $("#invoiceDetailPrice9999")
                                    .prop("disabled", "false")
                                    .removeAttr("disabled", "")
                                    .val('')
                                    ;
                            $("#invoiceDetailDiscount9999")
                                    .prop("disabled", "false")
                                    .removeAttr("disabled", "")
                                    .val('')
                                    ;
                            $("#invoiceDetailTax9999")
                                    .prop("disabled", "false")
                                    .removeAttr("disabled", "")
                                    .val('')
                                    ;
                            $("#invoiceDetailTotalPrice9999")
                                    .prop("disabled", "false")
                                    .removeAttr("disabled", "")
                                    .val('')
                                    ;
                            $("#isRule789999")
                                    .prop("disabled", "false")
                                    .removeAttr("disabled", "")
                                    .val('')
                                    ;
                            $("#executeBy9999")
                                    .prop("disabled", "false")
                                    .removeAttr("disabled", "")
                                    .val('')
                                    ;
                            $(".chzn-select").chosen();
                            $infoPanel
                                    .html('').empty()
                                    .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        }
                    },
                    error: function(xhr) {
                        var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                        $('#infoError')
                                .html('').empty()
                                .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                        $('#infoErrorRowFluid')
                                .removeClass().addClass('row-fluid');
                    }
                });
                $('#miniInfoPanel9999').html("<span class='label label-success'>&nbsp;<a class='close' data-dismiss='alert' href='#'>&times;</a><img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>").delay(1000).fadeOut();
            } else if (success === false) {
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + message + "</span>");
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
        }
    });
}
function showFormUpdateDetail(leafId, url, securityToken, invoiceDetailId) {
    var $infoPanel = $('#infoPanel');
    if ($('#productId' + invoiceDetailId).val().length === 0) {
        $infoPanel.html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['productIdLabel'] + "</span>");
        $('#productId' + invoiceDetailId + 'HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['productIdLabel'] + "</span>");
        $('#productId' + invoiceDetailId).data('chosen').activate_action();
        return false;
    }
    if ($('#unitOfMeasurementId' + invoiceDetailId).val().length === 0) {
        $infoPanel.html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['unitOfMeasurementIdLabel'] + "</span>");
        $('#unitOfMeasurementId' + invoiceDetailId + 'HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['unitOfMeasurementIdLabel'] + "</span>");
        $('#unitOfMeasurementId' + invoiceDetailId).data('chosen').activate_action();
        return false;
    }
    if ($('#discountId' + invoiceDetailId).val().length === 0) {
        $infoPanel.html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['discountIdLabel'] + "</span>");
        $('#discountId' + invoiceDetailId + 'HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['discountIdLabel'] + "</span>");
        $('#discountId' + invoiceDetailId).data('chosen').activate_action();
        return false;
    }
    if ($('#taxId' + invoiceDetailId).val().length === 0) {
        $infoPanel.html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['taxIdLabel'] + "</span>");
        $('#taxId' + invoiceDetailId + 'HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['taxIdLabel'] + "</span>");
        $('#taxId' + invoiceDetailId).data('chosen').activate_action();
        return false;
    }
    if ($('#invoiceDetailLineNumber' + invoiceDetailId).val().length === 0) {
        $infoPanel.html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['invoiceDetailLineNumberLabel'] + "</span>");
        $('#invoiceDetailLineNumber' + invoiceDetailId + 'HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['invoiceDetailLineNumberLabel'] + "</span>");
        $('#invoiceDetailLineNumber' + invoiceDetailId).data('chosen').activate_action();
        return false;
    }
    if ($('#invoiceDetailQuantity' + invoiceDetailId).val().length === 0) {
        $infoPanel.html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['invoiceDetailQuantityLabel'] + "</span>");
        $('#invoiceDetailQuantity' + invoiceDetailId + 'HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['invoiceDetailQuantityLabel'] + "</span>");
        $('#invoiceDetailQuantity' + invoiceDetailId)
                .removeClass().addClass('form-group has-error');
        $('#invoiceDetailQuantity' + invoiceDetailId).focus();
        return false;
    }
    if ($('#invoiceDetailDescription' + invoiceDetailId).val().length === 0) {
        $infoPanel.html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['invoiceDetailDescriptionLabel'] + "</span>");
        $('#invoiceDetailDescription' + invoiceDetailId + 'HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['invoiceDetailDescriptionLabel'] + "</span>");
        $('#invoiceDetailDescription' + invoiceDetailId)
                .removeClass().addClass('form-group has-error');
        $('#invoiceDetailDescription' + invoiceDetailId).focus();
        return false;
    }
    if ($('#invoiceDetailPrice' + invoiceDetailId).val().length === 0) {
        $infoPanel.html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['invoiceDetailPriceLabel'] + "</span>");
        $('#invoiceDetailPrice' + invoiceDetailId + 'HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['invoiceDetailPriceLabel'] + "</span>");
        $('#invoiceDetailPrice' + invoiceDetailId)
                .removeClass().addClass('form-group has-error');
        $('#invoiceDetailPrice' + invoiceDetailId).focus();
        return false;
    }
    if ($('#invoiceDetailDiscount' + invoiceDetailId).val().length === 0) {
        $infoPanel.html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['invoiceDetailDiscountLabel'] + "</span>");
        $('#invoiceDetailDiscount' + invoiceDetailId + 'HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['invoiceDetailDiscountLabel'] + "</span>");
        $('#invoiceDetailDiscount' + invoiceDetailId)
                .removeClass().addClass('form-group has-error');
        $('#invoiceDetailDiscount' + invoiceDetailId).focus();
        return false;
    }
    if ($('#invoiceDetailTax' + invoiceDetailId).val().length === 0) {
        $infoPanel.html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['invoiceDetailTaxLabel'] + "</span>");
        $('#invoiceDetailTax' + invoiceDetailId + 'HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['invoiceDetailTaxLabel'] + "</span>");
        $('#invoiceDetailTax' + invoiceDetailId)
                .removeClass().addClass('form-group has-error');
        $('#invoiceDetailTax' + invoiceDetailId).focus();
        return false;
    }
    if ($('#invoiceDetailTotalPrice' + invoiceDetailId).val().length === 0) {
        $infoPanel.html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['invoiceDetailTotalPriceLabel'] + "</span>");
        $('#invoiceDetailTotalPrice' + invoiceDetailId + 'HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['invoiceDetailTotalPriceLabel'] + "</span>");
        $('#invoiceDetailTotalPrice' + invoiceDetailId)
                .removeClass().addClass('form-group has-error');
        $('#invoiceDetailTotalPrice' + invoiceDetailId).focus();
        return false;
    }
    if ($('#isRule78' + invoiceDetailId).val().length === 0) {
        $infoPanel.html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['isRule78Label'] + "</span>");
        $('#isRule78' + invoiceDetailId + 'HelpMe')
                .html('').empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['isRule78Label'] + "</span>");
        $('#isRule78' + invoiceDetailId)
                .removeClass().addClass('form-group has-error');
        $('#isRule78' + invoiceDetailId).focus();
        return false;
    }
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'save',
            output: 'json',
            invoiceDetailId: $('#invoiceDetailId' + invoiceDetailId).val(),
            invoiceId: $('#invoiceId').val(),
            productId: $('#productId' + invoiceDetailId).val(),
            unitOfMeasurementId: $('#unitOfMeasurementId' + invoiceDetailId).val(),
            discountId: $('#discountId' + invoiceDetailId).val(),
            taxId: $('#taxId' + invoiceDetailId).val(),
            invoiceDetailLineNumber: $('#invoiceDetailLineNumber' + invoiceDetailId).val(),
            invoiceDetailQuantity: $('#invoiceDetailQuantity' + invoiceDetailId).val(),
            invoiceDetailDescription: $('#invoiceDetailDescription' + invoiceDetailId).val(),
            invoiceDetailPrice: $('#invoiceDetailPrice' + invoiceDetailId).val(),
            invoiceDetailDiscount: $('#invoiceDetailDiscount' + invoiceDetailId).val(),
            invoiceDetailTax: $('#invoiceDetailTax' + invoiceDetailId).val(),
            invoiceDetailTotalPrice: $('#invoiceDetailTotalPrice' + invoiceDetailId).val(),
            isRule78: $('#isRule78' + invoiceDetailId).val(),
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            $('#miniInfoPanel' + invoiceDetailId)
                    .html('').empty()
                    .html("<span class='label label-warning'> <img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $infoPanel = $('#infoPanel');
            var $miniInfoPanel = $('#miniInfoPanel' + invoiceDetailId);
            var smileyLol = './images/icons/smiley-lol.png';
            var success = data.success;
            var message = data.message;
            if (success === true) {
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['updateRecordTextLabel']) + "</span>");
                $miniInfoPanel
                        .html('').empty()
                        .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'><a class='close' data-dismiss='alert' href='#'>&times;</a></span>");
            } else if (success === false) {
                $infoPanel.html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                $miniInfoPanel.html("<span class='label label-danger'>&nbsp; " + message + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
        }
    });
}
function showModalDeleteDetail(invoiceDetailId) {
    $('#invoiceDetailIdPreview').val('').val(decodeURIComponent($("#invoiceDetailId" + invoiceDetailId).val()));

    $('#productIdPreview').val('').val(decodeURIComponent($("#productId" + invoiceDetailId + " option:selected").text()));

    $('#unitOfMeasurementIdPreview').val('').val(decodeURIComponent($("#unitOfMeasurementId" + invoiceDetailId + " option:selected").text()));

    $('#discountIdPreview').val('').val(decodeURIComponent($("#discountId" + invoiceDetailId + " option:selected").text()));

    $('#taxIdPreview').val('').val(decodeURIComponent($("#taxId" + invoiceDetailId + " option:selected").text()));

    $('#invoiceDetailLineNumberPreview').val('').val(decodeURIComponent($("#invoiceDetailLineNumber" + invoiceDetailId).val()));

    $('#invoiceDetailQuantityPreview').val('').val(decodeURIComponent($("#invoiceDetailQuantity" + invoiceDetailId).val()));

    $('#invoiceDetailDescriptionPreview').val('').val(decodeURIComponent($("#invoiceDetailDescription" + invoiceDetailId).val()));

    $('#invoiceDetailPricePreview').val('').val(decodeURIComponent($("#invoiceDetailPrice" + invoiceDetailId).val()));

    $('#invoiceDetailDiscountPreview').val('').val(decodeURIComponent($("#invoiceDetailDiscount" + invoiceDetailId).val()));

    $('#invoiceDetailTaxPreview').val('').val(decodeURIComponent($("#invoiceDetailTax" + invoiceDetailId).val()));

    $('#invoiceDetailTotalPricePreview').val('').val(decodeURIComponent($("#invoiceDetailTotalPrice" + invoiceDetailId).val()));

    $('#isRule78Preview').val('').val(decodeURIComponent($("#isRule78" + invoiceDetailId).val()));

    showMeModal('deleteDetailPreview', 1);
}
function deleteGridRecordDetail(leafId, url, urlList, securityToken) {
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'delete',
            output: 'json',
            invoiceDetailId: $('#invoiceDetailIdPreview').val(),
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $infoPanel = $('#infoPanel');
            var smileyLol = './images/icons/smiley-lol.png';
            var success = data.success;
            var message = data.message;
            if (success === true) {
                showMeModal('deleteDetailPreview', 0);
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['deleteRecordTextLabel']) + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
                removeMeTr($('#invoiceDetailIdPreview').val())
            } else if (success === false) {
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-danger'> " + message + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
        }
    });
}
function deleteGridRecordCheckbox(leafId, url, urlList, securityToken) {
    var stringText = '';
    var counter = 0;
    $('input:checkbox[name="invoiceId[]"]').each(function() {
        stringText = stringText + "&invoiceId[]=" + $(this).val();
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
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + message + "</span>");
            } else {
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + message + "</span>");
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
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
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                var path = "./v3/financial/accountReceivable/document/" + folder + "/" + filename;
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-success'>" + decodeURIComponent(t['requestFileTextLabel']) + "</span>");
                window.open(path);
            } else {
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
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
    var $businessPartnerId = $('#businessPartnerId');
    var $businessPartnerContactId = $('#businessPartnerContactId');
    var $countryId = $('#countryId');
    var $invoiceProjectId = $('#invoiceProjectId');
    var $paymentTermId = $('#paymentTermId');
    var $invoiceProcessId = $('#invoiceProcessId');
    var $businessPartnerAddress = $('#businessPartnerAddress');
    var $invoiceQuotationNumber = $('#invoiceQuotationNumber');
    var $invoiceNumber = $('#invoiceNumber');
    var $referenceNumber = $('#referenceNumber');
    var $invoiceCode = $('#invoiceCode');
    var $invoiceTotalAmount = $('#invoiceTotalAmount');
    var $invoiceTextAmount = $('#invoiceTextAmount');
    var $invoiceTaxAmount = $('#invoiceTaxAmount');
    var $invoiceDiscountAmount = $('#invoiceDiscountAmount');
    var $invoiceShippingAmount = $('#invoiceShippingAmount');
    var $invoiceInterestRate = $('#invoiceInterestRate');
    var $invoiceDate = $('#invoiceDate');
    var $invoiceStartDate = $('#invoiceStartDate');
    var $invoiceEndDate = $('#invoiceEndDate');
    var $invoiceDueDate = $('#invoiceDueDate');
    var $invoicePromiseDate = $('#invoicePromiseDate');
    var $invoiceShippingDate = $('#invoiceShippingDate');
    var $invoicePeriod = $('#invoicePeriod');
    var $invoiceDescription = $('#invoiceDescription');
    var $invoiceRemark = $('#invoiceRemark');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (type === 1) {
            if ($businessPartnerId.val().length === 0) {
                $('#businessPartnerIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $businessPartnerId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerContactId.val().length === 0) {
                $('#businessPartnerContactIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerContactIdLabel'] + " </span>");
                $businessPartnerContactId.data('chosen').activate_action();
                return false;
            }
            if ($countryId.val().length === 0) {
                $('#countryIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + " </span>");
                $countryId.data('chosen').activate_action();
                return false;
            }
            if ($invoiceProjectId.val().length === 0) {
                $('#invoiceProjectIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceProjectIdLabel'] + " </span>");
                $invoiceProjectId.data('chosen').activate_action();
                return false;
            }
            if ($paymentTermId.val().length === 0) {
                $('#paymentTermIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentTermIdLabel'] + " </span>");
                $paymentTermId.data('chosen').activate_action();
                return false;
            }
            if ($invoiceProcessId.val().length === 0) {
                $('#invoiceProcessIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceProcessIdLabel'] + " </span>");
                $invoiceProcessId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerAddress.val().length === 0) {
                $('#businessPartnerAddressHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerAddressLabel'] + " </span>");
                $('#businessPartnerAddressForm')
                        .removeClass().addClass('form-group has-error');
                $businessPartnerAddress.focus();
                return false;
            }
            if ($invoiceQuotationNumber.val().length === 0) {
                $('#invoiceQuotationNumberHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceQuotationNumberLabel'] + " </span>");
                $('#invoiceQuotationNumberForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceQuotationNumber.focus();
                return false;
            }
            if ($invoiceNumber.val().length === 0) {
                $('#invoiceNumberHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceNumberLabel'] + " </span>");
                $('#invoiceNumberForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceNumber.focus();
                return false;
            }
            if ($referenceNumber.val().length === 0) {
                $('#referenceNumberHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['referenceNumberLabel'] + " </span>");
                $('#referenceNumberForm')
                        .removeClass().addClass('form-group has-error');
                $referenceNumber.focus();
                return false;
            }
            if ($invoiceCode.val().length === 0) {
                $('#invoiceCodeHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceCodeLabel'] + " </span>");
                $('#invoiceCodeForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceCode.focus();
                return false;
            }
            if ($invoiceTotalAmount.val().length === 0) {
                $('#invoiceTotalAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceTotalAmountLabel'] + " </span>");
                $('#invoiceTotalAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceTotalAmount.focus();
                return false;
            }
            if ($invoiceTextAmount.val().length === 0) {
                $('#invoiceTextAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceTextAmountLabel'] + " </span>");
                $('#invoiceTextAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceTextAmount.focus();
                return false;
            }
            if ($invoiceTaxAmount.val().length === 0) {
                $('#invoiceTaxAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceTaxAmountLabel'] + " </span>");
                $('#invoiceTaxAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceTaxAmount.focus();
                return false;
            }
            if ($invoiceDiscountAmount.val().length === 0) {
                $('#invoiceDiscountAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDiscountAmountLabel'] + " </span>");
                $('#invoiceDiscountAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceDiscountAmount.focus();
                return false;
            }
            if ($invoiceShippingAmount.val().length === 0) {
                $('#invoiceShippingAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceShippingAmountLabel'] + " </span>");
                $('#invoiceShippingAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceShippingAmount.focus();
                return false;
            }
            if ($invoiceInterestRate.val().length === 0) {
                $('#invoiceInterestRateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceInterestRateLabel'] + " </span>");
                $('#invoiceInterestRateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceInterestRate.focus();
                return false;
            }
            if ($invoiceDate.val().length === 0) {
                $('#invoiceDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDateLabel'] + " </span>");
                $('#invoiceDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceDate.focus();
                return false;
            }
            if ($invoiceStartDate.val().length === 0) {
                $('#invoiceStartDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceStartDateLabel'] + " </span>");
                $('#invoiceStartDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceStartDate.focus();
                return false;
            }
            if ($invoiceEndDate.val().length === 0) {
                $('#invoiceEndDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceEndDateLabel'] + " </span>");
                $('#invoiceEndDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceEndDate.focus();
                return false;
            }
            if ($invoiceDueDate.val().length === 0) {
                $('#invoiceDueDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDueDateLabel'] + " </span>");
                $('#invoiceDueDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceDueDate.focus();
                return false;
            }
            if ($invoicePromiseDate.val().length === 0) {
                $('#invoicePromiseDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoicePromiseDateLabel'] + " </span>");
                $('#invoicePromiseDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoicePromiseDate.focus();
                return false;
            }
            if ($invoiceShippingDate.val().length === 0) {
                $('#invoiceShippingDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceShippingDateLabel'] + " </span>");
                $('#invoiceShippingDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceShippingDate.focus();
                return false;
            }
            if ($invoicePeriod.val().length === 0) {
                $('#invoicePeriodHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoicePeriodLabel'] + " </span>");
                $('#invoicePeriodForm')
                        .removeClass().addClass('form-group has-error');
                $invoicePeriod.focus();
                return false;
            }
            if ($invoiceDescription.val().length === 0) {
                $('#invoiceDescriptionHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDescriptionLabel'] + " </span>");
                $('#invoiceDescriptionForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceDescription.focus();
                return false;
            }
            if ($invoiceRemark.val().length === 0) {
                $('#invoiceRemarkHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRemarkLabel'] + " </span>");
                $('#invoiceRemarkForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceRemark.focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    businessPartnerId: $businessPartnerId.val(),
                    businessPartnerContactId: $businessPartnerContactId.val(),
                    countryId: $countryId.val(),
                    invoiceProjectId: $invoiceProjectId.val(),
                    paymentTermId: $paymentTermId.val(),
                    invoiceProcessId: $invoiceProcessId.val(),
                    businessPartnerAddress: $businessPartnerAddress.val(),
                    invoiceQuotationNumber: $invoiceQuotationNumber.val(),
                    invoiceNumber: $invoiceNumber.val(),
                    referenceNumber: $referenceNumber.val(),
                    invoiceCode: $invoiceCode.val(),
                    invoiceTotalAmount: $invoiceTotalAmount.val(),
                    invoiceTextAmount: $invoiceTextAmount.val(),
                    invoiceTaxAmount: $invoiceTaxAmount.val(),
                    invoiceDiscountAmount: $invoiceDiscountAmount.val(),
                    invoiceShippingAmount: $invoiceShippingAmount.val(),
                    invoiceInterestRate: $invoiceInterestRate.val(),
                    invoiceDate: $invoiceDate.val(),
                    invoiceStartDate: $invoiceStartDate.val(),
                    invoiceEndDate: $invoiceEndDate.val(),
                    invoiceDueDate: $invoiceDueDate.val(),
                    invoicePromiseDate: $invoicePromiseDate.val(),
                    invoiceShippingDate: $invoiceShippingDate.val(),
                    invoicePeriod: $invoicePeriod.val(),
                    invoiceDescription: $invoiceDescription.val(),
                    invoiceRemark: $invoiceRemark.val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel
                            .html('').empty()
                            .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                        $infoPanel
                                .html('').empty()
                                .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>").delay(1000).fadeOut();
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                        $businessPartnerId.val('');
                        $businessPartnerId.trigger("chosen:updated");
                        $('#businessPartnerIdHelpMe')
                                .html('').empty();
                        $businessPartnerContactId.val('');
                        $businessPartnerContactId.trigger("chosen:updated");
                        $('#businessPartnerContactIdHelpMe')
                                .html('').empty();
                        $countryId.val('');
                        $countryId.trigger("chosen:updated");
                        $('#countryIdHelpMe')
                                .html('').empty();
                        $invoiceProjectId.val('');
                        $invoiceProjectId.trigger("chosen:updated");
                        $('#invoiceProjectIdHelpMe')
                                .html('').empty();
                        $paymentTermId.val('');
                        $paymentTermId.trigger("chosen:updated");
                        $('#paymentTermIdHelpMe')
                                .html('').empty();
                        $invoiceProcessId.val('');
                        $invoiceProcessId.trigger("chosen:updated");
                        $('#invoiceProcessIdHelpMe')
                                .html('').empty();
                        $businessPartnerAddress.val('');
                        $('#businessPartnerAddressHelpMe')
                                .html('').empty();
                        $invoiceQuotationNumber.val('');
                        $('#invoiceQuotationNumberHelpMe')
                                .html('').empty();
                        $invoiceNumber.val('');
                        $('#invoiceNumberHelpMe')
                                .html('').empty();
                        $referenceNumber.val('');
                        $('#referenceNumberHelpMe')
                                .html('').empty();
                        $invoiceCode.val('');
                        $('#invoiceCodeHelpMe')
                                .html('').empty();
                        $invoiceTotalAmount.val('');
                        $('#invoiceTotalAmountHelpMe')
                                .html('').empty();
                        $invoiceTextAmount.val('');
                        $('#invoiceTextAmountHelpMe')
                                .html('').empty();
                        $invoiceTaxAmount.val('');
                        $('#invoiceTaxAmountHelpMe')
                                .html('').empty();
                        $invoiceDiscountAmount.val('');
                        $('#invoiceDiscountAmountHelpMe')
                                .html('').empty();
                        $invoiceShippingAmount.val('');
                        $('#invoiceShippingAmountHelpMe')
                                .html('').empty();
                        $invoiceInterestRate.val('');
                        $('#invoiceInterestRateHelpMe')
                                .html('').empty();
                        $invoiceDate.val('');
                        $('#invoiceDateHelpMe')
                                .html('').empty();
                        $invoiceStartDate.val('');
                        $('#invoiceStartDateHelpMe')
                                .html('').empty();
                        $invoiceEndDate.val('');
                        $('#invoiceEndDateHelpMe')
                                .html('').empty();
                        $invoiceDueDate.val('');
                        $('#invoiceDueDateHelpMe')
                                .html('').empty();
                        $invoicePromiseDate.val('');
                        $('#invoicePromiseDateHelpMe')
                                .html('').empty();
                        $invoiceShippingDate.val('');
                        $('#invoiceShippingDateHelpMe')
                                .html('').empty();
                        $invoicePeriod.val('');
                        $('#invoicePeriodHelpMe')
                                .html('').empty();
                        $invoiceDescription.val('');
                        $('#invoiceDescriptionHelpMe')
                                .html('').empty();
                        $invoiceRemark.val('');
                        $('#invoiceRemarkHelpMe')
                                .html('').empty();
                    } else if (success === false) {
                        $infoPanel
                                .html('').empty()
                                .html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError')
                            .html('').empty()
                            .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid')
                            .removeClass().addClass('row-fluid');
                }
            });
        } else if (type === 2) {
            // new record and update  or delete record
            if ($businessPartnerId.val().length === 0) {
                $('#businessPartnerIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $businessPartnerId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerContactId.val().length === 0) {
                $('#businessPartnerContactIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerContactIdLabel'] + " </span>");
                $businessPartnerContactId.data('chosen').activate_action();
                return false;
            }
            if ($countryId.val().length === 0) {
                $('#countryIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + " </span>");
                $countryId.data('chosen').activate_action();
                return false;
            }
            if ($invoiceProjectId.val().length === 0) {
                $('#invoiceProjectIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceProjectIdLabel'] + " </span>");
                $invoiceProjectId.data('chosen').activate_action();
                return false;
            }
            if ($paymentTermId.val().length === 0) {
                $('#paymentTermIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentTermIdLabel'] + " </span>");
                $paymentTermId.data('chosen').activate_action();
                return false;
            }
            if ($invoiceProcessId.val().length === 0) {
                $('#invoiceProcessIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceProcessIdLabel'] + " </span>");
                $invoiceProcessId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerAddress.val().length === 0) {
                $('#businessPartnerAddressHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerAddressLabel'] + " </span>");
                $('#businessPartnerAddressForm')
                        .removeClass().addClass('form-group has-error');
                $businessPartnerAddress.focus();
                return false;
            }
            if ($invoiceQuotationNumber.val().length === 0) {
                $('#invoiceQuotationNumberHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceQuotationNumberLabel'] + " </span>");
                $('#invoiceQuotationNumberForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceQuotationNumber.focus();
                return false;
            }
            if ($invoiceNumber.val().length === 0) {
                $('#invoiceNumberHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceNumberLabel'] + " </span>");
                $('#invoiceNumberForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceNumber.focus();
                return false;
            }
            if ($referenceNumber.val().length === 0) {
                $('#referenceNumberHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['referenceNumberLabel'] + " </span>");
                $('#referenceNumberForm')
                        .removeClass().addClass('form-group has-error');
                $referenceNumber.focus();
                return false;
            }
            if ($invoiceCode.val().length === 0) {
                $('#invoiceCodeHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceCodeLabel'] + " </span>");
                $('#invoiceCodeForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceCode.focus();
                return false;
            }
            if ($invoiceTotalAmount.val().length === 0) {
                $('#invoiceTotalAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceTotalAmountLabel'] + " </span>");
                $('#invoiceTotalAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceTotalAmount.focus();
                return false;
            }
            if ($invoiceTextAmount.val().length === 0) {
                $('#invoiceTextAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceTextAmountLabel'] + " </span>");
                $('#invoiceTextAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceTextAmount.focus();
                return false;
            }
            if ($invoiceTaxAmount.val().length === 0) {
                $('#invoiceTaxAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceTaxAmountLabel'] + " </span>");
                $('#invoiceTaxAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceTaxAmount.focus();
                return false;
            }
            if ($invoiceDiscountAmount.val().length === 0) {
                $('#invoiceDiscountAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDiscountAmountLabel'] + " </span>");
                $('#invoiceDiscountAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceDiscountAmount.focus();
                return false;
            }
            if ($invoiceShippingAmount.val().length === 0) {
                $('#invoiceShippingAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceShippingAmountLabel'] + " </span>");
                $('#invoiceShippingAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceShippingAmount.focus();
                return false;
            }
            if ($invoiceInterestRate.val().length === 0) {
                $('#invoiceInterestRateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceInterestRateLabel'] + " </span>");
                $('#invoiceInterestRateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceInterestRate.focus();
                return false;
            }
            if ($invoiceDate.val().length === 0) {
                $('#invoiceDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDateLabel'] + " </span>");
                $('#invoiceDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceDate.focus();
                return false;
            }
            if ($invoiceStartDate.val().length === 0) {
                $('#invoiceStartDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceStartDateLabel'] + " </span>");
                $('#invoiceStartDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceStartDate.focus();
                return false;
            }
            if ($invoiceEndDate.val().length === 0) {
                $('#invoiceEndDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceEndDateLabel'] + " </span>");
                $('#invoiceEndDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceEndDate.focus();
                return false;
            }
            if ($invoiceDueDate.val().length === 0) {
                $('#invoiceDueDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDueDateLabel'] + " </span>");
                $('#invoiceDueDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceDueDate.focus();
                return false;
            }
            if ($invoicePromiseDate.val().length === 0) {
                $('#invoicePromiseDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoicePromiseDateLabel'] + " </span>");
                $('#invoicePromiseDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoicePromiseDate.focus();
                return false;
            }
            if ($invoiceShippingDate.val().length === 0) {
                $('#invoiceShippingDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceShippingDateLabel'] + " </span>");
                $('#invoiceShippingDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceShippingDate.focus();
                return false;
            }
            if ($invoicePeriod.val().length === 0) {
                $('#invoicePeriodHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoicePeriodLabel'] + " </span>");
                $('#invoicePeriodForm')
                        .removeClass().addClass('form-group has-error');
                $invoicePeriod.focus();
                return false;
            }
            if ($invoiceDescription.val().length === 0) {
                $('#invoiceDescriptionHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDescriptionLabel'] + " </span>");
                $('#invoiceDescriptionForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceDescription.focus();
                return false;
            }
            if ($invoiceRemark.val().length === 0) {
                $('#invoiceRemarkHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRemarkLabel'] + " </span>");
                $('#invoiceRemarkForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceRemark.focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    businessPartnerId: $businessPartnerId.val(),
                    businessPartnerContactId: $businessPartnerContactId.val(),
                    countryId: $countryId.val(),
                    invoiceProjectId: $invoiceProjectId.val(),
                    paymentTermId: $paymentTermId.val(),
                    invoiceProcessId: $invoiceProcessId.val(),
                    businessPartnerAddress: $businessPartnerAddress.val(),
                    invoiceQuotationNumber: $invoiceQuotationNumber.val(),
                    invoiceNumber: $invoiceNumber.val(),
                    referenceNumber: $referenceNumber.val(),
                    invoiceCode: $invoiceCode.val(),
                    invoiceTotalAmount: $invoiceTotalAmount.val(),
                    invoiceTextAmount: $invoiceTextAmount.val(),
                    invoiceTaxAmount: $invoiceTaxAmount.val(),
                    invoiceDiscountAmount: $invoiceDiscountAmount.val(),
                    invoiceShippingAmount: $invoiceShippingAmount.val(),
                    invoiceInterestRate: $invoiceInterestRate.val(),
                    invoiceDate: $invoiceDate.val(),
                    invoiceStartDate: $invoiceStartDate.val(),
                    invoiceEndDate: $invoiceEndDate.val(),
                    invoiceDueDate: $invoiceDueDate.val(),
                    invoicePromiseDate: $invoicePromiseDate.val(),
                    invoiceShippingDate: $invoiceShippingDate.val(),
                    invoicePeriod: $invoicePeriod.val(),
                    invoiceDescription: $invoiceDescription.val(),
                    invoiceRemark: $invoiceRemark.val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel
                            .html('').empty()
                            .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    // successful request; do something with the data
                    var $infoPanel = $('#infoPanel');
                    var success = data.success;
                    var smileyLol = './images/icons/smiley-lol.png';
                    var message = data.message;
                    if (success === true) {
                        $infoPanel
                                .html('').empty()
                                .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>");
                        $('#invoiceId').val(data.invoiceId);
                        //$('#documentNumber').val(data.documentNumber); 
                        $('#newRecordButton1')
                                .removeClass().addClass('btn btn-success disabled');
                        $('#newRecordButton2')
                                .removeClass().addClass('btn dropdown-toggle btn-success disabled');
                        $('#newRecordButton3').attr('onClick', '');
                        $('#newRecordButton4').attr('onClick', '');
                        $('#newRecordButton5').attr('onClick', '');
                        $('#newRecordButton6').attr('onClick', '');
                        $('#newRecordButton7').attr('onClick', '');
                        if (updateAccess === 1) {
                            $('#updateRecordButton1')
                                    .removeClass().addClass('btn btn-info');
                            $('#updateRecordButton2')
                                    .removeClass().addClass('btn dropdown-toggle btn-info');
                            $('#updateRecordButton3').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1)");
                            $('#updateRecordButton4').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',2)");
                            $('#updateRecordButton5').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',3)");
                        } else {
                            $('#updateRecordButton1')
                                    .removeClass().addClass('btn btn-info disabled');
                            $('#updateRecordButton2')
                                    .removeClass().addClass('btn dropdown-toggle btn-info disabled');
                            $('#updateRecordButton3').attr('onClick', '');
                            $('#updateRecordButton4').attr('onClick', '');
                            $('#updateRecordButton5').attr('onClick', '');
                        }
                        if (deleteAccess === 1) {
                            $('#deleteRecordButton')
                                    .removeClass().addClass('btn btn-danger')
                                    .attr('onClick', "deleteRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "')");
                        } else {
                            $('#deleteRecordButton')
                                    .removeClass().addClass('btn btn-danger')
                                    .attr('onClick', '');
                        }
                        $("#invoiceId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#productId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#unitOfMeasurementId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#discountId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#taxId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#invoiceDetailLineNumber9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#invoiceDetailQuantity9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#invoiceDetailDescription9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#invoiceDetailPrice9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#invoiceDetailDiscount9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#invoiceDetailTax9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#invoiceDetailTotalPrice9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#isRule789999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#executeBy9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                    } else if (success === false) {
                        $infoPanel
                                .html('').empty()
                                .html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError')
                            .html('').empty()
                            .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid')
                            .removeClass().addClass('row-fluid');
                }
            });
        } else if (type === 5) {
            if ($businessPartnerId.val().length === 0) {
                $('#businessPartnerIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $businessPartnerId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerContactId.val().length === 0) {
                $('#businessPartnerContactIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerContactIdLabel'] + " </span>");
                $businessPartnerContactId.data('chosen').activate_action();
                return false;
            }
            if ($countryId.val().length === 0) {
                $('#countryIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + " </span>");
                $countryId.data('chosen').activate_action();
                return false;
            }
            if ($invoiceProjectId.val().length === 0) {
                $('#invoiceProjectIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceProjectIdLabel'] + " </span>");
                $invoiceProjectId.data('chosen').activate_action();
                return false;
            }
            if ($paymentTermId.val().length === 0) {
                $('#paymentTermIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentTermIdLabel'] + " </span>");
                $paymentTermId.data('chosen').activate_action();
                return false;
            }
            if ($invoiceProcessId.val().length === 0) {
                $('#invoiceProcessIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceProcessIdLabel'] + " </span>");
                $invoiceProcessId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerAddress.val().length === 0) {
                $('#businessPartnerAddressHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerAddressLabel'] + " </span>");
                $('#businessPartnerAddressForm')
                        .removeClass().addClass('form-group has-error');
                $businessPartnerAddress.focus();
                return false;
            }
            if ($invoiceQuotationNumber.val().length === 0) {
                $('#invoiceQuotationNumberHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceQuotationNumberLabel'] + " </span>");
                $('#invoiceQuotationNumberForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceQuotationNumber.focus();
                return false;
            }
            if ($invoiceNumber.val().length === 0) {
                $('#invoiceNumberHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceNumberLabel'] + " </span>");
                $('#invoiceNumberForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceNumber.focus();
                return false;
            }
            if ($referenceNumber.val().length === 0) {
                $('#referenceNumberHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['referenceNumberLabel'] + " </span>");
                $('#referenceNumberForm')
                        .removeClass().addClass('form-group has-error');
                $referenceNumber.focus();
                return false;
            }
            if ($invoiceCode.val().length === 0) {
                $('#invoiceCodeHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceCodeLabel'] + " </span>");
                $('#invoiceCodeForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceCode.focus();
                return false;
            }
            if ($invoiceTotalAmount.val().length === 0) {
                $('#invoiceTotalAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceTotalAmountLabel'] + " </span>");
                $('#invoiceTotalAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceTotalAmount.focus();
                return false;
            }
            if ($invoiceTextAmount.val().length === 0) {
                $('#invoiceTextAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceTextAmountLabel'] + " </span>");
                $('#invoiceTextAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceTextAmount.focus();
                return false;
            }
            if ($invoiceTaxAmount.val().length === 0) {
                $('#invoiceTaxAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceTaxAmountLabel'] + " </span>");
                $('#invoiceTaxAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceTaxAmount.focus();
                return false;
            }
            if ($invoiceDiscountAmount.val().length === 0) {
                $('#invoiceDiscountAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDiscountAmountLabel'] + " </span>");
                $('#invoiceDiscountAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceDiscountAmount.focus();
                return false;
            }
            if ($invoiceShippingAmount.val().length === 0) {
                $('#invoiceShippingAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceShippingAmountLabel'] + " </span>");
                $('#invoiceShippingAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceShippingAmount.focus();
                return false;
            }
            if ($invoiceInterestRate.val().length === 0) {
                $('#invoiceInterestRateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceInterestRateLabel'] + " </span>");
                $('#invoiceInterestRateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceInterestRate.focus();
                return false;
            }
            if ($invoiceDate.val().length === 0) {
                $('#invoiceDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDateLabel'] + " </span>");
                $('#invoiceDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceDate.focus();
                return false;
            }
            if ($invoiceStartDate.val().length === 0) {
                $('#invoiceStartDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceStartDateLabel'] + " </span>");
                $('#invoiceStartDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceStartDate.focus();
                return false;
            }
            if ($invoiceEndDate.val().length === 0) {
                $('#invoiceEndDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceEndDateLabel'] + " </span>");
                $('#invoiceEndDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceEndDate.focus();
                return false;
            }
            if ($invoiceDueDate.val().length === 0) {
                $('#invoiceDueDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDueDateLabel'] + " </span>");
                $('#invoiceDueDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceDueDate.focus();
                return false;
            }
            if ($invoicePromiseDate.val().length === 0) {
                $('#invoicePromiseDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoicePromiseDateLabel'] + " </span>");
                $('#invoicePromiseDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoicePromiseDate.focus();
                return false;
            }
            if ($invoiceShippingDate.val().length === 0) {
                $('#invoiceShippingDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceShippingDateLabel'] + " </span>");
                $('#invoiceShippingDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceShippingDate.focus();
                return false;
            }
            if ($invoicePeriod.val().length === 0) {
                $('#invoicePeriodHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoicePeriodLabel'] + " </span>");
                $('#invoicePeriodForm')
                        .removeClass().addClass('form-group has-error');
                $invoicePeriod.focus();
                return false;
            }
            if ($invoiceDescription.val().length === 0) {
                $('#invoiceDescriptionHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDescriptionLabel'] + " </span>");
                $('#invoiceDescriptionForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceDescription.focus();
                return false;
            }
            if ($invoiceRemark.val().length === 0) {
                $('#invoiceRemarkHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRemarkLabel'] + " </span>");
                $('#invoiceRemarkForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceRemark.focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    businessPartnerId: $businessPartnerId.val(),
                    businessPartnerContactId: $businessPartnerContactId.val(),
                    countryId: $countryId.val(),
                    invoiceProjectId: $invoiceProjectId.val(),
                    paymentTermId: $paymentTermId.val(),
                    invoiceProcessId: $invoiceProcessId.val(),
                    businessPartnerAddress: $businessPartnerAddress.val(),
                    invoiceQuotationNumber: $invoiceQuotationNumber.val(),
                    invoiceNumber: $invoiceNumber.val(),
                    referenceNumber: $referenceNumber.val(),
                    invoiceCode: $invoiceCode.val(),
                    invoiceTotalAmount: $invoiceTotalAmount.val(),
                    invoiceTextAmount: $invoiceTextAmount.val(),
                    invoiceTaxAmount: $invoiceTaxAmount.val(),
                    invoiceDiscountAmount: $invoiceDiscountAmount.val(),
                    invoiceShippingAmount: $invoiceShippingAmount.val(),
                    invoiceInterestRate: $invoiceInterestRate.val(),
                    invoiceDate: $invoiceDate.val(),
                    invoiceStartDate: $invoiceStartDate.val(),
                    invoiceEndDate: $invoiceEndDate.val(),
                    invoiceDueDate: $invoiceDueDate.val(),
                    invoicePromiseDate: $invoicePromiseDate.val(),
                    invoiceShippingDate: $invoiceShippingDate.val(),
                    invoicePeriod: $invoicePeriod.val(),
                    invoiceDescription: $invoiceDescription.val(),
                    invoiceRemark: $invoiceRemark.val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel
                            .html('').empty()
                            .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                        $infoPanel
                                .html('').empty()
                                .html("<span class='label label-danger'> <img src='" + smileyRollSweat + "'> " + message + "</span>");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError')
                            .html('').empty()
                            .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid')
                            .removeClass().addClass('row-fluid');
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
    var $invoiceId = $('#invoiceId');
    var $businessPartnerId = $('#businessPartnerId');
    var $businessPartnerContactId = $('#businessPartnerContactId');
    var $countryId = $('#countryId');
    var $invoiceProjectId = $('#invoiceProjectId');
    var $paymentTermId = $('#paymentTermId');
    var $invoiceProcessId = $('#invoiceProcessId');
    var $businessPartnerAddress = $('#businessPartnerAddress');
    var $invoiceQuotationNumber = $('#invoiceQuotationNumber');
    var $invoiceNumber = $('#invoiceNumber');
    var $referenceNumber = $('#referenceNumber');
    var $invoiceCode = $('#invoiceCode');
    var $invoiceTotalAmount = $('#invoiceTotalAmount');
    var $invoiceTextAmount = $('#invoiceTextAmount');
    var $invoiceTaxAmount = $('#invoiceTaxAmount');
    var $invoiceDiscountAmount = $('#invoiceDiscountAmount');
    var $invoiceShippingAmount = $('#invoiceShippingAmount');
    var $invoiceInterestRate = $('#invoiceInterestRate');
    var $invoiceDate = $('#invoiceDate');
    var $invoiceStartDate = $('#invoiceStartDate');
    var $invoiceEndDate = $('#invoiceEndDate');
    var $invoiceDueDate = $('#invoiceDueDate');
    var $invoicePromiseDate = $('#invoicePromiseDate');
    var $invoiceShippingDate = $('#invoiceShippingDate');
    var $invoicePeriod = $('#invoicePeriod');
    var $invoiceDescription = $('#invoiceDescription');
    var $invoiceRemark = $('#invoiceRemark');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $infoPanel
                .empty().html('');
        if (type === 1) {
            if ($businessPartnerId.val().length === 0) {
                $('#businessPartnerIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $businessPartnerId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerContactId.val().length === 0) {
                $('#businessPartnerContactIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerContactIdLabel'] + " </span>");
                $businessPartnerContactId.data('chosen').activate_action();
                return false;
            }
            if ($countryId.val().length === 0) {
                $('#countryIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + " </span>");
                $countryId.data('chosen').activate_action();
                return false;
            }
            if ($invoiceProjectId.val().length === 0) {
                $('#invoiceProjectIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceProjectIdLabel'] + " </span>");
                $invoiceProjectId.data('chosen').activate_action();
                return false;
            }
            if ($paymentTermId.val().length === 0) {
                $('#paymentTermIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentTermIdLabel'] + " </span>");
                $paymentTermId.data('chosen').activate_action();
                return false;
            }
            if ($invoiceProcessId.val().length === 0) {
                $('#invoiceProcessIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceProcessIdLabel'] + " </span>");
                $invoiceProcessId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerAddress.val().length === 0) {
                $('#businessPartnerAddressHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerAddressLabel'] + " </span>");
                $('#businessPartnerAddressForm')
                        .removeClass().addClass('form-group has-error');
                $businessPartnerAddress.focus();
                return false;
            }
            if ($invoiceQuotationNumber.val().length === 0) {
                $('#invoiceQuotationNumberHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceQuotationNumberLabel'] + " </span>");
                $('#invoiceQuotationNumberForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceQuotationNumber.focus();
                return false;
            }
            if ($invoiceNumber.val().length === 0) {
                $('#invoiceNumberHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceNumberLabel'] + " </span>");
                $('#invoiceNumberForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceNumber.focus();
                return false;
            }
            if ($referenceNumber.val().length === 0) {
                $('#referenceNumberHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['referenceNumberLabel'] + " </span>");
                $('#referenceNumberForm')
                        .removeClass().addClass('form-group has-error');
                $referenceNumber.focus();
                return false;
            }
            if ($invoiceCode.val().length === 0) {
                $('#invoiceCodeHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceCodeLabel'] + " </span>");
                $('#invoiceCodeForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceCode.focus();
                return false;
            }
            if ($invoiceTotalAmount.val().length === 0) {
                $('#invoiceTotalAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceTotalAmountLabel'] + " </span>");
                $('#invoiceTotalAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceTotalAmount.focus();
                return false;
            }
            if ($invoiceTextAmount.val().length === 0) {
                $('#invoiceTextAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceTextAmountLabel'] + " </span>");
                $('#invoiceTextAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceTextAmount.focus();
                return false;
            }
            if ($invoiceTaxAmount.val().length === 0) {
                $('#invoiceTaxAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceTaxAmountLabel'] + " </span>");
                $('#invoiceTaxAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceTaxAmount.focus();
                return false;
            }
            if ($invoiceDiscountAmount.val().length === 0) {
                $('#invoiceDiscountAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDiscountAmountLabel'] + " </span>");
                $('#invoiceDiscountAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceDiscountAmount.focus();
                return false;
            }
            if ($invoiceShippingAmount.val().length === 0) {
                $('#invoiceShippingAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceShippingAmountLabel'] + " </span>");
                $('#invoiceShippingAmountForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceShippingAmount.focus();
                return false;
            }
            if ($invoiceInterestRate.val().length === 0) {
                $('#invoiceInterestRateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceInterestRateLabel'] + " </span>");
                $('#invoiceInterestRateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceInterestRate.focus();
                return false;
            }
            if ($invoiceDate.val().length === 0) {
                $('#invoiceDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDateLabel'] + " </span>");
                $('#invoiceDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceDate.focus();
                return false;
            }
            if ($invoiceStartDate.val().length === 0) {
                $('#invoiceStartDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceStartDateLabel'] + " </span>");
                $('#invoiceStartDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceStartDate.focus();
                return false;
            }
            if ($invoiceEndDate.val().length === 0) {
                $('#invoiceEndDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceEndDateLabel'] + " </span>");
                $('#invoiceEndDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceEndDate.focus();
                return false;
            }
            if ($invoiceDueDate.val().length === 0) {
                $('#invoiceDueDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDueDateLabel'] + " </span>");
                $('#invoiceDueDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceDueDate.focus();
                return false;
            }
            if ($invoicePromiseDate.val().length === 0) {
                $('#invoicePromiseDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoicePromiseDateLabel'] + " </span>");
                $('#invoicePromiseDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoicePromiseDate.focus();
                return false;
            }
            if ($invoiceShippingDate.val().length === 0) {
                $('#invoiceShippingDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceShippingDateLabel'] + " </span>");
                $('#invoiceShippingDateForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceShippingDate.focus();
                return false;
            }
            if ($invoicePeriod.val().length === 0) {
                $('#invoicePeriodHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoicePeriodLabel'] + " </span>");
                $('#invoicePeriodForm')
                        .removeClass().addClass('form-group has-error');
                $invoicePeriod.focus();
                return false;
            }
            if ($invoiceDescription.val().length === 0) {
                $('#invoiceDescriptionHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDescriptionLabel'] + " </span>");
                $('#invoiceDescriptionForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceDescription.focus();
                return false;
            }
            if ($invoiceRemark.val().length === 0) {
                $('#invoiceRemarkHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRemarkLabel'] + " </span>");
                $('#invoiceRemarkForm')
                        .removeClass().addClass('form-group has-error');
                $invoiceRemark.focus();
                return false;
            }
            $infoPanel
                    .html('').empty();
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'save',
                    output: 'json',
                    invoiceId: $invoiceId.val(),
                    businessPartnerId: $businessPartnerId.val(),
                    businessPartnerContactId: $businessPartnerContactId.val(),
                    countryId: $countryId.val(),
                    invoiceProjectId: $invoiceProjectId.val(),
                    paymentTermId: $paymentTermId.val(),
                    invoiceProcessId: $invoiceProcessId.val(),
                    businessPartnerAddress: $businessPartnerAddress.val(),
                    invoiceQuotationNumber: $invoiceQuotationNumber.val(),
                    invoiceNumber: $invoiceNumber.val(),
                    referenceNumber: $referenceNumber.val(),
                    invoiceCode: $invoiceCode.val(),
                    invoiceTotalAmount: $invoiceTotalAmount.val(),
                    invoiceTextAmount: $invoiceTextAmount.val(),
                    invoiceTaxAmount: $invoiceTaxAmount.val(),
                    invoiceDiscountAmount: $invoiceDiscountAmount.val(),
                    invoiceShippingAmount: $invoiceShippingAmount.val(),
                    invoiceInterestRate: $invoiceInterestRate.val(),
                    invoiceDate: $invoiceDate.val(),
                    invoiceStartDate: $invoiceStartDate.val(),
                    invoiceEndDate: $invoiceEndDate.val(),
                    invoiceDueDate: $invoiceDueDate.val(),
                    invoicePromiseDate: $invoicePromiseDate.val(),
                    invoiceShippingDate: $invoiceShippingDate.val(),
                    invoicePeriod: $invoicePeriod.val(),
                    invoiceDescription: $invoiceDescription.val(),
                    invoiceRemark: $invoiceRemark.val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel
                            .html('').empty()
                            .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                        $infoPanel
                                .html('').empty()
                                .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['updateRecordTextLabel']) + "</span>");
                        if (deleteAccess === 1) {
                            $('#deleteRecordButton')
                                    .removeClass().addClass('btn btn-danger')
                                    .attr('onClick', "deleteRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + deleteAccess + ")");
                        } else {
                            $('#deleteRecordButton')
                                    .removeClass().addClass('btn btn-danger')
                                    .attr('onClick', '');
                        }
                    } else if (success === false) {
                        $infoPanel.empty().html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError')
                            .html('').empty()
                            .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid')
                            .removeClass().addClass('row-fluid');
                }
            });
        } else if (type === 3) {
            // update record and listing
            if ($businessPartnerId.val().length === 0) {
                $('#businessPartnerIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $businessPartnerId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerContactId.val().length === 0) {
                $('#businessPartnerContactIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerContactIdLabel'] + " </span>");
                $businessPartnerContactId.data('chosen').activate_action();
                return false;
            }
            if ($countryId.val().length === 0) {
                $('#countryIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + " </span>");
                $countryId.data('chosen').activate_action();
                return false;
            }
            if ($invoiceProjectId.val().length === 0) {
                $('#invoiceProjectIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceProjectIdLabel'] + " </span>");
                $invoiceProjectId.data('chosen').activate_action();
                return false;
            }
            if ($paymentTermId.val().length === 0) {
                $('#paymentTermIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentTermIdLabel'] + " </span>");
                $paymentTermId.data('chosen').activate_action();
                return false;
            }
            if ($invoiceProcessId.val().length === 0) {
                $('#invoiceProcessIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceProcessIdLabel'] + " </span>");
                $invoiceProcessId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerAddress.val().length === 0) {
                $('#businessPartnerAddressHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerAddressLabel'] + " </span>");
                $('#businessPartnerAddressForm').removeClass().addClass('form-group has-error');
                $businessPartnerAddress.focus();
                return false;
            }
            if ($invoiceQuotationNumber.val().length === 0) {
                $('#invoiceQuotationNumberHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceQuotationNumberLabel'] + " </span>");
                $('#invoiceQuotationNumberForm').removeClass().addClass('form-group has-error');
                $invoiceQuotationNumber.focus();
                return false;
            }
            if ($invoiceNumber.val().length === 0) {
                $('#invoiceNumberHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceNumberLabel'] + " </span>");
                $('#invoiceNumberForm').removeClass().addClass('form-group has-error');
                $invoiceNumber.focus();
                return false;
            }
            if ($referenceNumber.val().length === 0) {
                $('#referenceNumberHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['referenceNumberLabel'] + " </span>");
                $('#referenceNumberForm').removeClass().addClass('form-group has-error');
                $referenceNumber.focus();
                return false;
            }
            if ($invoiceCode.val().length === 0) {
                $('#invoiceCodeHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceCodeLabel'] + " </span>");
                $('#invoiceCodeForm').removeClass().addClass('form-group has-error');
                $invoiceCode.focus();
                return false;
            }
            if ($invoiceTotalAmount.val().length === 0) {
                $('#invoiceTotalAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceTotalAmountLabel'] + " </span>");
                $('#invoiceTotalAmountForm').removeClass().addClass('form-group has-error');
                $invoiceTotalAmount.focus();
                return false;
            }
            if ($invoiceTextAmount.val().length === 0) {
                $('#invoiceTextAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceTextAmountLabel'] + " </span>");
                $('#invoiceTextAmountForm').removeClass().addClass('form-group has-error');
                $invoiceTextAmount.focus();
                return false;
            }
            if ($invoiceTaxAmount.val().length === 0) {
                $('#invoiceTaxAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceTaxAmountLabel'] + " </span>");
                $('#invoiceTaxAmountForm').removeClass().addClass('form-group has-error');
                $invoiceTaxAmount.focus();
                return false;
            }
            if ($invoiceDiscountAmount.val().length === 0) {
                $('#invoiceDiscountAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDiscountAmountLabel'] + " </span>");
                $('#invoiceDiscountAmountForm').removeClass().addClass('form-group has-error');
                $invoiceDiscountAmount.focus();
                return false;
            }
            if ($invoiceShippingAmount.val().length === 0) {
                $('#invoiceShippingAmountHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceShippingAmountLabel'] + " </span>");
                $('#invoiceShippingAmountForm').removeClass().addClass('form-group has-error');
                $invoiceShippingAmount.focus();
                return false;
            }
            if ($invoiceInterestRate.val().length === 0) {
                $('#invoiceInterestRateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceInterestRateLabel'] + " </span>");
                $('#invoiceInterestRateForm').removeClass().addClass('form-group has-error');
                $invoiceInterestRate.focus();
                return false;
            }
            if ($invoiceDate.val().length === 0) {
                $('#invoiceDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDateLabel'] + " </span>");
                $('#invoiceDateForm').removeClass().addClass('form-group has-error');
                $invoiceDate.focus();
                return false;
            }
            if ($invoiceStartDate.val().length === 0) {
                $('#invoiceStartDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceStartDateLabel'] + " </span>");
                $('#invoiceStartDateForm').removeClass().addClass('form-group has-error');
                $invoiceStartDate.focus();
                return false;
            }
            if ($invoiceEndDate.val().length === 0) {
                $('#invoiceEndDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceEndDateLabel'] + " </span>");
                $('#invoiceEndDateForm').removeClass().addClass('form-group has-error');
                $invoiceEndDate.focus();
                return false;
            }
            if ($invoiceDueDate.val().length === 0) {
                $('#invoiceDueDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDueDateLabel'] + " </span>");
                $('#invoiceDueDateForm').removeClass().addClass('form-group has-error');
                $invoiceDueDate.focus();
                return false;
            }
            if ($invoicePromiseDate.val().length === 0) {
                $('#invoicePromiseDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoicePromiseDateLabel'] + " </span>");
                $('#invoicePromiseDateForm').removeClass().addClass('form-group has-error');
                $invoicePromiseDate.focus();
                return false;
            }
            if ($invoiceShippingDate.val().length === 0) {
                $('#invoiceShippingDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceShippingDateLabel'] + " </span>");
                $('#invoiceShippingDateForm').removeClass().addClass('form-group has-error');
                $invoiceShippingDate.focus();
                return false;
            }
            if ($invoicePeriod.val().length === 0) {
                $('#invoicePeriodHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoicePeriodLabel'] + " </span>");
                $('#invoicePeriodForm').removeClass().addClass('form-group has-error');
                $invoicePeriod.focus();
                return false;
            }
            if ($invoiceDescription.val().length === 0) {
                $('#invoiceDescriptionHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceDescriptionLabel'] + " </span>");
                $('#invoiceDescriptionForm').removeClass().addClass('form-group has-error');
                $invoiceDescription.focus();
                return false;
            }
            if ($invoiceRemark.val().length === 0) {
                $('#invoiceRemarkHelpMe')
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRemarkLabel'] + " </span>");
                $('#invoiceRemarkForm').removeClass().addClass('form-group has-error');
                $invoiceRemark.focus();
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
                    invoiceId: $invoiceId.val(),
                    businessPartnerId: $businessPartnerId.val(),
                    businessPartnerContactId: $businessPartnerContactId.val(),
                    countryId: $countryId.val(),
                    invoiceProjectId: $invoiceProjectId.val(),
                    paymentTermId: $paymentTermId.val(),
                    invoiceProcessId: $invoiceProcessId.val(),
                    businessPartnerAddress: $businessPartnerAddress.val(),
                    invoiceQuotationNumber: $invoiceQuotationNumber.val(),
                    invoiceNumber: $invoiceNumber.val(),
                    referenceNumber: $referenceNumber.val(),
                    invoiceCode: $invoiceCode.val(),
                    invoiceTotalAmount: $invoiceTotalAmount.val(),
                    invoiceTextAmount: $invoiceTextAmount.val(),
                    invoiceTaxAmount: $invoiceTaxAmount.val(),
                    invoiceDiscountAmount: $invoiceDiscountAmount.val(),
                    invoiceShippingAmount: $invoiceShippingAmount.val(),
                    invoiceInterestRate: $invoiceInterestRate.val(),
                    invoiceDate: $invoiceDate.val(),
                    invoiceStartDate: $invoiceStartDate.val(),
                    invoiceEndDate: $invoiceEndDate.val(),
                    invoiceDueDate: $invoiceDueDate.val(),
                    invoicePromiseDate: $invoicePromiseDate.val(),
                    invoiceShippingDate: $invoiceShippingDate.val(),
                    invoicePeriod: $invoicePeriod.val(),
                    invoiceDescription: $invoiceDescription.val(),
                    invoiceRemark: $invoiceRemark.val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel
                            .html('').empty()
                            .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                        $infoPanel
                                .html('').empty()
                                .html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                    }
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError')
                            .html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid')
                            .removeClass().addClass('row-fluid');
                }
            });
        }
    }
}
function deleteRecord(leafId, url, urlList, securityToken, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var $invoiceId = $('#invoiceId');
    var css = $('#deleteRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (deleteAccess === 1) {
            if (confirm(decodeURIComponent(t['deleteRecordMessageLabel']))) {
                var value = $invoiceId.val();
                if (!value) {
                    $infoPanel
                            .html('').empty()
                            .html("<span class='label label-danger'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "<span>");
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
                            invoiceId: $invoiceId.val(),
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            var $infoPanel = $('#infoPanel');
                            $infoPanel
                                    .html('').empty()
                                    .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                                $infoPanel
                                        .html('').empty()
                                        .html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                                if ($infoPanel.is(':hidden')) {
                                    $infoPanel.show();
                                }
                            }
                        },
                        error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError')
                                    .html('').empty()
                                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid')
                                    .removeClass().addClass('row-fluid');
                        }
                    });
                }
            } else {
                return false;
            }
        }
    }
}
function resetRecord(leafId, url, urlList, urlInvoiceDetail, securityToken, createAccess, updateAccess, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var resetIcon = './images/icons/fruit-orange.png';
    $infoPanel
            .html('').empty()
            .html("<span class='label label-danger'><img src='" + resetIcon + "'> " + decodeURIComponent(t['resetRecordTextLabel']) + "</span>").delay(1000).fadeOut();
    if ($infoPanel.is(':hidden')) {
        $infoPanel.show();
    }
    if (createAccess === 1) {
        $('#newRecordButton1')
                .removeClass().addClass('btn btn-success')
                .attr('onClick', '').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1)");
        $('#newRecordButton2')
                .attr('onClick', '')
                .removeClass().addClass('btn dropdown-toggle btn-success');
        $('#newRecordButton3').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1)");
        $('#newRecordButton4').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',2)");
        $('#newRecordButton5').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',3)");
        $('#newRecordButton6').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',4)");
        $('#newRecordButton7').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',5)");
    } else {
        $('#newRecordButton1')
                .removeClass().addClass('btn btn-success disabled').attr('onClick', '');
        $('#newRecordButton2')
                .removeClass().addClass('btn dropdown-toggle btn-success disabled');
        $('#newRecordButton3').attr('onClick', '');
        $('#newRecordButton4').attr('onClick', '');
        $('#newRecordButton5').attr('onClick', '');
        $('#newRecordButton6').attr('onClick', '');
        $('#newRecordButton7').attr('onClick', '');
    }
    $('#updateRecordButton1')
            .removeClass().addClass('btn btn-info disabled')
            .attr('onClick', '');
    $('#updateRecordButton2')
            .removeClass().addClass('btn dropdown-toggle btn-info disabled')
            .attr('onClick', '');
    $('#updateRecordButton3').attr('onClick', '');
    $('#updateRecordButton4').attr('onClick', '');
    $('#updateRecordButton5').attr('onClick', '');
    $('#deleteRecordButton')
            .removeClass().addClass('btn btn-danger disabled')
            .attr('onClick', '');
    $('#postRecordButton')
            .removeClass().addClass('btn btn-info')
            .attr('onClick', '');
    $('#firstRecordButton')
            .removeClass().addClass('btn btn-default')
            .attr('onClick', "firstRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlInvoiceDetail + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
    $('#previousRecordButton')
            .removeClass().addClass('btn btn-default disabled')
            .attr('onClick', '');
    $('#nextRecordButton')
            .removeClass().addClass('btn btn-default disabled')
            .attr('onClick', '');
    $('#endRecordButton')
            .removeClass().addClass('btn btn-default')
            .attr('onClick', "endRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlInvoiceDetail + "','" + securityToken + "'," + updateAccess + ")");
    $("#invoiceId").val('');
    $("#invoiceIdHelpMe")
            .empty().html('');
    $("#businessPartnerId").val('');
    $("#businessPartnerIdHelpMe")
            .empty().html('');
    $('#businessPartnerId').trigger("chosen:updated");
    $("#businessPartnerContactId").val('');
    $("#businessPartnerContactIdHelpMe")
            .empty().html('');
    $('#businessPartnerContactId').trigger("chosen:updated");
    $("#countryId").val('');
    $("#countryIdHelpMe")
            .empty().html('');
    $('#countryId').trigger("chosen:updated");
    $("#invoiceProjectId").val('');
    $("#invoiceProjectIdHelpMe")
            .empty().html('');
    $('#invoiceProjectId').trigger("chosen:updated");
    $("#paymentTermId").val('');
    $("#paymentTermIdHelpMe")
            .empty().html('');
    $('#paymentTermId').trigger("chosen:updated");
    $("#invoiceProcessId").val('');
    $("#invoiceProcessIdHelpMe")
            .empty().html('');
    $('#invoiceProcessId').trigger("chosen:updated");
    $("#businessPartnerAddress").val('');
    $("#businessPartnerAddressHelpMe")
            .empty().html('');
    $("#invoiceQuotationNumber").val('');
    $("#invoiceQuotationNumberHelpMe")
            .empty().html('');
    $("#invoiceNumber").val('');
    $("#invoiceNumberHelpMe")
            .empty().html('');
    $("#referenceNumber").val('');
    $("#referenceNumberHelpMe")
            .empty().html('');
    $("#invoiceCode").val('');
    $("#invoiceCodeHelpMe")
            .empty().html('');
    $("#invoiceTotalAmount").val('');
    $("#invoiceTotalAmountHelpMe")
            .empty().html('');
    $("#invoiceTextAmount").val('');
    $("#invoiceTextAmountHelpMe")
            .empty().html('');
    $("#invoiceTaxAmount").val('');
    $("#invoiceTaxAmountHelpMe")
            .empty().html('');
    $("#invoiceDiscountAmount").val('');
    $("#invoiceDiscountAmountHelpMe")
            .empty().html('');
    $("#invoiceShippingAmount").val('');
    $("#invoiceShippingAmountHelpMe")
            .empty().html('');
    $("#invoiceInterestRate").val('');
    $("#invoiceInterestRateHelpMe")
            .empty().html('');
    $("#invoiceDate").val('');
    $("#invoiceDateHelpMe")
            .empty().html('');
    $("#invoiceStartDate").val('');
    $("#invoiceStartDateHelpMe")
            .empty().html('');
    $("#invoiceEndDate").val('');
    $("#invoiceEndDateHelpMe")
            .empty().html('');
    $("#invoiceDueDate").val('');
    $("#invoiceDueDateHelpMe")
            .empty().html('');
    $("#invoicePromiseDate").val('');
    $("#invoicePromiseDateHelpMe")
            .empty().html('');
    $("#invoiceShippingDate").val('');
    $("#invoiceShippingDateHelpMe")
            .empty().html('');
    $("#invoicePeriod").val('');
    $("#invoicePeriodHelpMe")
            .empty().html('');
    $("#invoiceDescription").val('');
    $("#invoiceDescriptionHelpMe")
            .empty().html('');
    $("#invoiceRemark").val('');
    $("#invoiceRemarkHelpMe")
            .empty().html('');
    $("#invoiceDetailId9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            ;
    $("#invoiceId9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            .trigger("chosen:updated");
    $("#productId9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            .trigger("chosen:updated");
    $("#unitOfMeasurementId9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            .trigger("chosen:updated");
    $("#discountId9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            .trigger("chosen:updated");
    $("#taxId9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            .trigger("chosen:updated");
    $("#invoiceDetailLineNumber9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            ;
    $("#invoiceDetailQuantity9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            ;
    $("#invoiceDetailDescription9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            ;
    $("#invoiceDetailPrice9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            ;
    $("#invoiceDetailDiscount9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            ;
    $("#invoiceDetailTax9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            ;
    $("#invoiceDetailTotalPrice9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            ;
    $("#isRule789999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            ;
    $("#executeBy9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            ;
    $("#tableBody")
            .html('').empty();
}
function postRecord() {
    var css = $('#postRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        return false;
    }
}
function firstRecord(leafId, url, urlList, urlInvoiceDetail, securityToken, updateAccess, deleteAccess) {
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
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            },
            success: function(data) {
                var smileyRoll = './images/icons/smiley-roll.png';
                var $infoPanel = $('#infoPanel');
                var success = data.success;
                var firstRecord = data.firstRecord;
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
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
                            invoiceId: firstRecord,
                            output: 'json',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            $infoPanel
                                    .html('').empty()
                                    .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        },
                        success: function(data) {
                            var x, output;
                            var success = data.success;
                            var $infoPanel = $('#infoPanel');
                            var lastRecord = data.lastRecord;
                            var nextRecord = data.nextRecord;
                            var previousRecord = data.previousRecord;
                            if (success === true) {
                                $('#invoiceId').val(data.data.invoiceId);
                                $('#businessPartnerId').val(data.data.businessPartnerId).trigger("chosen:updated");
                                $('#businessPartnerContactId').val(data.data.businessPartnerContactId).trigger("chosen:updated");
                                $('#countryId').val(data.data.countryId).trigger("chosen:updated");
                                $('#invoiceProjectId').val(data.data.invoiceProjectId).trigger("chosen:updated");
                                $('#paymentTermId').val(data.data.paymentTermId).trigger("chosen:updated");
                                $('#invoiceProcessId').val(data.data.invoiceProcessId).trigger("chosen:updated");
                                $('#businessPartnerAddress').val(data.data.businessPartnerAddress);
                                $('#invoiceQuotationNumber').val(data.data.invoiceQuotationNumber);
                                $('#invoiceNumber').val(data.data.invoiceNumber);
                                $('#referenceNumber').val(data.data.referenceNumber);
                                $('#invoiceCode').val(data.data.invoiceCode);
                                $('#invoiceTotalAmount').val(data.data.invoiceTotalAmount);
                                $('#invoiceTextAmount').val(data.data.invoiceTextAmount);
                                $('#invoiceTaxAmount').val(data.data.invoiceTaxAmount);
                                $('#invoiceDiscountAmount').val(data.data.invoiceDiscountAmount);
                                $('#invoiceShippingAmount').val(data.data.invoiceShippingAmount);
                                $('#invoiceInterestRate').val(data.data.invoiceInterestRate);
                                x = data.data.invoiceDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#invoiceDate').val(output);
                                x = data.data.invoiceStartDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#invoiceStartDate').val(output);
                                x = data.data.invoiceEndDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#invoiceEndDate').val(output);
                                x = data.data.invoiceDueDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#invoiceDueDate').val(output);
                                x = data.data.invoicePromiseDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#invoicePromiseDate').val(output);
                                x = data.data.invoiceShippingDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#invoiceShippingDate').val(output);
                                $('#invoicePeriod').val(data.data.invoicePeriod);
                                $('#invoiceDescription').val(data.data.invoiceDescription);
                                $('#invoiceRemark').val(data.data.invoiceRemark);
                                $("#invoiceId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#productId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#unitOfMeasurementId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#discountId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#taxId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#invoiceDetailLineNumber9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        ;
                                $("#invoiceDetailQuantity9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        ;
                                $("#invoiceDetailDescription9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        ;
                                $("#invoiceDetailPrice9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        ;
                                $("#invoiceDetailDiscount9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        ;
                                $("#invoiceDetailTax9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        ;
                                $("#invoiceDetailTotalPrice9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        ;
                                $("#isRule789999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        ;
                                $("#executeBy9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        ;
                                $.ajax({
                                    type: 'POST',
                                    url: urlInvoiceDetail,
                                    data: {
                                        method: 'read',
                                        invoiceId: data.firstRecord,
                                        output: 'table',
                                        securityToken: securityToken,
                                        leafId: leafId
                                    },
                                    beforeSend: function() {
                                        var smileyRoll = './images/icons/smiley-roll.png';
                                        var $infoPanel = $('#infoPanel');
                                        $infoPanel
                                                .html('').empty()
                                                .html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                        if ($infoPanel.is(':hidden')) {
                                            $infoPanel.show();
                                        }
                                    },
                                    success: function(data) {
                                        var $infoPanel = $('#infoPanel');
                                        var smileyLol = './images/icons/smiley-lol.png';
                                        var success = data.success;
                                        var tableData = data.tableData;
                                        if (success === true) {
                                            $infoPanel
                                                    .html('').empty()
                                                    .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                            $('#tableBody')
                                                    .html('').empty()
                                                    .html(tableData);
                                            $(".chzn-select").chosen({search_contains: true});
                                            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                                        }
                                    },
                                    error: function(xhr) {
                                        var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                        $('#infoError')
                                                .html('').empty()
                                                .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                        $('#infoErrorRowFluid')
                                                .removeClass().addClass('row-fluid');
                                    }
                                });
                                if (nextRecord > 0) {
                                    $('#previousRecordButton')
                                            .removeClass().addClass('btn btn-default disabled')
                                            .attr('onClick', '');
                                    $('#nextRecordButton')
                                            .removeClass().addClass('btn btn-default')
                                            .attr('onClick', '')
                                            .attr('onClick', "nextRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlInvoiceDetail + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
                                    $('#firstRecordCounter').val(firstRecord);
                                    $('#previousRecordCounter').val(previousRecord);
                                    $('#nextRecordCounter').val(nextRecord);
                                    $('#lastRecordCounter').val(lastRecord);
                                    $('#newRecordButton1')
                                            .removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                                    $('#newRecordButton2')
                                            .removeClass().addClass('btn dropdown-toggle btn-success disabled');
                                    $('#newRecordButton3').attr('onClick', '');
                                    $('#newRecordButton4').attr('onClick', '');
                                    $('#newRecordButton5').attr('onClick', '');
                                    $('#newRecordButton6').attr('onClick', '');
                                    $('#newRecordButton7').attr('onClick', '');
                                    if (updateAccess === 1) {
                                        $('#updateRecordButton1')
                                                .removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1," + deleteAccess + ")");
                                        $('#updateRecordButton2')
                                                .removeClass().addClass('btn dropdown-toggle btn-info');
                                        $('#updateRecordButton3').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1," + deleteAccess + ")");
                                        $('#updateRecordButton4').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',2," + deleteAccess + ")");
                                        $('#updateRecordButton5').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',3," + deleteAccess + ")");
                                    } else {
                                        $('#updateRecordButton1')
                                                .removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                                        $('#updateRecordButton2')
                                                .removeClass().addClass('btn dropdown-toggle btn-info disabled');
                                        $('#updateRecordButton3').attr('onClick', '');
                                        $('#updateRecordButton4').attr('onClick', '');
                                        $('#updateRecordButton5').attr('onClick', '');
                                    }
                                    if (deleteAccess === 1) {
                                        $('#deleteRecordButton')
                                                .removeClass().addClass('btn btn-danger')
                                                .attr('onClick', '').attr('onClick', "deleteRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + deleteAccess + ")");
                                    } else {
                                        $('#deleteRecordButton')
                                                .removeClass().addClass('btn btn-danger')
                                                .attr('onClick', '');
                                    }
                                }
                                var startIcon = './images/icons/control-stop.png';
                                $infoPanel
                                        .html('').empty()
                                        .html("&nbsp;<img src='" + startIcon + "'> " + decodeURIComponent(t['firstButtonLabel']) + " ");
                                if ($infoPanel.is(':hidden')) {
                                    $infoPanel.show();
                                }
                            }
                        },
                        error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError')
                                    .html('').empty()
                                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid')
                                    .removeClass().addClass('row-fluid');
                        }
                    });
                } else {
                    $infoPanel
                            .html('').empty()
                            .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRollSweat + "'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }
            },
            error: function(xhr) {
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                $('#infoError')
                        .html('').empty()
                        .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid')
                        .removeClass().addClass('row-fluid');
            }
        });
    }
}
function endRecord(leafId, url, urlList, urlInvoiceDetail, securityToken, updateAccess, deleteAccess) {
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
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            },
            success: function(data) {
                var smileyRoll = './images/icons/smiley-roll.png';
                var success = data.success;
                var message = data.message;
                var lastRecord = data.lastRecord;
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
                            invoiceId: lastRecord,
                            output: 'json',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            $infoPanel
                                    .html('').empty()
                                    .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        },
                        success: function(data) {
                            var x, output;
                            var success = data.success;
                            var firstRecord = data.firstRecord;
                            var lastRecord = data.lastRecord;
                            var nextRecord = data.nextRecord;
                            var previousRecord = data.previousRecord;
                            if (success === true) {
                                $('#invoiceId').val(data.data.invoiceId);
                                $('#businessPartnerId').val(data.data.businessPartnerId).trigger("chosen:updated");
                                $('#businessPartnerContactId').val(data.data.businessPartnerContactId).trigger("chosen:updated");
                                $('#countryId').val(data.data.countryId).trigger("chosen:updated");
                                $('#invoiceProjectId').val(data.data.invoiceProjectId).trigger("chosen:updated");
                                $('#paymentTermId').val(data.data.paymentTermId).trigger("chosen:updated");
                                $('#invoiceProcessId').val(data.data.invoiceProcessId).trigger("chosen:updated");
                                $('#businessPartnerAddress').val(data.data.businessPartnerAddress);
                                $('#invoiceQuotationNumber').val(data.data.invoiceQuotationNumber);
                                $('#invoiceNumber').val(data.data.invoiceNumber);
                                $('#referenceNumber').val(data.data.referenceNumber);
                                $('#invoiceCode').val(data.data.invoiceCode);
                                $('#invoiceTotalAmount').val(data.data.invoiceTotalAmount);
                                $('#invoiceTextAmount').val(data.data.invoiceTextAmount);
                                $('#invoiceTaxAmount').val(data.data.invoiceTaxAmount);
                                $('#invoiceDiscountAmount').val(data.data.invoiceDiscountAmount);
                                $('#invoiceShippingAmount').val(data.data.invoiceShippingAmount);
                                $('#invoiceInterestRate').val(data.data.invoiceInterestRate);
                                x = data.data.invoiceDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#invoiceDate').val(output);
                                x = data.data.invoiceStartDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#invoiceStartDate').val(output);
                                x = data.data.invoiceEndDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#invoiceEndDate').val(output);
                                x = data.data.invoiceDueDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#invoiceDueDate').val(output);
                                x = data.data.invoicePromiseDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#invoicePromiseDate').val(output);
                                x = data.data.invoiceShippingDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#invoiceShippingDate').val(output);
                                $('#invoicePeriod').val(data.data.invoicePeriod);
                                $('#invoiceDescription').val(data.data.invoiceDescription);
                                $('#invoiceRemark').val(data.data.invoiceRemark);
                                $("#invoiceId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#productId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#unitOfMeasurementId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#discountId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#taxId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#invoiceDetailLineNumber9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        ;
                                $("#invoiceDetailQuantity9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        ;
                                $("#invoiceDetailDescription9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        ;
                                $("#invoiceDetailPrice9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        ;
                                $("#invoiceDetailDiscount9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        ;
                                $("#invoiceDetailTax9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        ;
                                $("#invoiceDetailTotalPrice9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        ;
                                $("#isRule789999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        ;
                                $("#executeBy9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        ;
                                $.ajax({
                                    type: 'POST',
                                    url: urlInvoiceDetail,
                                    data: {
                                        method: 'read',
                                        invoiceId: lastRecord,
                                        output: 'table',
                                        securityToken: securityToken,
                                        leafId: leafId
                                    },
                                    beforeSend: function() {
                                        var smileyRoll = './images/icons/smiley-roll.png';
                                        var $infoPanel = $('#infoPanel');
                                        $infoPanel
                                                .html('').empty()
                                                .html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                        if ($infoPanel.is(':hidden')) {
                                            $infoPanel.show();
                                        }
                                    },
                                    success: function(data) {
                                        var $infoPanel = $('#infoPanel');
                                        var success = data.success;
                                        var tableData = data.tableData;
                                        var smileyLol = './images/icons/smiley-lol.png';
                                        if (success === true) {
                                            $infoPanel
                                                    .html('').empty()
                                                    .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                            $('#tableBody')
                                                    .html('').empty()
                                                    .html(tableData);
                                            $(".chzn-select").chosen({search_contains: true});
                                            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                                        }
                                    },
                                    error: function(xhr) {
                                        var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                        $('#infoError')
                                                .html('').empty()
                                                .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                        $('#infoErrorRowFluid')
                                                .removeClass().addClass('row-fluid');
                                    }
                                });
                                if (lastRecord !== 0) {
                                    $('#previousRecordButton')
                                            .removeClass().addClass('btn btn-default')
                                            .attr('onClick', "previousRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlInvoiceDetail + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
                                    $('#nextRecordButton')
                                            .removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                                    $('#firstRecordCounter').val(firstRecord);
                                    $('#previousRecordCounter').val(previousRecord);
                                    $('#nextRecordCounter').val(nextRecord);
                                    $('#lastRecordCounter').val(lastRecord);
                                    $('#newRecordButton1')
                                            .removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                                    $('#newRecordButton2')
                                            .removeClass().addClass('btn dropdown-toggle btn-success disabled');
                                    $('#newRecordButton3').attr('onClick', '');
                                    $('#newRecordButton4').attr('onClick', '');
                                    $('#newRecordButton5').attr('onClick', '');
                                    $('#newRecordButton6').attr('onClick', '');
                                    $('#newRecordButton7').attr('onClick', '');
                                    if (updateAccess === 1) {
                                        $('#updateRecordButton1')
                                                .removeClass().addClass('btn btn-info')
                                                .attr('onClick', '').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1," + deleteAccess + ")");
                                        $('#updateRecordButton2')
                                                .removeClass().addClass('btn dropdown-toggle btn-info')
                                                .attr('onClick', '');
                                        $('#updateRecordButton3').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1," + deleteAccess + ")");
                                        $('#updateRecordButton4').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',2," + deleteAccess + ")");
                                        $('#updateRecordButton5').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',3," + deleteAccess + ")");
                                    } else {
                                        $('#updateRecordButton1')
                                                .removeClass().addClass('btn btn-info disabled')
                                                .attr('onClick', '');
                                        $('#updateRecordButton2')
                                                .removeClass().addClass('btn dropdown-toggle btn-info disabled')
                                                .attr('onClick', '');
                                        $('#updateRecordButton3').attr('onClick', '');
                                        $('#updateRecordButton4').attr('onClick', '');
                                        $('#updateRecordButton5').attr('onClick', '');
                                    }
                                    if (deleteAccess === 1) {
                                        $('#deleteRecordButton')
                                                .removeClass().addClass('btn btn-danger')
                                                .attr('onClick', "deleteRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + deleteAccess + ")");
                                    } else {
                                        $('#deleteRecordButton')
                                                .removeClass().addClass('btn btn-danger')
                                                .attr('onClick', '');
                                    }
                                }
                            }
                        },
                        error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError')
                                    .html('').empty()
                                    .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid')
                                    .removeClass().addClass('row-fluid');
                        }
                    });
                } else {
                    $infoPanel.html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                }
                var endIcon = './images/icons/control-stop-180.png';
                $infoPanel
                        .html('').empty()
                        .html("&nbsp;<img src='" + endIcon + "'> " + decodeURIComponent(t['endButtonLabel']) + " ");
            },
            error: function(xhr) {
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                $('#infoError')
                        .html('').empty()
                        .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid')
                        .removeClass().addClass('row-fluid');
            }
        });
    }
}
function previousRecord(leafId, url, urlList, urlInvoiceDetail, securityToken, updateAccess, deleteAccess) {
    var $previousRecordCounter = $('#previousRecordCounter');
    var $infoPanel = $('#infoPanel');
    var css = $('#previousRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($previousRecordCounter.val() === '' || $previousRecordCounter.val() === undefined) {
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-danger'>" + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                    invoiceId: $previousRecordCounter.val(),
                    output: 'json',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel
                            .html('').empty()
                            .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    var x, output;
                    var success = data.success;
                    var firstRecord = data.firstRecord;
                    var lastRecord = data.lastRecord;
                    var nextRecord = data.nextRecord;
                    var previousRecord = data.previousRecord;
                    var $infoPanel = $('#infoPanel');
                    if (success === true) {
                        $('#invoiceId').val(data.data.invoiceId);
                        $('#businessPartnerId').val(data.data.businessPartnerId).trigger("chosen:updated");
                        $('#businessPartnerContactId').val(data.data.businessPartnerContactId).trigger("chosen:updated");
                        $('#countryId').val(data.data.countryId).trigger("chosen:updated");
                        $('#invoiceProjectId').val(data.data.invoiceProjectId).trigger("chosen:updated");
                        $('#paymentTermId').val(data.data.paymentTermId).trigger("chosen:updated");
                        $('#invoiceProcessId').val(data.data.invoiceProcessId).trigger("chosen:updated");
                        $('#businessPartnerAddress').val(data.data.businessPartnerAddress);
                        $('#invoiceQuotationNumber').val(data.data.invoiceQuotationNumber);
                        $('#invoiceNumber').val(data.data.invoiceNumber);
                        $('#referenceNumber').val(data.data.referenceNumber);
                        $('#invoiceCode').val(data.data.invoiceCode);
                        $('#invoiceTotalAmount').val(data.data.invoiceTotalAmount);
                        $('#invoiceTextAmount').val(data.data.invoiceTextAmount);
                        $('#invoiceTaxAmount').val(data.data.invoiceTaxAmount);
                        $('#invoiceDiscountAmount').val(data.data.invoiceDiscountAmount);
                        $('#invoiceShippingAmount').val(data.data.invoiceShippingAmount);
                        $('#invoiceInterestRate').val(data.data.invoiceInterestRate);
                        x = data.data.invoiceDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#invoiceDate').val(output);
                        x = data.data.invoiceStartDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#invoiceStartDate').val(output);
                        x = data.data.invoiceEndDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#invoiceEndDate').val(output);
                        x = data.data.invoiceDueDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#invoiceDueDate').val(output);
                        x = data.data.invoicePromiseDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#invoicePromiseDate').val(output);
                        x = data.data.invoiceShippingDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#invoiceShippingDate').val(output);
                        $('#invoicePeriod').val(data.data.invoicePeriod);
                        $('#invoiceDescription').val(data.data.invoiceDescription);
                        $('#invoiceRemark').val(data.data.invoiceRemark);
                        $("#invoiceId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#productId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#unitOfMeasurementId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#discountId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#taxId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#invoiceDetailLineNumber9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#invoiceDetailQuantity9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#invoiceDetailDescription9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#invoiceDetailPrice9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#invoiceDetailDiscount9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#invoiceDetailTax9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#invoiceDetailTotalPrice9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#isRule789999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#executeBy9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $.ajax({
                            type: 'POST',
                            url: urlInvoiceDetail,
                            data: {
                                method: 'read',
                                invoiceId: $('#previousRecordCounter').val(),
                                output: 'table',
                                securityToken: securityToken,
                                leafId: leafId
                            },
                            beforeSend: function() {
                                var smileyRoll = './images/icons/smiley-roll.png';
                                var $infoPanel = $('#infoPanel');
                                $infoPanel
                                        .html('').empty()
                                        .html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                if ($infoPanel.is(':hidden')) {
                                    $infoPanel.show();
                                }
                            },
                            success: function(data) {
                                var $infoPanel = $('#infoPanel');
                                var success = data.success;
                                var tableData = data.tableData;
                                var smileyLol = './images/icons/smiley-lol.png';
                                if (success === true) {
                                    $infoPanel
                                            .html('').empty()
                                            .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                    $('#tableBody')
                                            .html('').empty()
                                            .html(tableData);
                                    $(".chzn-select").chosen({search_contains: true});
                                    $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                                }
                            },
                            error: function(xhr) {
                                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                $('#infoError')
                                        .html('').empty()
                                        .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                $('#infoErrorRowFluid')
                                        .removeClass().addClass('row-fluid');
                            }
                        });
                        $('#newRecordButton1')
                                .removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                        $('#newRecordButton2')
                                .removeClass().addClass('btn dropdown-toggle btn-success disabled').attr('onClick', '');
                        $('#newRecordButton3').attr('onClick', '');
                        $('#newRecordButton4').attr('onClick', '');
                        $('#newRecordButton5').attr('onClick', '');
                        $('#newRecordButton6').attr('onClick', '');
                        $('#newRecordButton7').attr('onClick', '');
                        $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1," + deleteAccess + ")");
                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info').attr('onClick', '');
                        $('#updateRecordButton3').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1," + deleteAccess + ")");
                        $('#updateRecordButton4').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',2," + deleteAccess + ")");
                        $('#updateRecordButton5').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',3," + deleteAccess + ")");
                    } else {
                        $('#updateRecordButton1')
                                .removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                        $('#updateRecordButton2')
                                .removeClass().addClass('btn dropdown-toggle btn-info disabled');
                        $('#updateRecordButton3').attr('onClick', '');
                        $('#updateRecordButton4').attr('onClick', '');
                        $('#updateRecordButton5').attr('onClick', '');
                    }
                    if (deleteAccess === 1) {
                        $('#deleteRecordButton')
                                .removeClass().addClass('btn btn-danger')
                                .attr('onClick', "deleteRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + deleteAccess + ")");
                    } else {
                        $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', '');
                    }
                    $('#firstRecordCounter').val(firstRecord);
                    $('#previousRecordCounter').val(previousRecord);
                    $('#nextRecordCounter').val(nextRecord);
                    $('#lastRecordCounter').val(lastRecord);
                    if (parseFloat(nextRecord) <= parseFloat(lastRecord)) {
                        $('#nextRecordButton')
                                .removeClass().addClass('btn btn-default')
                                .attr('onClick', '').attr('onClick', "nextRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlInvoiceDetail + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
                    } else {
                        $('#nextRecordButton')
                                .removeClass().addClass('btn btn-default disabled')
                                .attr('onClick', '');
                    }
                    if (parseFloat(previousRecord) === 0) {
                        var exclamationIcon = './images/icons/exclamation.png';
                        $infoPanel
                                .html('').empty()
                                .html("&nbsp;<img src='" + exclamationIcon + "'> " + decodeURIComponent(t['firstButtonLabel']) + " ");
                        $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                    } else {
                        var control = './images/icons/control-180.png';
                        $infoPanel
                                .html('').empty()
                                .html("&nbsp;<img src='" + control + "'> " + decodeURIComponent(t['previousButtonLabel']) + " ");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError')
                            .empty().html('')
                            .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid')
                            .removeClass().addClass('row-fluid');
                }
            });
        }
    }
}
function nextRecord(leafId, url, urlList, urlInvoiceDetail, securityToken, updateAccess, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var $nextRecordCounter = $('#nextRecordCounter');
    var css = $('#nextRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($nextRecordCounter.val() === '' || $nextRecordCounter.val() === undefined) {
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-danger'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                    invoiceId: $nextRecordCounter.val(),
                    output: 'json',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel
                            .html('').empty()
                            .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    var $infoPanel = $('#infoPanel');
                    var x, output;
                    var success = data.success;
                    var firstRecord = data.firstRecord;
                    var lastRecord = data.lastRecord;
                    var nextRecord = data.nextRecord;
                    var previousRecord = data.previousRecord;
                    if (success === true) {
                        $('#invoiceId').val(data.data.invoiceId);
                        $('#businessPartnerId').val(data.data.businessPartnerId).trigger("chosen:updated");
                        $('#businessPartnerContactId').val(data.data.businessPartnerContactId).trigger("chosen:updated");
                        $('#countryId').val(data.data.countryId).trigger("chosen:updated");
                        $('#invoiceProjectId').val(data.data.invoiceProjectId).trigger("chosen:updated");
                        $('#paymentTermId').val(data.data.paymentTermId).trigger("chosen:updated");
                        $('#invoiceProcessId').val(data.data.invoiceProcessId).trigger("chosen:updated");
                        $('#businessPartnerAddress').val(data.data.businessPartnerAddress);
                        $('#invoiceQuotationNumber').val(data.data.invoiceQuotationNumber);
                        $('#invoiceNumber').val(data.data.invoiceNumber);
                        $('#referenceNumber').val(data.data.referenceNumber);
                        $('#invoiceCode').val(data.data.invoiceCode);
                        $('#invoiceTotalAmount').val(data.data.invoiceTotalAmount);
                        $('#invoiceTextAmount').val(data.data.invoiceTextAmount);
                        $('#invoiceTaxAmount').val(data.data.invoiceTaxAmount);
                        $('#invoiceDiscountAmount').val(data.data.invoiceDiscountAmount);
                        $('#invoiceShippingAmount').val(data.data.invoiceShippingAmount);
                        $('#invoiceInterestRate').val(data.data.invoiceInterestRate);
                        x = data.data.invoiceDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#invoiceDate').val(output);
                        x = data.data.invoiceStartDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#invoiceStartDate').val(output);
                        x = data.data.invoiceEndDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#invoiceEndDate').val(output);
                        x = data.data.invoiceDueDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#invoiceDueDate').val(output);
                        x = data.data.invoicePromiseDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#invoicePromiseDate').val(output);
                        x = data.data.invoiceShippingDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#invoiceShippingDate').val(output);
                        $('#invoicePeriod').val(data.data.invoicePeriod);
                        $('#invoiceDescription').val(data.data.invoiceDescription);
                        $('#invoiceRemark').val(data.data.invoiceRemark);
                        $('#newRecordButton1')
                                .removeClass().addClass('btn btn-success disabled');
                        $('#newRecordButton2')
                                .removeClass().addClass('btn dropdown-toggle btn-success disabled');
                        $('#newRecordButton3').attr('onClick', '');
                        $('#newRecordButton4').attr('onClick', '');
                        $('#newRecordButton5').attr('onClick', '');
                        $('#newRecordButton6').attr('onClick', '');
                        $('#newRecordButton7').attr('onClick', '');
                        if (updateAccess === 1) {
                            $('#updateRecordButton1')
                                    .removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1,'" + deleteAccess + ")");
                            $('#updateRecordButton2')
                                    .removeClass().addClass('btn dropdown-toggle btn-info').attr('onClick', '');
                            $('#updateRecordButton3').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1,'" + deleteAccess + ")");
                            $('#updateRecordButton4').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',2,'" + deleteAccess + ")");
                            $('#updateRecordButton5').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',3,'" + deleteAccess + ")");
                        } else {
                            $('#updateRecordButton1')
                                    .removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                            $('#updateRecordButton2')
                                    .removeClass().addClass('btn dropdown-toggle btn-info disabled');
                            $('#updateRecordButton3').attr('onClick', '');
                            $('#updateRecordButton4').attr('onClick', '');
                            $('#updateRecordButton5').attr('onClick', '');
                        }
                        if (deleteAccess === 1) {
                            $('#deleteRecordButton')
                                    .removeClass().addClass('btn btn-danger')
                                    .attr('onClick', "deleteRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + deleteAccess + ")");
                        } else {
                            $('#deleteRecordButton')
                                    .removeClass().addClass('btn btn-danger')
                                    .attr('onClick', '');
                        }
                        $("#invoiceId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#productId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#unitOfMeasurementId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#discountId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#taxId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#invoiceDetailLineNumber9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#invoiceDetailQuantity9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#invoiceDetailDescription9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#invoiceDetailPrice9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#invoiceDetailDiscount9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#invoiceDetailTax9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#invoiceDetailTotalPrice9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#isRule789999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $("#executeBy9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                ;
                        $.ajax({
                            type: 'POST',
                            url: urlInvoiceDetail,
                            data: {
                                method: 'read',
                                invoiceId: $('#nextRecordCounter').val(),
                                output: 'table',
                                securityToken: securityToken,
                                leafId: leafId
                            },
                            beforeSend: function() {
                                var smileyRoll = './images/icons/smiley-roll.png';
                                var $infoPanel = $('#infoPanel');
                                $infoPanel
                                        .html('').empty()
                                        .html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                if ($infoPanel.is(':hidden')) {
                                    $infoPanel.show();
                                }
                            },
                            success: function(data) {
                                var $infoPanel = $('#infoPanel');
                                var success = data.success;
                                var tableData = data.tableData;
                                var smileyLol = './images/icons/smiley-lol.png';
                                if (success === true) {
                                    $('#tableBody')
                                            .html('').empty()
                                            .html(tableData);
                                    $(".chzn-select").chosen({search_contains: true});
                                    $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                                    $infoPanel
                                            .html('').empty()
                                            .html("<span class='label label-danger'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                    if ($infoPanel.is(':hidden')) {
                                        $infoPanel.show();
                                    }
                                }
                            },
                            error: function(xhr) {
                                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                $('#infoError')
                                        .html('').empty()
                                        .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                $('#infoErrorRowFluid')
                                        .removeClass().addClass('row-fluid');
                            }
                        });
                        $('#firstRecordCounter').val(firstRecord);
                        $('#previousRecordCounter').val(previousRecord);
                        $('#nextRecordCounter').val(nextRecord);
                        $('#lastRecordCounter').val(lastRecord);
                        if (parseFloat(previousRecord) > 0) {
                            $('#previousRecordButton')
                                    .removeClass().addClass('btn btn-default')
                                    .attr('onClick', "previousRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlInvoiceDetail + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
                        } else {
                            $('#previousRecordButton')
                                    .removeClass().addClass('btn btn-default disabled')
                                    .attr('onClick', '');
                        }
                        if (parseFloat(nextRecord) === 0) {
                            var exclamationIcon = './images/icons/exclamation.png';
                            $('#nextRecordButton')
                                    .removeClass().addClass('btn btn-default disabled')
                                    .attr('onClick', '');
                            $infoPanel
                                    .html('').empty()
                                    .html("&nbsp;<img src='" + exclamationIcon + "'> " + decodeURIComponent(t['endButtonLabel']) + " ");
                        } else {
                            var controlIcon = './images/icons/control.png';
                            $infoPanel
                                    .html('').empty()
                                    .html("&nbsp;<img src='" + controlIcon + "'> " + decodeURIComponent(t['nextButtonLabel']) + " ");
                        }
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError')
                            .html('').empty()
                            .html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid')
                            .removeClass().addClass('row-fluid');
                }
            });
        }
    }
}

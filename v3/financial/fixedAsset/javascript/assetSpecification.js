function getAssetSpecificationCostAccounts(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'assetSpecificationCostAccounts'}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#assetSpecificationCostAccounts").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function getAssetSpecificationAccumulativeDepreciationAccounts(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'assetSpecificationAccumulativeDepreciationAccounts'}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#assetSpecificationAccumulativeDepreciationAccounts").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function getAssetSpecificationWriteOffAccounts(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'assetSpecificationWriteOffAccounts'}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#assetSpecificationWriteOffAccounts").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function getAssetSpecificationDepreciationAccounts(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'assetSpecificationDepreciationAccounts'}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#assetSpecificationDepreciationAccounts").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function getAssetSpecificationRevaluationAccounts(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'assetSpecificationRevaluationAccounts'}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#assetSpecificationRevaluationAccounts").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function getAssetSpecificationGainAndLossAccounts(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'assetSpecificationGainAndLossAccounts'}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#assetSpecificationGainAndLossAccounts").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function checkDuplicate(leafId, page, securityToken) {
    var $assetSpecificationCode = $("#assetSpecificationCode");
    if ($assetSpecificationCode.val().length === 0) {
        alert(t['oddTextLabel']);
        return false;
    }
    $.ajax({type: 'GET', url: page, data: {assetSpecificationCode: $assetSpecificationCode.val(), method: 'duplicate', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            var total = data.total;
            if (success === true) {
                if (total !== 0) {
                    $("#assetSpecificationCode").val('').focus();
                    $("#assetSpecificationCodeForm").removeClass().addClass("form-group has-error");
                    $infoPanel.html('').empty().html("<img src='" + smileyRoll + "'> " + t['codeDuplicateTextLabel']).delay(5000).fadeOut();
                } else {
                    $infoPanel.html('').empty().html("<img src='" + smileyLol + "'> " + t['codeAvailableTextLabel']).delay(5000).fadeOut();
                }
            } else {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
                $("#assetSpecificationForm").removeClass().addClass("form-group has-error");
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function showGrid(leafId, page, securityToken, offset, limit, type) {
    $.ajax({type: 'POST', url: page, data: {offset: offset, limit: limit, method: 'read', type: 'list', detail: 'body', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
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
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
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
    $.ajax({type: 'POST', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'list', detail: 'body', query: queryText, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
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
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-md-12 col-sm-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function ajaxQuerySearchAllCharacter(leafId, url, securityToken, character) {
    $('#clearSearch').removeClass().addClass('btn btn-primary');
    $.ajax({type: 'POST', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'list', detail: 'body', securityToken: securityToken, leafId: leafId, character: character}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
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
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html('').html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
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
    $.ajax({type: 'POST', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'list', detail: 'body', query: $('#query').val(), securityToken: securityToken, leafId: leafId, dateRangeStart: dateRangeStart, dateRangeEnd: dateRangeEnd, dateRangeType: dateRangeType}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
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
                case'day':
                    strDate = "<b>" + t['dayTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear();
                    break;
                case'month':
                    strDate = "<b>" + t['monthTextLabel'] + "</b> : " + dateStart.getMonthName() + ", " + dateStart.getFullYear();
                    break;
                case'year':
                    strDate = "<b>" + t['yearTextLabel'] + "</b> : " + dateStart.getFullYear();
                    break;
                case'week':
                    if (dateRangeEnd.length === 0) {
                        strDate = "<b>" + t['dayTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear();
                    } else {
                        strDate = "<b>" + t['betweenTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear() + "&nbsp;<img src='" + betweenIcon + "'>&nbsp;" + dateEnd.getDayName() + ", " + dateEnd.getMonthName() + ", " + dateEnd.getDate() + ", " + dateEnd.getFullYear();
                    }
                    break;
                case'between':
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
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function ajaxQuerySearchAllDateRange(leafId, url, securityToken) {
    ajaxQuerySearchAllDate(leafId, url, securityToken, $('#dateRangeStart').val(), $('#dateRangeEnd').val(), 'between');
}
function showForm(leafId, url, securityToken) {
    sleep(500);
    $.ajax({type: 'POST', url: url, data: {method: 'new', type: 'form', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
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
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function showFormUpdate(leafId, url, urlList, securityToken, assetSpecificationId, updateAccess, deleteAccess) {
    sleep(500);
    $('a[rel=tooltip]').tooltip('hide');
    $.ajax({type: 'POST', url: urlList, data: {method: 'read', type: 'form', assetSpecificationId: assetSpecificationId, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
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
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-md-12 col-sm-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function showModalDelete(assetSpecificationId, assetDepreciationReasonId, assetSpecificationCostAccounts, assetSpecificationAccumulativeDepreciationAccounts, assetSpecificationWriteOffAccounts, assetSpecificationDepreciationAccounts, assetSpecificationRevaluationAccounts, assetSpecificationGainAndLossAccounts, assetSpecificationClearingAccounts, assetSpecificationNominalValue, assetSpecificationMinimumReOrder) {
    $('#assetSpecificationIdPreview').val('').val(decodeURIComponent(assetSpecificationId));
    $('#assetDepreciationReasonIdPreview').val('').val(decodeURIComponent(assetDepreciationReasonId));
    $('#assetSpecificationCostAccountsPreview').val('').val(decodeURIComponent(assetSpecificationCostAccounts));
    $('#assetSpecificationAccumulativeDepreciationAccountsPreview').val('').val(decodeURIComponent(assetSpecificationAccumulativeDepreciationAccounts));
    $('#assetSpecificationWriteOffAccountsPreview').val('').val(decodeURIComponent(assetSpecificationWriteOffAccounts));
    $('#assetSpecificationDepreciationAccountsPreview').val('').val(decodeURIComponent(assetSpecificationDepreciationAccounts));
    $('#assetSpecificationRevaluationAccountsPreview').val('').val(decodeURIComponent(assetSpecificationRevaluationAccounts));
    $('#assetSpecificationGainAndLossAccountsPreview').val('').val(decodeURIComponent(assetSpecificationGainAndLossAccounts));
    $('#assetSpecificationClearingAccountsPreview').val('').val(decodeURIComponent(assetSpecificationClearingAccounts));
    $('#assetSpecificationNominalValuePreview').val('').val(decodeURIComponent(assetSpecificationNominalValue));
    $('#assetSpecificationMinimumReOrderPreview').val('').val(decodeURIComponent(assetSpecificationMinimumReOrder));
    showMeModal('deletePreview', 1);
}
function deleteGridRecord(leafId, url, urlList, securityToken) {
    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', assetSpecificationId: $('#assetSpecificationIdPreview').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
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
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function deleteGridRecordCheckbox(leafId, url, urlList, securityToken) {
    var stringText = '';
    var counter = 0;
    $('input:checkbox[name="assetSpecificationId[]"]').each(function() {
        stringText = stringText + "&assetSpecificationId[]=" + $(this).val();
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
    $.ajax({type: 'GET', url: url, data: {method: 'updateStatus', output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
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
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function reportRequest(leafId, url, securityToken, mode) {
    $.ajax({type: 'GET', url: url, data: {method: 'report', mode: mode, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var $infoPanel = $('#infoPanel');
            var folder = data.folder;
            var filename = data.filename;
            var success = data.success;
            var message = data.message;
            if (success === true) {
                var path="./v3/humanResource/training/document/" + folder + "/" + filename;
                $infoPanel.html('').empty().html("<span class='label label-success'>" + decodeURIComponent(t['requestFileTextLabel']) + "</span>");
                window.open(path);
            } else {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
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
    var $assetDepreciationReasonId = $('#assetDepreciationReasonId');
    var $assetSpecificationCostAccounts = $('#assetSpecificationCostAccounts');
    var $assetSpecificationAccumulativeDepreciationAccounts = $('#assetSpecificationAccumulativeDepreciationAccounts');
    var $assetSpecificationWriteOffAccounts = $('#assetSpecificationWriteOffAccounts');
    var $assetSpecificationDepreciationAccounts = $('#assetSpecificationDepreciationAccounts');
    var $assetSpecificationRevaluationAccounts = $('#assetSpecificationRevaluationAccounts');
    var $assetSpecificationGainAndLossAccounts = $('#assetSpecificationGainAndLossAccounts');
    var $assetSpecificationClearingAccounts = $('#assetSpecificationClearingAccounts');
    var $assetSpecificationNominalValue = $('#assetSpecificationNominalValue');
    var $assetSpecificationMinimumReOrder = $('#assetSpecificationMinimumReOrder');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (type === 1) {
            if ($assetDepreciationReasonId.val().length === 0) {
                $('#assetDepreciationReasonIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDepreciationReasonIdLabel'] + " </span>");
                $('#assetDepreciationReasonIdForm').removeClass().addClass('form-group has-error');
                $assetDepreciationReasonId.focus();
                return false;
            }
            if ($assetSpecificationCostAccounts.val().length === 0) {
                $('#assetSpecificationCostAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationCostAccountsLabel'] + " </span>");
                $assetSpecificationCostAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationAccumulativeDepreciationAccounts.val().length === 0) {
                $('#assetSpecificationAccumulativeDepreciationAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationAccumulativeDepreciationAccountsLabel'] + " </span>");
                $assetSpecificationAccumulativeDepreciationAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationWriteOffAccounts.val().length === 0) {
                $('#assetSpecificationWriteOffAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationWriteOffAccountsLabel'] + " </span>");
                $assetSpecificationWriteOffAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationDepreciationAccounts.val().length === 0) {
                $('#assetSpecificationDepreciationAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationDepreciationAccountsLabel'] + " </span>");
                $assetSpecificationDepreciationAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationRevaluationAccounts.val().length === 0) {
                $('#assetSpecificationRevaluationAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationRevaluationAccountsLabel'] + " </span>");
                $assetSpecificationRevaluationAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationGainAndLossAccounts.val().length === 0) {
                $('#assetSpecificationGainAndLossAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationGainAndLossAccountsLabel'] + " </span>");
                $assetSpecificationGainAndLossAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationClearingAccounts.val().length === 0) {
                $('#assetSpecificationClearingAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationClearingAccountsLabel'] + " </span>");
                $('#assetSpecificationClearingAccountsForm').removeClass().addClass('form-group has-error');
                $assetSpecificationClearingAccounts.focus();
                return false;
            }
            if ($assetSpecificationNominalValue.val().length === 0) {
                $('#assetSpecificationNominalValueHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationNominalValueLabel'] + " </span>");
                $('#assetSpecificationNominalValueForm').removeClass().addClass('form-group has-error');
                $assetSpecificationNominalValue.focus();
                return false;
            }
            if ($assetSpecificationMinimumReOrder.val().length === 0) {
                $('#assetSpecificationMinimumReOrderHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationMinimumReOrderLabel'] + " </span>");
                $('#assetSpecificationMinimumReOrderForm').removeClass().addClass('form-group has-error');
                $assetSpecificationMinimumReOrder.focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', assetDepreciationReasonId: $assetDepreciationReasonId.val(), assetSpecificationCostAccounts: $assetSpecificationCostAccounts.val(), assetSpecificationAccumulativeDepreciationAccounts: $assetSpecificationAccumulativeDepreciationAccounts.val(), assetSpecificationWriteOffAccounts: $assetSpecificationWriteOffAccounts.val(), assetSpecificationDepreciationAccounts: $assetSpecificationDepreciationAccounts.val(), assetSpecificationRevaluationAccounts: $assetSpecificationRevaluationAccounts.val(), assetSpecificationGainAndLossAccounts: $assetSpecificationGainAndLossAccounts.val(), assetSpecificationClearingAccounts: $assetSpecificationClearingAccounts.val(), assetSpecificationNominalValue: $assetSpecificationNominalValue.val(), assetSpecificationMinimumReOrder: $assetSpecificationMinimumReOrder.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }, success: function(data) {
                    var $infoPanel = $('#infoPanel');
                    var success = data.success;
                    var message = data.message;
                    var smileyLol = './images/icons/smiley-lol.png';
                    if (success === true) {
                        $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>").delay(1000).fadeOut();
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                        $assetDepreciationReasonId.val('');
                        $assetDepreciationReasonId.trigger("chosen:updated");
                        $('#assetDepreciationReasonIdHelpMe').html('').empty();
                        $assetSpecificationCostAccounts.val('');
                        $assetSpecificationCostAccounts.trigger("chosen:updated");
                        $('#assetSpecificationCostAccountsHelpMe').html('').empty();
                        $assetSpecificationAccumulativeDepreciationAccounts.val('');
                        $assetSpecificationAccumulativeDepreciationAccounts.trigger("chosen:updated");
                        $('#assetSpecificationAccumulativeDepreciationAccountsHelpMe').html('').empty();
                        $assetSpecificationWriteOffAccounts.val('');
                        $assetSpecificationWriteOffAccounts.trigger("chosen:updated");
                        $('#assetSpecificationWriteOffAccountsHelpMe').html('').empty();
                        $assetSpecificationDepreciationAccounts.val('');
                        $assetSpecificationDepreciationAccounts.trigger("chosen:updated");
                        $('#assetSpecificationDepreciationAccountsHelpMe').html('').empty();
                        $assetSpecificationRevaluationAccounts.val('');
                        $assetSpecificationRevaluationAccounts.trigger("chosen:updated");
                        $('#assetSpecificationRevaluationAccountsHelpMe').html('').empty();
                        $assetSpecificationGainAndLossAccounts.val('');
                        $assetSpecificationGainAndLossAccounts.trigger("chosen:updated");
                        $('#assetSpecificationGainAndLossAccountsHelpMe').html('').empty();
                        $assetSpecificationClearingAccounts.val('');
                        $('#assetSpecificationClearingAccountsHelpMe').html('').empty();
                        $assetSpecificationNominalValue.val('');
                        $('#assetSpecificationNominalValueHelpMe').html('').empty();
                        $assetSpecificationMinimumReOrder.val('');
                        $('#assetSpecificationMinimumReOrderHelpMe').html('').empty();
                    } else if (success === false) {
                        $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                }, error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        } else if (type === 2) {
            if ($assetDepreciationReasonId.val().length === 0) {
                $('#assetDepreciationReasonIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDepreciationReasonIdLabel'] + " </span>");
                $('#assetDepreciationReasonIdForm').removeClass().addClass('form-group has-error');
                $assetDepreciationReasonId.focus();
                return false;
            }
            if ($assetSpecificationCostAccounts.val().length === 0) {
                $('#assetSpecificationCostAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationCostAccountsLabel'] + " </span>");
                $assetSpecificationCostAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationAccumulativeDepreciationAccounts.val().length === 0) {
                $('#assetSpecificationAccumulativeDepreciationAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationAccumulativeDepreciationAccountsLabel'] + " </span>");
                $assetSpecificationAccumulativeDepreciationAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationWriteOffAccounts.val().length === 0) {
                $('#assetSpecificationWriteOffAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationWriteOffAccountsLabel'] + " </span>");
                $assetSpecificationWriteOffAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationDepreciationAccounts.val().length === 0) {
                $('#assetSpecificationDepreciationAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationDepreciationAccountsLabel'] + " </span>");
                $assetSpecificationDepreciationAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationRevaluationAccounts.val().length === 0) {
                $('#assetSpecificationRevaluationAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationRevaluationAccountsLabel'] + " </span>");
                $assetSpecificationRevaluationAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationGainAndLossAccounts.val().length === 0) {
                $('#assetSpecificationGainAndLossAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationGainAndLossAccountsLabel'] + " </span>");
                $assetSpecificationGainAndLossAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationClearingAccounts.val().length === 0) {
                $('#assetSpecificationClearingAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationClearingAccountsLabel'] + " </span>");
                $('#assetSpecificationClearingAccountsForm').removeClass().addClass('form-group has-error');
                $assetSpecificationClearingAccounts.focus();
                return false;
            }
            if ($assetSpecificationNominalValue.val().length === 0) {
                $('#assetSpecificationNominalValueHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationNominalValueLabel'] + " </span>");
                $('#assetSpecificationNominalValueForm').removeClass().addClass('form-group has-error');
                $assetSpecificationNominalValue.focus();
                return false;
            }
            if ($assetSpecificationMinimumReOrder.val().length === 0) {
                $('#assetSpecificationMinimumReOrderHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationMinimumReOrderLabel'] + " </span>");
                $('#assetSpecificationMinimumReOrderForm').removeClass().addClass('form-group has-error');
                $assetSpecificationMinimumReOrder.focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', assetDepreciationReasonId: $assetDepreciationReasonId.val(), assetSpecificationCostAccounts: $assetSpecificationCostAccounts.val(), assetSpecificationAccumulativeDepreciationAccounts: $assetSpecificationAccumulativeDepreciationAccounts.val(), assetSpecificationWriteOffAccounts: $assetSpecificationWriteOffAccounts.val(), assetSpecificationDepreciationAccounts: $assetSpecificationDepreciationAccounts.val(), assetSpecificationRevaluationAccounts: $assetSpecificationRevaluationAccounts.val(), assetSpecificationGainAndLossAccounts: $assetSpecificationGainAndLossAccounts.val(), assetSpecificationClearingAccounts: $assetSpecificationClearingAccounts.val(), assetSpecificationNominalValue: $assetSpecificationNominalValue.val(), assetSpecificationMinimumReOrder: $assetSpecificationMinimumReOrder.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }, success: function(data) {
                    var $infoPanel = $('#infoPanel');
                    var success = data.success;
                    var smileyLol = './images/icons/smiley-lol.png';
                    if (success === true) {
                        $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>");
                        $('#assetSpecificationId').val(data.assetSpecificationId);
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
                }, error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        } else if (type === 5) {
            if ($assetDepreciationReasonId.val().length === 0) {
                $('#assetDepreciationReasonIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDepreciationReasonIdLabel'] + " </span>");
                $('#assetDepreciationReasonIdForm').removeClass().addClass('form-group has-error');
                $assetDepreciationReasonId.focus();
                return false;
            }
            if ($assetSpecificationCostAccounts.val().length === 0) {
                $('#assetSpecificationCostAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationCostAccountsLabel'] + " </span>");
                $assetSpecificationCostAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationAccumulativeDepreciationAccounts.val().length === 0) {
                $('#assetSpecificationAccumulativeDepreciationAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationAccumulativeDepreciationAccountsLabel'] + " </span>");
                $assetSpecificationAccumulativeDepreciationAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationWriteOffAccounts.val().length === 0) {
                $('#assetSpecificationWriteOffAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationWriteOffAccountsLabel'] + " </span>");
                $assetSpecificationWriteOffAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationDepreciationAccounts.val().length === 0) {
                $('#assetSpecificationDepreciationAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationDepreciationAccountsLabel'] + " </span>");
                $assetSpecificationDepreciationAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationRevaluationAccounts.val().length === 0) {
                $('#assetSpecificationRevaluationAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationRevaluationAccountsLabel'] + " </span>");
                $assetSpecificationRevaluationAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationGainAndLossAccounts.val().length === 0) {
                $('#assetSpecificationGainAndLossAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationGainAndLossAccountsLabel'] + " </span>");
                $assetSpecificationGainAndLossAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationClearingAccounts.val().length === 0) {
                $('#assetSpecificationClearingAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationClearingAccountsLabel'] + " </span>");
                $('#assetSpecificationClearingAccountsForm').removeClass().addClass('form-group has-error');
                $assetSpecificationClearingAccounts.focus();
                return false;
            }
            if ($assetSpecificationNominalValue.val().length === 0) {
                $('#assetSpecificationNominalValueHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationNominalValueLabel'] + " </span>");
                $('#assetSpecificationNominalValueForm').removeClass().addClass('form-group has-error');
                $assetSpecificationNominalValue.focus();
                return false;
            }
            if ($assetSpecificationMinimumReOrder.val().length === 0) {
                $('#assetSpecificationMinimumReOrderHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationMinimumReOrderLabel'] + " </span>");
                $('#assetSpecificationMinimumReOrderForm').removeClass().addClass('form-group has-error');
                $assetSpecificationMinimumReOrder.focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', assetDepreciationReasonId: $assetDepreciationReasonId.val(), assetSpecificationCostAccounts: $assetSpecificationCostAccounts.val(), assetSpecificationAccumulativeDepreciationAccounts: $assetSpecificationAccumulativeDepreciationAccounts.val(), assetSpecificationWriteOffAccounts: $assetSpecificationWriteOffAccounts.val(), assetSpecificationDepreciationAccounts: $assetSpecificationDepreciationAccounts.val(), assetSpecificationRevaluationAccounts: $assetSpecificationRevaluationAccounts.val(), assetSpecificationGainAndLossAccounts: $assetSpecificationGainAndLossAccounts.val(), assetSpecificationClearingAccounts: $assetSpecificationClearingAccounts.val(), assetSpecificationNominalValue: $assetSpecificationNominalValue.val(), assetSpecificationMinimumReOrder: $assetSpecificationMinimumReOrder.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }, success: function(data) {
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
                }, error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        }
        showMeDiv('tableDate', 0);
        showMeDiv('formEntry', 1);
    }
}
function updateRecord(leafId, url, urlList, securityToken, type, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var css = $('#updateRecordButton2').attr('class');
	var $assetSpecificationId= $('#assetSpecificationId');
    var $assetDepreciationReasonId = $('#assetDepreciationReasonId');
    var $assetSpecificationCostAccounts = $('#assetSpecificationCostAccounts');
    var $assetSpecificationAccumulativeDepreciationAccounts = $('#assetSpecificationAccumulativeDepreciationAccounts');
    var $assetSpecificationWriteOffAccounts = $('#assetSpecificationWriteOffAccounts');
    var $assetSpecificationDepreciationAccounts = $('#assetSpecificationDepreciationAccounts');
    var $assetSpecificationRevaluationAccounts = $('#assetSpecificationRevaluationAccounts');
    var $assetSpecificationGainAndLossAccounts = $('#assetSpecificationGainAndLossAccounts');
    var $assetSpecificationClearingAccounts = $('#assetSpecificationClearingAccounts');
    var $assetSpecificationNominalValue = $('#assetSpecificationNominalValue');
    var $assetSpecificationMinimumReOrder = $('#assetSpecificationMinimumReOrder');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $infoPanel.empty().html('');
        if (type === 1) {
            if ($assetDepreciationReasonId.val().length === 0) {
                $('#assetDepreciationReasonIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDepreciationReasonIdLabel'] + " </span>");
                $('#assetDepreciationReasonIdForm').removeClass().addClass('form-group has-error');
                $assetDepreciationReasonId.focus();
                return false;
            }
            if ($assetSpecificationCostAccounts.val().length === 0) {
                $('#assetSpecificationCostAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationCostAccountsLabel'] + " </span>");
                $assetSpecificationCostAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationAccumulativeDepreciationAccounts.val().length === 0) {
                $('#assetSpecificationAccumulativeDepreciationAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationAccumulativeDepreciationAccountsLabel'] + " </span>");
                $assetSpecificationAccumulativeDepreciationAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationWriteOffAccounts.val().length === 0) {
                $('#assetSpecificationWriteOffAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationWriteOffAccountsLabel'] + " </span>");
                $assetSpecificationWriteOffAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationDepreciationAccounts.val().length === 0) {
                $('#assetSpecificationDepreciationAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationDepreciationAccountsLabel'] + " </span>");
                $assetSpecificationDepreciationAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationRevaluationAccounts.val().length === 0) {
                $('#assetSpecificationRevaluationAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationRevaluationAccountsLabel'] + " </span>");
                $assetSpecificationRevaluationAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationGainAndLossAccounts.val().length === 0) {
                $('#assetSpecificationGainAndLossAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationGainAndLossAccountsLabel'] + " </span>");
                $assetSpecificationGainAndLossAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationClearingAccounts.val().length === 0) {
                $('#assetSpecificationClearingAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationClearingAccountsLabel'] + " </span>");
                $('#assetSpecificationClearingAccountsForm').removeClass().addClass('form-group has-error');
                $assetSpecificationClearingAccounts.focus();
                return false;
            }
            if ($assetSpecificationNominalValue.val().length === 0) {
                $('#assetSpecificationNominalValueHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationNominalValueLabel'] + " </span>");
                $('#assetSpecificationNominalValueForm').removeClass().addClass('form-group has-error');
                $assetSpecificationNominalValue.focus();
                return false;
            }
            if ($assetSpecificationMinimumReOrder.val().length === 0) {
                $('#assetSpecificationMinimumReOrderHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationMinimumReOrderLabel'] + " </span>");
                $('#assetSpecificationMinimumReOrderForm').removeClass().addClass('form-group has-error');
                $assetSpecificationMinimumReOrder.focus();
                return false;
            }
            $infoPanel.html('').empty();
            $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', assetSpecificationId: $assetSpecificationId.val(), assetDepreciationReasonId: $assetDepreciationReasonId.val(), assetSpecificationCostAccounts: $assetSpecificationCostAccounts.val(), assetSpecificationAccumulativeDepreciationAccounts: $assetSpecificationAccumulativeDepreciationAccounts.val(), assetSpecificationWriteOffAccounts: $assetSpecificationWriteOffAccounts.val(), assetSpecificationDepreciationAccounts: $assetSpecificationDepreciationAccounts.val(), assetSpecificationRevaluationAccounts: $assetSpecificationRevaluationAccounts.val(), assetSpecificationGainAndLossAccounts: $assetSpecificationGainAndLossAccounts.val(), assetSpecificationClearingAccounts: $assetSpecificationClearingAccounts.val(), assetSpecificationNominalValue: $assetSpecificationNominalValue.val(), assetSpecificationMinimumReOrder: $assetSpecificationMinimumReOrder.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }, success: function(data) {
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
                }, error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        } else if (type === 3) {
            if ($assetDepreciationReasonId.val().length === 0) {
                $('#assetDepreciationReasonIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDepreciationReasonIdLabel'] + " </span>");
                $('#assetDepreciationReasonIdForm').removeClass().addClass('form-group has-error');
                $assetDepreciationReasonId.focus();
                return false;
            }
            if ($assetSpecificationCostAccounts.val().length === 0) {
                $('#assetSpecificationCostAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationCostAccountsLabel'] + " </span>");
                $assetSpecificationCostAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationAccumulativeDepreciationAccounts.val().length === 0) {
                $('#assetSpecificationAccumulativeDepreciationAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationAccumulativeDepreciationAccountsLabel'] + " </span>");
                $assetSpecificationAccumulativeDepreciationAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationWriteOffAccounts.val().length === 0) {
                $('#assetSpecificationWriteOffAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationWriteOffAccountsLabel'] + " </span>");
                $assetSpecificationWriteOffAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationDepreciationAccounts.val().length === 0) {
                $('#assetSpecificationDepreciationAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationDepreciationAccountsLabel'] + " </span>");
                $assetSpecificationDepreciationAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationRevaluationAccounts.val().length === 0) {
                $('#assetSpecificationRevaluationAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationRevaluationAccountsLabel'] + " </span>");
                $assetSpecificationRevaluationAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationGainAndLossAccounts.val().length === 0) {
                $('#assetSpecificationGainAndLossAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationGainAndLossAccountsLabel'] + " </span>");
                $assetSpecificationGainAndLossAccounts.data('chosen').activate_action();
                return false;
            }
            if ($assetSpecificationClearingAccounts.val().length === 0) {
                $('#assetSpecificationClearingAccountsHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationClearingAccountsLabel'] + " </span>");
                $('#assetSpecificationClearingAccountsForm').removeClass().addClass('form-group has-error');
                $assetSpecificationClearingAccounts.focus();
                return false;
            }
            if ($assetSpecificationNominalValue.val().length === 0) {
                $('#assetSpecificationNominalValueHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationNominalValueLabel'] + " </span>");
                $('#assetSpecificationNominalValueForm').removeClass().addClass('form-group has-error');
                $assetSpecificationNominalValue.focus();
                return false;
            }
            if ($assetSpecificationMinimumReOrder.val().length === 0) {
                $('#assetSpecificationMinimumReOrderHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSpecificationMinimumReOrderLabel'] + " </span>");
                $('#assetSpecificationMinimumReOrderForm').removeClass().addClass('form-group has-error');
                $assetSpecificationMinimumReOrder.focus();
                return false;
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', assetSpecificationId: $assetSpecificationId.val(), assetDepreciationReasonId: $assetDepreciationReasonId.val(), assetSpecificationCostAccounts: $assetSpecificationCostAccounts.val(), assetSpecificationAccumulativeDepreciationAccounts: $assetSpecificationAccumulativeDepreciationAccounts.val(), assetSpecificationWriteOffAccounts: $assetSpecificationWriteOffAccounts.val(), assetSpecificationDepreciationAccounts: $assetSpecificationDepreciationAccounts.val(), assetSpecificationRevaluationAccounts: $assetSpecificationRevaluationAccounts.val(), assetSpecificationGainAndLossAccounts: $assetSpecificationGainAndLossAccounts.val(), assetSpecificationClearingAccounts: $assetSpecificationClearingAccounts.val(), assetSpecificationNominalValue: $assetSpecificationNominalValue.val(), assetSpecificationMinimumReOrder: $assetSpecificationMinimumReOrder.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }, success: function(data) {
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
                }, error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        }
    }
}
function deleteRecord(leafId, url, urlList, securityToken, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var $assetSpecificationId = $('#assetSpecificationId');
    var css = $('#deleteRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (deleteAccess === 1) {
            if (confirm(decodeURIComponent(t['deleteRecordMessageLabel']))) {
                var value = $assetSpecificationId.val();
                if (!value) {
                    $infoPanel.html('').empty().html("<span class='label label-important'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "<span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                    return false;
                } else {
                    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', assetSpecificationId: $assetSpecificationId.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            var $infoPanel = $('#infoPanel');
                            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        }, success: function(data) {
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
                        }, error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                        }});
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
    $("#assetSpecificationId").val('');
    $("#assetSpecificationIdHelpMe").empty().html('');
    
    $("#assetDepreciationReasonId").val('');
    $("#assetDepreciationReasonIdHelpMe").empty().html('');
    $('#assetDepreciationReasonId').trigger("chosen:updated");
    $("#assetSpecificationCostAccounts").val('');
    $("#assetSpecificationCostAccountsHelpMe").empty().html('');
    $('#assetSpecificationCostAccounts').trigger("chosen:updated");
    $("#assetSpecificationAccumulativeDepreciationAccounts").val('');
    $("#assetSpecificationAccumulativeDepreciationAccountsHelpMe").empty().html('');
    $('#assetSpecificationAccumulativeDepreciationAccounts').trigger("chosen:updated");
    $("#assetSpecificationWriteOffAccounts").val('');
    $("#assetSpecificationWriteOffAccountsHelpMe").empty().html('');
    $('#assetSpecificationWriteOffAccounts').trigger("chosen:updated");
    $("#assetSpecificationDepreciationAccounts").val('');
    $("#assetSpecificationDepreciationAccountsHelpMe").empty().html('');
    $('#assetSpecificationDepreciationAccounts').trigger("chosen:updated");
    $("#assetSpecificationRevaluationAccounts").val('');
    $("#assetSpecificationRevaluationAccountsHelpMe").empty().html('');
    $('#assetSpecificationRevaluationAccounts').trigger("chosen:updated");
    $("#assetSpecificationGainAndLossAccounts").val('');
    $("#assetSpecificationGainAndLossAccountsHelpMe").empty().html('');
    $('#assetSpecificationGainAndLossAccounts').trigger("chosen:updated");
    $("#assetSpecificationClearingAccounts").val('');
    $("#assetSpecificationClearingAccountsHelpMe").empty().html('');
    $("#assetSpecificationNominalValue").val('');
    $("#assetSpecificationNominalValueHelpMe").empty().html('');
    $("#assetSpecificationMinimumReOrder").val('');
    $("#assetSpecificationMinimumReOrderHelpMe").empty().html('');
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
        $.ajax({type: 'GET', url: url, data: {method: 'dataNavigationRequest', dataNavigation: 'firstRecord', output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                var smileyRoll = './images/icons/smiley-roll.png';
                var $infoPanel = $('#infoPanel');
                $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }, success: function(data) {
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
                    $.ajax({type: 'POST', url: url, data: {method: 'read', assetSpecificationId: firstRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        }, success: function(data) {
                            var x, output;
                            var success = data.success;
                            var $infoPanel = $('#infoPanel');
                            var lastRecord = data.lastRecord;
                            var nextRecord = data.nextRecord;
                            var previousRecord = data.previousRecord;
                            if (success === true) {
                                $('#assetSpecificationId').val(data.data.assetSpecificationId);
                                $('#assetDepreciationReasonId').val(data.data.assetDepreciationReasonId).trigger("chosen:updated");
                                $('#assetSpecificationCostAccounts').val(data.data.assetSpecificationCostAccounts).trigger("chosen:updated");
                                $('#assetSpecificationAccumulativeDepreciationAccounts').val(data.data.assetSpecificationAccumulativeDepreciationAccounts).trigger("chosen:updated");
                                $('#assetSpecificationWriteOffAccounts').val(data.data.assetSpecificationWriteOffAccounts).trigger("chosen:updated");
                                $('#assetSpecificationDepreciationAccounts').val(data.data.assetSpecificationDepreciationAccounts).trigger("chosen:updated");
                                $('#assetSpecificationRevaluationAccounts').val(data.data.assetSpecificationRevaluationAccounts).trigger("chosen:updated");
                                $('#assetSpecificationGainAndLossAccounts').val(data.data.assetSpecificationGainAndLossAccounts).trigger("chosen:updated");
                                $('#assetSpecificationClearingAccounts').val(data.data.assetSpecificationClearingAccounts);
                                $('#assetSpecificationNominalValue').val(data.data.assetSpecificationNominalValue);
                                $('#assetSpecificationMinimumReOrder').val(data.data.assetSpecificationMinimumReOrder);
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
                        }, error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                        }});
                } else {
                    $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRollSweat + "'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }
            }, error: function(xhr) {
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
            }});
    }
}
function endRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var css = $('#endRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $.ajax({type: 'GET', url: url, data: {method: 'dataNavigationRequest', dataNavigation: 'lastRecord', output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                var smileyRoll = './images/icons/smiley-roll.png';
                var $infoPanel = $('#infoPanel');
                $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }, success: function(data) {
                var success = data.success;
                var message = data.message;
                var lastRecord = data.lastRecord;
                var smileyRoll = './images/icons/smiley-roll.png';
                if (lastRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (success === true) {
                    $.ajax({type: 'POST', url: url, data: {method: 'read', assetSpecificationId: lastRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        }, success: function(data) {
                            var x, output;
                            var success = data.success;
                            var firstRecord = data.firstRecord;
                            var lastRecord = data.lastRecord;
                            var nextRecord = data.nextRecord;
                            var previousRecord = data.previousRecord;
                            if (success === true) {
                                $('#assetSpecificationId').val(data.data.assetSpecificationId);
                                $('#assetDepreciationReasonId').val(data.data.assetDepreciationReasonId).trigger("chosen:updated");
                                $('#assetSpecificationCostAccounts').val(data.data.assetSpecificationCostAccounts).trigger("chosen:updated");
                                $('#assetSpecificationAccumulativeDepreciationAccounts').val(data.data.assetSpecificationAccumulativeDepreciationAccounts).trigger("chosen:updated");
                                $('#assetSpecificationWriteOffAccounts').val(data.data.assetSpecificationWriteOffAccounts).trigger("chosen:updated");
                                $('#assetSpecificationDepreciationAccounts').val(data.data.assetSpecificationDepreciationAccounts).trigger("chosen:updated");
                                $('#assetSpecificationRevaluationAccounts').val(data.data.assetSpecificationRevaluationAccounts).trigger("chosen:updated");
                                $('#assetSpecificationGainAndLossAccounts').val(data.data.assetSpecificationGainAndLossAccounts).trigger("chosen:updated");
                                $('#assetSpecificationClearingAccounts').val(data.data.assetSpecificationClearingAccounts);
                                $('#assetSpecificationNominalValue').val(data.data.assetSpecificationNominalValue);
                                $('#assetSpecificationMinimumReOrder').val(data.data.assetSpecificationMinimumReOrder);
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
                        }, error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                        }});
                } else {
                    $infoPanel.html("<span class='label label-important'>&nbsp;" + message + "</span>");
                }
                var endIcon = './images/icons/control-stop-180.png';
                $infoPanel.html('').empty().html("&nbsp;<img src='" + endIcon + "'> " + decodeURIComponent(t['endButtonLabel']) + " ");
            }, error: function(xhr) {
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
            }});
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
            $.ajax({type: 'POST', url: url, data: {method: 'read', assetSpecificationId: $previousRecordCounter.val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }, success: function(data) {
                    var x, output;
                    var success = data.success;
                    var firstRecord = data.firstRecord;
                    var lastRecord = data.lastRecord;
                    var nextRecord = data.nextRecord;
                    var previousRecord = data.previousRecord;
                    var $infoPanel = $('#infoPanel');
                    if (success === true) {
                        $('#assetSpecificationId').val(data.data.assetSpecificationId);
                        $('#assetDepreciationReasonId').val(data.data.assetDepreciationReasonId).trigger("chosen:updated");
                        $('#assetSpecificationCostAccounts').val(data.data.assetSpecificationCostAccounts).trigger("chosen:updated");
                        $('#assetSpecificationAccumulativeDepreciationAccounts').val(data.data.assetSpecificationAccumulativeDepreciationAccounts).trigger("chosen:updated");
                        $('#assetSpecificationWriteOffAccounts').val(data.data.assetSpecificationWriteOffAccounts).trigger("chosen:updated");
                        $('#assetSpecificationDepreciationAccounts').val(data.data.assetSpecificationDepreciationAccounts).trigger("chosen:updated");
                        $('#assetSpecificationRevaluationAccounts').val(data.data.assetSpecificationRevaluationAccounts).trigger("chosen:updated");
                        $('#assetSpecificationGainAndLossAccounts').val(data.data.assetSpecificationGainAndLossAccounts).trigger("chosen:updated");
                        $('#assetSpecificationClearingAccounts').val(data.data.assetSpecificationClearingAccounts);
                        $('#assetSpecificationNominalValue').val(data.data.assetSpecificationNominalValue);
                        $('#assetSpecificationMinimumReOrder').val(data.data.assetSpecificationMinimumReOrder);
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
                }, error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
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
            $.ajax({type: 'POST', url: url, data: {method: 'read', assetSpecificationId: $nextRecordCounter.val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }, success: function(data) {
                    var $infoPanel = $('#infoPanel');
                    var x, output;
                    var success = data.success;
                    var firstRecord = data.firstRecord;
                    var lastRecord = data.lastRecord;
                    var nextRecord = data.nextRecord;
                    var previousRecord = data.previousRecord;
                    if (success === true) {
                        $('#assetSpecificationId').val(data.data.assetSpecificationId);
                        $('#assetDepreciationReasonId').val(data.data.assetDepreciationReasonId).trigger("chosen:updated");
                        $('#assetSpecificationCostAccounts').val(data.data.assetSpecificationCostAccounts).trigger("chosen:updated");
                        $('#assetSpecificationAccumulativeDepreciationAccounts').val(data.data.assetSpecificationAccumulativeDepreciationAccounts).trigger("chosen:updated");
                        $('#assetSpecificationWriteOffAccounts').val(data.data.assetSpecificationWriteOffAccounts).trigger("chosen:updated");
                        $('#assetSpecificationDepreciationAccounts').val(data.data.assetSpecificationDepreciationAccounts).trigger("chosen:updated");
                        $('#assetSpecificationRevaluationAccounts').val(data.data.assetSpecificationRevaluationAccounts).trigger("chosen:updated");
                        $('#assetSpecificationGainAndLossAccounts').val(data.data.assetSpecificationGainAndLossAccounts).trigger("chosen:updated");
                        $('#assetSpecificationClearingAccounts').val(data.data.assetSpecificationClearingAccounts);
                        $('#assetSpecificationNominalValue').val(data.data.assetSpecificationNominalValue);
                        $('#assetSpecificationMinimumReOrder').val(data.data.assetSpecificationMinimumReOrder);
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
                }, error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        }
    }
}
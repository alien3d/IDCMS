function getStaff(leafId, url, securityToken) {
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
            filter: 'staff'
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
                        .html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#staffId")
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
function getTodoCategory(leafId, url, securityToken) {
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
            filter: 'todoCategory'
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
                        .html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#todoCategoryId")
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
    var $todoCode = $("#todoCode");
    if ($todoCode.val().length === 0) {
        alert(t['oddTextLabel']);
        return false;
    }
    $.ajax({
        type: 'GET',
        url: page,
        data: {
            todoCode: $todoCode.val(),
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
                    $("#todoCode")
                            .val('')
                            .focus();
                    $("#todoCodeForm")
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
                        .html("<span class='label label-important'>&nbsp;" + message + "</span>");
                $("#todoForm")
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
                        .html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
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
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $centerViewPort
                        .html('').empty()

                        .html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort
                        .html('').empty()

                        .append(data);
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

                        .html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
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
                        .html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</span>");
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
                        .html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
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
function showFormUpdate(leafId, url, urlList, securityToken, todoId, updateAccess, deleteAccess) {
    sleep(500);
    $('a[rel=tooltip]').tooltip('hide');
    $.ajax({
        type: 'POST',
        url: urlList,
        data: {
            method: 'read',
            type: 'form',
            todoId: todoId,
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
                        .html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
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
function showModalDelete(todoId, staffId, todoCategoryId, todoTitle, todoDueDate, todoDescription) {
    // clear first old record if exist
    $('#todoIdPreview').val('').val(decodeURIComponent(todoId));
    $('#staffIdPreview').val('').val(decodeURIComponent(staffId));
    $('#todoCategoryIdPreview').val('').val(decodeURIComponent(todoCategoryId));
    $('#todoTitlePreview').val('').val(decodeURIComponent(todoTitle));
    $('#todoDueDatePreview').val('').val(decodeURIComponent(todoDueDate));
    $('#todoDescriptionPreview').val('').val(decodeURIComponent(todoDescription));
    showMeModal('deletePreview', 1);
}
function deleteGridRecord(leafId, url, urlList, securityToken) {
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'delete',
            output: 'json',
            todoId: $('#todoIdPreview').val(),
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
                        .html("<span class='label label-important'>&nbsp;" + message + "</span>");
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
    $('input:checkbox[name="todoId[]"]').each(function() {
        stringText = stringText + "&todoId[]=" + $(this).val();
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
                        .html("<span class='label label-important'>&nbsp;" + message + "</span>");
            } else {
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + message + "</span>");
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
                var path="./v3/system/management/document/" + folder + "/" + filename;
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-success'>" + decodeURIComponent(t['requestFileTextLabel']) + "</span>");
                window.open(path);
            } else {
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + message + "</span>");
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
    var $staffId = $('#staffId');
    var $todoCategoryId = $('#todoCategoryId');
    var $todoTitle = $('#todoTitle');
    var $todoDueDate = $('#todoDueDate');
    var $todoDescription = $('#todoDescription');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (type === 1) {
            if ($staffId.val().length === 0) {
                $('#staffIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffIdLabel'] + " </span>");
                $staffId.data('chosen').activate_action();
                return false;
            }
            if ($todoCategoryId.val().length === 0) {
                $('#todoCategoryIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoCategoryIdLabel'] + " </span>");
                $todoCategoryId.data('chosen').activate_action();
                return false;
            }
            if ($todoTitle.val().length === 0) {
                $('#todoTitleHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoTitleLabel'] + " </span>");
                $('#todoTitleForm')
                        .removeClass().addClass('form-group has-error');
                $todoTitle.focus();
                return false;
            }
            if ($todoDueDate.val().length === 0) {
                $('#todoDueDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoDueDateLabel'] + " </span>");
                $('#todoDueDateForm')
                        .removeClass().addClass('form-group has-error');
                $todoDueDate.focus();
                return false;
            }
            if ($todoDescription.val().length === 0) {
                $('#todoDescriptionHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoDescriptionLabel'] + " </span>");
                $('#todoDescriptionForm')
                        .removeClass().addClass('form-group has-error');
                $todoDescription.focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    staffId: $staffId.val(),
                    todoCategoryId: $todoCategoryId.val(),
                    todoTitle: $todoTitle.val(),
                    todoDueDate: $todoDueDate.val(),
                    todoDescription: $todoDescription.val(),
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
                        $staffId.val('');
                        $staffId.trigger("chosen:updated");
                        $('#staffIdHelpMe')
                                .html('').empty();
                        $todoCategoryId.val('');
                        $todoCategoryId.trigger("chosen:updated");
                        $('#todoCategoryIdHelpMe')
                                .html('').empty();
                        $todoTitle.val('');
                        $('#todoTitleHelpMe')
                                .html('').empty();
                        $todoDueDate.val('');
                        $('#todoDueDateHelpMe')
                                .html('').empty();
                        $todoDescription.val('');
                        $('#todoDescriptionHelpMe')
                                .html('').empty();
                    } else if (success === false) {
                        $infoPanel
                                .html('').empty()
                                .html("<span class='label label-important'>&nbsp;" + message + "</span>");
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
            if ($staffId.val().length === 0) {
                $('#staffIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffIdLabel'] + " </span>");
                $staffId.data('chosen').activate_action();
                return false;
            }
            if ($todoCategoryId.val().length === 0) {
                $('#todoCategoryIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoCategoryIdLabel'] + " </span>");
                $todoCategoryId.data('chosen').activate_action();
                return false;
            }
            if ($todoTitle.val().length === 0) {
                $('#todoTitleHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoTitleLabel'] + " </span>");
                $('#todoTitleForm')
                        .removeClass().addClass('form-group has-error');
                $todoTitle.focus();
                return false;
            }
            if ($todoDueDate.val().length === 0) {
                $('#todoDueDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoDueDateLabel'] + " </span>");
                $('#todoDueDateForm')
                        .removeClass().addClass('form-group has-error');
                $todoDueDate.focus();
                return false;
            }
            if ($todoDescription.val().length === 0) {
                $('#todoDescriptionHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoDescriptionLabel'] + " </span>");
                $('#todoDescriptionForm')
                        .removeClass().addClass('form-group has-error');
                $todoDescription.focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    staffId: $staffId.val(),
                    todoCategoryId: $todoCategoryId.val(),
                    todoTitle: $todoTitle.val(),
                    todoDueDate: $todoDueDate.val(),
                    todoDescription: $todoDescription.val(),
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
                    if (success === true) {
                        $infoPanel
                                .html('').empty()
                                .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>");
                        $('#todoId').val(data.todoId);
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
            if ($staffId.val().length === 0) {
                $('#staffIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffIdLabel'] + " </span>");
                $staffId.data('chosen').activate_action();
                return false;
            }
            if ($todoCategoryId.val().length === 0) {
                $('#todoCategoryIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoCategoryIdLabel'] + " </span>");
                $todoCategoryId.data('chosen').activate_action();
                return false;
            }
            if ($todoTitle.val().length === 0) {
                $('#todoTitleHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoTitleLabel'] + " </span>");
                $('#todoTitleForm')
                        .removeClass().addClass('form-group has-error');
                $todoTitle.focus();
                return false;
            }
            if ($todoDueDate.val().length === 0) {
                $('#todoDueDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoDueDateLabel'] + " </span>");
                $('#todoDueDateForm')
                        .removeClass().addClass('form-group has-error');
                $todoDueDate.focus();
                return false;
            }
            if ($todoDescription.val().length === 0) {
                $('#todoDescriptionHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoDescriptionLabel'] + " </span>");
                $('#todoDescriptionForm')
                        .removeClass().addClass('form-group has-error');
                $todoDescription.focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    staffId: $staffId.val(),
                    todoCategoryId: $todoCategoryId.val(),
                    todoTitle: $todoTitle.val(),
                    todoDueDate: $todoDueDate.val(),
                    todoDescription: $todoDescription.val(),
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
                                .html("<span class='label label-important'> <img src='" + smileyRollSweat + "'> " + message + "</span>");
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
    var $todoId = $('#todoId');
    var $staffId = $('#staffId');
    var $todoCategoryId = $('#todoCategoryId');
    var $todoTitle = $('#todoTitle');
    var $todoDueDate = $('#todoDueDate');
    var $todoDescription = $('#todoDescription');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $infoPanel
                .empty().html('');
        if (type === 1) {
            if ($staffId.val().length === 0) {
                $('#staffIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffIdLabel'] + " </span>");
                $staffId.data('chosen').activate_action();
                return false;
            }
            if ($todoCategoryId.val().length === 0) {
                $('#todoCategoryIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoCategoryIdLabel'] + " </span>");
                $todoCategoryId.data('chosen').activate_action();
                return false;
            }
            if ($todoTitle.val().length === 0) {
                $('#todoTitleHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoTitleLabel'] + " </span>");
                $('#todoTitleForm')
                        .removeClass().addClass('form-group has-error');
                $todoTitle.focus();
                return false;
            }
            if ($todoDueDate.val().length === 0) {
                $('#todoDueDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoDueDateLabel'] + " </span>");
                $('#todoDueDateForm')
                        .removeClass().addClass('form-group has-error');
                $todoDueDate.focus();
                return false;
            }
            if ($todoDescription.val().length === 0) {
                $('#todoDescriptionHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoDescriptionLabel'] + " </span>");
                $('#todoDescriptionForm')
                        .removeClass().addClass('form-group has-error');
                $todoDescription.focus();
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
                    todoId: $todoId.val(),
                    staffId: $staffId.val(),
                    todoCategoryId: $todoCategoryId.val(),
                    todoTitle: $todoTitle.val(),
                    todoDueDate: $todoDueDate.val(),
                    todoDescription: $todoDescription.val(),
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
                        $infoPanel.empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
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
            if ($staffId.val().length === 0) {
                $('#staffIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffIdLabel'] + " </span>");
                $staffId.data('chosen').activate_action();
                return false;
            }
            if ($todoCategoryId.val().length === 0) {
                $('#todoCategoryIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoCategoryIdLabel'] + " </span>");
                $todoCategoryId.data('chosen').activate_action();
                return false;
            }
            if ($todoTitle.val().length === 0) {
                $('#todoTitleHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoTitleLabel'] + " </span>");
                $('#todoTitleForm').removeClass().addClass('form-group has-error');
                $todoTitle.focus();
                return false;
            }
            if ($todoDueDate.val().length === 0) {
                $('#todoDueDateHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoDueDateLabel'] + " </span>");
                $('#todoDueDateForm').removeClass().addClass('form-group has-error');
                $todoDueDate.focus();
                return false;
            }
            if ($todoDescription.val().length === 0) {
                $('#todoDescriptionHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['todoDescriptionLabel'] + " </span>");
                $('#todoDescriptionForm').removeClass().addClass('form-group has-error');
                $todoDescription.focus();
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
                    todoId: $todoId.val(),
                    staffId: $staffId.val(),
                    todoCategoryId: $todoCategoryId.val(),
                    todoTitle: $todoTitle.val(),
                    todoDueDate: $todoDueDate.val(),
                    todoDescription: $todoDescription.val(),
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
                                .html("<span class='label label-important'>&nbsp;" + message + "</span>");
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
    var $todoId = $('#todoId');
    var css = $('#deleteRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (deleteAccess === 1) {
            if (confirm(decodeURIComponent(t['deleteRecordMessageLabel']))) {
                var value = $todoId.val();
                if (!value) {
                    $infoPanel
                            .html('').empty()
                            .html("<span class='label label-important'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "<span>");
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
                            todoId: $todoId.val(),
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
                                        .html("<span class='label label-important'>&nbsp;" + message + "</span>");
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
function resetRecord(leafId, url, urlList, urlPurchaseInvoiceCreditNoteDetail, securityToken, createAccess,updateAccess,deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var resetIcon = './images/icons/fruit-orange.png';
    $infoPanel
            .html('').empty()
            .html("<span class='label label-important'><img src='" + resetIcon + "'> " + decodeURIComponent(t['resetRecordTextLabel']) + "</span>").delay(1000).fadeOut();
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
            .attr('onClick', "firstRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
    $('#previousRecordButton')
            .removeClass().addClass('btn btn-default disabled')
            .attr('onClick', '');
    $('#nextRecordButton')
            .removeClass().addClass('btn btn-default disabled')
            .attr('onClick', '');
    $('#endRecordButton')
            .removeClass().addClass('btn btn-default')
            .attr('onClick', "endRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + ")");
    $("#todoId").val('');
    $("#todoIdHelpMe")
            .empty().html('');
    
    $("#staffId").val('');
    $("#staffIdHelpMe")
            .empty().html('');
    $('#staffId').trigger("chosen:updated");
    $("#todoCategoryId").val('');
    $("#todoCategoryIdHelpMe")
            .empty().html('');
    $('#todoCategoryId').trigger("chosen:updated");
    $("#todoTitle").val('');
    $("#todoTitleHelpMe")
            .empty().html('');
    $("#todoDueDate").val('');
    $("#todoDueDateHelpMe")
            .empty().html('');
    $("#todoDescription").val('');
    $("#todoDescriptionHelpMe")
            .empty().html('');
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
                            todoId: firstRecord,
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
                                $('#todoId').val(data.data.todoId);
                                $('#staffId').val(data.data.staffId).trigger("chosen:updated");
                                $('#todoCategoryId').val(data.data.todoCategoryId).trigger("chosen:updated");
                                $('#todoTitle').val(data.data.todoTitle);
                                x = data.data.todoDueDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#todoDueDate').val(output);
                                $('#todoDescription').val(data.data.todoDescription);
                                if (nextRecord > 0) {
                                    $('#previousRecordButton')
                                            .removeClass().addClass('btn btn-default disabled')
                                            .attr('onClick', '');
                                    $('#nextRecordButton')
                                            .removeClass().addClass('btn btn-default')
                                            .attr('onClick', '')
                                            .attr('onClick', "nextRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
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
                            .html("<span class='label label-important'>&nbsp;<img src='" + smileyRollSweat + "'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                            todoId: lastRecord,
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
                                $('#todoId').val(data.data.todoId);
                                $('#staffId').val(data.data.staffId).trigger("chosen:updated");
                                $('#todoCategoryId').val(data.data.todoCategoryId).trigger("chosen:updated");
                                $('#todoTitle').val(data.data.todoTitle);
                                x = data.data.todoDueDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#todoDueDate').val(output);
                                $('#todoDescription').val(data.data.todoDescription);
                                if (lastRecord !== 0) {
                                    $('#previousRecordButton')
                                            .removeClass().addClass('btn btn-default')
                                            .attr('onClick', "previousRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
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
                    $infoPanel.html("<span class='label label-important'>&nbsp;" + message + "</span>");
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
function previousRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess) {
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
                    .html("<span class='label label-important'>" + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                    todoId: $previousRecordCounter.val(),
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
                        $('#todoId').val(data.data.todoId);
                        $('#staffId').val(data.data.staffId).trigger("chosen:updated");
                        $('#todoCategoryId').val(data.data.todoCategoryId).trigger("chosen:updated");
                        $('#todoTitle').val(data.data.todoTitle);
                        x = data.data.todoDueDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#todoDueDate').val(output);
                        $('#todoDescription').val(data.data.todoDescription);
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
                                .attr('onClick', '').attr('onClick', "nextRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
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
function nextRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess) {
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
                    .html("<span class='label label-important'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                    todoId: $nextRecordCounter.val(),
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
                        $('#todoId').val(data.data.todoId);
                        $('#staffId').val(data.data.staffId).trigger("chosen:updated");
                        $('#todoCategoryId').val(data.data.todoCategoryId).trigger("chosen:updated");
                        $('#todoTitle').val(data.data.todoTitle);
                        x = data.data.todoDueDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#todoDueDate').val(output);
                        $('#todoDescription').val(data.data.todoDescription);
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
                        $('#firstRecordCounter').val(firstRecord);
                        $('#previousRecordCounter').val(previousRecord);
                        $('#nextRecordCounter').val(nextRecord);
                        $('#lastRecordCounter').val(lastRecord);
                        if (parseFloat(previousRecord) > 0) {
                            $('#previousRecordButton')
                                    .removeClass().addClass('btn btn-default')
                                    .attr('onClick', "previousRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
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

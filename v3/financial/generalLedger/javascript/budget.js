function getChartOfAccount(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'chartOfAccount'}, beforeSend: function() {
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
                $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#chartOfAccountId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function getFinanceYear(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'financeYear'}, beforeSend: function() {
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
                $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#financeYearId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function checkDuplicate(leafId, page, securityToken) {
    var $budgetCode = $("#budgetCode");
    if ($budgetCode.val().length === 0) {
        alert(t['oddTextLabel']);
        return false;
    }
    $.ajax({type: 'GET', url: page, data: {budgetCode: $budgetCode.val(), method: 'duplicate', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                    $("#budgetCode").val('').focus();
                    $("#budgetCodeForm").removeClass().addClass("form-group has-error");
                    $infoPanel.html('').empty().html("<img src='" + smileyRoll + "'> " + t['codeDuplicateTextLabel']).delay(5000).fadeOut();
                } else {
                    $infoPanel.html('').empty().html("<img src='" + smileyLol + "'> " + t['codeAvailableTextLabel']).delay(5000).fadeOut();
                }
            } else {
                $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                $("#budgetForm").removeClass().addClass("form-group has-error");
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                $centerViewPort.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
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
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                $centerViewPort.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
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
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                $centerViewPort.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
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
            $('#infoError').html('').empty().html('').html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                $centerViewPort.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</span>");
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
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                $centerViewPort.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
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
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function showFormUpdate(leafId, url, urlList, securityToken, budgetId, updateAccess, deleteAccess) {
    sleep(500);
    $('a[rel=tooltip]').tooltip('hide');
    $.ajax({type: 'POST', url: urlList, data: {method: 'read', type: 'form', budgetId: budgetId, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                $centerViewPort.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
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
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function showModalDelete(budgetId, chartOfAccountId, financeYearId, budgetTargetMonthOne, budgetActualMonthOne, budgetTargetMonthTwo, budgetActualMonthTwo, budgetTargetMonthThree, budgetActualMonthThree, budgetTargetMonthFourth, budgetActualMonthFourth, budgetTargetMonthFifth, budgetActualMonthFifth, budgetTargetMonthSix, budgetActualMonthSix, budgetTargetMonthSeven, budgetActualMonthSeven, budgetTargetMonthEight, budgetActualMonthEight, budgetTargetMonthNine, budgetActualMonthNine, budgetTargetMonthTen, budgetActualMonthTen, budgetTargetMonthEleven, budgetActualMonthEleven, budgetTargetMonthTwelve, budgetActualMonthTwelve, budgetTargetMonthThirteen, budgetActualMonthThirteen, budgetTargetMonthFourteen, budgetActualMonthFourteen, budgetTargetMonthFifteen, budgetActualMonthFifteen, budgetTargetMonthSixteen, budgetActualMonthSixteen, budgetTargetMonthSeventeen, budgetActualMonthSeventeen, budgetTargetMonthEighteen, budgetActualMonthEighteen, budgetTargetTotalYear, budgetActualTotalYear, budgetVersion, isLock) {
    $('#budgetIdPreview').val('').val(decodeURIComponent(budgetId));
    $('#chartOfAccountIdPreview').val('').val(decodeURIComponent(chartOfAccountId));
    $('#financeYearIdPreview').val('').val(decodeURIComponent(financeYearId));
    $('#budgetTargetMonthOnePreview').val('').val(decodeURIComponent(budgetTargetMonthOne));
    $('#budgetActualMonthOnePreview').val('').val(decodeURIComponent(budgetActualMonthOne));
    $('#budgetTargetMonthTwoPreview').val('').val(decodeURIComponent(budgetTargetMonthTwo));
    $('#budgetActualMonthTwoPreview').val('').val(decodeURIComponent(budgetActualMonthTwo));
    $('#budgetTargetMonthThreePreview').val('').val(decodeURIComponent(budgetTargetMonthThree));
    $('#budgetActualMonthThreePreview').val('').val(decodeURIComponent(budgetActualMonthThree));
    $('#budgetTargetMonthFourthPreview').val('').val(decodeURIComponent(budgetTargetMonthFourth));
    $('#budgetActualMonthFourthPreview').val('').val(decodeURIComponent(budgetActualMonthFourth));
    $('#budgetTargetMonthFifthPreview').val('').val(decodeURIComponent(budgetTargetMonthFifth));
    $('#budgetActualMonthFifthPreview').val('').val(decodeURIComponent(budgetActualMonthFifth));
    $('#budgetTargetMonthSixPreview').val('').val(decodeURIComponent(budgetTargetMonthSix));
    $('#budgetActualMonthSixPreview').val('').val(decodeURIComponent(budgetActualMonthSix));
    $('#budgetTargetMonthSevenPreview').val('').val(decodeURIComponent(budgetTargetMonthSeven));
    $('#budgetActualMonthSevenPreview').val('').val(decodeURIComponent(budgetActualMonthSeven));
    $('#budgetTargetMonthEightPreview').val('').val(decodeURIComponent(budgetTargetMonthEight));
    $('#budgetActualMonthEightPreview').val('').val(decodeURIComponent(budgetActualMonthEight));
    $('#budgetTargetMonthNinePreview').val('').val(decodeURIComponent(budgetTargetMonthNine));
    $('#budgetActualMonthNinePreview').val('').val(decodeURIComponent(budgetActualMonthNine));
    $('#budgetTargetMonthTenPreview').val('').val(decodeURIComponent(budgetTargetMonthTen));
    $('#budgetActualMonthTenPreview').val('').val(decodeURIComponent(budgetActualMonthTen));
    $('#budgetTargetMonthElevenPreview').val('').val(decodeURIComponent(budgetTargetMonthEleven));
    $('#budgetActualMonthElevenPreview').val('').val(decodeURIComponent(budgetActualMonthEleven));
    $('#budgetTargetMonthTwelvePreview').val('').val(decodeURIComponent(budgetTargetMonthTwelve));
    $('#budgetActualMonthTwelvePreview').val('').val(decodeURIComponent(budgetActualMonthTwelve));
    $('#budgetTargetMonthThirteenPreview').val('').val(decodeURIComponent(budgetTargetMonthThirteen));
    $('#budgetActualMonthThirteenPreview').val('').val(decodeURIComponent(budgetActualMonthThirteen));
    $('#budgetTargetMonthFourteenPreview').val('').val(decodeURIComponent(budgetTargetMonthFourteen));
    $('#budgetActualMonthFourteenPreview').val('').val(decodeURIComponent(budgetActualMonthFourteen));
    $('#budgetTargetMonthFifteenPreview').val('').val(decodeURIComponent(budgetTargetMonthFifteen));
    $('#budgetActualMonthFifteenPreview').val('').val(decodeURIComponent(budgetActualMonthFifteen));
    $('#budgetTargetMonthSixteenPreview').val('').val(decodeURIComponent(budgetTargetMonthSixteen));
    $('#budgetActualMonthSixteenPreview').val('').val(decodeURIComponent(budgetActualMonthSixteen));
    $('#budgetTargetMonthSeventeenPreview').val('').val(decodeURIComponent(budgetTargetMonthSeventeen));
    $('#budgetActualMonthSeventeenPreview').val('').val(decodeURIComponent(budgetActualMonthSeventeen));
    $('#budgetTargetMonthEighteenPreview').val('').val(decodeURIComponent(budgetTargetMonthEighteen));
    $('#budgetActualMonthEighteenPreview').val('').val(decodeURIComponent(budgetActualMonthEighteen));
    $('#budgetTargetTotalYearPreview').val('').val(decodeURIComponent(budgetTargetTotalYear));
    $('#budgetActualTotalYearPreview').val('').val(decodeURIComponent(budgetActualTotalYear));
    $('#budgetVersionPreview').val('').val(decodeURIComponent(budgetVersion));
    $('#isLockPreview').val('').val(decodeURIComponent(isLock));
    showMeModal('deletePreview', 1);
}
function deleteGridRecord(leafId, url, urlList, securityToken) {
    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', budgetId: $('#budgetIdPreview').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function deleteGridRecordCheckbox(leafId, url, urlList, securityToken) {
    var stringText = '';
    var counter = 0;
    $('input:checkbox[name="budgetId[]"]').each(function() {
        stringText = stringText + "&budgetId[]=" + $(this).val();
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
                $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;" + message + "</span>");
            } else {
                $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;" + message + "</span>");
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                var path = "./v3/financial/generalLedger/document/" + folder + "/" + filename;
                $infoPanel.html('').empty().html("<span class='label label-success'>" + decodeURIComponent(t['requestFileTextLabel']) + "</span>");
                window.open(path);
            } else {
                $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
    var $chartOfAccountId = $('#chartOfAccountId');
    var $financeYearId = $('#financeYearId');
    var $budgetTargetMonthOne = $('#budgetTargetMonthOne');
    var $budgetTargetMonthTwo = $('#budgetTargetMonthTwo');
    var $budgetTargetMonthThree = $('#budgetTargetMonthThree');
    var $budgetTargetMonthFourth = $('#budgetTargetMonthFourth');
    var $budgetTargetMonthFifth = $('#budgetTargetMonthFifth');
    var $budgetTargetMonthSix = $('#budgetTargetMonthSix');
    var $budgetTargetMonthSeven = $('#budgetTargetMonthSeven');
    var $budgetTargetMonthEight = $('#budgetTargetMonthEight');
    var $budgetTargetMonthNine = $('#budgetTargetMonthNine');
    var $budgetTargetMonthTen = $('#budgetTargetMonthTen');
    var $budgetTargetMonthEleven = $('#budgetTargetMonthEleven');
    var $budgetTargetMonthTwelve = $('#budgetTargetMonthTwelve');
    var $budgetTargetMonthThirteen = $('#budgetTargetMonthThirteen');
    var $budgetTargetMonthFourteen = $('#budgetTargetMonthFourteen');
    var $budgetTargetMonthFifteen = $('#budgetTargetMonthFifteen');
    var $budgetTargetMonthSixteen = $('#budgetTargetMonthSixteen');
    var $budgetTargetMonthSeventeen = $('#budgetTargetMonthSeventeen');
    var $budgetTargetMonthEighteen = $('#budgetTargetMonthEighteen');
    var $budgetVersion = $('#budgetVersion');
    var $isLock = $('#isLock');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (type === 1) {
            if ($chartOfAccountId.val().length === 0) {
                $('#chartOfAccountIdHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountIdLabel'] + " </span>");
                $chartOfAccountId.data('chosen').activate_action();
                return false;
            }
            if ($financeYearId.val().length === 0) {
                $('#financeYearIdHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['financeYearIdLabel'] + " </span>");
                $financeYearId.data('chosen').activate_action();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', chartOfAccountId: $chartOfAccountId.val(), financeYearId: $financeYearId.val(), budgetTargetMonthOne: $budgetTargetMonthOne.val(), budgetTargetMonthTwo: $budgetTargetMonthTwo.val(), budgetTargetMonthThree: $budgetTargetMonthThree.val(), budgetTargetMonthFourth: $budgetTargetMonthFourth.val(), budgetTargetMonthFifth: $budgetTargetMonthFifth.val(), budgetTargetMonthSix: $budgetTargetMonthSix.val(), budgetTargetMonthSeven: $budgetTargetMonthSeven.val(), budgetTargetMonthEight: $budgetTargetMonthEight.val(), budgetTargetMonthNine: $budgetTargetMonthNine.val(), budgetTargetMonthTen: $budgetTargetMonthTen.val(), budgetTargetMonthEleven: $budgetTargetMonthEleven.val(), budgetTargetMonthTwelve: $budgetTargetMonthTwelve.val(), budgetTargetMonthThirteen: $budgetTargetMonthThirteen.val(), budgetTargetMonthFourteen: $budgetTargetMonthFourteen.val(), budgetTargetMonthFifteen: $budgetTargetMonthFifteen.val(), budgetTargetMonthSixteen: $budgetTargetMonthSixteen.val(), budgetTargetMonthSeventeen: $budgetTargetMonthSeventeen.val(), budgetTargetMonthEighteen: $budgetTargetMonthEighteen.val(), budgetVersion: $budgetVersion.val(), isLock: $isLock.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                        $chartOfAccountId.val('');
                        $chartOfAccountId.trigger("chosen:updated");
                        $('#chartOfAccountIdHelpMe').html('').empty();
                        $financeYearId.val('');
                        $financeYearId.trigger("chosen:updated");
                        $('#financeYearIdHelpMe').html('').empty();
                        $budgetTargetMonthOne.val('');
                        $('#budgetTargetMonthOneHelpMe').html('').empty();
                        $budgetTargetMonthTwo.val('');
                        $('#budgetTargetMonthTwoHelpMe').html('').empty();
                        $budgetTargetMonthThree.val('');
                        $('#budgetTargetMonthThreeHelpMe').html('').empty();
                        $budgetTargetMonthFourth.val('');
                        $('#budgetTargetMonthFourthHelpMe').html('').empty();
                        $budgetTargetMonthFifth.val('');
                        $('#budgetTargetMonthFifthHelpMe').html('').empty();
                        $budgetTargetMonthSix.val('');
                        $('#budgetTargetMonthSixHelpMe').html('').empty();
                        $budgetTargetMonthSeven.val('');
                        $('#budgetTargetMonthSevenHelpMe').html('').empty();
                        $budgetTargetMonthEight.val('');
                        $('#budgetTargetMonthEightHelpMe').html('').empty();
                        $budgetTargetMonthNine.val('');
                        $('#budgetTargetMonthNineHelpMe').html('').empty();
                        $budgetTargetMonthTen.val('');
                        $('#budgetTargetMonthTenHelpMe').html('').empty();
                        $budgetTargetMonthEleven.val('');
                        $('#budgetTargetMonthElevenHelpMe').html('').empty();
                        $budgetTargetMonthTwelve.val('');
                        $('#budgetTargetMonthTwelveHelpMe').html('').empty();
                        $budgetTargetMonthThirteen.val('');
                        $('#budgetTargetMonthThirteenHelpMe').html('').empty();
                        $budgetTargetMonthFourteen.val('');
                        $('#budgetTargetMonthFourteenHelpMe').html('').empty();
                        $budgetTargetMonthFifteen.val('');
                        $('#budgetTargetMonthFifteenHelpMe').html('').empty();
                        $budgetTargetMonthSixteen.val('');
                        $('#budgetTargetMonthSixteenHelpMe').html('').empty();
                        $budgetTargetMonthSeventeen.val('');
                        $('#budgetTargetMonthSeventeenHelpMe').html('').empty();
                        $budgetTargetMonthEighteen.val('');
                        $('#budgetTargetMonthEighteenHelpMe').html('').empty();
                        $budgetVersion.val('');
                        $('#budgetVersionHelpMe').html('').empty();
                        $isLock.val('');
                        $('#isLockHelpMe').html('').empty();
                    } else if (success === false) {
                        $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                }, error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        } else if (type === 2) {
            if ($chartOfAccountId.val().length === 0) {
                $('#chartOfAccountIdHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountIdLabel'] + " </span>");
                $chartOfAccountId.data('chosen').activate_action();
                return false;
            }
            if ($financeYearId.val().length === 0) {
                $('#financeYearIdHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['financeYearIdLabel'] + " </span>");
                $financeYearId.data('chosen').activate_action();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', chartOfAccountId: $chartOfAccountId.val(), financeYearId: $financeYearId.val(), budgetTargetMonthOne: $budgetTargetMonthOne.val(), budgetTargetMonthTwo: $budgetTargetMonthTwo.val(), budgetTargetMonthThree: $budgetTargetMonthThree.val(), budgetTargetMonthFourth: $budgetTargetMonthFourth.val(), budgetTargetMonthFifth: $budgetTargetMonthFifth.val(), budgetTargetMonthSix: $budgetTargetMonthSix.val(), budgetTargetMonthSeven: $budgetTargetMonthSeven.val(), budgetTargetMonthEight: $budgetTargetMonthEight.val(), budgetTargetMonthNine: $budgetTargetMonthNine.val(), budgetTargetMonthTen: $budgetTargetMonthTen.val(), budgetTargetMonthEleven: $budgetTargetMonthEleven.val(), budgetTargetMonthTwelve: $budgetTargetMonthTwelve.val(), budgetTargetMonthThirteen: $budgetTargetMonthThirteen.val(), budgetTargetMonthFourteen: $budgetTargetMonthFourteen.val(), budgetTargetMonthFifteen: $budgetTargetMonthFifteen.val(), budgetTargetMonthSixteen: $budgetTargetMonthSixteen.val(), budgetTargetMonthSeventeen: $budgetTargetMonthSeventeen.val(), budgetTargetMonthEighteen: $budgetTargetMonthEighteen.val(), budgetVersion: $budgetVersion.val(), isLock: $isLock.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                        $('#budgetId').val(data.budgetId);
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
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        } else if (type === 5) {
            if ($chartOfAccountId.val().length === 0) {
                $('#chartOfAccountIdHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountIdLabel'] + " </span>");
                $chartOfAccountId.data('chosen').activate_action();
                return false;
            }
            if ($financeYearId.val().length === 0) {
                $('#financeYearIdHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['financeYearIdLabel'] + " </span>");
                $financeYearId.data('chosen').activate_action();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', chartOfAccountId: $chartOfAccountId.val(), financeYearId: $financeYearId.val(), budgetTargetMonthOne: $budgetTargetMonthOne.val(), budgetTargetMonthTwo: $budgetTargetMonthTwo.val(), budgetTargetMonthThree: $budgetTargetMonthThree.val(), budgetTargetMonthFourth: $budgetTargetMonthFourth.val(), budgetTargetMonthFifth: $budgetTargetMonthFifth.val(), budgetTargetMonthSix: $budgetTargetMonthSix.val(), budgetTargetMonthSeven: $budgetTargetMonthSeven.val(), budgetTargetMonthEight: $budgetTargetMonthEight.val(), budgetTargetMonthNine: $budgetTargetMonthNine.val(), budgetTargetMonthTen: $budgetTargetMonthTen.val(), budgetTargetMonthEleven: $budgetTargetMonthEleven.val(), budgetTargetMonthTwelve: $budgetTargetMonthTwelve.val(), budgetTargetMonthThirteen: $budgetTargetMonthThirteen.val(), budgetTargetMonthFourteen: $budgetTargetMonthFourteen.val(), budgetTargetMonthFifteen: $budgetTargetMonthFifteen.val(), budgetTargetMonthSixteen: $budgetTargetMonthSixteen.val(), budgetTargetMonthSeventeen: $budgetTargetMonthSeventeen.val(), budgetTargetMonthEighteen: $budgetTargetMonthEighteen.val(), budgetVersion: $budgetVersion.val(), isLock: $isLock.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                        $infoPanel.html('').empty().html("<span class='label label-danger'> <img src='" + smileyRollSweat + "'> " + message + "</span>");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                }, error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
    var $chartOfAccountId = $('#chartOfAccountId');
    var $financeYearId = $('#financeYearId');
    var $budgetTargetMonthOne = $('#budgetTargetMonthOne');
    var $budgetTargetMonthTwo = $('#budgetTargetMonthTwo');
    var $budgetTargetMonthThree = $('#budgetTargetMonthThree');
    var $budgetTargetMonthFourth = $('#budgetTargetMonthFourth');
    var $budgetTargetMonthFifth = $('#budgetTargetMonthFifth');
    var $budgetTargetMonthSix = $('#budgetTargetMonthSix');
    var $budgetTargetMonthSeven = $('#budgetTargetMonthSeven');
    var $budgetTargetMonthEight = $('#budgetTargetMonthEight');
    var $budgetTargetMonthNine = $('#budgetTargetMonthNine');
    var $budgetTargetMonthTen = $('#budgetTargetMonthTen');
    var $budgetTargetMonthEleven = $('#budgetTargetMonthEleven');
    var $budgetTargetMonthTwelve = $('#budgetTargetMonthTwelve');
    var $budgetTargetMonthThirteen = $('#budgetTargetMonthThirteen');
    var $budgetTargetMonthFourteen = $('#budgetTargetMonthFourteen');
    var $budgetTargetMonthFifteen = $('#budgetTargetMonthFifteen');
    var $budgetTargetMonthSixteen = $('#budgetTargetMonthSixteen');
    var $budgetTargetMonthSeventeen = $('#budgetTargetMonthSeventeen');
    var $budgetTargetMonthEighteen = $('#budgetTargetMonthEighteen');
    var $budgetVersion = $('#budgetVersion');
    var $isLock = $('#isLock');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $infoPanel.empty().html('');
        if (type === 1) {
            if ($chartOfAccountId.val().length === 0) {
                $('#chartOfAccountIdHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountIdLabel'] + " </span>");
                $chartOfAccountId.data('chosen').activate_action();
                return false;
            }
            if ($financeYearId.val().length === 0) {
                $('#financeYearIdHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['financeYearIdLabel'] + " </span>");
                $financeYearId.data('chosen').activate_action();
                return false;
            }
            $infoPanel.html('').empty();
            $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', budgetId: $budgetId.val(), chartOfAccountId: $chartOfAccountId.val(), financeYearId: $financeYearId.val(), budgetTargetMonthOne: $budgetTargetMonthOne.val(), budgetTargetMonthTwo: $budgetTargetMonthTwo.val(), budgetTargetMonthThree: $budgetTargetMonthThree.val(), budgetTargetMonthFourth: $budgetTargetMonthFourth.val(), budgetTargetMonthFifth: $budgetTargetMonthFifth.val(), budgetTargetMonthSix: $budgetTargetMonthSix.val(), budgetTargetMonthSeven: $budgetTargetMonthSeven.val(), budgetTargetMonthEight: $budgetTargetMonthEight.val(), budgetTargetMonthNine: $budgetTargetMonthNine.val(), budgetTargetMonthTen: $budgetTargetMonthTen.val(), budgetTargetMonthEleven: $budgetTargetMonthEleven.val(), budgetTargetMonthTwelve: $budgetTargetMonthTwelve.val(), budgetTargetMonthThirteen: $budgetTargetMonthThirteen.val(), budgetTargetMonthFourteen: $budgetTargetMonthFourteen.val(), budgetTargetMonthFifteen: $budgetTargetMonthFifteen.val(), budgetTargetMonthSixteen: $budgetTargetMonthSixteen.val(), budgetTargetMonthSeventeen: $budgetTargetMonthSeventeen.val(), budgetTargetMonthEighteen: $budgetTargetMonthEighteen.val(), budgetVersion: $budgetVersion.val(), isLock: $isLock.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                        $infoPanel.empty().html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                }, error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        } else if (type === 3) {
            if ($chartOfAccountId.val().length === 0) {
                $('#chartOfAccountIdHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountIdLabel'] + " </span>");
                $chartOfAccountId.data('chosen').activate_action();
                return false;
            }
            if ($financeYearId.val().length === 0) {
                $('#financeYearIdHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['financeYearIdLabel'] + " </span>");
                $financeYearId.data('chosen').activate_action();
                return false;
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', budgetId: $budgetId.val(), chartOfAccountId: $chartOfAccountId.val(), financeYearId: $financeYearId.val(), budgetTargetMonthOne: $budgetTargetMonthOne.val(), budgetTargetMonthTwo: $budgetTargetMonthTwo.val(), budgetTargetMonthThree: $budgetTargetMonthThree.val(), budgetTargetMonthFourth: $budgetTargetMonthFourth.val(), budgetTargetMonthFifth: $budgetTargetMonthFifth.val(), budgetTargetMonthSix: $budgetTargetMonthSix.val(), budgetTargetMonthSeven: $budgetTargetMonthSeven.val(), budgetTargetMonthEight: $budgetTargetMonthEight.val(), budgetTargetMonthNine: $budgetTargetMonthNine.val(), budgetTargetMonthTen: $budgetTargetMonthTen.val(), budgetTargetMonthEleven: $budgetTargetMonthEleven.val(), budgetTargetMonthTwelve: $budgetTargetMonthTwelve.val(), budgetTargetMonthThirteen: $budgetTargetMonthThirteen.val(), budgetTargetMonthFourteen: $budgetTargetMonthFourteen.val(), budgetTargetMonthFifteen: $budgetTargetMonthFifteen.val(), budgetTargetMonthSixteen: $budgetTargetMonthSixteen.val(), budgetTargetMonthSeventeen: $budgetTargetMonthSeventeen.val(), budgetTargetMonthEighteen: $budgetTargetMonthEighteen.val(), budgetVersion: $budgetVersion.val(), isLock: $isLock.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                        $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                    }
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }, error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        }
    }
}
function deleteRecord(leafId, url, urlList, securityToken, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var $budgetId = $('#budgetId');
    var css = $('#deleteRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (deleteAccess === 1) {
            if (confirm(decodeURIComponent(t['deleteRecordMessageLabel']))) {
                var value = $budgetId.val();
                if (!value) {
                    $infoPanel.html('').empty().html("<span class='label label-danger'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "<span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                    return false;
                } else {
                    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', budgetId: $budgetId.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                                $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                                if ($infoPanel.is(':hidden')) {
                                    $infoPanel.show();
                                }
                            }
                        }, error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
    $infoPanel.html('').empty().html("<span class='label label-danger'><img src='" + resetIcon + "'> " + decodeURIComponent(t['resetRecordTextLabel']) + "</span>").delay(1000).fadeOut();
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
    $('#firstRecordButton').removeClass().addClass('btn btn-info').attr('onClick', "firstRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
    $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
    $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
    $('#endRecordButton').removeClass().addClass('btn btn-info').attr('onClick', "endRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + ")");
    $("#budgetId").val('');
    $("#budgetIdHelpMe").empty().html('');

    $("#chartOfAccountId").val('');
    $("#chartOfAccountIdHelpMe").empty().html('');
    $('#chartOfAccountId').trigger("chosen:updated");
    $("#financeYearId").val('');
    $("#financeYearIdHelpMe").empty().html('');
    $('#financeYearId').trigger("chosen:updated");
    $("#budgetTargetMonthOne").val('');
    $("#budgetTargetMonthOneHelpMe").empty().html('');
    $("#budgetActualMonthOne").val('');
    $("#budgetActualMonthOneHelpMe").empty().html('');
    $("#budgetTargetMonthTwo").val('');
    $("#budgetTargetMonthTwoHelpMe").empty().html('');
    $("#budgetActualMonthTwo").val('');
    $("#budgetActualMonthTwoHelpMe").empty().html('');
    $("#budgetTargetMonthThree").val('');
    $("#budgetTargetMonthThreeHelpMe").empty().html('');
    $("#budgetActualMonthThree").val('');
    $("#budgetActualMonthThreeHelpMe").empty().html('');
    $("#budgetTargetMonthFourth").val('');
    $("#budgetTargetMonthFourthHelpMe").empty().html('');
    $("#budgetActualMonthFourth").val('');
    $("#budgetActualMonthFourthHelpMe").empty().html('');
    $("#budgetTargetMonthFifth").val('');
    $("#budgetTargetMonthFifthHelpMe").empty().html('');
    $("#budgetActualMonthFifth").val('');
    $("#budgetActualMonthFifthHelpMe").empty().html('');
    $("#budgetTargetMonthSix").val('');
    $("#budgetTargetMonthSixHelpMe").empty().html('');
    $("#budgetActualMonthSix").val('');
    $("#budgetActualMonthSixHelpMe").empty().html('');
    $("#budgetTargetMonthSeven").val('');
    $("#budgetTargetMonthSevenHelpMe").empty().html('');
    $("#budgetActualMonthSeven").val('');
    $("#budgetActualMonthSevenHelpMe").empty().html('');
    $("#budgetTargetMonthEight").val('');
    $("#budgetTargetMonthEightHelpMe").empty().html('');
    $("#budgetActualMonthEight").val('');
    $("#budgetActualMonthEightHelpMe").empty().html('');
    $("#budgetTargetMonthNine").val('');
    $("#budgetTargetMonthNineHelpMe").empty().html('');
    $("#budgetActualMonthNine").val('');
    $("#budgetActualMonthNineHelpMe").empty().html('');
    $("#budgetTargetMonthTen").val('');
    $("#budgetTargetMonthTenHelpMe").empty().html('');
    $("#budgetActualMonthTen").val('');
    $("#budgetActualMonthTenHelpMe").empty().html('');
    $("#budgetTargetMonthEleven").val('');
    $("#budgetTargetMonthElevenHelpMe").empty().html('');
    $("#budgetActualMonthEleven").val('');
    $("#budgetActualMonthElevenHelpMe").empty().html('');
    $("#budgetTargetMonthTwelve").val('');
    $("#budgetTargetMonthTwelveHelpMe").empty().html('');
    $("#budgetActualMonthTwelve").val('');
    $("#budgetActualMonthTwelveHelpMe").empty().html('');
    $("#budgetTargetMonthThirteen").val('');
    $("#budgetTargetMonthThirteenHelpMe").empty().html('');
    $("#budgetActualMonthThirteen").val('');
    $("#budgetActualMonthThirteenHelpMe").empty().html('');
    $("#budgetTargetMonthFourteen").val('');
    $("#budgetTargetMonthFourteenHelpMe").empty().html('');
    $("#budgetActualMonthFourteen").val('');
    $("#budgetActualMonthFourteenHelpMe").empty().html('');
    $("#budgetTargetMonthFifteen").val('');
    $("#budgetTargetMonthFifteenHelpMe").empty().html('');
    $("#budgetActualMonthFifteen").val('');
    $("#budgetActualMonthFifteenHelpMe").empty().html('');
    $("#budgetTargetMonthSixteen").val('');
    $("#budgetTargetMonthSixteenHelpMe").empty().html('');
    $("#budgetActualMonthSixteen").val('');
    $("#budgetActualMonthSixteenHelpMe").empty().html('');
    $("#budgetTargetMonthSeventeen").val('');
    $("#budgetTargetMonthSeventeenHelpMe").empty().html('');
    $("#budgetActualMonthSeventeen").val('');
    $("#budgetActualMonthSeventeenHelpMe").empty().html('');
    $("#budgetTargetMonthEighteen").val('');
    $("#budgetTargetMonthEighteenHelpMe").empty().html('');
    $("#budgetActualMonthEighteen").val('');
    $("#budgetActualMonthEighteenHelpMe").empty().html('');
    $("#budgetTargetTotalYear").val('');
    $("#budgetTargetTotalYearHelpMe").empty().html('');
    $("#budgetActualTotalYear").val('');
    $("#budgetActualTotalYearHelpMe").empty().html('');
    $("#budgetVersion").val('');
    $("#budgetVersionHelpMe").empty().html('');
    $("#isLock").val('');
    $("#isLockHelpMe").empty().html('');
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
                    $.ajax({type: 'POST', url: url, data: {method: 'read', budgetId: firstRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        }, success: function(data) {
                            var success = data.success;
                            var $infoPanel = $('#infoPanel');
                            var lastRecord = data.lastRecord;
                            var nextRecord = data.nextRecord;
                            var previousRecord = data.previousRecord;
                            if (success === true) {
                                $('#budgetId').val(data.data.budgetId);
                                $('#chartOfAccountId').val(data.data.chartOfAccountId).trigger("chosen:updated");
                                $('#financeYearId').val(data.data.financeYearId).trigger("chosen:updated");
                                $('#budgetTargetMonthOne').val(data.data.budgetTargetMonthOne);
                                $('#budgetActualMonthOne').val(data.data.budgetActualMonthOne);
                                $('#budgetTargetMonthTwo').val(data.data.budgetTargetMonthTwo);
                                $('#budgetActualMonthTwo').val(data.data.budgetActualMonthTwo);
                                $('#budgetTargetMonthThree').val(data.data.budgetTargetMonthThree);
                                $('#budgetActualMonthThree').val(data.data.budgetActualMonthThree);
                                $('#budgetTargetMonthFourth').val(data.data.budgetTargetMonthFourth);
                                $('#budgetActualMonthFourth').val(data.data.budgetActualMonthFourth);
                                $('#budgetTargetMonthFifth').val(data.data.budgetTargetMonthFifth);
                                $('#budgetActualMonthFifth').val(data.data.budgetActualMonthFifth);
                                $('#budgetTargetMonthSix').val(data.data.budgetTargetMonthSix);
                                $('#budgetActualMonthSix').val(data.data.budgetActualMonthSix);
                                $('#budgetTargetMonthSeven').val(data.data.budgetTargetMonthSeven);
                                $('#budgetActualMonthSeven').val(data.data.budgetActualMonthSeven);
                                $('#budgetTargetMonthEight').val(data.data.budgetTargetMonthEight);
                                $('#budgetActualMonthEight').val(data.data.budgetActualMonthEight);
                                $('#budgetTargetMonthNine').val(data.data.budgetTargetMonthNine);
                                $('#budgetActualMonthNine').val(data.data.budgetActualMonthNine);
                                $('#budgetTargetMonthTen').val(data.data.budgetTargetMonthTen);
                                $('#budgetActualMonthTen').val(data.data.budgetActualMonthTen);
                                $('#budgetTargetMonthEleven').val(data.data.budgetTargetMonthEleven);
                                $('#budgetActualMonthEleven').val(data.data.budgetActualMonthEleven);
                                $('#budgetTargetMonthTwelve').val(data.data.budgetTargetMonthTwelve);
                                $('#budgetActualMonthTwelve').val(data.data.budgetActualMonthTwelve);
                                $('#budgetTargetMonthThirteen').val(data.data.budgetTargetMonthThirteen);
                                $('#budgetActualMonthThirteen').val(data.data.budgetActualMonthThirteen);
                                $('#budgetTargetMonthFourteen').val(data.data.budgetTargetMonthFourteen);
                                $('#budgetActualMonthFourteen').val(data.data.budgetActualMonthFourteen);
                                $('#budgetTargetMonthFifteen').val(data.data.budgetTargetMonthFifteen);
                                $('#budgetActualMonthFifteen').val(data.data.budgetActualMonthFifteen);
                                $('#budgetTargetMonthSixteen').val(data.data.budgetTargetMonthSixteen);
                                $('#budgetActualMonthSixteen').val(data.data.budgetActualMonthSixteen);
                                $('#budgetTargetMonthSeventeen').val(data.data.budgetTargetMonthSeventeen);
                                $('#budgetActualMonthSeventeen').val(data.data.budgetActualMonthSeventeen);
                                $('#budgetTargetMonthEighteen').val(data.data.budgetTargetMonthEighteen);
                                $('#budgetActualMonthEighteen').val(data.data.budgetActualMonthEighteen);
                                $('#budgetTargetTotalYear').val(data.data.budgetTargetTotalYear);
                                $('#budgetActualTotalYear').val(data.data.budgetActualTotalYear);
                                $('#budgetVersion').val(data.data.budgetVersion);
                                $('#isLock').val(data.data.isLock);
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
                            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                        }});
                } else {
                    $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRollSweat + "'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }
            }, error: function(xhr) {
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                    $.ajax({type: 'POST', url: url, data: {method: 'read', budgetId: lastRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        }, success: function(data) {
                            var success = data.success;
                            var firstRecord = data.firstRecord;
                            var lastRecord = data.lastRecord;
                            var nextRecord = data.nextRecord;
                            var previousRecord = data.previousRecord;
                            if (success === true) {
                                $('#budgetId').val(data.data.budgetId);
                                $('#chartOfAccountId').val(data.data.chartOfAccountId).trigger("chosen:updated");
                                $('#financeYearId').val(data.data.financeYearId).trigger("chosen:updated");
                                $('#budgetTargetMonthOne').val(data.data.budgetTargetMonthOne);
                                $('#budgetActualMonthOne').val(data.data.budgetActualMonthOne);
                                $('#budgetTargetMonthTwo').val(data.data.budgetTargetMonthTwo);
                                $('#budgetActualMonthTwo').val(data.data.budgetActualMonthTwo);
                                $('#budgetTargetMonthThree').val(data.data.budgetTargetMonthThree);
                                $('#budgetActualMonthThree').val(data.data.budgetActualMonthThree);
                                $('#budgetTargetMonthFourth').val(data.data.budgetTargetMonthFourth);
                                $('#budgetActualMonthFourth').val(data.data.budgetActualMonthFourth);
                                $('#budgetTargetMonthFifth').val(data.data.budgetTargetMonthFifth);
                                $('#budgetActualMonthFifth').val(data.data.budgetActualMonthFifth);
                                $('#budgetTargetMonthSix').val(data.data.budgetTargetMonthSix);
                                $('#budgetActualMonthSix').val(data.data.budgetActualMonthSix);
                                $('#budgetTargetMonthSeven').val(data.data.budgetTargetMonthSeven);
                                $('#budgetActualMonthSeven').val(data.data.budgetActualMonthSeven);
                                $('#budgetTargetMonthEight').val(data.data.budgetTargetMonthEight);
                                $('#budgetActualMonthEight').val(data.data.budgetActualMonthEight);
                                $('#budgetTargetMonthNine').val(data.data.budgetTargetMonthNine);
                                $('#budgetActualMonthNine').val(data.data.budgetActualMonthNine);
                                $('#budgetTargetMonthTen').val(data.data.budgetTargetMonthTen);
                                $('#budgetActualMonthTen').val(data.data.budgetActualMonthTen);
                                $('#budgetTargetMonthEleven').val(data.data.budgetTargetMonthEleven);
                                $('#budgetActualMonthEleven').val(data.data.budgetActualMonthEleven);
                                $('#budgetTargetMonthTwelve').val(data.data.budgetTargetMonthTwelve);
                                $('#budgetActualMonthTwelve').val(data.data.budgetActualMonthTwelve);
                                $('#budgetTargetMonthThirteen').val(data.data.budgetTargetMonthThirteen);
                                $('#budgetActualMonthThirteen').val(data.data.budgetActualMonthThirteen);
                                $('#budgetTargetMonthFourteen').val(data.data.budgetTargetMonthFourteen);
                                $('#budgetActualMonthFourteen').val(data.data.budgetActualMonthFourteen);
                                $('#budgetTargetMonthFifteen').val(data.data.budgetTargetMonthFifteen);
                                $('#budgetActualMonthFifteen').val(data.data.budgetActualMonthFifteen);
                                $('#budgetTargetMonthSixteen').val(data.data.budgetTargetMonthSixteen);
                                $('#budgetActualMonthSixteen').val(data.data.budgetActualMonthSixteen);
                                $('#budgetTargetMonthSeventeen').val(data.data.budgetTargetMonthSeventeen);
                                $('#budgetActualMonthSeventeen').val(data.data.budgetActualMonthSeventeen);
                                $('#budgetTargetMonthEighteen').val(data.data.budgetTargetMonthEighteen);
                                $('#budgetActualMonthEighteen').val(data.data.budgetActualMonthEighteen);
                                $('#budgetTargetTotalYear').val(data.data.budgetTargetTotalYear);
                                $('#budgetActualTotalYear').val(data.data.budgetActualTotalYear);
                                $('#budgetVersion').val(data.data.budgetVersion);
                                $('#isLock').val(data.data.isLock);
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
                            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                        }});
                } else {
                    $infoPanel.html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                }
                var endIcon = './images/icons/control-stop-180.png';
                $infoPanel.html('').empty().html("&nbsp;<img src='" + endIcon + "'> " + decodeURIComponent(t['endButtonLabel']) + " ");
            }, error: function(xhr) {
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
            $infoPanel.html('').empty().html("<span class='label label-danger'>" + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }
        if (parseFloat($previousRecordCounter.val()) > 0 && parseFloat($previousRecordCounter.val()) < parseFloat($('#lastRecordCounter').val())) {
            $.ajax({type: 'POST', url: url, data: {method: 'read', budgetId: $previousRecordCounter.val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }, success: function(data) {
                    var success = data.success;
                    var firstRecord = data.firstRecord;
                    var lastRecord = data.lastRecord;
                    var nextRecord = data.nextRecord;
                    var previousRecord = data.previousRecord;
                    var $infoPanel = $('#infoPanel');
                    if (success === true) {
                        $('#budgetId').val(data.data.budgetId);
                        $('#chartOfAccountId').val(data.data.chartOfAccountId).trigger("chosen:updated");
                        $('#financeYearId').val(data.data.financeYearId).trigger("chosen:updated");
                        $('#budgetTargetMonthOne').val(data.data.budgetTargetMonthOne);
                        $('#budgetActualMonthOne').val(data.data.budgetActualMonthOne);
                        $('#budgetTargetMonthTwo').val(data.data.budgetTargetMonthTwo);
                        $('#budgetActualMonthTwo').val(data.data.budgetActualMonthTwo);
                        $('#budgetTargetMonthThree').val(data.data.budgetTargetMonthThree);
                        $('#budgetActualMonthThree').val(data.data.budgetActualMonthThree);
                        $('#budgetTargetMonthFourth').val(data.data.budgetTargetMonthFourth);
                        $('#budgetActualMonthFourth').val(data.data.budgetActualMonthFourth);
                        $('#budgetTargetMonthFifth').val(data.data.budgetTargetMonthFifth);
                        $('#budgetActualMonthFifth').val(data.data.budgetActualMonthFifth);
                        $('#budgetTargetMonthSix').val(data.data.budgetTargetMonthSix);
                        $('#budgetActualMonthSix').val(data.data.budgetActualMonthSix);
                        $('#budgetTargetMonthSeven').val(data.data.budgetTargetMonthSeven);
                        $('#budgetActualMonthSeven').val(data.data.budgetActualMonthSeven);
                        $('#budgetTargetMonthEight').val(data.data.budgetTargetMonthEight);
                        $('#budgetActualMonthEight').val(data.data.budgetActualMonthEight);
                        $('#budgetTargetMonthNine').val(data.data.budgetTargetMonthNine);
                        $('#budgetActualMonthNine').val(data.data.budgetActualMonthNine);
                        $('#budgetTargetMonthTen').val(data.data.budgetTargetMonthTen);
                        $('#budgetActualMonthTen').val(data.data.budgetActualMonthTen);
                        $('#budgetTargetMonthEleven').val(data.data.budgetTargetMonthEleven);
                        $('#budgetActualMonthEleven').val(data.data.budgetActualMonthEleven);
                        $('#budgetTargetMonthTwelve').val(data.data.budgetTargetMonthTwelve);
                        $('#budgetActualMonthTwelve').val(data.data.budgetActualMonthTwelve);
                        $('#budgetTargetMonthThirteen').val(data.data.budgetTargetMonthThirteen);
                        $('#budgetActualMonthThirteen').val(data.data.budgetActualMonthThirteen);
                        $('#budgetTargetMonthFourteen').val(data.data.budgetTargetMonthFourteen);
                        $('#budgetActualMonthFourteen').val(data.data.budgetActualMonthFourteen);
                        $('#budgetTargetMonthFifteen').val(data.data.budgetTargetMonthFifteen);
                        $('#budgetActualMonthFifteen').val(data.data.budgetActualMonthFifteen);
                        $('#budgetTargetMonthSixteen').val(data.data.budgetTargetMonthSixteen);
                        $('#budgetActualMonthSixteen').val(data.data.budgetActualMonthSixteen);
                        $('#budgetTargetMonthSeventeen').val(data.data.budgetTargetMonthSeventeen);
                        $('#budgetActualMonthSeventeen').val(data.data.budgetActualMonthSeventeen);
                        $('#budgetTargetMonthEighteen').val(data.data.budgetTargetMonthEighteen);
                        $('#budgetActualMonthEighteen').val(data.data.budgetActualMonthEighteen);
                        $('#budgetTargetTotalYear').val(data.data.budgetTargetTotalYear);
                        $('#budgetActualTotalYear').val(data.data.budgetActualTotalYear);
                        $('#budgetVersion').val(data.data.budgetVersion);
                        $('#isLock').val(data.data.isLock);
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
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
            $infoPanel.html('').empty().html("<span class='label label-danger'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }
        if (parseFloat($nextRecordCounter.val()) <= parseFloat($('#lastRecordCounter').val())) {
            $.ajax({type: 'POST', url: url, data: {method: 'read', budgetId: $nextRecordCounter.val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                    var lastRecord = data.lastRecord;
                    var nextRecord = data.nextRecord;
                    var previousRecord = data.previousRecord;
                    if (success === true) {
                        $('#budgetId').val(data.data.budgetId);
                        $('#chartOfAccountId').val(data.data.chartOfAccountId).trigger("chosen:updated");
                        $('#financeYearId').val(data.data.financeYearId).trigger("chosen:updated");
                        $('#budgetTargetMonthOne').val(data.data.budgetTargetMonthOne);
                        $('#budgetActualMonthOne').val(data.data.budgetActualMonthOne);
                        $('#budgetTargetMonthTwo').val(data.data.budgetTargetMonthTwo);
                        $('#budgetActualMonthTwo').val(data.data.budgetActualMonthTwo);
                        $('#budgetTargetMonthThree').val(data.data.budgetTargetMonthThree);
                        $('#budgetActualMonthThree').val(data.data.budgetActualMonthThree);
                        $('#budgetTargetMonthFourth').val(data.data.budgetTargetMonthFourth);
                        $('#budgetActualMonthFourth').val(data.data.budgetActualMonthFourth);
                        $('#budgetTargetMonthFifth').val(data.data.budgetTargetMonthFifth);
                        $('#budgetActualMonthFifth').val(data.data.budgetActualMonthFifth);
                        $('#budgetTargetMonthSix').val(data.data.budgetTargetMonthSix);
                        $('#budgetActualMonthSix').val(data.data.budgetActualMonthSix);
                        $('#budgetTargetMonthSeven').val(data.data.budgetTargetMonthSeven);
                        $('#budgetActualMonthSeven').val(data.data.budgetActualMonthSeven);
                        $('#budgetTargetMonthEight').val(data.data.budgetTargetMonthEight);
                        $('#budgetActualMonthEight').val(data.data.budgetActualMonthEight);
                        $('#budgetTargetMonthNine').val(data.data.budgetTargetMonthNine);
                        $('#budgetActualMonthNine').val(data.data.budgetActualMonthNine);
                        $('#budgetTargetMonthTen').val(data.data.budgetTargetMonthTen);
                        $('#budgetActualMonthTen').val(data.data.budgetActualMonthTen);
                        $('#budgetTargetMonthEleven').val(data.data.budgetTargetMonthEleven);
                        $('#budgetActualMonthEleven').val(data.data.budgetActualMonthEleven);
                        $('#budgetTargetMonthTwelve').val(data.data.budgetTargetMonthTwelve);
                        $('#budgetActualMonthTwelve').val(data.data.budgetActualMonthTwelve);
                        $('#budgetTargetMonthThirteen').val(data.data.budgetTargetMonthThirteen);
                        $('#budgetActualMonthThirteen').val(data.data.budgetActualMonthThirteen);
                        $('#budgetTargetMonthFourteen').val(data.data.budgetTargetMonthFourteen);
                        $('#budgetActualMonthFourteen').val(data.data.budgetActualMonthFourteen);
                        $('#budgetTargetMonthFifteen').val(data.data.budgetTargetMonthFifteen);
                        $('#budgetActualMonthFifteen').val(data.data.budgetActualMonthFifteen);
                        $('#budgetTargetMonthSixteen').val(data.data.budgetTargetMonthSixteen);
                        $('#budgetActualMonthSixteen').val(data.data.budgetActualMonthSixteen);
                        $('#budgetTargetMonthSeventeen').val(data.data.budgetTargetMonthSeventeen);
                        $('#budgetActualMonthSeventeen').val(data.data.budgetActualMonthSeventeen);
                        $('#budgetTargetMonthEighteen').val(data.data.budgetTargetMonthEighteen);
                        $('#budgetActualMonthEighteen').val(data.data.budgetActualMonthEighteen);
                        $('#budgetTargetTotalYear').val(data.data.budgetTargetTotalYear);
                        $('#budgetActualTotalYear').val(data.data.budgetActualTotalYear);
                        $('#budgetVersion').val(data.data.budgetVersion);
                        $('#isLock').val(data.data.isLock);
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
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        }
    }
}
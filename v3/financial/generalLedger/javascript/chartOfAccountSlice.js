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
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function checkDuplicate(leafId, page, securityToken) {
    var $chartOfAccountSliceCode = $("#chartOfAccountSliceCode");
    if ($chartOfAccountSliceCode.val().length === 0) {
        alert(t['oddTextLabel']);
        return false;
    }
    $.ajax({type: 'GET', url: page, data: {chartOfAccountSliceCode: $chartOfAccountSliceCode.val(), method: 'duplicate', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                    $("#chartOfAccountSliceCode").val('').focus();
                    $("#chartOfAccountSliceCodeForm").removeClass().addClass("form-group has-error");
                    $infoPanel.html('').empty().html("<img src='" + smileyRoll + "'> " + t['codeDuplicateTextLabel']).delay(5000).fadeOut();
                } else {
                    $infoPanel.html('').empty().html("<img src='" + smileyLol + "'> " + t['codeAvailableTextLabel']).delay(5000).fadeOut();
                }
            } else {
                $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                $("#chartOfAccountSliceForm").removeClass().addClass("form-group has-error");
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
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function showFormUpdate(leafId, url, urlList, securityToken, chartOfAccountSliceId, updateAccess, deleteAccess) {
    sleep(500);
    $('a[rel=tooltip]').tooltip('hide');
    $.ajax({type: 'POST', url: urlList, data: {method: 'read', type: 'form', chartOfAccountSliceId: chartOfAccountSliceId, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-md-12 col-sm-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function showModalDelete(chartOfAccountSliceId, chartOfAccountId, documentNumber, chartOfAccountSliceDate, chartOfAccountSliceAmount, chartOfAccountSliceDescription, isBalance) {
    $('#chartOfAccountSliceIdPreview').val('').val(decodeURIComponent(chartOfAccountSliceId));
    $('#chartOfAccountIdPreview').val('').val(decodeURIComponent(chartOfAccountId));
    $('#documentNumberPreview').val('').val(decodeURIComponent(documentNumber));
    $('#chartOfAccountSliceDatePreview').val('').val(decodeURIComponent(chartOfAccountSliceDate));
    $('#chartOfAccountSliceAmountPreview').val('').val(decodeURIComponent(chartOfAccountSliceAmount));
    $('#chartOfAccountSliceDescriptionPreview').val('').val(decodeURIComponent(chartOfAccountSliceDescription));
    $('#isBalancePreview').val('').val(decodeURIComponent(isBalance));
    showMeModal('deletePreview', 1);
}
function deleteGridRecord(leafId, url, urlList, securityToken) {
    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', chartOfAccountSliceId: $('#chartOfAccountSliceIdPreview').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function showFormCreateDetail(leafId, url, securityToken) {
    var $infoPanel = $('#infoPanel');
    if ($('#chartOfAccountId9999').val().length === 0) {
        $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountIdLabel'] + "</span>");
        $('#chartOfAccountId9999HelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountIdLabel'] + "</span>");
        $('#chartOfAccountId9999Form').removeClass().addClass('form-group has-error');
        $('#chartOfAccountId9999').focus();
        return false;
    }
    if ($('#documentNumber9999').val().length === 0) {
        $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['documentNumberLabel'] + "</span>");
        $('#documentNumber9999HelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['documentNumberLabel'] + "</span>");
        $('#documentNumber9999Form').removeClass().addClass('form-group has-error');
        $('#documentNumber9999').focus();
        return false;
    }
    if ($('#chartOfAccountSliceDetailAmount9999').val().length === 0) {
        $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountSliceDetailAmountLabel'] + "</span>");
        $('#chartOfAccountSliceDetailAmount9999HelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountSliceDetailAmountLabel'] + "</span>");
        $('#chartOfAccountSliceDetailAmount9999Form').removeClass().addClass('form-group has-error');
        $('#chartOfAccountSliceDetailAmount9999').focus();
        return false;
    }
    $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;" + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>");
    if ($infoPanel.is(':hidden')) {
        $infoPanel.show();
    }
    $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', chartOfAccountSliceId: $('#chartOfAccountSliceId').val(), chartOfAccountId: $('#chartOfAccountId9999').val(), documentNumber: $('#documentNumber9999').val(), chartOfAccountSliceDetailAmount: $('#chartOfAccountSliceDetailAmount9999').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            $('#miniInfoPanel9999').html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var success = data.success;
            var message = data.message;
            if (success === true) {
                $.ajax({type: 'POST', url: url, data: {method: 'read', output: 'table', offset: '0', limit: '9999', chartOfAccountSliceId: $('#chartOfAccountSliceId').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                        var smileyRoll = './images/icons/smiley-roll.png';
                        var $infoPanel = $('#infoPanel');
                        $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                        $('#miniInfoPanel9999').empty().html('').html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }, success: function(data) {
                        var $infoPanel = $('#infoPanel');
                        var smileyLol = './images/icons/smiley-lol.png';
                        var success = data.success;
                        if (success === true) {
                            $('#tableBody').html('').empty().html(data.tableData);
                            $("#chartOfAccountId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                            $("#chartOfAccountSliceDetailAmount9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                            $(".chzn-select").chosen();
                            $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        }
                    }, error: function(xhr) {
                        var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                        $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                        $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                    }});
                $('#miniInfoPanel9999').html("<span class='label label-success'>&nbsp;<a class='close' data-dismiss='alert' href='#'>&times;</a><img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>").delay(1000).fadeOut();
            } else if (success === false) {
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + message + "</span>");
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function showFormUpdateDetail(leafId, url, securityToken, chartOfAccountSliceDetailId) {
    var $infoPanel = $('#infoPanel');
    if ($('#chartOfAccountId' + chartOfAccountSliceDetailId).val().length === 0) {
        $infoPanel.html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['chartOfAccountIdLabel'] + "</span>");
        $('#chartOfAccountId' + chartOfAccountSliceDetailId + 'HelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['chartOfAccountIdLabel'] + "</span>");
        $('#chartOfAccountId' + chartOfAccountSliceDetailId).removeClass().addClass('form-group has-error');
        $('#chartOfAccountId' + chartOfAccountSliceDetailId).focus();
        return false;
    }
    if ($('#documentNumber' + chartOfAccountSliceDetailId).val().length === 0) {
        $infoPanel.html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['documentNumberLabel'] + "</span>");
        $('#documentNumber' + chartOfAccountSliceDetailId + 'HelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['documentNumberLabel'] + "</span>");
        $('#documentNumber' + chartOfAccountSliceDetailId).removeClass().addClass('form-group has-error');
        $('#documentNumber' + chartOfAccountSliceDetailId).focus();
        return false;
    }
    if ($('#journalNumber' + chartOfAccountSliceDetailId).val().length === 0) {
        $infoPanel.html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['journalNumberLabel'] + "</span>");
        $('#journalNumber' + chartOfAccountSliceDetailId + 'HelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['journalNumberLabel'] + "</span>");
        $('#journalNumber' + chartOfAccountSliceDetailId).removeClass().addClass('form-group has-error');
        $('#journalNumber' + chartOfAccountSliceDetailId).focus();
        return false;
    }
    if ($('#chartOfAccountSliceDetailAmount' + chartOfAccountSliceDetailId).val().length === 0) {
        $infoPanel.html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['chartOfAccountSliceDetailAmountLabel'] + "</span>");
        $('#chartOfAccountSliceDetailAmount' + chartOfAccountSliceDetailId + 'HelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['chartOfAccountSliceDetailAmountLabel'] + "</span>");
        $('#chartOfAccountSliceDetailAmount' + chartOfAccountSliceDetailId).removeClass().addClass('form-group has-error');
        $('#chartOfAccountSliceDetailAmount' + chartOfAccountSliceDetailId).focus();
        return false;
    }
    $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', chartOfAccountSliceDetailId: $('#chartOfAccountSliceDetailId' + chartOfAccountSliceDetailId).val(), chartOfAccountSliceId: $('#chartOfAccountSliceId').val(), chartOfAccountId: $('#chartOfAccountId' + chartOfAccountSliceDetailId).val(), documentNumber: $('#documentNumber' + chartOfAccountSliceDetailId).val(), journalNumber: $('#journalNumber' + chartOfAccountSliceDetailId).val(), chartOfAccountSliceDetailAmount: $('#chartOfAccountSliceDetailAmount' + chartOfAccountSliceDetailId).val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            $('#miniInfoPanel' + chartOfAccountSliceDetailId).html('').empty().html("<span class='label label-warning'> <img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var $infoPanel = $('#infoPanel');
            var $miniInfoPanel = $('#miniInfoPanel' + chartOfAccountSliceDetailId);
            var smileyLol = './images/icons/smiley-lol.png';
            var success = data.success;
            var message = data.message;
            if (success === true) {
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['updateRecordTextLabel']) + "</span>");
                $miniInfoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'><a class='close' data-dismiss='alert' href='#'>&times;</a></span>");
            } else if (success === false) {
                $infoPanel.html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                $miniInfoPanel.html("<span class='label label-danger'>&nbsp; " + message + "</span>");
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
function showModalDeleteDetail(chartOfAccountSliceDetailId) {
    $('#chartOfAccountSliceDetailIdPreview').val('').val(decodeURIComponent($("#chartOfAccountSliceDetailId" + chartOfAccountSliceDetailId).val()));
    $('#chartOfAccountIdPreview').val('').val(decodeURIComponent($("#chartOfAccountId" + chartOfAccountSliceDetailId + " option:selected").text()));
    $('#documentNumberPreview').val('').val(decodeURIComponent($("#documentNumber" + chartOfAccountSliceDetailId).val()));
    $('#journalNumberPreview').val('').val(decodeURIComponent($("#journalNumber" + chartOfAccountSliceDetailId).val()));
    $('#chartOfAccountSliceDetailAmountPreview').val('').val(decodeURIComponent($("#chartOfAccountSliceDetailAmount" + chartOfAccountSliceDetailId).val()));
    showMeModal('deleteDetailPreview', 1);
}
function deleteGridRecordDetail(leafId, url, urlList, securityToken) {
    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', chartOfAccountSliceDetailId: $('#chartOfAccountSliceDetailIdPreview').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var $infoPanel = $('#infoPanel');
            var smileyLol = './images/icons/smiley-lol.png';
            var success = data.success;
            var message = data.message;
            if (success === true) {
                showMeModal('deleteDetailPreview', 0);
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['deleteRecordTextLabel']) + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
                removeMeTr($('#chartOfAccountSliceDetailIdPreview').val())
            } else if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-danger'> " + message + "</span>");
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
    $('input:checkbox[name="chartOfAccountSliceId[]"]').each(function() {
        stringText = stringText + "&chartOfAccountSliceId[]=" + $(this).val();
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
    var $chartOfAccountId = $('#chartOfAccountId');
    var $chartOfAccountSliceDate = $('#chartOfAccountSliceDate');
    var $chartOfAccountSliceAmount = $('#chartOfAccountSliceAmount');
    var $chartOfAccountSliceDescription = $('#chartOfAccountSliceDescription');
    var $isBalance = $('#isBalance');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (type === 1) {
            if ($chartOfAccountId.val().length === 0) {
                $('#chartOfAccountIdHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountIdLabel'] + " </span>");
                $chartOfAccountId.data('chosen').activate_action();
                return false;
            }
            if ($chartOfAccountSliceDate.val().length === 0) {
                $('#chartOfAccountSliceDateHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountSliceDateLabel'] + " </span>");
                $('#chartOfAccountSliceDateForm').removeClass().addClass('form-group has-error');
                $chartOfAccountSliceDate.focus();
                return false;
            }
            if ($chartOfAccountSliceAmount.val().length === 0) {
                $('#chartOfAccountSliceAmountHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountSliceAmountLabel'] + " </span>");
                $('#chartOfAccountSliceAmountForm').removeClass().addClass('form-group has-error');
                $chartOfAccountSliceAmount.focus();
                return false;
            }
            if ($chartOfAccountSliceDescription.val().length === 0) {
                $('#chartOfAccountSliceDescriptionHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountSliceDescriptionLabel'] + " </span>");
                $('#chartOfAccountSliceDescriptionForm').removeClass().addClass('form-group has-error');
                $chartOfAccountSliceDescription.focus();
                return false;
            }
            if ($isBalance.val().length === 0) {
                $('#isBalanceHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isBalanceLabel'] + " </span>");
                $('#isBalanceForm').removeClass().addClass('form-group has-error');
                $isBalance.focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', chartOfAccountId: $chartOfAccountId.val(), chartOfAccountSliceDate: $chartOfAccountSliceDate.val(), chartOfAccountSliceAmount: $chartOfAccountSliceAmount.val(), chartOfAccountSliceDescription: $chartOfAccountSliceDescription.val(), isBalance: $isBalance.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                        $("documentNumber").val('');
                        $('#documentNumberHelpMe').html('').empty();
                        $chartOfAccountSliceDate.val('');
                        $('#chartOfAccountSliceDateHelpMe').html('').empty();
                        $chartOfAccountSliceAmount.val('');
                        $('#chartOfAccountSliceAmountHelpMe').html('').empty();
                        $chartOfAccountSliceDescription.val('');
                        $('#chartOfAccountSliceDescriptionForm').removeClass().addClass('form-group');
                        $('#chartOfAccountSliceDescriptionHelpMe').html('').empty();
                        $isBalance.val('');
                        $('#isBalanceHelpMe').html('').empty();
                    } else if (success === false) {
                        $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;" + message + "</span>");
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
            if ($chartOfAccountId.val().length === 0) {
                $('#chartOfAccountIdHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountIdLabel'] + " </span>");
                $chartOfAccountId.data('chosen').activate_action();
                return false;
            }
            if ($chartOfAccountSliceDate.val().length === 0) {
                $('#chartOfAccountSliceDateHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountSliceDateLabel'] + " </span>");
                $('#chartOfAccountSliceDateForm').removeClass().addClass('form-group has-error');
                $chartOfAccountSliceDate.focus();
                return false;
            }
            if ($chartOfAccountSliceAmount.val().length === 0) {
                $('#chartOfAccountSliceAmountHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountSliceAmountLabel'] + " </span>");
                $('#chartOfAccountSliceAmountForm').removeClass().addClass('form-group has-error');
                $chartOfAccountSliceAmount.focus();
                return false;
            }
            if ($chartOfAccountSliceDescription.val().length === 0) {
                $('#chartOfAccountSliceDescriptionHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountSliceDescriptionLabel'] + " </span>");
                $('#chartOfAccountSliceDescriptionForm').removeClass().addClass('form-group has-error');
                $chartOfAccountSliceDescription.focus();
                return false;
            }
            if ($isBalance.val().length === 0) {
                $('#isBalanceHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isBalanceLabel'] + " </span>");
                $('#isBalanceForm').removeClass().addClass('form-group has-error');
                $isBalance.focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', chartOfAccountId: $chartOfAccountId.val(), chartOfAccountSliceDate: $chartOfAccountSliceDate.val(), chartOfAccountSliceAmount: $chartOfAccountSliceAmount.val(), chartOfAccountSliceDescription: $chartOfAccountSliceDescription.val(), isBalance: $isBalance.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                        $('#chartOfAccountSliceId').val(data.chartOfAccountSliceId);
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
                        $("#chartOfAccountSliceId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                        $("#chartOfAccountId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                        $("#documentNumber9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                        $("#journalNumber9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                        $("#chartOfAccountSliceDetailAmount9999").prop("disabled", "false").removeAttr("disabled", "").val('');

                    }
                }, error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        } else if (type === 5) {
            if ($chartOfAccountId.val().length === 0) {
                $('#chartOfAccountIdHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountIdLabel'] + " </span>");
                $chartOfAccountId.data('chosen').activate_action();
                return false;
            }
            if ($chartOfAccountSliceDate.val().length === 0) {
                $('#chartOfAccountSliceDateHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountSliceDateLabel'] + " </span>");
                $('#chartOfAccountSliceDateForm').removeClass().addClass('form-group has-error');
                $chartOfAccountSliceDate.focus();
                return false;
            }
            if ($chartOfAccountSliceAmount.val().length === 0) {
                $('#chartOfAccountSliceAmountHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountSliceAmountLabel'] + " </span>");
                $('#chartOfAccountSliceAmountForm').removeClass().addClass('form-group has-error');
                $chartOfAccountSliceAmount.focus();
                return false;
            }
            if ($chartOfAccountSliceDescription.val().length === 0) {
                $('#chartOfAccountSliceDescriptionHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountSliceDescriptionLabel'] + " </span>");
                $('#chartOfAccountSliceDescriptionForm').removeClass().addClass('form-group has-error');
                $chartOfAccountSliceDescription.focus();
                return false;
            }
            if ($isBalance.val().length === 0) {
                $('#isBalanceHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isBalanceLabel'] + " </span>");
                $('#isBalanceForm').removeClass().addClass('form-group has-error');
                $isBalance.focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', chartOfAccountId: $chartOfAccountId.val(), chartOfAccountSliceDate: $chartOfAccountSliceDate.val(), chartOfAccountSliceAmount: $chartOfAccountSliceAmount.val(), chartOfAccountSliceDescription: $chartOfAccountSliceDescription.val(), isBalance: $isBalance.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
    var $chartOfAccountId = $('#chartOfAccountId');
    var $chartOfAccountSliceDate = $('#chartOfAccountSliceDate');
    var $chartOfAccountSliceAmount = $('#chartOfAccountSliceAmount');
    var $chartOfAccountSliceDescription = $('#chartOfAccountSliceDescription');
    var $isBalance = $('#isBalance');
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
            if ($chartOfAccountSliceDate.val().length === 0) {
                $('#chartOfAccountSliceDateHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountSliceDateLabel'] + " </span>");
                $('#chartOfAccountSliceDateForm').removeClass().addClass('form-group has-error');
                $chartOfAccountSliceDate.focus();
                return false;
            }
            if ($chartOfAccountSliceAmount.val().length === 0) {
                $('#chartOfAccountSliceAmountHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountSliceAmountLabel'] + " </span>");
                $('#chartOfAccountSliceAmountForm').removeClass().addClass('form-group has-error');
                $chartOfAccountSliceAmount.focus();
                return false;
            }
            if ($chartOfAccountSliceDescription.val().length === 0) {
                $('#chartOfAccountSliceDescriptionHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountSliceDescriptionLabel'] + " </span>");
                $('#chartOfAccountSliceDescriptionForm').removeClass().addClass('form-group has-error');
                $chartOfAccountSliceDescription.focus();
                return false;
            }
            if ($isBalance.val().length === 0) {
                $('#isBalanceHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isBalanceLabel'] + " </span>");
                $('#isBalanceForm').removeClass().addClass('form-group has-error');
                $isBalance.focus();
                return false;
            }
            $infoPanel.html('').empty();
            $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', chartOfAccountSliceId: $chartOfAccountSliceId.val(), chartOfAccountId: $chartOfAccountId.val(), chartOfAccountSliceDate: $chartOfAccountSliceDate.val(), chartOfAccountSliceAmount: $chartOfAccountSliceAmount.val(), chartOfAccountSliceDescription: $chartOfAccountSliceDescription.val(), isBalance: $isBalance.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        } else if (type === 3) {
            if ($chartOfAccountId.val().length === 0) {
                $('#chartOfAccountIdHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountIdLabel'] + " </span>");
                $chartOfAccountId.data('chosen').activate_action();
                return false;
            }
            if ($chartOfAccountSliceDate.val().length === 0) {
                $('#chartOfAccountSliceDateHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountSliceDateLabel'] + " </span>");
                $('#chartOfAccountSliceDateForm').removeClass().addClass('form-group has-error');
                $chartOfAccountSliceDate.focus();
                return false;
            }
            if ($chartOfAccountSliceAmount.val().length === 0) {
                $('#chartOfAccountSliceAmountHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountSliceAmountLabel'] + " </span>");
                $('#chartOfAccountSliceAmountForm').removeClass().addClass('form-group has-error');
                $chartOfAccountSliceAmount.focus();
                return false;
            }
            if ($chartOfAccountSliceDescription.val().length === 0) {
                $('#chartOfAccountSliceDescriptionHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountSliceDescriptionLabel'] + " </span>");
                $('#chartOfAccountSliceDescriptionForm').removeClass().addClass('form-group has-error');
                $chartOfAccountSliceDescription.focus();
                return false;
            }
            if ($isBalance.val().length === 0) {
                $('#isBalanceHelpMe').html('').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isBalanceLabel'] + " </span>");
                $('#isBalanceForm').removeClass().addClass('form-group has-error');
                $isBalance.focus();
                return false;
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', chartOfAccountSliceId: $chartOfAccountSliceId.val(), chartOfAccountId: $chartOfAccountId.val(), chartOfAccountSliceDate: $chartOfAccountSliceDate.val(), chartOfAccountSliceAmount: $chartOfAccountSliceAmount.val(), chartOfAccountSliceDescription: $chartOfAccountSliceDescription.val(), isBalance: $isBalance.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        }
    }
}
function deleteRecord(leafId, url, urlList, securityToken, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var $chartOfAccountSliceId = $('#chartOfAccountSliceId');
    var css = $('#deleteRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (deleteAccess === 1) {
            if (confirm(decodeURIComponent(t['deleteRecordMessageLabel']))) {
                var value = $chartOfAccountSliceId.val();
                if (!value) {
                    $infoPanel.html('').empty().html("<span class='label label-danger'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "<span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                    return false;
                } else {
                    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', chartOfAccountSliceId: $chartOfAccountSliceId.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
function resetRecord(leafId, url, urlList, urlChartOfAccountSliceDetail, securityToken, createAccess, updateAccess, deleteAccess) {
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
    $('#firstRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "firstRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlChartOfAccountSliceDetail + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
    $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
    $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
    $('#endRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "endRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlChartOfAccountSliceDetail + "','" + securityToken + "'," + updateAccess + ")");
    $("#chartOfAccountSliceId").val('');
    $("#chartOfAccountSliceIdHelpMe").empty().html('');

    $("#chartOfAccountId").val('');
    $("#chartOfAccountIdHelpMe").empty().html('');
    $('#chartOfAccountId').trigger("chosen:updated");
    $("#documentNumber").val('');
    $("#documentNumberHelpMe").empty().html('');
    $("#chartOfAccountSliceDate").val('');
    $("#chartOfAccountSliceDateHelpMe").empty().html('');
    $("#chartOfAccountSliceAmount").val('');
    $("#chartOfAccountSliceAmountHelpMe").empty().html('');
    $("#chartOfAccountSliceDescription").val('');
    $("#chartOfAccountSliceDescriptionHelpMe").empty().html('');
    $('#chartOfAccountSliceDescription').empty().val('');
    $("#isBalance").val('');
    $("#isBalanceHelpMe").empty().html('');
    $("#chartOfAccountSliceDetailId9999").prop("disabled", "true").attr("disabled", "disabled").val('');
    $("#chartOfAccountSliceId9999").prop("disabled", "true").attr("disabled", "disabled").val('').trigger("chosen:updated");
    $("#chartOfAccountId9999").prop("disabled", "true").attr("disabled", "disabled").val('').trigger("chosen:updated");
    $("#documentNumber9999").prop("disabled", "true").attr("disabled", "disabled").val('');
    $("#journalNumber9999").prop("disabled", "true").attr("disabled", "disabled").val('');
    $("#chartOfAccountSliceDetailAmount9999").prop("disabled", "true").attr("disabled", "disabled").val('');

    $("#tableBody").html('').empty();
}
function postRecord() {
    var css = $('#postRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        return false;
    }
}
function firstRecord(leafId, url, urlList, urlChartOfAccountSliceDetail, securityToken, updateAccess, deleteAccess) {
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
                    $.ajax({type: 'POST', url: url, data: {method: 'read', chartOfAccountSliceId: firstRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                                $('#chartOfAccountSliceId').val(data.data.chartOfAccountSliceId);
                                $('#chartOfAccountId').val(data.data.chartOfAccountId).trigger("chosen:updated");
                                $('#documentNumber').val(data.data.documentNumber);
                                x = data.data.chartOfAccountSliceDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#chartOfAccountSliceDate').val(output);
                                $('#chartOfAccountSliceAmount').val(data.data.chartOfAccountSliceAmount);
                                $('#chartOfAccountSliceDescription').val(data.data.chartOfAccountSliceDescription);
                                $('#isBalance').val(data.data.isBalance);
                                $("#chartOfAccountSliceId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                                $("#chartOfAccountId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                                $("#documentNumber9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                                $("#chartOfAccountSliceDetailAmount9999").prop("disabled", "false").removeAttr("disabled", "").val('');

                                $.ajax({type: 'POST', url: urlChartOfAccountSliceDetail, data: {method: 'read', chartOfAccountSliceId: data.firstRecord, output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                                        var smileyRoll = './images/icons/smiley-roll.png';
                                        var $infoPanel = $('#infoPanel');
                                        $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                        if ($infoPanel.is(':hidden')) {
                                            $infoPanel.show();
                                        }
                                    }, success: function(data) {
                                        var $infoPanel = $('#infoPanel');
                                        var smileyLol = './images/icons/smiley-lol.png';
                                        var success = data.success;
                                        var tableData = data.tableData;
                                        if (success === true) {
                                            $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                            $('#tableBody').html('').empty().html(tableData);
                                            $(".chzn-select").chosen({search_contains: true});
                                            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                                        }
                                    }, error: function(xhr) {
                                        var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                        $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                        $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                                    }});
                                if (nextRecord > 0) {
                                    $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                                    $('#nextRecordButton').removeClass().addClass('btn btn-default').attr('onClick', '').attr('onClick', "nextRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlChartOfAccountSliceDetail + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
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
                    $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRollSweat + "'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
function endRecord(leafId, url, urlList, urlChartOfAccountSliceDetail, securityToken, updateAccess, deleteAccess) {
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
                var smileyRoll = './images/icons/smiley-roll.png';
                var success = data.success;
                var message = data.message;
                var lastRecord = data.lastRecord;
                if (lastRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (success === true) {
                    $.ajax({type: 'POST', url: url, data: {method: 'read', chartOfAccountSliceId: lastRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                                $('#chartOfAccountSliceId').val(data.data.chartOfAccountSliceId);
                                $('#chartOfAccountId').val(data.data.chartOfAccountId).trigger("chosen:updated");
                                $('#documentNumber').val(data.data.documentNumber);
                                x = data.data.chartOfAccountSliceDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#chartOfAccountSliceDate').val(output);
                                $('#chartOfAccountSliceAmount').val(data.data.chartOfAccountSliceAmount);
                                $('#chartOfAccountSliceDescription').val(data.data.chartOfAccountSliceDescription);
                                ;
                                $('#isBalance').val(data.data.isBalance);
                                $("#chartOfAccountSliceId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                                $("#chartOfAccountId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                                $("#documentNumber9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                                $("#chartOfAccountSliceDetailAmount9999").prop("disabled", "false").removeAttr("disabled", "").val('');

                                $.ajax({type: 'POST', url: urlChartOfAccountSliceDetail, data: {method: 'read', chartOfAccountSliceId: lastRecord, output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                                        var smileyRoll = './images/icons/smiley-roll.png';
                                        var $infoPanel = $('#infoPanel');
                                        $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                        if ($infoPanel.is(':hidden')) {
                                            $infoPanel.show();
                                        }
                                    }, success: function(data) {
                                        var $infoPanel = $('#infoPanel');
                                        var success = data.success;
                                        var tableData = data.tableData;
                                        var smileyLol = './images/icons/smiley-lol.png';
                                        if (success === true) {
                                            $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                            $('#tableBody').html('').empty().html(tableData);
                                            $(".chzn-select").chosen({search_contains: true});
                                            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                                        }
                                    }, error: function(xhr) {
                                        var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                        $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                        $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                                    }});
                                if (lastRecord !== 0) {
                                    $('#previousRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "previousRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlChartOfAccountSliceDetail + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
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
                    $infoPanel.html("<span class='label label-danger'>&nbsp;" + message + "</span>");
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
function previousRecord(leafId, url, urlList, urlChartOfAccountSliceDetail, securityToken, updateAccess, deleteAccess) {
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
            $.ajax({type: 'POST', url: url, data: {method: 'read', chartOfAccountSliceId: $previousRecordCounter.val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                        $('#chartOfAccountSliceId').val(data.data.chartOfAccountSliceId);
                        $('#chartOfAccountId').val(data.data.chartOfAccountId).trigger("chosen:updated");
                        $('#documentNumber').val(data.data.documentNumber);
                        x = data.data.chartOfAccountSliceDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#chartOfAccountSliceDate').val(output);
                        $('#chartOfAccountSliceAmount').val(data.data.chartOfAccountSliceAmount);
                        $('#chartOfAccountSliceDescription').val(data.data.chartOfAccountSliceDescription);
                        ;
                        $('#isBalance').val(data.data.isBalance);
                        $("#chartOfAccountSliceId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                        $("#chartOfAccountId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                        $("#documentNumber9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                        $("#chartOfAccountSliceDetailAmount9999").prop("disabled", "false").removeAttr("disabled", "").val('');

                        $.ajax({type: 'POST', url: urlChartOfAccountSliceDetail, data: {method: 'read', chartOfAccountSliceId: $('#previousRecordCounter').val(), output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                                var smileyRoll = './images/icons/smiley-roll.png';
                                var $infoPanel = $('#infoPanel');
                                $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                if ($infoPanel.is(':hidden')) {
                                    $infoPanel.show();
                                }
                            }, success: function(data) {
                                var $infoPanel = $('#infoPanel');
                                var success = data.success;
                                var tableData = data.tableData;
                                var smileyLol = './images/icons/smiley-lol.png';
                                if (success === true) {
                                    $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                    $('#tableBody').html('').empty().html(tableData);
                                    $(".chzn-select").chosen({search_contains: true});
                                    $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                                }
                            }, error: function(xhr) {
                                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                            }});
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
                        $('#nextRecordButton').removeClass().addClass('btn btn-default').attr('onClick', '').attr('onClick', "nextRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlChartOfAccountSliceDetail + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
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
function nextRecord(leafId, url, urlList, urlChartOfAccountSliceDetail, securityToken, updateAccess, deleteAccess) {
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
            $.ajax({type: 'POST', url: url, data: {method: 'read', chartOfAccountSliceId: $nextRecordCounter.val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                        $('#chartOfAccountSliceId').val(data.data.chartOfAccountSliceId);
                        $('#chartOfAccountId').val(data.data.chartOfAccountId).trigger("chosen:updated");
                        $('#documentNumber').val(data.data.documentNumber);
                        x = data.data.chartOfAccountSliceDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#chartOfAccountSliceDate').val(output);
                        $('#chartOfAccountSliceAmount').val(data.data.chartOfAccountSliceAmount);
                        $('#chartOfAccountSliceDescription').val(data.data.chartOfAccountSliceDescription);
                        $('#isBalance').val(data.data.isBalance);
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
                        $("#chartOfAccountSliceId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                        $("#chartOfAccountId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                        $("#documentNumber9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                        $("#chartOfAccountSliceDetailAmount9999").prop("disabled", "false").removeAttr("disabled", "").val('');

                        $.ajax({type: 'POST', url: urlChartOfAccountSliceDetail, data: {method: 'read', chartOfAccountSliceId: $('#nextRecordCounter').val(), output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                                var smileyRoll = './images/icons/smiley-roll.png';
                                var $infoPanel = $('#infoPanel');
                                $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                if ($infoPanel.is(':hidden')) {
                                    $infoPanel.show();
                                }
                            }, success: function(data) {
                                var $infoPanel = $('#infoPanel');
                                var success = data.success;
                                var tableData = data.tableData;
                                var smileyLol = './images/icons/smiley-lol.png';
                                if (success === true) {
                                    $('#tableBody').html('').empty().html(tableData);
                                    $(".chzn-select").chosen({search_contains: true});
                                    $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                                    $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                    if ($infoPanel.is(':hidden')) {
                                        $infoPanel.show();
                                    }
                                }
                            }, error: function(xhr) {
                                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                            }});
                        $('#firstRecordCounter').val(firstRecord);
                        $('#previousRecordCounter').val(previousRecord);
                        $('#nextRecordCounter').val(nextRecord);
                        $('#lastRecordCounter').val(lastRecord);
                        if (parseFloat(previousRecord) > 0) {
                            $('#previousRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "previousRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlChartOfAccountSliceDetail + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
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
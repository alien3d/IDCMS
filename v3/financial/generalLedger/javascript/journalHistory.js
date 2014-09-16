function showGrid(leafId, page, securityToken, offset, limit, type) {
    $.ajax({type: 'POST', url: page, data: {offset: offset, limit: limit, method: 'read', from: 'journalHistory.php', type: 'list', detail: 'body', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
    $('#clearSearch').removeClass();
    $('#clearSearch').addClass('btn');
    var queryGrid = $('#query').val();
    var queryWidget = $('#queryWidget').val();
    var queryText;
    if (queryGrid != undefined) {
        if (queryGrid.length > 0) {
            queryText = queryGrid;
        } else {
            queryText = queryWidget;
        }
    } else {
        queryText = queryWidget;
    }
    $.ajax({type: 'POST', url: url, data: {offset: 0, limit: 99999, method: 'read', from: 'journalHistory.php', type: 'list', detail: 'body', query: queryText, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
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
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function ajaxQuerySearchAllCharacter(leafId, url, securityToken, character) {
    $('#clearSearch').removeClass();
    $('#clearSearch').addClass('btn');
    $.ajax({type: 'POST', url: url, data: {offset: 0, limit: 99999, method: 'read', from: 'journalHistory.php', type: 'list', detail: 'body', securityToken: securityToken, leafId: leafId, character: character}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
            } else {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("&nbsp;<img src='./images/icons/magnifier-zoom-actual-equal.png'> <b>" + decodeURIComponent(t['filterTextLabel']) + "</b>: " + character + " ");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
    if (dateRangeStart.length == 0) {
        dateRangeStart = $('#dateRangeStart').val();
    }
    if (dateRangeEnd.length == 0) {
        dateRangeEnd = $('#dateRangeEnd').val();
    }
    $.ajax({type: 'POST', url: url, data: {offset: 0, limit: 99999, method: 'read', from: 'journalHistory.php', type: 'list', detail: 'body', query: $('#query').val(), securityToken: securityToken, leafId: leafId, dateRangeStart: dateRangeStart, dateRangeEnd: dateRangeEnd, dateRangeType: dateRangeType}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + data.message + "</span>");
            } else {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').append(data);
                $('#infoPanel').empty();
                if (dateRangeType == 'day') {
                    calendarPng = 'calendar-select-days.png';
                } else if (dateRangeType == 'week' || dateRangeType == 'between') {
                    calendarPng = 'calendar-select-week.png';
                } else if (dateRangeType == 'month') {
                    calendarPng = 'calendar-select-month.png';
                } else if (dateRangeType == 'year') {
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
                        if (dateRangeEnd.length == 0) {
                            strDate = "<b>" + t['dayTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear();
                        } else {
                            strDate = "<b>" + t['betweenTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear() + "&nbsp;<img src='./images/icons/arrow-curve-000-left.png'>&nbsp;" + dateEnd.getDayName() + ", " + dateEnd.getMonthName() + ", " + dateEnd.getDate() + ", " + dateEnd.getFullYear();
                        }
                        break;
                    case'between':
                        if (dateRangeEnd.length == 0) {
                            strDate = "<b>" + t['dayTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ', ' + dateStart.getFullYear();
                        } else {
                            strDate = "<b>" + t['betweenTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear() + "&nbsp;<img src='./images/icons/arrow-curve-000-left.png'>&nbsp;" + dateEnd.getDayName() + ", " + dateEnd.getMonthName() + ", " + dateEnd.getDate() + ", " + dateEnd.getFullYear();
                        }
                        break;
                }
                $('#infoPanel').empty();
                $('#infoPanel').html("<img src='./images/icons/" + calendarPng + "'> " + strDate + " ");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
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
    $.ajax({type: 'POST', url: url, data: {method: 'new', from: 'journalHistory.php', type: 'form', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
            } else {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function showFormUpdate(leafId, url, urlList, securityToken, journalId) {
    sleep(500);
    $.ajax({type: 'POST', url: urlList, data: {method: 'read', type: 'form', from: 'journalHistory.php', journalId: journalId, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
            } else {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function showModalDelete(journalId, journalTypeId, documentNumber, referenceNumber, journalTitle, journalDescription, journalDate, journalAmount) {
    $('#journalIdPreview').val('');
    $('#journalIdPreview').val(decodeURIComponent(journalId));
    $('#journalTypeIdPreview').val('');
    $('#journalTypeIdPreview').val(decodeURIComponent(journalTypeId));
    $('#documentNumberPreview').val('');
    $('#documentNumberPreview').val(decodeURIComponent(documentNumber));
    $('#referenceNumberPreview').val('');
    $('#referenceNumberPreview').val(decodeURIComponent(referenceNumber));
    $('#journalTitlePreview').val('');
    $('#journalTitlePreview').val(decodeURIComponent(journalTitle));
    $('#journalDescriptionPreview').val('');
    $('#journalDescriptionPreview').val(decodeURIComponent(journalDescription));
    $('#journalDatePreview').val('');
    $('#journalDatePreview').val(decodeURIComponent(journalDate));
    $('#journalAmountPreview').val('');
    $('#journalAmountPreview').val(decodeURIComponent(journalAmount));
    showMeModal('deletePreview', 1);
}
function postGridRecordCheckbox(leafId, url, urlList, securityToken) {
    var stringText = '';
    var counter = 0;
    $('input:checkbox[name="journalId[]"]').each(function() {
        stringText = stringText + "&journalId[]=" + $(this).val();
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
    if (counter == 0) {
        alert(decodeURIComponent(t['deleteCheckboxTextLabel']));
        return false;
    } else {
        url = url + "?" + stringText;
    }
    $.ajax({type: 'GET', url: url, data: {method: 'updateStatus', from: 'journalHistory.php', output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            if (data.success == true) {
                showGrid(leafId, urlList, securityToken, 0, 10, 2);
            } else if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function reportRequest(leafId, url, securityToken, mode) {
    $.ajax({type: 'GET', url: url, data: {method: 'report', from: 'journalHistory.php', mode: mode, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            if (data.success == true) {
                var path = "./v3/financial/generalLedger/document/" + data.folder + "/" + data.filename;
                $('#infoPanel').html("<span class='label label-success'>" + decodeURIComponent(t['requestFileTextLabel']) + "</span>");
                window.open(path);
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function resetRecord(leafId, url, urlList, urlJournalDetail, securityToken) {
    $('#infoPanel').empty();
    $('#infoPanel').html('');
    $('#infoPanel').html("<span class='label label-danger'><img src='./images/icons/fruit-orange.png'> " + decodeURIComponent(t['resetRecordTextLabel']) + "</span>").delay(1000).fadeOut();
    if ($('#infoPanel').is(':hidden')) {
        $('#infoPanel').show();
    }
    $('#postRecordButton').removeClass();
    $('#postRecordButton').addClass('btn btn-info');
    $('#postRecordButton').attr('onClick', '');
    $('#firstRecordButton').removeClass();
    $('#firstRecordButton').addClass('btn btn-default');
    $('#firstRecordButton').attr('onClick', "firstRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlJournalDetail + "\",\"" + securityToken + "\")");
    $('#previousRecordButton').removeClass();
    $('#previousRecordButton').addClass('btn btn-default disabled');
    $('#previousRecordButton').attr('onClick', '');
    $('#nextRecordButton').removeClass();
    $('#nextRecordButton').addClass('btn btn-default disabled');
    $('#nextRecordButton').attr('onClick', '');
    $('#endRecordButton').removeClass();
    $('#endRecordButton').addClass('btn btn-default');
    $('#endRecordButton').attr('onClick', "endRecord\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlJournalDetail + "\",,\"" + securityToken + "\")");
    $("#journalId").val('');

    $("#journalTypeId").val('');
    $('#journalTypeId').trigger("chosen:updated");
    $("#documentNumber").val('');
    $("#referenceNumber").val('');
    $("#journalTitle").val('');
    $("#journalDescription").val('');
    $('#journalDescription').empty();
    $('#journalDescription').val('');
    $("#journalDate").val('');
    $("#journalAmount").val('');
    $("#tableBody").empty();
    $("#tableBody").html('');
}
function postRecord(leafId, url, urlList, urlJournalDetail, SecurityToken) {
    $.ajax({type: 'POST', url: url, data: {method: 'posting', from: 'journalHistory.php', journalId: $('#journalId').val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            if (data.success == true) {
            }
            else {
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function postRecord(leafId, url, urlList, urlJournalDetail, SecurityToken) {
    $.ajax({type: 'POST', url: url, data: {method: 'posting', from: 'journalHistory.php', journalId: $('#journalId').val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            if (data.success == true) {
            }
            else {
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function firstRecord(leafId, url, urlList, urlJournalDetail, securityToken) {
    var css = $('#firstRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $.ajax({type: 'GET', url: url, data: {method: 'dataNavigationRequest', from: 'journalHistory.php', dataNavigation: 'firstRecord', output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                var smileyRoll = './images/icons/smiley-roll.png';
                var $infoPanel = $('#infoPanel');
                $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }, success: function(data) {
                var smileyRoll = './images/icons/smiley-roll.png';
                if (data.firstRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (data.success == true) {
                    $.ajax({type: 'POST', url: url, data: {method: 'read', from: 'journalHistory.php', journalId: data.firstRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            var $infoPanel = $('#infoPanel');
                            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        }, success: function(data) {
                            if (data.success == true) {
                                $('#journalId').val(data.data.journalId);
                                $('#journalTypeId').val(data.data.journalTypeId);
                                $('#journalTypeId').trigger("chosen:updated");
                                $('#documentNumber').val(data.data.documentNumber);
                                $('#referenceNumber').val(data.data.referenceNumber);
                                $('#journalTitle').val(data.data.journalTitle);
                                $('#journalDescription').val(data.data.journalDescription);
                                var x = data.data.journalDate;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#journalDate').val(output);
                                $('#journalAmount').val(data.data.journalAmount);
                                $.ajax({type: 'POST', url: urlJournalDetail, data: {method: 'read', journalId: data.firstRecord, output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                                        var smileyRoll = './images/icons/smiley-roll.png';
                                        var $infoPanel = $('#infoPanel');
                                        $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                        if ($infoPanel.is(':hidden')) {
                                            $infoPanel.show();
                                        }
                                    }, success: function(data) {
                                        if (data.success == true) {
                                            $('#infoPanel').empty();
                                            $('#infoPanel').html('');
                                            $('#tableBody').empty();
                                            $('#tableBody').html('');
                                            $('#tableBody').html(data.tableData);
                                            $(".chzn-select").chosen({search_contains: true});
                                            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                                        }
                                    }, error: function(xhr) {
                                        var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                        $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                        $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                                    }});
                                if (data.nextRecord > 0) {
                                    $('#previousRecordButton').removeClass();
                                    $('#previousRecordButton').addClass('btn btn-default  disabled');
                                    $('#previousRecordButton').attr('onClick', '');
                                    $('#nextRecordButton').removeClass();
                                    $('#nextRecordButton').addClass('btn btn-default');
                                    $('#nextRecordButton').attr('onClick', '');
                                    $('#nextRecordButton').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlJournalDetail + "\",\"" + securityToken + "\")");
                                    $('#firstRecordCounter').val(data.firstRecord);
                                    $('#previousRecordCounter').val(data.previousRecord);
                                    $('#nextRecordCounter').val(data.nextRecord);
                                    $('#lastRecordCounter').val(data.lastRecord);
                                }
                                $('#infoPanel').empty();
                                $('#infoPanel').html("&nbsp;<img src='./images/icons/control-stop.png'> " + decodeURIComponent(t['firstButtonLabel']) + " ");
                                if ($('#infoPanel').is(':hidden')) {
                                    $('#infoPanel').show();
                                }
                            }
                        }, error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                        }});
                } else {
                    $('#infoPanel').empty();
                    $('#infoPanel').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll-sweat.png'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }
            }, error: function(xhr) {
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
            }});
    }
}
function endRecord(leafId, url, urlList, urlJournalDetail, securityToken) {
    var css = $('#endRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $.ajax({type: 'GET', url: url, data: {from: 'journalHistory.php', method: 'dataNavigationRequest', dataNavigation: 'lastRecord', output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                var smileyRoll = './images/icons/smiley-roll.png';
                var $infoPanel = $('#infoPanel');
                $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }, success: function(data) {
                var smileyRoll = './images/icons/smiley-roll.png';
                if (data.lastRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (data.success == true) {
                    $.ajax({type: 'POST', url: url, data: {method: 'read', from: 'journalHistory.php', journalId: data.lastRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            var $infoPanel = $('#infoPanel');
                            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        }, success: function(data) {
                            if (data.success == true) {
                                $('#journalId').val(data.data.journalId);
                                $('#journalTypeId').val(data.data.journalTypeId);
                                $('#journalTypeId').trigger("chosen:updated");
                                $('#documentNumber').val(data.data.documentNumber);
                                $('#referenceNumber').val(data.data.referenceNumber);
                                $('#journalTitle').val(data.data.journalTitle);
                                $('#journalDescription').val(data.data.journalDescription);
                                var x = data.data.journalDate;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#journalDate').val(output);
                                $('#journalAmount').val(data.data.journalAmount);
                                $.ajax({type: 'POST', url: urlJournalDetail, data: {method: 'read', journalId: data.lastRecord, output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                                        var smileyRoll = './images/icons/smiley-roll.png';
                                        var $infoPanel = $('#infoPanel');
                                        $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                                        if ($infoPanel.is(':hidden')) {
                                            $infoPanel.show();
                                        }
                                    }, success: function(data) {
                                        if (data.success == true) {
                                            $('#infoPanel').empty();
                                            $('#infoPanel').html('');
                                            $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                            $('#tableBody').empty();
                                            $('#tableBody').html('');
                                            $('#tableBody').html(data.tableData);
                                            $(".chzn-select").chosen({search_contains: true});
                                            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                                        }
                                    }, error: function(xhr) {
                                        var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                        $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                        $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                                    }});
                                if (data.lastRecord != 0) {
                                    $('#previousRecordButton').removeClass();
                                    $('#previousRecordButton').addClass('btn btn-default');
                                    $('#previousRecordButton').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlJournalDetail + "\",\"" + securityToken + "\")");
                                    $('#nextRecordButton').removeClass();
                                    $('#nextRecordButton').addClass('btn btn-default disabled');
                                    $('#nextRecordButton').attr('onClick', '');
                                    $('#firstRecordCounter').val(data.firstRecord);
                                    $('#previousRecordCounter').val(data.previousRecord);
                                    $('#nextRecordCounter').val(data.nextRecord);
                                    $('#lastRecordCounter').val(data.lastRecord);
                                }
                            }
                        }, error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                        }});
                } else {
                    $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                }
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("&nbsp;<img src='./images/icons/control-stop-180.png'> " + decodeURIComponent(t['endButtonLabel']) + " ");
            }, error: function(xhr) {
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
            }});
    }
}
function previousRecord(leafId, url, urlList, urlJournalDetail, securityToken) {
    var css = $('#previousRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($('#previousRecordCounter').val() == '' || $('#previousRecordCounter').val() == undefined) {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-danger'>" + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }
        if (parseFloat($('#previousRecordCounter').val()) > 0 && parseFloat($('#previousRecordCounter').val()) < parseFloat($('#lastRecordCounter').val())) {
            $.ajax({type: 'POST', url: url, data: {method: 'read', from: 'journalHistory.php', journalId: $('#previousRecordCounter').val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }, success: function(data) {
                    if (data.success == true) {
                        $('#journalId').val(data.data.journalId);
                        $('#journalTypeId').val(data.data.journalTypeId);
                        $('#journalTypeId').trigger("chosen:updated");
                        $('#documentNumber').val(data.data.documentNumber);
                        $('#referenceNumber').val(data.data.referenceNumber);
                        $('#journalTitle').val(data.data.journalTitle);
                        $('#journalDescription').val(data.data.journalDescription);
                        var x = data.data.journalDate;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#journalDate').val(output);
                        $('#journalAmount').val(data.data.journalAmount);
                        $.ajax({type: 'POST', url: urlJournalDetail, data: {method: 'read', from: 'journalHistory.php', journalId: $('#previousRecordCounter').val(), output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                                var smileyRoll = './images/icons/smiley-roll.png';
                                var $infoPanel = $('#infoPanel');
                                $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                                if ($infoPanel.is(':hidden')) {
                                    $infoPanel.show();
                                }
                            }, success: function(data) {
                                if (data.success == true) {
                                    $('#infoPanel').empty();
                                    $('#infoPanel').html('');
                                    $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                    $('#tableBody').empty();
                                    $('#tableBody').html('');
                                    $('#tableBody').html(data.tableData);
                                    $(".chzn-select").chosen({search_contains: true});
                                    $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                                }
                            }, error: function(xhr) {
                                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                            }});
                        $('#firstRecordCounter').val(data.firstRecord);
                        $('#previousRecordCounter').val(data.previousRecord);
                        $('#nextRecordCounter').val(data.nextRecord);
                        $('#lastRecordCounter').val(data.lastRecord);
                        if (parseFloat(data.nextRecord) <= parseFloat(data.lastRecord)) {
                            $('#nextRecordButton').removeClass();
                            $('#nextRecordButton').addClass('btn btn-default');
                            $('#nextRecordButton').attr('onClick', '');
                            $('#nextRecordButton').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlJournalDetail + "\",\"" + securityToken + "\")");
                        } else {
                            $('#nextRecordButton').removeClass();
                            $('#nextRecordButton').addClass('btn btn-default disabled');
                            $('#nextRecordButton').attr('onClick', '');
                        }
                        if (parseFloat(data.previousRecord) == 0) {
                            $('#infoPanel').empty();
                            $('#infoPanel').html("&nbsp;<img src='./images/icons/exclamation.png'> " + decodeURIComponent(t['firstButtonLabel']) + " ");
                            $('#previousRecordButton').removeClass();
                            $('#previousRecordButton').addClass('btn btn-default disabled');
                            $('#previousRecordButton').attr('onClick', '');
                        } else {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html("&nbsp;<img src='./images/icons/control-180.png'> " + decodeURIComponent(t['previousButtonLabel']) + " ");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        }
                    }
                }, error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        } else {
        }
    }
}
function nextRecord(leafId, url, urlList, urlJournalDetail, securityToken) {
    var css = $('#nextRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($('#nextRecordCounter').val() == '' || $('#nextRecordCounter').val() == undefined) {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-danger'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }
        if (parseFloat($('#nextRecordCounter').val()) <= parseFloat($('#lastRecordCounter').val())) {
            $.ajax({type: 'POST', url: url, data: {method: 'read', from: 'journalHistory.php', journalId: $('#nextRecordCounter').val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }, success: function(data) {
                    if (data.success == true) {
                        $('#journalId').val(data.data.journalId);
                        $('#journalTypeId').val(data.data.journalTypeId);
                        $('#journalTypeId').trigger("chosen:updated");
                        $('#documentNumber').val(data.data.documentNumber);
                        $('#referenceNumber').val(data.data.referenceNumber);
                        $('#journalTitle').val(data.data.journalTitle);
                        $('#journalDescription').val(data.data.journalDescription);
                        var x = data.data.journalDate;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#journalDate').val(output);
                        $('#journalAmount').val(data.data.journalAmount);
                        $.ajax({type: 'POST', url: urlJournalDetail, data: {method: 'read', from: 'journalHistory.php', journalId: $('#nextRecordCounter').val(), output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                                var smileyRoll = './images/icons/smiley-roll.png';
                                var $infoPanel = $('#infoPanel');
                                $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                                if ($infoPanel.is(':hidden')) {
                                    $infoPanel.show();
                                }
                            }, success: function(data) {
                                if (data.success == true) {
                                    $('#tableBody').empty();
                                    $('#tableBody').html('');
                                    $('#tableBody').html(data.tableData);
                                    $(".chzn-select").chosen({search_contains: true});
                                    $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                                    $('#infoPanel').empty();
                                    $('#infoPanel').html('');
                                    $('#infoPanel').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                    if ($('#infoPanel').is(':hidden')) {
                                        $('#infoPanel').show();
                                    }
                                }
                            }, error: function(xhr) {
                                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                            }});
                        $('#firstRecordCounter').val(data.firstRecord);
                        $('#previousRecordCounter').val(data.previousRecord);
                        $('#nextRecordCounter').val(data.nextRecord);
                        $('#lastRecordCounter').val(data.lastRecord);
                        if (parseFloat(data.previousRecord) > 0) {
                            $('#previousRecordButton').removeClass();
                            $('#previousRecordButton').addClass('btn btn-default');
                            $('#previousRecordButton').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlJournalDetail + "\",\"" + securityToken + "\")");
                        } else {
                            $('#previousRecordButton').removeClass();
                            $('#previousRecordButton').addClass('btn btn-default disabled');
                            $('#previousRecordButton').attr('onClick', '');
                        }
                        if (parseFloat(data.nextRecord) == 0) {
                            $('#nextRecordButton').removeClass();
                            $('#nextRecordButton').addClass('btn btn-default disabled');
                            $('#nextRecordButton').attr('onClick', '');
                            $('#infoPanel').html("&nbsp;<img src='./images/icons/exclamation.png'> " + decodeURIComponent(t['endButtonLabel']) + " ");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        } else {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html("&nbsp;<img src='./images/icons/control.png'> " + decodeURIComponent(t['nextButtonLabel']) + " ");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        }
                    }
                }, error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        } else {
        }
    }
}
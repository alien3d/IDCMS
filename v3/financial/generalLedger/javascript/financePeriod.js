function showGrid(leafId, page, securityToken, offset, limit, type) {
    $.ajax({type: 'POST', url: page, data: {offset: offset, limit: limit, method: 'read', type: 'list', detail: 'body', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '....</div>');
        }, success: function(data) {
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').html('<div class=\'alert alert-error  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + data.message + '</div>');
            } else {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'> <img src=\'./images/icons/smiley-lol.png\'> ' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
            }
        }, error: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
        }});
}
function ajaxQuerySearchAll(leafId, url, securityToken) {
    $('#clearSearch').removeClass();
    $('#clearSearch').addClass('btn');
    var queryGrid = $('#query').val();
    var queryWidget = $('#queryWidget').val();
    if (queryGrid != undefined) {
        if (queryGrid.length > 0) {
            queryText = queryGrid;
        } else {
            queryText = queryWidget;
        }
    } else {
        queryText = queryWidget;
    }
    $.ajax({type: 'POST', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'list', detail: 'body', query: queryText, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
        }, success: function(data) {
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').html('<div class=\'alert alert-error  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + data.message + '</div>');
            } else {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'> <img src=\'./images/icons/magnifier-zoom-actual-equal.png\'> <b>' + decodeURIComponent(t['filterTextLabel']) + '</b>: ' + queryText + '</div>');
            }
        }, error: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
        }});
}
function ajaxQuerySearchAllCharacter(leafId, url, securityToken, character) {
    $('#clearSearch').removeClass();
    $('#clearSearch').addClass('btn');
    $.ajax({type: 'POST', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'list', detail: 'body', securityToken: securityToken, leafId: leafId, character: character}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
        }, success: function(data) {
            if (data.success == false) {
                $('#centerViewport').html('').empty().html('<div class=\'alert alert-error  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + data.message + '</div>');
            } else {
                $('#centerViewport').html('').empty().append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'> <img src=\'./images/icons/magnifier-zoom-actual-equal.png\'> <b>' + decodeURIComponent(t['filterTextLabel']) + '</b>: ' + character + ' </div>');
            }
        }, error: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
        }});
}
function ajaxQuerySearchAllDate(leafId, url, securityToken, dateRangeStart, dateRangeEnd, dateRangeType, dateRangeExtraType) {
    $('#clearSearch').removeClass();
    $('#clearSearch').addClass('btn');
    if (dateRangeStart.length == 0) {
        dateRangeStart = $('#dateRangeStart').val()
    }
    if (dateRangeEnd.length == 0) {
        dateRangeEnd = $('#dateRangeEnd').val()
    }
    $.ajax({type: 'POST', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'list', detail: 'body', query: $('#query').val(), securityToken: securityToken, leafId: leafId, dateRangeStart: dateRangeStart, dateRangeEnd: dateRangeEnd, dateRangeType: dateRangeType}, beforeSend: function() {
            $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
        }, success: function(data) {
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').html('<div class=\'alert alert-error  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + data.message + '</div>');
            } else {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
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
                    case'month':
                    case'year':
                        strDate = dateRangeStart;
                        break;
                    case'week':
                    case'between':
                        strDate = dateRangeStart + " <img src=\'./images/icons/arrow-curve-000-left.png\'> " + dateRangeEnd;
                }
                $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'> <img src=\'./images/icons/smiley-lol.png\'> ' + decodeURIComponent(t['loadingCompleteTextLabel']) + '<img src=\'./images/icons/' + calendarPng + '\'> ' + strDate + '</div>');
            }
        }, error: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
        }});
}
function ajaxQuerySearchAllDateRange(leafId, url, securityToken) {
    ajaxQuerySearchAllDate(leafId, url, securityToken, $('#dateRangeStart').val(), $('#dateRangeEnd').val(), 'between', '', t['loadingTextLabel'], t['loadingCompleteTextLabel'], t['loadingErrorTextLabel']);
}
function showForm(leafId, url, securityToken) {
    sleep(500);
    $.ajax({type: 'POST', url: url, data: {method: 'new', type: 'form', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
        }, success: function(data) {
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').html('<div class=\'alert alert-error  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + data.message + '</div>');
            } else {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'> <img src=\'./images/icons/smiley-lol.png\'> ' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
            }
        }, error: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
        }});
}
function showFormUpdate(leafId, url, urlList, securityToken, financePeriodId, updateAccess, deleteAccess) {
    sleep(500);
    $.ajax({type: 'POST', url: urlList, data: {method: 'read', type: 'form', financePeriodId: financePeriodId, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
        }, success: function(data) {
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').html('<div class=\'alert alert-error  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + data.message + '</div>');
            } else {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'> <img src=\'./images/icons/smiley-lol.png\'> ' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
                $('#newRecordButton1').removeClass();
                $('#newRecordButton2').removeClass();
                $('#newRecordButton3').removeClass();
                $('#newRecordButton4').removeClass();
                $('#newRecordButton5').removeClass();
                $('#newRecordButton6').removeClass();
                $('#newRecordButton7').removeClass();
                $('#newRecordButton1').addClass('btn btn-success disabled');
                $('#newRecordButton2').addClass('btn dropdown-toggle btn-success disabled');
                $('#newRecordButton3').addClass('disabled');
                $('#newRecordButton4').addClass('disabled');
                $('#newRecordButton5').addClass('disabled');
                $('#newRecordButton6').addClass('disabled');
                $('#newRecordButton7').addClass('disabled');
                $('#newRecordButton1').attr('onClick', '');
                $('#newRecordButton2').attr('onClick', '');
                $('#newRecordButton3').attr('onClick', '');
                $('#newRecordButton4').attr('onClick', '');
                $('#newRecordButton5').attr('onClick', '');
                $('#newRecordButton6').attr('onClick', '');
                $('#newRecordButton7').attr('onClick', '');
                $('#updateRecordButton1').removeClass();
                $('#updateRecordButton2').removeClass();
                $('#updateRecordButton3').removeClass();
                $('#updateRecordButton4').removeClass();
                $('#updateRecordButton5').removeClass();
                $('#updateRecordButton1').removeClass();
                $('#updateRecordButton2').removeClass();
                $('#updateRecordButton3').removeClass();
                $('#updateRecordButton4').removeClass();
                $('#updateRecordButton5').removeClass();
                if (updateAccess == 1) {
                    $('#updateRecordButton1').addClass('btn btn-info');
                    $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info');
                    $('#updateRecordButton1').attr('onClick', '');
                    $('#updateRecordButton2').attr('onClick', '');
                    $('#updateRecordButton3').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\",\"" + deleteAccess + "\")");
                    $('#updateRecordButton4').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\",\"" + deleteAccess + "\")");
                    $('#updateRecordButton5').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\",\"" + deleteAccess + "\")");
                } else {
                    $('#updateRecordButton1').addClass('btn btn-info disabled');
                    $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info disabled');
                    $('#updateRecordButton1').attr('onClick', '');
                    $('#updateRecordButton2').attr('onClick', '');
                    $('#updateRecordButton3').attr('onClick', '');
                    $('#updateRecordButton4').attr('onClick', '');
                    $('#updateRecordButton5').attr('onClick', '');
                }
                if (deleteAccess == 1) {
                    $('#deleteRecordButton').removeClass();
                    $('#deleteRecordButton').addClass('btn btn-danger');
                    $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\")");
                } else {
                    $('#deleteRecordButton').removeClass();
                    $('#deleteRecordButton').addClass('btn btn-danger');
                    $('#deleteRecordButton').attr('onClick', '');
                }
            }
        }, error: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
        }});
}
function showModalDelete(financePeriodId, financePeriodStartDate, financePeriodEndDate) {
    $('#financePeriodIdPreview').val('');
    $('#financePeriodIdPreview').val(decodeURIComponent(financePeriodId));
    $('#financePeriodStartDatePreview').val('');
    $('#financePeriodStartDatePreview').val(decodeURIComponent(financePeriodStartDate));
    $('#financePeriodEndDatePreview').val('');
    $('#financePeriodEndDatePreview').val(decodeURIComponent(financePeriodEndDate));
    showMeModal('deletePreview', 1);
}
function deleteGridRecord(leafId, url, urlList, securityToken) {
    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', financePeriodId: $('#financePeriodIdPreview').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
        }, success: function(data) {
            if (data.success == true) {
                showMeModal('deletePreview', 0);
                showGrid(leafId, urlList, securityToken, 0, 10, t['loadingTextLabel'], t['deleteRecordTextLabel'], t['loadingErrorTextLabel'], 2);
            } else if (data.success == false) {
                $('#infoPanel').html('\'<div class=alert alert-error col-md-12\'>' + data.message + '</div>');
            }
        }, error: function() {
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
            }
        }});
}
function deleteGridRecordCheckbox(leafId, url, urlList, securityToken) {
    var stringText = '';
    var counter = 0;
    $('input:checkbox[name="financePeriodId[]"]').each(function() {
        stringText = stringText + "&financePeriodId[]=" + $(this).val();
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
        alert(decodeURIComponent(t['deleteCheckboxText']))
        return false;
    } else {
        url = url + "?" + stringText;
    }
    $.ajax({type: 'GET', url: url, data: {method: 'updateStatus', output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
        }, success: function(data) {
            if (data.success == true) {
                showGrid(leafId, urlList, securityToken, 0, 10, t['loadingTextLabel'], t['deleteRecordTextLabel'], t['loadingErrorTextLabel'], 2);
            } else if (data.success == false) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + data.message + '</div>');
            } else {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + data.message + '</div>');
            }
        }, error: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
        }});
}
function reportRequest(leafId, url, securityToken, mode) {
    $.ajax({type: 'GET', url: url, data: {method: 'report', mode: mode, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
        }, success: function(data) {
            if (data.success == true) {
                var path = './package/financial/generalLedger/document/' + data.folder + '/' + data.filename;
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>Your Request File have been created</div>');
                window.open(path);
            } else {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + data.message + '</div>');
            }
        }, error: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
        }});
}
function auditRecord(leafId, url, securityToken) {
    var css = $('#auditRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        return false;
    }
}
function newRecord(leafId, url, urlList, securityToken, type, createAccesss, updateAccess, deleteAccess) {
    var css = $('#newRecordButton2').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#infoPanel').empty();
        $('#infoPanel').html('');
        $('#newRecordButton1').removeClass();
        $('#newRecordButton2').removeClass();
        $('#newRecordButton3').removeClass();
        $('#newRecordButton4').removeClass();
        $('#newRecordButton5').removeClass();
        $('#newRecordButton6').removeClass();
        $('#newRecordButton7').removeClass();
        $('#newRecordButton1').addClass('btn btn-success disabled');
        $('#newRecordButton2').addClass('btn dropdown-toggle btn-success disabled');
        $('#newRecordButton3').addClass('disabled');
        $('#newRecordButton4').addClass('disabled');
        $('#newRecordButton5').addClass('disabled');
        $('#newRecordButton6').addClass('disabled');
        $('#newRecordButton7').addClass('disabled');
        $('#newRecordButton1').attr('onClick', '');
        $('#newRecordButton2').attr('onClick', '');
        $('#newRecordButton3').attr('onClick', '');
        $('#newRecordButton4').attr('onClick', '');
        $('#newRecordButton5').attr('onClick', '');
        $('#newRecordButton6').attr('onClick', '');
        $('#newRecordButton7').attr('onClick', '');
        if (type == 1) {
            if ($('#financePeriodStartDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['financePeriodStartDateLabel'] + ' </div>');
                $('#financePeriodStartDate').addClass('form-group has-error');
                $('#financePeriodStartDate').focus();
            } else if ($('#financePeriodEndDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['financePeriodEndDateLabel'] + ' </div>');
                $('#financePeriodEndDateForm').addClass('form-group has-error');
                $('#financePeriodEndDate').focus();
            } else {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>Form Complete</div>');
                $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', financePeriodStartDate: $('#financePeriodStartDate').val(), financePeriodEndDate: $('#financePeriodEndDate').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                    }, success: function(data) {
                        if (data.success == true) {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'> <img src=\'./images/icons/smiley-lol.png\'> ' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
                            $('#financePeriodStartDate').val('');
                            $('#financePeriodEndDate').val('');
                        } else if (data.success == false) {
                            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + data.message + '</div>');
                        }
                    }, error: function() {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        if (data.success == false) {
                            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                        }
                    }});
            }
        } else if (type == 2) {
            if ($('#financePeriodStartDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['financePeriodStartDateLabel'] + ' </div>');
                $('#financePeriodStartDate').addClass('form-group has-error');
                $('#financePeriodStartDate').focus();
            } else if ($('#financePeriodEndDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['financePeriodEndDateLabel'] + ' </div>');
                $('#financePeriodEndDate').addClass('form-group has-error');
                $('#financePeriodEndDate').focus();
            } else {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>Form Complete</div>');
                $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', financePeriodStartDate: $('#financePeriodStartDate').val(), financePeriodEndDate: $('#financePeriodEndDate').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                    }, success: function(data) {
                        if (data.success == true) {
                            $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'> <img src=\'./images/icons/smiley-lol.png\'> ' + decodeURIComponent(t['newRecordTextLabel']) + '</div>');
                            $('#financePeriodId').val(data.financePeriodId);
                            $('#newRecordButton1').removeClass();
                            $('#newRecordButton2').removeClass();
                            $('#newRecordButton3').removeClass();
                            $('#newRecordButton4').removeClass();
                            $('#newRecordButton5').removeClass();
                            $('#newRecordButton6').removeClass();
                            $('#newRecordButton7').removeClass();
                            $('#newRecordButton1').addClass('btn btn-success disabled');
                            $('#newRecordButton2').addClass('btn dropdown-toggle btn-success disabled');
                            $('#newRecordButton3').addClass('disabled');
                            $('#newRecordButton4').addClass('disabled');
                            $('#newRecordButton5').addClass('disabled');
                            $('#newRecordButton6').addClass('disabled');
                            $('#newRecordButton7').addClass('disabled');
                            $('#newRecordButton1').attr('onClick', '');
                            $('#newRecordButton2').attr('onClick', '');
                            $('#newRecordButton3').attr('onClick', '');
                            $('#newRecordButton4').attr('onClick', '');
                            $('#newRecordButton5').attr('onClick', '');
                            $('#newRecordButton6').attr('onClick', '');
                            $('#newRecordButton7').attr('onClick', '');
                            $('#updateRecordButton1').removeClass();
                            $('#updateRecordButton2').removeClass();
                            $('#updateRecordButton3').removeClass();
                            $('#updateRecordButton4').removeClass();
                            $('#updateRecordButton5').removeClass();
                            if (updateAccess == 1) {
                                $('#updateRecordButton1').addClass('btn btn-info');
                                $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info');
                                $('#updateRecordButton1').attr('onClick', '');
                                $('#updateRecordButton2').attr('onClick', '');
                                $('#updateRecordButton3').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\")");
                                $('#updateRecordButton4').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\")");
                                $('#updateRecordButton5').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\")");
                            } else {
                                $('#updateRecordButton1').addClass('btn btn-info disabled');
                                $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info disabled');
                                $('#updateRecordButton1').attr('onClick', '');
                                $('#updateRecordButton2').attr('onClick', '');
                                $('#updateRecordButton3').attr('onClick', '');
                                $('#updateRecordButton4').attr('onClick', '');
                                $('#updateRecordButton5').attr('onClick', '');
                            }
                            if (deleteAccess == 1) {
                                $('#deleteRecordButton').removeClass();
                                $('#deleteRecordButton').addClass('btn btn-danger');
                                $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\")");
                            } else {
                                $('#deleteRecordButton').removeClass();
                                $('#deleteRecordButton').addClass('btn btn-danger');
                                $('#deleteRecordButton').attr('onClick', '');
                            }
                        }
                    }, error: function() {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                    }});
            }
        } else if (type == 5) {
            if ($('#financePeriodStartDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['financePeriodStartDateLabel'] + ' </div>');
                $('#financePeriodStartDate').addClass('form-group has-error');
                $('#financePeriodStartDate').focus();
            } else if ($('#financePeriodEndDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['financePeriodEndDateLabel'] + ' </div>');
                $('#financePeriodEndDate').addClass('form-group has-error');
                $('#financePeriodEndDate').focus();
            } else {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
                $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', financePeriodStartDate: $('#financePeriodStartDate').val(), financePeriodEndDate: $('#financePeriodEndDate').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                    }, success: function(data) {
                        if (data.success == true) {
                            showGrid(leafId, urlList, securityToken, 0, 10, t['loadingTextLabel'], t['newRecordTextLabel'], t['loadingErrorTextLabel'], 2);
                        } else {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + data.message + '</div>');
                        }
                    }, error: function() {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                    }});
            }
            showMeDiv('tableDate', 0);
            showMeDiv('formEntry', 1);
        }
    }
}
function updateRecord(leafId, url, urlList, securityToken, type, deleteAccess) {
    var css = $('#updateRecordButton2').attr('class');
    if (css.search('disabled') > 0) {
    } else {
        $('#infoPanel').empty();
        $('#infoPanel').html('');
        if (type == 1) {
            if ($('#companyId').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['companyIdLabel'] + '</div>');
                $('#companyId').addClass('form-group has-error');
                $('#companyId').focus();
            } else if ($('#financePeriodStartDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['financePeriodStartDateLabel'] + ' </div>');
                $('#financePeriodStartDate').addClass('form-group has-error');
                $('#financePeriodStartDate').focus();
            } else if ($('#financePeriodEndDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['financePeriodEndDateLabel'] + ' </div>');
                $('#financePeriodEndDate').addClass('form-group has-error');
                $('#financePeriodEndDate').focus();
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
                $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', financePeriodId: $('#financePeriodId').val(), financePeriodStartDate: $('#financePeriodStartDate').val(), financePeriodEndDate: $('#financePeriodEndDate').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                    }, success: function(data) {
                        if (data.success == true) {
                            $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'> <img src=\'./images/icons/smiley-lol.png\'> ' + decodeURIComponent(t['updateRecordTextLabel']) + '</div>');
                            if (deleteAccess == 1) {
                                $('#deleteRecordButton').removeClass();
                                $('#deleteRecordButton').addClass('btn btn-danger');
                                $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\")");
                            } else {
                                $('#deleteRecordButton').removeClass();
                                $('#deleteRecordButton').addClass('btn btn-danger');
                                $('#deleteRecordButton').attr('onClick', '');
                            }
                        } else if (data.success == false) {
                            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + data.message + '</div>');
                        }
                    }, error: function() {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                    }});
            }
        } else if (type == 3) {
            if ($('#financePeriodStartDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['financePeriodStartDateLabel'] + ' </div>');
                $('#financePeriodStartDate').addClass('form-group has-error');
                $('#financePeriodStartDate').focus();
            } else if ($('#financePeriodEndDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['financePeriodEndDateLabel'] + ' </div>');
                $('#financePeriodEndDate').addClass('form-group has-error');
                $('#financePeriodEndDate').focus();
            } else {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
                $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', financePeriodId: $('#financePeriodId').val(), financePeriodStartDate: $('#financePeriodStartDate').val(), financePeriodEndDate: $('#financePeriodEndDate').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                    }, success: function(data) {
                        if (data.success == true) {
                            $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'> <img src=\'./images/icons/smiley-lol.png\'> ' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
                            showGrid(leafId, urlList, securityToken, 0, 10, t['loadingTextLabel'], 2)
                        } else if (data.success == false) {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + data.message + '</div>');
                        }
                    }, error: function() {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                    }});
            }
        }
    }
}
function deleteRecord(leafId, url, urlList, securityToken, deleteAccess) {
    var css = $('#deleteRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (deleteAccess == 1) {
            if (confirm(decodeURIComponent(t['deleteRecordMessageLabel']))) {
                var value = $('#financePeriodId').val();
                if (!value) {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html('<div class=\'alert\'>Please Contact Administrator</div>');
                } else {
                    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', financePeriodId: $('#financePeriodId').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                        }, success: function(data) {
                            if (data.success == true) {
                                showGrid(leafId, urlList, securityToken, 0, 10, 2);
                            } else if (data.success == false) {
                                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + data.message + '</div>');
                            }
                        }, error: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                        }});
                }
            } else {
                return false;
            }
        }
    }
}
function resetRecord(leafId, url, urlList, securityToken, createAccess, updateAccess, deleteAccess) {
    $('#infoPanel').empty();
    $('#infoPanel').html('');
    $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'><img src=\'./images/icons/fruit-orange.png\'> ' + decodeURIComponent(t['resetRecordTextLabel']) + '</div>');
    $('#newRecordButton1').removeClass();
    $('#newRecordButton2').removeClass();
    $('#newRecordButton1').addClass('btn btn-success');
    $('#newRecordButton2').addClass('btn dropdown-toggle btn-success');
    if (createAccess == 1) {
        $('#newRecordButton1').removeClass();
        $('#newRecordButton2').removeClass();
        $('#newRecordButton3').removeClass();
        $('#newRecordButton4').removeClass();
        $('#newRecordButton5').removeClass();
        $('#newRecordButton6').removeClass();
        $('#newRecordButton7').removeClass();
        $('#newRecordButton1').addClass('btn btn-success');
        $('#newRecordButton2').addClass('btn dropdown-toggle btn-success');
        $('#newRecordButton1').attr('onClick', '');
        $('#newRecordButton2').attr('onClick', '');
        $('#newRecordButton3').attr('onClick', "newRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\")");
        $('#newRecordButton4').attr('onClick', "newRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\")");
        $('#newRecordButton5').attr('onClick', "newRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\")");
        $('#newRecordButton6').attr('onClick', "newRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 4 + "\")");
        $('#newRecordButton7').attr('onClick', "newRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 5 + "\")");
    } else {
        $('#newRecordButton1').addClass('btn btn-success disabled');
        $('#newRecordButton2').addClass('btn dropdown-toggle btn-success disabled');
        $('#newRecordButton1').removeClass();
        $('#newRecordButton2').removeClass();
        $('#newRecordButton3').removeClass();
        $('#newRecordButton4').removeClass();
        $('#newRecordButton5').removeClass();
        $('#newRecordButton6').removeClass();
        $('#newRecordButton7').removeClass();
    }
    $('#updateRecordButton1').removeClass();
    $('#updateRecordButton2').removeClass();
    $('#updateRecordButton3').removeClass();
    $('#updateRecordButton4').removeClass();
    $('#updateRecordButton5').removeClass();
    $('#updateRecordButton1').addClass('btn btn-info disabled');
    $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info disabled');
    $('#updateRecordButton1').attr('onClick', '');
    $('#updateRecordButton2').attr('onClick', '');
    $('#updateRecordButton3').attr('onClick', '');
    $('#updateRecordButton4').attr('onClick', '');
    $('#updateRecordButton5').attr('onClick', '');
    $('#deleteRecordButton').removeClass();
    $('#deleteRecordButton').addClass('btn btn-danger disabled');
    $('#deleteRecordButton').attr('onClick', '');
    $('#postRecordButton').removeClass();
    $('#postRecordButton').addClass('btn btn-info');
    $('#postRecordButton').attr('onClick', '');
    $('#firstRecordButton').removeClass();
    $('#firstRecordButton').addClass('btn btn-default');
    $('#firstRecordButton').attr('onClick', "firstRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",     \"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
    $('#previousRecordButton').removeClass();
    $('#previousRecordButton').addClass('btn btn-default disabled');
    $('#previousRecordButton').attr('onClick', '');
    $('#nextRecordButton').removeClass();
    $('#nextRecordButton').addClass('btn btn-default disabled');
    $('#nextRecordButton').attr('onClick', '');
    $('#endRecordButton').removeClass();
    $('#endRecordButton').addClass('btn btn-default');
    $('#endRecordButton').attr('onClick', "endRecord\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\")");
    $('#financePeriodId').val('');
    $('#financePeriodStartDate').val('');
    $('#financePeriodEndDate').val('');

    $('#executeTime').val('');
}
function postRecord(leafId, url, urlList, SecurityToken) {
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
                $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
            }, success: function(data) {
                var smileyRoll = './images/icons/smiley-roll.png';
                if (data.firstRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (data.success == true) {
                    $.ajax({type: 'POST', url: url, data: {method: 'read', financePeriodId: data.firstRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                        }, success: function(data) {
                            if (data.success == true) {
                                $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'><img src=\'./images/icons/control-stop.png\'> ' + decodeURIComponent(t['firstButtonLabel']) + '</div>');
                                $('#financePeriodId').val(data.data.financePeriodId);
                                $('#financePeriodStartDate').val(data.data.financePeriodStartDate);
                                $('#financePeriodEndDate').val(data.data.financePeriodEndDate);
                                if (data.nextRecord > 0) {
                                    $('#previousRecordButton').removeClass();
                                    $('#previousRecordButton').addClass('btn btn-default  disabled');
                                    $('#previousRecordButton').attr('onClick', '');
                                    $('#nextRecordButton').removeClass();
                                    $('#nextRecordButton').addClass('btn btn-default');
                                    $('#nextRecordButton').attr('onClick', '');
                                    $('#nextRecordButton').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
                                    $('#firstRecordCounter').val(data.firstRecord);
                                    $('#previousRecordCounter').val(data.previousRecord);
                                    $('#nextRecordCounter').val(data.nextRecord);
                                    $('#lastRecordCounter').val(data.lastRecord);
                                    $('#newRecordButton1').removeClass();
                                    $('#newRecordButton2').removeClass();
                                    $('#newRecordButton3').removeClass();
                                    $('#newRecordButton4').removeClass();
                                    $('#newRecordButton5').removeClass();
                                    $('#newRecordButton6').removeClass();
                                    $('#newRecordButton7').removeClass();
                                    $('#newRecordButton1').addClass('btn btn-success disabled');
                                    $('#newRecordButton2').addClass('btn dropdown-toggle btn-success disabled');
                                    $('#newRecordButton3').addClass('disabled');
                                    $('#newRecordButton4').addClass('disabled');
                                    $('#newRecordButton5').addClass('disabled');
                                    $('#newRecordButton6').addClass('disabled');
                                    $('#newRecordButton7').addClass('disabled');
                                    $('#newRecordButton1').attr('onClick', '');
                                    $('#newRecordButton2').attr('onClick', '');
                                    $('#newRecordButton3').attr('onClick', '');
                                    $('#newRecordButton4').attr('onClick', '');
                                    $('#newRecordButton5').attr('onClick', '');
                                    $('#newRecordButton6').attr('onClick', '');
                                    $('#newRecordButton7').attr('onClick', '');
                                    $('#updateRecordButton1').removeClass();
                                    $('#updateRecordButton2').removeClass();
                                    $('#updateRecordButton3').removeClass();
                                    $('#updateRecordButton4').removeClass();
                                    $('#updateRecordButton5').removeClass();
                                    if (updateAccess == 1) {
                                        $('#updateRecordButton1').addClass('btn btn-info');
                                        $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info');
                                        $('#updateRecordButton1').attr('onClick', '');
                                        $('#updateRecordButton2').attr('onClick', '');
                                        $('#updateRecordButton3').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\",\"" + deleteAccess + "\")");
                                        $('#updateRecordButton4').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\",\"" + deleteAccess + "\")");
                                        $('#updateRecordButton5').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\",\"" + deleteAccess + "\")");
                                    } else {
                                        $('#updateRecordButton1').addClass('btn btn-info disabled');
                                        $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info disabled');
                                        $('#updateRecordButton1').attr('onClick', '');
                                        $('#updateRecordButton2').attr('onClick', '');
                                        $('#updateRecordButton3').attr('onClick', '');
                                        $('#updateRecordButton4').attr('onClick', '');
                                        $('#updateRecordButton5').attr('onClick', '');
                                    }
                                    if (deleteAccess == 1) {
                                        $('#deleteRecordButton').removeClass();
                                        $('#deleteRecordButton').addClass('btn btn-danger');
                                        $('#deleteRecordButton').attr('onClick', '');
                                        $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\")");
                                    } else {
                                        $('#deleteRecordButton').removeClass();
                                        $('#deleteRecordButton').addClass('btn btn-danger');
                                        $('#deleteRecordButton').attr('onClick', '');
                                    }
                                }
                            }
                        }, error: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                        }});
                } else {
                    $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + data.message + '</div>');
                }
            }, error: function() {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-error spann11\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
            }});
    }
}
function endRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess) {
    var css = $('#endRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $.ajax({type: 'GET', url: url, data: {method: 'dataNavigationRequest', dataNavigation: 'lastRecord', output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
            }, success: function(data) {
                var smileyRoll = './images/icons/smiley-roll.png';
                if (data.lastRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (data.success == true) {
                    $.ajax({type: 'POST', url: url, data: {method: 'read', financePeriodId: data.lastRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                        }, success: function(data) {
                            if (data.success == true) {
                                $('#infoPanel').empty();
                                $('#infoPanel').html('');
                                $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'><img src=\'./images/icons/control-stop-180.png\'> ' + decodeURIComponent(t['endButtonLabel']) + '</div>');
                                $('#financePeriodId').val(data.data.financePeriodId);
                                $('#financePeriodStartDate').val(data.data.financePeriodStartDate);
                                $('#financePeriodEndDate').val(data.data.financePeriodEndDate);
                                if (data.lastRecord != 0) {
                                    $('#previousRecordButton').removeClass();
                                    $('#previousRecordButton').addClass('btn btn-default');
                                    $('#previousRecordButton').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
                                    $('#nextRecordButton').removeClass();
                                    $('#nextRecordButton').addClass('btn btn-default disabled');
                                    $('#nextRecordButton').attr('onClick', '');
                                    $('#firstRecordCounter').val(data.firstRecord);
                                    $('#previousRecordCounter').val(data.previousRecord);
                                    $('#nextRecordCounter').val(data.nextRecord);
                                    $('#lastRecordCounter').val(data.lastRecord);
                                    $('#newRecordButton1').removeClass();
                                    $('#newRecordButton2').removeClass();
                                    $('#newRecordButton3').removeClass();
                                    $('#newRecordButton4').removeClass();
                                    $('#newRecordButton5').removeClass();
                                    $('#newRecordButton6').removeClass();
                                    $('#newRecordButton7').removeClass();
                                    $('#newRecordButton1').addClass('btn btn-success disabled');
                                    $('#newRecordButton2').addClass('btn dropdown-toggle btn-success disabled');
                                    $('#newRecordButton3').addClass('disabled');
                                    $('#newRecordButton4').addClass('disabled');
                                    $('#newRecordButton5').addClass('disabled');
                                    $('#newRecordButton6').addClass('disabled');
                                    $('#newRecordButton7').addClass('disabled');
                                    $('#newRecordButton1').attr('onClick', '');
                                    $('#newRecordButton2').attr('onClick', '');
                                    $('#newRecordButton3').attr('onClick', '');
                                    $('#newRecordButton4').attr('onClick', '');
                                    $('#newRecordButton5').attr('onClick', '');
                                    $('#newRecordButton6').attr('onClick', '');
                                    $('#newRecordButton7').attr('onClick', '');
                                    $('#updateRecordButton1').removeClass();
                                    $('#updateRecordButton2').removeClass();
                                    $('#updateRecordButton3').removeClass();
                                    $('#updateRecordButton4').removeClass();
                                    $('#updateRecordButton5').removeClass();
                                    if (updateAccess == 1) {
                                        $('#updateRecordButton1').addClass('btn btn-info');
                                        $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info');
                                        $('#updateRecordButton1').attr('onClick', '');
                                        $('#updateRecordButton2').attr('onClick', '');
                                        $('#updateRecordButton3').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\",\"" + deleteAccess + "\")");
                                        $('#updateRecordButton4').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\",\"" + deleteAccess + "\")");
                                        $('#updateRecordButton5').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\",\"" + deleteAccess + "\")");
                                    } else {
                                        $('#updateRecordButton1').addClass('btn btn-info disabled');
                                        $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info disabled');
                                        $('#updateRecordButton1').attr('onClick', '');
                                        $('#updateRecordButton2').attr('onClick', '');
                                        $('#updateRecordButton3').attr('onClick', '');
                                        $('#updateRecordButton4').attr('onClick', '');
                                        $('#updateRecordButton5').attr('onClick', '');
                                    }
                                    if (deleteAccess == 1) {
                                        $('#deleteRecordButton').removeClass();
                                        $('#deleteRecordButton').addClass('btn btn-danger');
                                        $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\")");
                                    } else {
                                        $('#deleteRecordButton').removeClass();
                                        $('#deleteRecordButton').addClass('btn btn-danger');
                                        $('#deleteRecordButton').attr('onClick', '');
                                    }
                                }
                            }
                        }, error: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                        }});
                } else {
                    $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + data.message + '</div>');
                }
            }, error: function() {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
            }});
    }
}
function previousRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess) {
    var css = $('#previousRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($('#previousRecordCounter').val() == '' || $('#previousRecordCounter').val() == undefined) {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>testingo</div>');
        }
        if (parseFloat($('#previousRecordCounter').val()) > 0 && parseFloat($('#previousRecordCounter').val()) < parseFloat($('#lastRecordCounter').val())) {
            $.ajax({type: 'POST', url: url, data: {method: 'read', financePeriodId: $('#previousRecordCounter').val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                }, success: function(data) {
                    if (data.success == true) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'><img src=\'./images/icons/control-180.png\'> ' + decodeURIComponent(t['previousButtonLabel']) + '</div>');
                        $('#financePeriodId').val(data.data.financePeriodId);
                        $('#financePeriodStartDate').val(data.data.financePeriodStartDate);
                        $('#financePeriodEndDate').val(data.data.financePeriodEndDate);
                        $('#newRecordButton1').removeClass();
                        $('#newRecordButton2').removeClass();
                        $('#newRecordButton3').removeClass();
                        $('#newRecordButton4').removeClass();
                        $('#newRecordButton5').removeClass();
                        $('#newRecordButton6').removeClass();
                        $('#newRecordButton7').removeClass();
                        $('#newRecordButton1').addClass('btn btn-success disabled');
                        $('#newRecordButton2').addClass('btn dropdown-toggle btn-success disabled');
                        $('#newRecordButton3').addClass('disabled');
                        $('#newRecordButton4').addClass('disabled');
                        $('#newRecordButton5').addClass('disabled');
                        $('#newRecordButton6').addClass('disabled');
                        $('#newRecordButton7').addClass('disabled');
                        $('#newRecordButton1').attr('onClick', '');
                        $('#newRecordButton2').attr('onClick', '');
                        $('#newRecordButton3').attr('onClick', '');
                        $('#newRecordButton4').attr('onClick', '');
                        $('#newRecordButton5').attr('onClick', '');
                        $('#newRecordButton6').attr('onClick', '');
                        $('#newRecordButton7').attr('onClick', '');
                        $('#updateRecordButton1').removeClass();
                        $('#updateRecordButton2').removeClass();
                        $('#updateRecordButton3').removeClass();
                        $('#updateRecordButton4').removeClass();
                        $('#updateRecordButton5').removeClass();
                        if (updateAccess == 1) {
                            $('#updateRecordButton1').addClass('btn btn-info');
                            $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info');
                            $('#updateRecordButton1').attr('onClick', '');
                            $('#updateRecordButton2').attr('onClick', '');
                            $('#updateRecordButton3').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\",\"" + deleteAccess + "\")");
                            $('#updateRecordButton4').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\",\"" + deleteAccess + "\")");
                            $('#updateRecordButton5').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#updateRecordButton1').addClass('btn btn-info disabled');
                            $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info disabled');
                            $('#updateRecordButton1').attr('onClick', '');
                            $('#updateRecordButton2').attr('onClick', '');
                            $('#updateRecordButton3').attr('onClick', '');
                            $('#updateRecordButton4').attr('onClick', '');
                            $('#updateRecordButton5').attr('onClick', '');
                        }
                        if (deleteAccess == 1) {
                            $('#deleteRecordButton').removeClass();
                            $('#deleteRecordButton').addClass('btn btn-danger');
                            $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#deleteRecordButton').removeClass();
                            $('#deleteRecordButton').addClass('btn btn-danger');
                            $('#deleteRecordButton').attr('onClick', '');
                        }
                        $('#firstRecordCounter').val(data.firstRecord);
                        $('#previousRecordCounter').val(data.previousRecord);
                        $('#nextRecordCounter').val(data.nextRecord);
                        $('#lastRecordCounter').val(data.lastRecord);
                        if (parseFloat(data.nextRecord) <= parseFloat(data.lastRecord)) {
                            $('#nextRecordButton').removeClass();
                            $('#nextRecordButton').addClass('btn btn-default');
                            $('#nextRecordButton').attr('onClick', '');
                            $('#nextRecordButton').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#nextRecordButton').removeClass();
                            $('#nextRecordButton').addClass('btn btn-default disabled');
                            $('#nextRecordButton').attr('onClick', '');
                        }
                        if (parseFloat(data.previousRecord) == 0) {
                            $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'><img src=\'./images/icons/exclamation.png\'> ' + decodeURIComponent(t['firstButtonLabel']) + '</div>');
                            $('#previousRecordButton').removeClass();
                            $('#previousRecordButton').addClass('btn btn-default disabled');
                            $('#previousRecordButton').attr('onClick', '');
                        }
                    }
                }, error: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                }});
        } else {
        }
    }
}
function nextRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess) {
    var css = $('#nextRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($('#nextRecordCounter').val() == '' || $('#nextRecordCounter').val() == undefined) {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>sdfd</div>');
        }
        if (parseFloat($('#nextRecordCounter').val()) <= parseFloat($('#lastRecordCounter').val())) {
            $.ajax({type: 'POST', url: url, data: {method: 'read', financePeriodId: $('#nextRecordCounter').val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                }, success: function(data) {
                    if (data.success == true) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'><img src=\'./images/icons/control.png\'> ' + decodeURIComponent(t['nextButtonLabel']) + '</div>');
                        $('#financePeriodId').val(data.data.financePeriodId);
                        $('#financePeriodStartDate').val(data.data.financePeriodStartDate);
                        $('#financePeriodEndDate').val(data.data.financePeriodEndDate);
                        $('#newRecordButton1').removeClass();
                        $('#newRecordButton2').removeClass();
                        $('#newRecordButton3').removeClass();
                        $('#newRecordButton4').removeClass();
                        $('#newRecordButton5').removeClass();
                        $('#newRecordButton6').removeClass();
                        $('#newRecordButton7').removeClass();
                        $('#newRecordButton1').addClass('btn btn-success disabled');
                        $('#newRecordButton2').addClass('btn dropdown-toggle btn-success disabled');
                        $('#newRecordButton3').addClass('disabled');
                        $('#newRecordButton4').addClass('disabled');
                        $('#newRecordButton5').addClass('disabled');
                        $('#newRecordButton6').addClass('disabled');
                        $('#newRecordButton7').addClass('disabled');
                        $('#newRecordButton1').attr('onClick', '');
                        $('#newRecordButton2').attr('onClick', '');
                        $('#newRecordButton3').attr('onClick', '');
                        $('#newRecordButton4').attr('onClick', '');
                        $('#newRecordButton5').attr('onClick', '');
                        $('#newRecordButton6').attr('onClick', '');
                        $('#newRecordButton7').attr('onClick', '');
                        $('#updateRecordButton1').removeClass();
                        $('#updateRecordButton2').removeClass();
                        $('#updateRecordButton3').removeClass();
                        $('#updateRecordButton4').removeClass();
                        $('#updateRecordButton5').removeClass();
                        if (updateAccess == 1) {
                            $('#updateRecordButton1').addClass('btn btn-info');
                            $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info');
                            $('#updateRecordButton1').attr('onClick', '');
                            $('#updateRecordButton2').attr('onClick', '');
                            $('#updateRecordButton3').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\",\"" + deleteAccess + "\")");
                            $('#updateRecordButton4').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\",\"" + deleteAccess + "\")");
                            $('#updateRecordButton5').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#updateRecordButton1').addClass('btn btn-info disabled');
                            $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info disabled');
                            $('#updateRecordButton1').attr('onClick', '');
                            $('#updateRecordButton2').attr('onClick', '');
                            $('#updateRecordButton3').attr('onClick', '');
                            $('#updateRecordButton4').attr('onClick', '');
                            $('#updateRecordButton5').attr('onClick', '');
                        }
                        if (deleteAccess == 1) {
                            $('#deleteRecordButton').removeClass();
                            $('#deleteRecordButton').addClass('btn btn-danger');
                            $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#deleteRecordButton').removeClass();
                            $('#deleteRecordButton').addClass('btn btn-danger');
                            $('#deleteRecordButton').attr('onClick', '');
                        }
                        $('#firstRecordCounter').val(data.firstRecord);
                        $('#previousRecordCounter').val(data.previousRecord);
                        $('#nextRecordCounter').val(data.nextRecord);
                        $('#lastRecordCounter').val(data.lastRecord);
                        if (parseFloat(data.previousRecord) > 0) {
                            $('#previousRecordButton').removeClass();
                            $('#previousRecordButton').addClass('btn btn-default');
                            $('#previousRecordButton').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'><img src=\'./images/icons/exclamation.png\'> ' + decodeURIComponent(t['endButtonLabel']) + '</div>');
                            $('#previousRecordButton').removeClass();
                            $('#previousRecordButton').addClass('btn btn-default disabled');
                            $('#previousRecordButton').attr('onClick', '');
                        }
                        if (parseFloat(data.nextRecord) == 0) {
                            $('#nextRecordButton').removeClass();
                            $('#nextRecordButton').addClass('btn btn-default disabled');
                            $('#nextRecordButton').attr('onClick', '');
                        }
                    }
                }, error: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                }});
        } else {
        }
    }
}
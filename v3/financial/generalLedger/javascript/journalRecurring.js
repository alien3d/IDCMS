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
function showFormUpdate(leafId, url, urlList, securityToken, journalReccurringId, updateAccess, deleteAccess) {
    sleep(500);
    $.ajax({type: 'POST', url: urlList, data: {method: 'read', type: 'form', journalReccurringId: journalReccurringId, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
function showModalDelete(journalReccurringId, journalReccurringTypeId, documentNo, journalTitle, journalDesc, journalDate, journalStartDate, journalEndDate, journalAmount) {
    $('#journalReccurringIdPreview').val('');
    $('#journalReccurringIdPreview').val(decodeURIComponent(journalReccurringId));
    $('#journalReccurringTypeIdPreview').val('');
    $('#journalReccurringTypeIdPreview').val(decodeURIComponent(journalReccurringTypeId));
    $('#documentNoPreview').val('');
    $('#documentNoPreview').val(decodeURIComponent(documentNo));
    $('#journalTitlePreview').val('');
    $('#journalTitlePreview').val(decodeURIComponent(journalTitle));
    $('#journalDescPreview').val('');
    $('#journalDescPreview').val(decodeURIComponent(journalDesc));
    $('#journalDatePreview').val('');
    $('#journalDatePreview').val(decodeURIComponent(journalDate));
    $('#journalStartDatePreview').val('');
    $('#journalStartDatePreview').val(decodeURIComponent(journalStartDate));
    $('#journalEndDatePreview').val('');
    $('#journalEndDatePreview').val(decodeURIComponent(journalEndDate));
    $('#journalAmountPreview').val('');
    $('#journalAmountPreview').val(decodeURIComponent(journalAmount));
    showMeModal('deletePreview', 1);
}
function deleteGridRecord(leafId, url, urlList, securityToken) {
    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', journalReccurringId: $('#journalReccurringIdPreview').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
function showFormCreateDetail(leafId, url, securityToken) {
    if ($('#journalRecurringId9999').val().length == 0) {
        $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['journalRecurringIdLabel'] + '</div>');
        $('#journalRecurringId9999HelpMe').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['journalRecurringIdLabel'] + '</div>');
        $('#journalRecurringId9999Detail').addClass('form-group has-error');
        $('#journalRecurringId9999').focus();
    } else if ($('#chartOfAccountId9999').val().length == 0) {
        $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['chartOfAccountIdLabel'] + ' </div>');
        $('#chartOfAccountId9999HelpMe').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['chartOfAccountIdLabel'] + '</div>');
        $('#chartOfAccountId9999Detail').addClass('form-group has-error');
        $('#chartOfAccountId9999').focus();
    } else if ($('#countryId9999').val().length == 0) {
        $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['countryIdLabel'] + ' </div>');
        $('#countryId9999HelpMe').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['countryIdLabel'] + '</div>');
        $('#countryId9999Detail').addClass('form-group has-error');
        $('#countryId9999').focus();
    } else if ($('#transactionTypeId9999').val().length == 0) {
        $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['transactionTypeIdLabel'] + ' </div>');
        $('#transactionTypeId9999HelpMe').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['transactionTypeIdLabel'] + '</div>');
        $('#transactionTypeId9999Detail').addClass('form-group has-error');
        $('#transactionTypeId9999').focus();
    } else if ($('#journalReccurringDetailAmount9999').val().length == 0) {
        $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalReccurringDetailAmountLabel'] + ' </div>');
        $('#journalReccurringDetailAmount9999HelpMe').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['journalReccurringDetailAmountLabel'] + '</div>');
        $('#journalReccurringDetailAmount9999Detail').addClass('form-group has-error');
        $('#journalReccurringDetailAmount9999').focus();
    } else {
        $('#infoPanel').empty();
        $('#infoPanel').html('');
        $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
        $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', journalReccurringId: $('#journalReccurringId').val(), journalRecurringId: $('#journalRecurringId9999').val(), chartOfAccountId: $('#chartOfAccountId9999').val(), countryId: $('#countryId9999').val(), transactionTypeId: $('#transactionTypeId9999').val(), journalReccurringDetailAmount: $('#journalReccurringDetailAmount9999').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                $('#miniInfoPanel9999').empty();
                $('#miniInfoPanel9999').html('');
                $('#miniInfoPanel9999').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
            }, success: function(data) {
                if (data.success == true) {
                    var appendTable = '';
                    appendTable = appendTable + "<tr id='" + data.journalReccurringDetailId + "'>";
                    appendTable = appendTable + "<td>" + data.totalRecord + "</td>";
                    appendTable = appendTable + "<td><div class='btn-group'>";
                    appendTable = appendTable + "<input type='hidden' name='journalReccurringDetailId[]'     id='journalReccurringDetailId" + data.journalReccurringDetailId + "'  value='" + data.journalReccurringDetailId + "'>";
                    appendTable = appendTable + "<input type='hidden' name='journalReccurringId[]'           id='journalReccurringId" + data.journalReccurringDetailId + "'       value='" + $("#journalReccurringId").val() + "'>";
                    appendTable = appendTable + "<a class=' btn btn-warning' title='Edit' onClick=showFormUpdateDetail('" + leafId + "','" + url + "','" + securityToken + "','" + data.journalReccurringDetailId + "')><i class='glyphicon glyphicon-edit glyphicon-white'></i></a>";
                    appendTable = appendTable + "<a class=' btn btn-danger' title='Delete' onClick=showModalDeleteDetail('" + data.journalReccurringDetailId + "')><i class='glyphicontrash  glyphicon-white'></i></a><div id='miniInfoPanel" + data.journalReccurringDetailId + "'></div></td>";
                    appendTable = appendTable + "<td><div class='form-group' id='journalRecurringIdDetail'>";
                    appendTable = appendTable + "<div class='input-group'><select name='journalRecurringId[]' id='journalRecurringId" + data.journalReccurringDetailId + "' class='col-md-8' onChange=removeMeErrorDetail('journalRecurringId" + data.journalReccurringDetailId + "')>";
                    appendTable = appendTable + "<option value=''>" + t['pleaseSelectTextLabel'] + "</option>";
                    appendTable = appendTable + journalRecurringIdTemplate;
                    appendTable = appendTable + "</select><button class='btn btn-info' type='button'><i class='glyphiconzoom-out'></i></button></div></div>";
                    appendTable = appendTable + "</td>";
                    appendTable = appendTable + "<td><div class='form-group' id='chartOfAccountIdDetail'>";
                    appendTable = appendTable + "<div class='input-group'><select name='chartOfAccountId[]' id='chartOfAccountId" + data.journalReccurringDetailId + "' class='col-md-8' onChange=removeMeErrorDetail('chartOfAccountId" + data.journalReccurringDetailId + "')>";
                    appendTable = appendTable + "<option value=''>" + t['pleaseSelectTextLabel'] + "</option>";
                    appendTable = appendTable + chartOfAccountIdTemplate;
                    appendTable = appendTable + "</select><button class='btn btn-info' type='button'><i class='glyphiconzoom-out'></i></button></div></div>";
                    appendTable = appendTable + "</td>";
                    appendTable = appendTable + "<td><div class='form-group' id='countryIdDetail'>";
                    appendTable = appendTable + "<div class='input-group'><select name='countryId[]' id='countryId" + data.journalReccurringDetailId + "' class='col-md-8' onChange=removeMeErrorDetail('countryId" + data.journalReccurringDetailId + "')>";
                    appendTable = appendTable + "<option value=''>" + t['pleaseSelectTextLabel'] + "</option>";
                    appendTable = appendTable + countryIdTemplate;
                    appendTable = appendTable + "</select><button class='btn btn-info' type='button'><i class='glyphiconzoom-out'></i></button></div></div>";
                    appendTable = appendTable + "</td>";
                    appendTable = appendTable + "<td><div class='form-group' id='transactionTypeIdDetail'>";
                    appendTable = appendTable + "<div class='input-group'><select name='transactionTypeId[]' id='transactionTypeId" + data.journalReccurringDetailId + "' class='col-md-8' onChange=removeMeErrorDetail('transactionTypeId" + data.journalReccurringDetailId + "')>";
                    appendTable = appendTable + "<option value=''>" + t['pleaseSelectTextLabel'] + "</option>";
                    appendTable = appendTable + transactionTypeIdTemplate;
                    appendTable = appendTable + "</select><button class='btn btn-info' type='button'><i class='glyphiconzoom-out'></i></button></div></div>";
                    appendTable = appendTable + "</td>";
                    appendTable = appendTable + "<td><input class='col-md-10' style=' text-align:right' type='text' name='journalReccurringDetailAmount[]' id='journalReccurringDetailAmount" + data.journalReccurringDetailId + "'   value='" + $("journalReccurringDetailAmount9999").val() + "' onKeyUp=validateMeCurrencyKeyUp('journalReccurringDetailAmount" + data.journalReccurringDetailId + "') onBlur=validateMeCurrencyBlur('journalReccurringDetailAmount" + data.journalReccurringDetailId + ")'></td>";

                    appendTable = appendTable + "<td><input type='text' name='executeTime' id='executeTime" + data.journalReccurringDetailId + "' value='" + data.executeTime + "' readOnly class='col-md-10'></td>";
                    appendTable = appendTable + "</tr>"
                    if (data.totalRecord == 1) {
                        $('#tableBody').empty();
                        $('#tableBody').html(appendTable);
                    } else {
                        $('#tableBody').append(appendTable);
                    }
                    $("#journalRecurringId" + data.journalReccurringDetailId).val($("#journalRecurringId9999").val());
                    $("#chartOfAccountId" + data.journalReccurringDetailId).val($("#chartOfAccountId9999").val());
                    $("#countryId" + data.journalReccurringDetailId).val($("#countryId9999").val());
                    $("#transactionTypeId" + data.journalReccurringDetailId).val($("#transactionTypeId9999").val());
                    $("#journalReccurringDetailAmount" + data.journalReccurringDetailId).val($("#journalReccurringDetailAmount9999").val());
                    $("#journalReccurringDetailId" + data.journalReccurringDetailId).val(data.journalReccurringDetailId);
                    $("#journalReccurringId" + data.journalReccurringDetailId).val($('#journalReccurringId').val());
                    $("#journalRecurringId9999").val('');
                    $("#chartOfAccountId9999").val('');
                    $("#countryId9999").val('');
                    $("#transactionTypeId9999").val('');
                    $("#journalReccurringDetailAmount9999").val('');
                    $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'> <img src=\'./images/icons/smiley-lol.png\'> ' + decodeURIComponent(t['newRecordTextLabel']) + '</div>');
                    $('#miniInfoPanel9999').html('<div class=\'alert alert-success  col-md-12\'><a class=\'close\' data-dismiss=\'alert\' href=\'#\'>&times;</a><img src=\'./images/icons/smiley-lol.png\'> ' + decodeURIComponent(t['newRecordTextLabel']) + '</div>');
                } else if (data.success == false) {
                    $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + data.message + '</div>');
                }
            }, error: function() {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
            }});
    }
}
function showFormUpdateDetail(leafId, url, securityToken, journalReccurringDetailId) {
    if ($('#journalRecurringId' + journalReccurringDetailId).val().length == 0) {
        $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['journalRecurringIdLabel'] + '</div>');
        $('#journalRecurringId' + journalReccurringDetailId + 'Detail').addClass('form-group has-error');
        $('#journalRecurringId' + journalReccurringDetailId).focus();
    } else if ($('#chartOfAccountId' + journalReccurringDetailId).val().length == 0) {
        $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['chartOfAccountIdLabel'] + ' </div>');
        $('#chartOfAccountId' + journalReccurringDetailId + 'Detail').addClass('form-group has-error');
        $('#chartOfAccountIdjournalReccurringDetailId').focus();
    } else if ($('#countryId' + journalReccurringDetailId).val().length == 0) {
        $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['countryIdLabel'] + ' </div>');
        $('#countryId' + journalReccurringDetailId + 'Detail').addClass('form-group has-error');
        $('#countryIdjournalReccurringDetailId').focus();
    } else if ($('#transactionTypeId' + journalReccurringDetailId).val().length == 0) {
        $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['transactionTypeIdLabel'] + ' </div>');
        $('#transactionTypeId' + journalReccurringDetailId + 'Detail').addClass('form-group has-error');
        $('#transactionTypeIdjournalReccurringDetailId').focus();
    } else if ($('#journalReccurringDetailAmount' + journalReccurringDetailId).val().length == 0) {
        $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalReccurringDetailAmountLabel'] + ' </div>');
        $('#journalReccurringDetailAmount' + journalReccurringDetailId + 'Detail').addClass('form-group has-error');
        $('#journalReccurringDetailAmountjournalReccurringDetailId').focus();
    } else {
        $('#infoPanel').empty();
        $('#infoPanel').html('');
        $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
        $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', journalReccurringDetailId: $('#journalReccurringDetailId' + journalReccurringDetailId).val(), journalRecurringId: $('#journalRecurringId' + journalReccurringDetailId).val(), chartOfAccountId: $('#chartOfAccountId' + journalReccurringDetailId).val(), countryId: $('#countryId' + journalReccurringDetailId).val(), transactionTypeId: $('#transactionTypeId' + journalReccurringDetailId).val(), journalReccurringDetailAmount: $('#journalReccurringDetailAmount' + journalReccurringDetailId).val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                $('#miniInfoPanel' + journalReccurringDetailId).empty();
                $('#miniInfoPanel' + journalReccurringDetailId).html('');
                $('#miniInfoPanel' + journalReccurringDetailId).html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
            }, success: function(data) {
                if (data.success == true) {
                    $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'> <img src=\'./images/icons/smiley-lol.png\'> ' + decodeURIComponent(t['updateRecordTextLabel']) + '</div>');
                    $('#miniInfoPanel' + journalReccurringDetailId).html('<div class=\'alert alert-success  col-md-12\'> <img src=\'./images/icons/smiley-lol.png\'><a class="close" data-dismiss="alert" href="#">&times;</a></div>');
                } else if (data.success == false) {
                    $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + data.message + '</div>');
                    $('#miniInfoPanel' + journalReccurringDetailId).html('<div class=\'alert alert-error col-md-12\'>' + data.message + '</div>');
                }
            }, error: function() {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                $('#miniInfoPanel' + journalReccurringDetailId).empty();
                $('#miniInfoPanel' + journalReccurringDetailId).html('');
                $('#miniInfoPanel' + journalReccurringDetailId).html('<div class=\'alert alert-error col-md-12\'> <img src=\'./images/icons/smiley-roll-sweat.png\'> ' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
            }});
    }
}
function showModalDeleteDetail(journalReccurringDetailId) {
    $('#journalReccurringDetailIdPreview').val('');
    $('#journalReccurringDetailIdPreview').val(decodeURIComponent($("#journalReccurringDetailId" + journalReccurringDetailId).val()));
    $('#journalRecurringIdPreview').val('');
    $('#journalRecurringIdPreview').val(decodeURIComponent($("#journalRecurringId" + journalReccurringDetailId + " option:selected").text()));
    $('#chartOfAccountIdPreview').val('');
    $('#chartOfAccountIdPreview').val(decodeURIComponent($("#chartOfAccountId" + journalReccurringDetailId + " option:selected").text()));
    $('#countryIdPreview').val('');
    $('#countryIdPreview').val(decodeURIComponent($("#countryId" + journalReccurringDetailId + " option:selected").text()));
    $('#transactionTypeIdPreview').val('');
    $('#transactionTypeIdPreview').val(decodeURIComponent($("#transactionTypeId" + journalReccurringDetailId + " option:selected").text()));
    $('#journalReccurringDetailAmountPreview').val('');
    $('#journalReccurringDetailAmountPreview').val(decodeURIComponent($("#journalReccurringDetailAmount" + journalReccurringDetailId).val()));
    showMeModal('deleteDetailPreview', 1);
}
function deleteGridRecordDetail(leafId, url, urlList, securityToken) {
    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', journalReccurringDetailId: $('#journalReccurringDetailIdPreview').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
        }, success: function(data) {
            if (data.success == true) {
                showMeModal('deleteDetailPreview', 0);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'> <img src=\'./images/icons/smiley-lol.png\'> ' + decodeURIComponent(t['deleteRecordTextLabel']) + '</div>');
                removeMeTr($('#journalReccurringDetailIdPreview').val())
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
    $('input:checkbox[name="journalReccurringId[]"]').each(function() {
        stringText = stringText + "&journalReccurringId[]=" + $(this).val();
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
            if ($('#journalReccurringTypeId').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalReccurringTypeIdLabel'] + ' </div>');
                $('#journalReccurringTypeId').addClass('form-group has-error');
                $('#journalReccurringTypeId').focus();
            } else if ($('#documentNo').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['documentNoLabel'] + ' </div>');
                $('#documentNoForm').addClass('form-group has-error');
                $('#documentNo').focus();
            } else if ($('#invoiceTitle').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalTitleLabel'] + ' </div>');
                $('#journalTitleForm').addClass('form-group has-error');
                $('#invoiceTitle').focus();
            } else if ($('#journalDesc').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalDescLabel'] + ' </div>');
                $('#journalDescForm').addClass('form-group has-error');
                $('#journalDesc').focus();
            } else if ($('#journalDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalDateLabel'] + ' </div>');
                $('#journalDateForm').addClass('form-group has-error');
                $('#journalDate').focus();
            } else if ($('#journalStartDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalStartDateLabel'] + ' </div>');
                $('#journalStartDateForm').addClass('form-group has-error');
                $('#journalStartDate').focus();
            } else if ($('#journalEndDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalEndDateLabel'] + ' </div>');
                $('#journalEndDateForm').addClass('form-group has-error');
                $('#journalEndDate').focus();
            } else if ($('#journalAmount').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalAmountLabel'] + ' </div>');
                $('#journalAmountForm').addClass('form-group has-error');
                $('#journalAmount').focus();
            } else {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>Form Complete</div>');
                $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', journalReccurringTypeId: $('#journalReccurringTypeId').val(), documentNo: $('#documentNo').val(), journalTitle: $('#invoiceTitle').val(), journalDesc: $('#journalDesc').val(), journalDate: $('#journalDate').val(), journalStartDate: $('#journalStartDate').val(), journalEndDate: $('#journalEndDate').val(), journalAmount: $('#journalAmount').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                    }, success: function(data) {
                        if (data.success == true) {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'> <img src=\'./images/icons/smiley-lol.png\'> ' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
                            $('#journalReccurringTypeId').val('');
                            $('#documentNo').val('');
                            $('#invoiceTitle').val('');
                            $('#journalDesc').val('');
                            $('#journalDate').val('');
                            $('#journalStartDate').val('');
                            $('#journalEndDate').val('');
                            $('#journalAmount').val('');
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
            if ($('#journalReccurringTypeId').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalReccurringTypeIdLabel'] + ' </div>');
                $('#journalReccurringTypeId').addClass('form-group has-error');
                $('#journalReccurringTypeId').focus();
            } else if ($('#documentNo').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['documentNoLabel'] + ' </div>');
                $('#documentNo').addClass('form-group has-error');
                $('#documentNo').focus();
            } else if ($('#invoiceTitle').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalTitleLabel'] + ' </div>');
                $('#invoiceTitle').addClass('form-group has-error');
                $('#invoiceTitle').focus();
            } else if ($('#journalDesc').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalDescLabel'] + ' </div>');
                $('#journalDesc').addClass('form-group has-error');
                $('#journalDesc').focus();
            } else if ($('#journalDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalDateLabel'] + ' </div>');
                $('#journalDate').addClass('form-group has-error');
                $('#journalDate').focus();
            } else if ($('#journalStartDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalStartDateLabel'] + ' </div>');
                $('#journalStartDate').addClass('form-group has-error');
                $('#journalStartDate').focus();
            } else if ($('#journalEndDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalEndDateLabel'] + ' </div>');
                $('#journalEndDate').addClass('form-group has-error');
                $('#journalEndDate').focus();
            } else if ($('#journalAmount').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalAmountLabel'] + ' </div>');
                $('#journalAmount').addClass('form-group has-error');
                $('#journalAmount').focus();
            } else {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>Form Complete</div>');
                $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', journalReccurringTypeId: $('#journalReccurringTypeId').val(), documentNo: $('#documentNo').val(), journalTitle: $('#invoiceTitle').val(), journalDesc: $('#journalDesc').val(), journalDate: $('#journalDate').val(), journalStartDate: $('#journalStartDate').val(), journalEndDate: $('#journalEndDate').val(), journalAmount: $('#journalAmount').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                    }, success: function(data) {
                        if (data.success == true) {
                            $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'> <img src=\'./images/icons/smiley-lol.png\'> ' + decodeURIComponent(t['newRecordTextLabel']) + '</div>');
                            $('#journalReccurringId').val(data.journalReccurringId);
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
            if ($('#journalReccurringTypeId').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalReccurringTypeIdLabel'] + ' </div>');
                $('#journalReccurringTypeId').addClass('form-group has-error');
                $('#journalReccurringTypeId').focus();
            } else if ($('#documentNo').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['documentNoLabel'] + ' </div>');
                $('#documentNo').addClass('form-group has-error');
                $('#documentNo').focus();
            } else if ($('#invoiceTitle').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalTitleLabel'] + ' </div>');
                $('#invoiceTitle').addClass('form-group has-error');
                $('#invoiceTitle').focus();
            } else if ($('#journalDesc').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalDescLabel'] + ' </div>');
                $('#journalDesc').addClass('form-group has-error');
                $('#journalDesc').focus();
            } else if ($('#journalDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalDateLabel'] + ' </div>');
                $('#journalDate').addClass('form-group has-error');
                $('#journalDate').focus();
            } else if ($('#journalStartDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalStartDateLabel'] + ' </div>');
                $('#journalStartDate').addClass('form-group has-error');
                $('#journalStartDate').focus();
            } else if ($('#journalEndDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalEndDateLabel'] + ' </div>');
                $('#journalEndDate').addClass('form-group has-error');
                $('#journalEndDate').focus();
            } else if ($('#journalAmount').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalAmountLabel'] + ' </div>');
                $('#journalAmount').addClass('form-group has-error');
                $('#journalAmount').focus();
            } else {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
                $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', journalReccurringTypeId: $('#journalReccurringTypeId').val(), documentNo: $('#documentNo').val(), journalTitle: $('#invoiceTitle').val(), journalDesc: $('#journalDesc').val(), journalDate: $('#journalDate').val(), journalStartDate: $('#journalStartDate').val(), journalEndDate: $('#journalEndDate').val(), journalAmount: $('#journalAmount').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
            } else if ($('#journalReccurringTypeId').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalReccurringTypeIdLabel'] + ' </div>');
                $('#journalReccurringTypeId').addClass('form-group has-error');
                $('#journalReccurringTypeId').focus();
            } else if ($('#documentNo').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['documentNoLabel'] + ' </div>');
                $('#documentNo').addClass('form-group has-error');
                $('#documentNo').focus();
            } else if ($('#invoiceTitle').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalTitleLabel'] + ' </div>');
                $('#invoiceTitle').addClass('form-group has-error');
                $('#invoiceTitle').focus();
            } else if ($('#journalDesc').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalDescLabel'] + ' </div>');
                $('#journalDesc').addClass('form-group has-error');
                $('#journalDesc').focus();
            } else if ($('#journalDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalDateLabel'] + ' </div>');
                $('#journalDate').addClass('form-group has-error');
                $('#journalDate').focus();
            } else if ($('#journalStartDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalStartDateLabel'] + ' </div>');
                $('#journalStartDate').addClass('form-group has-error');
                $('#journalStartDate').focus();
            } else if ($('#journalEndDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalEndDateLabel'] + ' </div>');
                $('#journalEndDate').addClass('form-group has-error');
                $('#journalEndDate').focus();
            } else if ($('#journalAmount').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalAmountLabel'] + ' </div>');
                $('#journalAmount').addClass('form-group has-error');
                $('#journalAmount').focus();
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
                $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', journalReccurringId: $('#journalReccurringId').val(), journalReccurringTypeId: $('#journalReccurringTypeId').val(), documentNo: $('#documentNo').val(), journalTitle: $('#invoiceTitle').val(), journalDesc: $('#journalDesc').val(), journalDate: $('#journalDate').val(), journalStartDate: $('#journalStartDate').val(), journalEndDate: $('#journalEndDate').val(), journalAmount: $('#journalAmount').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
            if ($('#journalReccurringTypeId').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalReccurringTypeIdLabel'] + ' </div>');
                $('#journalReccurringTypeId').addClass('form-group has-error');
                $('#journalReccurringTypeId').focus();
            } else if ($('#documentNo').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['documentNoLabel'] + ' </div>');
                $('#documentNo').addClass('form-group has-error');
                $('#documentNo').focus();
            } else if ($('#invoiceTitle').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalTitleLabel'] + ' </div>');
                $('#invoiceTitle').addClass('form-group has-error');
                $('#invoiceTitle').focus();
            } else if ($('#journalDesc').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalDescLabel'] + ' </div>');
                $('#journalDesc').addClass('form-group has-error');
                $('#journalDesc').focus();
            } else if ($('#journalDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalDateLabel'] + ' </div>');
                $('#journalDate').addClass('form-group has-error');
                $('#journalDate').focus();
            } else if ($('#journalStartDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalStartDateLabel'] + ' </div>');
                $('#journalStartDate').addClass('form-group has-error');
                $('#journalStartDate').focus();
            } else if ($('#journalEndDate').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalEndDateLabel'] + ' </div>');
                $('#journalEndDate').addClass('form-group has-error');
                $('#journalEndDate').focus();
            } else if ($('#journalAmount').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['requiredTextLabel']) + ' : ' + leafTranslation['journalAmountLabel'] + ' </div>');
                $('#journalAmount').addClass('form-group has-error');
                $('#journalAmount').focus();
            } else {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
                $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', journalReccurringId: $('#journalReccurringId').val(), journalReccurringTypeId: $('#journalReccurringTypeId').val(), documentNo: $('#documentNo').val(), journalTitle: $('#invoiceTitle').val(), journalDesc: $('#journalDesc').val(), journalDate: $('#journalDate').val(), journalStartDate: $('#journalStartDate').val(), journalEndDate: $('#journalEndDate').val(), journalAmount: $('#journalAmount').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                var value = $('#journalReccurringId').val();
                if (!value) {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html('<div class=\'alert\'>Please Contact Administrator</div>');
                } else {
                    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', journalReccurringId: $('#journalReccurringId').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
function resetRecord(leafId, url, urlList, urljournalrecurringdetail, securityToken, createAccess, updateAccess, deleteAccess) {
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
    $('#firstRecordButton').attr('onClick', "firstRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\", urljournalrecurringdetail,     \"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
    $('#previousRecordButton').removeClass();
    $('#previousRecordButton').addClass('btn btn-default disabled');
    $('#previousRecordButton').attr('onClick', '');
    $('#nextRecordButton').removeClass();
    $('#nextRecordButton').addClass('btn btn-default disabled');
    $('#nextRecordButton').attr('onClick', '');
    $('#endRecordButton').removeClass();
    $('#endRecordButton').addClass('btn btn-default');
    $('#endRecordButton').attr('onClick', "endRecord\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\", urljournalrecurringdetail,\"" + securityToken + "\",\"" + updateAccess + "\")");
    $('#journalReccurringId').val('');
    $('#journalReccurringTypeId').val('');
    $('#documentNo').val('');
    $('#invoiceTitle').val('');
    $('#journalDesc').val('');
    $('#journalDate').val('');
    $('#journalStartDate').val('');
    $('#journalEndDate').val('');
    $('#journalAmount').val('');

    $('#executeTime').val('');
}
function postRecord(leafId, url, urlList, urljournalrecurringdetail, SecurityToken) {
    var css = $('#postRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        return false;
    }
}
function firstRecord(leafId, url, urlList, urljournalrecurringdetail, securityToken, updateAccess, deleteAccess) {
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
                    $.ajax({type: 'POST', url: url, data: {method: 'read', journalReccurringId: data.firstRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                        }, success: function(data) {
                            if (data.success == true) {
                                $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'><img src=\'./images/icons/control-stop.png\'> ' + decodeURIComponent(t['firstButtonLabel']) + '</div>');
                                $('#journalReccurringId').val(data.data.journalReccurringId);
                                $('#journalReccurringTypeId').val(data.data.journalReccurringTypeId);
                                $('#documentNo').val(data.data.documentNo);
                                $('#invoiceTitle').val(data.data.journalTitle);
                                $('#journalDesc').val(data.data.journalDesc);
                                $('#journalDate').val(data.data.journalDate);
                                $('#journalStartDate').val(data.data.journalStartDate);
                                $('#journalEndDate').val(data.data.journalEndDate);
                                $('#journalAmount').val(data.data.journalAmount);
                                if (data.nextRecord > 0) {
                                    $('#previousRecordButton').removeClass();
                                    $('#previousRecordButton').addClass('btn btn-default  disabled');
                                    $('#previousRecordButton').attr('onClick', '');
                                    $('#nextRecordButton').removeClass();
                                    $('#nextRecordButton').addClass('btn btn-default');
                                    $('#nextRecordButton').attr('onClick', '');
                                    $('#nextRecordButton').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urljournalrecurringdetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
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
                                    $.ajax({type: 'POST', url: urljournalrecurringdetail, data: {method: 'read', journalReccurringId: data.firstRecord, output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                                            $('#infoPanel').empty();
                                            $('#infoPanel').html('');
                                            $('#infoPanel').html('<div class=\'alert alert-error  col-md-10\'><img src=\'./images/icons/smiley-roll.png\'> Loading...</div>');
                                        }, success: function(data) {
                                            if (data.success == true) {
                                                $('#infoPanel').empty();
                                                $('#infoPanel').html('');
                                                $('#infoPanel').html('<div class=\'alert alert-success  col-md-10\'><img src=\'./images/icons/smiley-lol.png\'> Loading Complete</div>');
                                                $('#tableBody').empty();
                                                $('#tableBody').html('');
                                                $('#tableBody').html(data.tableData);
                                            }
                                        }, error: function(data) {
                                            $('#infoPanel').empty();
                                            $('#infoPanel').html('');
                                            $('#infoPanel').html('<div class=\'alert alert-error\'><img src=\'./images/icons/smiley-roll-sweat.png\'> Error Could Load The Request Page .Info : [' + data.message + ']</div>');
                                        }});
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
function endRecord(leafId, url, urlList, urljournalrecurringdetail, securityToken, updateAccess, deleteAccess) {
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
                    $.ajax({type: 'POST', url: url, data: {method: 'read', journalReccurringId: data.lastRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                        }, success: function(data) {
                            if (data.success == true) {
                                $('#infoPanel').empty();
                                $('#infoPanel').html('');
                                $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'><img src=\'./images/icons/control-stop-180.png\'> ' + decodeURIComponent(t['endButtonLabel']) + '</div>');
                                $('#journalReccurringId').val(data.data.journalReccurringId);
                                $('#journalReccurringTypeId').val(data.data.journalReccurringTypeId);
                                $('#documentNo').val(data.data.documentNo);
                                $('#invoiceTitle').val(data.data.journalTitle);
                                $('#journalDesc').val(data.data.journalDesc);
                                $('#journalDate').val(data.data.journalDate);
                                $('#journalStartDate').val(data.data.journalStartDate);
                                $('#journalEndDate').val(data.data.journalEndDate);
                                $('#journalAmount').val(data.data.journalAmount);
                                if (data.lastRecord != 0) {
                                    $('#previousRecordButton').removeClass();
                                    $('#previousRecordButton').addClass('btn btn-default');
                                    $('#previousRecordButton').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urljournalrecurringdetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
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
                                    $.ajax({type: 'POST', url: urljournalrecurringdetail, data: {method: 'read', journalReccurringId: data.lastRecord, output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                                            $('#infoPanel').empty();
                                            $('#infoPanel').html('');
                                            $('#infoPanel').html('<div class=\'alert alert-error  col-md-10\'><img src=\'./images/icons/smiley-roll.png\'> Loading...</div>');
                                        }, success: function(data) {
                                            if (data.success == true) {
                                                $('#infoPanel').empty();
                                                $('#infoPanel').html('');
                                                $('#infoPanel').html('<div class=\'alert alert-success  col-md-10\'><img src=\'./images/icons/smiley-lol.png\'> Loading Complete</div>');
                                                $('#tableBody').empty();
                                                $('#tableBody').html('');
                                                $('#tableBody').html(data.tableData);
                                            }
                                        }, error: function(data) {
                                            $('#infoPanel').empty();
                                            $('#infoPanel').html('');
                                            $('#infoPanel').html('<div class=\'alert alert-error\'><img src=\'./images/icons/smiley-roll-sweat.png\'> Error Could Load The Request Page .Info : [' + data.message + ']</div>');
                                        }});
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
function previousRecord(leafId, url, urlList, urljournalrecurringdetail, securityToken, updateAccess, deleteAccess) {
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
            $.ajax({type: 'POST', url: url, data: {method: 'read', journalReccurringId: $('#previousRecordCounter').val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                }, success: function(data) {
                    if (data.success == true) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'><img src=\'./images/icons/control-180.png\'> ' + decodeURIComponent(t['previousButtonLabel']) + '</div>');
                        $('#journalReccurringId').val(data.data.journalReccurringId);
                        $('#journalReccurringTypeId').val(data.data.journalReccurringTypeId);
                        $('#documentNo').val(data.data.documentNo);
                        $('#invoiceTitle').val(data.data.journalTitle);
                        $('#journalDesc').val(data.data.journalDesc);
                        $('#journalDate').val(data.data.journalDate);
                        $('#journalStartDate').val(data.data.journalStartDate);
                        $('#journalEndDate').val(data.data.journalEndDate);
                        $('#journalAmount').val(data.data.journalAmount);
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
                            $('#nextRecordButton').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urljournalrecurringdetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
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
                        $.ajax({type: 'POST', url: urljournalrecurringdetail, data: {method: 'read', journalReccurringId: $('#previousRecordCounter').val(), output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                                $('#infoPanel').empty();
                                $('#infoPanel').html('');
                                $('#infoPanel').html('<div class=\'alert alert-error  col-md-10\'><img src=\'./images/icons/smiley-roll.png\'> Loading...</div>');
                            }, success: function(data) {
                                if (data.success == true) {
                                    $('#infoPanel').empty();
                                    $('#infoPanel').html('');
                                    $('#infoPanel').html('<div class=\'alert alert-success  col-md-10\'><img src=\'./images/icons/smiley-lol.png\'> Loading Complete</div>');
                                    $('#tableBody').empty();
                                    $('#tableBody').html('');
                                    $('#tableBody').html(data.tableData);
                                }
                            }, error: function(data) {
                                $('#infoPanel').empty();
                                $('#infoPanel').html('');
                                $('#infoPanel').html('<div class=\'alert alert-error\'><img src=\'./images/icons/smiley-roll-sweat.png\'> Error Could Load The Request Page .Info : [' + data.message + ']</div>');
                            }});
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
function nextRecord(leafId, url, urlList, urljournalrecurringdetail, securityToken, updateAccess, deleteAccess) {
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
            $.ajax({type: 'POST', url: url, data: {method: 'read', journalReccurringId: $('#nextRecordCounter').val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html('<div class=\'alert  col-md-12\'> <img src=\'./images/icons/smiley-roll.png\'> ' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                }, success: function(data) {
                    if (data.success == true) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'><img src=\'./images/icons/control.png\'> ' + decodeURIComponent(t['nextButtonLabel']) + '</div>');
                        $('#journalReccurringId').val(data.data.journalReccurringId);
                        $('#journalReccurringTypeId').val(data.data.journalReccurringTypeId);
                        $('#documentNo').val(data.data.documentNo);
                        $('#invoiceTitle').val(data.data.journalTitle);
                        $('#journalDesc').val(data.data.journalDesc);
                        $('#journalDate').val(data.data.journalDate);
                        $('#journalStartDate').val(data.data.journalStartDate);
                        $('#journalEndDate').val(data.data.journalEndDate);
                        $('#journalAmount').val(data.data.journalAmount);
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
                            $('#previousRecordButton').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urljournalrecurringdetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
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
                        $.ajax({type: 'POST', url: urljournalrecurringdetail, data: {method: 'read', journalReccurringId: $('#nextRecordCounter').val(), output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                                $('#infoPanel').empty();
                                $('#infoPanel').html('');
                                $('#infoPanel').html('<div class=\'alert alert-error  col-md-10\'><img src=\'./images/icons/smiley-roll.png\'> Loading...</div>');
                            }, success: function(data) {
                                if (data.success == true) {
                                    $('#infoPanel').empty();
                                    $('#infoPanel').html('');
                                    $('#infoPanel').html('<div class=\'alert alert-success  col-md-10\'><img src=\'./images/icons/smiley-lol.png\'> Loading Complete</div>');
                                    $('#tableBody').empty();
                                    $('#tableBody').html('');
                                    $('#tableBody').html(data.tableData);
                                }
                            }, error: function(data) {
                                $('#infoPanel').empty();
                                $('#infoPanel').html('');
                                $('#infoPanel').html('<div class=\'alert alert-error\'><img src=\'./images/icons/smiley-roll-sweat.png\'> Error Could Load The Request Page .Info : [' + data.message + ']</div>');
                            }});
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
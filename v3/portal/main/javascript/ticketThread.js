function showGrid(leafId, page, securityToken, offset, limit, loadingText, loadingCompleteText, loadingErrorText, type) {
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            if (type == 1) {
                span = 12;
            } else {
                span = 11;
            }
            $('#infoPanel').html('<div class=\'alert span' + span + '\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '....</div>');
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();

                $('#centerViewport').html('<div class=\'alert alert-error  col-md-12\'><img src=\'./images/icons/smiley-roll.png\'>' + data.message + '</div>');
            } else {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();

                $('#centerViewport').append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'><img src=\'./images/icons/smiley-lol.png\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
            }
        },
        error: function() {
            // failed request; give feedback to user
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
        }
    });
}
function ajaxQuerySearchAll(leafId, url, securityToken, loadingText, loadingCompleteText, loadingErrorText) {
    // unhide button search
    $('#clearSearch').removeClass();
    $('#clearSearch').addClass('btn');
    // unlimited for searching because  lazy paging.
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert col-md-12\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();

                $('#centerViewport').html('<div class=\'alert alert-error  col-md-12\'><img src=\'./images/icons/smiley-roll.png\'>' + data.message + '</div>');
            } else {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();

                $('#centerViewport').append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'><img src=\'./images/icons/smiley-lol.png\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + '<img src=\'./images/icons/binocular.png\'> ' + queryText + '</div>');
            }
        },
        error: function() {
            // failed request; give feedback to user
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
        }
    });
}
function ajaxQuerySearchAllCharacter(leafId, url, securityToken, character, loadingText, loadingCompleteText, loadingErrorText) {
    // unhide button search
    $('#clearSearch').removeClass();
    $('#clearSearch').addClass('btn');
    // unlimited for searching because  lazy paging.
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert  col-md-12\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();

                $('#centerViewport').html('<div class=\'alert alert-error  col-md-12\'><img src=\'./images/icons/smiley-roll.png\'>' + data.message + '</div>');
            } else {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();

                $('#centerViewport').append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'><img src=\'./images/icons/smiley-lol.png\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + ' <img src=\'./images/icons/arrow-skip.png\'> <img src=\'./images/icons/edit-size-up.png\'> [ <b>' + character + '</b> ] </div>');
            }
        },
        error: function() {
            // failed request; give feedback to user
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
        }
    });
}
function ajaxQuerySearchAllDate(leafId, url, securityToken, dateRangeStart, dateRangeEnd, dateRangeType, dateRangeExtraType, loadingText, loadingCompleteText, loadingErrorText) {
    // unhide button search
    $('#clearSearch').removeClass();
    $('#clearSearch').addClass('btn');
    // unlimited for searching because  lazy paging.
    if (dateRangeStart.length == 0) {
        dateRangeStart = $('#dateRangeStart').val()
    }
    if (dateRangeEnd.length == 0) {
        dateRangeEnd = $('#dateRangeEnd').val()
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
            // this is where we append a loading image
            $('#infoPanel').html('<div class=\'alert  col-md-12\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();

                $('#centerViewport').html('<div class=\'alert alert-error  col-md-12\'><img src=\'./images/icons/smiley-roll.png\'>' + data.message + '</div>');
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
                    case 'day':
                    case 'month':
                    case 'year':
                        strDate = dateRangeStart;
                        break;
                    case 'week':
                    case 'between':
                        strDate = dateRangeStart + " <img src=\'./images/icons/arrow-curve-000-left.png\'> " + dateRangeEnd;
                }
                $('#infoPanel').html('<div class=\'alert alert-success  col-md-12\'><img src=\'./images/icons/smiley-lol.png\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + '<img src=\'./images/icons/' + calendarPng + '\'> ' + strDate + '</div>');
            }
        },
        error: function() {
            // failed request; give feedback to user
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
        }
    });
}
function ajaxQuerySearchAllDateRange(leafId, url, securityToken, loadingText, loadingCompleteText, loadingErrorText) {
    ajaxQuerySearchAllDate(leafId, url, securityToken, $('#dateRangeStart').val(), $('#dateRangeEnd').val(), 'between', '', loadingText, loadingCompleteText, loadingErrorText);
}
function showForm(leafId, url, securityToken, loadingText, loadingCompleteText, loadingErrorText) {
    // unlimited for searching because  lazy paging.
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert  col-md-12\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();

                $('#centerViewport').html('<div class=\'alert alert-error  col-md-11\'><img src=\'./images/icons/smiley-roll.png\'>' + data.message + '</div>');
            } else {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();

                $('#centerViewport').append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-success  col-md-11\'><img src=\'./images/icons/smiley-lol.png\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
            }
        },
        error: function() {
            // failed request; give feedback to user
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
        }
    });
}
function showFormUpdate(leafId, url, securityToken, messageThreadId, loadingText, loadingCompleteText, loadingErrorText) {
    sleep(500);
    // unlimited for searching because  lazy paging.
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'read',
            type: 'form',
            messageThreadId: messageThreadId,
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            // this is where we append a loading image
            $('#infoPanel').html('<div class=\'alert  col-md-12\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();

                $('#centerViewport').html('<div class=\'alert alert-error  col-md-11\'><img src=\'./images/icons/smiley-roll.png\'>' + data.message + '</div>');
            } else {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();

                $('#centerViewport').append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-success  col-md-11\'><img src=\'./images/icons/smiley-lol.png\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
            }
        },
        error: function() {
            // failed request; give feedback to user
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
        }
    });
}
function showModalDelete(messageThreadId, ticketId, userIdFrom, userIdTo, ticketText, isSolve) {
    // clear first old record if exist
    $('#messageThreadIdPreview').val('');
    $('#messageThreadIdPreview').val(decodeURIComponent(messageThreadId));

    $('#ticketIdPreview').val('');
    $('#ticketIdPreview').val(decodeURIComponent(ticketId));

    $('#userIdFromPreview').val('');
    $('#userIdFromPreview').val(decodeURIComponent(userIdFrom));

    $('#userIdToPreview').val('');
    $('#userIdToPreview').val(decodeURIComponent(userIdTo));

    $('#ticketTextPreview').val('');
    $('#ticketTextPreview').val(decodeURIComponent(ticketText));

    $('#isSolvePreview').val('');
    $('#isSolvePreview').val(decodeURIComponent(isSolve));

    // open modal box
    showMeModal('deletePreview', 1);
}
function deleteGridRecord(leafId, url, urlList, securityToken, loadingText, loadingCompleteText, loadingErrorText, deleteRecordText) {
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'delete',
            output: 'json',
            messageThreadId: $('#messageThreadIdPreview').val(),
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert  col-md-12\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == true) {
                showMeModal('deletePreview', 0);
                showGrid(leafId, urlList, securityToken, 0, 10, loadingText, deleteRecordText, loadingErrorText, 2);
            } else if (data.success == false) {
                $('#infoPanel').html('\'<div class=alert alert-error col-md-12\'>' + data.message + '</div>');
            }
        },
        error: function() {
            // failed request; give feedback to user
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
            }
        }
    });
}
function deleteGridRecordCheckbox(leafId, url, urlList, securityToken, loadingText, loadingCompleteText, loadingErrorText, deleteRecordText, deleteCheckboxText) {
    var stringText = '';
    var counter = 0;
    $('input:checkbox[name="messageThreadId[]"]').each(function() {
        stringText = stringText + "&messageThreadId[]=" + $(this).val();
    });
//       $('input:checkbox[name="isDraft[]"]').each( function() { 
//           if($(this).is(':checked')) {
//               stringText=stringText+"&isDraft[]=true";
//           }else {
//               stringText=stringText+"&isDraft[]=false";
//           }
//           if($(this).is(':checked')) {
//               counter++;
//           }
//       });
//      $('input:checkbox[name="isDefault[]"]').each( function() { 
//           if($(this).is(':checked')) {
//               stringText=stringText+"&isDefault[]=true";
//           }else {
//               stringText=stringText+"&isDefault[]=false";
//           }
//           if($(this).is(':checked')) {
//               counter++;
//           }
//       });
//     $('input:checkbox[name="isNew[]"]').each( function() { 
//           if($(this).is(':checked')) {
//               stringText=stringText+"&isNew[]=true";
//           }else {
//               stringText=stringText+"&isNew[]=false";
//           }
//           if($(this).is(':checked')) {
//               counter++;
//           }
//     });
//     $('input:checkbox[name="isUpdate[]"]').each( function() { 
//           if($(this).is(':checked')) {
//               stringText=stringText+"&isUpdate[]=true";
//           }else {
//               stringText=stringText+"&isUpdate[]=false";
//           }
//           if($(this).is(':checked')) {
//               counter++;
//           }
//     });
    $('input:checkbox[name="isDelete[]"]').each(function() {
        // to cater old code extjs
        if ($(this).is(':checked')) {
            stringText = stringText + "&isDelete[]=true";
        } else {
            stringText = stringText + "&isDelete[]=false";
        }
        if ($(this).is(':checked')) {
            counter++;
        }
    });
//   $('input:checkbox[name="isActive[]"]').each( function() { 
//       if($(this).is(':checked')) {
//           stringText=stringText+"&isActive[]=true";
//       }else {
//         stringText=stringText+"&isActive[]=false";
//       }
//       if($(this).is(':checked')) {
//         counter++;
//       }
//    });
//    $('input:checkbox[name="isReview[]"]').each( function() { 
//       if($(this).is(':checked')) {
//           stringText=stringText+"&isReview[]=true";
//       }else {
//         stringText=stringText+"&isReview[]=false";
//       }
//       if($(this).is(':checked')) {
//         counter++;
//       }
//     });
//    $('input:checkbox[name="isPost[]"]').each( function() { 
//       if($(this).is(':checked')) {
//           stringText=stringText+"&isPost[]=true";
//       }else {
//           stringText=stringText+"&isPost[]=false";
//       }
//       if($(this).is(':checked')) {
//           counter++;
//       }
//    });
    if (counter == 0) {
        alert(decodeURIComponent(deleteCheckboxText))
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert  col-md-12\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == true) {
                showGrid(leafId, urlList, securityToken, 0, 10, loadingText, deleteRecordText, loadingErrorText, 2);
            } else if (data.success == false) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + data.message + '</div>');
            } else {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + data.message + '</div>');
            }
        },
        error: function() {
            // failed request; give feedback to user
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
        }
    });
}
function reportRequest(leafId, url, securityToken, mode, loadingText, loadingCompleteText, loadingErrorText) {
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert  col-md-12\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == true) {
                var path = './package/system/management/document/' + data.folder + '/' + data.filename;
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>Your Request File have been created</div>');
                window.open(path);
                // a hyper link will be given to click download..
            } else {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'>' + data.message + '</div>');
            }
        },
        error: function() {
            // failed request; give feedback to user
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-12\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
        }
    });
}
function auditRecord(leafId, url, securityToken, loadingText, loadingCompleteText, loadingErrorText) {
    var css = $('#auditRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        return false;
    }
}
function newRecord(leafId, url, urlList, securityToken, type, updateAccess, deleteAccess, loadingText, loadingCompleteText, loadingErrorText, requiredText, newRecordText, updateRecordText, deleteRecordText, deleteRecordMessage) {
    var css = $('#newRecordButton2').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#infoPanel').empty();
        $('#infoPanel').html('');
        if (type == 1) {
            // new record and continue.Reset Current Record
            if ($('#ticketId').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : ticketId First</div>');
                $('#ticketId').addClass('form-group has-error');
                $('#ticketId').focus();

            } else if ($('#userIdFrom').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : userIdFrom First</div>');
                $('#userIdFromForm').addClass('form-group has-error');
                $('#userIdFrom').focus();

            } else if ($('#userIdTo').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : userIdTo First</div>');
                $('#userIdToForm').addClass('form-group has-error');
                $('#userIdTo').focus();

            } else if ($('#ticketText').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : ticketText First</div>');
                $('#ticketTextForm').addClass('form-group has-error');
                $('#ticketText').focus();

            } else if ($('#isSolve').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : isSolve First</div>');
                $('#isSolveForm').addClass('form-group has-error');
                $('#isSolve').focus();

            } else {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>Form Complete</div>');
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        method: 'create',
                        output: 'json',
                        ticketId: $('#ticketId').val(),
                        userIdFrom: $('#userIdFrom').val(),
                        userIdTo: $('#userIdTo').val(),
                        ticketText: $('#ticketText').val(),
                        isSolve: $('#isSolve').val(),
                        securityToken: securityToken,
                        leafId: leafId
                    },
                    beforeSend: function() {
                        // this is where we append a loading image
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert  col-md-11\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                    },
                    success: function(data) {
                        // successful request; do something with the data
                        if (data.success == true) {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert alert-success  col-md-11\'><img src=\'./images/icons/smiley-lol.png\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
                            // reseting field value
                            $('#ticketId').val('');
                            $('#userIdFrom').val('');
                            $('#userIdTo').val('');
                            $('#ticketText').val('');
                            $('#isSolve').val('');
                        } else if (data.success == false) {
                            $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + data.message + '</div>');
                        }
                    },
                    error: function() {
                        // failed request; give feedback to user
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        if (data.success == false) {
                            $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                        }
                    }
                });
            }
        } else if (type == 2) {
            // new record and update  or delete record
            if ($('#ticketId').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : ticketId First</div>');
                $('#ticketId').addClass('form-group has-error');
                $('#ticketId').focus();

            } else if ($('#userIdFrom').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : userIdFrom First</div>');
                $('#userIdFrom').addClass('form-group has-error');
                $('#userIdFrom').focus();

            } else if ($('#userIdTo').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : userIdTo First</div>');
                $('#userIdTo').addClass('form-group has-error');
                $('#userIdTo').focus();

            } else if ($('#ticketText').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : ticketText First</div>');
                $('#ticketText').addClass('form-group has-error');
                $('#ticketText').focus();

            } else if ($('#isSolve').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : isSolve First</div>');
                $('#isSolve').addClass('form-group has-error');
                $('#isSolve').focus();

            } else {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>Form Complete</div>');
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        method: 'create',
                        output: 'json',
                        ticketId: $('#ticketId').val(),
                        userIdFrom: $('#userIdFrom').val(),
                        userIdTo: $('#userIdTo').val(),
                        ticketText: $('#ticketText').val(),
                        isSolve: $('#isSolve').val(),
                        securityToken: securityToken,
                        leafId: leafId
                    },
                    beforeSend: function() {
                        // this is where we append a loading image
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert  col-md-11\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                    },
                    success: function(data) {
                        // successful request; do something with the data
                        if (data.success == true) {
                            $('#infoPanel').html('<div class=\'alert alert-success  col-md-11\'><img src=\'./images/icons/smiley-lol.png\'>' + decodeURIComponent(newRecordText) + '</div>');
                            $('#messageThreadId').val(data.messageThreadId);
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
                                $('#updateRecordButton3').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + loadingErrorText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
                                $('#updateRecordButton4').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + loadingErrorText + "\"\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
                                $('#updateRecordButton5').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + loadingErrorText + "\"\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
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
                                $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + loadingErrorText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
                            } else {
                                $('#deleteRecordButton').removeClass();
                                $('#deleteRecordButton').addClass('btn btn-danger');
                                $('#deleteRecordButton').attr('onClick', '');
                            }
                        }
                    },
                    error: function() {
                        // failed request; give feedback to user
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        if (data.success == false) {
                            $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                        }
                    }
                });
            }
        } else if (type == 5) {
            //New Record and listing
            if ($('#ticketId').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : ticketId First</div>');
                $('#ticketId').addClass('form-group has-error');
                $('#ticketId').focus();

            } else if ($('#userIdFrom').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : userIdFrom First</div>');
                $('#userIdFrom').addClass('form-group has-error');
                $('#userIdFrom').focus();

            } else if ($('#userIdTo').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : userIdTo First</div>');
                $('#userIdTo').addClass('form-group has-error');
                $('#userIdTo').focus();

            } else if ($('#ticketText').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : ticketText First</div>');
                $('#ticketText').addClass('form-group has-error');
                $('#ticketText').focus();

            } else if ($('#isSolve').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : isSolve First</div>');
                $('#isSolve').addClass('form-group has-error');
                $('#isSolve').focus();

            } else {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        method: 'create',
                        output: 'json',
                        ticketId: $('#ticketId').val(),
                        userIdFrom: $('#userIdFrom').val(),
                        userIdTo: $('#userIdTo').val(),
                        ticketText: $('#ticketText').val(),
                        isSolve: $('#isSolve').val(),
                        securityToken: securityToken,
                        leafId: leafId
                    },
                    beforeSend: function() {
                        // this is where we append a loading image
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert  col-md-11\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                    },
                    success: function(data) {
                        // successful request; do something with the data
                        if (data.success == true) {
                            showGrid(leafId, urlList, securityToken, 0, 10, loadingText, newRecordText, loadingErrorText, 2);
                        } else {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + data.message + '</div>');
                        }
                    },
                    error: function() {
                        // failed request; give feedback to user
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                    }
                });
            }
            showMeDiv('tableDate', 0);
            showMeDiv('formEntry', 1);
        }
    }
}
function updateRecord(leafId, url, urlList, securityToken, type, deleteAccess, loadingText, loadingCompleteText, loadingErrorText, requiredText, newRecordText, updateRecordText, deleteRecordText, deleteRecordMessage) {
    var css = $('#updateRecordButton2').attr('class');
    if (css.search('disabled') > 0) {
        // access denied
    } else {
        $('#infoPanel').empty();
        $('#infoPanel').html('');
        if (type == 1) {
            // update record and continue
            if ($('#ticketId').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' :ticketId</div>');
                $('#ticketId').addClass('form-group has-error');
                $('#ticketId').focus();
            } else if ($('#userIdFrom').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : userIdFrom </div>');
                $('#userIdFrom').addClass('form-group has-error');
                $('#userIdFrom').focus();
            } else if ($('#userIdTo').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : userIdTo </div>');
                $('#userIdTo').addClass('form-group has-error');
                $('#userIdTo').focus();
            } else if ($('#ticketText').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : ticketText </div>');
                $('#ticketText').addClass('form-group has-error');
                $('#ticketText').focus();
            } else if ($('#isSolve').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : isSolve </div>');
                $('#isSolve').addClass('form-group has-error');
                $('#isSolve').focus();
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        method: 'save',
                        output: 'json',
                        messageThreadId: $('#messageThreadId').val(),
                        ticketId: $('#ticketId').val(),
                        userIdFrom: $('#userIdFrom').val(),
                        userIdTo: $('#userIdTo').val(),
                        ticketText: $('#ticketText').val(),
                        isSolve: $('#isSolve').val(),
                        securityToken: securityToken,
                        leafId: leafId
                    },
                    beforeSend: function() {
                        // this is where we append a loading image
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert  col-md-11\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                    },
                    success: function(data) {
                        // successful request; do something with the data
                        if (data.success == true) {
                            $('#infoPanel').html('<div class=\'alert alert-success  col-md-11\'><img src=\'./images/icons/smiley-lol.png\'>' + decodeURIComponent(updateRecordText) + '</div>');
                            if (deleteAccess == 1) {
                                $('#deleteRecordButton').removeClass();
                                $('#deleteRecordButton').addClass('btn btn-danger');
                                $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
                            } else {
                                $('#deleteRecordButton').removeClass();
                                $('#deleteRecordButton').addClass('btn btn-danger');
                                $('#deleteRecordButton').attr('onClick', '');
                            }
                        } else if (data.success == false) {
                            $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + data.message + '</div>');
                        }
                    },
                    error: function() {
                        // failed request; give feedback to user
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                    }
                });
            }
        } else if (type == 3) {
            // update record and listing
            if ($('#ticketId').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : ticketId First</div>');
                $('#ticketId').addClass('form-group has-error');
                $('#ticketId').focus();

            } else if ($('#userIdFrom').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : userIdFrom First</div>');
                $('#userIdFrom').addClass('form-group has-error');
                $('#userIdFrom').focus();

            } else if ($('#userIdTo').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : userIdTo First</div>');
                $('#userIdTo').addClass('form-group has-error');
                $('#userIdTo').focus();

            } else if ($('#ticketText').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : ticketText First</div>');
                $('#ticketText').addClass('form-group has-error');
                $('#ticketText').focus();

            } else if ($('#isSolve').val().length == 0) {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(requiredText) + ' : isSolve First</div>');
                $('#isSolve').addClass('form-group has-error');
                $('#isSolve').focus();

            } else {
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        method: 'save',
                        output: 'json',
                        messageThreadId: $('#messageThreadId').val(),
                        ticketId: $('#ticketId').val(),
                        userIdFrom: $('#userIdFrom').val(),
                        userIdTo: $('#userIdTo').val(),
                        ticketText: $('#ticketText').val(),
                        isSolve: $('#isSolve').val(),
                        securityToken: securityToken,
                        leafId: leafId
                    },
                    beforeSend: function() {
                        // this is where we append a loading image
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert  col-md-11\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                    },
                    success: function(data) {
                        // successful request; do something with the data
                        if (data.success == true) {
                            $('#infoPanel').html('<div class=\'alert alert-success  col-md-11\'><img src=\'./images/icons/smiley-lol.png\'>' + decodeURIComponent(t['loadingCompleteTextLabel']) + '</div>');
                            showGrid(leafId, urlList, securityToken, 0, 10, loadingText, updateRecordText, loadingErrorText, 2)
                        } else if (data.success == false) {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + data.message + '</div>');
                        }
                    },
                    error: function() {
                        // failed request; give feedback to user
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                    }
                });
            }
        }
    }
}
function deleteRecord(leafId, url, urlList, securityToken, deleteAccess, loadingText, loadingCompleteText, loadingErrorText, newRecordText, updateRecordText, deleteRecordText, deleteRecordMessage) {
    var css = $('#deleteRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (deleteAccess == 1) {
            if (confirm(decodeURIComponent(deleteRecordMessage))) {
                var value = $('#messageThreadId').val();
                if (!value) {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html('<div class=\'alert\'>Please Contact Administrator</div>');
                } else {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'delete',
                            output: 'json',
                            messageThreadId: $('#messageThreadId').val(),
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            // this is where we append a loading image
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert  col-md-11\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                        },
                        success: function(data) {
                            // successful request; do something with the data
                            if (data.success == true) {
                                showGrid(leafId, urlList, securityToken, 0, 10, loadingText, deleteRecordText, loadingErrorText, 2);
                            } else if (data.success == false) {
                                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + data.message + '</div>');
                            }
                        },
                        error: function() {
                            // failed request; give feedback to user
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                        }
                    });
                }
            } else {
                return false;
            }
        }
    }
}
function resetRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess, loadingText, loadingCompleteText, loadingErrorText, requiredText, newRecordText, updateRecordText, deleteRecordText, deleteRecordMessage, firstButton, endButton, previousButton, nextButton, resetRecordText) {
    $('#infoPanel').empty();
    $('#infoPanel').html('');
    $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'><img src=\'./images/icons/fruit-orange.png\'>' + decodeURIComponent(resetRecordText) + '</div>');
    $('#newRecordButton1').removeClass();
    $('#newRecordButton2').removeClass();
    $('#newRecordButton1').addClass('btn btn-success');
    $('#newRecordButton2').addClass('btn dropdown-toggle btn-success');
    $('#newRecordButton1').attr('onClick', '');
    $('#newRecordButton2').attr('onClick', '');
    $('#newRecordButton3').attr('onClick', "newRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + decodeURIComponent(t['loadingErrorTextLabel']) + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
    $('#newRecordButton4').attr('onClick', "newRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + decodeURIComponent(t['loadingErrorTextLabel']) + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
    $('#newRecordButton5').attr('onClick', "newecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",,\"" + decodeURIComponent(t['loadingErrorTextLabel']) + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
    $('#newRecordButton6').attr('onClick', "newRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 4 + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + decodeURIComponent(t['loadingErrorTextLabel']) + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
    $('#newRecordButton7').attr('onClick', "newRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 5 + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + decodeURIComponent(t['loadingErrorTextLabel']) + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
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
    $('#firstRecordButton').attr('onClick', "firstRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\",\"" + firstButton + "\",\"" + endButton + "\",\"" + previousButton + "\",\"" + nextButton + "\")");
    $('#previousRecordButton').removeClass();
    $('#previousRecordButton').addClass('btn btn-default disabled');
    $('#previousRecordButton').attr('onClick', '');
    $('#nextRecordButton').removeClass();
    $('#nextRecordButton').addClass('btn btn-default disabled');
    $('#nextRecordButton').attr('onClick', '');
    $('#endRecordButton').removeClass();
    $('#endRecordButton').addClass('btn btn-default');
    $('#endRecordButton').attr('onClick', "endRecord\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\",\"" + firstButton + "\",\"" + endButton + "\",\"" + previousButton + "\",\"" + nextButton + "\")");
    $('#messageThreadId').val('');
    $('#ticketId').val('');
    $('#userIdFrom').val('');
    $('#userIdTo').val('');
    $('#ticketText').val('');
    $('#isSolve').val('');

    $('#executeTime').val('');
}
function postRecord(leafId, url, urlList, SecurityToken, loadingText, loadingCompleteText, loadingErrorText, requiredText, newRecordText, updateRecordText, deleteRecordText, deleteRecordText) {
    var css = $('#postRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        return false;
    }
}
function firstRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess, loadingText, loadingCompleteText, loadingErrorText, requiredText, newRecordText, updateRecordText, deleteRecordText, deleteRecordMessage, firstButton, endButton, previousButton, nextButton) {
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
                // this is where we append a loading image
                $('#infoPanel').html('<div class=\'alert  col-md-11\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
            },
            success: function(data) {
                // successful request; do something with the data
                if (data.success == true) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'read',
                            messageThreadId: data.firstRecord,
                            output: 'json',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            // this is where we append a loading image
                            $('#infoPanel').html('<div class=\'alert  col-md-11\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                        },
                        success: function(data) {
                            // successful request; do something with the data
                            if (data.success == true) {
                                $('#infoPanel').html('<div class=\'alert alert-success  col-md-11\'><img src=\'./images/icons/control-stop.png\'>' + decodeURIComponent(firstButton) + '</div>');
                                // reseting field value
                                $('#messageThreadId').val(data.data.messageThreadId);
                                $('#ticketId').val(data.data.ticketId);
                                $('#userIdFrom').val(data.data.userIdFrom);
                                $('#userIdTo').val(data.data.userIdTo);
                                $('#ticketText').val(data.data.ticketText);
                                $('#isSolve').val(data.data.isSolve);
                                if (data.nextRecord > 0) {
                                    $('#previousRecordButton').removeClass();
                                    $('#previousRecordButton').addClass('btn btn-default  disabled');
                                    $('#previousRecordButton').attr('onClick', '');
                                    $('#nextRecordButton').removeClass();
                                    $('#nextRecordButton').addClass('btn btn-default');
                                    $('#nextRecordButton').attr('onClick', '');
                                    $('#nextRecordButton').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + loadingErrorText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\",\"" + firstButton + "\",\"" + endButton + "\",\"" + previousButton + "\",\"" + nextButton + "\")");
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
                                        $('#updateRecordButton3').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
                                        $('#updateRecordButton4').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
                                        $('#updateRecordButton5').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
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
                                        $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
                                    } else {
                                        $('#deleteRecordButton').removeClass();
                                        $('#deleteRecordButton').addClass('btn btn-danger');
                                        $('#deleteRecordButton').attr('onClick', '');
                                    }
                                }
                            }
                        },
                        error: function() {
                            // failed request; give feedback to user
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                        }
                    });
                } else {
                    $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + data.message + '</div>');
                }
            },
            error: function() {
                // failed request; give feedback to user
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html('<div class=\'alert alert-error spann11\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
            }
        });
    }
}
function endRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess, loadingText, loadingCompleteText, loadingErrorText, requiredText, newRecordText, updateRecordText, deleteRecordText, deleteRecordMessage, firstButton, endButton, previousButton, nextButton) {
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
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                // this is where we append a loading image
                $('#infoPanel').html('<div class=\'alert  col-md-11\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
            },
            success: function(data) {
                // successful request; do something with the data
                if (data.success == true) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'read',
                            messageThreadId: data.lastRecord,
                            output: 'json',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            // this is where we append a loading image
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html('<div class=\'alert  col-md-11\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                        },
                        success: function(data) {
                            // successful request; do something with the data
                            if (data.success == true) {
                                $('#infoPanel').empty();
                                $('#infoPanel').html('');
                                $('#infoPanel').html('<div class=\'alert alert-success  col-md-11\'><img src=\'./images/icons/control-stop-180.png\'>' + decodeURIComponent(endButton) + '</div>');
                                // reseting field value
                                $('#messageThreadId').val(data.data.messageThreadId);
                                $('#ticketId').val(data.data.ticketId);
                                $('#userIdFrom').val(data.data.userIdFrom);
                                $('#userIdTo').val(data.data.userIdTo);
                                $('#ticketText').val(data.data.ticketText);
                                $('#isSolve').val(data.data.isSolve);
                                if (data.lastRecord != 0) {
                                    $('#previousRecordButton').removeClass();
                                    $('#previousRecordButton').addClass('btn btn-default');
                                    $('#previousRecordButton').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + loadingErrorText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\",\"" + firstButton + "\",\"" + endButton + "\",\"" + previousButton + "\",\"" + nextButton + "\")");
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
                                        $('#updateRecordButton3').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
                                        $('#updateRecordButton4').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
                                        $('#updateRecordButton5').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
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
                                        $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
                                    } else {
                                        $('#deleteRecordButton').removeClass();
                                        $('#deleteRecordButton').addClass('btn btn-danger');
                                        $('#deleteRecordButton').attr('onClick', '');
                                    }
                                }
                            }
                        },
                        error: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            // failed request; give feedback to user
                            $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                        }
                    });
                } else {
                    $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>' + data.message + '</div>');
                }
            },
            error: function() {
                // failed request; give feedback to user
                $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
            }
        });
    }
}
function previousRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess, loadingText, loadingCompleteText, loadingErrorText, requiredText, newRecordText, updateRecordText, deleteRecordText, deleteRecordMessage, firstButton, endButton, previousButton, nextButton) {
    var css = $('#previousRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($('#previousRecordCounter').val() == '' || $('#previousRecordCounter').val() == undefined) {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>testingo</div>');
        }
        if (parseFloat($('#previousRecordCounter').val()) > 0 && parseFloat($('#previousRecordCounter').val()) < parseFloat($('#lastRecordCounter').val())) {
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'read',
                    messageThreadId: $('#previousRecordCounter').val(),
                    output: 'json',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    // this is where we append a loading image
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html('<div class=\'alert  col-md-11\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success == true) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert alert-success  col-md-11\'><img src=\'./images/icons/control-180.png\'>' + decodeURIComponent(previousButton) + '</div>');
                        $('#messageThreadId').val(data.data.messageThreadId);
                        $('#ticketId').val(data.data.ticketId);
                        $('#userIdFrom').val(data.data.userIdFrom);
                        $('#userIdTo').val(data.data.userIdTo);
                        $('#ticketText').val(data.data.ticketText);
                        $('#isSolve').val(data.data.isSolve);
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
                            $('#updateRecordButton3').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
                            $('#updateRecordButton4').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
                            $('#updateRecordButton5').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
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
                            $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
                        } else {
                            $('#deleteRecordButton').removeClass();
                            $('#deleteRecordButton').addClass('btn btn-danger');
                            $('#deleteRecordButton').attr('onClick', '');
                        }
                        $('#firstRecordCounter').val(data.firstRecord);
                        $('#previousRecordCounter').val(data.previousRecord);
                        $('#nextRecordCounter').val(data.nextRecord);
                        $('#lastRecordCounter').val(data.lastRecord);
                        if (parseFloat(data.nextRecord) != parseFloat(data.lastRecord)) {
                            $('#nextRecordButton').removeClass();
                            $('#nextRecordButton').addClass('btn btn-default');
                            $('#nextRecordButton').attr('onClick', '');
                            $('#nextRecordButton').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + loadingErrorText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\",\"" + firstButton + "\",\"" + endButton + "\",\"" + previousButton + "\",\"" + nextButton + "\")");
                        } else {
                            $('#nextRecordButton').removeClass();
                            $('#nextRecordButton').addClass('btn btn-default disabled');
                            $('#nextRecordButton').attr('onClick', '');
                        }
                        if (parseFloat(data.previousRecord) == 0) {
                            $('#infoPanel').html('<div class=\'alert alert-success  col-md-11\'><img src=\'./images/icons/exclamation.png\'>' + decodeURIComponent(firstButton) + '</div>');
                            $('#previousRecordButton').removeClass();
                            $('#previousRecordButton').addClass('btn btn-default disabled');
                            $('#previousRecordButton').attr('onClick', '');
                        }
                    }
                },
                error: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                }
            });
        } else {
            // debugging purpose only
        }
    }
}
function nextRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess, loadingText, loadingCompleteText, loadingErrorText, requiredText, newRecordText, updateRecordText, deleteRecordText, deleteRecordMessage, firstButton, endButton, previousButton, nextButton) {
    var css = $('#nextRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($('#nextRecordCounter').val() == '' || $('#nextRecordCounter').val() == undefined) {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'>sdfd</div>');
        }
        if (parseFloat($('#nextRecordCounter').val()) < parseFloat($('#lastRecordCounter').val())) {
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'read',
                    messageThreadId: $('#nextRecordCounter').val(),
                    output: 'json',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    // this is where we append a loading image
                    $('#infoPanel').html('<div class=\'alert  col-md-11\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success == true) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html('<div class=\'alert alert-success  col-md-11\'><img src=\'./images/icons/control.png\'>' + decodeURIComponent(nextButton) + '</div>');
                        $('#messageThreadId').val(data.data.messageThreadId);
                        $('#ticketId').val(data.data.ticketId);
                        $('#userIdFrom').val(data.data.userIdFrom);
                        $('#userIdTo').val(data.data.userIdTo);
                        $('#ticketText').val(data.data.ticketText);
                        $('#isSolve').val(data.data.isSolve);
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
                            $('#updateRecordButton3').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
                            $('#updateRecordButton4').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
                            $('#updateRecordButton5').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
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
                            $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\")");
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
                            $('#previousRecordButton').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\",\"" + loadingText + "\",\"" + loadingCompleteText + "\",\"" + loadingErrorText + "\",\"" + requiredText + "\",\"" + newRecordText + "\",\"" + updateRecordText + "\",\"" + deleteRecordText + "\",\"" + deleteRecordMessage + "\",\"" + firstButton + "\",\"" + endButton + "\",\"" + previousButton + "\",\"" + nextButton + "\")");
                        } else {
                            $('#infoPanel').html('<div class=\'alert alert-success  col-md-11\'><img src=\'./images/icons/exclamation.png\'>' + decodeURIComponent(endButton) + '</div>');
                            $('#previousRecordButton').removeClass();
                            $('#previousRecordButton').addClass('btn btn-default disabled');
                            $('#previousRecordButton').attr('onClick', '');
                        }
                        if (parseFloat(data.nextRecord) == parseFloat(data.lastRecord)) {
                            $('#nextRecordButton').removeClass();
                            $('#nextRecordButton').addClass('btn btn-default disabled');
                            $('#nextRecordButton').attr('onClick', '');
                        }
                    }
                },
                error: function() {
                    // failed request; give feedback to user
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html('<div class=\'alert alert-error col-md-11\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                }
            });
        } else {
        }
    }
}

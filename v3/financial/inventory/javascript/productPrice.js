function getBusinessPartner(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'businessPartner'}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + data.message + "</span>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#businessPartnerId").empty();
                $("#businessPartnerId").html('');
                $("#businessPartnerId").html(data.data);
                $("#businessPartnerId").trigger("chosen:updated");
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</spanm>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function getItemGroup(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'itemGroup'}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + data.message + "</span>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#itemGroupId").empty();
                $("#itemGroupId").html('');
                $("#itemGroupId").html(data.data);
                $("#itemGroupId").trigger("chosen:updated");
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</spanm>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function getItem(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'item'}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + data.message + "</span>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#itemId").empty();
                $("#itemId").html('');
                $("#itemId").html(data.data);
                $("#itemId").trigger("chosen:updated");
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</spanm>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function getCountry(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'country'}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + data.message + "</span>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#countryId").empty();
                $("#countryId").html('');
                $("#countryId").html(data.data);
                $("#countryId").trigger("chosen:updated");
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</spanm>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function getProduct(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'product'}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + data.message + "</span>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#productId").empty();
                $("#productId").html('');
                $("#productId").html(data.data);
                $("#productId").trigger("chosen:updated");
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</spanm>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function checkDuplicate(leafId, page, securityToken) {
    if ($("#productPriceCode").val().length == 0) {
        alert(t['oddTextLabel']);
        return false;
    }
    $.ajax({type: 'GET', url: page, data: {productPriceCode: $("#productPriceCode").val(), method: 'duplicate', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
            }
        }, success: function(data) {
            if (data.success == true) {
                if (data.total != 0) {
                    $("#productPriceCode").empty();
                    $("#productPriceCode").val('');
                    $("#productPriceCode").focus();
                    $("#productPriceCodeForm").removeClass();
                    $("#productPriceCodeForm").addClass("col-md-12 form-group has-error");
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $("#infoPanel").html("<img src='./images/icons/status-busy.png'> " + t['codeDuplicateTextLabel']).delay(5000).fadeOut();
                } else {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $("#infoPanel").html("<img src='./images/icons/status.png'> " + t['codeAvailableTextLabel']).delay(5000).fadeOut();
                }
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
                $("#productPriceForm").removeClass();
                $("#productPriceForm").addClass("col-md-12 form-group has-error");
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function showGrid(leafId, page, securityToken, offset, limit, type) {
    $.ajax({type: 'POST', url: page, data: {offset: offset, limit: limit, method: 'read', type: 'list', detail: 'body', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
            } else {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                if (type == 1) {
                    $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                } else if (type == 2) {
                    $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['deleteRecordTextLabel']) + "</span>");
                }
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
                $(document).scrollTop();
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
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
    $.ajax({type: 'POST', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'list', detail: 'body', query: queryText, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
            } else {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("&nbsp;<img src='./images/icons/magnifier-zoom-actual-equal.png'> <b>" + decodeURIComponent(t['filterTextLabel']) + '</b>: ' + queryText + "");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
                $(document).scrollTop();
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function ajaxQuerySearchAllCharacter(leafId, url, securityToken, character) {
    $('#clearSearch').removeClass();
    $('#clearSearch').addClass('btn');
    $.ajax({type: 'POST', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'list', detail: 'body', securityToken: securityToken, leafId: leafId, character: character}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
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
                $(document).scrollTop();
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
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
    $.ajax({type: 'POST', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'list', detail: 'body', query: $('#query').val(), securityToken: securityToken, leafId: leafId, dateRangeStart: dateRangeStart, dateRangeEnd: dateRangeEnd, dateRangeType: dateRangeType}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + data.message + "</span>");
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
                $(document).scrollTop();
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
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
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
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
                $(document).scrollTop();
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function showFormUpdate(leafId, url, urlList, securityToken, productPriceId, updateAccess, deleteAccess) {
    sleep(500);
    $.ajax({type: 'POST', url: urlList, data: {method: 'read', type: 'form', productPriceId: productPriceId, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();
                $('#centerViewport').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
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
                $(document).scrollTop();
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function showModalDelete(productPriceId, businessPartnerId, itemGroupId, itemId, countryId, productId, documentNumber, referenceNumber, productPriceDate, productPriceValidStart, productPriceValidEnd, productPrice, productSellingPrice) {
    $('#productPriceIdPreview').val('');
    $('#productPriceIdPreview').val(decodeURIComponent(productPriceId));
    $('#businessPartnerIdPreview').val('');
    $('#businessPartnerIdPreview').val(decodeURIComponent(businessPartnerId));
    $('#itemGroupIdPreview').val('');
    $('#itemGroupIdPreview').val(decodeURIComponent(itemGroupId));
    $('#itemIdPreview').val('');
    $('#itemIdPreview').val(decodeURIComponent(itemId));
    $('#countryIdPreview').val('');
    $('#countryIdPreview').val(decodeURIComponent(countryId));
    $('#productIdPreview').val('');
    $('#productIdPreview').val(decodeURIComponent(productId));
    $('#documentNumberPreview').val('');
    $('#documentNumberPreview').val(decodeURIComponent(documentNumber));
    $('#referenceNumberPreview').val('');
    $('#referenceNumberPreview').val(decodeURIComponent(referenceNumber));
    $('#productPriceDatePreview').val('');
    $('#productPriceDatePreview').val(decodeURIComponent(productPriceDate));
    $('#productPriceValidStartPreview').val('');
    $('#productPriceValidStartPreview').val(decodeURIComponent(productPriceValidStart));
    $('#productPriceValidEndPreview').val('');
    $('#productPriceValidEndPreview').val(decodeURIComponent(productPriceValidEnd));
    $('#productPricePreview').val('');
    $('#productPricePreview').val(decodeURIComponent(productPrice));
    $('#productSellingPricePreview').val('');
    $('#productSellingPricePreview').val(decodeURIComponent(productSellingPrice));
    showMeModal('deletePreview', 1);
}
function deleteGridRecord(leafId, url, urlList, securityToken) {
    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', productPriceId: $('#productPriceIdPreview').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == true) {
                showMeModal('deletePreview', 0);
                showGrid(leafId, urlList, securityToken, 0, 10, 2);
            } else if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function deleteGridRecordCheckbox(leafId, url, urlList, securityToken) {
    var stringText = '';
    var counter = 0;
    $('input:checkbox[name="productPriceId[]"]').each(function() {
        stringText = stringText + "&productPriceId[]=" + $(this).val();
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
    $.ajax({type: 'GET', url: url, data: {method: 'updateStatus', output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == true) {
                showGrid(leafId, urlList, securityToken, 0, 10, 2);
            } else if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            } else {
                $('#infoPanel').empty()
                $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function reportRequest(leafId, url, securityToken, mode) {
    $.ajax({type: 'GET', url: url, data: {method: 'report', mode: mode, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == true) {
                var path="./v3/financial/inventory/document/" + data.folder + "/" + data.filename;
                $('#infoPanel').html("<span class='label label-success'>" + decodeURIComponent(t['requestFileTextLabel']) + "</span>");
                window.open(path);
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
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
function newRecord(leafId, url, urlList, securityToken, type, createAccess, updateAccess, deleteAccess) {
    var css = $('#newRecordButton2').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#infoPanel').empty();
        $('#infoPanel').html('');
        if (type == 1) {
            if ($('#businessPartnerId').val().length == 0) {
                $('#businessPartnerIdHelpMe').empty();
                $('#businessPartnerIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $('#businessPartnerId').data('chosen').activate_action();
                return false;
            }
            if ($('#itemGroupId').val().length == 0) {
                $('#itemGroupIdHelpMe').empty();
                $('#itemGroupIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemGroupIdLabel'] + " </span>");
                $('#itemGroupId').data('chosen').activate_action();
                return false;
            }
            if ($('#itemId').val().length == 0) {
                $('#itemIdHelpMe').empty();
                $('#itemIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemIdLabel'] + " </span>");
                $('#itemId').data('chosen').activate_action();
                return false;
            }
            if ($('#countryId').val().length == 0) {
                $('#countryIdHelpMe').empty();
                $('#countryIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + " </span>");
                $('#countryId').data('chosen').activate_action();
                return false;
            }
            if ($('#productId').val().length == 0) {
                $('#productIdHelpMe').empty();
                $('#productIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productIdLabel'] + " </span>");
                $('#productId').data('chosen').activate_action();
                return false;
            }
            if ($('#referenceNumber').val().length == 0) {
                $('#referenceNumberHelpMe').empty();
                $('#referenceNumberHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['referenceNumberLabel'] + " </span>");
                $('#referenceNumberForm').addClass('form-group has-error');
                $('#referenceNumber').focus();
                return false;
            }
            if ($('#productPriceDate').val().length == 0) {
                $('#productPriceDateHelpMe').empty();
                $('#productPriceDateHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceDateLabel'] + " </span>");
                $('#productPriceDateForm').addClass('form-group has-error');
                $('#productPriceDate').focus();
                return false;
            }
            if ($('#productPriceValidStart').val().length == 0) {
                $('#productPriceValidStartHelpMe').empty();
                $('#productPriceValidStartHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceValidStartLabel'] + " </span>");
                $('#productPriceValidStartForm').addClass('form-group has-error');
                $('#productPriceValidStart').focus();
                return false;
            }
            if ($('#productPriceValidEnd').val().length == 0) {
                $('#productPriceValidEndHelpMe').empty();
                $('#productPriceValidEndHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceValidEndLabel'] + " </span>");
                $('#productPriceValidEndForm').addClass('form-group has-error');
                $('#productPriceValidEnd').focus();
                return false;
            }
            if ($('#productPrice').val().length == 0) {
                $('#productPriceHelpMe').empty();
                $('#productPriceHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceLabel'] + " </span>");
                $('#productPriceForm').addClass('form-group has-error');
                $('#productPrice').focus();
                return false;
            }
            if ($('#productSellingPrice').val().length == 0) {
                $('#productSellingPriceHelpMe').empty();
                $('#productSellingPriceHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productSellingPriceLabel'] + " </span>");
                $('#productSellingPriceForm').addClass('form-group has-error');
                $('#productSellingPrice').focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', businessPartnerId: $('#businessPartnerId').val(), itemGroupId: $('#itemGroupId').val(), itemId: $('#itemId').val(), countryId: $('#countryId').val(), productId: $('#productId').val(), referenceNumber: $('#referenceNumber').val(), productPriceDate: $('#productPriceDate').val(), productPriceValidStart: $('#productPriceValidStart').val(), productPriceValidEnd: $('#productPriceValidEnd').val(), productPrice: $('#productPrice').val(), productSellingPrice: $('#productSellingPrice').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }, success: function(data) {
                    if (data.success == true) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>").delay(1000).fadeOut();
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                        $('#businessPartnerId').val('');
                        $('#businessPartnerId').trigger("chosen:updated");
                        $('#businessPartnerIdHelpMe').empty();
                        $('#businessPartnerIdHelpMe').html('');
                        $('#itemGroupId').val('');
                        $('#itemGroupId').trigger("chosen:updated");
                        $('#itemGroupIdHelpMe').empty();
                        $('#itemGroupIdHelpMe').html('');
                        $('#itemId').val('');
                        $('#itemId').trigger("chosen:updated");
                        $('#itemIdHelpMe').empty();
                        $('#itemIdHelpMe').html('');
                        $('#countryId').val('');
                        $('#countryId').trigger("chosen:updated");
                        $('#countryIdHelpMe').empty();
                        $('#countryIdHelpMe').html('');
                        $('#productId').val('');
                        $('#productId').trigger("chosen:updated");
                        $('#productIdHelpMe').empty();
                        $('#productIdHelpMe').html('');
                        $('#documentNumber').val('');
                        $('#documentNumber').val('');
                        $('#documentNumberHelpMe').empty();
                        $('#documentNumberHelpMe').html('');
                        $('#referenceNumber').val('');
                        $('#referenceNumber').val('');
                        $('#referenceNumberHelpMe').empty();
                        $('#referenceNumberHelpMe').html('');
                        $('#productPriceDate').val('');
                        $('#productPriceDateHelpMe').empty();
                        $('#productPriceDateHelpMe').html('');
                        $('#productPriceValidStart').val('');
                        $('#productPriceValidStartHelpMe').empty();
                        $('#productPriceValidStartHelpMe').html('');
                        $('#productPriceValidEnd').val('');
                        $('#productPriceValidEndHelpMe').empty();
                        $('#productPriceValidEndHelpMe').html('');
                        $('#productPrice').val('');
                        $('#productPriceHelpMe').empty();
                        $('#productPriceHelpMe').html('');
                        $('#productSellingPrice').val('');
                        $('#productSellingPriceHelpMe').empty();
                        $('#productSellingPriceHelpMe').html('');
                    } else if (data.success == false) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                    }
                }, error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                }});
        } else if (type == 2) {
            if ($('#businessPartnerId').val().length == 0) {
                $('#businessPartnerIdHelpMe').empty();
                $('#businessPartnerIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $('#businessPartnerId').data('chosen').activate_action();
                return false;
            }
            if ($('#itemGroupId').val().length == 0) {
                $('#itemGroupIdHelpMe').empty();
                $('#itemGroupIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemGroupIdLabel'] + " </span>");
                $('#itemGroupId').data('chosen').activate_action();
                return false;
            }
            if ($('#itemId').val().length == 0) {
                $('#itemIdHelpMe').empty();
                $('#itemIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemIdLabel'] + " </span>");
                $('#itemId').data('chosen').activate_action();
                return false;
            }
            if ($('#countryId').val().length == 0) {
                $('#countryIdHelpMe').empty();
                $('#countryIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + " </span>");
                $('#countryId').data('chosen').activate_action();
                return false;
            }
            if ($('#productId').val().length == 0) {
                $('#productIdHelpMe').empty();
                $('#productIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productIdLabel'] + " </span>");
                $('#productId').data('chosen').activate_action();
                return false;
            }
            if ($('#referenceNumber').val().length == 0) {
                $('#referenceNumberHelpMe').empty();
                $('#referenceNumberHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['referenceNumberLabel'] + " </span>");
                $('#referenceNumberForm').addClass('form-group has-error');
                $('#referenceNumber').focus();
                return false;
            }
            if ($('#productPriceDate').val().length == 0) {
                $('#productPriceDateHelpMe').empty();
                $('#productPriceDateHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceDateLabel'] + " </span>");
                $('#productPriceDateForm').addClass('form-group has-error');
                $('#productPriceDate').focus();
                return false;
            }
            if ($('#productPriceValidStart').val().length == 0) {
                $('#productPriceValidStartHelpMe').empty();
                $('#productPriceValidStartHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceValidStartLabel'] + " </span>");
                $('#productPriceValidStartForm').addClass('form-group has-error');
                $('#productPriceValidStart').focus();
                return false;
            }
            if ($('#productPriceValidEnd').val().length == 0) {
                $('#productPriceValidEndHelpMe').empty();
                $('#productPriceValidEndHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceValidEndLabel'] + " </span>");
                $('#productPriceValidEndForm').addClass('form-group has-error');
                $('#productPriceValidEnd').focus();
                return false;
            }
            if ($('#productPrice').val().length == 0) {
                $('#productPriceHelpMe').empty();
                $('#productPriceHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceLabel'] + " </span>");
                $('#productPriceForm').addClass('form-group has-error');
                $('#productPrice').focus();
                return false;
            }
            if ($('#productSellingPrice').val().length == 0) {
                $('#productSellingPriceHelpMe').empty();
                $('#productSellingPriceHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productSellingPriceLabel'] + " </span>");
                $('#productSellingPriceForm').addClass('form-group has-error');
                $('#productSellingPrice').focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', businessPartnerId: $('#businessPartnerId').val(), itemGroupId: $('#itemGroupId').val(), itemId: $('#itemId').val(), countryId: $('#countryId').val(), productId: $('#productId').val(), referenceNumber: $('#referenceNumber').val(), productPriceDate: $('#productPriceDate').val(), productPriceValidStart: $('#productPriceValidStart').val(), productPriceValidEnd: $('#productPriceValidEnd').val(), productPrice: $('#productPrice').val(), productSellingPrice: $('#productSellingPrice').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }, success: function(data) {
                    if (data.success == true) {
                        $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>");
                        $('#productPriceId').val(data.productPriceId);
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
                }, error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                }});
        } else if (type == 5) {
            if ($('#businessPartnerId').val().length == 0) {
                $('#businessPartnerIdHelpMe').empty();
                $('#businessPartnerIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $('#businessPartnerId').data('chosen').activate_action();
                return false;
            }
            if ($('#itemGroupId').val().length == 0) {
                $('#itemGroupIdHelpMe').empty();
                $('#itemGroupIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemGroupIdLabel'] + " </span>");
                $('#itemGroupId').data('chosen').activate_action();
                return false;
            }
            if ($('#itemId').val().length == 0) {
                $('#itemIdHelpMe').empty();
                $('#itemIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemIdLabel'] + " </span>");
                $('#itemId').data('chosen').activate_action();
                return false;
            }
            if ($('#countryId').val().length == 0) {
                $('#countryIdHelpMe').empty();
                $('#countryIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + " </span>");
                $('#countryId').data('chosen').activate_action();
                return false;
            }
            if ($('#productId').val().length == 0) {
                $('#productIdHelpMe').empty();
                $('#productIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productIdLabel'] + " </span>");
                $('#productId').data('chosen').activate_action();
                return false;
            }
            if ($('#referenceNumber').val().length == 0) {
                $('#referenceNumberHelpMe').empty();
                $('#referenceNumberHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['referenceNumberLabel'] + " </span>");
                $('#referenceNumberForm').addClass('form-group has-error');
                $('#referenceNumber').focus();
                return false;
            }
            if ($('#productPriceDate').val().length == 0) {
                $('#productPriceDateHelpMe').empty();
                $('#productPriceDateHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceDateLabel'] + " </span>");
                $('#productPriceDateForm').addClass('form-group has-error');
                $('#productPriceDate').focus();
                return false;
            }
            if ($('#productPriceValidStart').val().length == 0) {
                $('#productPriceValidStartHelpMe').empty();
                $('#productPriceValidStartHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceValidStartLabel'] + " </span>");
                $('#productPriceValidStartForm').addClass('form-group has-error');
                $('#productPriceValidStart').focus();
                return false;
            }
            if ($('#productPriceValidEnd').val().length == 0) {
                $('#productPriceValidEndHelpMe').empty();
                $('#productPriceValidEndHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceValidEndLabel'] + " </span>");
                $('#productPriceValidEndForm').addClass('form-group has-error');
                $('#productPriceValidEnd').focus();
                return false;
            }
            if ($('#productPrice').val().length == 0) {
                $('#productPriceHelpMe').empty();
                $('#productPriceHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceLabel'] + " </span>");
                $('#productPriceForm').addClass('form-group has-error');
                $('#productPrice').focus();
                return false;
            }
            if ($('#productSellingPrice').val().length == 0) {
                $('#productSellingPriceHelpMe').empty();
                $('#productSellingPriceHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productSellingPriceLabel'] + " </span>");
                $('#productSellingPriceForm').addClass('form-group has-error');
                $('#productSellingPrice').focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', businessPartnerId: $('#businessPartnerId').val(), itemGroupId: $('#itemGroupId').val(), itemId: $('#itemId').val(), countryId: $('#countryId').val(), productId: $('#productId').val(), referenceNumber: $('#referenceNumber').val(), productPriceDate: $('#productPriceDate').val(), productPriceValidStart: $('#productPriceValidStart').val(), productPriceValidEnd: $('#productPriceValidEnd').val(), productPrice: $('#productPrice').val(), productSellingPrice: $('#productSellingPrice').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }, success: function(data) {
                    if (data.success == true) {
                        showGrid(leafId, urlList, securityToken, 0, 10, 1);
                    } else {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html("<span class='label label-important'> <img src='./images/icons/smiley-roll-sweat.png'> " + data.message + "</span>");
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                    }
                }, error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                }});
        }
        showMeDiv('tableDate', 0);
        showMeDiv('formEntry', 1);
    }
}
function updateRecord(leafId, url, urlList, securityToken, type, deleteAccess) {
    var css = $('#updateRecordButton2').attr('class');
    if (css.search('disabled') > 0) {
    } else {
        $('#infoPanel').empty();
        $('#infoPanel').html('');
        if (type == 1) {
            if ($('#businessPartnerId').val().length == 0) {
                $('#businessPartnerIdHelpMe').empty();
                $('#businessPartnerIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $('#businessPartnerId').data('chosen').activate_action();
                return false;
            }
            if ($('#itemGroupId').val().length == 0) {
                $('#itemGroupIdHelpMe').empty();
                $('#itemGroupIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemGroupIdLabel'] + " </span>");
                $('#itemGroupId').data('chosen').activate_action();
                return false;
            }
            if ($('#itemId').val().length == 0) {
                $('#itemIdHelpMe').empty();
                $('#itemIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemIdLabel'] + " </span>");
                $('#itemId').data('chosen').activate_action();
                return false;
            }
            if ($('#countryId').val().length == 0) {
                $('#countryIdHelpMe').empty();
                $('#countryIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + " </span>");
                $('#countryId').data('chosen').activate_action();
                return false;
            }
            if ($('#productId').val().length == 0) {
                $('#productIdHelpMe').empty();
                $('#productIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productIdLabel'] + " </span>");
                $('#productId').data('chosen').activate_action();
                return false;
            }
            if ($('#referenceNumber').val().length == 0) {
                $('#referenceNumberHelpMe').empty();
                $('#referenceNumberHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['referenceNumberLabel'] + " </span>");
                $('#referenceNumberForm').addClass('form-group has-error');
                $('#referenceNumber').focus();
                return false;
            }
            if ($('#productPriceDate').val().length == 0) {
                $('#productPriceDateHelpMe').empty();
                $('#productPriceDateHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceDateLabel'] + " </span>");
                $('#productPriceDateForm').addClass('form-group has-error');
                $('#productPriceDate').focus();
                return false;
            }
            if ($('#productPriceValidStart').val().length == 0) {
                $('#productPriceValidStartHelpMe').empty();
                $('#productPriceValidStartHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceValidStartLabel'] + " </span>");
                $('#productPriceValidStartForm').addClass('form-group has-error');
                $('#productPriceValidStart').focus();
                return false;
            }
            if ($('#productPriceValidEnd').val().length == 0) {
                $('#productPriceValidEndHelpMe').empty();
                $('#productPriceValidEndHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceValidEndLabel'] + " </span>");
                $('#productPriceValidEndForm').addClass('form-group has-error');
                $('#productPriceValidEnd').focus();
                return false;
            }
            if ($('#productPrice').val().length == 0) {
                $('#productPriceHelpMe').empty();
                $('#productPriceHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceLabel'] + " </span>");
                $('#productPriceForm').addClass('form-group has-error');
                $('#productPrice').focus();
                return false;
            }
            if ($('#productSellingPrice').val().length == 0) {
                $('#productSellingPriceHelpMe').empty();
                $('#productSellingPriceHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productSellingPriceLabel'] + " </span>");
                $('#productSellingPriceForm').addClass('form-group has-error');
                $('#productSellingPrice').focus();
                return false;
            }
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', productPriceId: $('#productPriceId').val(), businessPartnerId: $('#businessPartnerId').val(), itemGroupId: $('#itemGroupId').val(), itemId: $('#itemId').val(), countryId: $('#countryId').val(), productId: $('#productId').val(), referenceNumber: $('#referenceNumber').val(), productPriceDate: $('#productPriceDate').val(), productPriceValidStart: $('#productPriceValidStart').val(), productPriceValidEnd: $('#productPriceValidEnd').val(), productPrice: $('#productPrice').val(), productSellingPrice: $('#productSellingPrice').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }, success: function(data) {
                    if (data.success == true) {
                        $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['updateRecordTextLabel']) + "</span>");
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
                        $('#infoPanel').empty()
                        $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                    }
                }, error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                }});
        } else if (type == 3) {
            if ($('#businessPartnerId').val().length == 0) {
                $('#businessPartnerIdHelpMe').empty();
                $('#businessPartnerIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $('#businessPartnerId').data('chosen').activate_action();
                return false;
            }
            if ($('#itemGroupId').val().length == 0) {
                $('#itemGroupIdHelpMe').empty();
                $('#itemGroupIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemGroupIdLabel'] + " </span>");
                $('#itemGroupId').data('chosen').activate_action();
                return false;
            }
            if ($('#itemId').val().length == 0) {
                $('#itemIdHelpMe').empty();
                $('#itemIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemIdLabel'] + " </span>");
                $('#itemId').data('chosen').activate_action();
                return false;
            }
            if ($('#countryId').val().length == 0) {
                $('#countryIdHelpMe').empty();
                $('#countryIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + " </span>");
                $('#countryId').data('chosen').activate_action();
                return false;
            }
            if ($('#productId').val().length == 0) {
                $('#productIdHelpMe').empty();
                $('#productIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productIdLabel'] + " </span>");
                $('#productId').data('chosen').activate_action();
                return false;
            }
            if ($('#referenceNumber').val().length == 0) {
                $('#referenceNumberHelpMe').empty();
                $('#referenceNumberHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['referenceNumberLabel'] + " </span>");
                $('#referenceNumberForm').addClass('form-group has-error');
                $('#referenceNumber').focus();
                return false;
            }
            if ($('#productPriceDate').val().length == 0) {
                $('#productPriceDateHelpMe').empty();
                $('#productPriceDateHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceDateLabel'] + " </span>");
                $('#productPriceDateForm').addClass('form-group has-error');
                $('#productPriceDate').focus();
                return false;
            }
            if ($('#productPriceValidStart').val().length == 0) {
                $('#productPriceValidStartHelpMe').empty();
                $('#productPriceValidStartHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceValidStartLabel'] + " </span>");
                $('#productPriceValidStartForm').addClass('form-group has-error');
                $('#productPriceValidStart').focus();
                return false;
            }
            if ($('#productPriceValidEnd').val().length == 0) {
                $('#productPriceValidEndHelpMe').empty();
                $('#productPriceValidEndHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceValidEndLabel'] + " </span>");
                $('#productPriceValidEndForm').addClass('form-group has-error');
                $('#productPriceValidEnd').focus();
                return false;
            }
            if ($('#productPrice').val().length == 0) {
                $('#productPriceHelpMe').empty();
                $('#productPriceHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productPriceLabel'] + " </span>");
                $('#productPriceForm').addClass('form-group has-error');
                $('#productPrice').focus();
                return false;
            }
            if ($('#productSellingPrice').val().length == 0) {
                $('#productSellingPriceHelpMe').empty();
                $('#productSellingPriceHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productSellingPriceLabel'] + " </span>");
                $('#productSellingPriceForm').addClass('form-group has-error');
                $('#productSellingPrice').focus();
                return false;
            }
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
            $.ajax({
            	type : 'POST',
            	url : url,
            	data : {
            		method : 'save',
            		output : 'json',
            		productPriceId : $('#productPriceId').val(),
            		businessPartnerId : $('#businessPartnerId').val(),
            		itemGroupId : $('#itemGroupId').val(),
            		itemId : $('#itemId').val(),
            		countryId : $('#countryId').val(),
            		productId : $('#productId').val(),
            		referenceNumber : $('#referenceNumber').val(),
            		productPriceDate : $('#productPriceDate').val(),
            		productPriceValidStart : $('#productPriceValidStart').val(),
            		productPriceValidEnd : $('#productPriceValidEnd').val(),
            		productPrice : $('#productPrice').val(),
            		productSellingPrice : $('#productSellingPrice').val(),
            		securityToken : securityToken,
            		leafId : leafId
            	},
            	beforeSend : function () {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }, success: function(data) {
                    if (data.success == true) {
                        $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                        showGrid(leafId, urlList, securityToken, 0, 10, 1);
                    } else if (data.success == false) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                    }
                }, error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                }});
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
                var value = $('#productPriceId').val();
                if (!value) {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-important'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "<span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                    return false;
                } else {
                    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', productPriceId: $('#productPriceId').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        }, success: function(data) {
                            if (data.success == true) {
                                showGrid(leafId, urlList, securityToken, 0, 10, 2);
                            } else if (data.success == false) {
                                $('#infoPanel').empty();
                                $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
                                if ($('#infoPanel').is(':hidden')) {
                                    $('#infoPanel').show();
                                }
                            }
                        }, error: function(xhr) {
                            $('#infoError').empty();
                            $('#infoError').html('');
                            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass();
                            $('#infoErrorRowFluid').addClass('row');
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
    $('#infoPanel').html("<span class='label label-important'><img src='./images/icons/fruit-orange.png'> " + decodeURIComponent(t['resetRecordTextLabel']) + "</span>").delay(1000).fadeOut();
    if ($('#infoPanel').is(':hidden')) {
        $('#infoPanel').show();
    }
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
    $('#firstRecordButton').attr('onClick', "firstRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
    $('#previousRecordButton').removeClass();
    $('#previousRecordButton').addClass('btn btn-default disabled');
    $('#previousRecordButton').attr('onClick', '');
    $('#nextRecordButton').removeClass();
    $('#nextRecordButton').addClass('btn btn-default disabled');
    $('#nextRecordButton').attr('onClick', '');
    $('#endRecordButton').removeClass();
    $('#endRecordButton').addClass('btn btn-default');
    $('#endRecordButton').attr('onClick', "endRecord\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\")");
    $("#productPriceId").val('');
    
    $("#businessPartnerId").val('');
    $('#businessPartnerId').trigger("chosen:updated");
    $("#itemGroupId").val('');
    $('#itemGroupId').trigger("chosen:updated");
    $("#itemId").val('');
    $('#itemId').trigger("chosen:updated");
    $("#countryId").val('');
    $('#countryId').trigger("chosen:updated");
    $("#productId").val('');
    $('#productId').trigger("chosen:updated");
    $("#documentNumber").val('');
    $("#referenceNumber").val('');
    $("#productPriceDate").val('');
    $("#productPriceValidStart").val('');
    $("#productPriceValidEnd").val('');
    $("#productPrice").val('');
    $("#productSellingPrice").val('');
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
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }, success: function(data) {
                var smileyRoll = './images/icons/smiley-roll.png';
                if (data.firstRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (data.success == true) {
                    $.ajax({type: 'POST', url: url, data: {method: 'read', productPriceId: data.firstRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        }, success: function(data) {
                            if (data.success == true) {
                                $('#productPriceId').val(data.data.productPriceId);
                                $('#businessPartnerId').val(data.data.businessPartnerId);
                                $('#businessPartnerId').trigger("chosen:updated");
                                $('#itemGroupId').val(data.data.itemGroupId);
                                $('#itemGroupId').trigger("chosen:updated");
                                $('#itemId').val(data.data.itemId);
                                $('#itemId').trigger("chosen:updated");
                                $('#countryId').val(data.data.countryId);
                                $('#countryId').trigger("chosen:updated");
                                $('#productId').val(data.data.productId);
                                $('#productId').trigger("chosen:updated");
                                $('#documentNumber').val(data.data.documentNumber);
                                $('#referenceNumber').val(data.data.referenceNumber);
                                var x = data.data.productPriceDate;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#productPriceDate').val(output);
                                var x = data.data.productPriceValidStart;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#productPriceValidStart').val(output);
                                var x = data.data.productPriceValidEnd;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#productPriceValidEnd').val(output);
                                $('#productPrice').val(data.data.productPrice);
                                $('#productSellingPrice').val(data.data.productSellingPrice);
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
                                $('#infoPanel').empty();
                                $('#infoPanel').html("&nbsp;<img src='./images/icons/control-stop.png'> " + decodeURIComponent(t['firstButtonLabel']) + " ");
                                if ($('#infoPanel').is(':hidden')) {
                                    $('#infoPanel').show();
                                }
                            }
                        }, error: function(xhr) {
                            $('#infoError').empty();
                            $('#infoError').html('');
                            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass();
                            $('#infoErrorRowFluid').addClass('row');
                        }});
                } else {
                    $('#infoPanel').empty();
                    $('#infoPanel').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll-sweat.png'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }
            }, error: function(xhr) {
                $('#infoError').empty();
                $('#infoError').html('');
                $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass();
                $('#infoErrorRowFluid').addClass('row');
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
                $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }, success: function(data) {
                var smileyRoll = './images/icons/smiley-roll.png';
                if (data.lastRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (data.success == true) {
                    $.ajax({type: 'POST', url: url, data: {method: 'read', productPriceId: data.lastRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        }, success: function(data) {
                            if (data.success == true) {
                                $('#productPriceId').val(data.data.productPriceId);
                                $('#businessPartnerId').val(data.data.businessPartnerId);
                                $('#businessPartnerId').trigger("chosen:updated");
                                $('#itemGroupId').val(data.data.itemGroupId);
                                $('#itemGroupId').trigger("chosen:updated");
                                $('#itemId').val(data.data.itemId);
                                $('#itemId').trigger("chosen:updated");
                                $('#countryId').val(data.data.countryId);
                                $('#countryId').trigger("chosen:updated");
                                $('#productId').val(data.data.productId);
                                $('#productId').trigger("chosen:updated");
                                $('#documentNumber').val(data.data.documentNumber);
                                $('#referenceNumber').val(data.data.referenceNumber);
                                var x = data.data.productPriceDate;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#productPriceDate').val(output);
                                var x = data.data.productPriceValidStart;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#productPriceValidStart').val(output);
                                var x = data.data.productPriceValidEnd;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#productPriceValidEnd').val(output);
                                $('#productPrice').val(data.data.productPrice);
                                $('#productSellingPrice').val(data.data.productSellingPrice);
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
                        }, error: function(xhr) {
                            $('#infoError').empty();
                            $('#infoError').html('');
                            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass();
                            $('#infoErrorRowFluid').addClass('row');
                        }});
                } else {
                    $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
                }
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("&nbsp;<img src='./images/icons/control-stop-180.png'> " + decodeURIComponent(t['endButtonLabel']) + " ");
            }, error: function(xhr) {
                $('#infoError').empty();
                $('#infoError').html('');
                $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass();
                $('#infoErrorRowFluid').addClass('row');
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
            $('#infoPanel').html("<span class='label label-important'>" + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }
        if (parseFloat($('#previousRecordCounter').val()) > 0 && parseFloat($('#previousRecordCounter').val()) < parseFloat($('#lastRecordCounter').val())) {
            $.ajax({type: 'POST', url: url, data: {method: 'read', productPriceId: $('#previousRecordCounter').val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }, success: function(data) {
                    if (data.success == true) {
                        $('#productPriceId').val(data.data.productPriceId);
                        $('#businessPartnerId').val(data.data.businessPartnerId);
                        $('#businessPartnerId').trigger("chosen:updated");
                        $('#itemGroupId').val(data.data.itemGroupId);
                        $('#itemGroupId').trigger("chosen:updated");
                        $('#itemId').val(data.data.itemId);
                        $('#itemId').trigger("chosen:updated");
                        $('#countryId').val(data.data.countryId);
                        $('#countryId').trigger("chosen:updated");
                        $('#productId').val(data.data.productId);
                        $('#productId').trigger("chosen:updated");
                        $('#documentNumber').val(data.data.documentNumber);
                        $('#referenceNumber').val(data.data.referenceNumber);
                        var x = data.data.productPriceDate;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#productPriceDate').val(output);
                        var x = data.data.productPriceValidStart;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#productPriceValidStart').val(output);
                        var x = data.data.productPriceValidEnd;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#productPriceValidEnd').val(output);
                        $('#productPrice').val(data.data.productPrice);
                        $('#productSellingPrice').val(data.data.productSellingPrice);
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
                        $(document).scrollTop();
                    }
                }, error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
            $('#infoPanel').html("<span class='label label-important'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }
        if (parseFloat($('#nextRecordCounter').val()) <= parseFloat($('#lastRecordCounter').val())) {
            $.ajax({type: 'POST', url: url, data: {method: 'read', productPriceId: $('#nextRecordCounter').val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }, success: function(data) {
                    if (data.success == true) {
                        $('#productPriceId').val(data.data.productPriceId);
                        $('#businessPartnerId').val(data.data.businessPartnerId);
                        $('#businessPartnerId').trigger("chosen:updated");
                        $('#itemGroupId').val(data.data.itemGroupId);
                        $('#itemGroupId').trigger("chosen:updated");
                        $('#itemId').val(data.data.itemId);
                        $('#itemId').trigger("chosen:updated");
                        $('#countryId').val(data.data.countryId);
                        $('#countryId').trigger("chosen:updated");
                        $('#productId').val(data.data.productId);
                        $('#productId').trigger("chosen:updated");
                        $('#documentNumber').val(data.data.documentNumber);
                        $('#referenceNumber').val(data.data.referenceNumber);
                        var x = data.data.productPriceDate;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#productPriceDate').val(output);
                        var x = data.data.productPriceValidStart;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#productPriceValidStart').val(output);
                        var x = data.data.productPriceValidEnd;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#productPriceValidEnd').val(output);
                        $('#productPrice').val(data.data.productPrice);
                        $('#productSellingPrice').val(data.data.productSellingPrice);
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
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                }});
        } else {
        }
    }
}
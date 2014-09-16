function getBusinessPartner(leafId, url, securityToken) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            from: 'purchaseInvoiceVoid.php',
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
            if (data.success === false) {
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + data.message + "</spam>");
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
                    .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row');
        }
    });
}
function getPurchaseInvoiceProject(leafId, url, securityToken) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            from: 'purchaseInvoiceVoid.php',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'purchaseInvoiceProject'
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
            if (data.success === false) {
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + data.message + "</spam>");
            } else {
                $("#purchaseInvoiceProjectId")
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
                    .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row');
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
            from: 'purchaseInvoiceVoid.php',
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
            var $infoPanel = $('#infoPanel');
            var $centerViewPort = $('#centerViewport');
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            if (data.success === false) {
                $centerViewPort

                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + data.message + "</span>");
            } else {
                $centerViewPort
                        .html('').empty()

                        .append(data);
                $infoPanel
                        .html('').empty();
                if (type === 1) {
                    $infoPanel.html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                } else if (type === 2) {
                    $infoPanel.html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['deleteRecordTextLabel']) + "</span>");
                }
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
                    .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row');
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
            from: 'purchaseInvoiceVoid.php',
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
            var $centerViewPort = $('#centerViewport');
            var smileyRoll = './images/icons/smiley-roll.png';
            if (data.success === false) {
                $centerViewPort
                        .html('').empty()

                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + data.message + "</span>");
            } else {
                $centerViewPort
                        .html('').empty()

                        .append(data);
                $infoPanel
                        .html('').empty()
                        .html("&nbsp;<img src=''> <b>" + decodeURIComponent(t['filterTextLabel']) + '</b>: ' + queryText + "");
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
                    .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row');
        }
    });
}
function ajaxQuerySearchAllCharacter(leafId, url, securityToken, character) {
    $('#clearSearch')
            .removeClass().addClass('btn');
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'list',
            detail: 'body',
            from: 'purchaseInvoiceVoid.php',
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
            var $infoPanel = $('#infoPanel');
            var $centerViewPort = $('#centerViewport');
            var smileyRoll = './images/icons/smiley-roll.png';
            var zoomIcon = './images/icons/magnifier-zoom-actual-equal.png';
            if (data.success === false) {
                $centerViewPort
                        .html('').empty()

                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + data.message + "</span>");
            } else {
                $centerViewPort
                        .html('').empty()

                        .append(data);
                $infoPanel
                        .html('').empty()
                        .html("&nbsp;<img src='" + zoomIcon + "'> <b>" + decodeURIComponent(t['filterTextLabel']) + "</b>: " + character + " ");
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
                    .html('').html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row');
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
            from: 'purchaseInvoiceVoid.php',
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
            var $infoPanel = $('#infoPanel');
            var $centerViewPort = $('#centerViewport');
            var betweenIcon = './images/icons/arrow-curve-000-left.png';
            var smileyRoll = './images/icons/smiley-roll.png';
            if (data.success === false) {
                $centerViewPort

                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + data.message + "</span>");
            } else {
                $centerViewPort

                        .html('').empty()
                        .append(data);
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
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row');
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
            from: 'purchaseInvoiceVoid.php',
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
            if (data.success === false) {
                $centerViewPort

                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + data.message + "</span>");
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
                $(document).scrollTop();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row');
        }
    });
}
function showFormUpdate(leafId, url, urlList, securityToken, purchaseInvoiceId, updateAccess, deleteAccess) {
    sleep(500);

    $.ajax({
        type: 'POST',
        url: urlList,
        data: {
            method: 'read',
            type: 'form',
            from: 'purchaseInvoiceVoid.php',
            purchaseInvoiceId: purchaseInvoiceId,
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
            if (data.success === false) {
                $centerViewPort

                        .html('').empty()
                        .html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + data.message + "</span>");
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
                $(document).scrollTop();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row');
        }
    });
}
function showModalDelete(purchaseInvoiceId, businessPartnerId, documentNumber, referenceNumber, purchaseInvoiceAmount, purchaseInvoiceDate, purchaseInvoiceReminderDate, purchaseInvoiceCreditTerm, purchaseInvoiceDescription) {
    // clear first old record if exist
    $('#purchaseInvoiceIdPreview').val('').val(decodeURIComponent(purchaseInvoiceId));

    $('#businessPartnerIdPreview').val('').val(decodeURIComponent(businessPartnerId));


    $('#documentNumberPreview').val('').val(decodeURIComponent(documentNumber));

    $('#referenceNumberPreview').val('').val(decodeURIComponent(referenceNumber));

    $('#purchaseInvoiceAmountPreview').val('').val(decodeURIComponent(purchaseInvoiceAmount));

    $('#purchaseInvoiceDatePreview').val('').val(decodeURIComponent(purchaseInvoiceDate));

    $('#purchaseInvoiceReminderDatePreview').val('').val(decodeURIComponent(purchaseInvoiceReminderDate));

    $('#purchaseInvoiceCreditTermPreview').val('').val(decodeURIComponent(purchaseInvoiceCreditTerm));

    $('#purchaseInvoiceDescriptionPreview').val('').val(decodeURIComponent(purchaseInvoiceDescription));

    $('#isAllocatedPreview').val('').val(decodeURIComponent(isAllocated));

    showMeModal('deletePreview', 1);
}
function showModalDeleteDetail(purchaseInvoiceDetailId) {
    $('#purchaseInvoiceDetailIdPreview').val('').val(decodeURIComponent($("#purchaseInvoiceDetailId" + purchaseInvoiceDetailId).val()));

    $('#purchaseInvoiceProjectIdPreview').val('').val(decodeURIComponent($("#purchaseInvoiceProjectId" + purchaseInvoiceDetailId + " option:selected").text()));

    $('#countryIdPreview').val('').val(decodeURIComponent($("#countryId" + purchaseInvoiceDetailId + " option:selected").text()));

    $('#businessPartnerIdPreview').val('').val(decodeURIComponent($("#businessPartnerId" + purchaseInvoiceDetailId + " option:selected").text()));

    $('#chartOfAccountIdPreview').val('').val(decodeURIComponent($("#chartOfAccountId" + purchaseInvoiceDetailId + " option:selected").text()));

    $('#journalNumberPreview').val('').val(decodeURIComponent($("#journalNumber" + purchaseInvoiceDetailId).val()));

    $('#purchaseInvoiceDetailPrincipalAmountPreview').val('').val(decodeURIComponent($("#purchaseInvoiceDetailPrincipalAmount" + purchaseInvoiceDetailId).val()));

    $('#purchaseInvoiceDetailInterestAmountPreview').val('').val(decodeURIComponent($("#purchaseInvoiceDetailInterestAmount" + purchaseInvoiceDetailId).val()));

    $('#purchaseInvoiceDetailAmountPreview').val('').val(decodeURIComponent($("#purchaseInvoiceDetailAmount" + purchaseInvoiceDetailId).val()));

    showMeModal('deleteDetailPreview', 1);
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
                var path = "./v3/financial/accountPayable/document/" + folder + "/" + filename;
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
                    .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row');
        }
    });
}
function resetRecord(leafId, url, urlList, urlPurchaseInvoiceDetail, securityToken, createAccess, updateAccess, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var resetIcon = './images/icons/fruit-orange.png';
    $infoPanel
            .html('').empty()
            .html("<span class='label label-danger'><img src='" + resetIcon + "'> " + decodeURIComponent(t['resetRecordTextLabel']) + "</span>").delay(1000).fadeOut();
    if ($infoPanel.is(':hidden')) {
        $infoPanel.show();
    }
    $('#firstRecordButton')
            .removeClass().addClass('btn btn-info')
            .attr('onClick', "firstRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPurchaseInvoiceDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
    $('#previousRecordButton')
            .removeClass().addClass('btn btn-info disabled')
            .attr('onClick', '');
    $('#nextRecordButton')
            .removeClass().addClass('btn btn-info disabled')
            .attr('onClick', '');
    $('#endRecordButton')
            .removeClass().addClass('btn btn-info')
            .attr('onClick', "endRecord\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPurchaseInvoiceDetail + "\",,\"" + securityToken + "\",\"" + updateAccess + "\")");
    $("#purchaseInvoiceId").val('');
    $("#purchaseInvoiceIdHelpMe")
            .empty().html('');

    $("#businessPartnerId").val('');
    $("#businessPartnerIdHelpMe")
            .empty().html('');
    $('#businessPartnerId').trigger("chosen:updated");
    $("#purchaseInvoiceProjectId").val('');
    $("#purchaseInvoiceProjectIdHelpMe")
            .empty().html('');
    $('#purchaseInvoiceProjectId').trigger("chosen:updated");
    $("#documentNumber").val('');
    $("#documentNumberHelpMe")
            .empty().html('');
    $("#referenceNumber").val('');
    $("#referenceNumberHelpMe")
            .empty().html('');
    $("#purchaseInvoiceAmount").val('');
    $("#purchaseInvoiceAmountHelpMe")
            .empty().html('');
    $("#purchaseInvoiceDate").val('');
    $("#purchaseInvoiceDateHelpMe")
            .empty().html('');
    $("#purchaseInvoiceCreditTerm").val('');
    $("#purchaseInvoiceCreditTermHelpMe")
            .empty().html('');
    $("#purchaseInvoiceDescription").val('');
    $("#purchaseInvoiceDescriptionHelpMe")
            .empty().html('');
    $('#purchaseInvoiceDescription')
            .empty()
            .val('');
    $("#isAllocated").val('');
    $("#isAllocatedHelpMe")
            .empty().html('');
    $("#purchaseInvoiceDetailId9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('');

    $("#purchaseInvoiceProjectId9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            .trigger("chosen:updated");
    $("#purchaseInvoiceId9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            .trigger("chosen:updated");
    $("#countryId9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            .trigger("chosen:updated");
    $("#businessPartnerId9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            .trigger("chosen:updated");
    $("#chartOfAccountId9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('')
            .trigger("chosen:updated");
    $("#journalNumber9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('');
    $("#purchaseInvoiceDetailPrincipalAmount9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('');
    $("#purchaseInvoiceDetailInterestAmount9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('');
    $("#purchaseInvoiceDetailAmount9999")
            .prop("disabled", "true")
            .attr("disabled", "disabled")
            .val('');
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
function firstRecord(leafId, url, urlList, urlPurchaseInvoiceDetail, securityToken, updateAccess, deleteAccess) {
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
                from: 'purchaseInvoiceVoid.php',
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
                var firstRecord = data.firstRecord;
                var endRecord = data.endRecord;
                var lastRecord = data.lastRecord;
                var nextRecord = data.nextRecord;
                var previousRecord = data.previousRecord;
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                if (data.success === true) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'read',
                            purchaseInvoiceId: firstRecord,
                            output: 'json',
                            from: 'purchaseInvoiceVoid.php',
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
                            var x,
                                    output;
                            var success = data.success;
                            var $infoPanel = $('#infoPanel');
                            if (success === true) {
                                $('#purchaseInvoiceId').val(data.data.purchaseInvoiceId);
                                $('#businessPartnerId').val(data.data.businessPartnerId).trigger("chosen:updated");
                                ;
                                $('#purchaseInvoiceProjectId').val(data.data.purchaseInvoiceProjectId).trigger("chosen:updated");
                                ;
                                $('#documentNumber').val(data.data.documentNumber);
                                $('#referenceNumber').val(data.data.referenceNumber);
                                $('#purchaseInvoiceAmount').val(data.data.purchaseInvoiceAmount);
                                x = data.data.purchaseInvoiceDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#purchaseInvoiceDate').val(output);
                                $('#purchaseInvoiceCreditTerm').val(data.data.purchaseInvoiceCreditTerm);
                                $('#purchaseInvoiceDescription').val(data.data.purchaseInvoiceDescription);
                                $('#isAllocated').val(data.data.isAllocated);
                                $("#purchaseInvoiceProjectId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#purchaseInvoiceId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#countryId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#businessPartnerId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#chartOfAccountId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#purchaseInvoiceDetailPrincipalAmount9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('');
                                $("#purchaseInvoiceDetailInterestAmount9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('');
                                $("#purchaseInvoiceDetailAmount9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('');
                                $.ajax({
                                    type: 'POST',
                                    url: urlPurchaseInvoiceDetail,
                                    data: {
                                        method: 'read',
                                        purchaseInvoiceId: data.firstRecord,
                                        output: 'table',
                                        from: 'purchaseInvoiceVoid.php',
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
                                        if (data.success === true) {
                                            $infoPanel
                                                    .html('').empty()
                                                    .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                            $('#tableBody')
                                                    .html('').empty()
                                                    .html(tableData);
                                            $(".chzn-select").chosen({
                                                search_contains: true
                                            });
                                            $(".chzn-select-deselect").chosen({
                                                allow_single_deselect: true
                                            });
                                        }
                                    },
                                    error: function(xhr) {
                                        var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                        $('#infoError')
                                                .html('').empty()
                                                .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                        $('#infoErrorRowFluid')
                                                .removeClass().addClass('row');
                                    }
                                });
                                if (nextRecord > 0) {
                                    $('#previousRecordButton')
                                            .removeClass().addClass('btn btn-info  disabled')
                                            .attr('onClick', '');
                                    $('#nextRecordButton')
                                            .removeClass().addClass('btn btn-info')
                                            .attr('onClick', '')
                                            .attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPurchaseInvoiceDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
                                    $('#firstRecordCounter').val(firstRecord);
                                    $('#previousRecordCounter').val(previousRecord);
                                    $('#nextRecordCounter').val(nextRecord);
                                    $('#lastRecordCounter').val(lastRecord);
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
                                    .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid')
                                    .removeClass().addClass('row');
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
                        .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid')
                        .removeClass().addClass('row');
            }
        });
    }
}
function endRecord(leafId, url, urlList, urlPurchaseInvoiceDetail, securityToken, updateAccess, deleteAccess) {
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
                var success = data.success;
                var firstRecord = data.firstRecord;
                var endRecord = data.endRecord;
                var lastRecord = data.lastRecord;
                var nextRecord = data.nextRecord;
                var previousRecord = data.previousRecord;
                if (success === true) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'read',
                            purchaseInvoiceId: lastRecord,
                            output: 'json',
                            from: 'purchaseInvoiceVoid.php',
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
                            var x,
                                    output;
                            var success = data.success;
                            if (success === true) {
                                $('#purchaseInvoiceId').val(data.data.purchaseInvoiceId);
                                $('#businessPartnerId').val(data.data.businessPartnerId);
                                $('#businessPartnerId').trigger("chosen:updated");
                                $('#purchaseInvoiceProjectId').val(data.data.purchaseInvoiceProjectId);
                                $('#purchaseInvoiceProjectId').trigger("chosen:updated");
                                $('#documentNumber').val(data.data.documentNumber);
                                $('#referenceNumber').val(data.data.referenceNumber);
                                $('#purchaseInvoiceAmount').val(data.data.purchaseInvoiceAmount);
                                x = data.data.purchaseInvoiceDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#purchaseInvoiceDate').val(output);
                                $('#purchaseInvoiceCreditTerm').val(data.data.purchaseInvoiceCreditTerm);
                                $('#purchaseInvoiceDescription').val(data.data.purchaseInvoiceDescription);
                                $('#isAllocated').val(data.data.isAllocated);
                                $("#purchaseInvoiceProjectId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#purchaseInvoiceId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#countryId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#businessPartnerId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#chartOfAccountId9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('')
                                        .trigger("chosen:updated");
                                $("#purchaseInvoiceDetailPrincipalAmount9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('');
                                $("#purchaseInvoiceDetailInterestAmount9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('');
                                $("#purchaseInvoiceDetailAmount9999")
                                        .prop("disabled", "false")
                                        .removeAttr("disabled", "")
                                        .val('');
                                $.ajax({
                                    type: 'POST',
                                    url: urlPurchaseInvoiceDetail,
                                    data: {
                                        method: 'read',
                                        purchaseInvoiceId: lastRecord,
                                        output: 'table',
                                        from: 'purchaseInvoiceVoid.php',
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
                                            $(".chzn-select").chosen({
                                                search_contains: true
                                            });
                                            $(".chzn-select-deselect").chosen({
                                                allow_single_deselect: true
                                            });
                                        }
                                    },
                                    error: function(xhr) {
                                        var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                        $('#infoError')
                                                .html('').empty()
                                                .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                        $('#infoErrorRowFluid')
                                                .removeClass().addClass('row');
                                    }
                                });
                                if (lastRecord !== 0) {
                                    $('#previousRecordButton')
                                            .removeClass().addClass('btn btn-info')
                                            .attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPurchaseInvoiceDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
                                    $('#nextRecordButton')
                                            .removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                                    $('#firstRecordCounter').val(firstRecord);
                                    $('#previousRecordCounter').val(previousRecord);
                                    $('#nextRecordCounter').val(nextRecord);
                                    $('#lastRecordCounter').val(lastRecord);
                                }
                            }
                        },
                        error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError')
                                    .html('').empty()
                                    .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid')
                                    .removeClass().addClass('row');
                        }
                    });
                } else {
                    $infoPanel.html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
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
                        .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid')
                        .removeClass().addClass('row');
            }
        });
    }
}
function previousRecord(leafId, url, urlList, urlPurchaseInvoiceDetail, securityToken, updateAccess, deleteAccess) {
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
                    purchaseInvoiceId: $previousRecordCounter.val(),
                    output: 'json',
                    from: 'purchaseInvoiceVoid.php',
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
                    var x,
                            output;
                    var success = data.success;
                    var firstRecord = data.firstRecord;
                    var endRecord = data.endRecord;
                    var lastRecord = data.lastRecord;
                    var nextRecord = data.nextRecord;
                    var previousRecord = data.previousRecord;
                    var $infoPanel = $('#infoPanel');
                    if (success === true) {
                        $('#purchaseInvoiceId').val(data.data.purchaseInvoiceId);
                        $('#businessPartnerId').val(data.data.businessPartnerId);
                        $('#businessPartnerId').trigger("chosen:updated");
                        $('#purchaseInvoiceProjectId').val(data.data.purchaseInvoiceProjectId);
                        $('#purchaseInvoiceProjectId').trigger("chosen:updated");
                        $('#documentNumber').val(data.data.documentNumber);
                        $('#referenceNumber').val(data.data.referenceNumber);
                        $('#purchaseInvoiceAmount').val(data.data.purchaseInvoiceAmount);
                        x = data.data.purchaseInvoiceDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#purchaseInvoiceDate').val(output);
                        $('#purchaseInvoiceCreditTerm').val(data.data.purchaseInvoiceCreditTerm);
                        $('#purchaseInvoiceDescription').val(data.data.purchaseInvoiceDescription);
                        $('#isAllocated').val(data.data.isAllocated);
                        $("#purchaseInvoiceProjectId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#purchaseInvoiceId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#countryId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#businessPartnerId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#chartOfAccountId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#purchaseInvoiceDetailPrincipalAmount9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('');
                        $("#purchaseInvoiceDetailInterestAmount9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('');
                        $("#purchaseInvoiceDetailAmount9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('');
                        $.ajax({
                            type: 'POST',
                            url: urlPurchaseInvoiceDetail,
                            data: {
                                method: 'read',
                                purchaseInvoiceId: $('#previousRecordCounter').val(),
                                output: 'table',
                                from: 'purchaseInvoiceVoid.php',
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
                                if (data.success === true) {
                                    $infoPanel
                                            .html('').empty()
                                            .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                    $('#tableBody')
                                            .html('').empty()
                                            .html(tableData);
                                    $(".chzn-select").chosen({
                                        search_contains: true
                                    });
                                    $(".chzn-select-deselect").chosen({
                                        allow_single_deselect: true
                                    });
                                }
                            },
                            error: function(xhr) {
                                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                $('#infoError')
                                        .html('').empty()
                                        .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                $('#infoErrorRowFluid')
                                        .removeClass().addClass('row');
                            }
                        });
                    }
                    $('#firstRecordCounter').val(firstRecord);
                    $('#previousRecordCounter').val(previousRecord);
                    $('#nextRecordCounter').val(nextRecord);
                    $('#lastRecordCounter').val(lastRecord);
                    if (parseFloat(nextRecord) <= parseFloat(lastRecord)) {
                        $('#nextRecordButton')
                                .removeClass().addClass('btn btn-info')
                                .attr('onClick', '').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPurchaseInvoiceDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
                    } else {
                        $('#nextRecordButton')
                                .removeClass().addClass('btn btn-info disabled')
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
                            .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid')
                            .removeClass().addClass('row');
                }
            });
        }
    }
}
function nextRecord(leafId, url, urlList, urlPurchaseInvoiceDetail, securityToken, updateAccess, deleteAccess) {
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
                    purchaseInvoiceId: $nextRecordCounter.val(),
                    output: 'json',
                    from: 'purchaseInvoiceVoid.php',
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
                    var x,
                            output;
                    var success = data.success;
                    var firstRecord = data.firstRecord;
                    var lastRecord = data.lastRecord;
                    var nextRecord = data.nextRecord;
                    var previousRecord = data.previousRecord;
                    if (success === true) {
                        $('#purchaseInvoiceId').val(data.data.purchaseInvoiceId);
                        $('#businessPartnerId').val(data.data.businessPartnerId);
                        $('#businessPartnerId').trigger("chosen:updated");
                        $('#purchaseInvoiceProjectId').val(data.data.purchaseInvoiceProjectId);
                        $('#purchaseInvoiceProjectId').trigger("chosen:updated");
                        $('#documentNumber').val(data.data.documentNumber);
                        $('#referenceNumber').val(data.data.referenceNumber);
                        $('#purchaseInvoiceAmount').val(data.data.purchaseInvoiceAmount);
                        x = data.data.purchaseInvoiceDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#purchaseInvoiceDate').val(output);
                        $('#purchaseInvoiceCreditTerm').val(data.data.purchaseInvoiceCreditTerm);
                        $('#purchaseInvoiceDescription').val(data.data.purchaseInvoiceDescription);
                        $('#isAllocated').val(data.data.isAllocated);
                        $("#purchaseInvoiceProjectId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#purchaseInvoiceId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#countryId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#businessPartnerId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#chartOfAccountId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#purchaseInvoiceDetailPrincipalAmount9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('');
                        $("#purchaseInvoiceDetailInterestAmount9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('');
                        $("#purchaseInvoiceDetailAmount9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('');
                        $.ajax({
                            type: 'POST',
                            url: urlPurchaseInvoiceDetail,
                            data: {
                                method: 'read',
                                purchaseInvoiceId: $('#nextRecordCounter').val(),
                                output: 'table',
                                from: 'purchaseInvoiceVoid.php',
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
                                    $(".chzn-select").chosen({
                                        search_contains: true
                                    });
                                    $(".chzn-select-deselect").chosen({
                                        allow_single_deselect: true
                                    });
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
                                        .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                $('#infoErrorRowFluid')
                                        .removeClass().addClass('row');
                            }
                        });
                        $('#firstRecordCounter').val(firstRecord);
                        $('#previousRecordCounter').val(previousRecord);
                        $('#nextRecordCounter').val(nextRecord);
                        $('#lastRecordCounter').val(lastRecord);
                        if (parseFloat(previousRecord) > 0) {
                            $('#previousRecordButton')
                                    .removeClass().addClass('btn btn-info')
                                    .attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPurchaseInvoiceDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#previousRecordButton')
                                    .removeClass().addClass('btn btn-info disabled')
                                    .attr('onClick', '');
                        }
                        if (parseFloat(nextRecord) === 0) {
                            var exclamationIcon = './images/icons/exclamation.png';
                            $('#nextRecordButton')
                                    .removeClass().addClass('btn btn-info disabled')
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
                            .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid')
                            .removeClass().addClass('row');
                }
            });
        }
    }
}

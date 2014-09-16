function getProductBatch(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'productBatch'}, beforeSend: function() {
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
                $("#productBatchId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function getInvoice(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'invoice'}, beforeSend: function() {
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
                $("#invoiceId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function checkDuplicate(leafId, page, securityToken) {
    var $productResourcesCode = $("#productResourcesCode");
    if ($productResourcesCode.val().length === 0) {
        alert(t['oddTextLabel']);
        return false;
    }
    $.ajax({type: 'GET', url: page, data: {productResourcesCode: $productResourcesCode.val(), method: 'duplicate', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                    $("#productResourcesCode").val('').focus();
                    $("#productResourcesCodeForm").removeClass().addClass("form-group has-error");
                    $infoPanel.html('').empty().html("<img src='" + smileyRoll + "'> " + t['codeDuplicateTextLabel']).delay(5000).fadeOut();
                } else {
                    $infoPanel.html('').empty().html("<img src='" + smileyLol + "'> " + t['codeAvailableTextLabel']).delay(5000).fadeOut();
                }
            } else {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
                $("#productResourcesForm").removeClass().addClass("form-group has-error");
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
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $centerViewPort.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort.html('').empty().append(data);
            }
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("&nbsp;<img src=''> <b>" + decodeURIComponent(t['filterTextLabel']) + '</b>: ' + queryText + "");
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
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function showFormUpdate(leafId, url, urlList, securityToken, productResourcesId, updateAccess, deleteAccess) {
    sleep(500);
    $('a[rel=tooltip]').tooltip('hide');
    $.ajax({type: 'POST', url: urlList, data: {method: 'read', type: 'form', productResourcesId: productResourcesId, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                $('#newRecordButton1').removeClass().addClass('btn btn-success disabled');
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
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled');
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
function showModalDelete(productResourcesId, productBatchId, invoiceId, productResourcesTask, productResourcesEstimatedDate, productResourcesActualDate, productResourcesEstimatedEmployeeCost, productResourcesActualEmployeeCost, productResourcesEstimatedMachineCost, productResourcesActualMachineCost, productResourcesEstimatedAdditionalCost, productResourcesActualAdditionalCost, productResourcesEstimatedBillOfMaterialCost, productResourcesActualBillOfMaterialCost, productResourcesEstimatedTotalCost, productResourcesActualTotalCost) {
    $('#productResourcesIdPreview').val('').val(decodeURIComponent(productResourcesId));
    $('#productBatchIdPreview').val('').val(decodeURIComponent(productBatchId));
    $('#invoiceIdPreview').val('').val(decodeURIComponent(invoiceId));
    $('#productResourcesTaskPreview').val('').val(decodeURIComponent(productResourcesTask));
    $('#productResourcesEstimatedDatePreview').val('').val(decodeURIComponent(productResourcesEstimatedDate));
    $('#productResourcesActualDatePreview').val('').val(decodeURIComponent(productResourcesActualDate));
    $('#productResourcesEstimatedEmployeeCostPreview').val('').val(decodeURIComponent(productResourcesEstimatedEmployeeCost));
    $('#productResourcesActualEmployeeCostPreview').val('').val(decodeURIComponent(productResourcesActualEmployeeCost));
    $('#productResourcesEstimatedMachineCostPreview').val('').val(decodeURIComponent(productResourcesEstimatedMachineCost));
    $('#productResourcesActualMachineCostPreview').val('').val(decodeURIComponent(productResourcesActualMachineCost));
    $('#productResourcesEstimatedAdditionalCostPreview').val('').val(decodeURIComponent(productResourcesEstimatedAdditionalCost));
    $('#productResourcesActualAdditionalCostPreview').val('').val(decodeURIComponent(productResourcesActualAdditionalCost));
    $('#productResourcesEstimatedBillOfMaterialCostPreview').val('').val(decodeURIComponent(productResourcesEstimatedBillOfMaterialCost));
    $('#productResourcesActualBillOfMaterialCostPreview').val('').val(decodeURIComponent(productResourcesActualBillOfMaterialCost));
    $('#productResourcesEstimatedTotalCostPreview').val('').val(decodeURIComponent(productResourcesEstimatedTotalCost));
    $('#productResourcesActualTotalCostPreview').val('').val(decodeURIComponent(productResourcesActualTotalCost));
    showMeModal('deletePreview', 1);
}
function deleteGridRecord(leafId, url, urlList, securityToken) {
    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', productResourcesId: $('#productResourcesIdPreview').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function deleteGridRecordCheckbox(leafId, url, urlList, securityToken) {
    var stringText = '';
    var counter = 0;
    $('input:checkbox[name="productResourcesId[]"]').each(function() {
        stringText = stringText + "&productResourcesId[]=" + $(this).val();
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
                var path="./v3/financial/inventory/document/" + folder + "/" + filename;
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
    var $productBatchId = $('#productBatchId');
    var $invoiceId = $('#invoiceId');
    var $productResourcesTask = $('#productResourcesTask');
    var $productResourcesEstimatedDate = $('#productResourcesEstimatedDate');
    var $productResourcesActualDate = $('#productResourcesActualDate');
    var $productResourcesEstimatedEmployeeCost = $('#productResourcesEstimatedEmployeeCost');
    var $productResourcesActualEmployeeCost = $('#productResourcesActualEmployeeCost');
    var $productResourcesEstimatedMachineCost = $('#productResourcesEstimatedMachineCost');
    var $productResourcesActualMachineCost = $('#productResourcesActualMachineCost');
    var $productResourcesEstimatedAdditionalCost = $('#productResourcesEstimatedAdditionalCost');
    var $productResourcesActualAdditionalCost = $('#productResourcesActualAdditionalCost');
    var $productResourcesEstimatedBillOfMaterialCost = $('#productResourcesEstimatedBillOfMaterialCost');
    var $productResourcesActualBillOfMaterialCost = $('#productResourcesActualBillOfMaterialCost');
    var $productResourcesEstimatedTotalCost = $('#productResourcesEstimatedTotalCost');
    var $productResourcesActualTotalCost = $('#productResourcesActualTotalCost');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (type === 1) {
            if ($productBatchId.val().length === 0) {
                $('#productBatchIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productBatchIdLabel'] + " </span>");
                $productBatchId.data('chosen').activate_action();
                return false;
            }
            if ($invoiceId.val().length === 0) {
                $('#invoiceIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceIdLabel'] + " </span>");
                $invoiceId.data('chosen').activate_action();
                return false;
            }
            if ($productResourcesTask.val().length === 0) {
                $('#productResourcesTaskHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesTaskLabel'] + " </span>");
                $('#productResourcesTaskForm').removeClass().addClass('form-group has-error');
                $productResourcesTask.focus();
                return false;
            }
            if ($productResourcesEstimatedDate.val().length === 0) {
                $('#productResourcesEstimatedDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedDateLabel'] + " </span>");
                $('#productResourcesEstimatedDateForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedDate.focus();
                return false;
            }
            if ($productResourcesActualDate.val().length === 0) {
                $('#productResourcesActualDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualDateLabel'] + " </span>");
                $('#productResourcesActualDateForm').removeClass().addClass('form-group has-error');
                $productResourcesActualDate.focus();
                return false;
            }
            if ($productResourcesEstimatedEmployeeCost.val().length === 0) {
                $('#productResourcesEstimatedEmployeeCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedEmployeeCostLabel'] + " </span>");
                $('#productResourcesEstimatedEmployeeCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedEmployeeCost.focus();
                return false;
            }
            if ($productResourcesActualEmployeeCost.val().length === 0) {
                $('#productResourcesActualEmployeeCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualEmployeeCostLabel'] + " </span>");
                $('#productResourcesActualEmployeeCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualEmployeeCost.focus();
                return false;
            }
            if ($productResourcesEstimatedMachineCost.val().length === 0) {
                $('#productResourcesEstimatedMachineCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedMachineCostLabel'] + " </span>");
                $('#productResourcesEstimatedMachineCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedMachineCost.focus();
                return false;
            }
            if ($productResourcesActualMachineCost.val().length === 0) {
                $('#productResourcesActualMachineCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualMachineCostLabel'] + " </span>");
                $('#productResourcesActualMachineCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualMachineCost.focus();
                return false;
            }
            if ($productResourcesEstimatedAdditionalCost.val().length === 0) {
                $('#productResourcesEstimatedAdditionalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedAdditionalCostLabel'] + " </span>");
                $('#productResourcesEstimatedAdditionalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedAdditionalCost.focus();
                return false;
            }
            if ($productResourcesActualAdditionalCost.val().length === 0) {
                $('#productResourcesActualAdditionalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualAdditionalCostLabel'] + " </span>");
                $('#productResourcesActualAdditionalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualAdditionalCost.focus();
                return false;
            }
            if ($productResourcesEstimatedBillOfMaterialCost.val().length === 0) {
                $('#productResourcesEstimatedBillOfMaterialCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedBillOfMaterialCostLabel'] + " </span>");
                $('#productResourcesEstimatedBillOfMaterialCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedBillOfMaterialCost.focus();
                return false;
            }
            if ($productResourcesActualBillOfMaterialCost.val().length === 0) {
                $('#productResourcesActualBillOfMaterialCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualBillOfMaterialCostLabel'] + " </span>");
                $('#productResourcesActualBillOfMaterialCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualBillOfMaterialCost.focus();
                return false;
            }
            if ($productResourcesEstimatedTotalCost.val().length === 0) {
                $('#productResourcesEstimatedTotalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedTotalCostLabel'] + " </span>");
                $('#productResourcesEstimatedTotalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedTotalCost.focus();
                return false;
            }
            if ($productResourcesActualTotalCost.val().length === 0) {
                $('#productResourcesActualTotalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualTotalCostLabel'] + " </span>");
                $('#productResourcesActualTotalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualTotalCost.focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', productBatchId: $productBatchId.val(), invoiceId: $invoiceId.val(), productResourcesTask: $productResourcesTask.val(), productResourcesEstimatedDate: $productResourcesEstimatedDate.val(), productResourcesActualDate: $productResourcesActualDate.val(), productResourcesEstimatedEmployeeCost: $productResourcesEstimatedEmployeeCost.val(), productResourcesActualEmployeeCost: $productResourcesActualEmployeeCost.val(), productResourcesEstimatedMachineCost: $productResourcesEstimatedMachineCost.val(), productResourcesActualMachineCost: $productResourcesActualMachineCost.val(), productResourcesEstimatedAdditionalCost: $productResourcesEstimatedAdditionalCost.val(), productResourcesActualAdditionalCost: $productResourcesActualAdditionalCost.val(), productResourcesEstimatedBillOfMaterialCost: $productResourcesEstimatedBillOfMaterialCost.val(), productResourcesActualBillOfMaterialCost: $productResourcesActualBillOfMaterialCost.val(), productResourcesEstimatedTotalCost: $productResourcesEstimatedTotalCost.val(), productResourcesActualTotalCost: $productResourcesActualTotalCost.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                        $productBatchId.val('');
                        $productBatchId.trigger("chosen:updated");
                        $('#productBatchIdHelpMe').html('').empty();
                        $invoiceId.val('');
                        $invoiceId.trigger("chosen:updated");
                        $('#invoiceIdHelpMe').html('').empty();
                        $productResourcesTask.val('');
                        $('#productResourcesTaskForm').removeClass().addClass('form-group');
                        $('#productResourcesTaskHelpMe').html('').empty();
                        $productResourcesEstimatedDate.val('');
                        $('#productResourcesEstimatedDateHelpMe').html('').empty();
                        $productResourcesActualDate.val('');
                        $('#productResourcesActualDateHelpMe').html('').empty();
                        $productResourcesEstimatedEmployeeCost.val('');
                        $('#productResourcesEstimatedEmployeeCostHelpMe').html('').empty();
                        $productResourcesActualEmployeeCost.val('');
                        $('#productResourcesActualEmployeeCostHelpMe').html('').empty();
                        $productResourcesEstimatedMachineCost.val('');
                        $('#productResourcesEstimatedMachineCostHelpMe').html('').empty();
                        $productResourcesActualMachineCost.val('');
                        $('#productResourcesActualMachineCostHelpMe').html('').empty();
                        $productResourcesEstimatedAdditionalCost.val('');
                        $('#productResourcesEstimatedAdditionalCostHelpMe').html('').empty();
                        $productResourcesActualAdditionalCost.val('');
                        $('#productResourcesActualAdditionalCostHelpMe').html('').empty();
                        $productResourcesEstimatedBillOfMaterialCost.val('');
                        $('#productResourcesEstimatedBillOfMaterialCostHelpMe').html('').empty();
                        $productResourcesActualBillOfMaterialCost.val('');
                        $('#productResourcesActualBillOfMaterialCostHelpMe').html('').empty();
                        $productResourcesEstimatedTotalCost.val('');
                        $('#productResourcesEstimatedTotalCostHelpMe').html('').empty();
                        $productResourcesActualTotalCost.val('');
                        $('#productResourcesActualTotalCostHelpMe').html('').empty();
                    } else if (success === false) {
                        $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
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
            if ($productBatchId.val().length === 0) {
                $('#productBatchIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productBatchIdLabel'] + " </span>");
                $productBatchId.data('chosen').activate_action();
                return false;
            }
            if ($invoiceId.val().length === 0) {
                $('#invoiceIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceIdLabel'] + " </span>");
                $invoiceId.data('chosen').activate_action();
                return false;
            }
            if ($productResourcesTask.val().length === 0) {
                $('#productResourcesTaskHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesTaskLabel'] + " </span>");
                $('#productResourcesTaskForm').removeClass().addClass('form-group has-error');
                $productResourcesTask.focus();
                return false;
            }
            if ($productResourcesEstimatedDate.val().length === 0) {
                $('#productResourcesEstimatedDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedDateLabel'] + " </span>");
                $('#productResourcesEstimatedDateForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedDate.focus();
                return false;
            }
            if ($productResourcesActualDate.val().length === 0) {
                $('#productResourcesActualDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualDateLabel'] + " </span>");
                $('#productResourcesActualDateForm').removeClass().addClass('form-group has-error');
                $productResourcesActualDate.focus();
                return false;
            }
            if ($productResourcesEstimatedEmployeeCost.val().length === 0) {
                $('#productResourcesEstimatedEmployeeCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedEmployeeCostLabel'] + " </span>");
                $('#productResourcesEstimatedEmployeeCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedEmployeeCost.focus();
                return false;
            }
            if ($productResourcesActualEmployeeCost.val().length === 0) {
                $('#productResourcesActualEmployeeCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualEmployeeCostLabel'] + " </span>");
                $('#productResourcesActualEmployeeCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualEmployeeCost.focus();
                return false;
            }
            if ($productResourcesEstimatedMachineCost.val().length === 0) {
                $('#productResourcesEstimatedMachineCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedMachineCostLabel'] + " </span>");
                $('#productResourcesEstimatedMachineCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedMachineCost.focus();
                return false;
            }
            if ($productResourcesActualMachineCost.val().length === 0) {
                $('#productResourcesActualMachineCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualMachineCostLabel'] + " </span>");
                $('#productResourcesActualMachineCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualMachineCost.focus();
                return false;
            }
            if ($productResourcesEstimatedAdditionalCost.val().length === 0) {
                $('#productResourcesEstimatedAdditionalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedAdditionalCostLabel'] + " </span>");
                $('#productResourcesEstimatedAdditionalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedAdditionalCost.focus();
                return false;
            }
            if ($productResourcesActualAdditionalCost.val().length === 0) {
                $('#productResourcesActualAdditionalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualAdditionalCostLabel'] + " </span>");
                $('#productResourcesActualAdditionalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualAdditionalCost.focus();
                return false;
            }
            if ($productResourcesEstimatedBillOfMaterialCost.val().length === 0) {
                $('#productResourcesEstimatedBillOfMaterialCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedBillOfMaterialCostLabel'] + " </span>");
                $('#productResourcesEstimatedBillOfMaterialCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedBillOfMaterialCost.focus();
                return false;
            }
            if ($productResourcesActualBillOfMaterialCost.val().length === 0) {
                $('#productResourcesActualBillOfMaterialCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualBillOfMaterialCostLabel'] + " </span>");
                $('#productResourcesActualBillOfMaterialCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualBillOfMaterialCost.focus();
                return false;
            }
            if ($productResourcesEstimatedTotalCost.val().length === 0) {
                $('#productResourcesEstimatedTotalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedTotalCostLabel'] + " </span>");
                $('#productResourcesEstimatedTotalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedTotalCost.focus();
                return false;
            }
            if ($productResourcesActualTotalCost.val().length === 0) {
                $('#productResourcesActualTotalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualTotalCostLabel'] + " </span>");
                $('#productResourcesActualTotalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualTotalCost.focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', productBatchId: $productBatchId.val(), invoiceId: $invoiceId.val(), productResourcesTask: $productResourcesTask.val(), productResourcesEstimatedDate: $productResourcesEstimatedDate.val(), productResourcesActualDate: $productResourcesActualDate.val(), productResourcesEstimatedEmployeeCost: $productResourcesEstimatedEmployeeCost.val(), productResourcesActualEmployeeCost: $productResourcesActualEmployeeCost.val(), productResourcesEstimatedMachineCost: $productResourcesEstimatedMachineCost.val(), productResourcesActualMachineCost: $productResourcesActualMachineCost.val(), productResourcesEstimatedAdditionalCost: $productResourcesEstimatedAdditionalCost.val(), productResourcesActualAdditionalCost: $productResourcesActualAdditionalCost.val(), productResourcesEstimatedBillOfMaterialCost: $productResourcesEstimatedBillOfMaterialCost.val(), productResourcesActualBillOfMaterialCost: $productResourcesActualBillOfMaterialCost.val(), productResourcesEstimatedTotalCost: $productResourcesEstimatedTotalCost.val(), productResourcesActualTotalCost: $productResourcesActualTotalCost.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                        $('#productResourcesId').val(data.productResourcesId);
                        $('#newRecordButton1').removeClass().addClass('btn btn-success disabled');
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
                            $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled');
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
            if ($productBatchId.val().length === 0) {
                $('#productBatchIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productBatchIdLabel'] + " </span>");
                $productBatchId.data('chosen').activate_action();
                return false;
            }
            if ($invoiceId.val().length === 0) {
                $('#invoiceIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceIdLabel'] + " </span>");
                $invoiceId.data('chosen').activate_action();
                return false;
            }
            if ($productResourcesTask.val().length === 0) {
                $('#productResourcesTaskHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesTaskLabel'] + " </span>");
                $('#productResourcesTaskForm').removeClass().addClass('form-group has-error');
                $productResourcesTask.focus();
                return false;
            }
            if ($productResourcesEstimatedDate.val().length === 0) {
                $('#productResourcesEstimatedDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedDateLabel'] + " </span>");
                $('#productResourcesEstimatedDateForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedDate.focus();
                return false;
            }
            if ($productResourcesActualDate.val().length === 0) {
                $('#productResourcesActualDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualDateLabel'] + " </span>");
                $('#productResourcesActualDateForm').removeClass().addClass('form-group has-error');
                $productResourcesActualDate.focus();
                return false;
            }
            if ($productResourcesEstimatedEmployeeCost.val().length === 0) {
                $('#productResourcesEstimatedEmployeeCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedEmployeeCostLabel'] + " </span>");
                $('#productResourcesEstimatedEmployeeCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedEmployeeCost.focus();
                return false;
            }
            if ($productResourcesActualEmployeeCost.val().length === 0) {
                $('#productResourcesActualEmployeeCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualEmployeeCostLabel'] + " </span>");
                $('#productResourcesActualEmployeeCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualEmployeeCost.focus();
                return false;
            }
            if ($productResourcesEstimatedMachineCost.val().length === 0) {
                $('#productResourcesEstimatedMachineCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedMachineCostLabel'] + " </span>");
                $('#productResourcesEstimatedMachineCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedMachineCost.focus();
                return false;
            }
            if ($productResourcesActualMachineCost.val().length === 0) {
                $('#productResourcesActualMachineCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualMachineCostLabel'] + " </span>");
                $('#productResourcesActualMachineCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualMachineCost.focus();
                return false;
            }
            if ($productResourcesEstimatedAdditionalCost.val().length === 0) {
                $('#productResourcesEstimatedAdditionalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedAdditionalCostLabel'] + " </span>");
                $('#productResourcesEstimatedAdditionalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedAdditionalCost.focus();
                return false;
            }
            if ($productResourcesActualAdditionalCost.val().length === 0) {
                $('#productResourcesActualAdditionalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualAdditionalCostLabel'] + " </span>");
                $('#productResourcesActualAdditionalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualAdditionalCost.focus();
                return false;
            }
            if ($productResourcesEstimatedBillOfMaterialCost.val().length === 0) {
                $('#productResourcesEstimatedBillOfMaterialCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedBillOfMaterialCostLabel'] + " </span>");
                $('#productResourcesEstimatedBillOfMaterialCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedBillOfMaterialCost.focus();
                return false;
            }
            if ($productResourcesActualBillOfMaterialCost.val().length === 0) {
                $('#productResourcesActualBillOfMaterialCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualBillOfMaterialCostLabel'] + " </span>");
                $('#productResourcesActualBillOfMaterialCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualBillOfMaterialCost.focus();
                return false;
            }
            if ($productResourcesEstimatedTotalCost.val().length === 0) {
                $('#productResourcesEstimatedTotalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedTotalCostLabel'] + " </span>");
                $('#productResourcesEstimatedTotalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedTotalCost.focus();
                return false;
            }
            if ($productResourcesActualTotalCost.val().length === 0) {
                $('#productResourcesActualTotalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualTotalCostLabel'] + " </span>");
                $('#productResourcesActualTotalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualTotalCost.focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', productBatchId: $productBatchId.val(), invoiceId: $invoiceId.val(), productResourcesTask: $productResourcesTask.val(), productResourcesEstimatedDate: $productResourcesEstimatedDate.val(), productResourcesActualDate: $productResourcesActualDate.val(), productResourcesEstimatedEmployeeCost: $productResourcesEstimatedEmployeeCost.val(), productResourcesActualEmployeeCost: $productResourcesActualEmployeeCost.val(), productResourcesEstimatedMachineCost: $productResourcesEstimatedMachineCost.val(), productResourcesActualMachineCost: $productResourcesActualMachineCost.val(), productResourcesEstimatedAdditionalCost: $productResourcesEstimatedAdditionalCost.val(), productResourcesActualAdditionalCost: $productResourcesActualAdditionalCost.val(), productResourcesEstimatedBillOfMaterialCost: $productResourcesEstimatedBillOfMaterialCost.val(), productResourcesActualBillOfMaterialCost: $productResourcesActualBillOfMaterialCost.val(), productResourcesEstimatedTotalCost: $productResourcesEstimatedTotalCost.val(), productResourcesActualTotalCost: $productResourcesActualTotalCost.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
    var $productBatchId = $('#productBatchId');
    var $invoiceId = $('#invoiceId');
    var $productResourcesTask = $('#productResourcesTask');
    var $productResourcesEstimatedDate = $('#productResourcesEstimatedDate');
    var $productResourcesActualDate = $('#productResourcesActualDate');
    var $productResourcesEstimatedEmployeeCost = $('#productResourcesEstimatedEmployeeCost');
    var $productResourcesActualEmployeeCost = $('#productResourcesActualEmployeeCost');
    var $productResourcesEstimatedMachineCost = $('#productResourcesEstimatedMachineCost');
    var $productResourcesActualMachineCost = $('#productResourcesActualMachineCost');
    var $productResourcesEstimatedAdditionalCost = $('#productResourcesEstimatedAdditionalCost');
    var $productResourcesActualAdditionalCost = $('#productResourcesActualAdditionalCost');
    var $productResourcesEstimatedBillOfMaterialCost = $('#productResourcesEstimatedBillOfMaterialCost');
    var $productResourcesActualBillOfMaterialCost = $('#productResourcesActualBillOfMaterialCost');
    var $productResourcesEstimatedTotalCost = $('#productResourcesEstimatedTotalCost');
    var $productResourcesActualTotalCost = $('#productResourcesActualTotalCost');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $infoPanel.empty().html('');
        if (type === 1) {
            if ($productBatchId.val().length === 0) {
                $('#productBatchIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productBatchIdLabel'] + " </span>");
                $productBatchId.data('chosen').activate_action();
                return false;
            }
            if ($invoiceId.val().length === 0) {
                $('#invoiceIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceIdLabel'] + " </span>");
                $invoiceId.data('chosen').activate_action();
                return false;
            }
            if ($productResourcesTask.val().length === 0) {
                $('#productResourcesTaskHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesTaskLabel'] + " </span>");
                $('#productResourcesTaskForm').removeClass().addClass('form-group has-error');
                $productResourcesTask.focus();
                return false;
            }
            if ($productResourcesEstimatedDate.val().length === 0) {
                $('#productResourcesEstimatedDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedDateLabel'] + " </span>");
                $('#productResourcesEstimatedDateForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedDate.focus();
                return false;
            }
            if ($productResourcesActualDate.val().length === 0) {
                $('#productResourcesActualDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualDateLabel'] + " </span>");
                $('#productResourcesActualDateForm').removeClass().addClass('form-group has-error');
                $productResourcesActualDate.focus();
                return false;
            }
            if ($productResourcesEstimatedEmployeeCost.val().length === 0) {
                $('#productResourcesEstimatedEmployeeCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedEmployeeCostLabel'] + " </span>");
                $('#productResourcesEstimatedEmployeeCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedEmployeeCost.focus();
                return false;
            }
            if ($productResourcesActualEmployeeCost.val().length === 0) {
                $('#productResourcesActualEmployeeCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualEmployeeCostLabel'] + " </span>");
                $('#productResourcesActualEmployeeCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualEmployeeCost.focus();
                return false;
            }
            if ($productResourcesEstimatedMachineCost.val().length === 0) {
                $('#productResourcesEstimatedMachineCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedMachineCostLabel'] + " </span>");
                $('#productResourcesEstimatedMachineCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedMachineCost.focus();
                return false;
            }
            if ($productResourcesActualMachineCost.val().length === 0) {
                $('#productResourcesActualMachineCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualMachineCostLabel'] + " </span>");
                $('#productResourcesActualMachineCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualMachineCost.focus();
                return false;
            }
            if ($productResourcesEstimatedAdditionalCost.val().length === 0) {
                $('#productResourcesEstimatedAdditionalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedAdditionalCostLabel'] + " </span>");
                $('#productResourcesEstimatedAdditionalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedAdditionalCost.focus();
                return false;
            }
            if ($productResourcesActualAdditionalCost.val().length === 0) {
                $('#productResourcesActualAdditionalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualAdditionalCostLabel'] + " </span>");
                $('#productResourcesActualAdditionalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualAdditionalCost.focus();
                return false;
            }
            if ($productResourcesEstimatedBillOfMaterialCost.val().length === 0) {
                $('#productResourcesEstimatedBillOfMaterialCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedBillOfMaterialCostLabel'] + " </span>");
                $('#productResourcesEstimatedBillOfMaterialCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedBillOfMaterialCost.focus();
                return false;
            }
            if ($productResourcesActualBillOfMaterialCost.val().length === 0) {
                $('#productResourcesActualBillOfMaterialCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualBillOfMaterialCostLabel'] + " </span>");
                $('#productResourcesActualBillOfMaterialCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualBillOfMaterialCost.focus();
                return false;
            }
            if ($productResourcesEstimatedTotalCost.val().length === 0) {
                $('#productResourcesEstimatedTotalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedTotalCostLabel'] + " </span>");
                $('#productResourcesEstimatedTotalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedTotalCost.focus();
                return false;
            }
            if ($productResourcesActualTotalCost.val().length === 0) {
                $('#productResourcesActualTotalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualTotalCostLabel'] + " </span>");
                $('#productResourcesActualTotalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualTotalCost.focus();
                return false;
            }
            $infoPanel.html('').empty();
            $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', productResourcesId: $productResourcesId.val(), productBatchId: $productBatchId.val(), invoiceId: $invoiceId.val(), productResourcesTask: $productResourcesTask.val(), productResourcesEstimatedDate: $productResourcesEstimatedDate.val(), productResourcesActualDate: $productResourcesActualDate.val(), productResourcesEstimatedEmployeeCost: $productResourcesEstimatedEmployeeCost.val(), productResourcesActualEmployeeCost: $productResourcesActualEmployeeCost.val(), productResourcesEstimatedMachineCost: $productResourcesEstimatedMachineCost.val(), productResourcesActualMachineCost: $productResourcesActualMachineCost.val(), productResourcesEstimatedAdditionalCost: $productResourcesEstimatedAdditionalCost.val(), productResourcesActualAdditionalCost: $productResourcesActualAdditionalCost.val(), productResourcesEstimatedBillOfMaterialCost: $productResourcesEstimatedBillOfMaterialCost.val(), productResourcesActualBillOfMaterialCost: $productResourcesActualBillOfMaterialCost.val(), productResourcesEstimatedTotalCost: $productResourcesEstimatedTotalCost.val(), productResourcesActualTotalCost: $productResourcesActualTotalCost.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        } else if (type === 3) {
            if ($productBatchId.val().length === 0) {
                $('#productBatchIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productBatchIdLabel'] + " </span>");
                $productBatchId.data('chosen').activate_action();
                return false;
            }
            if ($invoiceId.val().length === 0) {
                $('#invoiceIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceIdLabel'] + " </span>");
                $invoiceId.data('chosen').activate_action();
                return false;
            }
            if ($productResourcesTask.val().length === 0) {
                $('#productResourcesTaskHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesTaskLabel'] + " </span>");
                $('#productResourcesTaskForm').removeClass().addClass('form-group has-error');
                $productResourcesTask.focus();
                return false;
            }
            if ($productResourcesEstimatedDate.val().length === 0) {
                $('#productResourcesEstimatedDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedDateLabel'] + " </span>");
                $('#productResourcesEstimatedDateForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedDate.focus();
                return false;
            }
            if ($productResourcesActualDate.val().length === 0) {
                $('#productResourcesActualDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualDateLabel'] + " </span>");
                $('#productResourcesActualDateForm').removeClass().addClass('form-group has-error');
                $productResourcesActualDate.focus();
                return false;
            }
            if ($productResourcesEstimatedEmployeeCost.val().length === 0) {
                $('#productResourcesEstimatedEmployeeCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedEmployeeCostLabel'] + " </span>");
                $('#productResourcesEstimatedEmployeeCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedEmployeeCost.focus();
                return false;
            }
            if ($productResourcesActualEmployeeCost.val().length === 0) {
                $('#productResourcesActualEmployeeCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualEmployeeCostLabel'] + " </span>");
                $('#productResourcesActualEmployeeCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualEmployeeCost.focus();
                return false;
            }
            if ($productResourcesEstimatedMachineCost.val().length === 0) {
                $('#productResourcesEstimatedMachineCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedMachineCostLabel'] + " </span>");
                $('#productResourcesEstimatedMachineCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedMachineCost.focus();
                return false;
            }
            if ($productResourcesActualMachineCost.val().length === 0) {
                $('#productResourcesActualMachineCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualMachineCostLabel'] + " </span>");
                $('#productResourcesActualMachineCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualMachineCost.focus();
                return false;
            }
            if ($productResourcesEstimatedAdditionalCost.val().length === 0) {
                $('#productResourcesEstimatedAdditionalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedAdditionalCostLabel'] + " </span>");
                $('#productResourcesEstimatedAdditionalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedAdditionalCost.focus();
                return false;
            }
            if ($productResourcesActualAdditionalCost.val().length === 0) {
                $('#productResourcesActualAdditionalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualAdditionalCostLabel'] + " </span>");
                $('#productResourcesActualAdditionalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualAdditionalCost.focus();
                return false;
            }
            if ($productResourcesEstimatedBillOfMaterialCost.val().length === 0) {
                $('#productResourcesEstimatedBillOfMaterialCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedBillOfMaterialCostLabel'] + " </span>");
                $('#productResourcesEstimatedBillOfMaterialCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedBillOfMaterialCost.focus();
                return false;
            }
            if ($productResourcesActualBillOfMaterialCost.val().length === 0) {
                $('#productResourcesActualBillOfMaterialCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualBillOfMaterialCostLabel'] + " </span>");
                $('#productResourcesActualBillOfMaterialCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualBillOfMaterialCost.focus();
                return false;
            }
            if ($productResourcesEstimatedTotalCost.val().length === 0) {
                $('#productResourcesEstimatedTotalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesEstimatedTotalCostLabel'] + " </span>");
                $('#productResourcesEstimatedTotalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesEstimatedTotalCost.focus();
                return false;
            }
            if ($productResourcesActualTotalCost.val().length === 0) {
                $('#productResourcesActualTotalCostHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['productResourcesActualTotalCostLabel'] + " </span>");
                $('#productResourcesActualTotalCostForm').removeClass().addClass('form-group has-error');
                $productResourcesActualTotalCost.focus();
                return false;
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', productResourcesId: $productResourcesId.val(), productBatchId: $productBatchId.val(), invoiceId: $invoiceId.val(), productResourcesTask: $productResourcesTask.val(), productResourcesEstimatedDate: $productResourcesEstimatedDate.val(), productResourcesActualDate: $productResourcesActualDate.val(), productResourcesEstimatedEmployeeCost: $productResourcesEstimatedEmployeeCost.val(), productResourcesActualEmployeeCost: $productResourcesActualEmployeeCost.val(), productResourcesEstimatedMachineCost: $productResourcesEstimatedMachineCost.val(), productResourcesActualMachineCost: $productResourcesActualMachineCost.val(), productResourcesEstimatedAdditionalCost: $productResourcesEstimatedAdditionalCost.val(), productResourcesActualAdditionalCost: $productResourcesActualAdditionalCost.val(), productResourcesEstimatedBillOfMaterialCost: $productResourcesEstimatedBillOfMaterialCost.val(), productResourcesActualBillOfMaterialCost: $productResourcesActualBillOfMaterialCost.val(), productResourcesEstimatedTotalCost: $productResourcesEstimatedTotalCost.val(), productResourcesActualTotalCost: $productResourcesActualTotalCost.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        }
    }
}
function deleteRecord(leafId, url, urlList, securityToken, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var $productResourcesId = $('#productResourcesId');
    var css = $('#deleteRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (deleteAccess === 1) {
            if (confirm(decodeURIComponent(t['deleteRecordMessageLabel']))) {
                var value = $productResourcesId.val();
                if (!value) {
                    $infoPanel.html('').empty().html("<span class='label label-important'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "<span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                    return false;
                } else {
                    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', productResourcesId: $productResourcesId.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
    $infoPanel.html('').empty().html("<span class='label label-important'><img src='" + resetIcon + "'> " + decodeURIComponent(t['resetRecordTextLabel']) + "</span>").delay(1000).fadeOut();
    if ($infoPanel.is(':hidden')) {
        $infoPanel.show();
    }
    if (createAccess === 1) {
        $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', '');
        $('#newRecordButton2').attr('onClick', '').removeClass().addClass('btn dropdown-toggle btn-success');
        $('#newRecordButton3').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1)");
        $('#newRecordButton4').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',2)");
        $('#newRecordButton5').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',3)");
        $('#newRecordButton6').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',4)");
        $('#newRecordButton7').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',5)");
    } else {
        $('#newRecordButton1').removeClass().addClass('btn btn-success disabled');
        $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
        $('#newRecordButton3').attr('onClick', '');
        $('#newRecordButton4').attr('onClick', '');
        $('#newRecordButton5').attr('onClick', '');
        $('#newRecordButton6').attr('onClick', '');
        $('#newRecordButton7').attr('onClick', '');
    }
    $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled').attr('onClick', '');
    $('#updateRecordButton3').attr('onClick', '');
    $('#updateRecordButton4').attr('onClick', '');
    $('#updateRecordButton5').attr('onClick', '');
    $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
    $('#postRecordButton').removeClass().addClass('btn btn-info').attr('onClick', '');
    $('#firstRecordButton').removeClass().addClass('btn btn-info').attr('onClick', "firstRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
    $('#previousRecordButton').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
    $('#nextRecordButton').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
    $('#endRecordButton').removeClass().addClass('btn btn-info').attr('onClick', "endRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + ")");
    $("#productResourcesId").val('');
    $("#productResourcesIdHelpMe").empty().html('');
    
    $("#productBatchId").val('');
    $("#productBatchIdHelpMe").empty().html('');
    $('#productBatchId').trigger("chosen:updated");
    $("#invoiceId").val('');
    $("#invoiceIdHelpMe").empty().html('');
    $('#invoiceId').trigger("chosen:updated");
    $("#productResourcesTask").val('');
    $("#productResourcesTaskHelpMe").empty().html('');
    $('#productResourcesTask').empty().val('');
    $("#productResourcesEstimatedDate").val('');
    $("#productResourcesEstimatedDateHelpMe").empty().html('');
    $("#productResourcesActualDate").val('');
    $("#productResourcesActualDateHelpMe").empty().html('');
    $("#productResourcesEstimatedEmployeeCost").val('');
    $("#productResourcesEstimatedEmployeeCostHelpMe").empty().html('');
    $("#productResourcesActualEmployeeCost").val('');
    $("#productResourcesActualEmployeeCostHelpMe").empty().html('');
    $("#productResourcesEstimatedMachineCost").val('');
    $("#productResourcesEstimatedMachineCostHelpMe").empty().html('');
    $("#productResourcesActualMachineCost").val('');
    $("#productResourcesActualMachineCostHelpMe").empty().html('');
    $("#productResourcesEstimatedAdditionalCost").val('');
    $("#productResourcesEstimatedAdditionalCostHelpMe").empty().html('');
    $("#productResourcesActualAdditionalCost").val('');
    $("#productResourcesActualAdditionalCostHelpMe").empty().html('');
    $("#productResourcesEstimatedBillOfMaterialCost").val('');
    $("#productResourcesEstimatedBillOfMaterialCostHelpMe").empty().html('');
    $("#productResourcesActualBillOfMaterialCost").val('');
    $("#productResourcesActualBillOfMaterialCostHelpMe").empty().html('');
    $("#productResourcesEstimatedTotalCost").val('');
    $("#productResourcesEstimatedTotalCostHelpMe").empty().html('');
    $("#productResourcesActualTotalCost").val('');
    $("#productResourcesActualTotalCostHelpMe").empty().html('');
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
                    $.ajax({type: 'POST', url: url, data: {method: 'read', productResourcesId: firstRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                                $('#productResourcesId').val(data.data.productResourcesId);
                                $('#productBatchId').val(data.data.productBatchId).trigger("chosen:updated");
                                $('#invoiceId').val(data.data.invoiceId).trigger("chosen:updated");
                                $('#productResourcesTask').val(data.data.productResourcesTask);
                                x = data.data.productResourcesEstimatedDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#productResourcesEstimatedDate').val(output);
                                x = data.data.productResourcesActualDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#productResourcesActualDate').val(output);
                                $('#productResourcesEstimatedEmployeeCost').val(data.data.productResourcesEstimatedEmployeeCost);
                                $('#productResourcesActualEmployeeCost').val(data.data.productResourcesActualEmployeeCost);
                                $('#productResourcesEstimatedMachineCost').val(data.data.productResourcesEstimatedMachineCost);
                                $('#productResourcesActualMachineCost').val(data.data.productResourcesActualMachineCost);
                                $('#productResourcesEstimatedAdditionalCost').val(data.data.productResourcesEstimatedAdditionalCost);
                                $('#productResourcesActualAdditionalCost').val(data.data.productResourcesActualAdditionalCost);
                                $('#productResourcesEstimatedBillOfMaterialCost').val(data.data.productResourcesEstimatedBillOfMaterialCost);
                                $('#productResourcesActualBillOfMaterialCost').val(data.data.productResourcesActualBillOfMaterialCost);
                                $('#productResourcesEstimatedTotalCost').val(data.data.productResourcesEstimatedTotalCost);
                                $('#productResourcesActualTotalCost').val(data.data.productResourcesActualTotalCost);
                                if (nextRecord > 0) {
                                    $('#previousRecordButton').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                                    $('#nextRecordButton').removeClass().addClass('btn btn-info').attr('onClick', '').attr('onClick', "nextRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
                                    $('#firstRecordCounter').val(firstRecord);
                                    $('#previousRecordCounter').val(previousRecord);
                                    $('#nextRecordCounter').val(nextRecord);
                                    $('#lastRecordCounter').val(lastRecord);
                                    $('#newRecordButton1').removeClass().addClass('btn btn-success disabled');
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
                                        $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled');
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
                    $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRollSweat + "'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                    $.ajax({type: 'POST', url: url, data: {method: 'read', productResourcesId: lastRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                                $('#productResourcesId').val(data.data.productResourcesId);
                                $('#productBatchId').val(data.data.productBatchId).trigger("chosen:updated");
                                $('#invoiceId').val(data.data.invoiceId).trigger("chosen:updated");
                                $('#productResourcesTask').val(data.data.productResourcesTask);
                                ;
                                x = data.data.productResourcesEstimatedDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#productResourcesEstimatedDate').val(output);
                                x = data.data.productResourcesActualDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#productResourcesActualDate').val(output);
                                $('#productResourcesEstimatedEmployeeCost').val(data.data.productResourcesEstimatedEmployeeCost);
                                $('#productResourcesActualEmployeeCost').val(data.data.productResourcesActualEmployeeCost);
                                $('#productResourcesEstimatedMachineCost').val(data.data.productResourcesEstimatedMachineCost);
                                $('#productResourcesActualMachineCost').val(data.data.productResourcesActualMachineCost);
                                $('#productResourcesEstimatedAdditionalCost').val(data.data.productResourcesEstimatedAdditionalCost);
                                $('#productResourcesActualAdditionalCost').val(data.data.productResourcesActualAdditionalCost);
                                $('#productResourcesEstimatedBillOfMaterialCost').val(data.data.productResourcesEstimatedBillOfMaterialCost);
                                $('#productResourcesActualBillOfMaterialCost').val(data.data.productResourcesActualBillOfMaterialCost);
                                $('#productResourcesEstimatedTotalCost').val(data.data.productResourcesEstimatedTotalCost);
                                $('#productResourcesActualTotalCost').val(data.data.productResourcesActualTotalCost);
                                if (lastRecord !== 0) {
                                    $('#previousRecordButton').removeClass().addClass('btn btn-info').attr('onClick', "previousRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
                                    $('#nextRecordButton').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                                    $('#firstRecordCounter').val(firstRecord);
                                    $('#previousRecordCounter').val(previousRecord);
                                    $('#nextRecordCounter').val(nextRecord);
                                    $('#lastRecordCounter').val(lastRecord);
                                    $('#newRecordButton1').removeClass().addClass('btn btn-success disabled');
                                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                                    $('#newRecordButton3').attr('onClick', '');
                                    $('#newRecordButton4').attr('onClick', '');
                                    $('#newRecordButton5').attr('onClick', '');
                                    $('#newRecordButton6').attr('onClick', '');
                                    $('#newRecordButton7').attr('onClick', '');
                                    if (updateAccess === 1) {
                                        $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', '');
                                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info').attr('onClick', '');
                                        $('#updateRecordButton3').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1," + deleteAccess + ")");
                                        $('#updateRecordButton4').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',2," + deleteAccess + ")");
                                        $('#updateRecordButton5').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',3," + deleteAccess + ")");
                                    } else {
                                        $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
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
                    $infoPanel.html("<span class='label label-important'>&nbsp;" + message + "</span>");
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
            $infoPanel.html('').empty().html("<span class='label label-important'>" + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }
        if (parseFloat($previousRecordCounter.val()) > 0 && parseFloat($previousRecordCounter.val()) < parseFloat($('#lastRecordCounter').val())) {
            $.ajax({type: 'POST', url: url, data: {method: 'read', productResourcesId: $previousRecordCounter.val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                        $('#productResourcesId').val(data.data.productResourcesId);
                        $('#productBatchId').val(data.data.productBatchId).trigger("chosen:updated");
                        $('#invoiceId').val(data.data.invoiceId).trigger("chosen:updated");
                        $('#productResourcesTask').val(data.data.productResourcesTask);
                        ;
                        x = data.data.productResourcesEstimatedDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#productResourcesEstimatedDate').val(output);
                        x = data.data.productResourcesActualDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#productResourcesActualDate').val(output);
                        $('#productResourcesEstimatedEmployeeCost').val(data.data.productResourcesEstimatedEmployeeCost);
                        $('#productResourcesActualEmployeeCost').val(data.data.productResourcesActualEmployeeCost);
                        $('#productResourcesEstimatedMachineCost').val(data.data.productResourcesEstimatedMachineCost);
                        $('#productResourcesActualMachineCost').val(data.data.productResourcesActualMachineCost);
                        $('#productResourcesEstimatedAdditionalCost').val(data.data.productResourcesEstimatedAdditionalCost);
                        $('#productResourcesActualAdditionalCost').val(data.data.productResourcesActualAdditionalCost);
                        $('#productResourcesEstimatedBillOfMaterialCost').val(data.data.productResourcesEstimatedBillOfMaterialCost);
                        $('#productResourcesActualBillOfMaterialCost').val(data.data.productResourcesActualBillOfMaterialCost);
                        $('#productResourcesEstimatedTotalCost').val(data.data.productResourcesEstimatedTotalCost);
                        $('#productResourcesActualTotalCost').val(data.data.productResourcesActualTotalCost);
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
                        $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled');
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
                        $('#nextRecordButton').removeClass().addClass('btn btn-info').attr('onClick', '').attr('onClick', "nextRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
                    } else {
                        $('#nextRecordButton').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
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
            $infoPanel.html('').empty().html("<span class='label label-important'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }
        if (parseFloat($nextRecordCounter.val()) <= parseFloat($('#lastRecordCounter').val())) {
            $.ajax({type: 'POST', url: url, data: {method: 'read', productResourcesId: $nextRecordCounter.val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                        $('#productResourcesId').val(data.data.productResourcesId);
                        $('#productBatchId').val(data.data.productBatchId).trigger("chosen:updated");
                        $('#invoiceId').val(data.data.invoiceId).trigger("chosen:updated");
                        $('#productResourcesTask').val(data.data.productResourcesTask);
                        x = data.data.productResourcesEstimatedDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#productResourcesEstimatedDate').val(output);
                        x = data.data.productResourcesActualDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#productResourcesActualDate').val(output);
                        $('#productResourcesEstimatedEmployeeCost').val(data.data.productResourcesEstimatedEmployeeCost);
                        $('#productResourcesActualEmployeeCost').val(data.data.productResourcesActualEmployeeCost);
                        $('#productResourcesEstimatedMachineCost').val(data.data.productResourcesEstimatedMachineCost);
                        $('#productResourcesActualMachineCost').val(data.data.productResourcesActualMachineCost);
                        $('#productResourcesEstimatedAdditionalCost').val(data.data.productResourcesEstimatedAdditionalCost);
                        $('#productResourcesActualAdditionalCost').val(data.data.productResourcesActualAdditionalCost);
                        $('#productResourcesEstimatedBillOfMaterialCost').val(data.data.productResourcesEstimatedBillOfMaterialCost);
                        $('#productResourcesActualBillOfMaterialCost').val(data.data.productResourcesActualBillOfMaterialCost);
                        $('#productResourcesEstimatedTotalCost').val(data.data.productResourcesEstimatedTotalCost);
                        $('#productResourcesActualTotalCost').val(data.data.productResourcesActualTotalCost);
                        $('#newRecordButton1').removeClass().addClass('btn btn-success disabled');
                        $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                        $('#newRecordButton3').attr('onClick', '');
                        $('#newRecordButton4').attr('onClick', '');
                        $('#newRecordButton5').attr('onClick', '');
                        $('#newRecordButton6').attr('onClick', '');
                        $('#newRecordButton7').attr('onClick', '');
                        if (updateAccess === 1) {
                            $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', '');
                            $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info').attr('onClick', '');
                            $('#updateRecordButton3').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1,'" + deleteAccess + ")");
                            $('#updateRecordButton4').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',2,'" + deleteAccess + ")");
                            $('#updateRecordButton5').attr('onClick', "updateRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',3,'" + deleteAccess + ")");
                        } else {
                            $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled');
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
                            $('#previousRecordButton').removeClass().addClass('btn btn-info').attr('onClick', "previousRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
                        } else {
                            $('#previousRecordButton').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                        }
                        if (parseFloat(nextRecord) === 0) {
                            var exclamationIcon = './images/icons/exclamation.png';
                            $('#nextRecordButton').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
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
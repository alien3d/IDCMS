function getBranch(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'branch'}, beforeSend: function() {
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
                $("#branchId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function getDepartment(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'department'}, beforeSend: function() {
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
                $("#departmentId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function getWarehouse(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'warehouse'}, beforeSend: function() {
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
                $("#warehouseId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function getLocation(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'location'}, beforeSend: function() {
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
                $("#locationId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function getItemCategory(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'itemCategory'}, beforeSend: function() {
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
                $("#itemCategoryId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function getItemType(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'itemType'}, beforeSend: function() {
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
                $("#itemTypeId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function getBusinessPartner(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'businessPartner'}, beforeSend: function() {
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
                $("#businessPartnerId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function getUnitOfMeasurement(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'unitOfMeasurement'}, beforeSend: function() {
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
                $("#unitOfMeasurementId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function getPurchaseInvoice(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'purchaseInvoice'}, beforeSend: function() {
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
                $("#purchaseInvoiceId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function checkDuplicate(leafId, page, securityToken) {
    var $assetCode = $("#assetCode");
    if ($assetCode.val().length === 0) {
        alert(t['oddTextLabel']);
        return false;
    }
    $.ajax({type: 'GET', url: page, data: {assetCode: $assetCode.val(), method: 'duplicate', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                    $("#assetCode").val('').focus();
                    $("#assetCodeForm").removeClass().addClass("form-group has-error");
                    $infoPanel.html('').empty().html("<img src='" + smileyRoll + "'> " + t['codeDuplicateTextLabel']).delay(5000).fadeOut();
                } else {
                    $infoPanel.html('').empty().html("<img src='" + smileyLol + "'> " + t['codeAvailableTextLabel']).delay(5000).fadeOut();
                }
            } else {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
                $("#assetForm").removeClass().addClass("form-group has-error");
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
                $centerViewPort.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
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
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function showFormUpdate(leafId, url, urlList, securityToken, assetId, updateAccess, deleteAccess) {
    sleep(500);
    $('a[rel=tooltip]').tooltip('hide');
    $.ajax({type: 'POST', url: urlList, data: {method: 'read', type: 'form', assetId: assetId, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
function showModalDelete(assetId, branchId, departmentId, warehouseId, locationId, itemCategoryId, itemTypeId, businessPartnerId, unitOfMeasurementId, purchaseInvoiceId, assetCode, assetSerialNumber, assetName, assetModel, assetPrice, assetDate, assetWarranty, assetColor, assetQuantity, assetInsuranceBusinessPartnerId, assetInsuranceStartDate, assetInsuranceExpiredDate, assetWarrantyStartDate, assetWarrantyEndDate, assetDepreciationRate, assetNetBookValue, assetPicture, assetDescription, isTransferAsKit, isDepreciate, isWriteOff, isDispose, isAdjust) {
    $('#assetIdPreview').val('').val(decodeURIComponent(assetId));
    $('#branchIdPreview').val('').val(decodeURIComponent(branchId));
    $('#departmentIdPreview').val('').val(decodeURIComponent(departmentId));
    $('#warehouseIdPreview').val('').val(decodeURIComponent(warehouseId));
    $('#locationIdPreview').val('').val(decodeURIComponent(locationId));
    $('#itemCategoryIdPreview').val('').val(decodeURIComponent(itemCategoryId));
    $('#itemTypeIdPreview').val('').val(decodeURIComponent(itemTypeId));
    $('#businessPartnerIdPreview').val('').val(decodeURIComponent(businessPartnerId));
    $('#unitOfMeasurementIdPreview').val('').val(decodeURIComponent(unitOfMeasurementId));
    $('#purchaseInvoiceIdPreview').val('').val(decodeURIComponent(purchaseInvoiceId));
    $('#assetCodePreview').val('').val(decodeURIComponent(assetCode));
    $('#assetSerialNumberPreview').val('').val(decodeURIComponent(assetSerialNumber));
    $('#assetNamePreview').val('').val(decodeURIComponent(assetName));
    $('#assetModelPreview').val('').val(decodeURIComponent(assetModel));
    $('#assetPricePreview').val('').val(decodeURIComponent(assetPrice));
    $('#assetDatePreview').val('').val(decodeURIComponent(assetDate));
    $('#assetWarrantyPreview').val('').val(decodeURIComponent(assetWarranty));
    $('#assetColorPreview').val('').val(decodeURIComponent(assetColor));
    $('#assetQuantityPreview').val('').val(decodeURIComponent(assetQuantity));
    $('#assetInsuranceBusinessPartnerIdPreview').val('').val(decodeURIComponent(assetInsuranceBusinessPartnerId));
    $('#assetInsuranceStartDatePreview').val('').val(decodeURIComponent(assetInsuranceStartDate));
    $('#assetInsuranceExpiredDatePreview').val('').val(decodeURIComponent(assetInsuranceExpiredDate));
    $('#assetWarrantyStartDatePreview').val('').val(decodeURIComponent(assetWarrantyStartDate));
    $('#assetWarrantyEndDatePreview').val('').val(decodeURIComponent(assetWarrantyEndDate));
    $('#assetDepreciationRatePreview').val('').val(decodeURIComponent(assetDepreciationRate));
    $('#assetNetBookValuePreview').val('').val(decodeURIComponent(assetNetBookValue));
    $('#assetPicturePreview').val('').val(decodeURIComponent(assetPicture));
    $('#assetDescriptionPreview').val('').val(decodeURIComponent(assetDescription));
    $('#isTransferAsKitPreview').val('').val(decodeURIComponent(isTransferAsKit));
    $('#isDepreciatePreview').val('').val(decodeURIComponent(isDepreciate));
    $('#isWriteOffPreview').val('').val(decodeURIComponent(isWriteOff));
    $('#isDisposePreview').val('').val(decodeURIComponent(isDispose));
    $('#isAdjustPreview').val('').val(decodeURIComponent(isAdjust));
    showMeModal('deletePreview', 1);
}
function deleteGridRecord(leafId, url, urlList, securityToken) {
    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', assetId: $('#assetIdPreview').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function deleteGridRecordCheckbox(leafId, url, urlList, securityToken) {
    var stringText = '';
    var counter = 0;
    $('input:checkbox[name="assetId[]"]').each(function() {
        stringText = stringText + "&assetId[]=" + $(this).val();
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
                var path="./v3/humanResource/training/document/" + folder + "/" + filename;
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
    var $branchId = $('#branchId');
    var $departmentId = $('#departmentId');
    var $warehouseId = $('#warehouseId');
    var $locationId = $('#locationId');
    var $itemCategoryId = $('#itemCategoryId');
    var $itemTypeId = $('#itemTypeId');
    var $businessPartnerId = $('#businessPartnerId');
    var $unitOfMeasurementId = $('#unitOfMeasurementId');
    var $purchaseInvoiceId = $('#purchaseInvoiceId');
    var $assetCode = $('#assetCode');
    var $assetSerialNumber = $('#assetSerialNumber');
    var $assetName = $('#assetName');
    var $assetModel = $('#assetModel');
    var $assetPrice = $('#assetPrice');
    var $assetDate = $('#assetDate');
    var $assetWarranty = $('#assetWarranty');
    var $assetColor = $('#assetColor');
    var $assetQuantity = $('#assetQuantity');
    var $assetInsuranceBusinessPartnerId = $('#assetInsuranceBusinessPartnerId');
    var $assetInsuranceStartDate = $('#assetInsuranceStartDate');
    var $assetInsuranceExpiredDate = $('#assetInsuranceExpiredDate');
    var $assetWarrantyStartDate = $('#assetWarrantyStartDate');
    var $assetWarrantyEndDate = $('#assetWarrantyEndDate');
    var $assetDepreciationRate = $('#assetDepreciationRate');
    var $assetNetBookValue = $('#assetNetBookValue');
    var $assetPicture = $('#assetPicture');
    var $assetDescription = $('#assetDescription');
    var $isTransferAsKit = $('#isTransferAsKit');
    var $isDepreciate = $('#isDepreciate');
    var $isWriteOff = $('#isWriteOff');
    var $isDispose = $('#isDispose');
    var $isAdjust = $('#isAdjust');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (type === 1) {
            if ($branchId.val().length === 0) {
                $('#branchIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['branchIdLabel'] + " </span>");
                $branchId.data('chosen').activate_action();
                return false;
            }
            if ($departmentId.val().length === 0) {
                $('#departmentIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['departmentIdLabel'] + " </span>");
                $departmentId.data('chosen').activate_action();
                return false;
            }
            if ($warehouseId.val().length === 0) {
                $('#warehouseIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['warehouseIdLabel'] + " </span>");
                $warehouseId.data('chosen').activate_action();
                return false;
            }
            if ($locationId.val().length === 0) {
                $('#locationIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['locationIdLabel'] + " </span>");
                $locationId.data('chosen').activate_action();
                return false;
            }
            if ($itemCategoryId.val().length === 0) {
                $('#itemCategoryIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemCategoryIdLabel'] + " </span>");
                $itemCategoryId.data('chosen').activate_action();
                return false;
            }
            if ($itemTypeId.val().length === 0) {
                $('#itemTypeIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemTypeIdLabel'] + " </span>");
                $itemTypeId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerId.val().length === 0) {
                $('#businessPartnerIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $businessPartnerId.data('chosen').activate_action();
                return false;
            }
            if ($unitOfMeasurementId.val().length === 0) {
                $('#unitOfMeasurementIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['unitOfMeasurementIdLabel'] + " </span>");
                $unitOfMeasurementId.data('chosen').activate_action();
                return false;
            }
            if ($purchaseInvoiceId.val().length === 0) {
                $('#purchaseInvoiceIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['purchaseInvoiceIdLabel'] + " </span>");
                $purchaseInvoiceId.data('chosen').activate_action();
                return false;
            }
            if ($assetCode.val().length === 0) {
                $('#assetCodeHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetCodeLabel'] + " </span>");
                $('#assetCodeForm').removeClass().addClass('form-group has-error');
                $assetCode.focus();
                return false;
            }
            if ($assetSerialNumber.val().length === 0) {
                $('#assetSerialNumberHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSerialNumberLabel'] + " </span>");
                $('#assetSerialNumberForm').removeClass().addClass('form-group has-error');
                $assetSerialNumber.focus();
                return false;
            }
            if ($assetName.val().length === 0) {
                $('#assetNameHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetNameLabel'] + " </span>");
                $('#assetNameForm').removeClass().addClass('form-group has-error');
                $assetName.focus();
                return false;
            }
            if ($assetModel.val().length === 0) {
                $('#assetModelHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetModelLabel'] + " </span>");
                $('#assetModelForm').removeClass().addClass('form-group has-error');
                $assetModel.focus();
                return false;
            }
            if ($assetPrice.val().length === 0) {
                $('#assetPriceHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetPriceLabel'] + " </span>");
                $('#assetPriceForm').removeClass().addClass('form-group has-error');
                $assetPrice.focus();
                return false;
            }
            if ($assetDate.val().length === 0) {
                $('#assetDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDateLabel'] + " </span>");
                $('#assetDateForm').removeClass().addClass('form-group has-error');
                $assetDate.focus();
                return false;
            }
            if ($assetWarranty.val().length === 0) {
                $('#assetWarrantyHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetWarrantyLabel'] + " </span>");
                $('#assetWarrantyForm').removeClass().addClass('form-group has-error');
                $assetWarranty.focus();
                return false;
            }
            if ($assetColor.val().length === 0) {
                $('#assetColorHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetColorLabel'] + " </span>");
                $('#assetColorForm').removeClass().addClass('form-group has-error');
                $assetColor.focus();
                return false;
            }
            if ($assetQuantity.val().length === 0) {
                $('#assetQuantityHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetQuantityLabel'] + " </span>");
                $('#assetQuantityForm').removeClass().addClass('form-group has-error');
                $assetQuantity.focus();
                return false;
            }
            if ($assetInsuranceBusinessPartnerId.val().length === 0) {
                $('#assetInsuranceBusinessPartnerIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetInsuranceBusinessPartnerIdLabel'] + " </span>");
                $('#assetInsuranceBusinessPartnerIdForm').removeClass().addClass('form-group has-error');
                $assetInsuranceBusinessPartnerId.focus();
                return false;
            }
            if ($assetInsuranceStartDate.val().length === 0) {
                $('#assetInsuranceStartDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetInsuranceStartDateLabel'] + " </span>");
                $('#assetInsuranceStartDateForm').removeClass().addClass('form-group has-error');
                $assetInsuranceStartDate.focus();
                return false;
            }
            if ($assetInsuranceExpiredDate.val().length === 0) {
                $('#assetInsuranceExpiredDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetInsuranceExpiredDateLabel'] + " </span>");
                $('#assetInsuranceExpiredDateForm').removeClass().addClass('form-group has-error');
                $assetInsuranceExpiredDate.focus();
                return false;
            }
            if ($assetWarrantyStartDate.val().length === 0) {
                $('#assetWarrantyStartDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetWarrantyStartDateLabel'] + " </span>");
                $('#assetWarrantyStartDateForm').removeClass().addClass('form-group has-error');
                $assetWarrantyStartDate.focus();
                return false;
            }
            if ($assetWarrantyEndDate.val().length === 0) {
                $('#assetWarrantyEndDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetWarrantyEndDateLabel'] + " </span>");
                $('#assetWarrantyEndDateForm').removeClass().addClass('form-group has-error');
                $assetWarrantyEndDate.focus();
                return false;
            }
            if ($assetDepreciationRate.val().length === 0) {
                $('#assetDepreciationRateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDepreciationRateLabel'] + " </span>");
                $('#assetDepreciationRateForm').removeClass().addClass('form-group has-error');
                $assetDepreciationRate.focus();
                return false;
            }
            if ($assetNetBookValue.val().length === 0) {
                $('#assetNetBookValueHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetNetBookValueLabel'] + " </span>");
                $('#assetNetBookValueForm').removeClass().addClass('form-group has-error');
                $assetNetBookValue.focus();
                return false;
            }
            if ($assetPicture.val().length === 0) {
                $('#assetPictureHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetPictureLabel'] + " </span>");
                $('#assetPictureForm').removeClass().addClass('form-group has-error');
                $assetPicture.focus();
                return false;
            }
            if ($assetDescription.val().length === 0) {
                $('#assetDescriptionHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDescriptionLabel'] + " </span>");
                $('#assetDescriptionForm').removeClass().addClass('form-group has-error');
                $assetDescription.focus();
                return false;
            }
            if ($isTransferAsKit.val().length === 0) {
                $('#isTransferAsKitHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isTransferAsKitLabel'] + " </span>");
                $('#isTransferAsKitForm').removeClass().addClass('form-group has-error');
                $isTransferAsKit.focus();
                return false;
            }
            if ($isDepreciate.val().length === 0) {
                $('#isDepreciateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isDepreciateLabel'] + " </span>");
                $('#isDepreciateForm').removeClass().addClass('form-group has-error');
                $isDepreciate.focus();
                return false;
            }
            if ($isWriteOff.val().length === 0) {
                $('#isWriteOffHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isWriteOffLabel'] + " </span>");
                $('#isWriteOffForm').removeClass().addClass('form-group has-error');
                $isWriteOff.focus();
                return false;
            }
            if ($isDispose.val().length === 0) {
                $('#isDisposeHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isDisposeLabel'] + " </span>");
                $('#isDisposeForm').removeClass().addClass('form-group has-error');
                $isDispose.focus();
                return false;
            }
            if ($isAdjust.val().length === 0) {
                $('#isAdjustHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isAdjustLabel'] + " </span>");
                $('#isAdjustForm').removeClass().addClass('form-group has-error');
                $isAdjust.focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', branchId: $branchId.val(), departmentId: $departmentId.val(), warehouseId: $warehouseId.val(), locationId: $locationId.val(), itemCategoryId: $itemCategoryId.val(), itemTypeId: $itemTypeId.val(), businessPartnerId: $businessPartnerId.val(), unitOfMeasurementId: $unitOfMeasurementId.val(), purchaseInvoiceId: $purchaseInvoiceId.val(), assetCode: $assetCode.val(), assetSerialNumber: $assetSerialNumber.val(), assetName: $assetName.val(), assetModel: $assetModel.val(), assetPrice: $assetPrice.val(), assetDate: $assetDate.val(), assetWarranty: $assetWarranty.val(), assetColor: $assetColor.val(), assetQuantity: $assetQuantity.val(), assetInsuranceBusinessPartnerId: $assetInsuranceBusinessPartnerId.val(), assetInsuranceStartDate: $assetInsuranceStartDate.val(), assetInsuranceExpiredDate: $assetInsuranceExpiredDate.val(), assetWarrantyStartDate: $assetWarrantyStartDate.val(), assetWarrantyEndDate: $assetWarrantyEndDate.val(), assetDepreciationRate: $assetDepreciationRate.val(), assetNetBookValue: $assetNetBookValue.val(), assetPicture: $assetPicture.val(), assetDescription: $assetDescription.val(), isTransferAsKit: $isTransferAsKit.val(), isDepreciate: $isDepreciate.val(), isWriteOff: $isWriteOff.val(), isDispose: $isDispose.val(), isAdjust: $isAdjust.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                        $branchId.val('');
                        $branchId.trigger("chosen:updated");
                        $('#branchIdHelpMe').html('').empty();
                        $departmentId.val('');
                        $departmentId.trigger("chosen:updated");
                        $('#departmentIdHelpMe').html('').empty();
                        $warehouseId.val('');
                        $warehouseId.trigger("chosen:updated");
                        $('#warehouseIdHelpMe').html('').empty();
                        $locationId.val('');
                        $locationId.trigger("chosen:updated");
                        $('#locationIdHelpMe').html('').empty();
                        $itemCategoryId.val('');
                        $itemCategoryId.trigger("chosen:updated");
                        $('#itemCategoryIdHelpMe').html('').empty();
                        $itemTypeId.val('');
                        $itemTypeId.trigger("chosen:updated");
                        $('#itemTypeIdHelpMe').html('').empty();
                        $businessPartnerId.val('');
                        $businessPartnerId.trigger("chosen:updated");
                        $('#businessPartnerIdHelpMe').html('').empty();
                        $unitOfMeasurementId.val('');
                        $unitOfMeasurementId.trigger("chosen:updated");
                        $('#unitOfMeasurementIdHelpMe').html('').empty();
                        $purchaseInvoiceId.val('');
                        $purchaseInvoiceId.trigger("chosen:updated");
                        $('#purchaseInvoiceIdHelpMe').html('').empty();
                        $assetCode.val('');
                        $('#assetCodeHelpMe').html('').empty();
                        $assetSerialNumber.val('');
                        $('#assetSerialNumberHelpMe').html('').empty();
                        $assetName.val('');
                        $('#assetNameHelpMe').html('').empty();
                        $assetModel.val('');
                        $('#assetModelHelpMe').html('').empty();
                        $assetPrice.val('');
                        $('#assetPriceHelpMe').html('').empty();
                        $assetDate.val('');
                        $('#assetDateHelpMe').html('').empty();
                        $assetWarranty.val('');
                        $('#assetWarrantyHelpMe').html('').empty();
                        $assetColor.val('');
                        $('#assetColorHelpMe').html('').empty();
                        $assetQuantity.val('');
                        $('#assetQuantityHelpMe').html('').empty();
                        $assetInsuranceBusinessPartnerId.val('');
                        $assetInsuranceBusinessPartnerId.trigger("chosen:updated");
                        $('#assetInsuranceBusinessPartnerIdHelpMe').html('').empty();
                        $assetInsuranceStartDate.val('');
                        $('#assetInsuranceStartDateHelpMe').html('').empty();
                        $assetInsuranceExpiredDate.val('');
                        $('#assetInsuranceExpiredDateHelpMe').html('').empty();
                        $assetWarrantyStartDate.val('');
                        $('#assetWarrantyStartDateHelpMe').html('').empty();
                        $assetWarrantyEndDate.val('');
                        $('#assetWarrantyEndDateHelpMe').html('').empty();
                        $assetDepreciationRate.val('');
                        $('#assetDepreciationRateHelpMe').html('').empty();
                        $assetNetBookValue.val('');
                        $('#assetNetBookValueHelpMe').html('').empty();
                        $assetPicture.val('');
                        $('#assetPictureHelpMe').html('').empty();
                        $assetDescription.val('');
                        $('#assetDescriptionForm').removeClass().addClass('form-group');
                        $('#assetDescriptionHelpMe').html('').empty();
                        $isTransferAsKit.val('');
                        $('#isTransferAsKitHelpMe').html('').empty();
                        $isDepreciate.val('');
                        $('#isDepreciateHelpMe').html('').empty();
                        $isWriteOff.val('');
                        $('#isWriteOffHelpMe').html('').empty();
                        $isDispose.val('');
                        $('#isDisposeHelpMe').html('').empty();
                        $isAdjust.val('');
                        $('#isAdjustHelpMe').html('').empty();
                    } else if (success === false) {
                        $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
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
            if ($branchId.val().length === 0) {
                $('#branchIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['branchIdLabel'] + " </span>");
                $branchId.data('chosen').activate_action();
                return false;
            }
            if ($departmentId.val().length === 0) {
                $('#departmentIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['departmentIdLabel'] + " </span>");
                $departmentId.data('chosen').activate_action();
                return false;
            }
            if ($warehouseId.val().length === 0) {
                $('#warehouseIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['warehouseIdLabel'] + " </span>");
                $warehouseId.data('chosen').activate_action();
                return false;
            }
            if ($locationId.val().length === 0) {
                $('#locationIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['locationIdLabel'] + " </span>");
                $locationId.data('chosen').activate_action();
                return false;
            }
            if ($itemCategoryId.val().length === 0) {
                $('#itemCategoryIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemCategoryIdLabel'] + " </span>");
                $itemCategoryId.data('chosen').activate_action();
                return false;
            }
            if ($itemTypeId.val().length === 0) {
                $('#itemTypeIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemTypeIdLabel'] + " </span>");
                $itemTypeId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerId.val().length === 0) {
                $('#businessPartnerIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $businessPartnerId.data('chosen').activate_action();
                return false;
            }
            if ($unitOfMeasurementId.val().length === 0) {
                $('#unitOfMeasurementIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['unitOfMeasurementIdLabel'] + " </span>");
                $unitOfMeasurementId.data('chosen').activate_action();
                return false;
            }
            if ($purchaseInvoiceId.val().length === 0) {
                $('#purchaseInvoiceIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['purchaseInvoiceIdLabel'] + " </span>");
                $purchaseInvoiceId.data('chosen').activate_action();
                return false;
            }
            if ($assetCode.val().length === 0) {
                $('#assetCodeHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetCodeLabel'] + " </span>");
                $('#assetCodeForm').removeClass().addClass('form-group has-error');
                $assetCode.focus();
                return false;
            }
            if ($assetSerialNumber.val().length === 0) {
                $('#assetSerialNumberHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSerialNumberLabel'] + " </span>");
                $('#assetSerialNumberForm').removeClass().addClass('form-group has-error');
                $assetSerialNumber.focus();
                return false;
            }
            if ($assetName.val().length === 0) {
                $('#assetNameHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetNameLabel'] + " </span>");
                $('#assetNameForm').removeClass().addClass('form-group has-error');
                $assetName.focus();
                return false;
            }
            if ($assetModel.val().length === 0) {
                $('#assetModelHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetModelLabel'] + " </span>");
                $('#assetModelForm').removeClass().addClass('form-group has-error');
                $assetModel.focus();
                return false;
            }
            if ($assetPrice.val().length === 0) {
                $('#assetPriceHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetPriceLabel'] + " </span>");
                $('#assetPriceForm').removeClass().addClass('form-group has-error');
                $assetPrice.focus();
                return false;
            }
            if ($assetDate.val().length === 0) {
                $('#assetDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDateLabel'] + " </span>");
                $('#assetDateForm').removeClass().addClass('form-group has-error');
                $assetDate.focus();
                return false;
            }
            if ($assetWarranty.val().length === 0) {
                $('#assetWarrantyHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetWarrantyLabel'] + " </span>");
                $('#assetWarrantyForm').removeClass().addClass('form-group has-error');
                $assetWarranty.focus();
                return false;
            }
            if ($assetColor.val().length === 0) {
                $('#assetColorHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetColorLabel'] + " </span>");
                $('#assetColorForm').removeClass().addClass('form-group has-error');
                $assetColor.focus();
                return false;
            }
            if ($assetQuantity.val().length === 0) {
                $('#assetQuantityHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetQuantityLabel'] + " </span>");
                $('#assetQuantityForm').removeClass().addClass('form-group has-error');
                $assetQuantity.focus();
                return false;
            }
            if ($assetInsuranceBusinessPartnerId.val().length === 0) {
                $('#assetInsuranceBusinessPartnerIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetInsuranceBusinessPartnerIdLabel'] + " </span>");
                $('#assetInsuranceBusinessPartnerIdForm').removeClass().addClass('form-group has-error');
                $assetInsuranceBusinessPartnerId.focus();
                return false;
            }
            if ($assetInsuranceStartDate.val().length === 0) {
                $('#assetInsuranceStartDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetInsuranceStartDateLabel'] + " </span>");
                $('#assetInsuranceStartDateForm').removeClass().addClass('form-group has-error');
                $assetInsuranceStartDate.focus();
                return false;
            }
            if ($assetInsuranceExpiredDate.val().length === 0) {
                $('#assetInsuranceExpiredDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetInsuranceExpiredDateLabel'] + " </span>");
                $('#assetInsuranceExpiredDateForm').removeClass().addClass('form-group has-error');
                $assetInsuranceExpiredDate.focus();
                return false;
            }
            if ($assetWarrantyStartDate.val().length === 0) {
                $('#assetWarrantyStartDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetWarrantyStartDateLabel'] + " </span>");
                $('#assetWarrantyStartDateForm').removeClass().addClass('form-group has-error');
                $assetWarrantyStartDate.focus();
                return false;
            }
            if ($assetWarrantyEndDate.val().length === 0) {
                $('#assetWarrantyEndDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetWarrantyEndDateLabel'] + " </span>");
                $('#assetWarrantyEndDateForm').removeClass().addClass('form-group has-error');
                $assetWarrantyEndDate.focus();
                return false;
            }
            if ($assetDepreciationRate.val().length === 0) {
                $('#assetDepreciationRateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDepreciationRateLabel'] + " </span>");
                $('#assetDepreciationRateForm').removeClass().addClass('form-group has-error');
                $assetDepreciationRate.focus();
                return false;
            }
            if ($assetNetBookValue.val().length === 0) {
                $('#assetNetBookValueHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetNetBookValueLabel'] + " </span>");
                $('#assetNetBookValueForm').removeClass().addClass('form-group has-error');
                $assetNetBookValue.focus();
                return false;
            }
            if ($assetPicture.val().length === 0) {
                $('#assetPictureHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetPictureLabel'] + " </span>");
                $('#assetPictureForm').removeClass().addClass('form-group has-error');
                $assetPicture.focus();
                return false;
            }
            if ($assetDescription.val().length === 0) {
                $('#assetDescriptionHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDescriptionLabel'] + " </span>");
                $('#assetDescriptionForm').removeClass().addClass('form-group has-error');
                $assetDescription.focus();
                return false;
            }
            if ($isTransferAsKit.val().length === 0) {
                $('#isTransferAsKitHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isTransferAsKitLabel'] + " </span>");
                $('#isTransferAsKitForm').removeClass().addClass('form-group has-error');
                $isTransferAsKit.focus();
                return false;
            }
            if ($isDepreciate.val().length === 0) {
                $('#isDepreciateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isDepreciateLabel'] + " </span>");
                $('#isDepreciateForm').removeClass().addClass('form-group has-error');
                $isDepreciate.focus();
                return false;
            }
            if ($isWriteOff.val().length === 0) {
                $('#isWriteOffHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isWriteOffLabel'] + " </span>");
                $('#isWriteOffForm').removeClass().addClass('form-group has-error');
                $isWriteOff.focus();
                return false;
            }
            if ($isDispose.val().length === 0) {
                $('#isDisposeHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isDisposeLabel'] + " </span>");
                $('#isDisposeForm').removeClass().addClass('form-group has-error');
                $isDispose.focus();
                return false;
            }
            if ($isAdjust.val().length === 0) {
                $('#isAdjustHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isAdjustLabel'] + " </span>");
                $('#isAdjustForm').removeClass().addClass('form-group has-error');
                $isAdjust.focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', branchId: $branchId.val(), departmentId: $departmentId.val(), warehouseId: $warehouseId.val(), locationId: $locationId.val(), itemCategoryId: $itemCategoryId.val(), itemTypeId: $itemTypeId.val(), businessPartnerId: $businessPartnerId.val(), unitOfMeasurementId: $unitOfMeasurementId.val(), purchaseInvoiceId: $purchaseInvoiceId.val(), assetCode: $assetCode.val(), assetSerialNumber: $assetSerialNumber.val(), assetName: $assetName.val(), assetModel: $assetModel.val(), assetPrice: $assetPrice.val(), assetDate: $assetDate.val(), assetWarranty: $assetWarranty.val(), assetColor: $assetColor.val(), assetQuantity: $assetQuantity.val(), assetInsuranceBusinessPartnerId: $assetInsuranceBusinessPartnerId.val(), assetInsuranceStartDate: $assetInsuranceStartDate.val(), assetInsuranceExpiredDate: $assetInsuranceExpiredDate.val(), assetWarrantyStartDate: $assetWarrantyStartDate.val(), assetWarrantyEndDate: $assetWarrantyEndDate.val(), assetDepreciationRate: $assetDepreciationRate.val(), assetNetBookValue: $assetNetBookValue.val(), assetPicture: $assetPicture.val(), assetDescription: $assetDescription.val(), isTransferAsKit: $isTransferAsKit.val(), isDepreciate: $isDepreciate.val(), isWriteOff: $isWriteOff.val(), isDispose: $isDispose.val(), isAdjust: $isAdjust.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                        $('#assetId').val(data.assetId);
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
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        } else if (type === 5) {
            if ($branchId.val().length === 0) {
                $('#branchIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['branchIdLabel'] + " </span>");
                $branchId.data('chosen').activate_action();
                return false;
            }
            if ($departmentId.val().length === 0) {
                $('#departmentIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['departmentIdLabel'] + " </span>");
                $departmentId.data('chosen').activate_action();
                return false;
            }
            if ($warehouseId.val().length === 0) {
                $('#warehouseIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['warehouseIdLabel'] + " </span>");
                $warehouseId.data('chosen').activate_action();
                return false;
            }
            if ($locationId.val().length === 0) {
                $('#locationIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['locationIdLabel'] + " </span>");
                $locationId.data('chosen').activate_action();
                return false;
            }
            if ($itemCategoryId.val().length === 0) {
                $('#itemCategoryIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemCategoryIdLabel'] + " </span>");
                $itemCategoryId.data('chosen').activate_action();
                return false;
            }
            if ($itemTypeId.val().length === 0) {
                $('#itemTypeIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemTypeIdLabel'] + " </span>");
                $itemTypeId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerId.val().length === 0) {
                $('#businessPartnerIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $businessPartnerId.data('chosen').activate_action();
                return false;
            }
            if ($unitOfMeasurementId.val().length === 0) {
                $('#unitOfMeasurementIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['unitOfMeasurementIdLabel'] + " </span>");
                $unitOfMeasurementId.data('chosen').activate_action();
                return false;
            }
            if ($purchaseInvoiceId.val().length === 0) {
                $('#purchaseInvoiceIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['purchaseInvoiceIdLabel'] + " </span>");
                $purchaseInvoiceId.data('chosen').activate_action();
                return false;
            }
            if ($assetCode.val().length === 0) {
                $('#assetCodeHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetCodeLabel'] + " </span>");
                $('#assetCodeForm').removeClass().addClass('form-group has-error');
                $assetCode.focus();
                return false;
            }
            if ($assetSerialNumber.val().length === 0) {
                $('#assetSerialNumberHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSerialNumberLabel'] + " </span>");
                $('#assetSerialNumberForm').removeClass().addClass('form-group has-error');
                $assetSerialNumber.focus();
                return false;
            }
            if ($assetName.val().length === 0) {
                $('#assetNameHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetNameLabel'] + " </span>");
                $('#assetNameForm').removeClass().addClass('form-group has-error');
                $assetName.focus();
                return false;
            }
            if ($assetModel.val().length === 0) {
                $('#assetModelHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetModelLabel'] + " </span>");
                $('#assetModelForm').removeClass().addClass('form-group has-error');
                $assetModel.focus();
                return false;
            }
            if ($assetPrice.val().length === 0) {
                $('#assetPriceHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetPriceLabel'] + " </span>");
                $('#assetPriceForm').removeClass().addClass('form-group has-error');
                $assetPrice.focus();
                return false;
            }
            if ($assetDate.val().length === 0) {
                $('#assetDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDateLabel'] + " </span>");
                $('#assetDateForm').removeClass().addClass('form-group has-error');
                $assetDate.focus();
                return false;
            }
            if ($assetWarranty.val().length === 0) {
                $('#assetWarrantyHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetWarrantyLabel'] + " </span>");
                $('#assetWarrantyForm').removeClass().addClass('form-group has-error');
                $assetWarranty.focus();
                return false;
            }
            if ($assetColor.val().length === 0) {
                $('#assetColorHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetColorLabel'] + " </span>");
                $('#assetColorForm').removeClass().addClass('form-group has-error');
                $assetColor.focus();
                return false;
            }
            if ($assetQuantity.val().length === 0) {
                $('#assetQuantityHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetQuantityLabel'] + " </span>");
                $('#assetQuantityForm').removeClass().addClass('form-group has-error');
                $assetQuantity.focus();
                return false;
            }
            if ($assetInsuranceBusinessPartnerId.val().length === 0) {
                $('#assetInsuranceBusinessPartnerIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetInsuranceBusinessPartnerIdLabel'] + " </span>");
                $('#assetInsuranceBusinessPartnerIdForm').removeClass().addClass('form-group has-error');
                $assetInsuranceBusinessPartnerId.focus();
                return false;
            }
            if ($assetInsuranceStartDate.val().length === 0) {
                $('#assetInsuranceStartDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetInsuranceStartDateLabel'] + " </span>");
                $('#assetInsuranceStartDateForm').removeClass().addClass('form-group has-error');
                $assetInsuranceStartDate.focus();
                return false;
            }
            if ($assetInsuranceExpiredDate.val().length === 0) {
                $('#assetInsuranceExpiredDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetInsuranceExpiredDateLabel'] + " </span>");
                $('#assetInsuranceExpiredDateForm').removeClass().addClass('form-group has-error');
                $assetInsuranceExpiredDate.focus();
                return false;
            }
            if ($assetWarrantyStartDate.val().length === 0) {
                $('#assetWarrantyStartDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetWarrantyStartDateLabel'] + " </span>");
                $('#assetWarrantyStartDateForm').removeClass().addClass('form-group has-error');
                $assetWarrantyStartDate.focus();
                return false;
            }
            if ($assetWarrantyEndDate.val().length === 0) {
                $('#assetWarrantyEndDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetWarrantyEndDateLabel'] + " </span>");
                $('#assetWarrantyEndDateForm').removeClass().addClass('form-group has-error');
                $assetWarrantyEndDate.focus();
                return false;
            }
            if ($assetDepreciationRate.val().length === 0) {
                $('#assetDepreciationRateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDepreciationRateLabel'] + " </span>");
                $('#assetDepreciationRateForm').removeClass().addClass('form-group has-error');
                $assetDepreciationRate.focus();
                return false;
            }
            if ($assetNetBookValue.val().length === 0) {
                $('#assetNetBookValueHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetNetBookValueLabel'] + " </span>");
                $('#assetNetBookValueForm').removeClass().addClass('form-group has-error');
                $assetNetBookValue.focus();
                return false;
            }
            if ($assetPicture.val().length === 0) {
                $('#assetPictureHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetPictureLabel'] + " </span>");
                $('#assetPictureForm').removeClass().addClass('form-group has-error');
                $assetPicture.focus();
                return false;
            }
            if ($assetDescription.val().length === 0) {
                $('#assetDescriptionHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDescriptionLabel'] + " </span>");
                $('#assetDescriptionForm').removeClass().addClass('form-group has-error');
                $assetDescription.focus();
                return false;
            }
            if ($isTransferAsKit.val().length === 0) {
                $('#isTransferAsKitHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isTransferAsKitLabel'] + " </span>");
                $('#isTransferAsKitForm').removeClass().addClass('form-group has-error');
                $isTransferAsKit.focus();
                return false;
            }
            if ($isDepreciate.val().length === 0) {
                $('#isDepreciateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isDepreciateLabel'] + " </span>");
                $('#isDepreciateForm').removeClass().addClass('form-group has-error');
                $isDepreciate.focus();
                return false;
            }
            if ($isWriteOff.val().length === 0) {
                $('#isWriteOffHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isWriteOffLabel'] + " </span>");
                $('#isWriteOffForm').removeClass().addClass('form-group has-error');
                $isWriteOff.focus();
                return false;
            }
            if ($isDispose.val().length === 0) {
                $('#isDisposeHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isDisposeLabel'] + " </span>");
                $('#isDisposeForm').removeClass().addClass('form-group has-error');
                $isDispose.focus();
                return false;
            }
            if ($isAdjust.val().length === 0) {
                $('#isAdjustHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isAdjustLabel'] + " </span>");
                $('#isAdjustForm').removeClass().addClass('form-group has-error');
                $isAdjust.focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', branchId: $branchId.val(), departmentId: $departmentId.val(), warehouseId: $warehouseId.val(), locationId: $locationId.val(), itemCategoryId: $itemCategoryId.val(), itemTypeId: $itemTypeId.val(), businessPartnerId: $businessPartnerId.val(), unitOfMeasurementId: $unitOfMeasurementId.val(), purchaseInvoiceId: $purchaseInvoiceId.val(), assetCode: $assetCode.val(), assetSerialNumber: $assetSerialNumber.val(), assetName: $assetName.val(), assetModel: $assetModel.val(), assetPrice: $assetPrice.val(), assetDate: $assetDate.val(), assetWarranty: $assetWarranty.val(), assetColor: $assetColor.val(), assetQuantity: $assetQuantity.val(), assetInsuranceBusinessPartnerId: $assetInsuranceBusinessPartnerId.val(), assetInsuranceStartDate: $assetInsuranceStartDate.val(), assetInsuranceExpiredDate: $assetInsuranceExpiredDate.val(), assetWarrantyStartDate: $assetWarrantyStartDate.val(), assetWarrantyEndDate: $assetWarrantyEndDate.val(), assetDepreciationRate: $assetDepreciationRate.val(), assetNetBookValue: $assetNetBookValue.val(), assetPicture: $assetPicture.val(), assetDescription: $assetDescription.val(), isTransferAsKit: $isTransferAsKit.val(), isDepreciate: $isDepreciate.val(), isWriteOff: $isWriteOff.val(), isDispose: $isDispose.val(), isAdjust: $isAdjust.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
    var $branchId = $('#branchId');
    var $departmentId = $('#departmentId');
    var $warehouseId = $('#warehouseId');
    var $locationId = $('#locationId');
    var $itemCategoryId = $('#itemCategoryId');
    var $itemTypeId = $('#itemTypeId');
    var $businessPartnerId = $('#businessPartnerId');
    var $unitOfMeasurementId = $('#unitOfMeasurementId');
    var $purchaseInvoiceId = $('#purchaseInvoiceId');
    var $assetCode = $('#assetCode');
    var $assetSerialNumber = $('#assetSerialNumber');
    var $assetName = $('#assetName');
    var $assetModel = $('#assetModel');
    var $assetPrice = $('#assetPrice');
    var $assetDate = $('#assetDate');
    var $assetWarranty = $('#assetWarranty');
    var $assetColor = $('#assetColor');
    var $assetQuantity = $('#assetQuantity');
    var $assetInsuranceBusinessPartnerId = $('#assetInsuranceBusinessPartnerId');
    var $assetInsuranceStartDate = $('#assetInsuranceStartDate');
    var $assetInsuranceExpiredDate = $('#assetInsuranceExpiredDate');
    var $assetWarrantyStartDate = $('#assetWarrantyStartDate');
    var $assetWarrantyEndDate = $('#assetWarrantyEndDate');
    var $assetDepreciationRate = $('#assetDepreciationRate');
    var $assetNetBookValue = $('#assetNetBookValue');
    var $assetPicture = $('#assetPicture');
    var $assetDescription = $('#assetDescription');
    var $isTransferAsKit = $('#isTransferAsKit');
    var $isDepreciate = $('#isDepreciate');
    var $isWriteOff = $('#isWriteOff');
    var $isDispose = $('#isDispose');
    var $isAdjust = $('#isAdjust');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $infoPanel.empty().html('');
        if (type === 1) {
            if ($branchId.val().length === 0) {
                $('#branchIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['branchIdLabel'] + " </span>");
                $branchId.data('chosen').activate_action();
                return false;
            }
            if ($departmentId.val().length === 0) {
                $('#departmentIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['departmentIdLabel'] + " </span>");
                $departmentId.data('chosen').activate_action();
                return false;
            }
            if ($warehouseId.val().length === 0) {
                $('#warehouseIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['warehouseIdLabel'] + " </span>");
                $warehouseId.data('chosen').activate_action();
                return false;
            }
            if ($locationId.val().length === 0) {
                $('#locationIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['locationIdLabel'] + " </span>");
                $locationId.data('chosen').activate_action();
                return false;
            }
            if ($itemCategoryId.val().length === 0) {
                $('#itemCategoryIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemCategoryIdLabel'] + " </span>");
                $itemCategoryId.data('chosen').activate_action();
                return false;
            }
            if ($itemTypeId.val().length === 0) {
                $('#itemTypeIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemTypeIdLabel'] + " </span>");
                $itemTypeId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerId.val().length === 0) {
                $('#businessPartnerIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $businessPartnerId.data('chosen').activate_action();
                return false;
            }
            if ($unitOfMeasurementId.val().length === 0) {
                $('#unitOfMeasurementIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['unitOfMeasurementIdLabel'] + " </span>");
                $unitOfMeasurementId.data('chosen').activate_action();
                return false;
            }
            if ($purchaseInvoiceId.val().length === 0) {
                $('#purchaseInvoiceIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['purchaseInvoiceIdLabel'] + " </span>");
                $purchaseInvoiceId.data('chosen').activate_action();
                return false;
            }
            if ($assetCode.val().length === 0) {
                $('#assetCodeHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetCodeLabel'] + " </span>");
                $('#assetCodeForm').removeClass().addClass('form-group has-error');
                $assetCode.focus();
                return false;
            }
            if ($assetSerialNumber.val().length === 0) {
                $('#assetSerialNumberHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSerialNumberLabel'] + " </span>");
                $('#assetSerialNumberForm').removeClass().addClass('form-group has-error');
                $assetSerialNumber.focus();
                return false;
            }
            if ($assetName.val().length === 0) {
                $('#assetNameHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetNameLabel'] + " </span>");
                $('#assetNameForm').removeClass().addClass('form-group has-error');
                $assetName.focus();
                return false;
            }
            if ($assetModel.val().length === 0) {
                $('#assetModelHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetModelLabel'] + " </span>");
                $('#assetModelForm').removeClass().addClass('form-group has-error');
                $assetModel.focus();
                return false;
            }
            if ($assetPrice.val().length === 0) {
                $('#assetPriceHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetPriceLabel'] + " </span>");
                $('#assetPriceForm').removeClass().addClass('form-group has-error');
                $assetPrice.focus();
                return false;
            }
            if ($assetDate.val().length === 0) {
                $('#assetDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDateLabel'] + " </span>");
                $('#assetDateForm').removeClass().addClass('form-group has-error');
                $assetDate.focus();
                return false;
            }
            if ($assetWarranty.val().length === 0) {
                $('#assetWarrantyHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetWarrantyLabel'] + " </span>");
                $('#assetWarrantyForm').removeClass().addClass('form-group has-error');
                $assetWarranty.focus();
                return false;
            }
            if ($assetColor.val().length === 0) {
                $('#assetColorHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetColorLabel'] + " </span>");
                $('#assetColorForm').removeClass().addClass('form-group has-error');
                $assetColor.focus();
                return false;
            }
            if ($assetQuantity.val().length === 0) {
                $('#assetQuantityHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetQuantityLabel'] + " </span>");
                $('#assetQuantityForm').removeClass().addClass('form-group has-error');
                $assetQuantity.focus();
                return false;
            }
            if ($assetInsuranceBusinessPartnerId.val().length === 0) {
                $('#assetInsuranceBusinessPartnerIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetInsuranceBusinessPartnerIdLabel'] + " </span>");
                $('#assetInsuranceBusinessPartnerIdForm').removeClass().addClass('form-group has-error');
                $assetInsuranceBusinessPartnerId.focus();
                return false;
            }
            if ($assetInsuranceStartDate.val().length === 0) {
                $('#assetInsuranceStartDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetInsuranceStartDateLabel'] + " </span>");
                $('#assetInsuranceStartDateForm').removeClass().addClass('form-group has-error');
                $assetInsuranceStartDate.focus();
                return false;
            }
            if ($assetInsuranceExpiredDate.val().length === 0) {
                $('#assetInsuranceExpiredDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetInsuranceExpiredDateLabel'] + " </span>");
                $('#assetInsuranceExpiredDateForm').removeClass().addClass('form-group has-error');
                $assetInsuranceExpiredDate.focus();
                return false;
            }
            if ($assetWarrantyStartDate.val().length === 0) {
                $('#assetWarrantyStartDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetWarrantyStartDateLabel'] + " </span>");
                $('#assetWarrantyStartDateForm').removeClass().addClass('form-group has-error');
                $assetWarrantyStartDate.focus();
                return false;
            }
            if ($assetWarrantyEndDate.val().length === 0) {
                $('#assetWarrantyEndDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetWarrantyEndDateLabel'] + " </span>");
                $('#assetWarrantyEndDateForm').removeClass().addClass('form-group has-error');
                $assetWarrantyEndDate.focus();
                return false;
            }
            if ($assetDepreciationRate.val().length === 0) {
                $('#assetDepreciationRateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDepreciationRateLabel'] + " </span>");
                $('#assetDepreciationRateForm').removeClass().addClass('form-group has-error');
                $assetDepreciationRate.focus();
                return false;
            }
            if ($assetNetBookValue.val().length === 0) {
                $('#assetNetBookValueHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetNetBookValueLabel'] + " </span>");
                $('#assetNetBookValueForm').removeClass().addClass('form-group has-error');
                $assetNetBookValue.focus();
                return false;
            }
            if ($assetPicture.val().length === 0) {
                $('#assetPictureHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetPictureLabel'] + " </span>");
                $('#assetPictureForm').removeClass().addClass('form-group has-error');
                $assetPicture.focus();
                return false;
            }
            if ($assetDescription.val().length === 0) {
                $('#assetDescriptionHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDescriptionLabel'] + " </span>");
                $('#assetDescriptionForm').removeClass().addClass('form-group has-error');
                $assetDescription.focus();
                return false;
            }
            if ($isTransferAsKit.val().length === 0) {
                $('#isTransferAsKitHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isTransferAsKitLabel'] + " </span>");
                $('#isTransferAsKitForm').removeClass().addClass('form-group has-error');
                $isTransferAsKit.focus();
                return false;
            }
            if ($isDepreciate.val().length === 0) {
                $('#isDepreciateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isDepreciateLabel'] + " </span>");
                $('#isDepreciateForm').removeClass().addClass('form-group has-error');
                $isDepreciate.focus();
                return false;
            }
            if ($isWriteOff.val().length === 0) {
                $('#isWriteOffHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isWriteOffLabel'] + " </span>");
                $('#isWriteOffForm').removeClass().addClass('form-group has-error');
                $isWriteOff.focus();
                return false;
            }
            if ($isDispose.val().length === 0) {
                $('#isDisposeHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isDisposeLabel'] + " </span>");
                $('#isDisposeForm').removeClass().addClass('form-group has-error');
                $isDispose.focus();
                return false;
            }
            if ($isAdjust.val().length === 0) {
                $('#isAdjustHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isAdjustLabel'] + " </span>");
                $('#isAdjustForm').removeClass().addClass('form-group has-error');
                $isAdjust.focus();
                return false;
            }
            $infoPanel.html('').empty();
            $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', assetId: $assetId.val(), branchId: $branchId.val(), departmentId: $departmentId.val(), warehouseId: $warehouseId.val(), locationId: $locationId.val(), itemCategoryId: $itemCategoryId.val(), itemTypeId: $itemTypeId.val(), businessPartnerId: $businessPartnerId.val(), unitOfMeasurementId: $unitOfMeasurementId.val(), purchaseInvoiceId: $purchaseInvoiceId.val(), assetCode: $assetCode.val(), assetSerialNumber: $assetSerialNumber.val(), assetName: $assetName.val(), assetModel: $assetModel.val(), assetPrice: $assetPrice.val(), assetDate: $assetDate.val(), assetWarranty: $assetWarranty.val(), assetColor: $assetColor.val(), assetQuantity: $assetQuantity.val(), assetInsuranceBusinessPartnerId: $assetInsuranceBusinessPartnerId.val(), assetInsuranceStartDate: $assetInsuranceStartDate.val(), assetInsuranceExpiredDate: $assetInsuranceExpiredDate.val(), assetWarrantyStartDate: $assetWarrantyStartDate.val(), assetWarrantyEndDate: $assetWarrantyEndDate.val(), assetDepreciationRate: $assetDepreciationRate.val(), assetNetBookValue: $assetNetBookValue.val(), assetPicture: $assetPicture.val(), assetDescription: $assetDescription.val(), isTransferAsKit: $isTransferAsKit.val(), isDepreciate: $isDepreciate.val(), isWriteOff: $isWriteOff.val(), isDispose: $isDispose.val(), isAdjust: $isAdjust.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        } else if (type === 3) {
            if ($branchId.val().length === 0) {
                $('#branchIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['branchIdLabel'] + " </span>");
                $branchId.data('chosen').activate_action();
                return false;
            }
            if ($departmentId.val().length === 0) {
                $('#departmentIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['departmentIdLabel'] + " </span>");
                $departmentId.data('chosen').activate_action();
                return false;
            }
            if ($warehouseId.val().length === 0) {
                $('#warehouseIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['warehouseIdLabel'] + " </span>");
                $warehouseId.data('chosen').activate_action();
                return false;
            }
            if ($locationId.val().length === 0) {
                $('#locationIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['locationIdLabel'] + " </span>");
                $locationId.data('chosen').activate_action();
                return false;
            }
            if ($itemCategoryId.val().length === 0) {
                $('#itemCategoryIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemCategoryIdLabel'] + " </span>");
                $itemCategoryId.data('chosen').activate_action();
                return false;
            }
            if ($itemTypeId.val().length === 0) {
                $('#itemTypeIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['itemTypeIdLabel'] + " </span>");
                $itemTypeId.data('chosen').activate_action();
                return false;
            }
            if ($businessPartnerId.val().length === 0) {
                $('#businessPartnerIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $businessPartnerId.data('chosen').activate_action();
                return false;
            }
            if ($unitOfMeasurementId.val().length === 0) {
                $('#unitOfMeasurementIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['unitOfMeasurementIdLabel'] + " </span>");
                $unitOfMeasurementId.data('chosen').activate_action();
                return false;
            }
            if ($purchaseInvoiceId.val().length === 0) {
                $('#purchaseInvoiceIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['purchaseInvoiceIdLabel'] + " </span>");
                $purchaseInvoiceId.data('chosen').activate_action();
                return false;
            }
            if ($assetCode.val().length === 0) {
                $('#assetCodeHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetCodeLabel'] + " </span>");
                $('#assetCodeForm').removeClass().addClass('form-group has-error');
                $assetCode.focus();
                return false;
            }
            if ($assetSerialNumber.val().length === 0) {
                $('#assetSerialNumberHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetSerialNumberLabel'] + " </span>");
                $('#assetSerialNumberForm').removeClass().addClass('form-group has-error');
                $assetSerialNumber.focus();
                return false;
            }
            if ($assetName.val().length === 0) {
                $('#assetNameHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetNameLabel'] + " </span>");
                $('#assetNameForm').removeClass().addClass('form-group has-error');
                $assetName.focus();
                return false;
            }
            if ($assetModel.val().length === 0) {
                $('#assetModelHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetModelLabel'] + " </span>");
                $('#assetModelForm').removeClass().addClass('form-group has-error');
                $assetModel.focus();
                return false;
            }
            if ($assetPrice.val().length === 0) {
                $('#assetPriceHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetPriceLabel'] + " </span>");
                $('#assetPriceForm').removeClass().addClass('form-group has-error');
                $assetPrice.focus();
                return false;
            }
            if ($assetDate.val().length === 0) {
                $('#assetDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDateLabel'] + " </span>");
                $('#assetDateForm').removeClass().addClass('form-group has-error');
                $assetDate.focus();
                return false;
            }
            if ($assetWarranty.val().length === 0) {
                $('#assetWarrantyHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetWarrantyLabel'] + " </span>");
                $('#assetWarrantyForm').removeClass().addClass('form-group has-error');
                $assetWarranty.focus();
                return false;
            }
            if ($assetColor.val().length === 0) {
                $('#assetColorHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetColorLabel'] + " </span>");
                $('#assetColorForm').removeClass().addClass('form-group has-error');
                $assetColor.focus();
                return false;
            }
            if ($assetQuantity.val().length === 0) {
                $('#assetQuantityHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetQuantityLabel'] + " </span>");
                $('#assetQuantityForm').removeClass().addClass('form-group has-error');
                $assetQuantity.focus();
                return false;
            }
            if ($assetInsuranceBusinessPartnerId.val().length === 0) {
                $('#assetInsuranceBusinessPartnerIdHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetInsuranceBusinessPartnerIdLabel'] + " </span>");
                $('#assetInsuranceBusinessPartnerIdForm').removeClass().addClass('form-group has-error');
                $assetInsuranceBusinessPartnerId.focus();
                return false;
            }
            if ($assetInsuranceStartDate.val().length === 0) {
                $('#assetInsuranceStartDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetInsuranceStartDateLabel'] + " </span>");
                $('#assetInsuranceStartDateForm').removeClass().addClass('form-group has-error');
                $assetInsuranceStartDate.focus();
                return false;
            }
            if ($assetInsuranceExpiredDate.val().length === 0) {
                $('#assetInsuranceExpiredDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetInsuranceExpiredDateLabel'] + " </span>");
                $('#assetInsuranceExpiredDateForm').removeClass().addClass('form-group has-error');
                $assetInsuranceExpiredDate.focus();
                return false;
            }
            if ($assetWarrantyStartDate.val().length === 0) {
                $('#assetWarrantyStartDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetWarrantyStartDateLabel'] + " </span>");
                $('#assetWarrantyStartDateForm').removeClass().addClass('form-group has-error');
                $assetWarrantyStartDate.focus();
                return false;
            }
            if ($assetWarrantyEndDate.val().length === 0) {
                $('#assetWarrantyEndDateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetWarrantyEndDateLabel'] + " </span>");
                $('#assetWarrantyEndDateForm').removeClass().addClass('form-group has-error');
                $assetWarrantyEndDate.focus();
                return false;
            }
            if ($assetDepreciationRate.val().length === 0) {
                $('#assetDepreciationRateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDepreciationRateLabel'] + " </span>");
                $('#assetDepreciationRateForm').removeClass().addClass('form-group has-error');
                $assetDepreciationRate.focus();
                return false;
            }
            if ($assetNetBookValue.val().length === 0) {
                $('#assetNetBookValueHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetNetBookValueLabel'] + " </span>");
                $('#assetNetBookValueForm').removeClass().addClass('form-group has-error');
                $assetNetBookValue.focus();
                return false;
            }
            if ($assetPicture.val().length === 0) {
                $('#assetPictureHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetPictureLabel'] + " </span>");
                $('#assetPictureForm').removeClass().addClass('form-group has-error');
                $assetPicture.focus();
                return false;
            }
            if ($assetDescription.val().length === 0) {
                $('#assetDescriptionHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['assetDescriptionLabel'] + " </span>");
                $('#assetDescriptionForm').removeClass().addClass('form-group has-error');
                $assetDescription.focus();
                return false;
            }
            if ($isTransferAsKit.val().length === 0) {
                $('#isTransferAsKitHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isTransferAsKitLabel'] + " </span>");
                $('#isTransferAsKitForm').removeClass().addClass('form-group has-error');
                $isTransferAsKit.focus();
                return false;
            }
            if ($isDepreciate.val().length === 0) {
                $('#isDepreciateHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isDepreciateLabel'] + " </span>");
                $('#isDepreciateForm').removeClass().addClass('form-group has-error');
                $isDepreciate.focus();
                return false;
            }
            if ($isWriteOff.val().length === 0) {
                $('#isWriteOffHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isWriteOffLabel'] + " </span>");
                $('#isWriteOffForm').removeClass().addClass('form-group has-error');
                $isWriteOff.focus();
                return false;
            }
            if ($isDispose.val().length === 0) {
                $('#isDisposeHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isDisposeLabel'] + " </span>");
                $('#isDisposeForm').removeClass().addClass('form-group has-error');
                $isDispose.focus();
                return false;
            }
            if ($isAdjust.val().length === 0) {
                $('#isAdjustHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['isAdjustLabel'] + " </span>");
                $('#isAdjustForm').removeClass().addClass('form-group has-error');
                $isAdjust.focus();
                return false;
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', assetId: $assetId.val(), branchId: $branchId.val(), departmentId: $departmentId.val(), warehouseId: $warehouseId.val(), locationId: $locationId.val(), itemCategoryId: $itemCategoryId.val(), itemTypeId: $itemTypeId.val(), businessPartnerId: $businessPartnerId.val(), unitOfMeasurementId: $unitOfMeasurementId.val(), purchaseInvoiceId: $purchaseInvoiceId.val(), assetCode: $assetCode.val(), assetSerialNumber: $assetSerialNumber.val(), assetName: $assetName.val(), assetModel: $assetModel.val(), assetPrice: $assetPrice.val(), assetDate: $assetDate.val(), assetWarranty: $assetWarranty.val(), assetColor: $assetColor.val(), assetQuantity: $assetQuantity.val(), assetInsuranceBusinessPartnerId: $assetInsuranceBusinessPartnerId.val(), assetInsuranceStartDate: $assetInsuranceStartDate.val(), assetInsuranceExpiredDate: $assetInsuranceExpiredDate.val(), assetWarrantyStartDate: $assetWarrantyStartDate.val(), assetWarrantyEndDate: $assetWarrantyEndDate.val(), assetDepreciationRate: $assetDepreciationRate.val(), assetNetBookValue: $assetNetBookValue.val(), assetPicture: $assetPicture.val(), assetDescription: $assetDescription.val(), isTransferAsKit: $isTransferAsKit.val(), isDepreciate: $isDepreciate.val(), isWriteOff: $isWriteOff.val(), isDispose: $isDispose.val(), isAdjust: $isAdjust.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        }
    }
}
function deleteRecord(leafId, url, urlList, securityToken, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var $assetId = $('#assetId');
    var css = $('#deleteRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (deleteAccess === 1) {
            if (confirm(decodeURIComponent(t['deleteRecordMessageLabel']))) {
                var value = $assetId.val();
                if (!value) {
                    $infoPanel.html('').empty().html("<span class='label label-important'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "<span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                    return false;
                } else {
                    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', assetId: $assetId.val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
function resetRecord(leafId, url, urlList, securityToken, createAccess, updateAccess, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var resetIcon = './images/icons/fruit-orange.png';
    $infoPanel.html('').empty().html("<span class='label label-important'><img src='" + resetIcon + "'> " + decodeURIComponent(t['resetRecordTextLabel']) + "</span>").delay(1000).fadeOut();
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
    $('#firstRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "firstRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
    $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
    $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
    $('#endRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "endRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "'," + updateAccess + ")");
    $("#assetId").val('');
    $("#assetIdHelpMe").empty().html('');
    
    $("#branchId").val('');
    $("#branchIdHelpMe").empty().html('');
    $('#branchId').trigger("chosen:updated");
    $("#departmentId").val('');
    $("#departmentIdHelpMe").empty().html('');
    $('#departmentId').trigger("chosen:updated");
    $("#warehouseId").val('');
    $("#warehouseIdHelpMe").empty().html('');
    $('#warehouseId').trigger("chosen:updated");
    $("#locationId").val('');
    $("#locationIdHelpMe").empty().html('');
    $('#locationId').trigger("chosen:updated");
    $("#itemCategoryId").val('');
    $("#itemCategoryIdHelpMe").empty().html('');
    $('#itemCategoryId').trigger("chosen:updated");
    $("#itemTypeId").val('');
    $("#itemTypeIdHelpMe").empty().html('');
    $('#itemTypeId').trigger("chosen:updated");
    $("#businessPartnerId").val('');
    $("#businessPartnerIdHelpMe").empty().html('');
    $('#businessPartnerId').trigger("chosen:updated");
    $("#unitOfMeasurementId").val('');
    $("#unitOfMeasurementIdHelpMe").empty().html('');
    $('#unitOfMeasurementId').trigger("chosen:updated");
    $("#purchaseInvoiceId").val('');
    $("#purchaseInvoiceIdHelpMe").empty().html('');
    $('#purchaseInvoiceId').trigger("chosen:updated");
    $("#assetCode").val('');
    $("#assetCodeHelpMe").empty().html('');
    $("#assetSerialNumber").val('');
    $("#assetSerialNumberHelpMe").empty().html('');
    $("#assetName").val('');
    $("#assetNameHelpMe").empty().html('');
    $("#assetModel").val('');
    $("#assetModelHelpMe").empty().html('');
    $("#assetPrice").val('');
    $("#assetPriceHelpMe").empty().html('');
    $("#assetDate").val('');
    $("#assetDateHelpMe").empty().html('');
    $("#assetWarranty").val('');
    $("#assetWarrantyHelpMe").empty().html('');
    $("#assetColor").val('');
    $("#assetColorHelpMe").empty().html('');
    $("#assetQuantity").val('');
    $("#assetQuantityHelpMe").empty().html('');
    $("#assetInsuranceBusinessPartnerId").val('');
    $("#assetInsuranceBusinessPartnerIdHelpMe").empty().html('');
    $('#assetInsuranceBusinessPartnerId').trigger("chosen:updated");
    $("#assetInsuranceStartDate").val('');
    $("#assetInsuranceStartDateHelpMe").empty().html('');
    $("#assetInsuranceExpiredDate").val('');
    $("#assetInsuranceExpiredDateHelpMe").empty().html('');
    $("#assetWarrantyStartDate").val('');
    $("#assetWarrantyStartDateHelpMe").empty().html('');
    $("#assetWarrantyEndDate").val('');
    $("#assetWarrantyEndDateHelpMe").empty().html('');
    $("#assetDepreciationRate").val('');
    $("#assetDepreciationRateHelpMe").empty().html('');
    $("#assetNetBookValue").val('');
    $("#assetNetBookValueHelpMe").empty().html('');
    $("#assetPicture").val('');
    $("#assetPictureHelpMe").empty().html('');
    $("#assetDescription").val('');
    $("#assetDescriptionHelpMe").empty().html('');
    $('#assetDescription').empty().val('');
    $("#isTransferAsKit").val('');
    $("#isTransferAsKitHelpMe").empty().html('');
    $("#isDepreciate").val('');
    $("#isDepreciateHelpMe").empty().html('');
    $("#isWriteOff").val('');
    $("#isWriteOffHelpMe").empty().html('');
    $("#isDispose").val('');
    $("#isDisposeHelpMe").empty().html('');
    $("#isAdjust").val('');
    $("#isAdjustHelpMe").empty().html('');
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
                    $.ajax({type: 'POST', url: url, data: {method: 'read', assetId: firstRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                                $('#assetId').val(data.data.assetId);
                                $('#branchId').val(data.data.branchId).trigger("chosen:updated");
                                $('#departmentId').val(data.data.departmentId).trigger("chosen:updated");
                                $('#warehouseId').val(data.data.warehouseId).trigger("chosen:updated");
                                $('#locationId').val(data.data.locationId).trigger("chosen:updated");
                                $('#itemCategoryId').val(data.data.itemCategoryId).trigger("chosen:updated");
                                $('#itemTypeId').val(data.data.itemTypeId).trigger("chosen:updated");
                                $('#businessPartnerId').val(data.data.businessPartnerId).trigger("chosen:updated");
                                $('#unitOfMeasurementId').val(data.data.unitOfMeasurementId).trigger("chosen:updated");
                                $('#purchaseInvoiceId').val(data.data.purchaseInvoiceId).trigger("chosen:updated");
                                $('#assetCode').val(data.data.assetCode);
                                $('#assetSerialNumber').val(data.data.assetSerialNumber);
                                $('#assetName').val(data.data.assetName);
                                $('#assetModel').val(data.data.assetModel);
                                $('#assetPrice').val(data.data.assetPrice);
                                x = data.data.assetDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#assetDate').val(output);
                                $('#assetWarranty').val(data.data.assetWarranty);
                                $('#assetColor').val(data.data.assetColor);
                                $('#assetQuantity').val(data.data.assetQuantity);
                                $('#assetInsuranceBusinessPartnerId').val(data.data.assetInsuranceBusinessPartnerId).trigger("chosen:updated");
                                x = data.data.assetInsuranceStartDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#assetInsuranceStartDate').val(output);
                                x = data.data.assetInsuranceExpiredDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#assetInsuranceExpiredDate').val(output);
                                x = data.data.assetWarrantyStartDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#assetWarrantyStartDate').val(output);
                                x = data.data.assetWarrantyEndDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#assetWarrantyEndDate').val(output);
                                $('#assetDepreciationRate').val(data.data.assetDepreciationRate);
                                $('#assetNetBookValue').val(data.data.assetNetBookValue);
                                $('#assetPicture').val(data.data.assetPicture);
                                $('#assetDescription').val(data.data.assetDescription);
                                $('#isTransferAsKit').val(data.data.isTransferAsKit);
                                $('#isDepreciate').val(data.data.isDepreciate);
                                $('#isWriteOff').val(data.data.isWriteOff);
                                $('#isDispose').val(data.data.isDispose);
                                $('#isAdjust').val(data.data.isAdjust);
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
                            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                    $.ajax({type: 'POST', url: url, data: {method: 'read', assetId: lastRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                                $('#assetId').val(data.data.assetId);
                                $('#branchId').val(data.data.branchId).trigger("chosen:updated");
                                $('#departmentId').val(data.data.departmentId).trigger("chosen:updated");
                                $('#warehouseId').val(data.data.warehouseId).trigger("chosen:updated");
                                $('#locationId').val(data.data.locationId).trigger("chosen:updated");
                                $('#itemCategoryId').val(data.data.itemCategoryId).trigger("chosen:updated");
                                $('#itemTypeId').val(data.data.itemTypeId).trigger("chosen:updated");
                                $('#businessPartnerId').val(data.data.businessPartnerId).trigger("chosen:updated");
                                $('#unitOfMeasurementId').val(data.data.unitOfMeasurementId).trigger("chosen:updated");
                                $('#purchaseInvoiceId').val(data.data.purchaseInvoiceId).trigger("chosen:updated");
                                $('#assetCode').val(data.data.assetCode);
                                $('#assetSerialNumber').val(data.data.assetSerialNumber);
                                $('#assetName').val(data.data.assetName);
                                $('#assetModel').val(data.data.assetModel);
                                $('#assetPrice').val(data.data.assetPrice);
                                x = data.data.assetDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#assetDate').val(output);
                                $('#assetWarranty').val(data.data.assetWarranty);
                                $('#assetColor').val(data.data.assetColor);
                                $('#assetQuantity').val(data.data.assetQuantity);
                                $('#assetInsuranceBusinessPartnerId').val(data.data.assetInsuranceBusinessPartnerId).trigger("chosen:updated");
                                x = data.data.assetInsuranceStartDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#assetInsuranceStartDate').val(output);
                                x = data.data.assetInsuranceExpiredDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#assetInsuranceExpiredDate').val(output);
                                x = data.data.assetWarrantyStartDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#assetWarrantyStartDate').val(output);
                                x = data.data.assetWarrantyEndDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#assetWarrantyEndDate').val(output);
                                $('#assetDepreciationRate').val(data.data.assetDepreciationRate);
                                $('#assetNetBookValue').val(data.data.assetNetBookValue);
                                $('#assetPicture').val(data.data.assetPicture);
                                $('#assetDescription').val(data.data.assetDescription);
                                ;
                                $('#isTransferAsKit').val(data.data.isTransferAsKit);
                                $('#isDepreciate').val(data.data.isDepreciate);
                                $('#isWriteOff').val(data.data.isWriteOff);
                                $('#isDispose').val(data.data.isDispose);
                                $('#isAdjust').val(data.data.isAdjust);
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
                            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                        }});
                } else {
                    $infoPanel.html("<span class='label label-important'>&nbsp;" + message + "</span>");
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
            $.ajax({type: 'POST', url: url, data: {method: 'read', assetId: $previousRecordCounter.val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                        $('#assetId').val(data.data.assetId);
                        $('#branchId').val(data.data.branchId).trigger("chosen:updated");
                        $('#departmentId').val(data.data.departmentId).trigger("chosen:updated");
                        $('#warehouseId').val(data.data.warehouseId).trigger("chosen:updated");
                        $('#locationId').val(data.data.locationId).trigger("chosen:updated");
                        $('#itemCategoryId').val(data.data.itemCategoryId).trigger("chosen:updated");
                        $('#itemTypeId').val(data.data.itemTypeId).trigger("chosen:updated");
                        $('#businessPartnerId').val(data.data.businessPartnerId).trigger("chosen:updated");
                        $('#unitOfMeasurementId').val(data.data.unitOfMeasurementId).trigger("chosen:updated");
                        $('#purchaseInvoiceId').val(data.data.purchaseInvoiceId).trigger("chosen:updated");
                        $('#assetCode').val(data.data.assetCode);
                        $('#assetSerialNumber').val(data.data.assetSerialNumber);
                        $('#assetName').val(data.data.assetName);
                        $('#assetModel').val(data.data.assetModel);
                        $('#assetPrice').val(data.data.assetPrice);
                        x = data.data.assetDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#assetDate').val(output);
                        $('#assetWarranty').val(data.data.assetWarranty);
                        $('#assetColor').val(data.data.assetColor);
                        $('#assetQuantity').val(data.data.assetQuantity);
                        $('#assetInsuranceBusinessPartnerId').val(data.data.assetInsuranceBusinessPartnerId).trigger("chosen:updated");
                        x = data.data.assetInsuranceStartDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#assetInsuranceStartDate').val(output);
                        x = data.data.assetInsuranceExpiredDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#assetInsuranceExpiredDate').val(output);
                        x = data.data.assetWarrantyStartDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#assetWarrantyStartDate').val(output);
                        x = data.data.assetWarrantyEndDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#assetWarrantyEndDate').val(output);
                        $('#assetDepreciationRate').val(data.data.assetDepreciationRate);
                        $('#assetNetBookValue').val(data.data.assetNetBookValue);
                        $('#assetPicture').val(data.data.assetPicture);
                        $('#assetDescription').val(data.data.assetDescription);
                        ;
                        $('#isTransferAsKit').val(data.data.isTransferAsKit);
                        $('#isDepreciate').val(data.data.isDepreciate);
                        $('#isWriteOff').val(data.data.isWriteOff);
                        $('#isDispose').val(data.data.isDispose);
                        $('#isAdjust').val(data.data.isAdjust);
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
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
            $.ajax({type: 'POST', url: url, data: {method: 'read', assetId: $nextRecordCounter.val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                        $('#assetId').val(data.data.assetId);
                        $('#branchId').val(data.data.branchId).trigger("chosen:updated");
                        $('#departmentId').val(data.data.departmentId).trigger("chosen:updated");
                        $('#warehouseId').val(data.data.warehouseId).trigger("chosen:updated");
                        $('#locationId').val(data.data.locationId).trigger("chosen:updated");
                        $('#itemCategoryId').val(data.data.itemCategoryId).trigger("chosen:updated");
                        $('#itemTypeId').val(data.data.itemTypeId).trigger("chosen:updated");
                        $('#businessPartnerId').val(data.data.businessPartnerId).trigger("chosen:updated");
                        $('#unitOfMeasurementId').val(data.data.unitOfMeasurementId).trigger("chosen:updated");
                        $('#purchaseInvoiceId').val(data.data.purchaseInvoiceId).trigger("chosen:updated");
                        $('#assetCode').val(data.data.assetCode);
                        $('#assetSerialNumber').val(data.data.assetSerialNumber);
                        $('#assetName').val(data.data.assetName);
                        $('#assetModel').val(data.data.assetModel);
                        $('#assetPrice').val(data.data.assetPrice);
                        x = data.data.assetDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#assetDate').val(output);
                        $('#assetWarranty').val(data.data.assetWarranty);
                        $('#assetColor').val(data.data.assetColor);
                        $('#assetQuantity').val(data.data.assetQuantity);
                        $('#assetInsuranceBusinessPartnerId').val(data.data.assetInsuranceBusinessPartnerId).trigger("chosen:updated");
                        x = data.data.assetInsuranceStartDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#assetInsuranceStartDate').val(output);
                        x = data.data.assetInsuranceExpiredDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#assetInsuranceExpiredDate').val(output);
                        x = data.data.assetWarrantyStartDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#assetWarrantyStartDate').val(output);
                        x = data.data.assetWarrantyEndDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#assetWarrantyEndDate').val(output);
                        $('#assetDepreciationRate').val(data.data.assetDepreciationRate);
                        $('#assetNetBookValue').val(data.data.assetNetBookValue);
                        $('#assetPicture').val(data.data.assetPicture);
                        $('#assetDescription').val(data.data.assetDescription);
                        $('#isTransferAsKit').val(data.data.isTransferAsKit);
                        $('#isDepreciate').val(data.data.isDepreciate);
                        $('#isWriteOff').val(data.data.isWriteOff);
                        $('#isDispose').val(data.data.isDispose);
                        $('#isAdjust').val(data.data.isAdjust);
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
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }});
        }
    }
}
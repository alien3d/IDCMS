function getCity(leafId, url, securityToken) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            securityToken: securityToken,
            leafId: leafId,
            stateId: $("#stateId").val(),
            filter: 'city'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#cityId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function getState(leafId, url, securityToken) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'state'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#stateId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function getCountry(leafId, url, securityToken) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'country'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#countryId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function getJob(leafId, url, securityToken) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'job'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#jobId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function getGender(leafId, url, securityToken) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'gender'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#genderId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function getMarriage(leafId, url, securityToken) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'marriage'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#marriageId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function getRace(leafId, url, securityToken) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'race'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#raceId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function getReligion(leafId, url, securityToken) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'religion'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#religionId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function getEmploymentStatus(leafId, url, securityToken) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'employmentStatus'
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#employmentStatusId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function checkDuplicate(leafId, page, securityToken) {
    var $employeeCode = $("#employeeCode");
    if ($employeeCode.val().length === 0) {
        alert(t['oddTextLabel']);
        return false;
    }
    $.ajax({
        type: 'GET',
        url: page,
        data: {
            employeeCode: $employeeCode.val(),
            method: 'duplicate',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            var total = data.total;
            if (success === true) {
                if (total !== 0) {
                    $("#employeeCode").val('').focus();
                    $("#employeeCodeForm").removeClass().addClass("form-group has-error");
                    $infoPanel.html('').empty().html("<img src='" + smileyRoll + "'> " + t['codeDuplicateTextLabel']).delay(5000).fadeOut();
                } else {
                    $infoPanel.html('').empty().html("<img src='" + smileyLol + "'> " + t['codeAvailableTextLabel']).delay(5000).fadeOut();
                }
            } else {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
                $("#employeeForm").removeClass().addClass("form-group has-error");
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
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
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
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
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
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
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
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
            $infoPanel.html('').empty().html("&nbsp;<img src='" + zoomIcon + "'> <b>" + decodeURIComponent(t['filterTextLabel']) + '</b>: ' + queryText + "");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $(document).scrollTop();
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function ajaxQuerySearchAllCharacter(leafId, url, securityToken, character) {
    $('#clearSearch').removeClass().addClass('btn btn-primary');
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
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
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
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html('').html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
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
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
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
            $infoPanel.html('').empty().html("<img src='" + imageCalendarPath + "'> " + strDate + " ");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $(document).scrollTop();
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
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
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
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
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function showFormUpdate(leafId, url, urlList, securityToken, employeeId, updateAccess, deleteAccess) {
    sleep(500);
    $('a[rel=tooltip]').tooltip('hide');
    $.ajax({
        type: 'POST',
        url: urlList,
        data: {
            method: 'read',
            type: 'form',
            employeeId: employeeId,
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
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
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function showModalDelete(employeeId, cityId, stateId, countryId, jobId, genderId, marriageId, raceId, religionId, employmentStatusId, nationalNumber, licenseNumber, employeeNumber, employeeFirstName, employeePicture, employeeLastName, employeeDateOfBirth, employeeDateHired, employeeDateRetired, employeeBusinessPhone, employeeHomePhone, employeeMobilePhone, employeeFaxNumber, employeeAddress, employeePostCode, employeeEmail, employeeFacebook, employeeTwitter, employeeLinkedIn, employeeNotes, employeeChequePrinting) {
    $('#employeeIdPreview').val('').val(decodeURIComponent(employeeId));
    $('#cityIdPreview').val('').val(decodeURIComponent(cityId));
    $('#stateIdPreview').val('').val(decodeURIComponent(stateId));
    $('#countryIdPreview').val('').val(decodeURIComponent(countryId));
    $('#jobIdPreview').val('').val(decodeURIComponent(jobId));
    $('#genderIdPreview').val('').val(decodeURIComponent(genderId));
    $('#marriageIdPreview').val('').val(decodeURIComponent(marriageId));
    $('#raceIdPreview').val('').val(decodeURIComponent(raceId));
    $('#religionIdPreview').val('').val(decodeURIComponent(religionId));
    $('#employmentStatusIdPreview').val('').val(decodeURIComponent(employmentStatusId));
    $('#nationalNumberPreview').val('').val(decodeURIComponent(nationalNumber));
    $('#licenseNumberPreview').val('').val(decodeURIComponent(licenseNumber));
    $('#employeeNumberPreview').val('').val(decodeURIComponent(employeeNumber));
    $('#employeeFirstNamePreview').val('').val(decodeURIComponent(employeeFirstName));
    $('#employeePicturePreview').val('').val(decodeURIComponent(employeePicture));
    $('#employeeLastNamePreview').val('').val(decodeURIComponent(employeeLastName));
    $('#employeeDateOfBirthPreview').val('').val(decodeURIComponent(employeeDateOfBirth));
    $('#employeeDateHiredPreview').val('').val(decodeURIComponent(employeeDateHired));
    $('#employeeDateRetiredPreview').val('').val(decodeURIComponent(employeeDateRetired));
    $('#employeeBusinessPhonePreview').val('').val(decodeURIComponent(employeeBusinessPhone));
    $('#employeeHomePhonePreview').val('').val(decodeURIComponent(employeeHomePhone));
    $('#employeeMobilePhonePreview').val('').val(decodeURIComponent(employeeMobilePhone));
    $('#employeeFaxNumberPreview').val('').val(decodeURIComponent(employeeFaxNumber));
    $('#employeeAddressPreview').val('').val(decodeURIComponent(employeeAddress));
    $('#employeePostCodePreview').val('').val(decodeURIComponent(employeePostCode));
    $('#employeeEmailPreview').val('').val(decodeURIComponent(employeeEmail));
    $('#employeeFacebookPreview').val('').val(decodeURIComponent(employeeFacebook));
    $('#employeeTwitterPreview').val('').val(decodeURIComponent(employeeTwitter));
    $('#employeeLinkedInPreview').val('').val(decodeURIComponent(employeeLinkedIn));
    $('#employeeNotesPreview').val('').val(decodeURIComponent(employeeNotes));
    $('#employeeChequePrintingPreview').val('').val(decodeURIComponent(employeeChequePrinting));
    showMeModal('deletePreview', 1);
}
function deleteGridRecord(leafId, url, urlList, securityToken) {
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'delete',
            output: 'json',
            employeeId: $('#employeeIdPreview').val(),
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
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
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function deleteGridRecordCheckbox(leafId, url, urlList, securityToken) {
    var stringText = '';
    var counter = 0;
    $('input:checkbox[name="employeeId[]"]').each(function() {
        stringText = stringText + "&employeeId[]=" + $(this).val();
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
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
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
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
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
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                var path="./v3/humanResource/employment/document/" + folder + "/" + filename;
                $infoPanel.html('').empty().html("<span class='label label-success'>" + decodeURIComponent(t['requestFileTextLabel']) + "</span>");
                window.open(path);
            } else {
                $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
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
    var $cityId = $('#cityId');
    var $stateId = $('#stateId');
    var $countryId = $('#countryId');
    var $jobId = $('#jobId');
    var $genderId = $('#genderId');
    var $marriageId = $('#marriageId');
    var $raceId = $('#raceId');
    var $religionId = $('#religionId');
    var $employmentStatusId = $('#employmentStatusId');
    var $nationalNumber = $('#nationalNumber');
    var $licenseNumber = $('#licenseNumber');
    var $employeeNumber = $('#employeeNumber');
    var $employeeFirstName = $('#employeeFirstName');
    var $employeePicture = $('#employeePicture');
    var $employeeLastName = $('#employeeLastName');
    var $employeeDateOfBirth = $('#employeeDateOfBirth');
    var $employeeDateHired = $('#employeeDateHired');
    var $employeeDateRetired = $('#employeeDateRetired');
    var $employeeBusinessPhone = $('#employeeBusinessPhone');
    var $employeeHomePhone = $('#employeeHomePhone');
    var $employeeMobilePhone = $('#employeeMobilePhone');
    var $employeeFaxNumber = $('#employeeFaxNumber');
    var $employeeAddress = $('#employeeAddress');
    var $employeePostCode = $('#employeePostCode');
    var $employeeEmail = $('#employeeEmail');
    var $employeeFacebook = $('#employeeFacebook');
    var $employeeTwitter = $('#employeeTwitter');
    var $employeeLinkedIn = $('#employeeLinkedIn');
    var $employeeNotes = $('#employeeNotes');
    var $employeeChequePrinting = $('#employeeChequePrinting');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (type === 1) {
            if ($employeeFirstName.val().length === 0) {
                $('#employeeFirstNameHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFirstNameLabel'] + " </span>");
                $('#employeeFirstNameForm').removeClass().addClass('form-group has-error');
                $employeeFirstName.focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    cityId: $cityId.val(),
                    stateId: $stateId.val(),
                    countryId: $countryId.val(),
                    jobId: $jobId.val(),
                    genderId: $genderId.val(),
                    marriageId: $marriageId.val(),
                    raceId: $raceId.val(),
                    religionId: $religionId.val(),
                    employmentStatusId: $employmentStatusId.val(),
                    nationalNumber: $nationalNumber.val(),
                    licenseNumber: $licenseNumber.val(),
                    employeeNumber: $employeeNumber.val(),
                    employeeFirstName: $employeeFirstName.val(),
                    employeePicture: $employeePicture.val(),
                    employeeLastName: $employeeLastName.val(),
                    employeeDateOfBirth: $employeeDateOfBirth.val(),
                    employeeDateHired: $employeeDateHired.val(),
                    employeeDateRetired: $employeeDateRetired.val(),
                    employeeBusinessPhone: $employeeBusinessPhone.val(),
                    employeeHomePhone: $employeeHomePhone.val(),
                    employeeMobilePhone: $employeeMobilePhone.val(),
                    employeeFaxNumber: $employeeFaxNumber.val(),
                    employeeAddress: $employeeAddress.val(),
                    employeePostCode: $employeePostCode.val(),
                    employeeEmail: $employeeEmail.val(),
                    employeeFacebook: $employeeFacebook.val(),
                    employeeTwitter: $employeeTwitter.val(),
                    employeeLinkedIn: $employeeLinkedIn.val(),
                    employeeNotes: $employeeNotes.val(),
                    employeeChequePrinting: $employeeChequePrinting.val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    var $infoPanel = $('#infoPanel');
                    var success = data.success;
                    var message = data.message;
                    var smileyLol = './images/icons/smiley-lol.png';
                    if (success === true) {
                        $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>").delay(1000).fadeOut();
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                        $cityId.val('');
                        $cityId.trigger("chosen:updated");
                        $('#cityIdHelpMe').html('').empty();
                        $stateId.val('');
                        $stateId.trigger("chosen:updated");
                        $('#stateIdHelpMe').html('').empty();
                        $countryId.val('');
                        $countryId.trigger("chosen:updated");
                        $('#countryIdHelpMe').html('').empty();
                        $jobId.val('');
                        $jobId.trigger("chosen:updated");
                        $('#jobIdHelpMe').html('').empty();
                        $genderId.val('');
                        $genderId.trigger("chosen:updated");
                        $('#genderIdHelpMe').html('').empty();
                        $marriageId.val('');
                        $marriageId.trigger("chosen:updated");
                        $('#marriageIdHelpMe').html('').empty();
                        $raceId.val('');
                        $raceId.trigger("chosen:updated");
                        $('#raceIdHelpMe').html('').empty();
                        $religionId.val('');
                        $religionId.trigger("chosen:updated");
                        $('#religionIdHelpMe').html('').empty();
                        $employmentStatusId.val('');
                        $employmentStatusId.trigger("chosen:updated");
                        $('#employmentStatusIdHelpMe').html('').empty();
                        $nationalNumber.val('');
                        $('#nationalNumberHelpMe').html('').empty();
                        $licenseNumber.val('');
                        $('#licenseNumberHelpMe').html('').empty();
                        $employeeNumber.val('');
                        $('#employeeNumberHelpMe').html('').empty();
                        $employeeFirstName.val('');
                        $('#employeeFirstNameHelpMe').html('').empty();
                        $employeePicture.val('');
                        $('#employeePictureHelpMe').html('').empty();
                        $employeeLastName.val('');
                        $('#employeeLastNameHelpMe').html('').empty();
                        $employeeDateOfBirth.val('');
                        $('#employeeDateOfBirthHelpMe').html('').empty();
                        $employeeDateHired.val('');
                        $('#employeeDateHiredHelpMe').html('').empty();
                        $employeeDateRetired.val('');
                        $('#employeeDateRetiredHelpMe').html('').empty();
                        $employeeBusinessPhone.val('');
                        $('#employeeBusinessPhoneHelpMe').html('').empty();
                        $employeeHomePhone.val('');
                        $('#employeeHomePhoneHelpMe').html('').empty();
                        $employeeMobilePhone.val('');
                        $('#employeeMobilePhoneHelpMe').html('').empty();
                        $employeeFaxNumber.val('');
                        $('#employeeFaxNumberHelpMe').html('').empty();
                        $employeeAddress.val('');
                        $('#employeeAddressForm').removeClass().addClass('form-group');
                        $('#employeeAddressHelpMe').html('').empty();
                        $employeePostCode.val('');
                        $('#employeePostCodeHelpMe').html('').empty();
                        $employeeEmail.val('');
                        $('#employeeEmailHelpMe').html('').empty();
                        $employeeFacebook.val('');
                        $('#employeeFacebookHelpMe').html('').empty();
                        $employeeTwitter.val('');
                        $('#employeeTwitterHelpMe').html('').empty();
                        $employeeLinkedIn.val('');
                        $('#employeeLinkedInHelpMe').html('').empty();
                        $employeeNotes.val('');
                        $('#employeeNotesHelpMe').html('').empty();
                        $employeeChequePrinting.val('');
                        $('#employeeChequePrintingHelpMe').html('').empty();
                    } else if (success === false) {
                        $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }
            });
        } else if (type === 2) {
            if ($employeeFirstName.val().length === 0) {
                $('#employeeFirstNameHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFirstNameLabel'] + " </span>");
                $('#employeeFirstNameForm').removeClass().addClass('form-group has-error');
                $employeeFirstName.focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    cityId: $cityId.val(),
                    stateId: $stateId.val(),
                    countryId: $countryId.val(),
                    jobId: $jobId.val(),
                    genderId: $genderId.val(),
                    marriageId: $marriageId.val(),
                    raceId: $raceId.val(),
                    religionId: $religionId.val(),
                    employmentStatusId: $employmentStatusId.val(),
                    nationalNumber: $nationalNumber.val(),
                    licenseNumber: $licenseNumber.val(),
                    employeeNumber: $employeeNumber.val(),
                    employeeFirstName: $employeeFirstName.val(),
                    employeePicture: $employeePicture.val(),
                    employeeLastName: $employeeLastName.val(),
                    employeeDateOfBirth: $employeeDateOfBirth.val(),
                    employeeDateHired: $employeeDateHired.val(),
                    employeeDateRetired: $employeeDateRetired.val(),
                    employeeBusinessPhone: $employeeBusinessPhone.val(),
                    employeeHomePhone: $employeeHomePhone.val(),
                    employeeMobilePhone: $employeeMobilePhone.val(),
                    employeeFaxNumber: $employeeFaxNumber.val(),
                    employeeAddress: $employeeAddress.val(),
                    employeePostCode: $employeePostCode.val(),
                    employeeEmail: $employeeEmail.val(),
                    employeeFacebook: $employeeFacebook.val(),
                    employeeTwitter: $employeeTwitter.val(),
                    employeeLinkedIn: $employeeLinkedIn.val(),
                    employeeNotes: $employeeNotes.val(),
                    employeeChequePrinting: $employeeChequePrinting.val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    var $infoPanel = $('#infoPanel');
                    var success = data.success;
                    var smileyLol = './images/icons/smiley-lol.png';
                    if (success === true) {
                        $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>");
                        $('#employeeId').val(data.employeeId);
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
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }
            });
        } else if (type === 5) {
            if ($employeeFirstName.val().length === 0) {
                $('#employeeFirstNameHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFirstNameLabel'] + " </span>");
                $('#employeeFirstNameForm').removeClass().addClass('form-group has-error');
                $employeeFirstName.focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    cityId: $cityId.val(),
                    stateId: $stateId.val(),
                    countryId: $countryId.val(),
                    jobId: $jobId.val(),
                    genderId: $genderId.val(),
                    marriageId: $marriageId.val(),
                    raceId: $raceId.val(),
                    religionId: $religionId.val(),
                    employmentStatusId: $employmentStatusId.val(),
                    nationalNumber: $nationalNumber.val(),
                    licenseNumber: $licenseNumber.val(),
                    employeeNumber: $employeeNumber.val(),
                    employeeFirstName: $employeeFirstName.val(),
                    employeePicture: $employeePicture.val(),
                    employeeLastName: $employeeLastName.val(),
                    employeeDateOfBirth: $employeeDateOfBirth.val(),
                    employeeDateHired: $employeeDateHired.val(),
                    employeeDateRetired: $employeeDateRetired.val(),
                    employeeBusinessPhone: $employeeBusinessPhone.val(),
                    employeeHomePhone: $employeeHomePhone.val(),
                    employeeMobilePhone: $employeeMobilePhone.val(),
                    employeeFaxNumber: $employeeFaxNumber.val(),
                    employeeAddress: $employeeAddress.val(),
                    employeePostCode: $employeePostCode.val(),
                    employeeEmail: $employeeEmail.val(),
                    employeeFacebook: $employeeFacebook.val(),
                    employeeTwitter: $employeeTwitter.val(),
                    employeeLinkedIn: $employeeLinkedIn.val(),
                    employeeNotes: $employeeNotes.val(),
                    employeeChequePrinting: $employeeChequePrinting.val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
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
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }
            });
        }
        showMeDiv('tableDate', 0);
        showMeDiv('formEntry', 1);
    }
}
function updateRecord(leafId, url, urlList, securityToken, type, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var css = $('#updateRecordButton2').attr('class');
    var $cityId = $('#cityId');
    var $stateId = $('#stateId');
    var $countryId = $('#countryId');
    var $jobId = $('#jobId');
    var $genderId = $('#genderId');
    var $marriageId = $('#marriageId');
    var $raceId = $('#raceId');
    var $religionId = $('#religionId');
    var $employmentStatusId = $('#employmentStatusId');
    var $nationalNumber = $('#nationalNumber');
    var $licenseNumber = $('#licenseNumber');
    var $employeeNumber = $('#employeeNumber');
    var $employeeFirstName = $('#employeeFirstName');
    var $employeePicture = $('#employeePicture');
    var $employeeLastName = $('#employeeLastName');
    var $employeeDateOfBirth = $('#employeeDateOfBirth');
    var $employeeDateHired = $('#employeeDateHired');
    var $employeeDateRetired = $('#employeeDateRetired');
    var $employeeBusinessPhone = $('#employeeBusinessPhone');
    var $employeeHomePhone = $('#employeeHomePhone');
    var $employeeMobilePhone = $('#employeeMobilePhone');
    var $employeeFaxNumber = $('#employeeFaxNumber');
    var $employeeAddress = $('#employeeAddress');
    var $employeePostCode = $('#employeePostCode');
    var $employeeEmail = $('#employeeEmail');
    var $employeeFacebook = $('#employeeFacebook');
    var $employeeTwitter = $('#employeeTwitter');
    var $employeeLinkedIn = $('#employeeLinkedIn');
    var $employeeNotes = $('#employeeNotes');
    var $employeeChequePrinting = $('#employeeChequePrinting');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $infoPanel.empty().html('');
        if (type === 1) {
            if ($employeeFirstName.val().length === 0) {
                $('#employeeFirstNameHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFirstNameLabel'] + " </span>");
                $('#employeeFirstNameForm').removeClass().addClass('form-group has-error');
                $employeeFirstName.focus();
                return false;
            }
            $infoPanel.html('').empty();
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'save',
                    output: 'json',
                    employeeId: $employeeId.val(),
                    cityId: $cityId.val(),
                    stateId: $stateId.val(),
                    countryId: $countryId.val(),
                    jobId: $jobId.val(),
                    genderId: $genderId.val(),
                    marriageId: $marriageId.val(),
                    raceId: $raceId.val(),
                    religionId: $religionId.val(),
                    employmentStatusId: $employmentStatusId.val(),
                    nationalNumber: $nationalNumber.val(),
                    licenseNumber: $licenseNumber.val(),
                    employeeNumber: $employeeNumber.val(),
                    employeeFirstName: $employeeFirstName.val(),
                    employeePicture: $employeePicture.val(),
                    employeeLastName: $employeeLastName.val(),
                    employeeDateOfBirth: $employeeDateOfBirth.val(),
                    employeeDateHired: $employeeDateHired.val(),
                    employeeDateRetired: $employeeDateRetired.val(),
                    employeeBusinessPhone: $employeeBusinessPhone.val(),
                    employeeHomePhone: $employeeHomePhone.val(),
                    employeeMobilePhone: $employeeMobilePhone.val(),
                    employeeFaxNumber: $employeeFaxNumber.val(),
                    employeeAddress: $employeeAddress.val(),
                    employeePostCode: $employeePostCode.val(),
                    employeeEmail: $employeeEmail.val(),
                    employeeFacebook: $employeeFacebook.val(),
                    employeeTwitter: $employeeTwitter.val(),
                    employeeLinkedIn: $employeeLinkedIn.val(),
                    employeeNotes: $employeeNotes.val(),
                    employeeChequePrinting: $employeeChequePrinting.val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
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
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }
            });
        } else if (type === 3) {
            if ($employeeFirstName.val().length === 0) {
                $('#employeeFirstNameHelpMe').html('').empty().html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFirstNameLabel'] + " </span>");
                $('#employeeFirstNameForm').removeClass().addClass('form-group has-error');
                $employeeFirstName.focus();
                return false;
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'save',
                    output: 'json',
                    employeeId: $employeeId.val(),
                    cityId: $cityId.val(),
                    stateId: $stateId.val(),
                    countryId: $countryId.val(),
                    jobId: $jobId.val(),
                    genderId: $genderId.val(),
                    marriageId: $marriageId.val(),
                    raceId: $raceId.val(),
                    religionId: $religionId.val(),
                    employmentStatusId: $employmentStatusId.val(),
                    nationalNumber: $nationalNumber.val(),
                    licenseNumber: $licenseNumber.val(),
                    employeeNumber: $employeeNumber.val(),
                    employeeFirstName: $employeeFirstName.val(),
                    employeePicture: $employeePicture.val(),
                    employeeLastName: $employeeLastName.val(),
                    employeeDateOfBirth: $employeeDateOfBirth.val(),
                    employeeDateHired: $employeeDateHired.val(),
                    employeeDateRetired: $employeeDateRetired.val(),
                    employeeBusinessPhone: $employeeBusinessPhone.val(),
                    employeeHomePhone: $employeeHomePhone.val(),
                    employeeMobilePhone: $employeeMobilePhone.val(),
                    employeeFaxNumber: $employeeFaxNumber.val(),
                    employeeAddress: $employeeAddress.val(),
                    employeePostCode: $employeePostCode.val(),
                    employeeEmail: $employeeEmail.val(),
                    employeeFacebook: $employeeFacebook.val(),
                    employeeTwitter: $employeeTwitter.val(),
                    employeeLinkedIn: $employeeLinkedIn.val(),
                    employeeNotes: $employeeNotes.val(),
                    employeeChequePrinting: $employeeChequePrinting.val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
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
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }
            });
        }
    }
}
function deleteRecord(leafId, url, urlList, securityToken, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var $employeeId = $('#employeeId');
    var css = $('#deleteRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (deleteAccess === 1) {
            if (confirm(decodeURIComponent(t['deleteRecordMessageLabel']))) {
                var value = $employeeId.val();
                if (!value) {
                    $infoPanel.html('').empty().html("<span class='label label-important'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "<span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                    return false;
                } else {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'delete',
                            output: 'json',
                            employeeId: $employeeId.val(),
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            var $infoPanel = $('#infoPanel');
                            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        },
                        success: function(data) {
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
                        },
                        error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                        }
                    });
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
    $("#employeeId").val('');
    $("#employeeIdHelpMe").empty().html('');
    
    $("#cityId").val('');
    $("#cityIdHelpMe").empty().html('');
    $('#cityId').trigger("chosen:updated");
    $("#stateId").val('');
    $("#stateIdHelpMe").empty().html('');
    $('#stateId').trigger("chosen:updated");
    $("#countryId").val('');
    $("#countryIdHelpMe").empty().html('');
    $('#countryId').trigger("chosen:updated");
    $("#jobId").val('');
    $("#jobIdHelpMe").empty().html('');
    $('#jobId').trigger("chosen:updated");
    $("#genderId").val('');
    $("#genderIdHelpMe").empty().html('');
    $('#genderId').trigger("chosen:updated");
    $("#marriageId").val('');
    $("#marriageIdHelpMe").empty().html('');
    $('#marriageId').trigger("chosen:updated");
    $("#raceId").val('');
    $("#raceIdHelpMe").empty().html('');
    $('#raceId').trigger("chosen:updated");
    $("#religionId").val('');
    $("#religionIdHelpMe").empty().html('');
    $('#religionId').trigger("chosen:updated");
    $("#employmentStatusId").val('');
    $("#employmentStatusIdHelpMe").empty().html('');
    $('#employmentStatusId').trigger("chosen:updated");
    $("#nationalNumber").val('');
    $("#nationalNumberHelpMe").empty().html('');
    $("#licenseNumber").val('');
    $("#licenseNumberHelpMe").empty().html('');
    $("#employeeNumber").val('');
    $("#employeeNumberHelpMe").empty().html('');
    $("#employeeFirstName").val('');
    $("#employeeFirstNameHelpMe").empty().html('');
    $("#employeePicture").val('');
    $("#employeePictureHelpMe").empty().html('');
    $("#employeeLastName").val('');
    $("#employeeLastNameHelpMe").empty().html('');
    $("#employeeDateOfBirth").val('');
    $("#employeeDateOfBirthHelpMe").empty().html('');
    $("#employeeDateHired").val('');
    $("#employeeDateHiredHelpMe").empty().html('');
    $("#employeeDateRetired").val('');
    $("#employeeDateRetiredHelpMe").empty().html('');
    $("#employeeBusinessPhone").val('');
    $("#employeeBusinessPhoneHelpMe").empty().html('');
    $("#employeeHomePhone").val('');
    $("#employeeHomePhoneHelpMe").empty().html('');
    $("#employeeMobilePhone").val('');
    $("#employeeMobilePhoneHelpMe").empty().html('');
    $("#employeeFaxNumber").val('');
    $("#employeeFaxNumberHelpMe").empty().html('');
    $("#employeeAddress").val('');
    $("#employeeAddressHelpMe").empty().html('');
    $('#employeeAddress').empty().val('');
    $("#employeePostCode").val('');
    $("#employeePostCodeHelpMe").empty().html('');
    $("#employeeEmail").val('');
    $("#employeeEmailHelpMe").empty().html('');
    $("#employeeFacebook").val('');
    $("#employeeFacebookHelpMe").empty().html('');
    $("#employeeTwitter").val('');
    $("#employeeTwitterHelpMe").empty().html('');
    $("#employeeLinkedIn").val('');
    $("#employeeLinkedInHelpMe").empty().html('');
    $("#employeeNotes").val('');
    $("#employeeNotesHelpMe").empty().html('');
    $("#employeeChequePrinting").val('');
    $("#employeeChequePrintingHelpMe").empty().html('');
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
                var smileyRoll = './images/icons/smiley-roll.png';
                var $infoPanel = $('#infoPanel');
                $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            },
            success: function(data) {
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
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'read',
                            employeeId: firstRecord,
                            output: 'json',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        },
                        success: function(data) {
                            var x,
                                    output;
                            var success = data.success;
                            var $infoPanel = $('#infoPanel');
                            var lastRecord = data.lastRecord;
                            var nextRecord = data.nextRecord;
                            var previousRecord = data.previousRecord;
                            if (success === true) {
                                $('#employeeId').val(data.data.employeeId);
                                $('#cityId').val(data.data.cityId).trigger("chosen:updated");
                                $('#stateId').val(data.data.stateId).trigger("chosen:updated");
                                $('#countryId').val(data.data.countryId).trigger("chosen:updated");
                                $('#jobId').val(data.data.jobId).trigger("chosen:updated");
                                $('#genderId').val(data.data.genderId).trigger("chosen:updated");
                                $('#marriageId').val(data.data.marriageId).trigger("chosen:updated");
                                $('#raceId').val(data.data.raceId).trigger("chosen:updated");
                                $('#religionId').val(data.data.religionId).trigger("chosen:updated");
                                $('#employmentStatusId').val(data.data.employmentStatusId).trigger("chosen:updated");
                                $('#nationalNumber').val(data.data.nationalNumber);
                                $('#licenseNumber').val(data.data.licenseNumber);
                                $('#employeeNumber').val(data.data.employeeNumber);
                                $('#employeeFirstName').val(data.data.employeeFirstName);
                                $('#employeePicture').val(data.data.employeePicture);
                                var filename;
                                if (data.data.employeePicture.length === 0) {
                                    filename = 'Kathleen_Byrne.jpg';
                                } else {
                                    filename = data.data.employeePicture;
                                }
                                $("#employeePicturePreviewUpload").html("").empty().html("<ul class=\"img-thumbnails\"><li>&nbsp;<div class=\"img-thumbnail\"><img src='./package/humanResource/images/" + filename + "'  width='80' height='80'></div></li></ul>");
                                $('#employeeLastName').val(data.data.employeeLastName);
                                x = data.data.employeeDateOfBirth;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#employeeDateOfBirth').val(output);
                                x = data.data.employeeDateHired;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#employeeDateHired').val(output);
                                x = data.data.employeeDateRetired;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#employeeDateRetired').val(output);
                                $('#employeeBusinessPhone').val(data.data.employeeBusinessPhone);
                                $('#employeeHomePhone').val(data.data.employeeHomePhone);
                                $('#employeeMobilePhone').val(data.data.employeeMobilePhone);
                                $('#employeeFaxNumber').val(data.data.employeeFaxNumber);
                                $('#employeeAddress').val(data.data.employeeAddress);
                                $('#employeePostCode').val(data.data.employeePostCode);
                                $('#employeeEmail').val(data.data.employeeEmail);
                                $('#employeeFacebook').val(data.data.employeeFacebook);
                                $('#employeeTwitter').val(data.data.employeeTwitter);
                                $('#employeeLinkedIn').val(data.data.employeeLinkedIn);
                                $('#employeeNotes').val(data.data.employeeNotes);
                                $('#employeeChequePrinting').val(data.data.employeeChequePrinting);
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
                        },
                        error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                        }
                    });
                } else {
                    $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRollSweat + "'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }
            },
            error: function(xhr) {
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
            }
        });
    }
}
function endRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess) {
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
                $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            },
            success: function(data) {
                var success = data.success;
                var message = data.message;
                var lastRecord = data.lastRecord;
                var smileyRoll = './images/icons/smiley-roll.png';
                if (lastRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (success === true) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'read',
                            employeeId: lastRecord,
                            output: 'json',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        },
                        success: function(data) {
                            var x,
                                    output;
                            var success = data.success;
                            var firstRecord = data.firstRecord;
                            var lastRecord = data.lastRecord;
                            var nextRecord = data.nextRecord;
                            var previousRecord = data.previousRecord;
                            if (success === true) {
                                $('#employeeId').val(data.data.employeeId);
                                $('#cityId').val(data.data.cityId).trigger("chosen:updated");
                                $('#stateId').val(data.data.stateId).trigger("chosen:updated");
                                $('#countryId').val(data.data.countryId).trigger("chosen:updated");
                                $('#jobId').val(data.data.jobId).trigger("chosen:updated");
                                $('#genderId').val(data.data.genderId).trigger("chosen:updated");
                                $('#marriageId').val(data.data.marriageId).trigger("chosen:updated");
                                $('#raceId').val(data.data.raceId).trigger("chosen:updated");
                                $('#religionId').val(data.data.religionId).trigger("chosen:updated");
                                $('#employmentStatusId').val(data.data.employmentStatusId).trigger("chosen:updated");
                                $('#nationalNumber').val(data.data.nationalNumber);
                                $('#licenseNumber').val(data.data.licenseNumber);
                                $('#employeeNumber').val(data.data.employeeNumber);
                                $('#employeeFirstName').val(data.data.employeeFirstName);
                                $('#employeePicture').val(data.data.employeePicture);
                                var filename;
                                if (data.data.employeePicture.length === 0) {
                                    filename = 'Kathleen_Byrne.jpg';
                                } else {
                                    filename = data.data.employeePicture;
                                }
                                $("#employeePicturePreviewUpload").html("").empty().html("<ul class=\"img-thumbnails\"><li>&nbsp;<div class=\"img-thumbnail\"><img src='./package/humanResource/images/" + filename + "'  width='80' height='80'></div></li></ul>");
                                $('#employeeLastName').val(data.data.employeeLastName);
                                x = data.data.employeeDateOfBirth;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#employeeDateOfBirth').val(output);
                                x = data.data.employeeDateHired;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#employeeDateHired').val(output);
                                x = data.data.employeeDateRetired;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#employeeDateRetired').val(output);
                                $('#employeeBusinessPhone').val(data.data.employeeBusinessPhone);
                                $('#employeeHomePhone').val(data.data.employeeHomePhone);
                                $('#employeeMobilePhone').val(data.data.employeeMobilePhone);
                                $('#employeeFaxNumber').val(data.data.employeeFaxNumber);
                                $('#employeeAddress').val(data.data.employeeAddress);
                                ;
                                $('#employeePostCode').val(data.data.employeePostCode);
                                $('#employeeEmail').val(data.data.employeeEmail);
                                $('#employeeFacebook').val(data.data.employeeFacebook);
                                $('#employeeTwitter').val(data.data.employeeTwitter);
                                $('#employeeLinkedIn').val(data.data.employeeLinkedIn);
                                $('#employeeNotes').val(data.data.employeeNotes);
                                $('#employeeChequePrinting').val(data.data.employeeChequePrinting);
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
                        },
                        error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                        }
                    });
                } else {
                    $infoPanel.html("<span class='label label-important'>&nbsp;" + message + "</span>");
                }
                var endIcon = './images/icons/control-stop-180.png';
                $infoPanel.html('').empty().html("&nbsp;<img src='" + endIcon + "'> " + decodeURIComponent(t['endButtonLabel']) + " ");
            },
            error: function(xhr) {
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
            }
        });
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
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'read',
                    employeeId: $previousRecordCounter.val(),
                    output: 'json',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    var x,
                            output;
                    var success = data.success;
                    var firstRecord = data.firstRecord;
                    var lastRecord = data.lastRecord;
                    var nextRecord = data.nextRecord;
                    var previousRecord = data.previousRecord;
                    var $infoPanel = $('#infoPanel');
                    if (success === true) {
                        $('#employeeId').val(data.data.employeeId);
                        $('#cityId').val(data.data.cityId).trigger("chosen:updated");
                        $('#stateId').val(data.data.stateId).trigger("chosen:updated");
                        $('#countryId').val(data.data.countryId).trigger("chosen:updated");
                        $('#jobId').val(data.data.jobId).trigger("chosen:updated");
                        $('#genderId').val(data.data.genderId).trigger("chosen:updated");
                        $('#marriageId').val(data.data.marriageId).trigger("chosen:updated");
                        $('#raceId').val(data.data.raceId).trigger("chosen:updated");
                        $('#religionId').val(data.data.religionId).trigger("chosen:updated");
                        $('#employmentStatusId').val(data.data.employmentStatusId).trigger("chosen:updated");
                        $('#nationalNumber').val(data.data.nationalNumber);
                        $('#licenseNumber').val(data.data.licenseNumber);
                        $('#employeeNumber').val(data.data.employeeNumber);
                        $('#employeeFirstName').val(data.data.employeeFirstName);
                        $('#employeePicture').val(data.data.employeePicture);
                        var filename;
                        if (data.data.employeePicture.length === 0) {
                            filename = 'Kathleen_Byrne.jpg';
                        } else {
                            filename = data.data.employeePicture;
                        }
                        $("#employeePicturePreviewUpload").html("").empty().html("<ul class=\"img-thumbnails\"><li>&nbsp;<div class=\"img-thumbnail\"><img src='./package/humanResource/images/" + filename + "'  width='80' height='80'></div></li></ul>");
                        $('#employeeLastName').val(data.data.employeeLastName);
                        x = data.data.employeeDateOfBirth;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#employeeDateOfBirth').val(output);
                        x = data.data.employeeDateHired;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#employeeDateHired').val(output);
                        x = data.data.employeeDateRetired;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#employeeDateRetired').val(output);
                        $('#employeeBusinessPhone').val(data.data.employeeBusinessPhone);
                        $('#employeeHomePhone').val(data.data.employeeHomePhone);
                        $('#employeeMobilePhone').val(data.data.employeeMobilePhone);
                        $('#employeeFaxNumber').val(data.data.employeeFaxNumber);
                        $('#employeeAddress').val(data.data.employeeAddress);
                        ;
                        $('#employeePostCode').val(data.data.employeePostCode);
                        $('#employeeEmail').val(data.data.employeeEmail);
                        $('#employeeFacebook').val(data.data.employeeFacebook);
                        $('#employeeTwitter').val(data.data.employeeTwitter);
                        $('#employeeLinkedIn').val(data.data.employeeLinkedIn);
                        $('#employeeNotes').val(data.data.employeeNotes);
                        $('#employeeChequePrinting').val(data.data.employeeChequePrinting);
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
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }
            });
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
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'read',
                    employeeId: $nextRecordCounter.val(),
                    output: 'json',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                        $('#employeeId').val(data.data.employeeId);
                        $('#cityId').val(data.data.cityId).trigger("chosen:updated");
                        $('#stateId').val(data.data.stateId).trigger("chosen:updated");
                        $('#countryId').val(data.data.countryId).trigger("chosen:updated");
                        $('#jobId').val(data.data.jobId).trigger("chosen:updated");
                        $('#genderId').val(data.data.genderId).trigger("chosen:updated");
                        $('#marriageId').val(data.data.marriageId).trigger("chosen:updated");
                        $('#raceId').val(data.data.raceId).trigger("chosen:updated");
                        $('#religionId').val(data.data.religionId).trigger("chosen:updated");
                        $('#employmentStatusId').val(data.data.employmentStatusId).trigger("chosen:updated");
                        $('#nationalNumber').val(data.data.nationalNumber);
                        $('#licenseNumber').val(data.data.licenseNumber);
                        $('#employeeNumber').val(data.data.employeeNumber);
                        $('#employeeFirstName').val(data.data.employeeFirstName);
                        $('#employeePicture').val(data.data.employeePicture);
                        var filename;
                        if (data.data.employeePicture.length === 0) {
                            filename = 'Kathleen_Byrne.jpg';
                        } else {
                            filename = data.data.employeePicture;
                        }
                        $("#employeePicturePreviewUpload").html("").empty().html("<ul class=\"img-thumbnails\"><li>&nbsp;<div class=\"img-thumbnail\"><img src='./package/humanResource/images/" + filename + "'  width='80' height='80'></div></li></ul>");
                        $('#employeeLastName').val(data.data.employeeLastName);
                        x = data.data.employeeDateOfBirth;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#employeeDateOfBirth').val(output);
                        x = data.data.employeeDateHired;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#employeeDateHired').val(output);
                        x = data.data.employeeDateRetired;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#employeeDateRetired').val(output);
                        $('#employeeBusinessPhone').val(data.data.employeeBusinessPhone);
                        $('#employeeHomePhone').val(data.data.employeeHomePhone);
                        $('#employeeMobilePhone').val(data.data.employeeMobilePhone);
                        $('#employeeFaxNumber').val(data.data.employeeFaxNumber);
                        $('#employeeAddress').val(data.data.employeeAddress);
                        $('#employeePostCode').val(data.data.employeePostCode);
                        $('#employeeEmail').val(data.data.employeeEmail);
                        $('#employeeFacebook').val(data.data.employeeFacebook);
                        $('#employeeTwitter').val(data.data.employeeTwitter);
                        $('#employeeLinkedIn').val(data.data.employeeLinkedIn);
                        $('#employeeNotes').val(data.data.employeeNotes);
                        $('#employeeChequePrinting').val(data.data.employeeChequePrinting);
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
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }
            });
        }
    }
}

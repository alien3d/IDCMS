function getCity(leafId, url, securityToken) {
    // un hide button search
    // unlimited for searching because  lazy paging.
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
            filter: 'city'
        },
        beforeSend: function() {
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<div class='alert alert-error col-md-12'>" + data.message + "</div>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#city").trigger("chosen:updated");
                $('#infoPanel').html("<div class='alert alert-success  col-md-12'> <img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</div>");
            }
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }
    });
}
function getState(leafId, url, securityToken) {
    // un hide button search
    // unlimited for searching because  lazy paging.
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<div class='alert alert-error col-md-12'>" + data.message + "</div>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#state").trigger("chosen:updated");
                $('#infoPanel').html("<div class='alert alert-success  col-md-12'> <img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</div>");
            }
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }
    });
}
function getCountry(leafId, url, securityToken) {
    // un hide button search
    // unlimited for searching because  lazy paging.
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<div class='alert alert-error col-md-12'>" + data.message + "</div>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#country").trigger("chosen:updated");
                $('#infoPanel').html("<div class='alert alert-success  col-md-12'> <img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</div>");
            }
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }
    });
}
function getJob(leafId, url, securityToken) {
    // un hide button search
    // unlimited for searching because  lazy paging.
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<div class='alert alert-error col-md-12'>" + data.message + "</div>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#job").trigger("chosen:updated");
                $('#infoPanel').html("<div class='alert alert-success  col-md-12'> <img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</div>");
            }
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }
    });
}
function getGender(leafId, url, securityToken) {
    // un hide button search
    // unlimited for searching because  lazy paging.
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<div class='alert alert-error col-md-12'>" + data.message + "</div>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#gender").trigger("chosen:updated");
                $('#infoPanel').html("<div class='alert alert-success  col-md-12'> <img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</div>");
            }
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }
    });
}
function getMarriage(leafId, url, securityToken) {
    // un hide button search
    // unlimited for searching because  lazy paging.
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<div class='alert alert-error col-md-12'>" + data.message + "</div>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#marriage").trigger("chosen:updated");
                $('#infoPanel').html("<div class='alert alert-success  col-md-12'> <img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</div>");
            }
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }
    });
}
function getRace(leafId, url, securityToken) {
    // un hide button search
    // unlimited for searching because  lazy paging.
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<div class='alert alert-error col-md-12'>" + data.message + "</div>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#race").trigger("chosen:updated");
                $('#infoPanel').html("<div class='alert alert-success  col-md-12'> <img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</div>");
            }
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }
    });
}
function getReligion(leafId, url, securityToken) {
    // un hide button search
    // unlimited for searching because  lazy paging.
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<div class='alert alert-error col-md-12'>" + data.message + "</div>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#religion").trigger("chosen:updated");
                $('#infoPanel').html("<div class='alert alert-success  col-md-12'> <img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</div>");
            }
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }
    });
}
function getEmploymentStatus(leafId, url, securityToken) {
    // un hide button search
    // unlimited for searching because  lazy paging.
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<div class='alert alert-error col-md-12'>" + data.message + "</div>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#employmentStatus").trigger("chosen:updated");
                $('#infoPanel').html("<div class='alert alert-success  col-md-12'> <img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</div>");
            }
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }
    });
}
function checkDuplicate(leafId, page, securityToken) {
    if ($("#employeeCode").val().length == 0) {
        alert(t['oddTextLabel']);
        return false;
    }
    $.ajax({
        type: 'GET',
        url: page,
        data: {
            employeeCode: $("#employeeCode").val(),
            method: 'duplicate',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == true) {
                if (data.total != 0) {
                    $("#employeeCode").empty();
                    $("#employeeCode").val('');
                    $("#employeeCode").focus();
                    $("#employeeCodeForm").removeClass();
                    $("#employeeCodeForm").addClass("col-md-12 form-group has-error");
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
                $("#employeeForm").removeClass();
                $("#employeeForm").addClass("col-md-12 form-group has-error");
            }
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
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
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }
    });
}
function ajaxQuerySearchAll(leafId, url, securityToken) {
    // un hide button search
    $('#clearSearch').removeClass();
    $('#clearSearch').addClass('btn');
    // unlimited for searching because  lazy paging.
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
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
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
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }
    });
}
function ajaxQuerySearchAllCharacter(leafId, url, securityToken, character) {
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
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
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
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
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
    // end date array
    // declare/set date object
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
    if (dateRangeStart.length == 0) {
        dateRangeStart = $('#dateRangeStart').val();
    }
    if (dateRangeEnd.length == 0) {
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
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
                        if (dateRangeEnd.length == 0) {
                            strDate = "<b>" + t['dayTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear();
                        } else {
                            strDate = "<b>" + t['betweenTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear() + "&nbsp;<img src='./images/icons/arrow-curve-000-left.png'>&nbsp;" + dateEnd.getDayName() + ", " + dateEnd.getMonthName() + ", " + dateEnd.getDate() + ", " + dateEnd.getFullYear();
                        }
                        break;
                    case 'between':
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
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }
    });
}
function ajaxQuerySearchAllDateRange(leafId, url, securityToken) {
    ajaxQuerySearchAllDate(leafId, url, securityToken, $('#dateRangeStart').val(), $('#dateRangeEnd').val(), 'between', '', t['loadingTextLabel'], t['loadingCompleteTextLabel'], t['loadingErrorTextLabel']);
}
function showForm(leafId, url, securityToken) {
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
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
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
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }
    });
}
function showFormUpdate(leafId, url, urlList, securityToken, employeeId, updateAccess, deleteAccess) {
    sleep(500);
    // unlimited for searching because  lazy paging.
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
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
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }
    });
}
function showModalDelete(employeeId, cityId, stateId, countryId, jobId, genderId, marriageId, raceId, religionId, employmentStatusId, employeeNumber, employeeFirstName, employeeCompany, employeeLastName, employeeDateOfBirth, employeeDateHired, employeeDateRetired, employeeBusinessPhone, employeeHomePhone, employeeMobilePhone, employeeFaxNumber, employeeAddress, employeePostCode, employeeEmail, employeeFacebook, employeeTwitter, employeeLinkedIn, employeeNotes, employeeChequePrinting) {
    // clear first old record if exist
    $('#employeeIdPreview').val('');
    $('#employeeIdPreview').val(decodeURIComponent(employeeId));

    $('#cityIdPreview').val('');
    $('#cityIdPreview').val(decodeURIComponent(cityId));

    $('#stateIdPreview').val('');
    $('#stateIdPreview').val(decodeURIComponent(stateId));

    $('#countryIdPreview').val('');
    $('#countryIdPreview').val(decodeURIComponent(countryId));

    $('#jobIdPreview').val('');
    $('#jobIdPreview').val(decodeURIComponent(jobId));

    $('#genderIdPreview').val('');
    $('#genderIdPreview').val(decodeURIComponent(genderId));

    $('#marriageIdPreview').val('');
    $('#marriageIdPreview').val(decodeURIComponent(marriageId));

    $('#raceIdPreview').val('');
    $('#raceIdPreview').val(decodeURIComponent(raceId));

    $('#religionIdPreview').val('');
    $('#religionIdPreview').val(decodeURIComponent(religionId));

    $('#employmentStatusIdPreview').val('');
    $('#employmentStatusIdPreview').val(decodeURIComponent(employmentStatusId));

    $('#employeeNumberPreview').val('');
    $('#employeeNumberPreview').val(decodeURIComponent(employeeNumber));

    $('#employeeFirstNamePreview').val('');
    $('#employeeFirstNamePreview').val(decodeURIComponent(employeeFirstName));

    $('#employeeCompanyPreview').val('');
    $('#employeeCompanyPreview').val(decodeURIComponent(employeeCompany));

    $('#employeeLastNamePreview').val('');
    $('#employeeLastNamePreview').val(decodeURIComponent(employeeLastName));

    $('#employeeDateOfBirthPreview').val('');
    $('#employeeDateOfBirthPreview').val(decodeURIComponent(employeeDateOfBirth));

    $('#employeeDateHiredPreview').val('');
    $('#employeeDateHiredPreview').val(decodeURIComponent(employeeDateHired));

    $('#employeeDateRetiredPreview').val('');
    $('#employeeDateRetiredPreview').val(decodeURIComponent(employeeDateRetired));

    $('#employeeBusinessPhonePreview').val('');
    $('#employeeBusinessPhonePreview').val(decodeURIComponent(employeeBusinessPhone));

    $('#employeeHomePhonePreview').val('');
    $('#employeeHomePhonePreview').val(decodeURIComponent(employeeHomePhone));

    $('#employeeMobilePhonePreview').val('');
    $('#employeeMobilePhonePreview').val(decodeURIComponent(employeeMobilePhone));

    $('#employeeFaxNumberPreview').val('');
    $('#employeeFaxNumberPreview').val(decodeURIComponent(employeeFaxNumber));

    $('#employeeAddressPreview').val('');
    $('#employeeAddressPreview').val(decodeURIComponent(employeeAddress));

    $('#employeePostCodePreview').val('');
    $('#employeePostCodePreview').val(decodeURIComponent(employeePostCode));

    $('#employeeEmailPreview').val('');
    $('#employeeEmailPreview').val(decodeURIComponent(employeeEmail));

    $('#employeeFacebookPreview').val('');
    $('#employeeFacebookPreview').val(decodeURIComponent(employeeFacebook));

    $('#employeeTwitterPreview').val('');
    $('#employeeTwitterPreview').val(decodeURIComponent(employeeTwitter));

    $('#employeeLinkedInPreview').val('');
    $('#employeeLinkedInPreview').val(decodeURIComponent(employeeLinkedIn));

    $('#employeeNotesPreview').val('');
    $('#employeeNotesPreview').val(decodeURIComponent(employeeNotes));

    $('#employeeChequePrintingPreview').val('');
    $('#employeeChequePrintingPreview').val(decodeURIComponent(employeeChequePrinting));

    // open modal box
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
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
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
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
    if (counter == 0) {
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
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
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
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
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == true) {
                var path="./v3/humanResource/employment/document/" + data.folder + "/" + data.filename;
                $('#infoPanel').html("<span class='label label-success'>" + decodeURIComponent(t['requestFileTextLabel']) + "</span>");
                window.open(path);
                // a hyper link will be given to click download..
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
        },
        error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }
    });
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
            // new record and continue.Reset Current Record
            if ($('#cityId').val().length == 0) {
                $('#cityIdHelpMe').empty();
                $('#cityIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['cityIdLabel'] + " </span>");
                $('#cityId').data('chosen').activate_action();
                return false;
            }
            if ($('#stateId').val().length == 0) {
                $('#stateIdHelpMe').empty();
                $('#stateIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['stateIdLabel'] + " </span>");
                $('#stateId').data('chosen').activate_action();
                return false;
            }
            if ($('#countryId').val().length == 0) {
                $('#countryIdHelpMe').empty();
                $('#countryIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + " </span>");
                $('#countryId').data('chosen').activate_action();
                return false;
            }
            if ($('#jobId').val().length == 0) {
                $('#jobIdHelpMe').empty();
                $('#jobIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['jobIdLabel'] + " </span>");
                $('#jobId').data('chosen').activate_action();
                return false;
            }
            if ($('#genderId').val().length == 0) {
                $('#genderIdHelpMe').empty();
                $('#genderIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['genderIdLabel'] + " </span>");
                $('#genderId').data('chosen').activate_action();
                return false;
            }
            if ($('#marriageId').val().length == 0) {
                $('#marriageIdHelpMe').empty();
                $('#marriageIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['marriageIdLabel'] + " </span>");
                $('#marriageId').data('chosen').activate_action();
                return false;
            }
            if ($('#raceId').val().length == 0) {
                $('#raceIdHelpMe').empty();
                $('#raceIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['raceIdLabel'] + " </span>");
                $('#raceId').data('chosen').activate_action();
                return false;
            }
            if ($('#religionId').val().length == 0) {
                $('#religionIdHelpMe').empty();
                $('#religionIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['religionIdLabel'] + " </span>");
                $('#religionId').data('chosen').activate_action();
                return false;
            }
            if ($('#employmentStatusId').val().length == 0) {
                $('#employmentStatusIdHelpMe').empty();
                $('#employmentStatusIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employmentStatusIdLabel'] + " </span>");
                $('#employmentStatusId').data('chosen').activate_action();
                return false;
            }
            if ($('#employeeNumber').val().length == 0) {
                $('#employeeNumberHelpMe').empty();
                $('#employeeNumberHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeNumberLabel'] + " </span>");
                $('#employeeNumberForm').addClass('form-group has-error');
                $('#employeeNumber').focus();
                return false;
            }
            if ($('#employeeFirstName').val().length == 0) {
                $('#employeeFirstNameHelpMe').empty();
                $('#employeeFirstNameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFirstNameLabel'] + " </span>");
                $('#employeeFirstNameForm').addClass('form-group has-error');
                $('#employeeFirstName').focus();
                return false;
            }
            if ($('#employeeCompany').val().length == 0) {
                $('#employeeCompanyHelpMe').empty();
                $('#employeeCompanyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeCompanyLabel'] + " </span>");
                $('#employeeCompanyForm').addClass('form-group has-error');
                $('#employeeCompany').focus();
                return false;
            }
            if ($('#employeeLastName').val().length == 0) {
                $('#employeeLastNameHelpMe').empty();
                $('#employeeLastNameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeLastNameLabel'] + " </span>");
                $('#employeeLastNameForm').addClass('form-group has-error');
                $('#employeeLastName').focus();
                return false;
            }
            if ($('#employeeDateOfBirth').val().length == 0) {
                $('#employeeDateOfBirthHelpMe').empty();
                $('#employeeDateOfBirthHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeDateOfBirthLabel'] + " </span>");
                $('#employeeDateOfBirthForm').addClass('form-group has-error');
                $('#employeeDateOfBirth').focus();
                return false;
            }
            if ($('#employeeDateHired').val().length == 0) {
                $('#employeeDateHiredHelpMe').empty();
                $('#employeeDateHiredHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeDateHiredLabel'] + " </span>");
                $('#employeeDateHiredForm').addClass('form-group has-error');
                $('#employeeDateHired').focus();
                return false;
            }
            if ($('#employeeDateRetired').val().length == 0) {
                $('#employeeDateRetiredHelpMe').empty();
                $('#employeeDateRetiredHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeDateRetiredLabel'] + " </span>");
                $('#employeeDateRetiredForm').addClass('form-group has-error');
                $('#employeeDateRetired').focus();
                return false;
            }
            if ($('#employeeBusinessPhone').val().length == 0) {
                $('#employeeBusinessPhoneHelpMe').empty();
                $('#employeeBusinessPhoneHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeBusinessPhoneLabel'] + " </span>");
                $('#employeeBusinessPhoneForm').addClass('form-group has-error');
                $('#employeeBusinessPhone').focus();
                return false;
            }
            if ($('#employeeHomePhone').val().length == 0) {
                $('#employeeHomePhoneHelpMe').empty();
                $('#employeeHomePhoneHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeHomePhoneLabel'] + " </span>");
                $('#employeeHomePhoneForm').addClass('form-group has-error');
                $('#employeeHomePhone').focus();
                return false;
            }
            if ($('#employeeMobilePhone').val().length == 0) {
                $('#employeeMobilePhoneHelpMe').empty();
                $('#employeeMobilePhoneHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeMobilePhoneLabel'] + " </span>");
                $('#employeeMobilePhoneForm').addClass('form-group has-error');
                $('#employeeMobilePhone').focus();
                return false;
            }
            if ($('#employeeFaxNumber').val().length == 0) {
                $('#employeeFaxNumberHelpMe').empty();
                $('#employeeFaxNumberHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFaxNumberLabel'] + " </span>");
                $('#employeeFaxNumberForm').addClass('form-group has-error');
                $('#employeeFaxNumber').focus();
                return false;
            }
            if ($('#employeeAddress').val().length == 0) {
                $('#employeeAddressHelpMe').empty();
                $('#employeeAddressHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeAddressLabel'] + " </span>");
                $('#employeeAddressForm').addClass('form-group has-error');
                $('#employeeAddress').focus();
                return false;
            }
            if ($('#employeePostCode').val().length == 0) {
                $('#employeePostCodeHelpMe').empty();
                $('#employeePostCodeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeePostCodeLabel'] + " </span>");
                $('#employeePostCodeForm').addClass('form-group has-error');
                $('#employeePostCode').focus();
                return false;
            }
            if ($('#employeeEmail').val().length == 0) {
                $('#employeeEmailHelpMe').empty();
                $('#employeeEmailHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeEmailLabel'] + " </span>");
                $('#employeeEmailForm').addClass('form-group has-error');
                $('#employeeEmail').focus();
                return false;
            }
            if ($('#employeeFacebook').val().length == 0) {
                $('#employeeFacebookHelpMe').empty();
                $('#employeeFacebookHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFacebookLabel'] + " </span>");
                $('#employeeFacebookForm').addClass('form-group has-error');
                $('#employeeFacebook').focus();
                return false;
            }
            if ($('#employeeTwitter').val().length == 0) {
                $('#employeeTwitterHelpMe').empty();
                $('#employeeTwitterHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeTwitterLabel'] + " </span>");
                $('#employeeTwitterForm').addClass('form-group has-error');
                $('#employeeTwitter').focus();
                return false;
            }
            if ($('#employeeLinkedIn').val().length == 0) {
                $('#employeeLinkedInHelpMe').empty();
                $('#employeeLinkedInHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeLinkedInLabel'] + " </span>");
                $('#employeeLinkedInForm').addClass('form-group has-error');
                $('#employeeLinkedIn').focus();
                return false;
            }
            if ($('#employeeNotes').val().length == 0) {
                $('#employeeNotesHelpMe').empty();
                $('#employeeNotesHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeNotesLabel'] + " </span>");
                $('#employeeNotesForm').addClass('form-group has-error');
                $('#employeeNotes').focus();
                return false;
            }
            if ($('#employeeChequePrinting').val().length == 0) {
                $('#employeeChequePrintingHelpMe').empty();
                $('#employeeChequePrintingHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeChequePrintingLabel'] + " </span>");
                $('#employeeChequePrintingForm').addClass('form-group has-error');
                $('#employeeChequePrinting').focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    cityId: $('#cityId').val(),
                    stateId: $('#stateId').val(),
                    countryId: $('#countryId').val(),
                    jobId: $('#jobId').val(),
                    genderId: $('#genderId').val(),
                    marriageId: $('#marriageId').val(),
                    raceId: $('#raceId').val(),
                    religionId: $('#religionId').val(),
                    employmentStatusId: $('#employmentStatusId').val(),
                    employeeNumber: $('#employeeNumber').val(),
                    employeeFirstName: $('#employeeFirstName').val(),
                    employeeCompany: $('#employeeCompany').val(),
                    employeeLastName: $('#employeeLastName').val(),
                    employeeDateOfBirth: $('#employeeDateOfBirth').val(),
                    employeeDateHired: $('#employeeDateHired').val(),
                    employeeDateRetired: $('#employeeDateRetired').val(),
                    employeeBusinessPhone: $('#employeeBusinessPhone').val(),
                    employeeHomePhone: $('#employeeHomePhone').val(),
                    employeeMobilePhone: $('#employeeMobilePhone').val(),
                    employeeFaxNumber: $('#employeeFaxNumber').val(),
                    employeeAddress: $('#employeeAddress').val(),
                    employeePostCode: $('#employeePostCode').val(),
                    employeeEmail: $('#employeeEmail').val(),
                    employeeFacebook: $('#employeeFacebook').val(),
                    employeeTwitter: $('#employeeTwitter').val(),
                    employeeLinkedIn: $('#employeeLinkedIn').val(),
                    employeeNotes: $('#employeeNotes').val(),
                    employeeChequePrinting: $('#employeeChequePrinting').val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    // this is where we append a loading image
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success == true) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>").delay(1000).fadeOut();
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                        // resetting field value
                        $('#cityId').val('');
                        $('#cityId').trigger("chosen:updated");
                        $('#cityIdHelpMe').empty();
                        $('#cityIdHelpMe').html('');
                        $('#stateId').val('');
                        $('#stateId').trigger("chosen:updated");
                        $('#stateIdHelpMe').empty();
                        $('#stateIdHelpMe').html('');
                        $('#countryId').val('');
                        $('#countryId').trigger("chosen:updated");
                        $('#countryIdHelpMe').empty();
                        $('#countryIdHelpMe').html('');
                        $('#jobId').val('');
                        $('#jobId').trigger("chosen:updated");
                        $('#jobIdHelpMe').empty();
                        $('#jobIdHelpMe').html('');
                        $('#genderId').val('');
                        $('#genderId').trigger("chosen:updated");
                        $('#genderIdHelpMe').empty();
                        $('#genderIdHelpMe').html('');
                        $('#marriageId').val('');
                        $('#marriageId').trigger("chosen:updated");
                        $('#marriageIdHelpMe').empty();
                        $('#marriageIdHelpMe').html('');
                        $('#raceId').val('');
                        $('#raceId').trigger("chosen:updated");
                        $('#raceIdHelpMe').empty();
                        $('#raceIdHelpMe').html('');
                        $('#religionId').val('');
                        $('#religionId').trigger("chosen:updated");
                        $('#religionIdHelpMe').empty();
                        $('#religionIdHelpMe').html('');
                        $('#employmentStatusId').val('');
                        $('#employmentStatusId').trigger("chosen:updated");
                        $('#employmentStatusIdHelpMe').empty();
                        $('#employmentStatusIdHelpMe').html('');
                        $('#employeeNumber').val('');
                        $('#employeeNumber').val('');
                        $('#employeeNumberHelpMe').empty();
                        $('#employeeNumberHelpMe').html('');
                        $('#employeeFirstName').val('');
                        $('#employeeFirstName').val('');
                        $('#employeeFirstNameHelpMe').empty();
                        $('#employeeFirstNameHelpMe').html('');
                        $('#employeeCompany').val('');
                        $('#employeeCompany').val('');
                        $('#employeeCompanyHelpMe').empty();
                        $('#employeeCompanyHelpMe').html('');
                        $('#employeeLastName').val('');
                        $('#employeeLastName').val('');
                        $('#employeeLastNameHelpMe').empty();
                        $('#employeeLastNameHelpMe').html('');
                        $('#employeeDateOfBirth').val('');
                        $('#employeeDateOfBirthHelpMe').empty();
                        $('#employeeDateOfBirthHelpMe').html('');
                        $('#employeeDateHired').val('');
                        $('#employeeDateHiredHelpMe').empty();
                        $('#employeeDateHiredHelpMe').html('');
                        $('#employeeDateRetired').val('');
                        $('#employeeDateRetiredHelpMe').empty();
                        $('#employeeDateRetiredHelpMe').html('');
                        $('#employeeBusinessPhone').val('');
                        $('#employeeBusinessPhone').val('');
                        $('#employeeBusinessPhoneHelpMe').empty();
                        $('#employeeBusinessPhoneHelpMe').html('');
                        $('#employeeHomePhone').val('');
                        $('#employeeHomePhone').val('');
                        $('#employeeHomePhoneHelpMe').empty();
                        $('#employeeHomePhoneHelpMe').html('');
                        $('#employeeMobilePhone').val('');
                        $('#employeeMobilePhone').val('');
                        $('#employeeMobilePhoneHelpMe').empty();
                        $('#employeeMobilePhoneHelpMe').html('');
                        $('#employeeFaxNumber').val('');
                        $('#employeeFaxNumber').val('');
                        $('#employeeFaxNumberHelpMe').empty();
                        $('#employeeFaxNumberHelpMe').html('');
                        $('#employeeAddress').val('');
                        $('#employeeAddressForm').removeClass().addClass('col-md-12 form-group');
                        $('#employeeAddress').val('');
                        $('#employeeAddressHelpMe').empty();
                        $('#employeeAddressHelpMe').html('');
                        $('#employeePostCode').val('');
                        $('#employeePostCode').val('');
                        $('#employeePostCodeHelpMe').empty();
                        $('#employeePostCodeHelpMe').html('');
                        $('#employeeEmail').val('');
                        $('#employeeEmail').val('');
                        $('#employeeEmailHelpMe').empty();
                        $('#employeeEmailHelpMe').html('');
                        $('#employeeFacebook').val('');
                        $('#employeeFacebook').val('');
                        $('#employeeFacebookHelpMe').empty();
                        $('#employeeFacebookHelpMe').html('');
                        $('#employeeTwitter').val('');
                        $('#employeeTwitter').val('');
                        $('#employeeTwitterHelpMe').empty();
                        $('#employeeTwitterHelpMe').html('');
                        $('#employeeLinkedIn').val('');
                        $('#employeeLinkedIn').val('');
                        $('#employeeLinkedInHelpMe').empty();
                        $('#employeeLinkedInHelpMe').html('');
                        $('#employeeNotes').val('');
                        $('#employeeNotes').val('');
                        $('#employeeNotesHelpMe').empty();
                        $('#employeeNotesHelpMe').html('');
                        $('#employeeChequePrinting').val('');
                        $('#employeeChequePrinting').val('');
                        $('#employeeChequePrintingHelpMe').empty();
                        $('#employeeChequePrintingHelpMe').html('');
                    } else if (data.success == false) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                    }
                },
                error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                }
            });
        } else if (type == 2) {
            // new record and update  or delete record
            if ($('#cityId').val().length == 0) {
                $('#cityIdHelpMe').empty();
                $('#cityIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['cityIdLabel'] + " </span>");
                $('#cityId').data('chosen').activate_action();
                return false;
            }
            if ($('#stateId').val().length == 0) {
                $('#stateIdHelpMe').empty();
                $('#stateIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['stateIdLabel'] + " </span>");
                $('#stateId').data('chosen').activate_action();
                return false;
            }
            if ($('#countryId').val().length == 0) {
                $('#countryIdHelpMe').empty();
                $('#countryIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + " </span>");
                $('#countryId').data('chosen').activate_action();
                return false;
            }
            if ($('#jobId').val().length == 0) {
                $('#jobIdHelpMe').empty();
                $('#jobIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['jobIdLabel'] + " </span>");
                $('#jobId').data('chosen').activate_action();
                return false;
            }
            if ($('#genderId').val().length == 0) {
                $('#genderIdHelpMe').empty();
                $('#genderIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['genderIdLabel'] + " </span>");
                $('#genderId').data('chosen').activate_action();
                return false;
            }
            if ($('#marriageId').val().length == 0) {
                $('#marriageIdHelpMe').empty();
                $('#marriageIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['marriageIdLabel'] + " </span>");
                $('#marriageId').data('chosen').activate_action();
                return false;
            }
            if ($('#raceId').val().length == 0) {
                $('#raceIdHelpMe').empty();
                $('#raceIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['raceIdLabel'] + " </span>");
                $('#raceId').data('chosen').activate_action();
                return false;
            }
            if ($('#religionId').val().length == 0) {
                $('#religionIdHelpMe').empty();
                $('#religionIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['religionIdLabel'] + " </span>");
                $('#religionId').data('chosen').activate_action();
                return false;
            }
            if ($('#employmentStatusId').val().length == 0) {
                $('#employmentStatusIdHelpMe').empty();
                $('#employmentStatusIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employmentStatusIdLabel'] + " </span>");
                $('#employmentStatusId').data('chosen').activate_action();
                return false;
            }
            if ($('#employeeNumber').val().length == 0) {
                $('#employeeNumberHelpMe').empty();
                $('#employeeNumberHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeNumberLabel'] + " </span>");
                $('#employeeNumberForm').addClass('form-group has-error');
                $('#employeeNumber').focus();
                return false;
            }
            if ($('#employeeFirstName').val().length == 0) {
                $('#employeeFirstNameHelpMe').empty();
                $('#employeeFirstNameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFirstNameLabel'] + " </span>");
                $('#employeeFirstNameForm').addClass('form-group has-error');
                $('#employeeFirstName').focus();
                return false;
            }
            if ($('#employeeCompany').val().length == 0) {
                $('#employeeCompanyHelpMe').empty();
                $('#employeeCompanyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeCompanyLabel'] + " </span>");
                $('#employeeCompanyForm').addClass('form-group has-error');
                $('#employeeCompany').focus();
                return false;
            }
            if ($('#employeeLastName').val().length == 0) {
                $('#employeeLastNameHelpMe').empty();
                $('#employeeLastNameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeLastNameLabel'] + " </span>");
                $('#employeeLastNameForm').addClass('form-group has-error');
                $('#employeeLastName').focus();
                return false;
            }
            if ($('#employeeDateOfBirth').val().length == 0) {
                $('#employeeDateOfBirthHelpMe').empty();
                $('#employeeDateOfBirthHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeDateOfBirthLabel'] + " </span>");
                $('#employeeDateOfBirthForm').addClass('form-group has-error');
                $('#employeeDateOfBirth').focus();
                return false;
            }
            if ($('#employeeDateHired').val().length == 0) {
                $('#employeeDateHiredHelpMe').empty();
                $('#employeeDateHiredHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeDateHiredLabel'] + " </span>");
                $('#employeeDateHiredForm').addClass('form-group has-error');
                $('#employeeDateHired').focus();
                return false;
            }
            if ($('#employeeDateRetired').val().length == 0) {
                $('#employeeDateRetiredHelpMe').empty();
                $('#employeeDateRetiredHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeDateRetiredLabel'] + " </span>");
                $('#employeeDateRetiredForm').addClass('form-group has-error');
                $('#employeeDateRetired').focus();
                return false;
            }
            if ($('#employeeBusinessPhone').val().length == 0) {
                $('#employeeBusinessPhoneHelpMe').empty();
                $('#employeeBusinessPhoneHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeBusinessPhoneLabel'] + " </span>");
                $('#employeeBusinessPhoneForm').addClass('form-group has-error');
                $('#employeeBusinessPhone').focus();
                return false;
            }
            if ($('#employeeHomePhone').val().length == 0) {
                $('#employeeHomePhoneHelpMe').empty();
                $('#employeeHomePhoneHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeHomePhoneLabel'] + " </span>");
                $('#employeeHomePhoneForm').addClass('form-group has-error');
                $('#employeeHomePhone').focus();
                return false;
            }
            if ($('#employeeMobilePhone').val().length == 0) {
                $('#employeeMobilePhoneHelpMe').empty();
                $('#employeeMobilePhoneHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeMobilePhoneLabel'] + " </span>");
                $('#employeeMobilePhoneForm').addClass('form-group has-error');
                $('#employeeMobilePhone').focus();
                return false;
            }
            if ($('#employeeFaxNumber').val().length == 0) {
                $('#employeeFaxNumberHelpMe').empty();
                $('#employeeFaxNumberHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFaxNumberLabel'] + " </span>");
                $('#employeeFaxNumberForm').addClass('form-group has-error');
                $('#employeeFaxNumber').focus();
                return false;
            }
            if ($('#employeeAddress').val().length == 0) {
                $('#employeeAddressHelpMe').empty();
                $('#employeeAddressHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeAddressLabel'] + " </span>");
                $('#employeeAddressForm').addClass('form-group has-error');
                $('#employeeAddress').focus();
                return false;
            }
            if ($('#employeePostCode').val().length == 0) {
                $('#employeePostCodeHelpMe').empty();
                $('#employeePostCodeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeePostCodeLabel'] + " </span>");
                $('#employeePostCodeForm').addClass('form-group has-error');
                $('#employeePostCode').focus();
                return false;
            }
            if ($('#employeeEmail').val().length == 0) {
                $('#employeeEmailHelpMe').empty();
                $('#employeeEmailHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeEmailLabel'] + " </span>");
                $('#employeeEmailForm').addClass('form-group has-error');
                $('#employeeEmail').focus();
                return false;
            }
            if ($('#employeeFacebook').val().length == 0) {
                $('#employeeFacebookHelpMe').empty();
                $('#employeeFacebookHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFacebookLabel'] + " </span>");
                $('#employeeFacebookForm').addClass('form-group has-error');
                $('#employeeFacebook').focus();
                return false;
            }
            if ($('#employeeTwitter').val().length == 0) {
                $('#employeeTwitterHelpMe').empty();
                $('#employeeTwitterHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeTwitterLabel'] + " </span>");
                $('#employeeTwitterForm').addClass('form-group has-error');
                $('#employeeTwitter').focus();
                return false;
            }
            if ($('#employeeLinkedIn').val().length == 0) {
                $('#employeeLinkedInHelpMe').empty();
                $('#employeeLinkedInHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeLinkedInLabel'] + " </span>");
                $('#employeeLinkedInForm').addClass('form-group has-error');
                $('#employeeLinkedIn').focus();
                return false;
            }
            if ($('#employeeNotes').val().length == 0) {
                $('#employeeNotesHelpMe').empty();
                $('#employeeNotesHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeNotesLabel'] + " </span>");
                $('#employeeNotesForm').addClass('form-group has-error');
                $('#employeeNotes').focus();
                return false;
            }
            if ($('#employeeChequePrinting').val().length == 0) {
                $('#employeeChequePrintingHelpMe').empty();
                $('#employeeChequePrintingHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeChequePrintingLabel'] + " </span>");
                $('#employeeChequePrintingForm').addClass('form-group has-error');
                $('#employeeChequePrinting').focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    cityId: $('#cityId').val(),
                    stateId: $('#stateId').val(),
                    countryId: $('#countryId').val(),
                    jobId: $('#jobId').val(),
                    genderId: $('#genderId').val(),
                    marriageId: $('#marriageId').val(),
                    raceId: $('#raceId').val(),
                    religionId: $('#religionId').val(),
                    employmentStatusId: $('#employmentStatusId').val(),
                    employeeNumber: $('#employeeNumber').val(),
                    employeeFirstName: $('#employeeFirstName').val(),
                    employeeCompany: $('#employeeCompany').val(),
                    employeeLastName: $('#employeeLastName').val(),
                    employeeDateOfBirth: $('#employeeDateOfBirth').val(),
                    employeeDateHired: $('#employeeDateHired').val(),
                    employeeDateRetired: $('#employeeDateRetired').val(),
                    employeeBusinessPhone: $('#employeeBusinessPhone').val(),
                    employeeHomePhone: $('#employeeHomePhone').val(),
                    employeeMobilePhone: $('#employeeMobilePhone').val(),
                    employeeFaxNumber: $('#employeeFaxNumber').val(),
                    employeeAddress: $('#employeeAddress').val(),
                    employeePostCode: $('#employeePostCode').val(),
                    employeeEmail: $('#employeeEmail').val(),
                    employeeFacebook: $('#employeeFacebook').val(),
                    employeeTwitter: $('#employeeTwitter').val(),
                    employeeLinkedIn: $('#employeeLinkedIn').val(),
                    employeeNotes: $('#employeeNotes').val(),
                    employeeChequePrinting: $('#employeeChequePrinting').val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    // this is where we append a loading image
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success == true) {
                        $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>");
                        $('#employeeId').val(data.employeeId);
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
                },
                error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                }
            });
        } else if (type == 5) {
            //New Record and listing
            if ($('#cityId').val().length == 0) {
                $('#cityIdHelpMe').empty();
                $('#cityIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['cityIdLabel'] + " </span>");
                $('#cityId').data('chosen').activate_action();
                return false;
            }
            if ($('#stateId').val().length == 0) {
                $('#stateIdHelpMe').empty();
                $('#stateIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['stateIdLabel'] + " </span>");
                $('#stateId').data('chosen').activate_action();
                return false;
            }
            if ($('#countryId').val().length == 0) {
                $('#countryIdHelpMe').empty();
                $('#countryIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + " </span>");
                $('#countryId').data('chosen').activate_action();
                return false;
            }
            if ($('#jobId').val().length == 0) {
                $('#jobIdHelpMe').empty();
                $('#jobIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['jobIdLabel'] + " </span>");
                $('#jobId').data('chosen').activate_action();
                return false;
            }
            if ($('#genderId').val().length == 0) {
                $('#genderIdHelpMe').empty();
                $('#genderIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['genderIdLabel'] + " </span>");
                $('#genderId').data('chosen').activate_action();
                return false;
            }
            if ($('#marriageId').val().length == 0) {
                $('#marriageIdHelpMe').empty();
                $('#marriageIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['marriageIdLabel'] + " </span>");
                $('#marriageId').data('chosen').activate_action();
                return false;
            }
            if ($('#raceId').val().length == 0) {
                $('#raceIdHelpMe').empty();
                $('#raceIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['raceIdLabel'] + " </span>");
                $('#raceId').data('chosen').activate_action();
                return false;
            }
            if ($('#religionId').val().length == 0) {
                $('#religionIdHelpMe').empty();
                $('#religionIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['religionIdLabel'] + " </span>");
                $('#religionId').data('chosen').activate_action();
                return false;
            }
            if ($('#employmentStatusId').val().length == 0) {
                $('#employmentStatusIdHelpMe').empty();
                $('#employmentStatusIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employmentStatusIdLabel'] + " </span>");
                $('#employmentStatusId').data('chosen').activate_action();
                return false;
            }
            if ($('#employeeNumber').val().length == 0) {
                $('#employeeNumberHelpMe').empty();
                $('#employeeNumberHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeNumberLabel'] + " </span>");
                $('#employeeNumberForm').addClass('form-group has-error');
                $('#employeeNumber').focus();
                return false;
            }
            if ($('#employeeFirstName').val().length == 0) {
                $('#employeeFirstNameHelpMe').empty();
                $('#employeeFirstNameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFirstNameLabel'] + " </span>");
                $('#employeeFirstNameForm').addClass('form-group has-error');
                $('#employeeFirstName').focus();
                return false;
            }
            if ($('#employeeCompany').val().length == 0) {
                $('#employeeCompanyHelpMe').empty();
                $('#employeeCompanyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeCompanyLabel'] + " </span>");
                $('#employeeCompanyForm').addClass('form-group has-error');
                $('#employeeCompany').focus();
                return false;
            }
            if ($('#employeeLastName').val().length == 0) {
                $('#employeeLastNameHelpMe').empty();
                $('#employeeLastNameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeLastNameLabel'] + " </span>");
                $('#employeeLastNameForm').addClass('form-group has-error');
                $('#employeeLastName').focus();
                return false;
            }
            if ($('#employeeDateOfBirth').val().length == 0) {
                $('#employeeDateOfBirthHelpMe').empty();
                $('#employeeDateOfBirthHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeDateOfBirthLabel'] + " </span>");
                $('#employeeDateOfBirthForm').addClass('form-group has-error');
                $('#employeeDateOfBirth').focus();
                return false;
            }
            if ($('#employeeDateHired').val().length == 0) {
                $('#employeeDateHiredHelpMe').empty();
                $('#employeeDateHiredHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeDateHiredLabel'] + " </span>");
                $('#employeeDateHiredForm').addClass('form-group has-error');
                $('#employeeDateHired').focus();
                return false;
            }
            if ($('#employeeDateRetired').val().length == 0) {
                $('#employeeDateRetiredHelpMe').empty();
                $('#employeeDateRetiredHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeDateRetiredLabel'] + " </span>");
                $('#employeeDateRetiredForm').addClass('form-group has-error');
                $('#employeeDateRetired').focus();
                return false;
            }
            if ($('#employeeBusinessPhone').val().length == 0) {
                $('#employeeBusinessPhoneHelpMe').empty();
                $('#employeeBusinessPhoneHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeBusinessPhoneLabel'] + " </span>");
                $('#employeeBusinessPhoneForm').addClass('form-group has-error');
                $('#employeeBusinessPhone').focus();
                return false;
            }
            if ($('#employeeHomePhone').val().length == 0) {
                $('#employeeHomePhoneHelpMe').empty();
                $('#employeeHomePhoneHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeHomePhoneLabel'] + " </span>");
                $('#employeeHomePhoneForm').addClass('form-group has-error');
                $('#employeeHomePhone').focus();
                return false;
            }
            if ($('#employeeMobilePhone').val().length == 0) {
                $('#employeeMobilePhoneHelpMe').empty();
                $('#employeeMobilePhoneHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeMobilePhoneLabel'] + " </span>");
                $('#employeeMobilePhoneForm').addClass('form-group has-error');
                $('#employeeMobilePhone').focus();
                return false;
            }
            if ($('#employeeFaxNumber').val().length == 0) {
                $('#employeeFaxNumberHelpMe').empty();
                $('#employeeFaxNumberHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFaxNumberLabel'] + " </span>");
                $('#employeeFaxNumberForm').addClass('form-group has-error');
                $('#employeeFaxNumber').focus();
                return false;
            }
            if ($('#employeeAddress').val().length == 0) {
                $('#employeeAddressHelpMe').empty();
                $('#employeeAddressHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeAddressLabel'] + " </span>");
                $('#employeeAddressForm').addClass('form-group has-error');
                $('#employeeAddress').focus();
                return false;
            }
            if ($('#employeePostCode').val().length == 0) {
                $('#employeePostCodeHelpMe').empty();
                $('#employeePostCodeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeePostCodeLabel'] + " </span>");
                $('#employeePostCodeForm').addClass('form-group has-error');
                $('#employeePostCode').focus();
                return false;
            }
            if ($('#employeeEmail').val().length == 0) {
                $('#employeeEmailHelpMe').empty();
                $('#employeeEmailHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeEmailLabel'] + " </span>");
                $('#employeeEmailForm').addClass('form-group has-error');
                $('#employeeEmail').focus();
                return false;
            }
            if ($('#employeeFacebook').val().length == 0) {
                $('#employeeFacebookHelpMe').empty();
                $('#employeeFacebookHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFacebookLabel'] + " </span>");
                $('#employeeFacebookForm').addClass('form-group has-error');
                $('#employeeFacebook').focus();
                return false;
            }
            if ($('#employeeTwitter').val().length == 0) {
                $('#employeeTwitterHelpMe').empty();
                $('#employeeTwitterHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeTwitterLabel'] + " </span>");
                $('#employeeTwitterForm').addClass('form-group has-error');
                $('#employeeTwitter').focus();
                return false;
            }
            if ($('#employeeLinkedIn').val().length == 0) {
                $('#employeeLinkedInHelpMe').empty();
                $('#employeeLinkedInHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeLinkedInLabel'] + " </span>");
                $('#employeeLinkedInForm').addClass('form-group has-error');
                $('#employeeLinkedIn').focus();
                return false;
            }
            if ($('#employeeNotes').val().length == 0) {
                $('#employeeNotesHelpMe').empty();
                $('#employeeNotesHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeNotesLabel'] + " </span>");
                $('#employeeNotesForm').addClass('form-group has-error');
                $('#employeeNotes').focus();
                return false;
            }
            if ($('#employeeChequePrinting').val().length == 0) {
                $('#employeeChequePrintingHelpMe').empty();
                $('#employeeChequePrintingHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeChequePrintingLabel'] + " </span>");
                $('#employeeChequePrintingForm').addClass('form-group has-error');
                $('#employeeChequePrinting').focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    cityId: $('#cityId').val(),
                    stateId: $('#stateId').val(),
                    countryId: $('#countryId').val(),
                    jobId: $('#jobId').val(),
                    genderId: $('#genderId').val(),
                    marriageId: $('#marriageId').val(),
                    raceId: $('#raceId').val(),
                    religionId: $('#religionId').val(),
                    employmentStatusId: $('#employmentStatusId').val(),
                    employeeNumber: $('#employeeNumber').val(),
                    employeeFirstName: $('#employeeFirstName').val(),
                    employeeCompany: $('#employeeCompany').val(),
                    employeeLastName: $('#employeeLastName').val(),
                    employeeDateOfBirth: $('#employeeDateOfBirth').val(),
                    employeeDateHired: $('#employeeDateHired').val(),
                    employeeDateRetired: $('#employeeDateRetired').val(),
                    employeeBusinessPhone: $('#employeeBusinessPhone').val(),
                    employeeHomePhone: $('#employeeHomePhone').val(),
                    employeeMobilePhone: $('#employeeMobilePhone').val(),
                    employeeFaxNumber: $('#employeeFaxNumber').val(),
                    employeeAddress: $('#employeeAddress').val(),
                    employeePostCode: $('#employeePostCode').val(),
                    employeeEmail: $('#employeeEmail').val(),
                    employeeFacebook: $('#employeeFacebook').val(),
                    employeeTwitter: $('#employeeTwitter').val(),
                    employeeLinkedIn: $('#employeeLinkedIn').val(),
                    employeeNotes: $('#employeeNotes').val(),
                    employeeChequePrinting: $('#employeeChequePrinting').val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    // this is where we append a loading image
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                },
                success: function(data) {
                    // successful request; do something with the data
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
                },
                error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                }
            });
        }
        showMeDiv('tableDate', 0);
        showMeDiv('formEntry', 1);
    }
}
function updateRecord(leafId, url, urlList, securityToken, type, deleteAccess) {
    var css = $('#updateRecordButton2').attr('class');
    if (css.search('disabled') > 0) {
        // access denied
    } else {
        $('#infoPanel').empty();
        $('#infoPanel').html('');
        if (type == 1) {
            // update record and continue
            if ($('#cityId').val().length == 0) {
                $('#cityIdHelpMe').empty();
                $('#cityIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['cityIdLabel'] + " </span>");
                $('#cityId').data('chosen').activate_action();
                return false;
            }
            if ($('#stateId').val().length == 0) {
                $('#stateIdHelpMe').empty();
                $('#stateIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['stateIdLabel'] + " </span>");
                $('#stateId').data('chosen').activate_action();
                return false;
            }
            if ($('#countryId').val().length == 0) {
                $('#countryIdHelpMe').empty();
                $('#countryIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + " </span>");
                $('#countryId').data('chosen').activate_action();
                return false;
            }
            if ($('#jobId').val().length == 0) {
                $('#jobIdHelpMe').empty();
                $('#jobIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['jobIdLabel'] + " </span>");
                $('#jobId').data('chosen').activate_action();
                return false;
            }
            if ($('#genderId').val().length == 0) {
                $('#genderIdHelpMe').empty();
                $('#genderIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['genderIdLabel'] + " </span>");
                $('#genderId').data('chosen').activate_action();
                return false;
            }
            if ($('#marriageId').val().length == 0) {
                $('#marriageIdHelpMe').empty();
                $('#marriageIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['marriageIdLabel'] + " </span>");
                $('#marriageId').data('chosen').activate_action();
                return false;
            }
            if ($('#raceId').val().length == 0) {
                $('#raceIdHelpMe').empty();
                $('#raceIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['raceIdLabel'] + " </span>");
                $('#raceId').data('chosen').activate_action();
                return false;
            }
            if ($('#religionId').val().length == 0) {
                $('#religionIdHelpMe').empty();
                $('#religionIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['religionIdLabel'] + " </span>");
                $('#religionId').data('chosen').activate_action();
                return false;
            }
            if ($('#employmentStatusId').val().length == 0) {
                $('#employmentStatusIdHelpMe').empty();
                $('#employmentStatusIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employmentStatusIdLabel'] + " </span>");
                $('#employmentStatusId').data('chosen').activate_action();
                return false;
            }
            if ($('#employeeNumber').val().length == 0) {
                $('#employeeNumberHelpMe').empty();
                $('#employeeNumberHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeNumberLabel'] + " </span>");
                $('#employeeNumberForm').addClass('form-group has-error');
                $('#employeeNumber').focus();
                return false;
            }
            if ($('#employeeFirstName').val().length == 0) {
                $('#employeeFirstNameHelpMe').empty();
                $('#employeeFirstNameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFirstNameLabel'] + " </span>");
                $('#employeeFirstNameForm').addClass('form-group has-error');
                $('#employeeFirstName').focus();
                return false;
            }
            if ($('#employeeCompany').val().length == 0) {
                $('#employeeCompanyHelpMe').empty();
                $('#employeeCompanyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeCompanyLabel'] + " </span>");
                $('#employeeCompanyForm').addClass('form-group has-error');
                $('#employeeCompany').focus();
                return false;
            }
            if ($('#employeeLastName').val().length == 0) {
                $('#employeeLastNameHelpMe').empty();
                $('#employeeLastNameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeLastNameLabel'] + " </span>");
                $('#employeeLastNameForm').addClass('form-group has-error');
                $('#employeeLastName').focus();
                return false;
            }
            if ($('#employeeDateOfBirth').val().length == 0) {
                $('#employeeDateOfBirthHelpMe').empty();
                $('#employeeDateOfBirthHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeDateOfBirthLabel'] + " </span>");
                $('#employeeDateOfBirthForm').addClass('form-group has-error');
                $('#employeeDateOfBirth').focus();
                return false;
            }
            if ($('#employeeDateHired').val().length == 0) {
                $('#employeeDateHiredHelpMe').empty();
                $('#employeeDateHiredHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeDateHiredLabel'] + " </span>");
                $('#employeeDateHiredForm').addClass('form-group has-error');
                $('#employeeDateHired').focus();
                return false;
            }
            if ($('#employeeDateRetired').val().length == 0) {
                $('#employeeDateRetiredHelpMe').empty();
                $('#employeeDateRetiredHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeDateRetiredLabel'] + " </span>");
                $('#employeeDateRetiredForm').addClass('form-group has-error');
                $('#employeeDateRetired').focus();
                return false;
            }
            if ($('#employeeBusinessPhone').val().length == 0) {
                $('#employeeBusinessPhoneHelpMe').empty();
                $('#employeeBusinessPhoneHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeBusinessPhoneLabel'] + " </span>");
                $('#employeeBusinessPhoneForm').addClass('form-group has-error');
                $('#employeeBusinessPhone').focus();
                return false;
            }
            if ($('#employeeHomePhone').val().length == 0) {
                $('#employeeHomePhoneHelpMe').empty();
                $('#employeeHomePhoneHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeHomePhoneLabel'] + " </span>");
                $('#employeeHomePhoneForm').addClass('form-group has-error');
                $('#employeeHomePhone').focus();
                return false;
            }
            if ($('#employeeMobilePhone').val().length == 0) {
                $('#employeeMobilePhoneHelpMe').empty();
                $('#employeeMobilePhoneHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeMobilePhoneLabel'] + " </span>");
                $('#employeeMobilePhoneForm').addClass('form-group has-error');
                $('#employeeMobilePhone').focus();
                return false;
            }
            if ($('#employeeFaxNumber').val().length == 0) {
                $('#employeeFaxNumberHelpMe').empty();
                $('#employeeFaxNumberHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFaxNumberLabel'] + " </span>");
                $('#employeeFaxNumberForm').addClass('form-group has-error');
                $('#employeeFaxNumber').focus();
                return false;
            }
            if ($('#employeeAddress').val().length == 0) {
                $('#employeeAddressHelpMe').empty();
                $('#employeeAddressHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeAddressLabel'] + " </span>");
                $('#employeeAddressForm').addClass('form-group has-error');
                $('#employeeAddress').focus();
                return false;
            }
            if ($('#employeePostCode').val().length == 0) {
                $('#employeePostCodeHelpMe').empty();
                $('#employeePostCodeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeePostCodeLabel'] + " </span>");
                $('#employeePostCodeForm').addClass('form-group has-error');
                $('#employeePostCode').focus();
                return false;
            }
            if ($('#employeeEmail').val().length == 0) {
                $('#employeeEmailHelpMe').empty();
                $('#employeeEmailHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeEmailLabel'] + " </span>");
                $('#employeeEmailForm').addClass('form-group has-error');
                $('#employeeEmail').focus();
                return false;
            }
            if ($('#employeeFacebook').val().length == 0) {
                $('#employeeFacebookHelpMe').empty();
                $('#employeeFacebookHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFacebookLabel'] + " </span>");
                $('#employeeFacebookForm').addClass('form-group has-error');
                $('#employeeFacebook').focus();
                return false;
            }
            if ($('#employeeTwitter').val().length == 0) {
                $('#employeeTwitterHelpMe').empty();
                $('#employeeTwitterHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeTwitterLabel'] + " </span>");
                $('#employeeTwitterForm').addClass('form-group has-error');
                $('#employeeTwitter').focus();
                return false;
            }
            if ($('#employeeLinkedIn').val().length == 0) {
                $('#employeeLinkedInHelpMe').empty();
                $('#employeeLinkedInHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeLinkedInLabel'] + " </span>");
                $('#employeeLinkedInForm').addClass('form-group has-error');
                $('#employeeLinkedIn').focus();
                return false;
            }
            if ($('#employeeNotes').val().length == 0) {
                $('#employeeNotesHelpMe').empty();
                $('#employeeNotesHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeNotesLabel'] + " </span>");
                $('#employeeNotesForm').addClass('form-group has-error');
                $('#employeeNotes').focus();
                return false;
            }
            if ($('#employeeChequePrinting').val().length == 0) {
                $('#employeeChequePrintingHelpMe').empty();
                $('#employeeChequePrintingHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeChequePrintingLabel'] + " </span>");
                $('#employeeChequePrintingForm').addClass('form-group has-error');
                $('#employeeChequePrinting').focus();
                return false;
            }
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'save',
                    output: 'json',
                    employeeId: $('#employeeId').val(),
                    cityId: $('#cityId').val(),
                    stateId: $('#stateId').val(),
                    countryId: $('#countryId').val(),
                    jobId: $('#jobId').val(),
                    genderId: $('#genderId').val(),
                    marriageId: $('#marriageId').val(),
                    raceId: $('#raceId').val(),
                    religionId: $('#religionId').val(),
                    employmentStatusId: $('#employmentStatusId').val(),
                    employeeNumber: $('#employeeNumber').val(),
                    employeeFirstName: $('#employeeFirstName').val(),
                    employeeCompany: $('#employeeCompany').val(),
                    employeeLastName: $('#employeeLastName').val(),
                    employeeDateOfBirth: $('#employeeDateOfBirth').val(),
                    employeeDateHired: $('#employeeDateHired').val(),
                    employeeDateRetired: $('#employeeDateRetired').val(),
                    employeeBusinessPhone: $('#employeeBusinessPhone').val(),
                    employeeHomePhone: $('#employeeHomePhone').val(),
                    employeeMobilePhone: $('#employeeMobilePhone').val(),
                    employeeFaxNumber: $('#employeeFaxNumber').val(),
                    employeeAddress: $('#employeeAddress').val(),
                    employeePostCode: $('#employeePostCode').val(),
                    employeeEmail: $('#employeeEmail').val(),
                    employeeFacebook: $('#employeeFacebook').val(),
                    employeeTwitter: $('#employeeTwitter').val(),
                    employeeLinkedIn: $('#employeeLinkedIn').val(),
                    employeeNotes: $('#employeeNotes').val(),
                    employeeChequePrinting: $('#employeeChequePrinting').val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    // this is where we append a loading image
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                },
                success: function(data) {
                    // successful request; do something with the data
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
                },
                error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                }
            });
        } else if (type == 3) {
            // update record and listing
            if ($('#cityId').val().length == 0) {
                $('#cityIdHelpMe').empty();
                $('#cityIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['cityIdLabel'] + " </span>");
                $('#cityId').data('chosen').activate_action();
                return false;
            }
            if ($('#stateId').val().length == 0) {
                $('#stateIdHelpMe').empty();
                $('#stateIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['stateIdLabel'] + " </span>");
                $('#stateId').data('chosen').activate_action();
                return false;
            }
            if ($('#countryId').val().length == 0) {
                $('#countryIdHelpMe').empty();
                $('#countryIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + " </span>");
                $('#countryId').data('chosen').activate_action();
                return false;
            }
            if ($('#jobId').val().length == 0) {
                $('#jobIdHelpMe').empty();
                $('#jobIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['jobIdLabel'] + " </span>");
                $('#jobId').data('chosen').activate_action();
                return false;
            }
            if ($('#genderId').val().length == 0) {
                $('#genderIdHelpMe').empty();
                $('#genderIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['genderIdLabel'] + " </span>");
                $('#genderId').data('chosen').activate_action();
                return false;
            }
            if ($('#marriageId').val().length == 0) {
                $('#marriageIdHelpMe').empty();
                $('#marriageIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['marriageIdLabel'] + " </span>");
                $('#marriageId').data('chosen').activate_action();
                return false;
            }
            if ($('#raceId').val().length == 0) {
                $('#raceIdHelpMe').empty();
                $('#raceIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['raceIdLabel'] + " </span>");
                $('#raceId').data('chosen').activate_action();
                return false;
            }
            if ($('#religionId').val().length == 0) {
                $('#religionIdHelpMe').empty();
                $('#religionIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['religionIdLabel'] + " </span>");
                $('#religionId').data('chosen').activate_action();
                return false;
            }
            if ($('#employmentStatusId').val().length == 0) {
                $('#employmentStatusIdHelpMe').empty();
                $('#employmentStatusIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employmentStatusIdLabel'] + " </span>");
                $('#employmentStatusId').data('chosen').activate_action();
                return false;
            }
            if ($('#employeeNumber').val().length == 0) {
                $('#employeeNumberHelpMe').empty();
                $('#employeeNumberHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeNumberLabel'] + " </span>");
                $('#employeeNumberForm').addClass('form-group has-error');
                $('#employeeNumber').focus();
                return false;
            }
            if ($('#employeeFirstName').val().length == 0) {
                $('#employeeFirstNameHelpMe').empty();
                $('#employeeFirstNameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFirstNameLabel'] + " </span>");
                $('#employeeFirstNameForm').addClass('form-group has-error');
                $('#employeeFirstName').focus();
                return false;
            }
            if ($('#employeeCompany').val().length == 0) {
                $('#employeeCompanyHelpMe').empty();
                $('#employeeCompanyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeCompanyLabel'] + " </span>");
                $('#employeeCompanyForm').addClass('form-group has-error');
                $('#employeeCompany').focus();
                return false;
            }
            if ($('#employeeLastName').val().length == 0) {
                $('#employeeLastNameHelpMe').empty();
                $('#employeeLastNameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeLastNameLabel'] + " </span>");
                $('#employeeLastNameForm').addClass('form-group has-error');
                $('#employeeLastName').focus();
                return false;
            }
            if ($('#employeeDateOfBirth').val().length == 0) {
                $('#employeeDateOfBirthHelpMe').empty();
                $('#employeeDateOfBirthHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeDateOfBirthLabel'] + " </span>");
                $('#employeeDateOfBirthForm').addClass('form-group has-error');
                $('#employeeDateOfBirth').focus();
                return false;
            }
            if ($('#employeeDateHired').val().length == 0) {
                $('#employeeDateHiredHelpMe').empty();
                $('#employeeDateHiredHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeDateHiredLabel'] + " </span>");
                $('#employeeDateHiredForm').addClass('form-group has-error');
                $('#employeeDateHired').focus();
                return false;
            }
            if ($('#employeeDateRetired').val().length == 0) {
                $('#employeeDateRetiredHelpMe').empty();
                $('#employeeDateRetiredHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeDateRetiredLabel'] + " </span>");
                $('#employeeDateRetiredForm').addClass('form-group has-error');
                $('#employeeDateRetired').focus();
                return false;
            }
            if ($('#employeeBusinessPhone').val().length == 0) {
                $('#employeeBusinessPhoneHelpMe').empty();
                $('#employeeBusinessPhoneHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeBusinessPhoneLabel'] + " </span>");
                $('#employeeBusinessPhoneForm').addClass('form-group has-error');
                $('#employeeBusinessPhone').focus();
                return false;
            }
            if ($('#employeeHomePhone').val().length == 0) {
                $('#employeeHomePhoneHelpMe').empty();
                $('#employeeHomePhoneHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeHomePhoneLabel'] + " </span>");
                $('#employeeHomePhoneForm').addClass('form-group has-error');
                $('#employeeHomePhone').focus();
                return false;
            }
            if ($('#employeeMobilePhone').val().length == 0) {
                $('#employeeMobilePhoneHelpMe').empty();
                $('#employeeMobilePhoneHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeMobilePhoneLabel'] + " </span>");
                $('#employeeMobilePhoneForm').addClass('form-group has-error');
                $('#employeeMobilePhone').focus();
                return false;
            }
            if ($('#employeeFaxNumber').val().length == 0) {
                $('#employeeFaxNumberHelpMe').empty();
                $('#employeeFaxNumberHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFaxNumberLabel'] + " </span>");
                $('#employeeFaxNumberForm').addClass('form-group has-error');
                $('#employeeFaxNumber').focus();
                return false;
            }
            if ($('#employeeAddress').val().length == 0) {
                $('#employeeAddressHelpMe').empty();
                $('#employeeAddressHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeAddressLabel'] + " </span>");
                $('#employeeAddressForm').addClass('form-group has-error');
                $('#employeeAddress').focus();
                return false;
            }
            if ($('#employeePostCode').val().length == 0) {
                $('#employeePostCodeHelpMe').empty();
                $('#employeePostCodeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeePostCodeLabel'] + " </span>");
                $('#employeePostCodeForm').addClass('form-group has-error');
                $('#employeePostCode').focus();
                return false;
            }
            if ($('#employeeEmail').val().length == 0) {
                $('#employeeEmailHelpMe').empty();
                $('#employeeEmailHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeEmailLabel'] + " </span>");
                $('#employeeEmailForm').addClass('form-group has-error');
                $('#employeeEmail').focus();
                return false;
            }
            if ($('#employeeFacebook').val().length == 0) {
                $('#employeeFacebookHelpMe').empty();
                $('#employeeFacebookHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeFacebookLabel'] + " </span>");
                $('#employeeFacebookForm').addClass('form-group has-error');
                $('#employeeFacebook').focus();
                return false;
            }
            if ($('#employeeTwitter').val().length == 0) {
                $('#employeeTwitterHelpMe').empty();
                $('#employeeTwitterHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeTwitterLabel'] + " </span>");
                $('#employeeTwitterForm').addClass('form-group has-error');
                $('#employeeTwitter').focus();
                return false;
            }
            if ($('#employeeLinkedIn').val().length == 0) {
                $('#employeeLinkedInHelpMe').empty();
                $('#employeeLinkedInHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeLinkedInLabel'] + " </span>");
                $('#employeeLinkedInForm').addClass('form-group has-error');
                $('#employeeLinkedIn').focus();
                return false;
            }
            if ($('#employeeNotes').val().length == 0) {
                $('#employeeNotesHelpMe').empty();
                $('#employeeNotesHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeNotesLabel'] + " </span>");
                $('#employeeNotesForm').addClass('form-group has-error');
                $('#employeeNotes').focus();
                return false;
            }
            if ($('#employeeChequePrinting').val().length == 0) {
                $('#employeeChequePrintingHelpMe').empty();
                $('#employeeChequePrintingHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['employeeChequePrintingLabel'] + " </span>");
                $('#employeeChequePrintingForm').addClass('form-group has-error');
                $('#employeeChequePrinting').focus();
                return false;
            }
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'save',
                    output: 'json',
                    employeeId: $('#employeeId').val(),
                    cityId: $('#cityId').val(),
                    stateId: $('#stateId').val(),
                    countryId: $('#countryId').val(),
                    jobId: $('#jobId').val(),
                    genderId: $('#genderId').val(),
                    marriageId: $('#marriageId').val(),
                    raceId: $('#raceId').val(),
                    religionId: $('#religionId').val(),
                    employmentStatusId: $('#employmentStatusId').val(),
                    employeeNumber: $('#employeeNumber').val(),
                    employeeFirstName: $('#employeeFirstName').val(),
                    employeeCompany: $('#employeeCompany').val(),
                    employeeLastName: $('#employeeLastName').val(),
                    employeeDateOfBirth: $('#employeeDateOfBirth').val(),
                    employeeDateHired: $('#employeeDateHired').val(),
                    employeeDateRetired: $('#employeeDateRetired').val(),
                    employeeBusinessPhone: $('#employeeBusinessPhone').val(),
                    employeeHomePhone: $('#employeeHomePhone').val(),
                    employeeMobilePhone: $('#employeeMobilePhone').val(),
                    employeeFaxNumber: $('#employeeFaxNumber').val(),
                    employeeAddress: $('#employeeAddress').val(),
                    employeePostCode: $('#employeePostCode').val(),
                    employeeEmail: $('#employeeEmail').val(),
                    employeeFacebook: $('#employeeFacebook').val(),
                    employeeTwitter: $('#employeeTwitter').val(),
                    employeeLinkedIn: $('#employeeLinkedIn').val(),
                    employeeNotes: $('#employeeNotes').val(),
                    employeeChequePrinting: $('#employeeChequePrinting').val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    // this is where we append a loading image
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                },
                success: function(data) {
                    // successful request; do something with the data
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
                },
                error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                }
            });
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
                var value = $('#employeeId').val();
                if (!value) {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-important'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "<span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                    return false;
                } else {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'delete',
                            output: 'json',
                            employeeId: $('#employeeId').val(),
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            // this is where we append a loading image
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        },
                        success: function(data) {
                            // successful request; do something with the data
                            if (data.success == true) {
                                showGrid(leafId, urlList, securityToken, 0, 10, 2);
                            } else if (data.success == false) {
                                $('#infoPanel').empty();
                                $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
                                if ($('#infoPanel').is(':hidden')) {
                                    $('#infoPanel').show();
                                }
                            }
                        },
                        error: function(xhr) {
                            $('#infoError').empty();
                            $('#infoError').html('');
                            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass();
                            $('#infoErrorRowFluid').addClass('row');
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
    $("#employeeId").val('');
    
    $("#cityId").val('');
    $('#cityId').trigger("chosen:updated");
    $("#stateId").val('');
    $('#stateId').trigger("chosen:updated");
    $("#countryId").val('');
    $('#countryId').trigger("chosen:updated");
    $("#jobId").val('');
    $('#jobId').trigger("chosen:updated");
    $("#genderId").val('');
    $('#genderId').trigger("chosen:updated");
    $("#marriageId").val('');
    $('#marriageId').trigger("chosen:updated");
    $("#raceId").val('');
    $('#raceId').trigger("chosen:updated");
    $("#religionId").val('');
    $('#religionId').trigger("chosen:updated");
    $("#employmentStatusId").val('');
    $('#employmentStatusId').trigger("chosen:updated");
    $("#employeeNumber").val('');
    $("#employeeFirstName").val('');
    $("#employeeCompany").val('');
    $("#employeeLastName").val('');
    $("#employeeDateOfBirth").val('');
    $("#employeeDateHired").val('');
    $("#employeeDateRetired").val('');
    $("#employeeBusinessPhone").val('');
    $("#employeeHomePhone").val('');
    $("#employeeMobilePhone").val('');
    $("#employeeFaxNumber").val('');
    $("#employeeAddress").val('');
    $('#employeeAddress').empty();
    $('#employeeAddress').val('');
    $("#employeePostCode").val('');
    $("#employeeEmail").val('');
    $("#employeeFacebook").val('');
    $("#employeeTwitter").val('');
    $("#employeeLinkedIn").val('');
    $("#employeeNotes").val('');
    $("#employeeChequePrinting").val('');
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
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            },
            success: function(data) {
                // successful request; do something with the data
                if (data.success == true) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'read',
                            employeeId: data.firstRecord,
                            output: 'json',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            // this is where we append a loading image
                            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        },
                        success: function(data) {
                            // successful request; do something with the data
                            if (data.success == true) {
                                // resetting field value
                                $('#employeeId').val(data.data.employeeId);
                                $('#cityId').val(data.data.cityId);
                                $('#cityId').trigger("chosen:updated");
                                $('#stateId').val(data.data.stateId);
                                $('#stateId').trigger("chosen:updated");
                                $('#countryId').val(data.data.countryId);
                                $('#countryId').trigger("chosen:updated");
                                $('#jobId').val(data.data.jobId);
                                $('#jobId').trigger("chosen:updated");
                                $('#genderId').val(data.data.genderId);
                                $('#genderId').trigger("chosen:updated");
                                $('#marriageId').val(data.data.marriageId);
                                $('#marriageId').trigger("chosen:updated");
                                $('#raceId').val(data.data.raceId);
                                $('#raceId').trigger("chosen:updated");
                                $('#religionId').val(data.data.religionId);
                                $('#religionId').trigger("chosen:updated");
                                $('#employmentStatusId').val(data.data.employmentStatusId);
                                $('#employmentStatusId').trigger("chosen:updated");
                                $('#employeeNumber').val(data.data.employeeNumber);
                                $('#employeeFirstName').val(data.data.employeeFirstName);
                                $('#employeeCompany').val(data.data.employeeCompany);
                                $('#employeeLastName').val(data.data.employeeLastName);
                                var x = data.data.employeeDateOfBirth;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#employeeDateOfBirth').val(output);
                                var x = data.data.employeeDateHired;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#employeeDateHired').val(output);
                                var x = data.data.employeeDateRetired;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
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
                        },
                        error: function(xhr) {
                            $('#infoError').empty();
                            $('#infoError').html('');
                            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass();
                            $('#infoErrorRowFluid').addClass('row');
                        }
                    });
                } else {
                    $('#infoPanel').empty();
                    $('#infoPanel').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll-sweat.png'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }
            },
            error: function(xhr) {
                $('#infoError').empty();
                $('#infoError').html('');
                $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass();
                $('#infoErrorRowFluid').addClass('row');
            }
        });
    }
}
function endRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess) {
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
                $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            },
            success: function(data) {
                // successful request; do something with the data
                if (data.success == true) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'read',
                            employeeId: data.lastRecord,
                            output: 'json',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            // this is where we append a loading image
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        },
                        success: function(data) {
                            // successful request; do something with the data
                            if (data.success == true) {
                                // reseting field value
                                $('#employeeId').val(data.data.employeeId);
                                $('#cityId').val(data.data.cityId);
                                $('#cityId').trigger("chosen:updated");
                                $('#stateId').val(data.data.stateId);
                                $('#stateId').trigger("chosen:updated");
                                $('#countryId').val(data.data.countryId);
                                $('#countryId').trigger("chosen:updated");
                                $('#jobId').val(data.data.jobId);
                                $('#jobId').trigger("chosen:updated");
                                $('#genderId').val(data.data.genderId);
                                $('#genderId').trigger("chosen:updated");
                                $('#marriageId').val(data.data.marriageId);
                                $('#marriageId').trigger("chosen:updated");
                                $('#raceId').val(data.data.raceId);
                                $('#raceId').trigger("chosen:updated");
                                $('#religionId').val(data.data.religionId);
                                $('#religionId').trigger("chosen:updated");
                                $('#employmentStatusId').val(data.data.employmentStatusId);
                                $('#employmentStatusId').trigger("chosen:updated");
                                $('#employeeNumber').val(data.data.employeeNumber);
                                $('#employeeFirstName').val(data.data.employeeFirstName);
                                $('#employeeCompany').val(data.data.employeeCompany);
                                $('#employeeLastName').val(data.data.employeeLastName);
                                var x = data.data.employeeDateOfBirth;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#employeeDateOfBirth').val(output);
                                var x = data.data.employeeDateHired;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#employeeDateHired').val(output);
                                var x = data.data.employeeDateRetired;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
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
                        },
                        error: function(xhr) {
                            $('#infoError').empty();
                            $('#infoError').html('');
                            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass();
                            $('#infoErrorRowFluid').addClass('row');
                        }
                    });
                } else {
                    $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
                }
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("&nbsp;<img src='./images/icons/control-stop-180.png'> " + decodeURIComponent(t['endButtonLabel']) + " ");
            },
            error: function(xhr) {
                $('#infoError').empty();
                $('#infoError').html('');
                $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass();
                $('#infoErrorRowFluid').addClass('row');
            }
        });
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
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'read',
                    employeeId: $('#previousRecordCounter').val(),
                    output: 'json',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    // this is where we append a loading image
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success == true) {
                        $('#employeeId').val(data.data.employeeId);
                        $('#cityId').val(data.data.cityId);
                        $('#cityId').trigger("chosen:updated");
                        $('#stateId').val(data.data.stateId);
                        $('#stateId').trigger("chosen:updated");
                        $('#countryId').val(data.data.countryId);
                        $('#countryId').trigger("chosen:updated");
                        $('#jobId').val(data.data.jobId);
                        $('#jobId').trigger("chosen:updated");
                        $('#genderId').val(data.data.genderId);
                        $('#genderId').trigger("chosen:updated");
                        $('#marriageId').val(data.data.marriageId);
                        $('#marriageId').trigger("chosen:updated");
                        $('#raceId').val(data.data.raceId);
                        $('#raceId').trigger("chosen:updated");
                        $('#religionId').val(data.data.religionId);
                        $('#religionId').trigger("chosen:updated");
                        $('#employmentStatusId').val(data.data.employmentStatusId);
                        $('#employmentStatusId').trigger("chosen:updated");
                        $('#employeeNumber').val(data.data.employeeNumber);
                        $('#employeeFirstName').val(data.data.employeeFirstName);
                        $('#employeeCompany').val(data.data.employeeCompany);
                        $('#employeeLastName').val(data.data.employeeLastName);
                        var x = data.data.employeeDateOfBirth;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#employeeDateOfBirth').val(output);
                        var x = data.data.employeeDateHired;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#employeeDateHired').val(output);
                        var x = data.data.employeeDateRetired;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
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
                },
                error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                }
            });
        } else {
            // debugging purpose only
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
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'read',
                    employeeId: $('#nextRecordCounter').val(),
                    output: 'json',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success == true) {
                        $('#employeeId').val(data.data.employeeId);
                        $('#cityId').val(data.data.cityId);
                        $('#cityId').trigger("chosen:updated");
                        $('#stateId').val(data.data.stateId);
                        $('#stateId').trigger("chosen:updated");
                        $('#countryId').val(data.data.countryId);
                        $('#countryId').trigger("chosen:updated");
                        $('#jobId').val(data.data.jobId);
                        $('#jobId').trigger("chosen:updated");
                        $('#genderId').val(data.data.genderId);
                        $('#genderId').trigger("chosen:updated");
                        $('#marriageId').val(data.data.marriageId);
                        $('#marriageId').trigger("chosen:updated");
                        $('#raceId').val(data.data.raceId);
                        $('#raceId').trigger("chosen:updated");
                        $('#religionId').val(data.data.religionId);
                        $('#religionId').trigger("chosen:updated");
                        $('#employmentStatusId').val(data.data.employmentStatusId);
                        $('#employmentStatusId').trigger("chosen:updated");
                        $('#employeeNumber').val(data.data.employeeNumber);
                        $('#employeeFirstName').val(data.data.employeeFirstName);
                        $('#employeeCompany').val(data.data.employeeCompany);
                        $('#employeeLastName').val(data.data.employeeLastName);
                        var x = data.data.employeeDateOfBirth;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#employeeDateOfBirth').val(output);
                        var x = data.data.employeeDateHired;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#employeeDateHired').val(output);
                        var x = data.data.employeeDateRetired;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
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
                },
                error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                }
            });
        } else {
        }
    }
}

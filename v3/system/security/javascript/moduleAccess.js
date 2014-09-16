function filterForm(leafId, url, securityToken, offset, limit) {
    $.ajax({type: 'GET', url: url, data: {offset: offset, limit: limit, method: 'read', output: 'option', filter: 'moduleId', applicationId: $("#applicationId").val(), securityToken: securityToken, roleId: $("#roleId").val(), leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var success = data.success;
            var message = data.message;
            var $infoPanel = $('#infoPanel');
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            if (data.success === false) {
                $('#centerViewport').html('').empty().html("<span class=\"label label-success\"><img src='" + smileyRoll + "'>" + message + "</span>");
            } else {
                $('#moduleId').html('').empty().html(data.data).trigger("chosen:updated");
                $('#infoPanel').html('').empty().html("<span class=\"label label-success\"><img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }});
}
function filterGrid(leafId, url, securityToken, offset, limit) {
    $.ajax({type: 'POST', url: url, data: {offset: offset, limit: limit, method: 'read', output: 'table', securityToken: securityToken, leafId: leafId, applicationId: $("#applicationId").val(), moduleId: $("#moduleId").val(), roleId: $("#roleId").val()}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var success = data.success;
            var message = data.message;
            var $infoPanel = $('#infoPanel');
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            if (data.success === false) {
                $('#centerViewport').html('').empty().html("<span class=\"label label-success\"><img src='" + smileyRoll + "'>" + message + "</span>");
            } else {
                $('#tableBody').html('').empty().html(data.data);
                $('#infoPanel').html('').empty().html("<span class=\"label label-success\"><img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }});
}
function showGrid(leafId, page, securityToken, offset, limit, message) {
    if (typeof message === "undefined") {
        var message = decodeURIComponent(t['loadingCompleteTextLabel']);
    }
    $.ajax({type: 'POST', url: page, data: {offset: offset, limit: limit, method: 'read', type: 'list', detail: 'body', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var success = data.success;
            var message = data.message;
            var $infoPanel = $('#infoPanel');
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            if (data.success === false) {
                $('#centerViewport').html('').empty().html("<span class=\"label label-success\"><img src='" + smileyRoll + "'>" + message + "</span>");
            } else {
                $('#centerViewport').html('').empty().append(data);
                $('#infoPanel').html('').empty().html("<span class=\"label label-success\"><img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }});
}
function showForm(leafId, url, securityToken) {
    sleep(500);
    $.ajax({type: 'POST', url: url, data: {method: 'new', type: 'form', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var success = data.success;
            var message = data.message;
            var $infoPanel = $('#infoPanel');
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            if (data.success === false) {
                $('#centerViewport').html('').empty().html("<span class=\"label label-success\"><img src='" + smileyRoll + "'>" + message + "</span>");
            } else {
                $('#centerViewport').html('').empty().append(data);
                $infoPanel.html('').empty().html("<span class=\"label label-success\"><img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }});
}
function updateGridRecordCheckbox(leafId, url, urlList, securityToken) {
    var stringText = '';
    var counter = 0;
    $('input:checkbox[name="moduleAccessId[]"]').each(function() {
        stringText = stringText + "&moduleAccessId[]=" + $(this).val();
    });
    $('input:checkbox[name="moduleAccessValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&moduleAccessValue[]=true";
        } else {
            stringText = stringText + "&moduleAccessValue[]=false";
        }
        if ($(this).is(':checked')) {
            counter++;
        }
    });
    url = url + "?securityMayhem=chak" + stringText;
    $.ajax({type: 'GET', url: url, data: {method: 'update', output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var success = data.success;
            var message = data.message;
            var $infoPanel = $('#infoPanel');
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            if (data.success === true) {
                showGrid(leafId, urlList, securityToken, 0, 99999, t['updateRecordTextLabel']);
            } else if (data.success === false) {
                $infoPanel.html('<span class=\"label label-success\">' + message + "</span>");
            } else {
                $infoPanel.html('<span class=\"label label-success\">' + message + "</span>");
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }});
}
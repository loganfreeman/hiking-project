var cmsCommentLock = true;

function cmsCommentSubmit(that) {
    $(that).ajaxSubmit({
        url: $("#comments").data("url")+"/"+$(that).data("pk"),
        dataType: 'json',
        clearForm: true,
        resetForm: true,
        timeout: 5000,
        success: function(data, status, xhr) {
            if (!xhr.responseJSON) {
                $("#edit_comment").modal("hide");
                return;
            }
            if (xhr.responseJSON.success !== true || !xhr.responseJSON.msg || !xhr.responseJSON.comment_id || !xhr.responseJSON.comment_ver || !xhr.responseJSON.comment_text) {
                $("#edit_comment").modal("hide");
                return;
            }
            $("#comment_"+xhr.responseJSON.comment_id).data("ver", xhr.responseJSON.comment_ver);
            $("#main_comment_"+xhr.responseJSON.comment_id).fadeOut(cmsCommentTime/2, function() {
                $(this).html(xhr.responseJSON.comment_text);
                $(this).fadeIn(cmsCommentTime/2, function() {
                    $("#edit_comment").modal("hide");
                });
            });
        },
        error: function(xhr, status, error) {
            $("#edit_comment").modal("hide");
        }
    });
}

function cmsCommentEditShow(that) {
    $("#edit_commentform").data("pk", $(that).data('pk'));
    $("#verion").attr("value", $("#comment_"+$(that).data('pk')).data('ver'));
    $("#edit_body").val($("#main_comment_"+$(that).data('pk')).text().replace(/<br\s*[\/]?>/gi, "\n"));
    $("#edit_comment").modal("show");
}

function cmsCommentEdit(bindval) {
    bindval = bindval || ".editable";
    $(bindval).click(function() {
        var that = this;
        var cmsCommentEditCheck = setInterval(function() {
            if (cmsCommentLock == false) {
                clearInterval(cmsCommentEditCheck);
                cmsCommentLock = true;
                cmsCommentEditShow(that);
            }
        }, 10);
        return false;
    });
}

function cmsCommentModel() {
    $("#edit_comment").on("hidden.bs.modal", function () {
        cmsCommentLock = false;
    });
    $("#edit_comment_ok").click(function () {
        $("#edit_commentform").trigger("submit");
    });
    $("textarea#edit_body").keydown(function (e) {
        if (e.ctrlKey && e.keyCode === 13) {
            $("#edit_commentform").trigger("submit");
        }
    });
    $("#edit_commentform").submit(function() {
        var that = this;
        cmsCommentSubmit(that);
        return false;
    });
}

function cmsCommentDeleteSubmit(that) {
    $.ajax({
        url: $(that).attr("href"),
        type: "DELETE",
        dataType: "json",
        timeout: 5000,
        success: function(data, status, xhr) {
            if (!xhr.responseJSON) {
                cmsCommentLock = false;
                return;
            }
            if (xhr.responseJSON.success !== true || !xhr.responseJSON.msg || !xhr.responseJSON.comment_id) {
                cmsCommentLock = false;
                return;
            }
            $("#comment_"+xhr.responseJSON.comment_id).slideUp(cmsCommentTime, function() {
                $(this).remove();
                if ($("#comments > div").length == 0 && $("#comments > p").length == 0) {
                    $("<p id=\"nocomments\">There are currently no comments.</p>").prependTo("#comments").hide().fadeIn(cmsCommentTime, function() {
                        cmsCommentLock = false;
                    });
                } else {
                    cmsCommentLock = false;
                }
            });
        },
        error: function(xhr, status, error) {
            cmsCommentLock = false;
        }
    });
}

function cmsCommentDelete(bindval) {
    bindval = bindval || ".deletable";
    $(bindval).click(function() {
        var that = this;
        var cmsCommentDeleteCheck = setInterval(function() {
            if (cmsCommentLock == false) {
                clearInterval(cmsCommentDeleteCheck);
                cmsCommentLock = true;
                cmsCommentDeleteSubmit(that);
            }
        }, 10);
        return false;
    });
}

function cmsCommentMessage(message, type) {
    $("#commentstatus").replaceWith("<label id=\"commentstatus\" class=\"has-"+type+"\"><div class=\"editable-error-block help-block\" style=\"display: block;\">"+message+"</div></label>");
}

function cmsCommentCreateSubmit(that) {
    cmsCommentMessage("Submitting comment...", "info");
    $(that).ajaxSubmit({
        dataType: 'json',
        clearForm: true,
        resetForm: true,
        timeout: 5000,
        success: function(data, status, xhr) {
            if (!xhr.responseJSON) {
                cmsCommentMessage("There was an unknown error!", "error");
                cmsCommentLock = false;
                return;
            }
            if (xhr.responseJSON.success !== true || !xhr.responseJSON.msg || !xhr.responseJSON.contents || !xhr.responseJSON.comment_id) {
                if (!xhr.responseJSON.msg) {
                    cmsCommentMessage("There was an unknown error!", "error");
                    cmsCommentLock = false;
                    return;
                }
                cmsCommentMessage(xhr.responseJSON.msg, "error");
                cmsCommentLock = false;
                return;
            }
            cmsCommentMessage(xhr.responseJSON.msg, "success");
            if ($("#comments > div").length == 0) {
                $("#nocomments").fadeOut(cmsCommentTime, function() {
                    $(this).remove();
                    $(xhr.responseJSON.contents).prependTo('#comments').hide().slideDown(cmsCommentTime, function() {
                        cmsTimeAgo("#timeago_comment_"+xhr.responseJSON.comment_id);
                        cmsCommentEdit("#editable_comment_"+xhr.responseJSON.comment_id+"_1");
                        cmsCommentEdit("#editable_comment_"+xhr.responseJSON.comment_id+"_2");
                        cmsCommentDelete("#deletable_comment_"+xhr.responseJSON.comment_id+"_1");
                        cmsCommentDelete("#deletable_comment_"+xhr.responseJSON.comment_id+"_2");
                        cmsCommentLock = false;
                    });
                });
            } else {
                $(xhr.responseJSON.contents).prependTo('#comments').hide().slideDown(cmsCommentTime, function() {
                    cmsTimeAgo("#timeago_comment_"+xhr.responseJSON.comment_id);
                    cmsCommentEdit("#editable_comment_"+xhr.responseJSON.comment_id+"_1");
                    cmsCommentEdit("#editable_comment_"+xhr.responseJSON.comment_id+"_2");
                    cmsCommentDelete("#deletable_comment_"+xhr.responseJSON.comment_id+"_1");
                    cmsCommentDelete("#deletable_comment_"+xhr.responseJSON.comment_id+"_2");
                    cmsCommentLock = false;
                });
            }
        },
        error: function(xhr, status, error) {
            if (!xhr.responseJSON || !xhr.responseJSON.msg) {
                cmsCommentMessage("There was an unknown error!", "error");
                cmsCommentLock = false;
                return;
            }
            cmsCommentMessage(xhr.responseJSON.msg, "error");
            cmsCommentLock = false;
        }
    });
}

function cmsCommentCreate(bindval, body) {
    bindval = bindval || "#commentform";
    body = body || "textarea#body";
    $(bindval).submit(function() {
        cmsCommentMessage("Waiting for lock to clear...", "info");
        var that = this;
        var cmsCommentCreateCheck = setInterval(function() {
            if (cmsCommentLock == false) {
                cmsCommentLock = true;
                clearInterval(cmsCommentCreateCheck);
                cmsCommentCreateSubmit(that);
            }
        }, 10);
        return false;
    });
    $(body).keydown(function (e) {
        if (e.ctrlKey && e.keyCode === 13) {
            $(bindval).trigger("submit");
        }
    });
}

var cmsCommentFetchRaw;
var cmsCommentFetchData;

function cmsCommentFetchGet() {
    if (cmsCommentFetchData.length != 0) {
        $.ajax({
            url: $("#comments").data("url")+"/"+cmsCommentFetchData[0],
            type: "GET",
            dataType: "json",
            timeout: 5000,
            success: function(data, status, xhr) {
                if (!xhr.responseJSON) {
                    cmsCommentFetchData.splice(0, 1);
                    cmsCommentFetchGet();
                    return;
                }
                if (!xhr.responseJSON.contents || !xhr.responseJSON.comment_id) {
                    cmsCommentFetchData.splice(0, 1);
                    cmsCommentFetchGet();
                    return;
                }
                if ($("#comments > div").length == 0) {
                    $("#nocomments").fadeOut(cmsCommentTime, function() {
                        $(this).remove();
                        $(xhr.responseJSON.contents).prependTo('#comments').hide().slideDown(cmsCommentTime, function() {
                            cmsTimeAgo("#timeago_comment_"+xhr.responseJSON.comment_id);
                            cmsCommentEdit("#editable_comment_"+xhr.responseJSON.comment_id+"_1");
                            cmsCommentEdit("#editable_comment_"+xhr.responseJSON.comment_id+"_2");
                            cmsCommentDelete("#deletable_comment_"+xhr.responseJSON.comment_id+"_1");
                            cmsCommentDelete("#deletable_comment_"+xhr.responseJSON.comment_id+"_2");
                            cmsCommentFetchData.splice(0, 1);
                            cmsCommentFetchGet();
                        });
                    });
                } else {
                    $(xhr.responseJSON.contents).prependTo('#comments').hide().slideDown(cmsCommentTime, function() {
                        cmsTimeAgo("#timeago_comment_"+xhr.responseJSON.comment_id);
                        cmsCommentEdit("#editable_comment_"+xhr.responseJSON.comment_id+"_1");
                        cmsCommentEdit("#editable_comment_"+xhr.responseJSON.comment_id+"_2");
                        cmsCommentDelete("#deletable_comment_"+xhr.responseJSON.comment_id+"_1");
                        cmsCommentDelete("#deletable_comment_"+xhr.responseJSON.comment_id+"_2");
                        cmsCommentFetchData.splice(0, 1);
                        cmsCommentFetchGet();
                    });
                }
            },
            error: function(xhr, status, error) {
                cmsCommentFetchData.splice(0, 1);
                cmsCommentFetchGet();
            }
        });
        return;
    }

    if ($("#comments > div").length == 0 && $("#comments > p").length == 0) {
        $("<p id=\"nocomments\">There are currently no comments.</p>").prependTo("#comments").hide().fadeIn(cmsCommentTime, function(){
            cmsCommentLock = false;
            cmsCommentFetch();
        });
    } else {
        cmsCommentLock = false;
        cmsCommentFetch();
    }
}

function cmsCommentFetchNew() {
    var length = cmsCommentFetchRaw.length;
    cmsCommentFetchData = new Array();

    for (var i = 0; i < length; i++) {
        var ok = false;
        $("#comments > div").each(function() {
            if ($(this).data('pk') == cmsCommentFetchRaw[i].comment_id) {
                ok = true;
            }
        });

        if (ok == false) {
            cmsCommentFetchData.push(cmsCommentFetchRaw[i].comment_id);
        }
    }

    cmsCommentFetchGet();
}

function cmsCommentFetchReplace() {
    if (cmsCommentFetchData.length != 0) {
        $.ajax({
            url: $("#comments").data("url")+"/"+cmsCommentFetchData[0],
            type: "GET",
            dataType: "json",
            timeout: 5000,
            success: function(data, status, xhr) {
                if (!xhr.responseJSON) {
                    cmsCommentFetchData.splice(0, 1);
                    cmsCommentFetchReplace();
                    return;
                }
                if (!xhr.responseJSON.comment_id || !xhr.responseJSON.comment_ver || !xhr.responseJSON.comment_text) {
                    cmsCommentFetchData.splice(0, 1);
                    cmsCommentFetchReplace();
                    return;
                }
                $("#comment_"+xhr.responseJSON.comment_id).data("ver", xhr.responseJSON.comment_ver);
                $("#main_comment_"+xhr.responseJSON.comment_id).fadeOut(cmsCommentTime/2, function() {
                    $(this).html(xhr.responseJSON.comment_text);
                    $(this).fadeIn(cmsCommentTime/2, function() {
                        cmsCommentFetchData.splice(0, 1);
                        cmsCommentFetchReplace();
                    });
                });
            },
            error: function(xhr, status, error) {
                cmsCommentFetchData.splice(0, 1);
                cmsCommentFetchReplace();
            }
        });
        return;
    }

    cmsCommentFetchNew();
}

function cmsCommentFetchUpdate() {
    var length = cmsCommentFetchRaw.length;
    cmsCommentFetchData = new Array();

    for (var i = 0; i < length; i++) {
        $("#comments > div").each(function() {
            if ($(this).data('pk') == cmsCommentFetchRaw[i].comment_id) {
                if ($(this).data('ver') != cmsCommentFetchRaw[i].comment_ver) {
                    cmsCommentFetchData.push(cmsCommentFetchRaw[i].comment_id);
                }
            }
        });
    }

    cmsCommentFetchReplace();
}

function cmsCommentFetchProcess() {
    var length = cmsCommentFetchRaw.length;
    var num = 0;
    var done = 0;

    $("#comments > div").each(function() {
        var ok = false;
        for (var i = 0; i < length; i++) {
            if ($(this).data('pk') == cmsCommentFetchRaw[i].comment_id) {
                ok = true;
            }
        }
        if (ok == false) {
            num++;
            $(this).slideUp(cmsCommentTime, function() {
                $(this).remove();
                done++;
            });
        }
    });

    var cmsCommentNewCheck = setInterval(function() {
        if (num === done) {
            clearInterval(cmsCommentNewCheck);
            cmsCommentFetchUpdate();
        }
    }, 10);
}

function cmsCommentFetchWork() {
    $.ajax({
        url: $("#comments").data('url'),
        type: "GET",
        dataType: "json",
        timeout: 5000,
        success: function(data, status, xhr) {
            if (!xhr.responseJSON) {
                cmsCommentLock = false;
                cmsCommentFetch();
                return;
            }
            cmsCommentFetchRaw = xhr.responseJSON;
            cmsCommentFetchProcess();
        },
        error: function(xhr, status, error) {
            if (!xhr.responseJSON) {
                cmsCommentLock = false;
                cmsCommentFetch();
                return;
            }
            if (xhr.responseJSON.url && xhr.responseJSON.code == 404) {
                $("body").fadeOut(1000, function() {
                    window.location.replace(xhr.responseJSON.url);
                });
                return;
            }
            cmsCommentLock = false;
            cmsCommentFetch();
        }
    });
}

function cmsCommentFetchWait() {
    var cmsCommentFetchCheck = setInterval(function() {
        if (cmsCommentLock == false) {
            cmsCommentLock = true;
            clearInterval(cmsCommentFetchCheck);
            cmsCommentFetchWork()
        }
    }, 10);
    return false;
}

function cmsCommentFetch() {
    setTimeout(function() {
        cmsCommentFetchWait()
    }, cmsCommentInterval);
}

$(document).ready(function() {
    cmsCommentEdit();
    cmsCommentModel();
    cmsCommentDelete();
    cmsCommentCreate();
    cmsCommentFetch();
    cmsCommentLock = false;
});

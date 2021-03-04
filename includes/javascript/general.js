var jqxhr = {abort: function () {}};
$(function() {
    function reloadView(url = '/'){
        if(window.location.pathname ==  url){
            console.log('same');
            return;
        }
        jqxhr.abort();
        jqxhr = $.ajax({
            async: true,
            url:'/',
            dataType: "html",
            contentType: "application/json; charset=utf-8",
        })
        .done((res) => {$("body").html(res);console.log(res.replace(/<!DOCTYPE html><html lang=\'nl\'>/,""));});
        history.pushState(null, '', url);
    }

    $("a").click(function(e){
        e.preventDefault();
        $("body").empty();
        reloadView(e.target.href);
    });
});

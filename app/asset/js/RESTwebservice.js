
var request = {
    ajax : function(method, url, data, action, $this) {
        $.ajax({
            method: method,
            url: url,
            data: data,
            success: function(data) {
                request.appendData(data)
            },
            error: function() {
            }
        });
    },

    appendData : function(data) {
        $('.trResponse').remove();

        if (typeof data === 'object') {
            if (!jQuery.isEmptyObject(data)) {
                for (var mainKey in data) {
                    if (typeof data[mainKey] === 'object') {
                        $('.responseBody').append("<tr class='trResponse" + mainKey + " trResponse'>");

                        for (var secKey in data[mainKey]) {
                            $('.trResponse' + mainKey).append("<td></td><td>" + secKey + " : " + data[mainKey][secKey] + "</td>");
                        }

                        $('.responseBody').append("</tr>");
                    } else {
                        $('.responseBody').append("<tr class='trResponse'><td></td><td>" + mainKey + " : " + data[mainKey] + "</td>");
                    }
                }
            } else {
                $('.responseBody').append("<tr class='trResponse'><td></td><td> no data </td>");
            }
        } else {
            data = JSON.parse(data);

            for (var mainKey in data) {
                $('.responseBody').append("<tr class='trResponse'><td></td><td>" + mainKey + " : " + data[mainKey] + "</td>");
            }
        }
    }
};

(function(){
    $('#send').click(function(){
        var method = $("#methods option:selected").val();
        var url = $("#url").val();
        var data = {};

        $(".trParams").each(function(index) {
            if ($('.keyParam' + index).val() && $('.valueParam' + index).val()) {
                eval("data." + $('.keyParam' + index).val() + "= '" + $('.valueParam' + index).val() + "'");
            }
        });

        request.ajax(method, url, data);
    });

    var i=0;
    $('span.plus').click(function(){

        $('#paramBody').append("<tr class='trParams'><td><span id=\"moins"+ (i + 1) +"\" class=\"ic \">-</span></td><td><input type=\"text\" placeholder=\"Nom\" class=\"keyParam"+(i + 1)+"\"></td><td><input type=\"text\" placeholder=\"Valeur\" class=\"valueParam"+(i + 1)+"\"></td></tr>")
        $('span#moins'+ (i + 1)).click(function(){
            $(this).parents('.trParams').remove();
            i--;
        });
        i++;
    });
})(jQuery);


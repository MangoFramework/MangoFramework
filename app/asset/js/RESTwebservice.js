
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
        $('.responseBody').empty();

        if (typeof data === 'object') {
            if (!jQuery.isEmptyObject(data)) {
                // print column title
                $('.responseBody').append("<tr class='trResponse trResponseKey'>");

                for (var mainKey in data) {
                    if (typeof data[mainKey] === 'object' && data[mainKey] != null) {
                        for (var secKey in data[mainKey]) {
                            if (mainKey == 0) {
                                $('.trResponseKey').append("<th>" + secKey + "</th>");
                            }
                        }
                    } else {
                        $('.trResponseKey').append("<th>" + mainKey + "</th>");
                    }
                }

                $('.responseBody').append("</tr>");

                // print value
                if (data[0] && typeof data[0] === 'object') {
                    for (var mainKey in data) {
                        $('.responseBody').append("<tr class='trResponse trResponseValue" + mainKey + " trResponseValue'>");

                        for (var secKey in data[mainKey]) {
                            $('.trResponseValue' + mainKey).append("<td>" + data[mainKey][secKey] + "</td>");
                        }

                        $('.responseBody').append("</tr>");
                    }
                } else {
                    $('.responseBody').append("<tr class='trResponse trResponseValue'>");

                    for (var mainKey in data) {
                        $('.trResponseValue').append("<td>" + data[mainKey] + "</td>");
                    }

                    $('.responseBody').append("</tr>");
                }
            } else {
                $('.responseBody').append("<tr class='trResponse'><td>no data</td></tr>");
            }
        } else {
            data = JSON.parse(data);

            for (var mainKey in data) {
                $('.responseBody').append("<tr class='trResponse'><td>" + mainKey + " : " + data[mainKey] + "</td></tr>");
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


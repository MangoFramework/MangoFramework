
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
        $('.trResponse').empty();
        for (var i in data) {

            if (typeof data[i] === 'object') {
                $('.responseBody').append("<tr class='trResponse" + i + " trResponse'>");

                for (var j in data[i]) {
                    $('.trResponse' + i).append("<td></td><td>" + j + " : " + data[i][j] + "</td>");
                }

                $('.responseBody').append("</tr>");
            } else {
                $('.responseBody').append("<tr class='trResponse'><td></td><td>" + i + " : " + data[i] + "</td>");
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

        $('span#moins'+ i).click(function(){
            $(this).parents('tr').remove();
        });
        i++;
    });
})(jQuery);


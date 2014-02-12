
var request = {
    ajax : function(method, url, data, action, $this) {
        $.ajax({
            method: method,
            url: url,
            data: data,
            success: function(data) {
                ajax.appendData(data)
            },
            error: function() {
            }
        });
    },

    appendData : function(data) {
        for (var i in data) {
            $('#tdResponse').append(data[i] + "<br>");
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
        console.log(data);
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


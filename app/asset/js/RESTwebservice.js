
var request = {
    ajax : function(method, url, data, action, $this) {
        $.ajax({
            method: method,
            url: url,
            data: data,
            success: function(response) {
                console.log(response);
            },
            error: function() {
            }
        });
    }
};

(function(){
    $('#send').click(function(){
        var method;
        var url;



        request.ajax('GET','http://localhost/MangoFrameworkSkelet/app/user');
    });

    var i=0;
    $('span.plus').click(function(){

        $('#paramBody').append("<tr><td><span id=\"moins"+ i +"\" class=\"ic \">-</span></td><td><input type=\"text\" placeholder=\"Nom\" class=\"param"+i+"\"></td><td><input type=\"text\" placeholder=\"Valeur\" class=\"param"+i+"\"></td></tr>")

        $('span#moins'+ i).click(function(){
            $(this).parents('tr').remove();
        });
        i++;
    });
})(jQuery);


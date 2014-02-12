
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

    $('span.plus').click(function(){

        $('#paramBody').append("<tr><td><span class=\"ic moins\">-</span></td><td><input type=\"text\" placeholder=\"Nom\" class=\"couple1\"></td><td><input type=\"text\" placeholder=\"Valeur\" class=\"couple1\"></td></tr>")

        $('span.moins').click(function(){
            $('span.moins').parent().parent().remove();
        });
    });
})(jQuery);


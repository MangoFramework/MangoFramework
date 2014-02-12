
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
})(jQuery);


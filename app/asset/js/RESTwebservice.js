xmlToJson = function(xml) {
    var obj = {};
    if (xml.nodeType == 1) {
        if (xml.attributes.length > 0) {
            obj["@attributes"] = {};
            for (var j = 0; j < xml.attributes.length; j++) {
                var attribute = xml.attributes.item(j);
                obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
            }
        }
    } else if (xml.nodeType == 3) {
        obj = xml.nodeValue;
    }
    if (xml.hasChildNodes()) {
        for (var i = 0; i < xml.childNodes.length; i++) {
            var item = xml.childNodes.item(i);
            var nodeName = item.nodeName;
            if (typeof (obj[nodeName]) == "undefined") {
                obj[nodeName] = xmlToJson(item);
            } else {
                if (typeof (obj[nodeName].push) == "undefined") {
                    var old = obj[nodeName];
                    obj[nodeName] = [];
                    obj[nodeName].push(old);
                }
                obj[nodeName].push(xmlToJson(item));
            }
        }
    }
    return obj;
}

var request = {
    ajax : function(method, url, data, action, $this) {
        $.ajax({
            method: method,
            url: url,
            data: data,
            success: function(data) {
                request.appendData(data);
            },
            error: function() {
            }
        });
    },

    appendData : function(data) {
        $('.responseBody').empty();

        if ('/^<\?xml/i'.match(data)) {
            data = $.xml2json(data);
        }

        if (typeof data !== 'object') {
            data = JSON.parse(data);
        }

        if (!jQuery.isEmptyObject(data)) {
            // print column title
            $('.responseBody').append("<tr class='trResponse trResponseKey'>");

            for (var mainKey in data) {
                if (typeof data[mainKey] === 'object' && data[mainKey] != null) {
                    for (var secKey in data[mainKey]) {
                        if (mainKey == 0 || mainKey == 'item0') {
                            $('.trResponseKey').append("<th>" + secKey + "</th>");
                        }
                    }
                } else {
                    $('.trResponseKey').append("<th>" + mainKey + "</th>");
                }
            }

            $('.responseBody').append("</tr>");

            // print value
            if ((data[0] && typeof data[0] === 'object') ||
                (data['item0'] && typeof data['item0'] === 'object')) {
                for (var mainKey in data) {
                    convertKey = ((mainKey.match(/^[0-9]{1,}/gi)) ? mainKey : mainKey.substr(4));

                    $('.responseBody').append("<tr class='trResponse trResponseValue" + convertKey + " trResponseValue'>");

                    for (var secKey in data[mainKey]) {
                        $('.trResponseValue' + convertKey).append("<td>" + (data[mainKey][secKey] != '' || typeof data[mainKey][secKey] === 'boolean' ? data[mainKey][secKey] : '<span class="noData">empty</span>') + "</td>");
                    }

                    $('.responseBody').append("</tr>");
                }
            } else {
                $('.responseBody').append("<tr class='trResponse trResponseValue'>");

                for (var mainKey in data) {
                    $('.trResponseValue').append("<td>" + (data[mainKey] != '' || typeof data[mainKey] === 'boolean' ? data[mainKey] : '<span class="noData">empty</span>') + "</td>");
                }

                $('.responseBody').append("</tr>");
            }
        } else {
            $('.responseBody').append("<tr class='trResponse'><td>no data</td></tr>");
        }
    }
};

(function(){
    $('#send').on('click', function(){
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


    $(document).keydown(function(e) {
        if (e.keyCode === 13) {
            $('#send').click();
        }
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


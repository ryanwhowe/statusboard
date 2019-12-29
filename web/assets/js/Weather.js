/**
 *
 *
 */

$.widget("howe.WeatherInfo",{
    url: 'api/weather',
    options: {
        update_interval: 60*60*1000, /* update the weather every hour */
    },

    _create: function(){
        let me = this,
            e = this.element;
        me.my_name = this.eventNamespace.replace('.', '');
        me.data_response = null;
        me._updateData();
    },

    _updateData: function(){
        let me = this;
        $.ajax({
            url: me.url,
            method: 'GET',
            dataType: 'json',
            async: true,
            cache: false,
            timeout: 5*1000,
            success: function(data, status) {
                if(status === 'timeout'){
                    me.__Error('Ajax Request Timeout');
                }
                me.data_response = data;
                me.__renderWidget();
            },
            error: function(xhr, status) {
                if(status === 'timeout'){
                    me.__Error('Ajax Request Timeout');
                }
                if(status === 'error'){
                    me.__Error('Internal Server Error');
                }
                let error = JSON.parse(xhr.responseText);
                let error_text = 'Invalid Response';
                $.each(error.error, function(index, value){
                    if(typeof(value) !== 'object'){
                        error_text += value + '<br>';
                    } else {
                        if (typeof value.args[1] !== 'undefined'){
                            error_text += value.args[1] + '<br>';
                        }
                    }
                });
                me.__Error(error_text);
            }
        });
    },

    __renderWidget: function(){
        let me = this;
        let $e = $(this.element);

        me.widget = $([
            "<div class='col-lg-12 col-md-12 col-sm-12 text-center'>" + me.data_response[0]['headline'] + "</div>",
            "<div class='col-lg-3 col-md-6 col-sm-12'>",
            "<div class='panel panel-info'>",
            "<div class='panel-heading text-center border-bottom-primary '>Today</div>",
            "<div class='panel-body text-center'>",
            "High: " + me.data_response[0]['hightemp'] + " &deg;<br>",
            "Low: " + me.data_response[0]['lowtemp'] + " &deg;<br>",
            "<img src='" + me.data_response[0]['icons']['day'] + "' alt='" + me.data_response[0]['icontext']['day'] + "'><br>",
            me.data_response[0]['icontext']['day'] + "<br>",
            "<img src='" + me.data_response[0]['icons']['night'] + "' alt='" + me.data_response[0]['icontext']['night'] + "'><br>",
            me.data_response[0]['icontext']['night'],
            "</div>",
            "</div>",
            "</div>",
            "<div class='col-lg-3 col-md-6 col-sm-12'>",
            "<div class='panel panel-info'>",
            "<div class='panel-heading text-center'>" + me.data_response[1]['day'] + "</div>",
            "<div class='panel-body text-center'>",
            "High: " + me.data_response[1]['hightemp'] + " &deg;<br>",
            "Low: " + me.data_response[1]['lowtemp'] + " &deg;<br>",
            "<img src='" + me.data_response[1]['icons']['day'] + "' alt='" + me.data_response[1]['icontext']['day'] + "'><br>",
            me.data_response[1]['icontext']['day'] + "<br>",
            "<img src='" + me.data_response[1]['icons']['night'] + "' alt='" + me.data_response[1]['icontext']['night'] + "'><br>",
            me.data_response[1]['icontext']['night'],
            "</div>",
            "</div>",
            "</div>",
            "<div class='col-lg-3 col-md-6 col-sm-12'>",
            "<div class='panel panel-info'>",
            "<div class='panel-heading text-center'>" + me.data_response[2]['day'] + "</div>",
            "<div class='panel-body text-center'>",
            "High: " + me.data_response[2]['hightemp'] + " &deg;<br>",
            "Low: " + me.data_response[2]['lowtemp'] + " &deg;<br>",
            "<img src='" + me.data_response[2]['icons']['day'] + "' alt='" + me.data_response[2]['icontext']['day'] + "'><br>",
            me.data_response[2]['icontext']['day'] + "<br>",
            "<img src='" + me.data_response[2]['icons']['night'] + "' alt='" + me.data_response[2]['icontext']['night'] + "'><br>",
            me.data_response[2]['icontext']['night'],
            "</div>",
            "</div>",
            "</div>",
            "<div class='col-lg-3 col-md-6 col-sm-12'>",
            "<div class='panel panel-info'>",
            "<div class='panel-heading text-center'>" + me.data_response[3]['day'] + "</div>",
            "<div class='panel-body text-center'>",
            "High: " + me.data_response[3]['hightemp'] + " &deg;<br>",
            "Low: " + me.data_response[3]['lowtemp'] + " &deg;<br>",
            "<img src='" + me.data_response[3]['icons']['day'] + "' alt='" + me.data_response[3]['icontext']['day'] + "'><br>",
            me.data_response[3]['icontext']['day'] + "<br>",
            "<img src='" + me.data_response[3]['icons']['night'] + "' alt='" + me.data_response[3]['icontext']['night'] + "'><br>",
            me.data_response[3]['icontext']['night'],
            "</div>",
            "</div>",
            "</div>",

        ].join("\n"));
        $e.replaceWith(me.widget);

        me.update_interval = setInterval(function() {
            me._updateData();
        }, me.options.update_interval);
    },

    /**
     * Error handling method for the widget
     *@private
     */
    __Error: function (message) {
        let me = this;
        let error_message = me.widgetFullName + ':' + message;
        let e = this.element;
        $(e).replaceWith('<div class="alert alert-danger"><strong>ALERT: </strong>' + error_message + '</div>');
        clearInterval(me.update_interval);
    },
});
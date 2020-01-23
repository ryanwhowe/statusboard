/**
 *
 *
 */

$.widget("howe.WeatherInfo",{
    url: 'api/weather',
    options: {
        update_interval: 10*60*1000, /* update the weather every 10 minutes unless there is an expires flag present */
    },

    /**
     * The widget create script, this is fired on widget creation
     * 
     * @private
     */
    _create: function(){
        let me = this,
            e = this.element;
        me.my_name = this.eventNamespace.replace('.', '');
        me.data_response = null;
        me._updateData();
    },

    /**
     * Update the widget's data from the api, then call the render method
     * 
     * @private
     */
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

    /**
     * Render the widget's html and css
     * 
     * @private
     */
    __renderWidget: function(){
        let me = this;
        let $e = $(this.element);

        me.widget = $([
            "<div class='row'>",
            "<div class='col-lg-3 col-md-3 col-sm-12'><div class='panel panel-warning'><div class='panel-heading text-center'>",
            me.data_response['current']['condition'] + "<br>Currently: " + me.data_response['current']['temp'] + " &deg;F<br>",
            "<a target='_blank' href='" + me.data_response['current']['link'] + "'><img src='" + me.data_response['current']['icon'] + "' alt='" + me.data_response['current']['condition'] + "'></a><br>",
            "</div></div></div>",
            "<div class='col-lg-9 col-md-9 col-sm-12' title='updated: " + me.__convertTimestamp(Math.floor(new Date().getTime()/1000.0)) + ", expires: " + me.__convertTimestamp(me.data_response['expires']) + "'><div class='panel panel-success'><div class='panel-heading text-center'>" + me.data_response['headline'] + "</div></div></div>",
            "</div>",
            "<div class='row'>",
            "<div class='col-lg-3 col-md-6 col-sm-6'>",
            "<div class='panel panel-warning'>",
            "<div class='panel-heading text-center'>Today</div>",
            "<div class='panel-body text-center'>",
            "High: " + me.data_response[0]['hightemp'] + " &deg;F<br>",
            "Low: " + me.data_response[0]['lowtemp'] + " &deg;F<br>",
            "<img src='" + me.data_response[0]['icons']['day'] + "' alt='" + me.data_response[0]['icontext']['day'] + "'><br>",
            me.data_response[0]['icontext']['day'] + "<br>",
            "<img src='" + me.data_response[0]['icons']['night'] + "' alt='" + me.data_response[0]['icontext']['night'] + "'><br>",
            me.data_response[0]['icontext']['night'],
            "</div>",
            "</div>",
            "</div>",
            "<div class='col-lg-3 col-md-6 col-sm-6'>",
            "<div class='panel panel-info'>",
            "<div class='panel-heading text-center'>" + me.data_response[1]['day'] + "</div>",
            "<div class='panel-body text-center'>",
            "High: " + me.data_response[1]['hightemp'] + " &deg;F<br>",
            "Low: " + me.data_response[1]['lowtemp'] + " &deg;F<br>",
            "<img src='" + me.data_response[1]['icons']['day'] + "' alt='" + me.data_response[1]['icontext']['day'] + "'><br>",
            me.data_response[1]['icontext']['day'] + "<br>",
            "<img src='" + me.data_response[1]['icons']['night'] + "' alt='" + me.data_response[1]['icontext']['night'] + "'><br>",
            me.data_response[1]['icontext']['night'],
            "</div>",
            "</div>",
            "</div>",
            "<div class='col-lg-3 col-md-6 col-sm-6'>",
            "<div class='panel panel-info'>",
            "<div class='panel-heading text-center'>" + me.data_response[2]['day'] + "</div>",
            "<div class='panel-body text-center'>",
            "High: " + me.data_response[2]['hightemp'] + " &deg;F<br>",
            "Low: " + me.data_response[2]['lowtemp'] + " &deg;F<br>",
            "<img src='" + me.data_response[2]['icons']['day'] + "' alt='" + me.data_response[2]['icontext']['day'] + "'><br>",
            me.data_response[2]['icontext']['day'] + "<br>",
            "<img src='" + me.data_response[2]['icons']['night'] + "' alt='" + me.data_response[2]['icontext']['night'] + "'><br>",
            me.data_response[2]['icontext']['night'],
            "</div>",
            "</div>",
            "</div>",
            "<div class='col-lg-3 col-md-6 col-sm-6'>",
            "<div class='panel panel-info'>",
            "<div class='panel-heading text-center'>" + me.data_response[3]['day'] + "</div>",
            "<div class='panel-body text-center'>",
            "High: " + me.data_response[3]['hightemp'] + " &deg;F<br>",
            "Low: " + me.data_response[3]['lowtemp'] + " &deg;F<br>",
            "<img src='" + me.data_response[3]['icons']['day'] + "' alt='" + me.data_response[3]['icontext']['day'] + "'><br>",
            me.data_response[3]['icontext']['day'] + "<br>",
            "<img src='" + me.data_response[3]['icons']['night'] + "' alt='" + me.data_response[3]['icontext']['night'] + "'><br>",
            me.data_response[3]['icontext']['night'],
            "</div>",
            "</div>",
            "</div>",
            "</div>",
            "<div class='row text-center small'>powered by Accuweather, Inc.&reg;</div>"
        ].join("\n"));
        $e.replaceWith(me.widget);

        let update_interval = me.__getUpdateInterval();

        me.update_interval = setInterval(function() {
            me._updateData();
        }, update_interval);
    },

    /**
     * return the update interval to use for the api based off the data expiration time returned in the response
     * 
     * @returns {number} update interval to use in microseconds
     * @private
     */
    __getUpdateInterval: function(){
        let me = this;
        let expires_epoch = me.data_response['expires'];
        let current_epoch = Math.floor(new Date().getTime()/1000.0);
        let delta_in_seconds = expires_epoch - current_epoch;
        if(delta_in_seconds >= 0) return (delta_in_seconds + 60) * 1000; /* add a minute to the response and convert to microseconds */
        return me.options.update_interval;
    },

    /**
     * Error handling method for the widget
     * @private
     */
    __Error: function (message) {
        let me = this;
        let error_message = me.widgetFullName + ':' + message;
        let e = this.element;
        $(e).replaceWith('<div class="alert alert-danger"><strong>ALERT: </strong>' + error_message + '</div>');
        clearInterval(me.update_interval);
    },

    /**
     * This is an internal helper function to format the timestamp return values into actual local times
     *
     * @param timestamp
     * @returns {string|*}
     * @private
     */
    __convertTimestamp: function (timestamp) {
        let d = new Date(timestamp * 1000),	// Convert the passed timestamp to milliseconds
            yyyy = d.getFullYear(),
            mm = ('0' + (d.getMonth() + 1)).slice(-2),	// Months are zero based. Add leading 0.
            dd = ('0' + d.getDate()).slice(-2),			// Add leading 0.
            hh = d.getHours(),
            h = hh,
            min = ('0' + d.getMinutes()).slice(-2),		// Add leading 0.
            ampm = 'AM',
            time;

        if (hh > 12) {
            h = hh - 12;
            ampm = 'PM';
        } else if (hh === 12) {
            h = 12;
            ampm = 'PM';
        } else if (hh === 0) {
            h = 12;
        }

        // ie: 2013-02-18, 8:35 AM
        time = yyyy + '-' + mm + '-' + dd + ', ' + h + ':' + min + ' ' + ampm;

        return time;
    },
});
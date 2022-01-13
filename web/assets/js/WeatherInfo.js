/**
 * This is a helper function to format the timestamp return values into actual local times
 *
 * @param timestamp
 * @returns {string|*}
 * @private
 */
function convertTimestamp(timestamp) {
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
}

/**
 * This is a helper function to format the weather data response for the widgets
 *
 */
function renderWeatherWidget(data) {
    let expires = new Date(data['expires']);
    return $([
        "<div class='row'>",
        "<div class='col-lg-3 col-md-3 col-sm-12'><div class='panel panel-warning'><div class='panel-heading text-center'>",
        data['current']['condition'] + "<br>Currently: " + data['current']['temp'] + " &deg;F<br>",
        "<a target='_blank' href='" + data['current']['link'] + "'>",
        "<i class='wi " + data['current']['weather-icon'] + "' style='color:#401718; font-size:2em; padding-top: 8px;'></i></a><br>",
        "</div></div></div>",
        "<div class='col-lg-9 col-md-9 col-sm-12' title='updated: " + convertTimestamp(Math.floor(new Date().getTime()/1000.0)) + ", expires: " + convertTimestamp(expires.getTime()/1000) + "'><div class='panel panel-success'><div class='panel-heading text-center'>" + data['headline'] + "</div></div></div>",
        "</div>",
        "<div class='row'>",
        "<div class='col-lg-3 col-md-6 col-sm-6'>",
        "<div class='panel panel-warning'>",
        "<div class='panel-heading text-center'>Today</div>",
        "<div class='panel-body text-center'>",
        "High: " + data[0]['hightemp'] + " &deg;F<br>",
        "Low: " + data[0]['lowtemp'] + " &deg;F<br>",
        "<i class='wi " + data[0]['weather-icons']['day'] + "' style='color:#401718; font-size:2em; padding-top: 8px;'></i><br>",
        data[0]['icontext']['day'] + "<br>",
        "<i class='wi " + data[0]['weather-icons']['night'] + "' style='color:#401718; font-size:2em; padding-top: 8px;'></i><br>",
        data[0]['icontext']['night'],
        "</div>",
        "</div>",
        "</div>",
        "<div class='col-lg-3 col-md-6 col-sm-6'>",
        "<div class='panel panel-info'>",
        "<div class='panel-heading text-center'>" + data[1]['day'] + "</div>",
        "<div class='panel-body text-center'>",
        "High: " + data[1]['hightemp'] + " &deg;F<br>",
        "Low: " + data[1]['lowtemp'] + " &deg;F<br>",
        "<i class='wi " + data[1]['weather-icons']['day'] + "' style='color:#401718; font-size:2em; padding-top: 8px;'></i><br>",
        data[1]['icontext']['day'] + "<br>",
        "<i class='wi " + data[1]['weather-icons']['night'] + "' style='color:#401718; font-size:2em; padding-top: 8px;'></i><br>",
        data[1]['icontext']['night'],
        "</div>",
        "</div>",
        "</div>",
        "<div class='col-lg-3 col-md-6 col-sm-6'>",
        "<div class='panel panel-info'>",
        "<div class='panel-heading text-center'>" + data[2]['day'] + "</div>",
        "<div class='panel-body text-center'>",
        "High: " + data[2]['hightemp'] + " &deg;F<br>",
        "Low: " + data[2]['lowtemp'] + " &deg;F<br>",
        "<i class='wi " + data[2]['weather-icons']['day'] + "' style='color:#401718; font-size:2em; padding-top: 8px;'></i><br>",
        data[2]['icontext']['day'] + "<br>",
        "<i class='wi " + data[2]['weather-icons']['night'] + "' style='color:#401718; font-size:2em; padding-top: 8px;'></i><br>",
        data[2]['icontext']['night'],
        "</div>",
        "</div>",
        "</div>",
        "<div class='col-lg-3 col-md-6 col-sm-6'>",
        "<div class='panel panel-info'>",
        "<div class='panel-heading text-center'>" + data[3]['day'] + "</div>",
        "<div class='panel-body text-center'>",
        "High: " + data[3]['hightemp'] + " &deg;F<br>",
        "Low: " + data[3]['lowtemp'] + " &deg;F<br>",
        "<i class='wi " + data[3]['weather-icons']['day'] + "' style='color:#401718; font-size:2em; padding-top: 8px;'></i><br>",
        data[3]['icontext']['day'] + "<br>",
        "<i class='wi " + data[3]['weather-icons']['night'] + "' style='color:#401718; font-size:2em; padding-top: 8px;'></i><br>",
        data[3]['icontext']['night'],
        "</div>",
        "</div>",
        "</div>",
        "</div>",
        "<div class='row text-center small'>powered by Accuweather, Inc.&reg;</div>"
    ].join("\n"));
}

$.widget("howe.WeatherInfo",{
    url: 'weather/',
    options: {
        postalCode: '',
        update_interval: 10 * 60 * 1000, /* update the weather every 10 minutes unless there is an expires flag present */
        baseUrl: ''
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
        me.options.authToken = $(e).data('token');
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
            url: me.options.baseUrl + me.url + me.options.postalCode,
            method: 'GET',
            dataType: 'json',
            async: true,
            cache: false,
            headers: { 'X-AUTH-TOKEN': me.options.authToken },
            timeout: 10 * 1000,
            success: function (data, status) {
                if (status === 'timeout') {
                    me.__Error('Ajax Request Timeout');
                }
                me.data_response = data;
                me.__renderWidget();
            },
            error: function (xhr, status) {
                if (status === 'timeout') {
                    me.__Error('Ajax Request Timeout');
                }
                if(status === 'error'){
                    if(xhr.status === 403){
                        me.__Error('Request volume has been exceeded for today');
                    }
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

        clearTimeout(me.update_interval);

        me.widget = renderWeatherWidget(me.data_response)
        $e.html('').append(me.widget);

        let update_interval = me.__getUpdateInterval();

        me.update_interval = setTimeout(function() {
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

});

$.widget("howe.WeatherHistory",{
    url: 'weather/history/id/',
    options: {
        postalCode: '',
        baseUrl: '',
        weatherHistoryId: null
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
        me.options.authToken = $(e).data('token');
        if(me.options.weatherHistoryId !== null){
            me._updateData();
        }
    },

    /**
     * Update the widget's data from the api, then call the render method
     *
     * @private
     */
    _updateData: function(){
        let me = this;
        $.ajax({
            url: me.options.baseUrl + me.url + me.options.weatherHistoryId,
            method: 'GET',
            dataType: 'json',
            async: true,
            cache: false,
            headers: { 'X-AUTH-TOKEN': me.options.authToken },
            timeout: 10 * 1000,
            success: function (data, status) {
                if (status === 'timeout') {
                    me.__Error('Ajax Request Timeout');
                }
                me.data_response = data.response;
                me.data_date = data.date;
                me.__renderWidget();
            },
            error: function (xhr, status) {
                if (status === 'timeout') {
                    me.__Error('Ajax Request Timeout');
                }
                if(status === 'error'){
                    if(xhr.status === 403){
                        me.__Error('Request volume has been exceeded for today');
                    }
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

    UpdateId: function(id) {
        this.options.weatherHistoryId = id;
        this._updateData();
    },

    /**
     * Render the widget's html and css
     *
     * @private
     */
    __renderWidget: function(){
        let me = this;
        let $e = $(this.element);

        clearTimeout(me.update_interval);

        me.widget = renderWeatherWidget(me.data_response)
        $e.html('').append("<div class='row'><div class='col-lg-12 col-md-12 col-sm-12'><div class='panel panel-danger'><div class='panel-heading text-center'>" + me.data_date + "</div></div></div></div>").append(me.widget);

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

});
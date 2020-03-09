/**
 *
 *
 */

$.widget("howe.Mbta",{
    url: '/api/mbta',
    //url: 'http://127.0.0.1:3100/mbta',
    options: {
        update_interval: 10*60*1000, /* update the mbta every 10 minutes unless there is an expires flag present */
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
                if(status === 'nocontent'){
                    me.__Error('No Schedule Available')
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

        clearTimeout(me.update_interval);

        let rows = [];
        me.data_response['trips'].forEach(function(item){
            let departs = me.__formatTimestamp(me.__convertTimestamp(item.departs));
            let arrives = me.__formatTimestamp(me.__convertTimestamp(item.arrives));
            rows.push('<tr>'+
                '<td class="text-center text-primary">' + item.trip + '</td>'+
                '<td class="text-center text-danger">' + departs + '</td>'+
                '<td class="text-center">' + arrives + '</td>'+
                '</tr>');
        });

        me.widget = $([
            "<div class='row'>",
            "<div class='col-lg-12 col-md-12 col-sm-12'>",
            "<table class='table table-striped'>",
            "<thead><tr><th class='text-center'>Trip</th>",
            "<th class='text-center'>Departs</th>",
            "<th class='text-center '>Arrives</th></tr></thead>",
            rows.join("\n"),
            "</table>",
            "</div>",
            "<div class='row text-center small'>powered by Keolis, Inc.&reg;</div>"
        ].join("\n"));
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

    /**
     * This is an internal helper function to format the timestamp return values into actual local times
     *
     * @param {String} timestamp
     * @returns {Date}
     * @private
     */
    __convertTimestamp: function (timestamp) {
        return new Date(timestamp * 1000); // Convert the passed timestamp to milliseconds
    },

    /**
     * Format a date object for the AM/PM time
     * @param {Date} timestamp
     * @returns {string}
     * @private
     */
    __formatTimestamp :function (timestamp) {
        let yyyy = timestamp.getFullYear(),
            mm = ('0' + (timestamp.getMonth() + 1)).slice(-2),	// Months are zero based. Add leading 0.
            dd = ('0' + timestamp.getDate()).slice(-2),			// Add leading 0.
            hh = timestamp.getHours(),
            h = hh,
            min = ('0' + timestamp.getMinutes()).slice(-2),		// Add leading 0.
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

        // ie: 8:35 AM
        time = h + ':' + min + ' ' + ampm;

        return time;
    }
});
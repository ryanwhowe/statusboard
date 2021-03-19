/**
 * Created by Ryan Howe
 *
 */

/**
 */
$.widget("howe.ServerInfo", {
    url: 'server/',
    options: {
        server: null,
        disabled: false,
        id: null,
        max_age: 31 * 60 * 1000,
        skip_keys: ['time_out','keyNames'],
        baseUrl: ''
    },

    /**
     * The constructor of the widget, this is ran on instantiation of the widget
     *
     * @private
     */
    _create: function () {
        let me = this,
            o = this.options,
            e = this.element;
        me.my_name = this.eventNamespace.replace('.', '');
        me.data_response = null;
        let create_server = $(e).data('server');
        let disabled = $(e).data('disabled');
        let baseUrl = $(e).data('baseurl');
        o.authToken = $(e).data('token');
        o.id = $(e).data('serverid');
        if (typeof create_server !== 'undefined') {
            o.server = create_server;
        }

        if(typeof disabled !== 'undefined'){
            o.disabled = disabled;
        }

        if (o.server === null) {
            me.__Error('No Server Name Given');
        }

        if (typeof baseUrl !== 'undefined') {
            o.baseUrl = baseUrl;
        }

        $(e).html(o.server).attr('data-toggle', 'modal').attr('data-target', me.my_name + '_serverModal');

        (e).text(o.server);

        me.__createDialog();

        me.updateStatus();
        $(me.element).show();

        let refresh = me.__intervalRefreshTime();

        me.update_interval = setInterval(function () {
            me.updateStatus();
        }, refresh);
    },

    /**
     * Method for pulling updated data
     *
     */
    updateStatus: function () {
        let me = this,
            e = this.element,
            o = this.options;
        $(e).removeClass('btn-danger btn-success').addClass('btn-primary');
        $.ajax({
            url: o.baseUrl + me.url + o.id,
            method: 'GET',
            dataType: 'json',
            async: true,
            cache: false,
            headers: { 'X-AUTH-TOKEN': o.authToken },
            timeout: 30 * 1000,
            success: function (data, status) {
                if (status === 'timeout') {
                    me.__Error('Ajax Request Timeout');
                }
                me.data_response = data.data;
                o.disabled = data.isDisabled;
                me.__formatData();
            },
            error: function (xhr, status) {
                if(status === 'timeout'){
                    me.__Error('Ajax Request Timeout');
                }
                let error = JSON.parse(xhr.responseText);
                let error_text = 'Invalid Server Name: ' + o.server;
                $.each(error.error, function (index, value) {
                    if (typeof(value) !== 'object') {
                        error_text += value + '<br>';
                    } else {
                        if (typeof value.args[1] !== 'undefined') {
                            error_text += value.args[1] + '<br>';
                        }
                    }
                });
                me.__Error(error_text);
            }
        });
    },

    /**
     * Determine the refresh interval to sync the widget with the server refreshes on the half hours
     *
     * @returns {*}
     * @private
     */
    __intervalRefreshTime: function () {
        let refreshInterval = 5; // minutes
        let now = new Date();
        let next = new Date();
        let result;
        let minuteSet = Math.ceil(next.getMinutes() / refreshInterval) * refreshInterval;
        if (minuteSet === next.getMinutes()){ minuteSet += refreshInterval; }
        next.setMinutes(minuteSet);
        result = next - now;

        /* between 5 seconds and 60 seconds */
        result += this.__randomTime(5 * 1000, 60 * 1000);
        return result;

    },

    /**
     * This is a formatter that will conditionally format the passed value differently for specific keys.  If there is
     * no custom formatter for a passed key the value is just returned.
     *
     * @param key
     * @param value
     * @returns {*}
     * @private
     */
    __formatter: function (key, value) {
        let me = this;
        switch (key) {
            case 'heartbeat':
                return value;
            case 'freespace_root':
            case 'freespace_HDD':
            case 'freespace_HDD1':
                return me.__convertToDiskSize(value);
            case 'timelapse_count':
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            default:
                return value;
        }
    },

    /**
     * Convert the number value to human readable string with size info
     * @param value in kB
     * @returns {string}
     * @private
     */
    __convertToDiskSize: function(value){
        let sizes = ['MB', 'GB', 'TB'];
        if (value == 0) return '0 MB';
        let i = parseInt(Math.floor(Math.log(value) / Math.log(1024)).toString());
        let n = value / Math.pow(1024, i);
        return n.toFixed(2) + ' ' + sizes[i];
    },

    /**
     * Create the dialog box for the widget
     *
     * @private
     */
    __createDialog: function () {
        let me = this;
        let e = this.element;

        me.element_dialog = $([
            "<div class='modal fade' id='" + me.my_name + "_serverModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' style='display: none;'>",
            "    <div class='modal-dialog'>",
            "       <div class='modal-content'>",
            "           <div class='modal-header'>",
            "               <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>",
            "               <h4 class='modal-title' id='" + me.my_name + "_ModalLabel'>" + me.options.server + " Details</h4>",
            "           </div>",
            "           <div class='modal-body'>",
            "               <table id='" + me.my_name + "_table'>",
            "                   <thead>",
            "                       <th>Point</th>",
            "                       <th>Value</th>",
            "                   </thead>",
            "                   <tbody id='" + me.my_name + "_tableBody'>",
            "                   </tbody>",
            "               </table>",
            "           </div>",
            "           <div class='modal-footer'>",
            "				<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>",
            "           </div>",
            "       </div>",
            "    </div>",
            "</div>",
        ].join("\n"));

        $(e).parent().append(me.element_dialog);

        $(e).click(function () {
            $('#' + me.my_name + "_serverModal").modal('show');
        });
    },

    /**
     * Helper method for formatting the key names into more readable versions by converting underscores to spaces and
     * capitalizing all the first letters
     *
     * @param data
     * @returns {string}
     * @private
     */
    __textFormat: function (data) {
        data = String(data);
        return data.replace('_', ' ').replace(/\w\S*/g, function (txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1);
        });
    },

    /**
     * Format the returned api data and update the display
     * @private
     */
    __formatData: function () {
        let me = this;
        let e = this.element;
        let o = this.options;
        let data = this.data_response;

        if (data === null) {
            this.__Error('__formatData called on null data set');
        }
        $(e).removeClass('btn-primary');

        let update_data = [];
        $.each(me.data_response, function (index, datapoint) {
            if (datapoint.keyName === 'heartbeat') {
                me.data_heartbeat = new Date(datapoint.lastUpdateEpoch * 1000);
                update_data.unshift("<tr><td style='font-weight: bold;text-align: right'>" + me.__textFormat(datapoint.keyName) + " : </td><td style='padding-left: 1em;text-align: left' title='" + me.__convertTimestamp(+datapoint.lastUpdateEpoch) + "'>" + me.__formatter(datapoint.keyName, datapoint.keyValue) + "</td></tr>");
            } else if(o.skip_keys.includes(index)){
              return true;
            } else {
                update_data.push("<tr><td style='font-weight: bold;text-align: right'>" + me.__textFormat(datapoint.keyName) + " : </td><td style='padding-left: 1em;text-align: left' title='" + me.__convertTimestamp(+datapoint.lastUpdateEpoch) + "'>" + me.__formatter(datapoint.keyName, datapoint.keyValue) + "</td></tr>");
            }
        });

        update_data = $(update_data.join('\n'));

        $(me.element_dialog).find('#' + me.my_name + '_tableBody').first().html(update_data);

        let current_date = new Date();

        let current_age = (current_date.getTime() - me.data_heartbeat);

        if ( isNaN(current_age) || typeof(current_age) === "undefined" || current_age > o.max_age) {
            if(o.disabled){
                $(e).removeClass('btn-danger btn-success').addClass('btn-warning');
            } else {
                $(e).removeClass('btn-success btn-warning').addClass('btn-danger');
            }
        } else {
            $(e).removeClass('btn-danger btn-warning').addClass('btn-success');
        }
        clearInterval(me.update_interval);
        if(!o.disabled) {
            let refresh = me.__intervalRefreshTime();
            me.update_interval = setInterval(function () {
                me.updateStatus();
            }, refresh);
        }

    },

    /**
     * Return a random value between the max and min values passed
     *
     * @param min
     * @param max
     * @returns int
     */
    __randomTime: function (min, max) {
        return Math.random() * (max - min) + min;
    },

    /**
     * Error handling method for the widget
     *@private
     */
    __Error: function (message) {
        let me = this;
        let error_message = me.widgetFullName + ':' + message;
        let e = this.element;
        $(me.element_dialog).find('#' + me.my_name + '_table').first().replaceWith('<div class="alert alert-danger"><strong>ALERT: </strong>' + error_message + '</div>');
        $(e).removeClass('btn-primary btn-success').addClass('btn-danger');
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

$.widget("howe.ServerGroup", {
    url: 'server',
    options: {
        baseUrl: '',
        rowCount: 3,
    },
    /**
     * The constructor of the widget, this is ran on instantiation of the widget
     *
     * @private
     */
    _create: function () {
        let me = this,
            o = this.options,
            e = this.element;

        o.authToken = $(e).data('token');

        me.data_response = null;

        me.__initUi();

        me.updateStatus();
    },

    __initUi: function () {
        let me = this,
            e = this.element;
        e.addClass('panel panel-info');
        me.heading = $([
            "<div class='panel-heading'>",
            "<div class='panel-title text-center'>",
            "<i class='fa fa-server fa-fw'></i> Servers",
            "<div>",
            "<div>"
        ].join("\n"));
        me.body = $([
            "<div class='panel-body'>"
        ].join("\n"));
        e.append(me.heading).append(me.body);
    },

    /**
     * Method for pulling updated data
     *
     */
    updateStatus: function () {
        let me = this,
            e = this.element,
            o = this.options;

        $.ajax({
            url: o.baseUrl + me.url,
            method: 'GET',
            dataType: 'json',
            async: true,
            cache: false,
            headers: { 'X-AUTH-TOKEN': o.authToken },
            timeout: 30 * 1000,
            success: function (data, status) {
                if (status === 'timeout') {
                    me.__Error('Ajax Request Timeout');
                }
                me.data_response = data;
                me.__formatData();
            },
            error: function (xhr, status) {
                if (status === 'timeout') {
                    me.__Error('Ajax Request Timeout');
                }
                let error = JSON.parse(xhr.responseText);
                let error_text = 'Invalid Server Name: ' + o.server;
                $.each(error.error, function (index, value) {
                    if (typeof (value) !== 'object') {
                        error_text += value + '<br>';
                    } else {
                        if (typeof value.args[1] !== 'undefined') {
                            error_text += value.args[1] + '<br>';
                        }
                    }
                });
                me.__Error(error_text);
            }
        });
    },

    /**
     * Format the returned api data and update the display
     * @private
     */
    __formatData: function () {
        let me = this;
        let o = this.options;
        let data = this.data_response;

        if (data === null) {
            this.__Error('__formatData called on null data set');
        }

        let update_data = [];
        let counter = 0;
        $.each(me.data_response.servers, function (index, server) {
            if (counter++ === 0) {
                update_data.push("<div class='row'>");
            }
            update_data.push("<div class='col-md-12 col-lg-4'>");
            update_data.push("<button style='display: none;' type='button' class='btn btn-primary btn-lg btn-block serverinfo' data-token='" + o.authToken + "' data-serverid='" + server.id + "' data-server='" + server.displayName + "'>");
            update_data.push("</div>");
            if (counter >= o.rowCount) {
                update_data.push("</div>");
                counter = 0;
            }
        });

        update_data = $(update_data.join('\n'));
        me.body.append(update_data);

        $('.serverinfo').ServerInfo({baseUrl: o.baseUrl});
    },


    /**
     * Error handling method for the widget
     *@private
     */
    __Error: function (message) {
        let me = this;
        let error_message = me.widgetFullName + ':' + message;
        let e = this.element;
        $(me.element_dialog).find('#' + me.my_name + '_table').first().replaceWith('<div class="alert alert-danger"><strong>ALERT: </strong>' + error_message + '</div>');
        $(e).removeClass('btn-primary btn-success').addClass('btn-danger');
        clearInterval(me.update_interval);
    },
});

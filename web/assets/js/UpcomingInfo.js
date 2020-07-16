/**
 * Created by Ryan Howe
 *
 */

$.widget("howe.UpcomingInfo", {
    url: 'api/calendar/upcoming',
    options: {
        baseUrl: ''
    },
    /**
     * The constructor of the widget, this is ran on instantiation of the widget
     *
     * @private
     */
    _create: function () {
        let me = this

        me.data_response = null;
        me.__initUi(me);
        me.updateStatus();
    },

    __initUi: function (me) {
        let e = me.element;
        e.addClass('panel panel-info');
        me.heading = $([
            "<div class='panel-heading'>",
            "<div class='panel-title text-center'>",
            "<i class='fa fa-calendar-check-o fa-fw'></i> Upcoming",
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

        update_data.push("<table class='table table-bordered table-striped table-hover'>");
        update_data.push("<thead>");
        update_data.push("<tr class='info'>");
        update_data.push("<th class='text-center'>Event</th>");
        update_data.push("<th class='text-center'>Days</th>");
        update_data.push("</tr>")
        update_data.push("</thead>")
        update_data.push("<tbody>")
        $.each(me.data_response, function (index, event) {
            let the_days = (event.days) ? event.days : "None Scheduled";
            let the_date = (event.days) ? event.date : "N/A";
            update_data.push("<tr>");
            update_data.push("<td>" + event.display_name + "</td>");
            update_data.push("<td title='" + the_date + "'>" + the_days + "</td>")
            update_data.push("</tr>");
        });
        update_data.push("</tbody>")

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

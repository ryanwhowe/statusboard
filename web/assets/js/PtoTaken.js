
$.widget("howe.PtoTaken", {
    url:'api/calendar/pto',
    levels : {
        'good': 'success',
        'warning': 'warning',
        'danger': 'danger'
    },
    options: {
        baseUrl:'',
        ptoRate: 2.0,
    },

    _create: function() {
        let me = this,
            o = this.options,
            e = this.element;
        me.my_name = this.eventNamespace.replace('.', '');
        me.data_response = null;
        me._updateData();
    },

    __initUi: function (style) {
        let me = this;
        let e = me.element;
        let panel_style = ' panel-' + style;
        if(style === 'success'){ style = ''; panel_style = '' }
        e.addClass('panel panel-info' + panel_style);
        me.heading = $([
            "<div class='panel-heading'>",
            "<div class='panel-title text-center'>",
            "<i class='fa fa-motorcycle fa-fw'></i> PTO Taken",
            "<div>",
            "<div>"
        ].join("\n"));
        me.body = $([
            "<div class='panel-body'>"
        ].join("\n"));
        e.append(me.heading).append(me.body);
    },

    _updateData: function() {
        let me = this,
            e = this.element,
            o = this.options;
        $.ajax({
            url: o.baseUrl + me.url,
            method: 'GET',
            dataType: 'json',
            async: true,
            cache: false,
            timeout: 10 * 1000,
            success: function(data, status) {
                if(status === 'timeout'){
                    me.__Error('Ajax Request Timeout');
                }
                if(status === 'nocontent'){
                    me.__Error('No PTO Data Available');
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

        clearTimeout(me.update_interval);
        let daysTaken = +me.data_response.daysTaken;
        let daysScheduled = +me.data_response.daysScheduled;
        let lastPtoDate = me.data_response.lastPtoDate;
        let totalDays = daysTaken + daysScheduled;
        if(lastPtoDate === null){
            me.lastPtoDate = new Date(me.data_response.requestedDate);
        } else {
            me.lastPtoDate = new Date(lastPtoDate);
        }
        const expected = Math.floor(this.__calculateExpectedDays()*10)/10;
        const style = me.__generateStyle(totalDays, expected);

        let $body = $([
            "<div class='row'><table class='table table-striped'>",
            "<thead><tr>",
            "<th class='text-center'>Taken</th><th class='text-center'>Scheduled</th><th class='text-center'>Total</th>",
            "</thead></tr>",
            "<td class='text-center'>" + daysTaken + "</td>",
            "<td class='text-center'>" + daysScheduled + "</td>",
            "<td class='text-center " + style + "' title='Expected:" + expected + " days'>" + totalDays + "</td>",
            "<tbody><tr>",
            "</tr></tbody>",
            "</table></div>"
        ].join("\n"));
        me.body.append($body);
    },

    /**
     * Calculate the expected days of PTO that should have been taken by the
     * last pto scheduled date
     *
     * @returns {number}
     * @private
     */
    __calculateExpectedDays(){
        return (this.lastPtoDate.getMonth() + 1 + this.__monthPercent()) * this.options.ptoRate;
    },

    /**
     * Generate the warning style level based off the time scheduled vs what
     * is expected to be scheduled
     *
     * @param totalDays
     * @param expected
     * @returns {string}
     * @private
     */
    __generateStyle: function(totalDays, expected) {
        // get the total percentage of year completed
        const diff = totalDays - expected;
        if(diff >= -1) return this.levels.good;
        if(diff >= -5) return this.levels.warning;
        return this.levels.danger;
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

    __monthPercent: function () {
        //Determine the Month Values
        let currentDayOfMonth = this.lastPtoDate;
        let lastDayOfMonth = new Date(this.lastPtoDate.getFullYear(), this.lastPtoDate.getMonth()+1, 0);
        let workedMonthDays = currentDayOfMonth.getDate();
        let workingMonthDays = lastDayOfMonth.getDate();
        //console.log({'workedMonthDays': workedMonthDays, 'workingMonthDays': workingMonthDays})
        const percentage = ( workedMonthDays / workingMonthDays );
        //console.log({'monthPercentage':percentage});
        return percentage;
    },

});
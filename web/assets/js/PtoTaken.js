
$.widget("howe.PtoTaken", {
    url:'calendar/pto',
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
        if(style === 'success'){ panel_style = 'panel-info' }
        if(style === 'secondary'){ panel_style = 'panel-default' }
        e.addClass('panel ' + panel_style);
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
        let plannedBurn = 0;
        me.requestDate = new Date(me.data_response.requestedDate);
        if(lastPtoDate === null){
            lastPtoDate = 'None';
        } else {
            plannedBurn = this.__slope(totalDays, new Date(lastPtoDate));
        }
        const expected = Math.floor(this.__expectedAmount(me.requestDate)*10)/10;
        const currentBurn = this.__slope(daysTaken, new Date());

        const status = this.__status(currentBurn, plannedBurn, me.options.ptoRate);

        let style = me.__generateStyle(status);
        if(me.requestDate.getMonth() === 0 || totalDays === 0) style = 'secondary';

        me.__initUi(style);
        const tr_style = (style === 'success') ? 'info' : style;
        let $body = $([
            "<table class='table table-striped table-bordered table-hover'>",
            "<thead><tr class='" + tr_style + "'>",
            "<th class='text-center'>Taken</th><th class='text-center'>Scheduled</th><th class='text-center'>Total</th>",
            "</thead></tr>",
            "<tbody><tr>",
            "<td class='text-center'>" + daysTaken + "</td>",
            "<td class='text-center'>" + daysScheduled + "</td>",
            "<td class='text-center " + style + "'>" + totalDays + "</td>",
            "</tr>",
            "<tr class='" + tr_style + "'><td colspan='3'></td></tr>",
            "<tr><td colspan='2' class='text-right'>Expected Days Scheduled</td><td class='text-center'>" + expected + "</td></tr>",
            "<tr><td colspan='2' class='text-right'>Last PTO date Scheduled</td><td class='text-center'>" + lastPtoDate + "</td></tr>",
            "</tbody>",
            "</table>"
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
    __expectedAmount(theDate){
        return (theDate.getMonth() + this.__monthPercent(theDate)) * this.options.ptoRate;
    },

    /**
     * Generate the warning style level based off the time scheduled vs what
     * is expected to be scheduled
     *
     * @param status
     * @returns {string}
     * @private
     */
    __generateStyle: function(status) {
        if(status === 2) return this.levels.danger;
        if(status === 1) return this.levels.warning;
        return this.levels.good;
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

    __monthPercent: function (theDate) {
        //Determine the Month Values
        let currentDayOfMonth = theDate;
        let lastDayOfMonth = new Date(theDate.getFullYear(), theDate.getMonth()+1, 0);
        let workedMonthDays = currentDayOfMonth.getDate();
        let workingMonthDays = lastDayOfMonth.getDate();
        //console.log({'workedMonthDays': workedMonthDays, 'workingMonthDays': workingMonthDays})
        const percentage = ( workedMonthDays / workingMonthDays );
        //console.log({'monthPercentage':percentage});
        return percentage;
    },

    __months: function(theDate) {
        return (theDate.getMonth() + this.__monthPercent(theDate));
    },

    __status: function(currentBurn, plannedBurn, idealBurn){
        let currentDelta = this.__delta(currentBurn, idealBurn)
        let plannedDelta = this.__delta(plannedBurn, idealBurn);
        let theDelta = this.__useDelta(currentDelta, plannedDelta);
        if(theDelta.over){ /// we are OVER the burn rate
            if(theDelta.delta < 0.1) return 0;
            if(theDelta.delta < 0.2) return 1;
            return 2;
        } else { // we are under the burn rate
            if(theDelta.delta < 0.2) return 0;
            if(theDelta.delta < 0.4) return 1;
            return 2;
        }
    },

    __slope: function(taken, theDate) {
          return taken / this.__months(theDate);
    },

    __delta: function (slope, ideal) {
        return {'delta': Math.abs(slope - ideal), 'over': 0 < (slope - ideal)};
    },

    __useDelta: function(current, planned) {
        if(planned.over) return planned;
        if(current.delta < planned.delta) return current;
        return planned;
    },

});
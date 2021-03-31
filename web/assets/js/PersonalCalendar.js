let PersonalCalendar = {

    date_data: null,

    init: function(dateData){
        this.setDateDate(dateData);
    },

    setDateDate: function(dateData){
        this.date_data = dateData;
    },
    /**
     * This contains the parsing logic to return the option array for the datepicker date before it is rendered.
     * The array return is :
     *  [
     *      bool - if the date should be selectable,
     *      string - css class to apply to the date element,
     *      string - tooltip text to apply to the date element
     *  ]
     *
     * @param currentDate
     * @returns array
     */
    parseDate: function (currentDate) {
        let parsedDate = currentDate.toISOString().slice(0, 10);
        let result = this.checkDate(parsedDate);
        let noWeekend = $.datepicker.noWeekends(currentDate);
        if (noWeekend[0] === false) {
            return noWeekend;
        } else {
            return result;
        }
    },

    dateProcessor: function(events) {
        let selectable = events.reduce(function(val, event,) { return (val ? (event.type !== 1) : val);}, true);
        let css = events.reduce(this.getStackableCss, events.reduce(this.getPrimaryCss,''));
        let tooltips = events.reduce(this.collectTooltips, [])
        return {selectable: selectable, css: css.trim(), tooltips: tooltips.filter((el) => el != null).join(', ')};
    },

    getPrimaryCss: function(css, event) {
        if(css === 'pto') return css;
        if(event.type === 2) return 'pto'
        if(event.type === 3) return 'sick'
        if(css === 'sick') return css;
        if(event.type === 1) return 'companyholiday'
        if(css === 'companyholiday') return css;
        if(event.type === 94) return 'nationalholiday'
        return css;
    },

    getStackableCss: function(css, event) {
        if(event.type === 99) return css + ' paydate';
        if(event.type === 95) return css + ' fimeeting';
        return css
    },

    collectTooltips: function(tooltips, event) {
        let mapTypeToPriority = function(type) {
            if(type === 94) return 1;
            if(type === 1) return 2;
            if(type === 2) return 3;
            if(type === 3) return 4;
            if(type === 95) return 5;
            if(type === 99) return 6;
        };
        let priority = mapTypeToPriority(event.type);
        tooltips[priority] = event.description;
        return tooltips;
    },

    /**
     * check the date
     * @param parsed_date
     * @returns {[boolean, string, string]}
     */
    checkDate: function (parsed_date) {
        let selectable = true;
        let tooltips='';
        let css = '';
        if(parsed_date in this.date_data){
            let result = this.dateProcessor(this.date_data[parsed_date].events);
            selectable = result.selectable;
            css = result.css;
            tooltips = result.tooltips;
        }
        return [selectable, css, tooltips];
    }
};
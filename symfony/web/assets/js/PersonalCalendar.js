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

    /**
     * check the date
     * @param parsed_date
     * @returns {[boolean, string, string]}
     */
    checkDate: function (parsed_date) {
        let selectable = true;
        var tooltips='';
        let css = '';
        let css_class = '';
        let company_holiday = false;
        let css_selector = function(calendarItem){
            if (calendarItem['type_id'] === 1) return 'companyholiday';
            if (calendarItem['type_id'] === 2) return 'pto';
            if (calendarItem['type_id'] === 3) return 'sick';
            if (calendarItem['type_id'] === 4) return 'nationalholiday';
            if (calendarItem['type_id'] === 99) return 'payday';
        };
        if(parsed_date in this.date_data){
            let event = this.date_data[parsed_date];
            tooltips = [];
            $.each(event.events, function(index, item){
                selectable = selectable ? !(item['type_id'] === 1) : selectable;

                css_class = css_selector(item);
                if(css_class === 'companyholiday'){
                    company_holiday = true;
                    css = css_class;
                }
                if(!company_holiday) {
                    css = css_class;
                }
                tooltips.push(item['description']);
            });
            tooltips = tooltips.join(', ');
        }
        return [selectable, css, tooltips];
    }
};
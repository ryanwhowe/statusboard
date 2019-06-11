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

        let noWeekend;

        let result = this.isPayDay(currentDate);

        /*
            WD:RWH - 2017-12-14: change this to be a "better" chain-able object

            This is a chaining from the least important to the most important.  It is important to note that since
            this runs after the pay day check any of these that fall on the same date as a pay-day will add to that
            event
         */
        result = this.checkParseDate(parsedDate,
            this.date_data.sick,
            this.checkParseDate(
                parsedDate,
                this.date_data.holiday,
                this.checkParseDate(
                    parsedDate,
                    this.date_data.pto,
                    result,
                    "PTO",
                    "pto"
                ),
                "TRUECar Holiday",
                "holiday",
                false
            ),
            "Sick Day",
            "sick");

        noWeekend = $.datepicker.noWeekends(currentDate);
        if (noWeekend[0] === false) {
            return noWeekend;
        } else {
            return result;
        }
    },

    /**
     * Modify and return the result_array depending if the parsed date is contained in the check_data array.
     *
     * @param parsed_date string
     * @param check_data array
     * @param result_array array
     * @param tip_text string
     * @param css_text string
     * @param selectable boolean
     * @returns {*}
     */
    checkParseDate: function (parsed_date, check_data, result_array, tip_text, css_text, selectable = true) {
        if ($.inArray(parsed_date, check_data) !== -1) {
            if (result_array[2].length > 1) {
                result_array[2] = result_array[2] + ', ' + tip_text;
            } else {
                result_array[2] = tip_text;
            }
            result_array[1] = css_text;
            result_array[0] = selectable;
        }
        return result_array;
    },

    /**
     * Determine if this date is a pay date, if not return the default result array
     *
     * @param date
     * @returns []
     */
    isPayDay: function (date) {
        if (this.isTrueCarPayDate(date)) return [true, 'payday', "Bi-monthly Pay Date"];
        if (this.isIvesPayDate(date)) return [true, 'payday', "Bi-weekly Pay Date"];
        if (this.isAventionPayDate(date)) return [true, 'payday', "Bi-weekly Pay Date"];
        return [true, '', ''];
    },

    /**
     * Determine if the date is an Avention pay date, a friday on a week number that is even
     *
     * @param date
     * @returns {boolean}
     */
    isAventionPayDate: function (date) {
        let start_date = new Date(2007, 5, 1, 0, 0, 0);
        let end_date = new Date(2015, 7, 1, 0, 0, 0);
        if (end_date - date < 0) return false;
        if (date - start_date < 0) return false;
        if (date.getDay() !== 5) return false;
        return $.datepicker.iso8601Week(date) % 2 === 0;
    },

    /**
     * Determine if the date is an Ives pay date, a friday on a weeknumber that is odd
     *
     * @param date
     * @returns {boolean}
     */
    isIvesPayDate: function (date) {
        let start_date = new Date(2015, 7, 31, 0, 0, 0);
        let end_date = new Date(2019, 2, 7, 0, 0, 0);
        if (end_date - date < 0) return false;
        if (date - start_date < 0) return false;
        if (date.getDay() !== 5) return false;
        return $.datepicker.iso8601Week(date) % 2 !== 0;
    },

    /**
     * Determine if the date is a TRUECar by monthly pay date.  Paydays are on the weekdays of or before the 15th and last day of the
     * month
     *
     * @param date
     * @returns {boolean}
     */
    isTrueCarPayDate: function (date) {
        let start_date = new Date(2019, 4, 28, 0, 0, 0);
        if (date - start_date < 0) return false;
        // weekends can never be paydays
        if (date.getDay() === 0 || date.getDay() === 6) return false;

        // check if today is the 15th
        if (date.getDate() === 15) return true;

        // if it is the last day of the month
        let last_day_of_month = new Date(date.getFullYear(), date.getMonth() + 1, 1);
        last_day_of_month = new Date(last_day_of_month - (24 * 60 * 60 * 1000));

        if (date.getTime() === last_day_of_month.getTime()) return true;

        // simple cases are done, we only need to peek at Fridays to ensure they are not before a weekend paydate
        if (date.getDay() === 5) {
            if (date.getDate() === 13 || date.getDate() === 14) return true;
            if ((last_day_of_month.getDate() - date.getDate()) <= 2) return true;
        }

        return false;
    }
};
'use strict';
class WorkDateRange{
    constructor(start_date, end_date){
        if(end_date !== null && end_date - start_date < 0) { new Error('Start Date can not be after End Date') }
        return {
            start_date: start_date,
            end_date : end_date
        }
    }
}

class PayDate{

    static DAY_MILLISECONDS = 24 * 60 * 60 * 1000;

    static workDates = {
        Ives: new WorkDateRange(new Date(2015, 7, 31, 0, 0, 0), new Date(2019, 2, 7, 0, 0, 0)),
        Avention : new WorkDateRange(new Date(2007, 5, 1, 0, 0, 0), new Date(2015, 7, 1, 0, 0, 0)),
        TRUECar : new WorkDateRange(new Date(2019, 4, 28, 0, 0, 0), null)
    };

    constructor(current_date){
        return PayDate.isPayDay(current_date);
    }

    /**
     * Determine if this date is a pay date, if not return the default result array
     *
     * @param date
     * @returns []
     */
    static isPayDay (date) {
        if (PayDate.isTrueCarPayDate(date)) return [true, 'payday', "TRUECar Pay Date"];
        if (PayDate.isIvesPayDate(date)) return [true, 'payday', "Ives Pay Date"];
        if (PayDate.isAventionPayDate(date)) return [true, 'payday', "Avention Pay Date"];
        return [true, '', ''];
    }

    /**
     * Determine if the date is an Avention pay date, a friday on a week number that is even
     *
     * @param date
     * @returns {boolean}
     */
    static isAventionPayDate (date) {
        if(PayDate.validDateRange(date, PayDate.workDates.Avention)) {
            if (date.getDay() !== 5) return false;
            return $.datepicker.iso8601Week(date) % 2 === 0;
        } else {
            return false;
        }
    }

    /**
     * Determine if the date is an Ives pay date, a friday on a weeknumber that is odd
     *
     * @param date
     * @returns {boolean}
     */
    static isIvesPayDate (date) {
        if(PayDate.validDateRange(date, PayDate.workDates.Ives)) {
            if (date.getDay() !== 5) return false;
            return $.datepicker.iso8601Week(date) % 2 !== 0;
        } return false;
    }

    /**
     * Determine if the date is a TRUECar by monthly pay date.  Paydays are on the weekdays of or before the 15th and last day of the
     * month
     *
     * @param date
     * @returns {boolean}
     */
    static isTrueCarPayDate (date) {
        if(PayDate.validDateRange(date, PayDate.workDates.TRUECar)) {
            // weekends can never be paydays
            if (date.getDay() === 0 || date.getDay() === 6) return false;

            // check if today is the 15th
            if (date.getDate() === 15) return true;

            // if it is the last day of the month
            let last_day_of_month = new Date(date.getFullYear(), date.getMonth() + 1, 1);
            last_day_of_month = new Date(last_day_of_month - (PayDate.DAY_MILLISECONDS));

            if (date.getTime() === last_day_of_month.getTime()) return true;

            // simple cases are done, we only need to peek at Fridays to ensure they are not before a weekend paydate
            if (date.getDay() === 5) {
                if (date.getDate() === 13 || date.getDate() === 14) return true;
                if ((last_day_of_month.getDate() - date.getDate()) <= 2) return true;
            }

            return false;
        } else {
            return false;
        }
    }

    /**
     * Check the date passed against the date range object passed to see if the date falls within the boundaries of the
     * start and end date.  A null end date indicates that there is NO end date to range against and will return true
     * for any dates after the start date.
     *
     * @param date
     * @param dates
     * @returns {boolean}
     */
    static validDateRange(date, dates){
        if (date - dates.start_date < 0) return false;
        if (dates.end_date !== null && dates.end_date - date < 0) return false;
        return true;
    }

}

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
        let result = new PayDate(currentDate);

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
                "Holiday",
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


};

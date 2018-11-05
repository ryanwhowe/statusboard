/**
 * The Clock object is used to determine the percentage of various durations of time that have passed for the
 * current time.
 *
 * @type {{CurrentDate: Date, dayStart: number, dayEnd: number, weekStart: number, weekEnd: number, dayPercent: Clock.dayPercent, weekPercent: Clock.weekPercent, monthPercent: Clock.monthPercent, quarter: Clock.quarter, quarterPercent: Clock.quarterPercent, yearPercent: Clock.yearPercent, currentTimeString: Clock.currentTimeString, update: Clock.update, workingMonthDays: Clock.workingMonthDays, workedMonthDays: Clock.workedMonthDays, fixPercent: Clock.fixPercent}}
 */
var Clock = {

    CurrentDate: new Date(),

    dayStart: 8.0,
    dayEnd: 16.0,
    weekStart: 1,
    weekEnd: 6,

    /**
     *
     * @returns {number}
     */
    dayPercent: function () {
        var dayPercent = ((this.CurrentDate.getHours() + this.CurrentDate.getMinutes() / 60 + this.CurrentDate.getSeconds() / 3600) - this.dayStart) / (this.dayEnd - this.dayStart);
        dayPercent = (dayPercent > 1 ? 1 : dayPercent);
        dayPercent = (dayPercent < 0 ? 0 : dayPercent);
        return dayPercent;
    },

    weekPercent: function () {
        //Determine the WeekPercent Values
        var weekPercent = (this.CurrentDate.getDay() - this.weekStart) / (this.weekEnd - this.weekStart);
        weekPercent = weekPercent + (this.dayPercent() / (this.weekEnd - this.weekStart));
        weekPercent = (weekPercent > 1 ? 1 : weekPercent);
        weekPercent = (weekPercent < 0 ? 0 : weekPercent);
        return weekPercent;
    },

    monthPercent: function () {
        //Determin the Month Values
        var workedMonthDays = this.workedMonthDays();
        var workingMonthDays = this.workingMonthDays();
        var dayPercent = this.dayPercent();
        return workedMonthDays / workingMonthDays + (dayPercent / (workingMonthDays) );
    },

    quarter: function () {
        //Determin the Quarter Values
        return Math.round(Math.ceil((this.CurrentDate.getMonth() + 1) / 3));
    },

    quarterPercent: function () {
        return (((this.CurrentDate.getMonth() % 3)) / 3) + (this.monthPercent() / 3);
    },

    yearPercent: function () {
        return (Math.round(((this.CurrentDate - (new Date(this.CurrentDate.getFullYear(), 0, 1))) / 1000 / 60 / 60 / 24) + .5, 0) / 365) + (this.dayPercent() / 365) - (1 / 365);
    },

    year: function(){
        return this.CurrentDate.getFullYear();
    },

    currentTimeString: function () {

        this.update();

        var currentHours = this.CurrentDate.getHours();
        var currentMinutes = this.CurrentDate.getMinutes();
        var currentSeconds = this.CurrentDate.getSeconds();

        // Pad the minutes and seconds with leading zeros, if required
        currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
        currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

        // Choose either "AM" or "PM" as appropriate
        var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

        // Convert the hours component to 12-hour format if needed
        currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

        // Convert an hours component of "0" to "12"
        currentHours = ( currentHours === 0 ) ? 12 : currentHours;

        // Compose the string for display
        return currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;
    },

    update: function () {
        this.CurrentDate = new Date();
    },

    workingMonthDays: function () {
        var workingMonthDays = 0;
        var StartOfMonth = new Date(this.CurrentDate.getFullYear(), this.CurrentDate.getMonth(), 1);
        var EndOfMonth = new Date(this.CurrentDate.getFullYear(), this.CurrentDate.getMonth() + 1, 0);
        for (var d = StartOfMonth; d <= EndOfMonth; d.setDate(d.getDate() + 1)) {
            if (d.getDay() !== 0 && d.getDay() !== 6) {
                workingMonthDays++;
            }
        }
        return workingMonthDays;
    },
    workedMonthDays: function () {
        var totalMonthDays = 0;
        var StartOfMonth = new Date(this.CurrentDate.getFullYear(), this.CurrentDate.getMonth(), 1);
        var EndOfMonth = new Date(this.CurrentDate.getFullYear(), this.CurrentDate.getMonth() + 1, 0);
        for (var d = StartOfMonth; d <= EndOfMonth; d.setDate(d.getDate() + 1)) {
            if (d.getDay() !== 0 && d.getDay() !== 6 && d.getDate() < this.CurrentDate.getDate()) {
                totalMonthDays++;
            }
        }
        return totalMonthDays;
    },

    fixPercent: function (value) {
        return Math.round(value * 10) / 10;
    }
};
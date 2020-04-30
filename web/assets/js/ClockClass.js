/**
 * The Clock object is used to determine the percentage of various durations of time that have passed for the
 * current time.
 *
 * @type {{CurrentDate: Date, dayStart: number, dayEnd: number, weekStart: number, weekEnd: number, dayPercent: (function(): number), weekPercent: (function(): number), monthPercent: (function(): number), quarter: (function(): number), quarterPercent: (function(): number), yearPercent: (function(): number), year: (function(): number), currentTimeString: (function(): string), update: Clock.update, workingMonthDays: (function(): number), workedMonthDays: (function(): number), displayPercent: (function(*): number)}}
 */

let Clock = {

    CurrentDate: new Date(),

    dayStart: 9.0,
    dayEnd: 17.0,
    weekStart: 1,
    weekEnd: 6,

    /**
     *
     * @returns {number}
     */
    dayPercent: function () {
        let dayPercent = ((this.CurrentDate.getHours() + this.CurrentDate.getMinutes() / 60 + this.CurrentDate.getSeconds() / 3600) - this.dayStart) / (this.dayEnd - this.dayStart);
        return this.boundPercent(dayPercent);
    },

    weekPercent: function () {
        //Determine the WeekPercent Values
        let weekPercent = (this.CurrentDate.getDay() - this.weekStart) / (this.weekEnd - this.weekStart);
        weekPercent = weekPercent + (this.dayPercent() / (this.weekEnd - this.weekStart));
        return this.boundPercent(weekPercent);
    },

    monthPercent: function () {
        //Determine the Month Values
        let workedMonthDays = this.workedMonthDays();
        let workingMonthDays = this.workingMonthDays();
        let dayPercent = this.dayPercent();
        return ( workedMonthDays / workingMonthDays ) + ( dayPercent / workingMonthDays );
    },

    quarter: function () {
        //Determine the Quarter Values
        return Math.round(Math.ceil((this.CurrentDate.getMonth() + 1) / 3));
    },

    quarterPercent: function () {
        return (((this.CurrentDate.getMonth() % 3)) / 3) + (this.monthPercent() / 3);
    },

    yearPercent: function () {
        return (((this.quarter() - 1) * 25 / 100) + (this.quarterPercent()/4));
    },

    year: function(){
        return this.CurrentDate.getFullYear();
    },

    currentTimeString: function () {

        this.update();

        let currentHours = this.CurrentDate.getHours();
        let currentMinutes = this.CurrentDate.getMinutes();
        let currentSeconds = this.CurrentDate.getSeconds();

        // Pad the minutes and seconds with leading zeros, if required
        currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
        currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

        // Choose either "AM" or "PM" as appropriate
        let timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

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
        let workingMonthDays = 0;
        let StartOfMonth = new Date(this.CurrentDate.getFullYear(), this.CurrentDate.getMonth(), 1);
        let EndOfMonth = new Date(this.CurrentDate.getFullYear(), this.CurrentDate.getMonth() + 1, 0);
        for (let d = StartOfMonth; d <= EndOfMonth; d.setDate(d.getDate() + 1)) {
            if (d.getDay() !== 0 && d.getDay() !== 6) {
                workingMonthDays++;
            }
        }
        return workingMonthDays;
    },
    workedMonthDays: function () {
        let workedMonthDays = 0;
        let StartOfMonth = new Date(this.CurrentDate.getFullYear(), this.CurrentDate.getMonth(), 1);
        let EndOfMonth = new Date(this.CurrentDate.getFullYear(), this.CurrentDate.getMonth() + 1, 0);
        for (let d = StartOfMonth; d <= EndOfMonth; d.setDate(d.getDate() + 1)) {
            if (d.getDay() !== 0 && d.getDay() !== 6 && d.getDate() < this.CurrentDate.getDate()) {
                workedMonthDays++;
            }
        }
        return workedMonthDays;
    },

    displayPercent: function (value) {
        return Math.floor(value * 10) / 10;
    },

    boundPercent: function (percent){
        let result = percent > 1.0 ? 1.0 : percent;
        return result < 0.0 ? 0.0 : result;
    }
};
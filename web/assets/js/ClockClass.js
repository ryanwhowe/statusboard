'use strict';
class MetaClock {

    CurrentDate;

    dayStart= 9.0;
    dayEnd= 17.0;
    weekStart= 1;
    weekEnd= 6;

    workedDays= {};
    workingDays= {};

    constructor(day_start = 9.0){
        this.CurrentDate = new Date();
        this.dayStart = day_start;
        this.workedMonthDays();
        this.workingMonthDays();
    }

    dayPercent() {
        let dayPercent = ((this.CurrentDate.getHours() + this.CurrentDate.getMinutes() / 60 + this.CurrentDate.getSeconds() / 3600) - this.dayStart) / (this.dayEnd - this.dayStart);
        return this._boundPercentages(dayPercent);
    }

    _boundPercentages(percentage){
        percentage = percentage > 1.0 ? 1.0 : percentage;
        return (percentage < 0.0) ? 0.0 : percentage;
    }

    weekPercent() {
        //Determine the WeekPercent Values
        let weekPercent = (this.CurrentDate.getDay() - this.weekStart) / (this.weekEnd - this.weekStart);
        weekPercent = weekPercent + (this.dayPercent() / (this.weekEnd - this.weekStart));
        return this._boundPercentages(weekPercent);
    }

    monthPercent() {
        //Determine the Month Values
        let workedMonthDays = this.workedMonthDays();
        let workingMonthDays = this.workingMonthDays();
        let dayPercent = this.dayPercent();
        return workedMonthDays / workingMonthDays + (dayPercent / workingMonthDays );
    }

    quarter() {
        //Determine the Quarter Values
        return Math.round(Math.ceil((this.CurrentDate.getMonth() + 1) / 3));
    }

    quarterPercent() {
        return (((this.CurrentDate.getMonth() % 3)) / 3) + (this.monthPercent() / 3);
    }

    yearPercent() {
        return (Math.round(((this.CurrentDate - (new Date(this.CurrentDate.getFullYear(), 0, 1))) / 1000 / 60 / 60 / 24) + .5) / 365) + (this.dayPercent() / 365) - (1 / 365);
    }

    year(){
        return this.CurrentDate.getFullYear();
    }

    currentTimeString() {

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
    }

    update() {
        this.CurrentDate = new Date();
    }

    workingMonthDays() {
        if(this.CurrentDate.getMonth() in this.workingDays){
            return this.workingDays[this.CurrentDate.getMonth()];
        } else {
            let workingMonthDays = 0;
            let StartOfMonth = new Date(this.CurrentDate.getFullYear(), this.CurrentDate.getMonth(), 1);
            let EndOfMonth = new Date(this.CurrentDate.getFullYear(), this.CurrentDate.getMonth() + 1, 0);
            for (let d = StartOfMonth; d <= EndOfMonth; d.setDate(d.getDate() + 1)) {
                if (d.getDay() !== 0 && d.getDay() !== 6) {
                    workingMonthDays++;
                }
            }
            this.workingDays[this.CurrentDate.getMonth()] = workingMonthDays;
            return this.workingDays[this.CurrentDate.getMonth()];
        }
    }

    workedMonthDays() {
        if(this.CurrentDate.getMonth() in this.workedDays){
            return this.workedDays[this.CurrentDate.getMonth()];
        } else {
            let totalMonthDays = 0;
            let StartOfMonth = new Date(this.CurrentDate.getFullYear(), this.CurrentDate.getMonth(), 1);
            let EndOfMonth = new Date(this.CurrentDate.getFullYear(), this.CurrentDate.getMonth() + 1, 0);
            for (let d = StartOfMonth; d <= EndOfMonth; d.setDate(d.getDate() + 1)) {
                if (d.getDay() !== 0 && d.getDay() !== 6 && d.getDate() < this.CurrentDate.getDate()) {
                    totalMonthDays++;
                }
            }
            this.workedDays[this.CurrentDate.getMonth()] = totalMonthDays;
            return this.workedDays[this.CurrentDate.getMonth()];
        }
    }

    displayPercent(value) {
        return Math.floor(value * 10) / 10;
    }

}
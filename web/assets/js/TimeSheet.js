'use strict';

/**
 * Refactored from the TimeSheet object for better performance and readibility
 *
 */
class TimeSheet {

    static MINUTE_MILLISECONDS = 60 * 1000;
    static HOUR_MILLISECTIONS = 60 * 60 * 1000;

    constructor(startTime, offset){
        this.setHourOffset(offset);
        this.setStartTime(startTime);
    }

    getStartTime(){ return this.startTime; }

    setStartTime(startTime){
        /* @todo This needs to adjust the start time based off of the offset */
        this.startTime = startTime;
        this.stepsArray = this.generateStepsArray()
    }

    setHourOffset(offset){ this.offset = offset; }

    /**
     * todo: unfuck this thing into something better
     * @returns {Array}
     */
    generateStepsArray(){
        let hours;
        let arrayLength = 24 * 10; // hours * steps
        let steps = [...Array(arrayLength).keys()];
        steps.unshift(0);
        let output = [];
        let arrival = this.startTime;
        output.push({ time_index :  arrival, hours : 0 });
        $.each(steps, function(i){
            if(i !== 0) {
                hours = (i / 10);
                arrival = TimeSheet.addMinutes(arrival, 6);
                output.push({ time_index: arrival, hours: hours });
            }
        });
        return output;
    }

    /**
     * Add minutes to the provided date object
     *
     * @param date
     * @param minutes
     * @returns {Date}
     */
    static addMinutes(date, minutes){ return new Date(date.getTime() + minutes * TimeSheet.MINUTE_MILLISECONDS); }

    getDisplayValue(){
        let current_time = TimeSheet.getTime();
        let display_hour = 0, index_time = new Date(0,0,0,0,0,0);
        let me = this;
        $.each(this.stepsArray, function(i, step){
            if(current_time >= step.time_index){
                display_hour = step.hours + me.offset;
                index_time = me.stepsArray[i+1].time_index;
            }
        });
        return {'display_hour': display_hour.toFixed(1), 'index_time': index_time }
    }

    static getTime(){
        let parser = new Date();
        return new Date(0,0,0,parser.getHours(), parser.getMinutes(), parser.getSeconds());
    }

    getFormattedTime(){ return TimeSheet.formatTime(TimeSheet.getTime()); }

    static formatTime(date) {
        return date.getHours().toString().padStart(2, '0') + ':' + date.getMinutes().toString().padStart(2, '0') + ':' + date.getSeconds().toString().padStart(2, '0');
    }

    getEighthHourTime() {
        return TimeSheet.addMinutes( this.startTime, 8 * 60 );
    }

    getGivenOffsetTime(offset) {
        let me = this;
        let result = false;
        $.each(this.stepsArray, function(i, step){
            if( offset === step.hours + me.offset ){
                let parser = step.time_index;
                result = new Date(0,0,0,parser.getHours(), parser.getMinutes(), parser.getSeconds());
                return false;
            }
        });
        return result;
    }

}
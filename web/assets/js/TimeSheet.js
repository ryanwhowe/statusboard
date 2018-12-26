
let TimeSheet = {

    init: function(startTime, offset){
        this.setStartTime(startTime);
        this.setHourOffset(offset);
    },

    getStartTime: function(){ return this.startTime; },

    setStartTime: function(startTime){
        this.startTime = startTime;
        this.stepsArray = this.generateStepsArray()
    },

    setHourOffset: function(offset){ this.offset = offset; },

    generateStepsArray: function(){
        let hours;
        let steps = [...Array(100).keys()];
        steps.unshift(0);
        let output = [];
        let arrival = this.startTime;
        output.push({ time_index :  arrival, hours : 0 });
        let me = this;
        $.each(steps, function(i){
            if(i !== 0) {
                hours = (i / 10);
                arrival = me.addMinutes(arrival, 6);
                output.push({ time_index: arrival, hours: hours });
            }
        });
        return output;
    },

    addMinutes: function(date, minutes){ return new Date(date.getTime() + minutes*60000); },

    getDisplayValue: function(){
        let current_time = this.getTime();
        let display_hour = 0, index_time = new Date(0,0,0,0,0,0);
        let me = this;
        $.each(this.stepsArray, function(i, step){
            if(current_time >= step.time_index){
                display_hour = step.hours + me.offset;
                index_time = me.stepsArray[i+1].time_index;
            }
        });
        return {'display_hour': display_hour.toFixed(1), 'index_time': index_time }
    },

    getTime: function(){
        let parser = new Date();
        return new Date(0,0,0,parser.getHours(), parser.getMinutes(), parser.getSeconds());
    },

    getFormattedTime: function(){ return this.formatTime(this.getTime()); },

    formatTime: function(date) {
        return date.getHours().toString().padStart(2, '0') + ':' + date.getMinutes().toString().padStart(2, '0') + ':' + date.getSeconds().toString().padStart(2, '0');
    },

    getEighthHourTime: function() {
        return this.getGivenOffsetTime(8.0);
    },

    getGivenOffsetTime: function(offset) {
        let me = this;
        let result;
        $.each(this.stepsArray, function(i, step){
            if( offset === step.hours + me.offset ){
                let parser = step.time_index;
                result =  new Date(0,0,0,parser.getHours(), parser.getMinutes(), parser.getSeconds());
                return false;
            }
        });
        return result;
    },

};


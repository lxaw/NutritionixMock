"use strict"

/*
This class represents a NutrientPieChart.
It is a wrapper over a Chart.js Pie (Doughnut) object.
*/

class NutrientPieChart{
    /*
    strTitle: [title of chart]
        Title of chart.
    strId: [id of html canvas]
        The ID of the canvas that is populated with the chart.
    strChartType: [increase,decrease]
        If increase, the pie chart begins at zero and increases as nutrients added.
        If decrease, pie chart begins at limit and decreases as nutrients added.
    strColorBg: [hex color]
        Color of background of chart.
    strColorFg: [hex color]
        Color of foreground of chart.
    strColorOver: [hex color]
        Color that chart turns into when over limit.
    intLimit: [int]
        Integer limit for macronutrient amount. When go over the chart turns color `strColorOver`.
    */
    constructor(strTitle,strId,strChartType,strColorBg,strColorFg,strColorOver,intLimit){
        this.strId = strId;

        this.strChartType=strChartType;

        this.strColorBg=strColorBg;
        this.strColorFg=strColorFg;
        this.strColorOver=strColorOver;
        this.strTitle=strTitle;

        this.intLimit = intLimit;
        
        // reference to chart object
        this.chart = null;

        // if the chart is over capacity or not
        this.boolIsOver = false

        this.voidGenerateChart()
    }
    // getters

    getIntLimit(){
        return this.intLimit
    }
    getChart(){
        return this.chart
    }
    getBoolIsOver(){
        return this.boolIsOver
    }
    getStrColorOver(){
        return this.strColorOver
    }

    /*
    Create the chart.
    */
    voidGenerateChart(){
        /*
        If strChartType == "increase", added nutrient vals increase
        the chart; thus the chart starts at zero and goes up
        If strChartType == "decrease", added nutrients take away from limit,
        thus chart starts at limit and goes down.
        */
        var data = null;
        var labels = null;
        var backgroundColor = [this.strColorBg,this.strColorFg]
        var text = null

        if(this.strChartType == "increase"){
            data = [this.intLimit,0]
            labels = ['Remaining','Used']
            text = "0%"
        }else if(this.strChartType == "decrease"){
            data = [0, this.intLimit]
            labels = ["Used","Remaining"]
            text = "100%"
        }

        this.chart = new Chart(this.strId,{
            type:'doughnut',
            data:{
                labels:labels,
                datasets:[{
                    backgroundColor:backgroundColor,
                    data:data,
                    borderWidth:0,
                    borderColor:'black',
                    hoverBorderWidth:0
                }]
            },
            plugins:[{
                beforeDraw:function(chart){
                    var w = chart.chart.width;
                    var h = chart.chart.height;
                    var ctx = chart.chart.ctx;
                    var fontSize = h / 112;
                    ctx.font = fontSize + 'em sans-serif';
                    ctx.textBaseline = 'middle';
                    var textX = Math.round((w- ctx.measureText(text).width)/2);
                    var textY = h / 2;

                    ctx.fillText(text,textX,textY);
                    ctx.save();
                }
            }],
            options:{
                legend:{
                    display:false,
                }
            }
        })
    }

    voidUpdateChart(intNewVal){
        // y values of chart
        // yVals[0] = amount of nutrients remaining
        // yVals[1] = amount of nutrients used
        var yVals = this.chart.data.datasets[0].data

        if(intNewVal >= this.intLimit){
            // dont do anything as we are still above the limit
            // only change the bg color / border if not already changed
            //
            if(this.boolIsOver != true){
                this.boolIsOver = true;
                if(this.strChartType == "decrease"){
                    yVals[0] = this.intLimit
                    yVals[1] = 0
                }else{
                    yVals[1] = this.intLimit
                    yVals[0] = 0
                }
                this.chart.data.datasets[0].backgroundColor[1] = this.strColorOver;
            }
        }else{
            // else just continue
            // add to used and take away from remaining
            //
            if(this.strChartType == "decrease"){
                yVals[0] =intNewVal;
                yVals[1] = this.intLimit - intNewVal;
            }else{
                yVals[1] =intNewVal;
                yVals[0] = this.intLimit - intNewVal;
            }
            // if was over, make so not over 
            if(this.boolIsOver){
                this.boolIsOver = false;
                // change bg
                this.chart.data.datasets[0].backgroundColor[1] = this.strColorFg
            }

            // update chart regardless
            this.chart.data.datasets[0].data = yVals;
        }
        // update the ctx
        this.voidUpdateCtx()
        // update the chart
        this.chart.update()
    }
    voidUpdateCtx(){
        // so we can reference `this` object
        var self = this;

        this.chart.config.plugins[0].beforeDraw = function(chart,options){
            var w = chart.chart.width;
            var h = chart.chart.height;
            var ctx = chart.chart.ctx;
            var fontSize = h / 112;
            // the amount of nutrient consumed
            var usedNutrient = self.chart.data.datasets[0].data[1];
            var percentUsed = Math.ceil((usedNutrient/self.intLimit) * 100);

            ctx.font = fontSize + 'em sans-serif';
            ctx.textBaseline = 'middle';
            var text = percentUsed + "%";
            var textX = Math.round((w- ctx.measureText(text).width)/2);
            var textY = h / 2;
            ctx.fillText(text,textX,textY);
            ctx.save();
        }
    }
}
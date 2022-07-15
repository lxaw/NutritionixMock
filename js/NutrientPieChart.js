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

        // size of border
        this.intBorderWidth = 2

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
        this.chart = new Chart(this.strId,{
            type:'doughnut',
            data:{
                labels:['Remaining','Used'],
                datasets:[{
                    backgroundColor:[this.strColorBg,this.strColorFg],
                    data:[this.intLimit,0],
                    borderWidth:this.intBorderWidth,
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
                    var text = "0%";
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
        var yVals = this.chart.data.datasets[0].data

        if(intNewVal >= this.intLimit){
            // dont do anything as we are still above the limit
            // only change the bg color / border if not already changed
            //
            if(this.chart.data.datasets[0].backgroundColor[1] != this.strColorOver){
                // change to over
                this.chart.data.datasets[0].backgroundColor[1] = this.strColorOver;
                // update bool
                this.boolIsOver = true;
                // remove border
                this.chart.data.datasets[0].borderWidth = 0;

                // keep the y values as over; remain at 100% completion
                //
                yVals[0] = 0;
                yVals[1] = this.intLimit
            }
        }else{
            // else just continue
            // add to used and take away from remaining
            //
            yVals[1] =intNewVal;
            yVals[0] = this.intLimit - intNewVal;

            // if was over, make so not over 
            if(this.chart.data.datasets[0].backgroundColor[1] == this.strColorOver){
                this.boolIsOver = false;
                // change bg
                this.chart.data.datasets[0].backgroundColor[1] = this.strColorFg
                // give border again
                this.chart.data.datasets[0].borderWidth = this.intBorderWidth
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
        var yVals = this.chart.data.datasets[0].data
        var intLimit = this.intLimit
        this.chart.config.plugins[0].beforeDraw = function(chart,options){
            var w = chart.chart.width;
            var h = chart.chart.height;
            var ctx = chart.chart.ctx;
            var fontSize = h / 112;
            ctx.font = fontSize + 'em sans-serif';
            ctx.textBaseline = 'middle';
            var text = Math.ceil((yVals[1] / intLimit)*100) + "%";
            var textX = Math.round((w- ctx.measureText(text).width)/2);
            var textY = h / 2;
            ctx.fillText(text,textX,textY);
            ctx.save();
        }
    }
}
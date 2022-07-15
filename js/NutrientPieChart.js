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
    constructor(strTitle,strId,strChartType,strColorBg,strColorFg,strColorOver){
        this.strId = strId;
        this.strChartType=strChartType;
        this.strColorBg=strColorBg;
        this.strColorFg=strColorFg;
        this.strColorOver=strColorOver;
        this.strTitle=strTitle
    }
    /*
    Create the chart.
    */
    voidGenerateChart(){
        
    }
}
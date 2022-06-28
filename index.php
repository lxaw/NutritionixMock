<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- custom styles -->
    <link rel='stylesheet' href='./styles/styles.css' >
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <!-- bootstrap -->
    <link  href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
    <!-- chart.js -->
    <script
    src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
    </script>

    <title>Nutritionix Mock Demo</title>

    <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    ?>
        
</head>
<body>
    <div class = 'container shadow p-3'>
        <div class = 'row'>
            <div class = 'col-8'>
                <small>
                    Food Name
                </small>
                <div class="input-group">
                    <span class="input-group-text" id ="span__svg-clicker-holder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"></path>
                            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"></path>
                        </svg>
                    </span>
                    <input id = "input___food-name" 
                    placeholder = 'type at least 3 characters'
                    class = 'form-control' > 
                </div>
            </div>
            <div class = 'col-4'>
                <small>
                    Database Type
                </small>
                <select class = "form-select" id = "select__db-options">
                    <option selected value = "menustat">
                        <h2>
                            Menustat
                        </h2>
                    </option>
                    <option value = "usda_branded">
                        <h2>
                            USDA Branded
                        </h2>
                    </option>
                    <option value = "usda_non-branded">
                        <h2>
                            USDA Non-branded
                        </h2>
                    </option>
                </select>
            </div>
            <div class = 'col-6'>
            </div>
        </div>
        <br>
        <div>
            <h4>
                Results for 
                <strong><span id = "span__food-query">...</span></strong>
            </h4>
            <div class ='rounded'
                style='overflow-y:auto;max-height:15rem;height:15rem;'
                id = 'div__table-holder'
            >
                <table class = 'table table-hover' id = "table__food-search"
                >
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col" class = 'text-center'>Food Info</th>
                            <th scope="col" class=  'text-center'>Food Image</th>
                        </tr>
                    </thead>
                    <tbody id = "div__results-container">

                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <div>
            <div class = 'd-flex justify-content-between'>
                <div>
                    <h4>
                        Stored Foods
                    </h4>
                </div>
                <div>
                    <h4>
                        Total Kilocalories: <span id = "span__total-kcals" class = 'text-success'>0</span>
                    </h4>
                </div>
            </div>
            <div id = "div__saved-food-container"
            style="overflow-y:scroll;max-height:15rem;height:15rem;"
            >

            </div>
            <span id = 'span__hidden-offset' style='display:none'>
                0
            </span>
        </div>
        <div id = 'div__chart-holder'>
        <div id = "div__graph-container">
            <div class = 'row'>
                <div class= 'col-6'>
                    <div class = 'div__graph-section text-center
                    p-3 border rounded
                    ' data-name ='fat' data-limit='1200'
                    >
                    <div class = 'div__chart-holder'>
                        <canvas class = 'canvas__chart' id = "canvas__fat-totals"></canvas>
                        <h2 class = 'text__graph-title'>
                            Fat
                        </h2>
                    </div>
                    <br>
                </div>
            </div>
            <div class= 'col-6'>
                <div class = 'div__graph-section text-center
                p-3 border rounded
                ' data-name = "calorie" data-limit ='2300'
                >
                    <div class = 'div__chart-holder'>
                        <canvas class = 'canvas__chart'
                        id = "canvas__calorie-totals"></canvas>
                        <h2 class = 'text__graph-title'>
                            Calories
                        </h2>
                    </div>
                    <br>
                </div>
            </div>
        </div>
        </div>
        </div>
    </div> 

<!-- 
    For hidden menustat entry
-->
<?php
    include('templates/menustat/saved_entry.html');
?>

<!-- 
    For hidden branded entry
 -->
 <?php
    include('templates/usda_branded/saved_entry.html');
 ?>

<!-- 
    For hidden non-branded entry
 -->
<?php
    include('templates/usda_non_branded/saved_entry.html');
?>



</body>
<div id = "div__modal-holder">

</div>

</html>
<script>
    // store the chart objects and their limits
    // access like
    // __CHARTS[CHART_NAME]['chart'] -> gives the chart itself
    // __CHARTS[CHART_NAME]['limit'] -> gives the chart nutrient limit
    //
    var __CHARTS = {

    };
    // functions on start
    //
    $(document).ready(()=>{
        // allow for scrolling more
        //
        scrollMore();
        // set up charts
        //
        $('.div__graph-section').each((i,e)=>{
            // get name of chart
            var strName = $(e).attr('data-name');
            // the nutrient limit
            var intNutrientLimit = parseInt($(e).attr('data-limit'));
            // the chart id
            var strChartId = $(e).find('.canvas__chart').first().attr('id');
            var xVals = ['Remaining','Used'];
            var yVals = [intNutrientLimit,0];
            var pieColors = ['green','red'];
            var chart = new Chart(strChartId,{
                type:'doughnut',
                data:{
                    labels:xVals,
                    datasets:[{
                        backgroundColor:pieColors,
                        data:yVals,
                        borderWidth:0,
                        hoverBorderWidth:0,
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
                        var text = intNutrientLimit;
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
            __CHARTS[strName]= {'chart':chart,'limit':intNutrientLimit};
        })

    });

    // function to allow other function to 
    // be called after certain period of time
    // see:
    // https://stackoverflow.com/a/57763036/12939325
    function debounce(callback,wait){
        let timeout;
        return(...args)=>{
            clearTimeout(timeout);
            timeout = setTimeout(function(){callback.apply(this,args); }, wait);
        };
    }

    // add to saved button //
    function addToSavedClick(strDataType,modalModal,trE){
        // disable to table row
        $(trE).css('background-color','silver');
        // remove the onclick
        $(trE).attr("onclick","");

        $(modalModal).modal('hide');
        // depending on data type, change how saved info looks
        //
        // get name of food 
        let strFoodName = $(modalModal).find('.span__description').first().text();
        // get img src
        let strImgSrc = $(modalModal).find('.img__food-img').first().attr('src');

        // for saved entry
        let divSavedEntry = ""

        // kcal value to add
        let intAddKcals = null;
        // fat value to add
        
        if(strDataType == 'usda_non-branded'){
            divSavedEntry = $('#div__hidden-saved-entry-usda_non_branded').clone();
            // remove id
            divSavedEntry.attr('id','');

            // get info

            $.each((modalModal).find('.div__popup-data'),function(index,element){
                // get element that is visible
                if($(element).css('display') != 'none'){
                    // get info from element
                    //
                    let strKcals = $(element).find('.span__kilocalorie-amount').first().text();
                    let strServingSize = $(element).find('.span__serving-size').first().text();
                    // put in info into saved entry
                    //
                    divSavedEntry.find('.span__description').first().text(strFoodName);
                    divSavedEntry.find('.span__kilocalories').text(strKcals);
                    divSavedEntry.find('.span__serving-size').first().text(strServingSize);
                    divSavedEntry.find('.img__saved-entry').first().attr('src',strImgSrc);
                    // add the kcals to the total
                    //
                    intAddKcals = parseInt(strKcals)
                }
            });
        }else if(strDataType == 'menustat'){
            // get the div
            divSavedEntry = $('#div__hidden-saved-entry-menustat').clone();
            // remove id
            divSavedEntry.attr('id','');
            // get info
            $.each((modalModal).find('.div__popup-data'),function(index,element){
                // get element that is visible
                if($(element).css('display')!='none'){
                    // get info from element
                    //
                    let strRestaurant = $(element).find('.span__restaurant').first().text();
                    let strKcals = $(element).find('.span__kilocalorie-amount').first().text();
                    let strServingSize = $(element).find('.span__serving-size').first().text();
                    // put in info into saved entry
                    //
                    divSavedEntry.find('.span__description').first().text(strFoodName);
                    divSavedEntry.find('.span__kilocalories').text(strKcals);
                    divSavedEntry.find('.span__serving-size').first().text(strServingSize);
                    divSavedEntry.find('.img__saved-entry').first().attr('src',strImgSrc);
                    divSavedEntry.find('.span__restaurant').first().text(strRestaurant);

                    intAddKcals = parseInt(strKcals);
                }
            });
        }else if(strDataType == 'usda_branded'){
            // get the div
            divSavedEntry = $('#div__hidden-saved-entry-usda_branded').clone();
            // remove id
            divSavedEntry.attr('id','');
            // get info
            $.each((modalModal).find('.div__popup-data'),function(index,element){
                // get element that is visible
                if($(element).css('display')!='none'){
                    // get info from element
                    //
                    let strBrandOwner = $(element).find('.span__brand-owner').first().text();
                    let strKcals = $(element).find('.span__kilocalorie-amount').first().text();
                    let strServingSize = $(element).find('.span__serving-size').first().text();
                    // put in info into saved entry
                    //
                    divSavedEntry.find('.span__description').first().text(strFoodName);
                    divSavedEntry.find('.span__kilocalories').text(strKcals);
                    divSavedEntry.find('.span__serving-size').first().text(strServingSize);
                    divSavedEntry.find('.img__saved-entry').first().attr('src',strImgSrc);
                    divSavedEntry.find('.span__brand-owner').first().text(strBrandOwner);

                    intAddKcals = parseInt(strKcals);
                }
            });
        }else{
            alert('invalid db type');
            return;
        }
        addToTotalKcals(intAddKcals);
        // update the kcals graph
        addToChart(intAddKcals,__CHARTS['calorie']['limit'],__CHARTS['calorie']['chart']);
        // make visible
        //
        divSavedEntry.css('display','');
        // give a remove feature
        //
        divSavedEntry.find('.svg__x-clicker').first().click(()=>{
            // remove current kcals 
            //
            let intRemoveKcals= -1*parseInt(divSavedEntry.find('.span__kilocalories').first().text());
            addToTotalKcals(intRemoveKcals);
            // remove from graph
            addToChart(intRemoveKcals,__CHARTS['calorie']['limit'],__CHARTS['calorie']['chart'])

            // make the table row able to be clicked again
            // and update the background color
            //
            $(trE).attr('onclick','displayMore(this)');
            $(trE).css('background-color','');
            

            divSavedEntry.remove();
        });
        // when update Qty update kcal amount
        //
        divSavedEntry.find('.input__saved-entry-qty').first().change(()=>{
            // get original kcal amount
            let intOriginalKcals = parseInt(divSavedEntry.find('.span__hidden-kilocalories').first().text());
            // get current value
            let intCurrentKcals = parseInt(divSavedEntry.find('.span__kilocalories').first().text());

            // mulptiply it by the current value of input
            let intMultiplier = parseInt(divSavedEntry.find('.input__saved-entry-qty').first().val());
            // get new val
            let intNewVal = intOriginalKcals * intMultiplier;
            // update kcal count for individual entry
            divSavedEntry.find('.span__kilocalories').first().text(intNewVal);
            // update total kcal count by subtracting the current kcals and then adding the new
            //
            let intChange = intNewVal - intCurrentKcals;
            addToTotalKcals(intChange);
            // do the same thing with the graph
            addToChart(intChange,__CHARTS['calorie']['limit'],__CHARTS['calorie']['chart'])

        })
            
        // append to saved
        $('#div__saved-food-container').append(divSavedEntry);
    }


    /*
        Queries the db to provide more info on the food.
        Looks for match of description and restaurant.
        Or in the case of usda foods, the fdc_id
    */
    function displayMore(trE){
        var strFoodName = $(trE).attr('data-description');
        var strRestaurantName = $(trE).attr('data-restaurant');

        // consider the type of data
        // ie: menustat, usda_branded, usda_non_branded
        //
        var strDBType = $("#select__db-options").val();
        // if fdc_id present, get
        //
        var strFdcId = $(trE).attr('data-fdc-id');

        $.ajax({
            url: "php/funcs/query_info.php",
            type:'GET',
            dataType:'html',
            data:{
                strFoodName:strFoodName,
                strRestaurantName: strRestaurantName,
                strDBType:strDBType,
                strFdcId:strFdcId
            },
            success:function(data){
                // data is an array of
                // type 
                // {'data_type': STRING
                // 'templates': ARRAY}
                //

                // sanity check
                // console.log(data);
                let arrData = JSON.parse(data);

                // datatype
                //
                let strDataType = arrData['data_type'];
                // array of template entries
                // templates are just html
                //
                let strModal = arrData['modal'];

                // remove old results
                $('#div__modal-holder').html('');
                // add new results
                $("#div__modal-holder").html(strModal);

                // show modal
                $('#simpleModal').modal('toggle');
                // fix modal not closing
                $('#simpleModal').find('button').click(()=>{$('#simpleModal').modal('toggle')})

                // add on select change feature
                //
                $('#simpleModal').find('select').first().change(()=>{
                    // get the serving size
                    //
                    let intSelectedIndex =  $('#simpleModal').find('select').first().find(':selected').index();

                    // make all info divs invisible
                    //
                    $('#simpleModal').find('.div__popup-data').css('display','none');
                    // make the selected div visible
                    // replace all nonalphanumerics
                    // to do: likely better organization possible
                    //
                    let strModifiedFoodName =strFoodName.replace(new RegExp('[^a-zA-Z0-9]+','g'),'-');
                    let strDivTitle = '#div__popup-data-'+strModifiedFoodName+ '-' +intSelectedIndex;
                    $('#simpleModal').find(strDivTitle).css('display','');
                })
                // add a save feature
                //
                $('#simpleModal').find('.btn__add-to-saved').first().click(()=>{
                    addToSavedClick(strDBType,$('#simpleModal'),trE);
                })
                
            }
        }).done(function(response){
            console.log('success');
        }).fail(function(response){
            console.log('fail');
        })
    }


    // get names from db
    // this is the initial query, so no consideration of
    // scroll
    //
    function voidQueryNamesInitial(){
        // get name of food
        //
        var strFoodQuery = $("#input___food-name").val();
        // if less than 3 chars, don't do anything
        //

        if(strFoodQuery.length < 3){
            return;
        }
        // reset scroll position
        //
        $('#div__table-holder').scrollTop(0);

        // reset the hidden offset
        //
        $('#span__hidden-offset').text(0);

        // update the name of the query
        //
        $("#span__food-query").text(strFoodQuery!="" ? strFoodQuery : "...");

        // query the db based on if menustat, branded usda, or reg usda selected
        //
        var strDBType = $("#select__db-options").val();

        console.log('querying db: ' + strDBType + ' for ' + strFoodQuery);

        $.when(ajaxGetFoodSearchResults(strFoodQuery,strDBType,0)).done(
            function(arrFoods, textStatus, jqXHR){
                $('#div__results-container').html(arrFoods);
            }
        )
    };

    // get names from db
    // input: intOffset
    // output: array of table entries (html)
    // desc:
    // intOffset is the offset value for mysql.
    // this function is used to query regular entries and 
    // also when scrolling
    function ajaxGetFoodSearchResults(strFoodName,strDBType,intOffset){
        console.log('querying: '+ strDBType + ' for ' + strFoodName);

        return $.ajax({
            url:'php/funcs/query_names.php',
            type:'GET',
            dataType:'html',
            data:{
                strQuery:strFoodName,
                strDBType:strDBType,
                intOffset:intOffset
            },
            success:function(data){
                // return the data
                //
                return data;
            }
        }).done((response)=>{
            console.log('success');
        }).fail((response)=>{
            console.log('fail');
        })
    }

    // if scroll to end of div give more results
    //
    function scrollMore(){
        $('#div__table-holder').scroll(()=>{
            var divTableHolder = $('#div__table-holder');
            var intScrollH = divTableHolder.prop('scrollHeight');
            var intDivHeight = divTableHolder.height();
            var intScrollerEndPoint = intScrollH - intDivHeight;
            var intDivScrollerTop = divTableHolder.scrollTop();

            if(intDivScrollerTop === intScrollerEndPoint){
                // query more
                //
                // get food name, db type, offset amount
                //
                var strFoodName = $('#input___food-name').val();
                var strDBType = $('#select__db-options').val();
                // TO DO:
                // make a constant value for the offset
                // this 20 comes from db_config.php
                //
                var intNewOffset= parseInt($('#span__hidden-offset').text()) + 20;
                // need to update the offset
                $('#span__hidden-offset').text(intNewOffset);
                // console.log('old offset: '$(''))

                $.when(ajaxGetFoodSearchResults(strFoodName, strDBType,intNewOffset)).done(
                    function(arrFoods,textStatus,jqXHR){
                        // append the data
                        //
                        $('#div__results-container').append(arrFoods);
                    }
                )
            }
        })
    }

    // update a chart with an added value
    //
    function addToChart(intAddVal,intLimit,chartChart){
        var yVals = chartChart.data.datasets[0].data;
        // add to used and take away from remaining
        yVals[1] += intAddVal;
        yVals[0] -= intAddVal;

        if(yVals[1] > intLimit){
            // if greater than limit, do something
            //
            alert('over limit');
        }else{
            // else you just update chart
            chartChart.data.datasets[0].data = yVals;
            // update the ctx
            chartChart.config.plugins[0].beforeDraw = function(chart,options){
                var w = chart.chart.width;
                var h = chart.chart.height;
                var ctx = chart.chart.ctx;
                var fontSize = h / 112;
                ctx.font = fontSize + 'em sans-serif';
                ctx.textBaseline = 'middle';
                var text = yVals[0];
                var textX = Math.round((w- ctx.measureText(text).width)/2);
                var textY = h / 2;

                ctx.fillText(text,textX,textY);
                ctx.save();
            }

            chartChart.update();
        }
    };

    // add a kcal value to the total kcals
    //
    function addToTotalKcals(intVal){
        // adds to span__total-kcals value
        //
        // get original value
        let intOriginalValue = parseInt($("#span__total-kcals").text());
        let intNewVal = intOriginalValue + intVal;
        // set new val
        $("#span__total-kcals").text(intNewVal);
    }

    // on click clockwise clicker query
    //
    $('#span__svg-clicker-holder').click(()=>{
        voidQueryNamesInitial();
    })

    // on keyup query db
    //
    $("#input___food-name").keyup(debounce( ()=>{
            voidQueryNamesInitial();
        },1000
    ));

    // on change of select query db
    //
    $("#select__db-options").change(()=>{
        voidQueryNamesInitial();
    })
    // on change of select query db 
    //
    $("#input__per-page").change(()=>{
        voidQueryNamesInitial();
    })

</script>
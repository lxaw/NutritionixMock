<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Menustat Demo</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    ?>
        
</head>
<body>
    <br>
        <div class = 'container'>
            <input id = "input__food-info" class = 'form-control' > 

            <hr>
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
            <select id = "input__per-page">
                <option selected value = "5">
                    5
                </option>
                <option value = "10">
                    10
                </option>
                <option value = "20">
                    20
                </option>
            </select>
            <br>
            <div class = 'container m-3 p-3'>
                <h4>
                    Stored Foods
                </h4>
                <hr>
                <div class = 'container overflow-scroll' id = "div__stored-food-container">

                </div>
            </div>
            <br>
            <div>
                <h4>
                    Raw Results for <span id = "span__food-query">...</span>
                </h4>
                <table class = 'table table-striped table-hover'>
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Food Info</th>
                            <th scope="col">Food Image</th>
                        </tr>
                    </thead>
                    <tbody id = "div__results-container">

                    </tbody>
                </table>
            </div>
        </div> 
<!--  
    For modal
-->
    <div id = "div__modal-holder">

    </div>

<!-- 
    For hidden menustat entry
-->
<?php
    include('templates/general/menustat_entry.html');
?>

<!-- 
    For hidden branded entry
 -->

<!-- 
    For hidden non-branded entry
 -->


</body>

</html>
<script>
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
    function addToSavedClick(e){
        console.log(e.children);
    }


    // on keyup query db
    //
    $("#input__food-info").keyup(debounce( ()=>{
            queryDb();
        },1000
    ));

    // on change of select query db
    //
    $("#select__db-options").change(()=>{
        queryDb();
    })
    // on change of select query db 
    //
    $("#input__per-page").change(()=>{
        queryDb();
    })


    function displayMore(trE){
        /*
        Queries the db to provide more info on the food.
        Looks for match of description and restaurant.
        */
        var strFoodName = $(trE).attr('data-description');
        var strRestaurantName = $(trE).attr('data-restaurant');
        var strDBType = $("#select__db-options").val();
        // if fdc_id present, get
        var strFdcId = $(trE).attr('data-fdc-id');

        console.log('querying for: '+ strFoodName + " from " + strRestaurantName);

        $.ajax({
            url: "php_funcs/query_info.php",
            type:'GET',
            dataType:'html',
            data:{
                strFoodName:strFoodName,
                strRestaurantName: strRestaurantName,
                strDBType:strDBType,
                strFdcId:strFdcId
            },
            success:function(data){
                // writes data to results div
                //

                // remove old results
                $('#div__modal-holder').html('');
                // add new results
                $("#div__modal-holder").html(data);

                // show modal
                $('#simpleModal').modal('show');

                $('#simpleModal').find('.btn__add-to-saved').first().click(()=>{
                    let divClone = $('#div__hidden-saved-entry').clone();
                    let modalModal = $('#simpleModal');
                    // remove id
                    //
                    divClone.attr('id','');
                    // get img src
                    //
                    let imgSrc = modalModal.find('.img__food-img').first().attr('src');
                    // get restaurant
                    //
                    let strRestaurantName = modalModal.find('.span__restaurant').first().text();
                    // get servingsize
                    //
                    let strServingSize = modalModal.find('.span__serving-size').first().text();
                    // get unit
                    //
                    let strServingSizeUnit = modalModal.find('.span__serving-size-unit').first().text();
                    // get cals
                    //
                    let strCalories = modalModal.find('.span__calories').first().text();
                    // get name
                    //
                    let strDescription = modalModal.find('.span__description').first().text();

                    divClone.find('.img__food-img').attr('src',imgSrc);
                    divClone.find('.span__restaurant').first().text(strRestaurantName);
                    divClone.find('.span__description').first().text(strDescription)
                    divClone.find('.span__kilocalories').first().text(strCalories);
                    divClone.find('.span__serving-size').first().text(strServingSize);
                    divClone.find('.span__serving-size-unit').first().text(strServingSizeUnit);

                    // display it
                    //
                    divClone.css('display','');

                    $('#div__stored-food-container').append(divClone);
                })
                
            }
        }).done(function(response){
            console.log('success');
        }).fail(function(response){
            console.log('fail');
        })
    }

    // get names from db
    //
    function queryDb(){
        // get name of food
        //
        var strFoodQuery = $("#input__food-info").val();
        // get amount per page
        //
        var intPerPage = $("#input__per-page").val();
        // update the name of the query
        //
        $("#span__food-query").text(strFoodQuery!="" ? strFoodQuery : "...");

        // if no food query, don't do anything
        if(strFoodQuery == ""){
            // clear the results
            $("#div__results-container").html("");
            return;
        }

        // query the db based on if menustat, branded usda, or reg usda selected
        //
        var strDBType = $("#select__db-options").val();

        $.ajax({
            url: "php_funcs/query_names.php",
            type:'GET',
            dataType:'html',
            data:{
                strQuery:strFoodQuery,
                strDBType:strDBType,
                intPerPage:intPerPage
            },
            success:function(data){
                // writes data to results div
                //
                $("#div__results-container").html(data);
            }
        }).done(function(response){
            console.log('success');
        }).fail(function(response){
            console.log('fail');
        })
    }

</script>
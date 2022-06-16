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
    <title>Nutritionix Mock Demo</title>

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
                <div class = 'container overflow-scroll' id = "div__saved-food-container">

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
    include('templates/menustat/menustat_saved_entry.html');
?>

<!-- 
    For hidden branded entry
 -->

<!-- 
    For hidden non-branded entry
 -->
<?php
    include('templates/usda_non_branded/usda_non_branded_saved_entry.html');
?>


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
    function addToSavedClick(strDataType,modalModal){
        // depending on data type, change how saved info looks
        //
        
        switch(strDataType){
            // we need to get the info from the modal
            // and put it in the saved entry
            //
            
            case 'usda_non_branded':
                let divSavedEntry = $('#div__hidden-saved-entry-usda_non_branded').clone();
                // remove id
                divSavedEntry.attr('id','');

                // get info
                let strDescription = modalModal.find('.span__description').first().text();
                let strPortionModifier = modalModal.find('.span__portion_modifier').first().text();
                let strKcals = modalModal.find('.span__kilocalories').first().text();

                console.log(strKcals);

                break;
            case 'usda_branded':
                break;
            case 'menustat':
                break;
            default:
                // incorrect datatype, do nothing
                console.log('error: addToSavedClick\ninvalid datatype');
                break;
        }
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
                console.log(data);
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
                $('#simpleModal').modal('show');

                // add on select change feature
                //
                $('#simpleModal').find('select').first().change(()=>{
                    // get the serving size
                    //
                    let strServingSize =  $('#simpleModal').find('select').first().find(':selected').val();

                    let strServingSizeNoSpace = strServingSize.replaceAll(' ','-');
                    alert(strServingSizeNoSpace);

                    // make all info divs invisible
                    //
                    $('#simpleModal').find('.div__popup-data').css('display','none');
                    // make the selected div visible
                    // note that id must have no spaces, so we replace spaces with '-'
                    //
                    $('#simpleModal').find('#div__popup-data-'+strServingSizeNoSpace).css('display','');
                })
                // add a save feature
                //
                $('#simpleModal').find('.btn__add-to-saved').first().click(()=>{
                    addToSavedClick(strDataType,$('#simpleModal'));
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
            url: "php/funcs/query_names.php",
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
    };

</script>
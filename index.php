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
        <div class = 'container shadow' style='height:50rem'>
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
                    style='overflow-y:auto;max-height:15rem;'
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
                <h4>
                    Stored Foods
                </h4>
                <div id = "div__saved-food-container"
                style="overflow-y:scroll;max-height:15rem;"
                >

                </div>
                <span id = 'span__hidden-offset' style='display:none'>
                    0
                </span>
            </div>
            <div class = 'd-flex justify-content-end'>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Submit
                </button>


            </div>
        </div> 
<!-- 
    Modal 
    TO DO: only really need one modal here, just keep swapping out things
-->
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Food Submission</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body" style="overflow-y:auto;max-height:6rem">

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
    </div>
    </div>
</div>
</div> -->

<!--  
    For modal
-->
    <div id = "div__modal-holder">

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

</html>
<script>

    // functions on start
    //
    $(document).ready(()=>{
        scrollMore();
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
    function addToSavedClick(strDataType,modalModal){
        // depending on data type, change how saved info looks
        //
        // get name of food 
        let strFoodName = $(modalModal).find('.span__description').first().text();
        // get img src
        let strImgSrc = $(modalModal).find('.img__food-img').first().attr('src');
        // for saved entry
        let divSavedEntry = ""
        
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
                }
            });
        }else{
            console.log('saved entry: invalid db type');
            return;
        }
        // make visible
        //
        divSavedEntry.css('display','');
        // give a remove feature
        //
        divSavedEntry.find('.svg__x-clicker').first().click(()=>{
            divSavedEntry.remove();
        });
        // when update Qty update kcal amount
        //
        divSavedEntry.find('.input__saved-entry-qty').first().change(()=>{
            // get original kcal amount
            let floatOriginalKcals = parseInt(divSavedEntry.find('.span__hidden-kilocalories').first().text());
            // mulptiply it by the current value of input
            let intMultiplier = parseInt(divSavedEntry.find('.input__saved-entry-qty').first().val());
            // update kcal count
            divSavedEntry.find('.span__kilocalories').first().text(floatOriginalKcals * intMultiplier);
        })
            
        // append to saved
        $('#div__saved-food-container').append(divSavedEntry);
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
        console.log(strDBType);
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
                $('#simpleModal').modal('show');

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
                    addToSavedClick(strDBType,$('#simpleModal'));
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

                console.log('end of scroll');
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

</script>
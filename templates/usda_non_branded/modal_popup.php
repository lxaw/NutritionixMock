
<div id="simpleModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">[description] details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container m-3 p-3">
                    <img src="[img_src]" alt="img" class = 'img-thumbnail img__food-img'>
                </div>
                <p>
                    Servings: [serving_amount] [portion_modifier]
                    In grams: [portion_gram_weight]
                </p>
                <p>
                    Carbohydrate: [carbohydrate] [carbohydrate_unit]
                </p>
                <p>
                    Fat: [fat] [fat_unit]
                </p>
                <p>
                    Protein: [protein] [protein_unit]
                </p>
                <p>
                    KCals: <span class = 'span__calories'>[calories] </span>
                </p>
            </div>
            <div class="modal-footer">
                <div class = "d-flex justify-content-between">
                    <button type = "button" class = "btn btn-primary btn__add-to-saved">
                        Add to Saved
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

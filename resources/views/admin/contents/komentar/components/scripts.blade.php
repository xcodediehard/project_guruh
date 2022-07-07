<script>
    function dynamical_inputs(attribute_add,attribute_delete,new_content) {
        $(`.${attribute_add}`).on("click", function () {
            let html = `<div class="row_data_${attribute_delete}">
                            <div class="input-group mb-1">
                                <div class="input-group-prepend">
                                    <button class="btn btn-primary ${attribute_delete}" id="${attribute_delete}" type="button">
                                        <i class="bi bi-trash"></i>
                                        Delete
                                    </button>
                                </div>
                                <input type="number" class="form-control m-input border border-primary" placeholder="Size" name="size[]">
                                <input type="number" class="form-control m-input border border-danger" placeholder="Stok" name="stok[]">
                            </div>
                        </div>`
            $(`.${new_content}`).append(html);
        });

        $(document).on('click', `.${attribute_delete}`, function () {
        $(this).closest(`.row_data_${attribute_delete}`).remove();
    });
    }
    dynamical_inputs("add_data","add_delete","newinput")
    dynamical_inputs("update_data","update_delete","updatenewinput")
    $('#inputGroupFile01').on('change',function(){
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    })
</script>
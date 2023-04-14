// TOGGLE
let counter = 1;
$("#formToggle").click(function (e) {
    e.preventDefault();
    if (counter % 2 === 0) {
        $(this).removeClass("fa-angle-down").addClass("fa-angle-up");
    } else {
        $(this).addClass("fa-angle-down").removeClass("fa-angle-up");
    }
    $("#input-form div.flex").toggle();
    counter++;
});

// FLASH MASSAGE
$("#flash-close").click(function (e) {
    e.preventDefault();
    $("#flash").hide();
});

// LIVE SEARCH
$("#keyword").keyup(function (e) {
    search();
});

function search() {
    let keyword = $("#keyword").val();
    $.post(
        `/product/search`,
        {
            _token: $('meta[name="csrf-token"]').attr("content"),
            keyword: keyword,
        },
        function (data) {
            tableRows(data);
            // console.log(data);
        }
    );
}

function tableRows(response) {
    let htmlView = "";
    if (response.product.length <= 0) {
        htmlView += `
            <tr>
                <td colspan="6" class="text-center pt-3 text-gray-600">No data.</td>
            </tr>`;
    }
    for (let i = 0; i < response.product.length; i++) {
        htmlView +=
            `
            <tr class="border-t border-b text-center">
                <td class="p-2">` +
                (i + 1) +
                `</td>
                <td class="p-2">` +
                response.product[i].code +
                `</td>
                <td class="p-2 w-80">` +
                response.product[i].name +
                `</td>
                <td class="p-2">` +
                response.product[i].dimension +
                `</td>
                <td class="p-2">` +
                response.product[i].unit +
                `</td>
                <td class="p-2">
                    <button value=` + 
            response.product[i].id + ` class="edit-button"><i class="fa-regular fa-pen-to-square text-gray-400 hover:text-gray-600 text-lg px-3"></i></button>
                    <button value=` +
            response.product[i].id +
            `" class="delete-button"><i class="fa-solid fa-trash hover:text-[#144272] text-[#2C74B3] text-lg px-3"></i></button>
                </td>
            </tr>`;
    }
    $("tbody").html(htmlView);
    deleteProduct();
    editProduct();
}

// DELETE
function deleteProduct() {
    $(".delete-button").click(function (e) {
        e.preventDefault();
        let id = $(this).val();
        
        $("#delete-wrap").show();

        $.get('/product/' + id, function(data) {
            $('#product-name-delete').html(data.name);
        })
        
        $("#delete-form").attr("action", "/product/"+id);
    });

    $("#delete-close").click(function (e) {
        e.preventDefault();
        $("#delete-wrap").hide();
    });
}
deleteProduct();

// EDIT
function editProduct() {
    $(".edit-button").click(function (e) {
        e.preventDefault();
        var id = $(this).val();
    
        $.get(`/product/`+id, function(data, status) {
            $("#edit-wrap input[name='edit-code']").val(data.code);
            $("#edit-wrap input[name='edit-name']").val(data.name);
            $("#edit-wrap input[name='edit-dimension']").val(data.dimension);
        });
    
        $("#edit-wrap").show();
    
        $("#edit-form").attr("action", "/product/"+id);
    });
    
    $("#edit-close").click(function (e) {
        e.preventDefault();
        $("#edit-wrap").hide();
    });
}
editProduct();

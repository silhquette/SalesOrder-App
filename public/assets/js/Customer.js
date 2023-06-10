// FLASH MESSAGE
$("#flash-close").click(function (e) {
e.preventDefault();
$("#flash").hide();
});

// DELETE
function deleteCustomer() {
    $(".delete-button").click(function (e) {
        e.preventDefault();
        let id = $(this).val();
        
        $("#delete-wrap").show();

        $.get('/customer/' + id, function(data) {
            $('#customer-name-delete').html(data.name);
            // console.log(data);
        })
        
        $("#delete-form").attr("action", "/customer/"+id);
    });

    $("#delete-close").click(function (e) {
        e.preventDefault();
        $("#delete-wrap").hide();
    });
}
deleteCustomer();

// LIVE SEARCH
$("#keyword").keyup(function (e) {
    search();
});

function search() {
    let keyword = $("#keyword").val();
    $.post(
        `/customer/search`,
        {
            _token: $('meta[name="csrf-token"]').attr("content"),
            keyword: keyword,
        },
        function (data) {
            tableRows(data);
        }
    );
}

function tableRows(response) {
    let htmlView = "";
    if (response.customer.length <= 0) {
        htmlView += `
            <tr>
                <td colspan="7" class="text-center pt-3 text-gray-600">No data.</td>
            </tr>`;
    }
    for (let i = 0; i < response.customer.length; i++) {
        htmlView +=
            `
            <tr class="border-t border-b text-center">
                <td class="p-2 w-20">` + (i+1) + `</td>
                <td class="p-2 w-36">` + response.customer[i].code + `</td>
                <td class="p-2">` + response.customer[i].name + `</td>
                <td class="p-2">` + response.customer[i].npwp + `</td>
                <td class="p-2">` + (response.customer[i].contact ? response.customer[i].contact : '') + `</td>
                <td class="p-2">` + response.customer[i].address.slice(0, 18) + (response.customer[i].address.length > 18 ? "..." : "") + `</td>
                <td class="p-2 w-3">
                    <a class="edit-button" href="/customer/` + response.customer[i].code + `/edit"><i class="fa-regular fa-pen-to-square text-gray-400 hover:text-gray-600 text-lg px-3"></i></a>
                    <button value="` + response.customer[i].code + `" class="delete-button"><i class="fa-solid fa-trash hover:text-[#144272] text-[#2C74B3] text-lg px-3"></i></button>
                </td>
            </tr>
            `;
    }
    $("tbody").html(htmlView);
    deleteCustomer();
}

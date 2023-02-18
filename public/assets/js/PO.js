function load_js() {
    // CLOSE FLASH
    $("#flash-close").click(function (e) {
        e.preventDefault();
        $("#flash").hide();
    });

    // CLOSE DETAILS
    $("#detail-close").click(function (e) {
        e.preventDefault();
        $("#detail-wrap").hide();
    });

    // OPEN DETAILS
    $(".show-button").click(function (e) {
        e.preventDefault();
        const BUTTON_VALUE = $(this).val();
        $.get("order/" + BUTTON_VALUE, function (data, textStatus, jqXHR) {
            const CREATED = DateFormat(data.created_at);
            const DUE = DateFormat(data.due_time);

            $("#detail-wrap").show();
            $("#order-number-customer").html(data.order_code);
            $("#term-customer").html(`Created at ${CREATED} | Due on  ${DUE}`);
            $("#nama-customer").html(data.customer.name);
            $("#npwp-customer").html(data.customer.npwp);
            $("#alamat-customer").html(data.customer.address);
            $("#kontak-customer").html(data.customer.contact ? data.customer.contact : "-");
            $("#email-customer").html(data.customer.email ? data.customer.email : "-");
            $("#delete-button").val(data.order_code);
            $("#print-surat-jalan").attr("href", "/order/surat-jalan/" + data.order_code);
            $("#print-invoice").attr("href", "/order/print-invoice/" + data.order_code);

            let htmlView = "";
            let sub_total = 0;
            let item_summary = 0;
            $.map(data.orders, function (value, key) {
                htmlView +=
                `<tr class="text-center">
                    <td class="text-left pl-16">
                        <div class="text-lg font-semibold">${value.product.name}</div>
                        <div class="text-sm">${value.product.dimension}</div>
                        <span class="text-gray-600 text-sm">${value.product.code}</span>
                    </td>
                    <td>${toRupiah(value.price, {spaceBeforeUnit: true})}</td>
                    <td>${value.qty}</td>
                    <td>disc ${value.discount}%<i class="fa-solid fa-tag ml-2"></i></td>
                    <td>${toRupiah(value.amount, {spaceBeforeUnit: true})}</td>
                </tr>`;
                sub_total += parseInt(value.amount);
                item_summary++;
            });
            $("#ordered-item").html(htmlView);
            $("#item-summary").html(item_summary + " items ordered");

            $("#sub-total").html(toRupiah(sub_total, {spaceBeforeUnit: true}));
            $("#ppn").html(data.ppn + '%'); 
            $("#amount").html(toRupiah(data.total, {spaceBeforeUnit: true}));
        });

    });

    //  OPEN DELETE
    $("#delete-button").click(function (e) { 
        e.preventDefault();
        $("#delete-wrap").show();
        $("#delete-form").attr("action", "/order/" + $(this).val());
    });

    // CLOSE DELETE
    $("#delete-close").click(function (e) {
        e.preventDefault();
        $("#delete-wrap").hide();
    });
} load_js();

function DateFormat(date) {
    let new_date = new Date(date);
    const YE = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(new_date);
    const MO = new Intl.DateTimeFormat('en', { month: 'short' }).format(new_date);
    const DA = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(new_date);
    new_date = `${DA} ${MO} ${YE}`;
    return new_date;
}

// LIVE SEARCH
$("#keyword").keyup(function (e) {
    search();
});

function search() {
    let keyword = $("#keyword").val();
    $.post(
        `/order/search`,
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
    if (response.purchaseOrder.length <= 0) {
        htmlView += `
            <tr>
                <td colspan="8" class="text-center pt-3 text-gray-600">No data.</td>
            </tr>`;
    }
    for (let i = 0; i < response.purchaseOrder.length; i++) {
        htmlView +=
            `
            <tr class="border-t border-b text-center">
                <td class="p-2">` + (i+1) + `</td>
                <td class="p-2">` + response.purchaseOrder[i].order_code + `</td>
                <td class="p-2">` + response.purchaseOrder[i].delivery_order + `</td>
                <td class="p-2">` + response.purchaseOrder[i].customer.name + `</td>
                <td class="p-2">` + response.purchaseOrder[i].customer.address.slice(0, 10) + (response.purchaseOrder[i].customer.address.length > 18 ? "..." : "") + `</td>
                <td class="p-2">` + toRupiah(response.purchaseOrder[i].total, {spaceBeforeUnit: true}) + `</td>
                <td class="p-2">` + DateFormat(response.purchaseOrder[i].due_time) + `</td>
                <td class="p-2">
                    <button value="` + response.purchaseOrder[i].order_code + `" class="show-button"><i class="fa-solid fa-eye hover:text-[#144272] text-[#2C74B3] text-lg px-3"></i></button>
                </td>
            </tr>
            `;
    }
    $("tbody#main-table").html(htmlView);
    load_js();
}

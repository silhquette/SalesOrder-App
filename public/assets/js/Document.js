// FLASH MASSAGE
$("#flash-close").click(function (e) {
    e.preventDefault();
    $("#flash").hide();
});

// DELETE
function deleteDocument() {
    $(".delete-button").click(function (e) {
        e.preventDefault();        
        $("#delete-wrap").show();
        $("#delete-form").attr("action", "/document/" + $(this).val());
    });

    $("#delete-close").click(function (e) {
        e.preventDefault();
        $("#delete-wrap").hide();
    });
}
deleteDocument();

const CURRENT_URL = window.location.href;
const DOCUMENT_CODE = CURRENT_URL.split("/")[4];
$("#surat-jalan-preview").attr("src", "/document/print-surat-jalan/" + DOCUMENT_CODE);
$("#invoice-preview").attr("src", "/document/print-invoice/" + DOCUMENT_CODE);

$(".check-box:checked").parent().siblings(".keterangan-wrap").children(".keterangan").removeAttr("disabled");
$(".check-box").click(function(e) {
    let keterangan = $(this).parent().siblings(".keterangan-wrap").children(".keterangan");
    if (! $(this).is(":checked")) {
        keterangan.val("");
        keterangan.attr("disabled","true");
    } else {
        keterangan.removeAttr("disabled");
    };
})


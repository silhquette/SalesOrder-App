const CURRENT_URL = window.location.href;
const ORDER_NUMBER = CURRENT_URL.split("/")[5];
console.log(ORDER_NUMBER);
$("#surat-jalan-preview").attr("src", "/doc/print-surat-jalan/" + ORDER_NUMBER);
$("#invoice-preview").attr("src", "/doc/print-invoice/" + ORDER_NUMBER);
const CURRENT_URL = window.location.href;
const ORDER_NUMBER = CURRENT_URL.split("/")[5];
console.log(ORDER_NUMBER);
$("#preview").attr("src", "/order/print-surat-jalan/" + ORDER_NUMBER);
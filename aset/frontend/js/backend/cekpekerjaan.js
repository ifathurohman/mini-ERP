function disable() {
    var x = document.getElementById("select_work");
   /* alert(x.getAttribute("disabled"));
    x.setAttribute("disabled", "true");
    alert(x.getAttribute("disabled"));*/
    document.getElementById("select_work").disabled= true;
}
document.getElementById("Cekpakerjaan").addEventListener("onclick", disable);
document.getElementById("Cekpakerjaan").addEventListener("onclick", enable);
function enable() {
    var x = document.getElementById("Cekpakerjaan").disabled= false;
   /* alert(x.getAttribute("disabled"));
    x.setAttribute("disabled", "false");
    alert(x.getAttribute("disabled"));*/
}
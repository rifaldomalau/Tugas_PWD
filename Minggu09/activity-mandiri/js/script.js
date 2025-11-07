function fn_ValForm() {
    var sMsg = ""
    var emailPattern = /^[a-z0-9][a-z0-9_\.-]{0,}[a-z0-9]@[a-z0-9][a-z0-9_\.-]{0,}[a-z0-9][\.][a-z0-9]{2,4}$/;
    
    if (document.getElementById("name").value == "") {
        sMsg += "\n* Anda belum mengisikan nama";
    }
    
    if (document.getElementById("email").value.test == "") {
        sMsg += "\n* Anda belum mengisikan email";
    } 
    else if (!emailPattern.test(document.getElementById("email").value)) {
        sMsg += "\n* Format email tidak valid";
    }

    if (document.getElementById("message").value == "") {
        sMsg += "\n* Anda belum mengisikan pesan";
    }

    if (sMsg != "") {
        alert("Peringatan:\n" + sMsg);
        return false;
    } else {
        return true;
    }
}
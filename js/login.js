/**
 * Created by hullberg on 20/02/17.
 */


function loginValidate(opt) {

    // opt depending on security measures.


    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    // Look at database
    if ( username == "Formget" && password == "formget#123"){
        //alert ("Login successfully");
        window.location = "index.html"; // Redirecting to other page.
        return false;
    }
}
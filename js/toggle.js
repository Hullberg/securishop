/**
 * Created by hullberg on 20/02/17.
 */


function toggle(button, id)
{
    if(document.getElementById(id).value=="OFF"){
        document.getElementById(id).value="ON";

        if (id == 'm1') {
            // Prevent SQL injection

        }
        else if (id == 'm2') {
            // Prevent XSS

        }
        else if (id == 'm3') {
            // Encrypt

        }
    }

    else if(document.getElementById(id).value=="ON"){
        document.getElementById(id).value="OFF";

        if (id == 'm1') {
            // Allow SQL injection

        }
        else if (id == 'm2') {
            // Allow XSS

        }
        else if (id == 'm3') {
            // Decrypt

        }

    }
}
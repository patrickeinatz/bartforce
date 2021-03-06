$(function()
{
    var expanded = false;
    $('#toggler').click(function()
    {
        if (expanded)
        {
            $('#content').animate({'top' : '0px'}, {duration : 250});
            expanded = false;
        }
        else
        {
            $('#content').animate({'top' : '320px'}, {duration: 250});
            expanded = true;
        }

    });
});

// ALERTS
$("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
    $("#success-alert").slideUp(500);
});

$(document).ready(function() {
    $("#content").markRegExp(/([@]|[#])([a-z])\w+/gmi);
});

// BOT
var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        // Typical action to be performed when the document is ready
        console.log(JSON.stringify(xhttp.responseText));
    }
};
xhttp.open("GET", "https://bot.bartforce.de", true);
xhttp.send();
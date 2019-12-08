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
            $('#content').animate({'top' : '290px'}, {duration: 250});
            expanded = true;
        }

    });
});

// ALERTS
$("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
    $("#success-alert").slideUp(500);
});

// WYSIWYG Editor
$('.trumbowyg').trumbowyg({
    btns: [
        ['viewHTML'],
        ['formatting'],
        ['strong', 'em', 'del'],
        ['link'],
        ['insertImage'],
        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
        ['unorderedList', 'orderedList'],
        ['removeformat']
    ],
    autogrow: true
});

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
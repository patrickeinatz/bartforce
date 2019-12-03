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
$('.trumbowyg').trumbowyg();


function prepareNewPost(topicId)
{
    var forms = document.querySelectorAll('form');

    for (var i =0; i < forms.length; i++){
        if(forms[i].name === "forum_post") {
            var action = forms[i].getAttribute('action');
            forms[i].action = action.replace('%action%', '/forum/topic/'+topicId);
        }
    }
}

function reparePostReply(postId)
{
    var forms = document.querySelectorAll('form');

    for (var i =0; i < forms.length; i++){
        if(forms[i].name === "forum_reply") {
            var action = forms[i].getAttribute('action');
            forms[i].action = action.replace('%action%', '/forum/postReply/'+postId);
        }
    }
}

function preparePostUpdate(postContent, postId)
{
    var divs = document.querySelectorAll('div');

    for (var i =0; i < divs.length; i++) {
        if(divs[i].className == "trumbowyg-editor"){
            divs[i].innerHTML = postContent;
        }
    }
    var forms = document.querySelectorAll('form');

    for (var i =0; i < forms.length; i++){
        if(forms[i].name === "forum_post") {
            var action = forms[i].getAttribute('action');
            forms[i].action = action.replace('%action%', '/forum/postUpdate/'+postId);
        }
    }

}

function prepareTopicUpdate(topicTitle, topicContent, topicId)
{
    var divs = document.querySelectorAll('div');
    var inputs = document.querySelectorAll('input');

    for (var i =0; i < divs.length; i++) {
        if(divs[i].className == "trumbowyg-editor"){
            divs[i].innerHTML = topicContent;
        }
    }

    for (var j =0; j < inputs.length; j++) {
        if(inputs[j].name == "forum_topic[title]"){
            inputs[j].value = topicTitle;
        }
    }

    var forms = document.querySelectorAll('form');

    for (var i =0; i < forms.length; i++){
        if(forms[i].name === "forum_topic") {
            var action = forms[i].getAttribute('action');
            forms[i].action = action.replace('%action%', '/forum/topicUpdate/'+topicId);
        }
    }
}


function createCategory()
{
    var forms = document.querySelectorAll('form');

    for (var i =0; i < forms.length; i++){
        if(forms[i].name === "forum_category") {
            var action = forms[i].getAttribute('action');
            forms[i].action = action.replace('%action%', '/forum/createCategory');
        }
    }
}

function createTopic(catId)
{
    var forms = document.querySelectorAll('form');
    var title = document.getElementById('topicModalTitle');
    title.innerHTML = "Neues Thema eröffnen";

    for (var i =0; i < forms.length; i++){
        if(forms[i].name === "forum_topic") {
            var action = forms[i].getAttribute('action');
            forms[i].action = action.replace('%action%', '/forum/'+catId+'/createTopic');
        }
    }
}

function createPost(catId, topicId)
{
    var forms = document.querySelectorAll('form');

    for (var i =0; i < forms.length; i++){
        if(forms[i].name === "forum_post") {
            var action = forms[i].getAttribute('action');
            forms[i].action = action.replace('%action%', '/forum/'+catId+'/'+topicId+'/createPost');
        }
    }
}

function createReply(topicId, postId)
{
    var forms = document.querySelectorAll('form');

    var title = document.getElementById('postModalTitle');
    title.innerHTML = "Neuen Beitrag verfassen";

    for (var i =0; i < forms.length; i++){
        if(forms[i].name === "forum_reply") {
            var action = forms[i].getAttribute('action');
            forms[i].action = action.replace('%action%', '/forum/'+topicId+'/'+postId+'/createReply');
        }
    }
}

function updatePost(postContent, postId)
{
    var divs = document.querySelectorAll('div');
    var title = document.getElementById('postModalTitle');
    title.innerHTML = "Beitrag bearbeiten";

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

function updateTopic(topicTitle, topicContent, topicId, redirectRoute=false)
{

    var divs = document.querySelectorAll('div');
    var inputs = document.querySelectorAll('input');

    var title = document.getElementById('topicModalTitle');
    title.innerHTML = "Thema bearbeiten";

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

    if(!redirectRoute) {
        for (var i =0; i < forms.length; i++){
            if(forms[i].name === "forum_topic") {
                var action = forms[i].getAttribute('action');
                forms[i].action = action.replace('%action%', '/forum/topicUpdate/'+topicId);
            }
        }
    } else {
        for (var i =0; i < forms.length; i++){
            if(forms[i].name === "forum_topic") {
                var action = forms[i].getAttribute('action');
                forms[i].action = action.replace('%action%', '/forum/topicUpdate/'+topicId+'/redirectRoute');
            }
        }
    }
}

function updateCategory(catTitle, catDescription, catId)
{
    var title = document.getElementById('categoryModalTitle');
    title.innerHTML = "Kategorie bearbeiten";

    var titleData = document.getElementById('categoryTitle');
    var descriptionData = document.getElementById('categoryDescription');

    var forms = document.querySelectorAll('form');

    for (var i =0; i < forms.length; i++){
        if(forms[i].name === "forum_category") {
            var action = forms[i].getAttribute('action');
            forms[i].action = action.replace('%action%', '/forum/categoryUpdate/'+catId);
        }
    }

    console.log(catTitle);
    titleData.value = catTitle;
    descriptionData.innerHTML = catDescription;
}

function deleteCategory(catId)
{
    var warningPrompt = document.getElementById('warningText');
    var modalTitle = document.getElementById('deleteModalTitle');
    var a = document.querySelectorAll('a');

    for (var i =0; i < a.length; i++){
        if(a[i].id === "killSwitch") {
            var href = a[i].getAttribute('href');
            a[i].href = href.replace('%thingToKill%', '/forum/deleteCategory/'+catId);
        }
    }

    modalTitle.innerHTML = "Kategorie löschen"
    warningPrompt.innerHTML = "Willst du wirklich diese Kategorie, <u>ALLE</u> enthaltenen Themen, Posts und Antworten entgültig löschen?"
}

function deleteTopic(topicId, postCount, replyCount)
{
    var warningPrompt = document.getElementById('warningText');
    var modalTitle = document.getElementById('deleteModalTitle');
    var a = document.querySelectorAll('a');

    for (var i =0; i < a.length; i++){
        if(a[i].id === "killSwitch") {
            var href = a[i].getAttribute('href');
            a[i].href = href.replace('%thingToKill%', '/forum/deleteTopic/'+topicId);
        }
    }

    modalTitle.innerHTML = "Thema löschen"
    warningPrompt.innerHTML = "Willst du wirklich <b>1 Thema</b>, <b>"+postCount+" Posts</b> und <b>"+replyCount+" Antworten</b> entgültig löschen?"

}

function deletePost(postId, replyCount)
{
    var warningPrompt = document.getElementById('warningText');
    var modalTitle = document.getElementById('deleteModalTitle');
    var a = document.querySelectorAll('a');

    for (var i =0; i < a.length; i++){
        if(a[i].id === "killSwitch") {
            var href = a[i].getAttribute('href');
            a[i].href = href.replace('%thingToKill%', '/forum/deletePost/'+postId);
        }
    }

    modalTitle.innerHTML = "Post löschen"
    warningPrompt.innerHTML = "Willst du wirklich <b>1 Post</b> und <b>"+replyCount+" Antworten</b> entgültig löschen?"
}

function deleteReply(replyId)
{
    var warningPrompt = document.getElementById('warningText');
    var modalTitle = document.getElementById('deleteModalTitle');
    var a = document.querySelectorAll('a');

    for (var i =0; i < a.length; i++){
        if(a[i].id === "killSwitch") {
            var href = a[i].getAttribute('href');
            a[i].href = href.replace('%thingToKill%', '/forum/deleteReply/'+replyId);
        }
    }

    modalTitle.innerHTML = "Antwort löschen"
    warningPrompt.innerHTML = "Willst du diese Antwort wirklich entgültig löschen?"
}

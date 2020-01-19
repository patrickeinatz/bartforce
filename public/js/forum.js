function updateTopicKudos(topicId, e){

    if(e.className === 'btn btn-sm btn-gold'){
        e.className = 'btn btn-sm btn-hot';
    } else {
        e.className = 'btn btn-sm btn-gold';
    }

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            var kudos = JSON.parse(this.responseText);
            var kudosCount = e.getElementsByClassName('kudos-count');

            kudosCount[0].innerHTML = kudos['kudos'];
        }
    };
    xhttp.open("GET", "updateTopicKudos/"+topicId, true);
    xhttp.send();

}

function updatePostKudos(postId, e){

    if(e.className === 'btn btn-sm btn-gold'){
        e.className = 'btn btn-sm btn-hot';
    } else {
        e.className = 'btn btn-sm btn-gold';
    }

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            var kudos = JSON.parse(this.responseText);
            var kudosCount = e.getElementsByClassName('kudos-count');

            kudosCount[0].innerHTML = kudos['kudos'];
        }
    };
    xhttp.open("GET", "updatePostKudos/"+postId, true);
    xhttp.send();

}

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

function createTopic(catId, topicModules)
{
    var forms = document.querySelectorAll('form');
    var title = document.getElementById('topicModalTitle');
    title.innerHTML = "Neues Thema eröffnen";

    initRadioButtons(topicModules);

    //Preselect first element
    (document.getElementById("forum_topic_topicContentModule_1")).checked = true;
    //Hide Content field
    (document.getElementById('forum_topic_topicContent').parentElement).hidden = true;

    for (var i =0; i < forms.length; i++){
        if(forms[i].name === "forum_topic") {
            var action = forms[i].getAttribute('action');
            forms[i].action = action.replace('%action%', '/forum/'+catId+'/createTopic');
        }
    }
}

function prepareTopicContent(moduleType){
    var labels = document.querySelectorAll('label');

    for(var i = 0; i < labels.length; i++) {
        if (labels[i].htmlFor === 'forum_topic_topicContent') {

            switch ((moduleType.className.split(' '))[1]) {
                case 'image':
                    labels[i].innerHTML = 'Grafik-Link (*.png, *.gif oder *.jpg )';
                    labels[i].parentElement.hidden = false;
                    break;
                case 'video':
                    labels[i].innerHTML = 'Youtube-Link (Youtube URL z.B. https://youtube.com/xxxx)';
                    labels[i].parentElement.hidden = false;
                    break;
                case 'text':
                    labels[i].parentElement.hidden = true;
                    break;
            }
        }
    }
}

function initRadioButtons(topicModules){

    var divs = document.querySelectorAll('div');

    for (var i=0; i < divs.length; i++) {
        if(divs[i].className === 'form-check'){
            var divContent = divs[i].innerHTML;
            if(divContent.search('prepared') < 0){
                var moduleType = (divs[i].querySelector('label')).innerHTML;
                var inputField = (divContent.split('\n'))[0]
                divs[i].innerHTML = '<label class="prepared '+moduleType+'" onclick="prepareTopicContent(this)">\n'+inputField+'\n<i class="'+topicModules[moduleType]+' fa-lg"></i></label>';
            }
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
    var title = document.getElementById('postModalTitle');
    title.innerHTML = "Beitrag bearbeiten";

    document.getElementById("forum_post_postContent").innerHTML = postContent;

    var forms = document.querySelectorAll('form');

    for (var i =0; i < forms.length; i++){
        if(forms[i].name === "forum_post") {
            var action = forms[i].getAttribute('action');
            forms[i].action = action.replace('%action%', '/forum/postUpdate/'+postId);
        }
    }

}

function updateTopic(topicTitle, topicContent, topicId, topicContentTypeId, topicText, topicModules, redirectRoute=false)
{
    console.log(topicContentTypeId);
    var inputs = document.querySelectorAll('input');

    var title = document.getElementById('topicModalTitle');
    title.innerHTML = "Thema bearbeiten";



    for (var j =0; j < inputs.length; j++) {
        if(inputs[j].name == "forum_topic[title]"){
            inputs[j].value = topicTitle;
        }
        if(inputs[j].name == "forum_topic[topicContent]"){
            inputs[j].value = topicContent;
        }
            }

    document.getElementById("forum_topic_topicText").innerHTML = topicText;

    var forms = document.querySelectorAll('form');

    initRadioButtons(topicModules);

    (document.getElementById("forum_topic_topicContentModule_"+topicContentTypeId)).checked = true;

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
    warningPrompt.innerHTML = "Willst du wirklich diese Kategorie, <u>ALLE</u> enthaltenen Themen, Beiträgen und Antworten entgültig löschen?"
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
    warningPrompt.innerHTML = "Willst du wirklich <b>1 Thema</b>, <b>"+postCount+" Beiträge</b> und <b>"+replyCount+" Antworten</b> entgültig löschen?"

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
    warningPrompt.innerHTML = "Willst du wirklich <b>1 Beitrag</b> und <b>"+replyCount+" Antworten</b> entgültig löschen?"
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


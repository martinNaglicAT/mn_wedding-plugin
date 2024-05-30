console.log('contact-script');


var userId = myChatAjax.user_id;

var visibilityChangeTimeout;


function autoResizeTextarea(textarea) {
    textarea.style.height = 'auto';
    textarea.style.marginTop = '0';
    textarea.style.marginBottom = '0';

    if(textarea.offsetHeight<textarea.scrollHeight){
    	textarea.style.height = textarea.scrollHeight + 'px';
        textarea.style.marginTop = '.5em';
        textarea.style.marginBottom = '.5em';
        loadContentAndScroll();
    }
    handleContentAdjustments();
}

function loadContentAndScroll() {
    var container = document.querySelector('.messenger-main')
    var element = document.querySelector('.conversation-messages');
    var page = document.querySelector('.page_wrap');

    setTimeout(function() {
        if(element !== null){
            element.scrollTop = element.scrollHeight;
        }
        if(container !== null){
            container.classList.add('visible'); 
        }
    }, 1); 
}

function scrollerHeightCalculator(parentClass, childClass){
    setTimeout(function() {
        var totalHeight = jQuery(parentClass).outerHeight(true);
        var minusHeight = jQuery('.messenger-main .title-row').outerHeight(true);
        var input = jQuery('.messenger-main .input-row');
        var adminBar = document.getElementById('wpadminbar');
        adminHeight = 0;
        if(adminBar !== null){
            if(window.innerWidth < 782){
                adminHeight = 47;
            } else {
                adminHeight = 33;
            }
        }
        if(input.length > 0){
            minusInput = input.outerHeight(true);
        } else {
            minusInput = 0;
        }
        var calcHeight = totalHeight - minusHeight - minusInput - adminHeight;
        var conversationCont = document.querySelector(parentClass+' '+childClass);
        if(conversationCont != null){
            conversationCont.style.height = 'calc('+calcHeight+'px - 3em)';    
        }  
    }, 0);
}

function adjustContactForAdmin(){
    adminBar = document.getElementById('wpadminbar');
    if(adminBar !== null){
        var style = document.createElement('style');
        style.textContent = `
            .messenger-main .input-row { bottom: calc(1em + 46px) !important; }

            @media only screen and(min-width: 768px) {
                .messenger-main .input-row { bottom: calc(1em + 33px) !important; } 
            }
        `;

        document.head.appendChild(style);
    }
}

function handleContentAdjustments() {
    if(window.location.href.indexOf('?chat_id') > -1 && window.location.href.indexOf('?chat_id=new') === -1){
       scrollerHeightCalculator('.messenger-main', '.conversation-messages'); 
    } else if (window.location.href.indexOf('?chat_id=new') > -1){
        
        var container = document.querySelector('.contact-form-container');
        var title = document.querySelector('.title-row');
        var contact = document.querySelector('.conversation-form');
        var contactH = jQuery(container).outerHeight(true) - jQuery(title).outerHeight(true);
        contact.style.height = contactH+'px';        
    } else {
        scrollerHeightCalculator('.messenger-column', '.conversation-messages');
    }

    loadContentAndScroll();
}

document.addEventListener('ajaxContentLoaded', handleContentAdjustments);
window.addEventListener('resize', handleContentAdjustments);



function filtersDisplay(){
    var filters = document.querySelector('.filters');
    if(filters !== null){
        var specific = document.getElementById('specific');
        var individual = document.getElementById('individual');
        var test = document.getElementById('test_user');
        var nonSubmit = document.getElementById('not_submit');
        var yesSubmit = document.getElementById('yes_submit');

        specific.addEventListener('change', function(){
            if(specific.checked == true){
                individual.style.display = 'block';
                test.checked = false;
                nonSubmit.checked = false;
                yesSubmit.checked = false;
            } else {
                individual.style.display = 'none';
            }
        });

        nonSubmit.addEventListener('change', function(){
            if(nonSubmit.checked == true){
                yesSubmit.checked = false;
                specific.checked = false;
            }
        });

        yesSubmit.addEventListener('change', function(){
            if(yesSubmit.checked == true){
                nonSubmit.checked = false;
                specific.checked = false;
            }
        });

        test.addEventListener('change', function(){
            if(test.checked == true){
                specific.checked = false;
            }
        });
    }

}



jQuery(document).ready(function($) {

    var adminBar = document.getElementById('wpadminbar');
    var messenger = document.querySelector('.messenger-column');

    if(adminBar != null && messenger != null){
        messenger.classList.add('admin-compensate');
    }

});



//hide/show messenger

document.addEventListener('DOMContentLoaded', function() {
    var messengerLink = document.querySelector('.chat-icon-link');
    var messenger = document.querySelector('.messenger-column');
    var pageContent = document.querySelector('.page_content');

    var messengerList = document.querySelector('.messenger-list');
    var messengerMain = document.querySelector('.messenger-main');

    if (messengerLink != null) {
        messengerLink.addEventListener('click', function(event) {
            event.preventDefault();

            if (messengerLink.classList.contains('hide')) {
                // Code to open the messenger
                if(window.innerWidth < 768){
                    pageContent.style.opacity = "0";
                }
                messenger.classList.add('visible');
                messengerLink.classList.add('show');
                messengerLink.classList.remove('hide');                
                messengerList.style.display = 'block';
                messengerMain.style.display = 'none';
                loadMessagesList(-1);
                var previousNotification = document.querySelector('.message-popup');
                if(previousNotification !== null && previousNotification !== undefined){
                    previousNotification.classList.remove('visible');
                    setTimeout(function(){
                        previousNotification.remove();
                    },500);
                }
            } else if (messengerLink.classList.contains('show')) {
                // Code to close the messenger
                messengerLink.classList.remove('show');
                messengerLink.classList.add('hide');
                setTimeout(function() {
                    messenger.classList.remove('visible');
                }, 1);
                setTimeout(function() {
                    pageContent.style.opacity = "1";
                    messengerList.style.display = 'block';
                    messengerMain.style.display = 'none';
                }, 300);
            }
        });
    }
});



document.addEventListener('DOMContentLoaded', function() {
    var ajaxContentLoadedEvent = new CustomEvent('ajaxContentLoaded');
    document.dispatchEvent(ajaxContentLoadedEvent);
    checkNewMessages();
    adjustContactForAdmin();
    filtersDisplay();
});






//AJAX

//Delete conversation
jQuery(document).ready(function($) {
    $('body').on('click', '.delete-conversation', function() {
        var conversationId = $(this).data('conversation-id');
        var nonce = myChatAjax.nonce;

        if(!confirm('Are you sure you want to delete this conversation?')) {
            return false; 
        }

        deleteConversation(conversationId, nonce);
    });
});



function deleteConversation(conversationId, nonce){

    jQuery.ajax({
        type: 'post',
        url: myChatAjax.ajax_url,
        data: {
            action: 'delete_conversation_ajax',
            conversation_id: conversationId,
            nonce: nonce
        },
        success: function(response) {
            if(response == 'success') {
                deletedPost = document.getElementById('chat_'+conversationId);
                deletedFull = document.getElementById('conversation_'+conversationId);
                deletedPost.remove();

                if(deletedFull != null && deletedFull != undefined){
                    deletedFull.innerHTML = "Post was deleted succesfully";
                }

            } else {
                // Handle failure
            }
        }
    });

}


//Show conversation on click
jQuery(document).ready(function($) {
    $('body').on('click', '.single-convo', function(e) {
        e.preventDefault();
        var conversationId = $(this).data('chat-id');

        var currentUrl = window.location.href;

        if(currentUrl.indexOf('chat') > -1 || currentUrl.indexOf('klepet') > -1){
           var newUrl = myChatAjax.home_url + '/chat/?chat_id=' + conversationId;
            window.history.pushState({path: newUrl}, '', newUrl); 
        }

        showConversation(conversationId);
    });
});

function showConversation(conversationId){
    jQuery.ajax({
        url: myChatAjax.ajax_url,
        type: 'POST',
        data: {
            action: 'load_conversation_content_ajax',
            post_id: conversationId, 
            nonce: myChatAjax.nonce,
        },
        success: function(response) {
            checkNewMessages();
            if(window.innerWidth < 768 || jQuery('.messenger-column').length > 0 ){
                jQuery('.messenger-list #chat_'+conversationId).removeClass('unread');
                jQuery('.messenger-list').hide();
                jQuery('.messenger-main').show();   
                jQuery('.messenger-main').addClass('visible'); 

                scrollerHeightCalculator('.messenger-column', '.conversation-messages');

            }  

            jQuery('.messenger-list #chat_'+conversationId).removeClass('unread'); 

            jQuery('.messenger-main').html(response);

            var ajaxContentLoadedEvent = new CustomEvent('ajaxContentLoaded');
            document.dispatchEvent(ajaxContentLoadedEvent);
        },
        error: function(jqXHR, textStatus, errorThrown) {
        }
    });
}








//show new message form on click
jQuery(document).ready(function($) {
    $('body').on('click', '.new-convo', function(e) {
        e.preventDefault();

        if(currentUrl.indexOf('chat') > -1 || currentUrl.indexOf('klepet') > -1){
            var newUrl = myChatAjax.home_url + '/chat/?chat_id=new';
            window.history.pushState({path: newUrl}, '', newUrl);
        }

        showMessageForm();
    });
});

function showMessageForm(){
    jQuery.ajax({
        url: myChatAjax.ajax_url,
        type: 'POST',
        data: {
            action: 'load_conversation_form_ajax',
            chat_id: 'new', 
            nonce: myChatAjax.nonce,
        },
        success: function(response) {
            if(window.innerWidth < 768 || jQuery('.messenger-column').length > 0){
                jQuery('.messenger-list').hide();
                jQuery('.messenger-main').show();
                jQuery('.messenger-main').addClass('visible');
                jQuery('.messenger-main').addClass('new-form');
                scrollerHeightCalculator('.messenger-column', '.conversation-form');

            }
         
            jQuery('.messenger-main').html(response);
            var ajaxContentLoadedEvent = new CustomEvent('ajaxContentLoaded');
            document.dispatchEvent(ajaxContentLoadedEvent);
        },
        error: function(jqXHR, textStatus, errorThrown) {
        }
    });
}



//back to list on click
jQuery(document).ready(function($) {
    $('body').on('click', '.back-to-list', function(e) {
        e.preventDefault();

        if(currentUrl.indexOf('chat') > -1 || currentUrl.indexOf('klepet') > -1){
            var newUrl = myChatAjax.home_url + '/chat/?chat_id=list';
            window.history.pushState({path: newUrl}, '', newUrl);
        }

        jQuery('.messenger-main').removeClass('new-form');

        var post_id = $('.conversation').data('chat-id');

        console.log(post_id);
        console.log(typeof post_id);
 

        // AJAX call
        loadMessagesList(post_id);
    });
});



function loadMessagesList(post_id){
    jQuery.ajax({
        url: myChatAjax.ajax_url,
        type: 'POST',
        data: {
            action: 'load_conversation_list_ajax',
            chat_id: 'list', 
            nonce: myChatAjax.nonce,
        },
        success: function(response) {
            if(window.innerWidth < 768 || jQuery('.messenger-column').length > 0){
                jQuery('.messenger-main').hide();
                jQuery('.messenger-list').show();
                jQuery('.messenger-main').removeClass('visible');
            }
         
            jQuery('.messenger-list').html(response);
            if(post_id > -1){
                document.getElementById('chat_'+post_id).classList.remove('unread');
            }


            var ajaxContentLoadedEvent = new CustomEvent('ajaxContentLoaded');
            document.dispatchEvent(ajaxContentLoadedEvent);
        },
        error: function(jqXHR, textStatus, errorThrown) {
        }
    });
}



//submit message
function submitMessage() {
    var form = jQuery('#contact-message');
    var conversationId = form.find('input[name="post_id"]').val(); 
    var messageContent = form.find('textarea[name="conversation_new_message"]').val();
    var messagesContainer = jQuery('.conversation-messages');

    jQuery.ajax({
        url: myChatAjax.ajax_url,
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'submit_new_message_ajax', 
            chat_id: conversationId,
            message: messageContent, 
            nonce: myChatAjax.nonce,
        },
        success: function(response) {
            jQuery('.conversation-messages').append(response[0]);
            form.find('textarea[name="conversation_new_message"]').val('');
            loadContentAndScroll();

            jQuery('.messenger-list #chat_'+conversationId).remove();
            jQuery('.messenger-list #new-m').after(response[1]);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error submitting message:', textStatus, errorThrown);
        }
    });
}


jQuery(document).ready(function($) {
    $('body').on('submit', '#contact-message', function(e) {
        e.preventDefault(); 
        submitMessage(); 
    });

    $('body').on('keydown', '#contact-message #autoresizing', function(e) {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault(); 
            $('#contact-message').submit(); 
        }
    });

});





//submit conversation
function submitConversation(placement) {
    if (placement === 'static'){
        var form = jQuery('#contact-form-static');
    } else {
       var form = jQuery('#contact-form'); 
    }

    var messageSubject = form.find('input[name="subject"]').val();
    var messageContent = form.find('textarea[name="message"]').val();

    if(jQuery('.contact-data-user').length > 0){
        var messageEmail = form.find('input[name="email"]').val();
        var emailGuest = form.find('select[name="email_guest"]').val();
        var countryCode = form.find('select[name="country_code"]').val();
        var messagePhone = form.find('input[name="phone"]').val();
        var phoneGuest = form.find('select[name="phone_guest"]').val();
        var updateContact = form.find('checkbox[name="update_contact_info"]').val();
        
        var filterTest = '';
        var filterNotSubmit = '';
        var filterSubmit = '';
        var filterSpecific = '';
        var filterLanguage = '';
        var filterUser = '';

    } else {
        var messageEmail = '';
        var emailGuest = '';
        var countryCode = '';
        var messagePhone = '';
        var phoneGuest = '';
        var updateContact = '';

        var filterTest = form.find('checkbox[name="test_user"]').val();
        var filterNotSubmit = form.find('checkbox[name="not_submit"]').val();
        var filterSubmit = form.find('checkbox[name="yes_submit"]').val();
        var filterSpecific = form.find('checkbox[name="specific"]').val();
        var filterLanguage = form.find('select[name="language"]').val();
        var filterUser = form.find('select[name="individual"]').val();


    }
    


    jQuery.ajax({
        url: myChatAjax.ajax_url,
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'submit_new_conversation_ajax',
            subject: messageSubject, 
            message: messageContent, 
            email: messageEmail,
            email_guest: emailGuest,
            country_code: countryCode,
            phone: messagePhone,
            phone_guest: phoneGuest,
            update_contact_info: updateContact,
            specific: filterSpecific,
            individual: filterUser,
            language: filterLanguage,
            yes_submit: filterSubmit,
            not_submit: filterNotSubmit,
            test_user: filterTest,
            nonce: myChatAjax.nonce,
        },
        success: function(response) {

            form.find('textarea[name="message"]').val('');
            form.find('input[name="subject"]').val('');

            var messengerLink = document.querySelector('.chat-icon-link');
            var messenger = document.querySelector('.messenger-column');
            var pageContent = document.querySelector('.page_content');
            var messengerList = document.querySelector('.messenger-list');
            var messengerMain = document.querySelector('.messenger-main');

            if(messenger !== null){
                if(window.innerWidth < 768){
                    pageContent.style.opacity = "0";
                }
                messenger.classList.add('visible');  

                messengerLink.classList.add('show');
                messengerLink.classList.remove('hide'); 
            
                messengerList.style.display = 'none';
                messengerMain.style.display = 'block';
            }
            messengerMain.innerHTML = '';

            jQuery('.messenger-main').append(response[0]);

            loadContentAndScroll();

            jQuery('.messenger-list #new-m').after(response[1]);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error submitting message:', textStatus, errorThrown);
        }
    });
}


jQuery(document).ready(function($) {
    $('body').on('submit', '#contact-form', function(e) {
        e.preventDefault(); 
        submitConversation(''); 
    });

    $('body').on('keydown', '#contact-form #autoresizing', function(e) {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault(); 
            $('#contact-form').submit(); 
        }
    });

});

jQuery(document).ready(function($) {
    $('body').on('submit', '#contact-form-static', function(e) {
        e.preventDefault(); 
        submitConversation('static'); 
    });

    $('body').on('keydown', '#contact-form-static #autoresizing', function(e) {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault(); 
            $('#contact-form').submit(); 
        }
    });

});


function markAsRead(idNum){
    jQuery.ajax({
        url: myChatAjax.ajax_url,
        type: 'POST',
        data: {
            action: 'mark_message_as_read_ajax', 
            chat_id: idNum,
            nonce: myChatAjax.nonce,
        },
        success: function(response) {
            if(response === 'success'){
                console.log('message '+idNum+' marked as read.');
                var message = document.getElementById('chat_'+idNum);
                if(message !== undefined && message !== null){
                    message.classList.remove('unread');
                }
            }
            
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error marking as read:', textStatus, errorThrown);
        }
    });
}



function checkNewMessages(){
    jQuery.ajax({
        url: myChatAjax.ajax_url,
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'check_new_messages', 
            nonce: myChatAjax.nonce,
        },
        success: function(response) {
            if(response['status'] !== 'none' && ''+response['author_id'] !== userId ){
                var chat_id = response['chat_id'];
                var author = response['author'];
                var date = response['date'];
                var content = response['content'];

                if(content !== 'undefined' && content !== undefined){
                   renderUnread(chat_id, author, date, content); 
                }

            } else if(response['status'] !== 'unread'){
                var messengerMain = document.querySelector('.messenger-main');
                if(messengerMain !== null && messengerMain !== undefined){
                    messengerMain.querySelector('.back-to-list').classList.remove('unread');
                }
            }                
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
        }
    });
}



function renderUnread(chat_id, author, date, content){

    var messengerList = document.querySelector('.messenger-list');

    var listIden = document.getElementById('chat_'+chat_id);
    var messengerMain = document.querySelector('.messenger-main');
    var messageIden = document.getElementById('conversation_'+chat_id);
    var messengerOpen = document.querySelector('.conversation-messages');
    var messengerColumn = document.querySelector('.messenger-column');

    if(messengerList.style.display !== "none"){
        loadMessagesList(-1);
    }

    if(messageIden !== null && messageIden !== undefined ){
        var newMessage = document.createElement('div');
        newMessage.classList.add('message');
        newMessage.classList.add('message-reply');
        newMessage.innerHTML = ''+
            '<span class="author">'+author+'</span>'+
            '<p>'+content+'</p>'+
            '<div class="datetime">'+date+'</div>'+
            '';
        messengerOpen.appendChild(newMessage);
        loadContentAndScroll();        
    } else {
        if(messengerMain !== null && messengerMain !== undefined){
            messengerMain.querySelector('.back-to-list').classList.add('unread');
        }

    }

    if (listIden !== null && listIden !== undefined){
        listIden.classList.remove('unread');
        listIden.classList.add('unread');
    }

    if(!(messengerColumn.classList.contains('visible'))){

            var body = document.body;
            var previousNotification = document.querySelector('.message-popup');
            if(previousNotification != null && previousNotification != undefined){
                previousNotification.classList.remove('visible');
                setTimeout(function(){
                    previousNotification.remove();
                },500);
            }
            var pageContent = document.querySelector('.page_content');
            var newMessage = document.createElement('div');
            newMessage.classList.add('message');
            newMessage.classList.add('message-reply');
            newMessage.classList.add('message-popup');
            newMessage.innerHTML = ''+
                '<div class="close-container">'+
                '<a href="#" class="close-popup-link">'+
                '<div class="close-popup-link-inner"></div>'+
                '</a>'+
                '</div>'+
                '<div>'+
                '<a href="#" class="popup-link">'+
                '<span class="author">'+author+'</span>'+
                '<p class="message-content">'+content+'</p>'+
                '<div class="datetime">'+date+'</div>'+
                '</a>'+
                '</div>'+
                '';
            body.appendChild(newMessage);
            var textContent = newMessage.querySelector('.message-content').innerHTML;
            newMessage.classList.add('visible');

            newMessage.querySelector('.close-popup-link').addEventListener('click', function(e){
                e.preventDefault();
                newMessage.classList.remove('visible');
                setTimeout(function(){
                    newMessage.remove();
                },500);
            });

            newMessage.querySelector('.popup-link').addEventListener('click', function(e){
                e.preventDefault();
                showConversation(chat_id);
                pageContent.style.opacity = '0';
                messengerColumn.querySelector('.messenger-list').style.display = "none";
                messengerColumn.classList.add('visible');
                var chatIcon = document.querySelector('.chat-icon-link');
                chatIcon.classList.add('show');
                chatIcon.classList.remove('hide');
                
                newMessage.remove();
            });

        
    }
}






var pusher = new Pusher('592eb7ecf87a4c83a851', {
  cluster: 'eu'
});


var channel = pusher.subscribe('channel_'+userId);

channel.bind('new-message-reciever', function(data) {

    chatId = data.chat_id;
    author = data.author;
    date = data.time;
    content = data.message;

    renderUnread(chatId, author, date, content);      

});

channel.bind('mark-as-read', function(data){
    var listIden = document.getElementById('chat_'+data.chat_id);
    if (listIden !== null && listIden !== undefined){
        listIden.classList.remove('unread');
    }
});

channel.bind('new-conversation-receiver', function(data){
    chatId = data.chat_id;
    author = data.author;
    date = data.time;
    content = data.message;

    renderUnread(chatId, author, date, content);
});







let observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            var firstElementChild = entry.target.querySelector(':first-child');
            if (firstElementChild && firstElementChild.id) {
                var firstChildId = firstElementChild.id;
                var idNum = firstChildId.replace('conversation_', '');
                setTimeout(function(){
                    markAsRead(idNum);
                }, 2000);
            } else {
            }
        }
    });
});

// Start observing when the document is initially loaded or when the tab becomes active again
function startObserving() {
    if(document.querySelector('.messenger-main') != null){
       observer.observe(document.querySelector(".messenger-main")); 
    }
}

// A function to stop observing when the tab becomes inactive
function stopObserving() {
    observer.disconnect();
}

function handleVisibilityChange() {
    if (document.hidden) {
        pusher.disconnect();
        stopObserving();
        clearTimeout(visibilityChangeTimeout);
    } else {
        pusher.connect();
        startObserving();
        clearTimeout(visibilityChangeTimeout);
        visibilityChangeTimeout = setTimeout(function(){
            if (!document.hidden) {
                checkNewMessages(); 
            }
        }, 1000);
    }
}

document.addEventListener("visibilitychange", handleVisibilityChange);

// Start observing initially if needed
startObserving();



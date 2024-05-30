console.log("rsvp script");

var currentUrl = window.location.href;

document.addEventListener('DOMContentLoaded', function() {
	var submitButton = document.querySelector('input[type="submit"]');

	if(submitButton != null && pageWidth < 1024){
		setInterval(function(){
			submitButton.classList.add('animated');
			setTimeout(function(){
				submitButton.classList.remove('animated');
			},2000);
		},12000);
	}
});


document.addEventListener('DOMContentLoaded', function() {
	var helpContact = document.getElementById('help-contact');
	var helpGuests = document.getElementById('help-guests');

	var help = [];
	help.push(helpContact);
	help.push(helpGuests);

	if(helpContact !== null && helpGuests !== null){

		if(help.length > 0){

			for (var i = 0; i < help.length; i++) {
			    (function(nr) {
			        var openHelp = help[nr].querySelector('.open-help');
			        var helpText = help[nr].querySelector('.help-text');
			        var closeHelp = help[nr].querySelector('.close-help');


			        openHelp.addEventListener('click', function() {

			        	event.preventDefault();

			        	var helpHeight = helpText.querySelector('p').offsetHeight + 30;
			        	openHelp.style.display = 'none';
			        	closeHelp.style.display = 'flex';
			        	helpText.style.height =  helpHeight + 'px';



			        });

			        closeHelp.addEventListener('click', function() {

			        	event.preventDefault();

			        	var helpHeight = helpText.querySelector('p').offsetHeight + 30;
			        	closeHelp.style.display = 'none';
			        	openHelp.style.display = 'block';
			            helpText.style.height = '0';

			        });
			    })(i);
			}

		}

	}
});


function vegetarianVeganHook(hidden){

	var vegan = hidden.querySelector('.hook-vegan');
	var vegetarian = hidden.querySelector('.hook-vegetarian');

	vegan.addEventListener('change', function(){
		if(vegan.checked == true){
			vegetarian.checked = true;
		} 							
	});

	vegetarian.addEventListener('change', function(){
		if(vegetarian.checked == false){
			vegan.checked = false;
		} 							
	});

}


document.addEventListener('DOMContentLoaded', function() {

	var attend = document.querySelectorAll('.unlock_guest_form');

	if(attend.length > 0){
		for (var j = 0; j < attend.length; j++) {
		    (function(index) {



		    	var hidden = document.querySelectorAll('.rsvp-hidden')[index];
		    	var hidInner = hidden.querySelector('.rsvp-hidden-inner');
		    	var hidHeight = hidInner.offsetHeight;
		    	var plusOneOption = hidden.querySelector('.unlock_plus_one');

		    	hidden.calcHeight = hidHeight;


		    	attend[index].addEventListener('change', function(){

		    		var calcHeight = hidden.calcHeight;

		    		if(this.checked){

		    			var totalHeight = jQuery(hidInner).outerHeight(true);
		    			calcHeight = totalHeight + 50;
	        			hidden.style.height = calcHeight + 'px';

	        			var help = hidden.querySelectorAll(".help");
						if(help.length > 0){

							for (var i = 0; i < help.length; i++) {
							    (function(nr) {
							        var openHelp = help[nr].querySelector('.open-help');
							        var helpText = help[nr].querySelector('.help-text');
							        var closeHelp = help[nr].querySelector('.close-help');


							        openHelp.addEventListener('click', function(event) {

							        	event.preventDefault();

							        	var helpHeight = helpText.querySelector('p').offsetHeight + 30;
							        	openHelp.style.display = 'none';
							        	closeHelp.style.display = 'flex';
							        	helpText.style.height =  helpHeight + 'px';
							        	hidden.calcHeight += helpHeight;
							        	hidden.style.height = hidden.calcHeight + 'px';


							        });

							        closeHelp.addEventListener('click', function(event) {

							        	event.preventDefault();

							        	var helpHeight = helpText.querySelector('p').offsetHeight + 30;
							        	closeHelp.style.display = 'none';
							        	openHelp.style.display = 'block';
							            helpText.style.height = '0';
							            hidden.calcHeight -= helpHeight;
							            hidden.style.height = hidden.calcHeight + 'px';

							        });
							    })(i);
							}

						}

					vegetarianVeganHook(hidden);

		    		} else {

		    			hidden.style.height = '0px';

		    			if(plusOneOption !== null){

		    				plusOneOption.checked = false;

		    				plusOneOption.dispatchEvent(new Event('change'));

		    			}

		    		}

                	hidden.calcHeight = calcHeight;
		    		
		    	});

		    	if ('fonts' in document) {
				    document.fonts.ready.then(function() {

				        attend[index].dispatchEvent(new Event('change'));
				    });
				}

		    })(j);
		}
	}

});






function updateSubmitButtonStatus() {
    var submitButton = document.getElementById('custom_rsvp_submit');
    var errorArray = document.querySelectorAll('.name-error');
    var errorsListContainer = document.querySelector('.errors-list');
    var errorContainer = document.querySelector('.error-m-container');
    var headerOffset = 200; 

    submitButton.disabled = errorArray.length > 0;

    // Clear the existing error links
    errorsListContainer.innerHTML = '';

    if (errorArray.length > 0) {

    	errorContainer.style.display = 'block';

        for (var i = 0; i < errorArray.length; i++) {
            var errorElement = errorArray[i];

            // Ensure each error element has a unique ID
            var errorId = errorElement.id;//'error-' + (i + 1);
            //errorElement.id = errorId;

            var inputElement = errorElement.previousElementSibling;
            var guestId = '';
            if (inputElement && inputElement.name) {
                guestIdConstruct = inputElement.name.match(/(\w+)_name_plus/)[1]; 

                guestId = guestIdConstruct+'_name';

                guestName = document.querySelector('.'+guestId).innerHTML;
            }


            var errorLink = document.createElement('a');
            errorLink.href = 'javascript:void(0);';
            errorLink.textContent = guestName;
            errorLink.classList.add('error-link');

            (function(link, targetId) {
                link.addEventListener('click', function(e) {
                    var targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        var targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerOffset;
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }

                    errorLinks = document.querySelectorAll('.error-link');

                    for(q = 0; q < errorLinks.length; q++){
                    	errorLinks[q].classList.remove('active');
                    }

                    link.classList.add('active');

                    if(errorArray.length > 1) {
                    	errorContainer.classList.add('visible');

                    	var errorClose = document.querySelector('.close-popup');

                    	errorClose.addEventListener('click', function(event){
                    		event.preventDefault();
                    		errorContainer.classList.remove('visible');
                    	})
                    }
                });
            })(errorLink, errorId);

            errorsListContainer.appendChild(errorLink);

            errorsListContainer.appendChild(document.createElement('br'));
        }

    } else {

    	errorContainer.classList.remove('visible');
    	errorContainer.style.display = 'none';
    }

    //console.log(errorArray);
}


var plusOne = document.querySelectorAll('.unlock_plus_one');

if(plusOne.length > 0){
	for (var k = 0; k < plusOne.length; k++) {
	    (function(index) {



	    	var plusHidden = document.querySelectorAll('.form-plus-one')[index];
	    	var plusFeedback = document.querySelectorAll('.plus-one-feedback')[index];
	    	
	    	//trigger an event to capture saved state on load
	    	document.addEventListener('DOMContentLoaded', function() {
	    		plusOne[index].dispatchEvent(new Event('change'));
	    	});

	    	plusOne[index].addEventListener('change', function(){
	    		if(this.checked){

	    			plusHidden.style.display = 'block';
	    			plusFeedback.style.opacity = '1';

	    			var plus_one_name = plusHidden.querySelector('.name-plus-one');

					if(plus_one_name != null && plus_one_name.value === ''){
							setInterval(function() {
								plus_one_name.classList.add('animated');
								setTimeout(function(){
									plus_one_name.classList.remove('animated');
								},2000);
							},5000);
					}


	    			var help = plusHidden.querySelectorAll(".help");

					if(help.length > 0){

						for (var q = 0; q < help.length; q++) {
						    (function(nr) {
						        var openHelp = help[nr].querySelector('.open-help');
						        var helpText = help[nr].querySelector('.help-text');
						        var closeHelp = help[nr].querySelector('.close-help');

						        openHelp.addEventListener('click', function(event) {

						        	event.preventDefault();

						        	var helpHeight = helpText.querySelector('p').offsetHeight + 30;
						        	openHelp.style.display = 'none';
						        	closeHelp.style.display = 'flex';
						        	helpText.style.height =  helpHeight + 'px';


						        });

						        closeHelp.addEventListener('click', function(event) {

						        	event.preventDefault();

						        	closeHelp.style.display = 'none';
						        	openHelp.style.display = 'block';
						            helpText.style.height = '0';

						        });
						    })(q);
						}

					}

					var nameInput = plusHidden.querySelector('.name-plus-one');
					var nameRequired = plusHidden.querySelector('.required-field');

					nameInput.addEventListener('input', function(event) {
						var currentValue = event.target.value;

						if(currentValue !== ''){
							nameRequired.classList.add('condition-met');
							nameRequired.classList.remove('name-error');
							updateSubmitButtonStatus();
						} else {
							nameRequired.classList.remove('condition-met');
							nameRequired.classList.add('name-error');
							updateSubmitButtonStatus();
						}

					});

					var event = new Event('input');
    				nameInput.dispatchEvent(event);

    				vegetarianVeganHook(plusHidden);

	    		} else {

	    			plusHidden.style.display = 'none';
	    			plusFeedback.style.opacity = '0';
	    			var nameRequired = plusHidden.querySelector('.required-field');
	    			nameRequired.classList.remove('name-error');
	    			updateSubmitButtonStatus();

	    		}
	    		
	    	});

	    })(k);
	}
}





document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('rsvp_form_id'); 
    
    if(form !== null){
    	submitLastUpdated(form);
	} else {
		form = document.getElementById("contact-form");
		if(form !== null){
			submitLastUpdated(form);
		}
	}

});


function submitLastUpdated(form){
	form.addEventListener('submit', function(event) {

        var now = new Date();

        var year = now.getFullYear();
        var month = ('0' + (now.getMonth() + 1)).slice(-2);
        var day = ('0' + now.getDate()).slice(-2);
        var hours = ('0' + now.getHours()).slice(-2);
        var minutes = ('0' + now.getMinutes()).slice(-2);
        var seconds = ('0' + now.getSeconds()).slice(-2);

        var timestamp = year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;

        document.getElementById('last_updated').value = timestamp;

    });
}


if ( currentUrl.includes('/rsvp-summary/') || currentUrl.includes('/rsvp-povzetek/') || currentUrl.includes('/rsvp-zusammenfassung/') || currentUrl.includes('?mnwedding_action=view') ) {

	document.addEventListener('DOMContentLoaded', function() {
		var editRSVPlink = document.querySelector('.summary-popup-container');
		var editRSVPclose = editRSVPlink.querySelector('.close-popup');
		var marginBottom = document.querySelector('.rsvp_guest-sheet');

		if(editRSVPlink != undefined){
			editRSVPlink.style.bottom = '0px';
			if(editRSVPclose != undefined){
				editRSVPclose.addEventListener('click', function(event){
					event.preventDefault();
					editRSVPlink.style.bottom = '-70px';
					setTimeout(function(){
						editRSVPlink.classList.remove('visible');
						marginBottom.classList.remove('popup-margin');
					}, 1000);
				});
			}
		}


	});


}




jQuery(document).ready(function($) {
    $('.mark-as-reviewed').change(function() {
        var userId = $(this).data('user-id');
        var reviewed = $(this).is(':checked') ? 'on' : 'off';

        $.ajax({
            url: mnWeddingPluginAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'mark_as_reviewed',
                user_id: userId,
                reviewed: reviewed, 
                nonce: mnWeddingPluginAjax.nonce
            },
            success: function(response) {
                if(response.success) {
                    alert('RSVP review status updated.');
                    // Update UI accordingly
                } else {
                    alert('Error updating RSVP review status.');
                }
            }
        });
    });
});


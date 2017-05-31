jQuery(function($){
	var $start_date = $('#start_date'),
	$end_date = $('#end_date'),
	$calender_heading = $('#calendar-heading'),
	$delete_button = $( "#delete-button" ),
	$delete_confirm = $( "#delete-confirm" ),
	dateFormat = 'm/d/yy',
	timeFormat = 'h:mm tt';

	$start_date.on('change', function(event){
		$calender_heading.text($.datepicker.formatDate( "M d, yy", $(this).datepicker('getDate') ));		
	});
	
	$.timepicker.datetimeRange(
		$start_date,
		$end_date,
		{
			dateFormat: dateFormat, 
			timeFormat: timeFormat,
			hourGrid: 4,
			minuteGrid: 10,
			changeMonth: true,
			changeYear: true,
			timeInput: true,
			start: {}, // start picker options
			end: {} // end picker options					
		}
	);
	
	$delete_button.click(function(event){
		event.preventDefault();
		
		$delete_confirm.dialog({
	        resizable: false,
	        height: "auto",
	        width: 400,
	        modal: true,
	        show: {
	            effect: "drop",
	            duration: 500
	          },
	          hide: {
	            effect: "drop",
	            duration: 500
	          },
	        buttons: {
	          "Delete": function() {
	        	  $delete_button[0].form.submit();
	          },
	          Cancel: function() {
	            $( this ).dialog( "close" );
	          }
	        }
	      });
	});
	
	

	function validateForm(form, button){	
		var validationMessages = JSON.parse(window.sessionStorage.getItem('validationMessages'));
		
		if(form === undefined){
			$form = $('form');
			form = $form[0];		
		} else{		
			$form = typeof form['get'] === "function" ? form : $(form);
			form = $form[0];
		}
	
		if(button === undefined){
			$button = $form.find("button[type=submit]");
			button = $button[0];		
		} else{		
			$button = typeof button['get'] === "function" ? button : $(button);
			button = $button[0];
		}
	
		
		/*
		 * Check the entire form's validity and set its view appropriately
		 * 
		 * @param boolean additionalCheck If present, also use this condition to
		 * determine validity. 
		 */
		function checkFormValidity(additionalCheck){
			if (additionalCheck === undefined){
				additionalCheck = true;
			}
			
			if($form[0].checkValidity() && additionalCheck){
				$button.removeAttr('disabled');
			} else{
				$button.attr('disabled', 'disabled');
			}
		}
		
		checkFormValidity();
		
		$form.find('input, textarea').on('blur keyup change', function(event){
			var $thisInput = $(this); 
			var ruleName = '';
			var inputMessages = {};
			var isValid = true;
			
			var $help_block = $(this).next('span.help-block');		
			if($help_block.length === 0){
				var help_block = document.createElement('span');
				help_block.className = 'help-block';
				$help_block = $(help_block); 
				$(this).after($help_block);
			} else{
				$help_block.empty();
			}		
			
			if(!this.checkValidity()){
				isValid = false;
				$(this).closest('.form-group').addClass('has-danger');	
				$(this).addClass('form-control-danger');
				
				if(this.validity.valueMissing){
					ruleName = 'required';
				}
				
				if(this.validity.tooLong){
					ruleName = 'max';
				}
				
				if(this.validity.tooShort){
					ruleName = 'min';				
				}
				
				if(this.validity.typeMismatch && $(this).attr('name') === 'email'){
					ruleName = 'email';				
				}
				
				inputMessages[ruleName] = validationMessages[$(this).attr('name')][ruleName];
				
				
			} 
			if($(this).val().length > $(this).attr('maxlength')){
				isValid = false;
				
				$(this).closest('.form-group').addClass('has-danger');
				$(this).addClass('form-control-danger');
				
				ruleName = 'max';
				
				inputMessages[ruleName] = validationMessages[$(this).attr('name')][ruleName];
				
			} 
			
			if($(this).val().length < $(this).attr('minlength')){
				isValid = false;
				
				$(this).closest('.form-group').addClass('has-danger');
				$(this).addClass('form-control-danger');
				ruleName = 'min';
				
				inputMessages[ruleName] = validationMessages[$(this).attr('name')][ruleName];						
			} 
			
			if(isValid){
				$(this).closest('.form-group').removeClass('has-danger');
				$(this).removeClass('form-control-danger');
				$help_block.remove();			
			} else {			
				for(var inputMessageRule in inputMessages){
					var inputMessage = inputMessages[inputMessageRule];
					var ruleMessageId = $thisInput.attr('name') + '-' + inputMessageRule;
					if($('strong#' + ruleMessageId).length === 0){
						var $strong = $(document.createElement('strong')).attr('id', $thisInput.attr('name') + '-' + inputMessageRule);
						$strong.addClass('form-control-feedback');
						$strong.text(inputMessage);
						$help_block.append($strong);
					}
				}
			}
			
			checkFormValidity();
		});
		
		$form.find('#end_date, #start_date').on('blur keyup change', function(event){
			var $thisInput = $(this); 
			var ruleName = '';
			var inputMessages = {};
			var isValid = true;
			var datesValid = true;
			
			var $help_block = $(this).next('span.help-block');		
			if($help_block.length === 0){
				var help_block = document.createElement('span');
				help_block.className = 'help-block';
				$help_block = $(help_block); 
				$(this).after($help_block);
			} else{
				$help_block.empty();
			}
			
			$(this).closest('.form-group').addClass('has-danger');
			$(this).addClass('form-control-danger');
			
			if(!this.checkValidity()){
				isValid = false;
				
				if(this.validity.valueMissing){
					ruleName = 'required';
				}
				
				if(this.validity.tooLong){
					ruleName = 'max';
				}
				
				if(this.validity.tooShort){
					ruleName = 'min';				
				}
				
				inputMessages[ruleName] = validationMessages[$(this).attr('name')][ruleName];
			}		
			
			if( ($('#start_date').datetimepicker('getDate') && $('#end_date').datetimepicker('getDate')) &&
					($('#start_date').datetimepicker('getDate') > $('#end_date').datetimepicker('getDate'))){
				isValid = false;
				datesValid = false;
				
				ruleName = $(this).attr('id') === 'start_date' ? 'before_or_equal' : 'after_or_equal';
				
				inputMessages[ruleName] = validationMessages[$(this).attr('name')][ruleName];
				inputMessages[ruleName] = inputMessages[ruleName].replace(/:date/, $(this).attr('id') === 'start_date' ? 'end_date' : 'start_date');
			} else {
				
			}
			
			if(isValid){
				var $elements;
				if(datesValid){
					$elements = $('.date')
					$help_block = $elements.next('span.help-block');	
				} else {
					$elements = $(this)
				}
				$elements.closest('.form-group').removeClass('has-danger');
				$elements.removeClass('form-control-danger');
				$help_block.remove();			
			} else {			
				for(var inputMessageRule in inputMessages){
					var inputMessage = inputMessages[inputMessageRule];
					var ruleMessageId = $thisInput.attr('name') + '-' + inputMessageRule;
					if($('strong#' + ruleMessageId).length === 0){
						var $strong = $(document.createElement('strong')).attr('id', $thisInput.attr('name') + '-' + inputMessageRule);
						$strong.addClass('form-control-feedback');
						$strong.text(inputMessage);
						$help_block.append($strong);
					}
				}
			}
			
			checkFormValidity();
		});
	}
	
	validateForm($('#create-calendar-event-form'));
});
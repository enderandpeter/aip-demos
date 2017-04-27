jQuery(function($){
	$('#start_date').datetimepicker({
		format: 'm/d/Y H:i',
		formatTime:'g:i A',
		minDate: $('#start_date').data('startdate'),
		maxDate: $('#start_date').data('startdate'),
		onShow:function( ct ){
			this.setOptions({				
				maxTime: $('#end_date').datetimepicker("getValue") ? $('#end_date').datetimepicker("getValue") : false
		   })
		}
	});
	
	$('#end_date').datetimepicker({
		format: 'm/d/Y H:i',
		formatTime:'g:i A', 
		onShow:function( ct ){
			this.setOptions({
				minDate: $('#start_date').val() ? $('#start_date').val() : $('#start_date').data('startdate'),
				minTime: $('#start_date').datetimepicker("getValue") && 
						$('#end_date').datetimepicker("getValue").toDateString() === $('#start_date').datetimepicker("getValue").toDateString() ? 
								$('#start_date').datetimepicker("getValue") : false
		   })
		},
		onChangeDateTime: function( ct ){
			this.setOptions({
				minTime: ($('#start_date').datetimepicker("getValue") && $('#end_date').datetimepicker("getValue")) && 
				$('#end_date').datetimepicker("getValue").toDateString() === $('#start_date').datetimepicker("getValue").toDateString() ? 
						$('#start_date').datetimepicker("getValue") : false
			})
		}
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
		
		var $help_block = $(this).next('span.help-block');		
		if($help_block.length === 0){
			var help_block = document.createElement('span');
			help_block.className = 'help-block';
			$help_block = $(help_block); 
			$(this).after($help_block);
		} else{
			$help_block.empty();
		}
		
		
		isValid = false;
		$(this).closest('.form-group').addClass('has-danger');
		$(this).addClass('form-control-danger');
		
		if( ($('#start_date').datetimepicker('getValue') && $('#end_date').datetimepicker('getValue')) &&
				($('#start_date').datetimepicker('getValue') > $('#end_date').datetimepicker('getValue'))){
			
			ruleName = $(this).attr('id') === 'start_date' ? 'before_or_equal' : 'after_or_equal';
			
			inputMessages[ruleName] = validationMessages[$(this).attr('name')][ruleName];
			inputMessages[ruleName] = inputMessages[ruleName].replace(/:date/, $(this).attr('id') === 'start_date' ? 'end_date' : 'start_date');
		} else {
			isValid = true;
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
}
	validateForm($('#create-calendar-event-form'));
});
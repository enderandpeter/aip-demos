jQuery(function($){	
	var validationMessages = JSON.parse(window.sessionStorage.getItem('validationMessages'));
	validationMessages.password_confirmation = validationMessages.password;
	
	var $register_button = $('#register_button');
	var $register_form = $('#register-form');
	
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
		
		if($register_form[0].checkValidity() && additionalCheck){
			$register_button.removeAttr('disabled');
		} else{
			$register_button.attr('disabled', 'disabled');
		}
	}
	
	checkFormValidity();
	
	$register_form.find('input').blur(function(event){
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
	
	$register_form.find('input[name^=password]').on('change keyup blur', function(event){
		var ruleName = '';
		var inputMessages = [];
		var isValid = true;
		
		var $help_block = $(this).next('span.help-block');
		if($help_block.length === 0){
			var help_block = document.createElement('span');
			help_block.className = 'help-block';
			$help_block = $(help_block); 
			$(this).after($help_block);
		}else {
			$help_block.empty();
		}
		
		$thisInput = $(this);
		
		if(!this.checkValidity()){
			isValid = false;
			if(this.validity.valueMissing){
				ruleName = 'required';
				
				inputMessages[ruleName] = validationMessages[$thisInput.attr('name')][ruleName];
			}
		}
		
		if(this.validity.tooLong){
			isValid = false;
			
			ruleName = 'max';
			
			inputMessages[ruleName] = validationMessages[$thisInput.attr('name')][ruleName];
		} 
		
		if(this.validity.tooShort){
			isValid = false;
			
			ruleName = 'min';
			
			inputMessages[ruleName] = validationMessages[$thisInput.attr('name')][ruleName];				
		} 
		
		if($('#register-form').find('input[name=password]').val() !== $('#register-form').find('input[name=password_confirmation]').val()){
			isValid = false;
			
			ruleName = 'confirmed';	
			
			inputMessages[ruleName] = validationMessages[$thisInput.attr('name')][ruleName];
		}
		
		if(isValid){
			$('input[name^=password]').closest('.form-group').removeClass('has-danger');
			$('input[name^=password]').removeClass('form-control-danger');
			$('input[name^=password]').next('span.help-block').remove();
			$register_button.removeAttr('disabled');
		} else {
			$register_button.attr('disabled', 'disabled');
			$(this).closest('.form-group').addClass('has-danger');
			$(this).addClass('form-control-danger');
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
		
		checkFormValidity(isValid);
	});	
});
jQuery(function($){	
	var validationMessages = JSON.parse(window.sessionStorage.getItem('validationMessages'));
	validationMessages.password_confirmation = validationMessages.password;
	
	$('#register-form input').blur(function(event){
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
			$(this).closest('.form-group').addClass('has-error');	
			
			if(this.validity.valueMissing){
				ruleName = 'required';
			}
			
			if(this.validity.rangeOverflow){
				ruleName = 'max';
			}
			
			if(this.validity.rangeUnderflow){
				ruleName = 'min';				
			}
			
			if(this.validity.typeMismatch && $(this).attr('name') === 'email'){
				ruleName = 'email';				
			}
			
			inputMessages[ruleName] = validationMessages[$(this).attr('name')][ruleName];
			
			
		} 
		if($(this).val().length > $(this).attr('maxlength')){
			isValid = false;
			
			$(this).closest('.form-group').addClass('has-error');
			ruleName = 'max';
			
			inputMessages[ruleName] = validationMessages[$(this).attr('name')][ruleName];
			
		} 
		
		if($(this).val().length < $(this).attr('minlength')){
			isValid = false;
			
			$(this).closest('.form-group').addClass('has-error');
			ruleName = 'min';
			
			inputMessages[ruleName] = validationMessages[$(this).attr('name')][ruleName];						
		} 
		
		if(isValid){
			$(this).closest('.form-group').removeClass('has-error');
			$help_block.remove();
		} else {
			for(var inputMessageRule in inputMessages){
				var inputMessage = inputMessages[inputMessageRule];
				var ruleMessageId = $thisInput.attr('name') + '-' + inputMessageRule;
				if($('strong#' + ruleMessageId).length === 0){
					var $strong = $(document.createElement('strong')).attr('id', $thisInput.attr('name') + '-' + inputMessageRule);
					$strong.text(inputMessage);
					$help_block.append($strong);
				}
			}
		}
	});
	
	$('#register-form input[name^=password]').on('change keyup', function(event){
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
		
		$thisInput = $('#register-form input[name=password]');
		$thisInput.closest('.form-group').addClass('has-error')
		
		if(!this.checkValidity()){
			isValid = false;
			if(this.validity.valueMissing){
				ruleName = 'required';
				
				inputMessages[ruleName] = validationMessages[$thisInput.attr('name')][ruleName];
			}
		}
		
		if($(this).val().length > $(this).attr('maxlength')){
			isValid = false;
			
			$(this).closest('.form-group').addClass('has-error');
			ruleName = 'max';
			
			inputMessages[ruleName] = validationMessages[$(this).attr('name')][ruleName];
		} 
		
		if($(this).val().length < $(this).attr('minlength')){
			isValid = false;
			
			$(this).closest('.form-group').addClass('has-error');
			ruleName = 'min';
			
			inputMessages[ruleName] = validationMessages[$(this).attr('name')][ruleName];				
		} 
		
		if($('#register-form input[name=password]').val() !== $('#register-form input[name=password_confirmation]').val()){
			isValid = false;
			
			ruleName = 'confirmed';	
			
			inputMessages[ruleName] = validationMessages[$thisInput.attr('name')][ruleName];
		}
		
		if(isValid){
			$(this).closest('.form-group').removeClass('has-error');
			$(this).next('span.help-block').remove();
		} else {
			for(var inputMessageRule in inputMessages){
				var inputMessage = inputMessages[inputMessageRule];
				var ruleMessageId = $thisInput.attr('name') + '-' + inputMessageRule;
				if($('strong#' + ruleMessageId).length === 0){
					var $strong = $(document.createElement('strong')).attr('id', $thisInput.attr('name') + '-' + inputMessageRule);
					$strong.text(inputMessage);
					$help_block.append($strong);
				}
			}
		}
	});	
});
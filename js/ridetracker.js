//
// ride (namespace)
//
// Self Executing Anonymous Function:
// -Enables use of private and public properties/methods
// -Also protects jQuery $ and undefined from conflicts
//

(function(ride, undefined) {

	// -----------------------------------------
	// PUBLIC
	//
	// Properties
	//
	ride.property = '';


	// -----------------------------------------
	// PRIVATE
	//
	// Properties
	//
	var privateProperty = '';


	// -----------------------------------------
	// PRIVATE
	//
	// Methods
	//


	// -----------------------------------------
	// PUBLIC
	//
	// Methods
	//

	ride.init = function(){

	};

	ride.forms = function(){
		
		$('form').on('submit', function(event){
			event.preventDefault();
			var $this = $(this);
			var refresh = $this.data('refresh');
			$.ajax({
				url: $this.attr('action'),
				type: 'POST',
				data: $this.serialize(),
				success: function(data){
					console.log(data);
					$('.ajax-message').html(data);
					$('.refresh-this').load(refresh);
					$this.find('input[type=text]').val('');
				}
			});
		});

	}



	// -----------------------------------------
	// ride on
	//
	$(document).ready(function() {
		ride.init();
		ride.forms();
	});


}(window.ride = window.ride || {}, jQuery));

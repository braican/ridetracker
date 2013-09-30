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

	//
	// getUrlParam
	//
	// Utility function to snag a query string value when passed the parameter
	//
	function getUrlParam(name) {
		var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
		if (!results) {
	  		return 0;
		}
		return results[1] || 0;
  	}


	// -----------------------------------------
	// PUBLIC
	//
	// Methods
	//

	// console.log(getUrlParam('route'));
	ride.init = function(){
		
		if($('#canvas').length > 0){

			$.ajax({
				url: 'util/charts.php?route=' + getUrlParam('route'),
				type: 'GET',
				dataType: 'json',
				success: function(data){
					console.log(data.from);
					var lineChartData = {
						labels : ["January","February","March","April","May"],
						datasets : [
							{
								fillColor : "rgba(220,220,220,0.5)",
								strokeColor : "rgba(220,220,220,1)",
								pointColor : "rgba(220,220,220,1)",
								pointStrokeColor : "#fff",
								data : data.to
							},
							{
								fillColor : "rgba(188,188,188,0.5)",
								strokeColor : "rgba(188,188,188,1)",
								pointColor : "rgba(188,188,188,1)",
								pointStrokeColor : "#fff",
								data : data.from
							}
						]
						
					}

					var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Line(lineChartData);
				}
			});
			
			
		}

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

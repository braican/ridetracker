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
	var listTop = $('.ride-list').position().top - 100;
	var listBottom = $('.ride-list').position().top + $('.ride-row-container').height() - 80;


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

  	function getTheCharts(){
  		var canvases = document.getElementsByTagName('canvas'),
  			ww = $('.the-charts').width();;
  		
  		$.each(canvases, function(index, val) {
  			 val.width = ww;
  		});

  		$.ajax({
  			url: 'util/charts.php?route=' + getUrlParam('route'),
  			type: 'GET',
  			dataType: 'json',
  			success: function(data){
				console.log(data);

				var fromArray = data.from;
				var toArray = data.to;

				var lowest = Array.min(fromArray.concat(toArray));
				lowest = Math.round(lowest / 60) * 60;

				console.log(lowest);

				var isPhone = ww < 481;

				var chartOptions = {
					scaleShowLabels : !isPhone,
					scaleOverride : true,		//Boolean - If we want to override with a hard coded scale
					scaleSteps : 16,			//Number - The number of steps in a hard coded scale
					scaleStepWidth : 30, 		//Number - The value jump in the hard coded scale
					scaleStartValue : lowest - 120,		//Number - The scale starting value
					scaleFontFamily : "'Courier New'",
					scaleLabel : "<%= Math.floor(value/3600) %>:<%= Math.floor((value % 3600) / 60) %>:<%= ((value%3600) % 60) < 10 ? '0'+((value%3600)%60) : ((value%3600)%60)%>"
				}

				if(toArray.length){
					// the chart -> to the place
					var toData = {
						labels : data.to_dates,
						datasets : [
							{
								fillColor : "rgba(188,188,188,0.5)",
								strokeColor : "rgba(188,188,188,1)",
								pointColor : "#900",
								pointStrokeColor : "#900",
								data : data.to
							}
						]
					};
					var toChart = new Chart(document.getElementById("to-canvas").getContext("2d")).Line(toData, chartOptions);
				} else {
					$('#to-canvas-container').hide();
				}

				if(fromArray.length){
					// the chart <- from the place
					var fromData = {
						labels : data.from_dates,
						datasets : [
							{
								fillColor : "rgba(188,188,188,0.5)",
								strokeColor : "rgba(188,188,188,1)",
								pointColor : "#090",
								pointStrokeColor : "#090",
								data : data.from
							}
						],
						
					};
					var fromChart = new Chart(document.getElementById("from-canvas").getContext("2d")).Line(fromData, chartOptions);
				} else{
					$('#from-canvas-container').hide();
				}
  				
  			}
  		});
  	}


	// -----------------------------------------
	// PUBLIC
	//
	// Methods
	//

	Array.min = function( array ){
	    return Math.min.apply( Math, array );
	};

	// document ready
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
					if($this.attr('id') == 'add-ride-form'){
						getTheCharts();
					}
				}
			});
		});

	}

	// window load
	ride.windowload = function(){
		if($('.the-charts').length > 0){
			getTheCharts();
			// $('canvas').width($('.the-charts').width());

		}
	}


	// -----------------------------------------
	// ride on
	//
	$(document).ready(function() {
		ride.init();
		ride.forms();
	});

	$(window).load(function() {
		ride.windowload();
	});

	$(window).scroll(function(){
		var sTop = $(window).scrollTop();
		if(sTop > listTop) {
			$('.rides-headers').addClass('out');
		}
		if(sTop > listBottom || sTop < listTop){
			$('.rides-headers').removeClass('out');
		}
	});


}(window.ride = window.ride || {}, jQuery));

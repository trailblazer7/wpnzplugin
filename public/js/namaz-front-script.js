var nz = nz || {};

jQuery(function() {
	var $ = jQuery
	  , $wrapper = $('#namaz-plugin')
	  , selectedVal
	  , $dateSelect = $('#namaz-plugin-date-select')
	  , $citySelect = $('#namaz-plugin-select');

	  $dateSelect.change(_selectCallback);
	  $citySelect.change(_selectCallback);

	  function _selectCallback() {
	  	var date = $dateSelect.val()
	  	  , city = $citySelect.val();

  		jQuery.ajax({
	         type : "post",
	         dataType : "json",
	         url : namazAjax.ajaxurl,
	         data : {action: "namaz_selected", city: city, date: date},
	         success: function(data) {
	         	
		  		if (data.namaz) {
		  			$wrapper.find('.np-time-block .np-time').each(function (i, el) {
	  					$(this).text(data.namaz['prayer_time'+(i+1)]);
	  				});
		  		} else {
		  			var $errorMes = $('<div class="np-error-mess">На эту дату и город рассписания не обнаружено. Выберите другие.</div>');
		  			$wrapper.prepend($errorMes);

		  			$errorMes.fadeOut(5000, function() {
		  				$errorMes.remove();
		  			});
		  		}

		  		if (data.namaz && data.namaz['altdate']) {
		  			$wrapper.find('.np-info-altdate').text(data.namaz['altdate']);
		  		}

		  		if (data.text) {
		  			$wrapper.find('.np-text-block').text(data.text['namaz_description']);
		  		}

	         },
	         error: function(resp, stat, error) {
	         	console.log(resp);
	         	throw error;
	         }
	      })  
	  }
});
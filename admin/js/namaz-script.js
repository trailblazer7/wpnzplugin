/*
 * Namaz Plugin global variable nz 
 */

var nz = nz || {};

jQuery(function() {
	var $ = jQuery,
		_months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
		_dataStore = {};

	function _getAddTimeRowHTML(city, altdate, times, date, nextDate) {
		if (!date) { 
			var _tuday = new Date();
			date = _tuday.getDate() +'-'+_months[_tuday.getMonth()]+'-'+_tuday.getFullYear(); 
		}
		if (!altdate) { altdate = ''; }
		if (!times) { times = ['', '', '', '', '']; }

		var _tr, timesNames, datesNames, addButton;
		if (!nextDate) {
			addButton = '<span class="button button-primary add-time" onclick="nz.addTimeHandler(this)">Добавить время</span>';
			_tr = '<tr>';
			timesNames = ['', '', '', '', ''];
			datesNames = ['', ''];
		} else {
			addButton = '<span class="button button-secondary remove-city" onclick="nz.removeTimeRow(this)">Удалить</span>';
			_tr = '<tr class="added-row">';
			timesNames = ['prayer_time1['+city+'][]', 'prayer_time2['+city+'][]', 'prayer_time3['+city+'][]', 'prayer_time4['+city+'][]', 'prayer_time5['+city+'][]'];
			datesNames = ['date['+city+'][]', 'altdate['+city+'][]'];
		}

		return _tr +
			'<th class="thin">Дата: <code class="mainDate" data-city="'+city+'">'+ date +'</code><input name="'+datesNames[0]+'" type="hidden" value="'+$.trim(date)+'"></th>'+
			'<td class="thin"><input name="'+datesNames[1]+'" class="regular-text namaz-input altdate" placeholder="Дата в Исламе" value="'+altdate+'"/></td>'+
			'<td class="thin">'+
				'<input name="'+timesNames[0]+'" class="regular-text namaz-input-small pt1" placeholder="İsmak" value="'+times[0]+'"/>'+
				'<input name="'+timesNames[1]+'" class="regular-text namaz-input-small pt2" placeholder="Sübh" value="'+times[1]+'"/>'+
				'<input name="'+timesNames[2]+'" class="regular-text namaz-input-small pt3" placeholder="Gün çıxır" value="'+times[2]+'"/>'+
				'<input name="'+timesNames[3]+'" class="regular-text namaz-input-small pt4" placeholder="Zöhr" value="'+times[3]+'"/>'+
				'<input name="'+timesNames[4]+'" class="regular-text namaz-input-small pt5" placeholder="Məğrib" value="'+times[4]+'"/>'+ addButton +
			'</td>'+
		'</tr>';
	}

	function _getLockedRow(city) {
		return $(
			'<tr class="city-block"><td colspan="3"><table class="form-table">'+
				'<tr>'+
					'<th class="thin" scope="row"><label>Город:</label></th>'+
					'<td width="15%" class="thin"><code class="city-wrap">'+$.trim(city)+'</code><input name="city[]" type="hidden" value="'+$.trim(city)+'"></td>'+
					'<td class="thin"></td>'+
				'</tr>'+
				 _getAddTimeRowHTML(city) +
				'<tr><td class="thin-hor thin"><span class="button button-secondary remove-city" onclick="nz.removeSityRow(this)">Удалить город</span></td></tr>'+
			'</table></td></tr>');
	}

	nz.removeSityRow = function (context) {
		if (confirm('Время этого города будет удалено вместе с ним после сохранения. Уверены что хотите удалить город?')) {
			$(context).closest('.city-block').remove();
		}
	}

	nz.removeTimeRow = function (context) {
		if (confirm('Время будет удалено после сохранения. Удалить?')) {
			$(context).closest('tr').remove();
		}
	}

	function _refreshTimeRow($tRow) {
		$tRow.find('input').each(function(i, el) {
			$(el).val('');
		});

		var $mainDate = $tRow.find('.mainDate'),
			lastDate = new Date($mainDate.text()),
			afterLast = new Date();

		// Set next date
		afterLast.setMonth(lastDate.getMonth());
		afterLast.setDate(lastDate.getDate()+1);
		$mainDate.html(afterLast.getDate()+'-'+_months[afterLast.getMonth()]+'-'+afterLast.getFullYear()); 
	}

	 nz.addTimeHandler = function (context) {
		var $timeRow = $(context).closest('tr'),
			dateVal = $timeRow.find('.mainDate').text(),
			altDateVal = $timeRow.find('input.altdate').val(),
			city = $timeRow.find('.mainDate').data('city'),
			timesVal = [
				$timeRow.find('input.pt1').val(),
				$timeRow.find('input.pt2').val(),
				$timeRow.find('input.pt3').val(),
				$timeRow.find('input.pt4').val(),
				$timeRow.find('input.pt5').val(),
			];

		if (!altDateVal.length || !timesVal[0].length || !timesVal[1].length || !timesVal[2].length || !timesVal[3].length || !timesVal[4].length) {
			alert('Заполните все поля.');
			return;
		}

		$(_getAddTimeRowHTML(city, altDateVal, timesVal, dateVal, true)).insertBefore($timeRow);
		_refreshTimeRow($timeRow);
	}

	nz.addDateText = function (context) {
		var $textRow = $(context).closest('tr'),
			$dateEl = $textRow.find('code.desc-date'),
			$inputDate = $textRow.find('.desc-date-input'),
			$textInput = $textRow.find('.namaz-description'),
			lastDate = new Date( $dateEl.text() ),
			nextDate = new Date();

			if (!$textInput.val().length) {
				alert('Введите пожалуйста текст.');
				return;
			}

			// Set next date
			nextDate.setMonth(lastDate.getMonth());
			nextDate.setDate(lastDate.getDate()+1);
			nextDateStr = nextDate.getDate()+'-'+_months[nextDate.getMonth()]+'-'+nextDate.getFullYear();

		// Clone filled row, insert before with typed data
		var $clonedRow = $textRow.clone();

		$clonedRow
			.find('code.desc-date').text($dateEl.text())
			.find('.desc-date-input').val($dateEl.text())
			.find('.namaz-description').val($textInput.val());

		$clonedRow.insertBefore($textRow);

		$clonedRow
			.addClass('added-row')
			.find('.add-text')
			.removeClass('button-primary')
			.removeClass('add-text')
			.addClass('button-secondary')
			.addClass('remove-text')
			.text('Удалить')
			.attr('onclick', 'nz.removeDateText(this)');

		// Clear text field and set date + 1
		$textInput.val('');
		$dateEl.text(nextDateStr);
		$inputDate.val(nextDateStr);
	}

	nz.removeDateText = function (context) {
		if (confirm('Текст будет удален после сохранения. Удалить?')) {
			$(context).closest('tr').remove();
		}
	}

	$('.add-city').click(function() {
		var $cityRow = $(this).closest('tr'),
			$cityInput = $cityRow.find('.regular-text');

		if (!$cityInput.val()) {
			alert("Введите название города для добавления.");
			return;
		}

		_getLockedRow($cityInput.val()).insertBefore($cityRow);
		$cityInput.val('');
	});
});
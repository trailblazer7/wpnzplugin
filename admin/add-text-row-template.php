<tr <?php if (!empty($dateText)) { ?> class="added-row" <?php } ?> >
	<th class="thin">
		Дата: <code class="desc-date"><?php echo $textDate; ?></code>
		<input name="desc-date[]" class="desc-date-input" type="hidden" value="<?php echo $textDate; ?>">
	</th>
	<td class="namaz-text-td">
		<textarea rows="5" cols="70" class="namaz-description" name="namaz-description[]" 
		value="<?php echo $dateText; ?>"><?php echo $dateText; ?></textarea>
	</td>
	<td>
		<?php if (!empty($dateText)) { ?>
			<span class="button button-secondary remove-text" onclick="nz.removeDateText(this)">Удалить</span>
		<?php } else { ?>
			<span class="button button-primary add-text" onclick="nz.addDateText(this)">Добавить</span>
		<?php } ?>
	</td>
</tr>
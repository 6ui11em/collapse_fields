jQuery(document).ready(function() {
	// Insert anchor that will act as toggle to collapse/uncollapse the sectionfields
	jQuery('.field-collapse_fields').each(function() {
		var link = jQuery(this).find('a');
		toggleFields(link);
	});
	
	jQuery(".field-collapse_fields").click(function() {
		var link = jQuery(this).find('a');
		toggleFields(link);	
		return false;
	});

});

function toggleFields(link) {
	var numFields = link.attr('href').match(/\d+\.?\d*/g);
	var container = link.parents('.field-collapse_fields');
	var text = link.text();
	var aNextFields = container.nextAll('.field');
	var hasErrors = container.nextAll('.field').children('.invalid').length > 0;

	if (link.hasClass('hide') && aNextFields.length > 0 && !hasErrors) {
		for (i=0; i<numFields && i<aNextFields.length; i++) {
			jQuery(aNextFields[i]).hide();		
			if (i== numFields-1 || i==aNextFields.length-1)
				jQuery(aNextFields[i]).addClass('collapse_fields_last');
		}
		link.removeClass('hide');
		container.removeClass('selected');

		link.text(text.replace('(-)','(+)'));
	} else {
		for (i=0; i<numFields && i<aNextFields.length; i++) {
			jQuery(aNextFields[i]).show();
			if (i== numFields-1 || i==aNextFields.length-1)
				jQuery(aNextFields[i]).addClass('collapse_fields_last');
		}
		
		link.addClass('hide');
		container.addClass('selected');

		link.text(text.replace('(+)','(-)'));
	}
}
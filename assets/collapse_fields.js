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

	if (link.hasClass('hide') && aNextFields.length > 0) {
		for (i=0; i<numFields && i<aNextFields.length; i++)
			jQuery(aNextFields[i]).hide();
		link.removeClass('hide');
		container.removeClass('selected');

		link.text(text.replace('(-)','(+)'));
	} else {
		for (i=0; i<numFields && i<aNextFields.length; i++)
			jQuery(aNextFields[i]).show();
		link.addClass('hide');
		container.addClass('selected');

		link.text(text.replace('(+)','(-)'));
	}
}
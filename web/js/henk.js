jQuery.each(jQuery.browser, function(i) {
	if($.browser.msie)
		$('#tabs a[href="#ie"]').tab('show');
	else if($.browser.mozilla)
		$('#tabs a[href="#firefox"]').tab('show');
	else if($.browser.chrome)
		$('#tabs a[href="#chrome"]').tab('show');
	else if($.browser.opera)
		$('#tabs a[href="#opera"]').tab('show');
});
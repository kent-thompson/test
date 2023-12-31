function gGetBasepath() {
	var loc = window.location.hostname;
	if( loc.indexOf('local') != -1 ) {
		return "http://localhost:8000";
	} else {
		return "http://74.208.242.240:4000";
	}
}

function gClearForm(ele) {
    $(ele).find(':input').each(function() {
        switch(this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
            case 'date':
                $(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;
        }
    });
}

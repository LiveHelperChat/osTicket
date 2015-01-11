var osTicket = {
	createTicket : function(chat_id) {		
		$('#os-tickter-'+chat_id).attr('disabled','disabled');		
		$.postJSON(WWW_DIR_JAVASCRIPT  + 'osticket/createanissue/' + chat_id, function(data){
			if (data.error == false) {
				$('#os-tickter-'+chat_id).replaceWith(data.msg);
			} else {
				alert(data.msg);
				$('#os-tickter-'+chat_id).removeAttr('disabled');
			}			
        });	
		return false;
	}	
};
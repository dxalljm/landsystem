function assign(key) {
		$.get(
		    'index.php',         
		    {
		    	r: 'assignment/assignmentindex',
		        id: key,
		         
		    },
		    function (data) {
		        $('.modal-body').html(data);
		        
		    }  
		);
}

function businessupdate(key) {
	// $('#createassign').click(function() {
		// alert(key);
		$.get(
		    'index.php',         
		    {
		    	r: 'business/businessupdate',
		        id: key,
		         
		    },
		    function (data) {
		        $('.modal-body').html(data);
		        
		    }  
		);
	// });
}

function setField(tablename) {
	$('#tablefieldscreate').val(tablename);
	$('#tablefields-modal').modal('hide');
}

function setMenuUserid(user_id) {
	$('#menutousercreate').val(user_id);
	$('#menutousercreate-modal').modal('hide');
}

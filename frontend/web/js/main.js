function farmercontract(key) {
	// $('#createassign').click(function() {
		// alert(key);
		$.get(
		    'index.php',         
		    {
		    	r: 'farmer/farmercontract',
		        id: key,
		         
		    },
		    function (data) {
		        $('.modal-body').html(data);
		        
		    }  
		);
	// });
}
function farmercreate(key) {
	// $('#createassign').click(function() {
		// alert(key);
		$.get(
		    'index.php',         
		    {
		    	r: 'farmer/farmercreate',
		        id: key,
		        year:$('#theyear-years').val(),
		    },
		    function (data) {
		        $('.modal-body').html(data);
		        
		    }  
		);
	// });
}

function leaseindex(farmsid) {
	// $('#createassign').click(function() {
		// alert(key);
		$.get(
		    'index.php',         
		    {
		    	r: 'lease/leaseindex',
		        id: farmsid,
		    },
		    function (data) {
		        $('.modal-body').html(data);
		        
		    }  
		);
	// });
}
function collectioncreate(farmsid,cardid) {
	$.get(
	    'index.php',         
	    {
	    	r: 'collection/collectioncreate',
	        farmsid: farmsid,
	        cardid:cardid,
	        year:$('#years').val(),
	        
	    },
	    function (data) {
	        $('.modal-body').html(data);
	        
	    }  
	);
// });
}
function leasecreate(farmsid,theyear) {
	// $('#createassign').click(function() {
		// alert(key);
		$.get(
		    'index.php',         
		    {
		    	r: 'lease/leasecreate',
		        id: farmsid,
		        year:theyear,
		    },
		    function (data) {
		        $('.modal-body').html(data);
		        
		    }  
		);
		
	// });
}
function offleasecreate() {
	$('#leasecreate-modal').modal('hide');
}


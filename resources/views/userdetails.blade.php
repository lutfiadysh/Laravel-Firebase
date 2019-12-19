@yield('content') 
    	{{-- <div class="col-md-4">
    		<div class="card card-default mt-5">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-10">
                            <strong>Add User</strong>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form id="addUser" class="" method="POST" action="">
                    	<div class="form-group">
                            <label for="first_name" class="col-md-12 col-form-label">First Name</label>

                            <div class="col-md-12">
                                <input id="first_name" type="text" class="form-control" name="first_name" value="" required autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="col-md-12 col-form-label">Last Name</label>

                            <div class="col-md-12">
                                <input id="last_name" type="text" class="form-control" name="last_name" value="" required autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 col-md-offset-3">
                                <button type="button" class="btn btn-primary btn-block desabled" id="submitUser">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    	</div> --}}
        <div class="card card-default mt-5">
            <div class="card-header bg-success">
                <h3 class="text-white">Data SHUN Siswa</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered py-30">
                    <tr>
                        <th>NISN</th>
                        <th class="text-center">TANGGAL</th>
                        <th class="text-center">Asal Sekolah</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Tanggal Lahir</th>
                        <th class="text-center">UN</th>
                        <th class="text-center">Rombel</th>
                        <th class="text-center">Rayon</th>
                        <th>NIS</th>
                    </tr>
                    <tbody id="tbody">
                        
                    </tbody>	
                </table>
            </div>
        </div>

<!-- Delete Model -->
<form action="" method="POST" class="users-remove-record-model">
    <div id="remove-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="width:55%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="custom-width-modalLabel">Delete Record</h4>
                    <button type="button" class="close remove-data-from-delete-form" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <h4>You Sure Want Delete This Record?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger waves-effect waves-light deleteMatchRecord">Delete</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Update Model -->
<form action="" method="POST" class="users-update-record-model form-horizontal">
    <div id="update-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="width:55%;">
            <div class="modal-content" style="overflow: hidden;">
                <div class="modal-header">
                    <h4 class="modal-title" id="custom-width-modalLabel">Update Record</h4>
                    <button type="button" class="close update-data-from-delete-form" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body" id="updateBody">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect update-data-from-delete-form" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success waves-effect waves-light updateUserRecord">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>
@push('js')
<script src="https://www.gstatic.com/firebasejs/4.9.1/firebase.js"></script>
<script>
// Initialize Firebase
var config = {
    apiKey: "{{ config('services.firebase.api_key') }}",
    authDomain: "{{ config('services.firebase.auth_domain') }}",
    databaseURL: "{{ config('services.firebase.database_url') }}",
    storageBucket: "{{ config('services.firebase.storage_bucket') }}",
};
firebase.initializeApp(config);

var database = firebase.database();

var lastIndex = 0;

// Get Data
firebase.database().ref('data/shun').on('value', function(snapshot) {
    var $load = $('<div class="loading">Loading...</div>').appendTo('body')
    ,db = database.ref('data/')

    db.on('value', function () {
    $load.hide()
    })
    var value = snapshot.val();
    var htmls = [];
    $.each(value, function(index, value){
        var data = value.title + toString();
        var pisah = data.split('r');
    	if(value) {
    		htmls.push('<tr>\
        		<td>'+ pisah[0] +'</td>\
                <td>'+ pisah[1] +'</td>\
                <td>'+ pisah[2] +'</td>\
                <td>'+ pisah[3] +'</td>\
                <td>'+ pisah[4] +'</td>\
                <td>'+ pisah[5] +'</td>\
                <td>'+ value.rombel +'</td>\
                <td>'+ value.rayon +'</td>\
                <td>'+ value.name +'</td>\
        	</tr>');
    	}    	
    	lastIndex = index;
    });
    $('#tbody').html(htmls);
    $("#submitUser").removeClass('desabled');
});


// Add Data
$('#submitUser').on('click', function(){
	var values = $("#addUser").serializeArray();
	var first_name = values[0].value;
	var last_name = values[1].value;
	var userID = lastIndex+1;

    firebase.database().ref('users/' + userID).set({
        first_name: first_name,
        last_name: last_name,
    });

    // Reassign lastID value
    lastIndex = userID;
	$("#addUser input").val("");
});

// Update Data
var updateID = 0;
$('body').on('click', '.updateData', function() {
	updateID = $(this).attr('data-id');
	firebase.database().ref('users/' + updateID).on('value', function(snapshot) {
		var values = snapshot.val();
		var updateData = '<div class="form-group">\
		        <label for="first_name" class="col-md-12 col-form-label">First Name</label>\
		        <div class="col-md-12">\
		            <input id="first_name" type="text" class="form-control" name="first_name" value="'+values.first_name+'" required autofocus>\
		        </div>\
		    </div>\
		    <div class="form-group">\
		        <label for="last_name" class="col-md-12 col-form-label">Last Name</label>\
		        <div class="col-md-12">\
		            <input id="last_name" type="text" class="form-control" name="last_name" value="'+values.last_name+'" required autofocus>\
		        </div>\
		    </div>';

		    $('#updateBody').html(updateData);
	});
});

$('.updateUserRecord').on('click', function() {
	var values = $(".users-update-record-model").serializeArray();
	var postData = {
	    first_name : values[0].value,
	    last_name : values[1].value,
	};

	var updates = {};
	updates['/users/' + updateID] = postData;

	firebase.database().ref().update(updates);

	$("#update-modal").modal('hide');
});


// Remove Data
$("body").on('click', '.removeData', function() {
	var id = $(this).attr('data-id');
	$('body').find('.users-remove-record-model').append('<input name="id" type="hidden" value="'+ id +'">');
});

$('.deleteMatchRecord').on('click', function(){
	var values = $(".users-remove-record-model").serializeArray();
	var id = values[0].value;
	firebase.database().ref('users/' + id).remove();
    $('body').find('.users-remove-record-model').find( "input" ).remove();
	$("#remove-modal").modal('hide');
});
$('.remove-data-from-delete-form').click(function() {
	$('body').find('.users-remove-record-model').find( "input" ).remove();
});
</script>
@endpush
<body>
<div class="col-center">
    <!-- DATA Table -->
    <h5>User List</h5>
    <button type="button" class="btn btn-primary btn-vertical-bmargin" onclick="addUser()" >Add User</button>
    <button type="button" class="btn btn-primary btn-vertical-bmargin" onclick="$('#backform').submit()" >Back</button><br><br>
    <table id="usertable" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>UserName</th>
                <th>FirstName</th>
                <th>LastName</th>
                <th style="min-width: 8em">Birthday</th>
                <th>Email</th>
                <th>Password</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <button type="button" class="btn btn-primary btn-vertical-bmargin" onclick="addUser()" >Add User</button>
    <button type="button" class="btn btn-primary btn-vertical-bmargin" onclick="$('#backform').submit()" >Back</button>
    <p></p>
    <form id='backform' action="/home/indexAuth" method="POST"><input type="hidden" id="ejwt" name="jwt"></form>

    <!-- Model Dialog -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 input='text' id="txtAction" readonly ></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="uform" action="" method="POST">
                        <input type="hidden" id="jwt" name="jwt">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="col-md-3">
                                    <label class="control-label">UserID:</label>
                                    <input type="text" class="position-userid form-control-plaintext" id="docid" name="docid" value="" readonly />
                                </div>
                            </div> 
                        </div>    
                        <div class="form-group">
                            <label for="uname"><b>Username</b></label><br/>
                            <input class="form-control" placeholder="Enter Username" id="uname" name="uname" value='' autofocus required>
                        </div>
                        <div class="form-group">
                            <label for="fname"><b>First Name</b></label><br/>
                            <input class="form-control" placeholder="Enter First Name" id='fname' name="fname" value='' required>
                        </div>
                        <div class="form-group">
                            <label for="lname"><b>Last Name</b></label><br/>
                            <input class="form-control" placeholder="Enter Last Name" id='lname' name="lname" value='' required>
                        </div>
                        <div class="form-group">
                            <label for="bdate"><b>Birthdate</b></label><br/>
                            <input type="date" class="form-control" id='bdate' name="birthday" value='' required>
                        </div>
                        <div class="form-group">	
                            <label for="email"><b>Email</b></label><br/>
                            <input type="email" class="form-control" placeholder="Enter Email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="psw"><b>Password</b></label><br/>
                            <input type="password" class="form-control" placeholder="Enter Password" id="psw" name="psw" required>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary float-md-left" >Save</button>&nbsp
                        <button type="button" class="btn btn-primary" onclick="$('#myModal').modal('hide');">Cancel</button>
                        <button type="button" class="btn btn-primary float-md-right" onclick="$('#msgBox').modal('show');">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        <p></p>
    </div>

    <!-- Modal Pop-Up -->
    <div id="msgBox" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="magBoxTitle" class="modal-title">Delete User</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button><br>
                </div>
                <div id=popup-msg class="modal-body">Delete This User?</div>
                <div class="modal-footer">
                    <button type="button" id="btnOk"  class="btn btn-success" onclick="sendAjax( eAction.DELETE );">OK</button>  
                    <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var gDTable = null;
var gID = null;
var txtAction = '';
var gDBAction = 0;
var eAction = Object.freeze({ // enum
    ADD: 1,
    EDIT: 2,
    DELETE: 3
});

$(document).ready(function() {

// scrollToPager = () => {
//   var y = $(window).scrollTop();
//   $('html, body').animate({
//     scrollTop: y + $('#myTable').height()
//   })
// }
// var w_h = window.innerHeight;
// var w_height = window.innerHeight  = (window.innerHeight * 0.5);

    gDtable = $('#usertable').DataTable( {
        ajax: {
            type:'POST',
            dataType:'json',
            url: location.origin + '/api/user/getAllUsers',
            dataSrc: "",
            headers: {"Authorization": 'Bearer '+ sessionStorage.getItem('ktc_token')},
            error: function (xhr, error, code) {
                var err = xhr.statusText +  ', ' + xhr.responseText;
                alert( err );
                console.log(xhr, code); },
        },
        "columns": [
        { "data": "UserID" },
        { "data": "UserName" },
        { "data": "FirstName" },
        { "data": "LastName" },
        { "data": "Birthday" },
        { "data": "Email" },
        { "data": "Password" }
    ],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "pageLength": 25
    });
});


// show empty user form
addUser = () => {   // :(
//function addUser() {
    gClearForm( $('#uform') );
    gDBAction = eAction.ADD;
    $('#psw').attr('readonly', false);
    $('#txtAction').text('Add User')

    $('#myModal').modal({
        backdrop: 'static',
        keyboard: false
    });    
}

// $("#usertable").on( 'done', function () {
//     //console.log( 'Redraw occurred at: '+new Date().getTime() );
//     $("#usertable").append('<p>');
// } );

// selection & update / edit
$("#usertable").on("dblclick", "tr", function(e) {
    gDBAction = eAction.EDIT;
    gID = $(this).children(':first').html();
    $('#psw').attr('readonly', true);
    $('#txtAction').text('Edit User')
    getUserData( gID );

    $('#myModal').modal({
        backdrop: 'static',
        keyboard: false
    });
});


// VALIDATE using Bootstrap and then ajax it
$('#uform').submit( function(e) {
    e.preventDefault();
    if( $('#uform')[0].checkValidity() === false ) {
        e.stopPropagation();
    } else {
        // $('#uform').addClass('was-validated');
        sendAjax( gDBAction );
    }
});

// add token and redirect
$('#backform').on('submit', function() {
    $('#ejwt').val( sessionStorage.getItem('ktc_token') );
    return true;
});

// "autofocus" first editible field
$(".modal").on('shown.bs.modal', function () {
    $('#uname').focus();
});

// *** API CALLS ***
function sendAjax( action ) {	                // handles add, update, delete
    var ddata = null;

    if( action === eAction.ADD ) {
        apiPath = location.origin + '/api/user/adduser';
        ddata = $('#uform').serialize();
    } 

    if( action === eAction.EDIT ) {
        apiPath =location.origin + '/api/user/updateuser';
        ddata = $('#uform').serialize();
    }

    if( action === eAction.DELETE )  {
        $('#msgBox').modal('hide');
        apiPath = location.origin + '/api/user/deleteUserById';
        ddata = {'docid':gID};    
    }
    
    $.ajax({
        url: apiPath,
        type:'POST',
        dataType:'json',
        headers: {"Authorization": 'Bearer '+ sessionStorage.getItem('ktc_token')},
        data: ddata
    })
    .done( function( data, textStatus, jQxhr ) {
        if( textStatus == 'success' ) {
            $('#myModal').modal('hide');
            gDtable.ajax.reload();
        }
    })
    .fail( function( jqXhr, textStatus, errorThrown ) {
            console.log(jqXhr, textStatus, errorThrown);
            // RE-USING this general popup msgBox
            $('#magBoxTitle').text('Validation Errors');
            $('#popup-msg').text( JSON.stringify(jqXhr.responseJSON) );
            $('#msgBox').modal('show');
    });
}

function getUserData( id ) {
    $.ajax({
        url: location.origin + '/api/user/getUser', // byID
        dataType:'json',
        type:'post',
        headers: {"Authorization": 'Bearer '+ sessionStorage.getItem('ktc_token')},
        data: { 'docid': id }
    })
    .done( function( data, textStatus, jQxhr ) {
        $('#docid').val(data.UserID);
        $('#uname').val(data.UserName);
        $('#fname').val(data.FirstName);
        $('#lname').val(data.LastName);
        $('#email').val(data.Email);
        $('#bdate').val(data.Birthday);
        $('#psw').val(data.Password);
    })
    .fail( function( jqXhr, textStatus, errorThrown ) {
        console.log( errorThrown );
        alert( textStatus + ", " + errorThrown )
    });
}
/*

function deleteMe() {
    $.ajax({
        url: gBasepath + '/api/deleteuserbyid',
        dataType:'json',
        type:'post',
        headers: {"Authorization": 'Bearer '+ sessionStorage.getItem('ktc_token')},
        data: { 'docid':gID }    
    })
    .done( function( data, textStatus, jQxhr ) {
        if( textStatus == 'success' ) {
            $('#myModal').modal('hide');
            gDtable.ajax.reload();
        }
    })
    .fail( function( jqXhr, textStatus, errorThrown ) {
        console.log( errorThrown );
        alert( textStatus + ", " + errorThrown )
    });
}
*/
</script>
</body>
</html>
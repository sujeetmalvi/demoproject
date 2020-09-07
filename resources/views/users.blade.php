@extends('maintemplate')


@section('content')
  <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Users</h1>
          </div>
          @if(Auth::user()->company_id==2)
          <div class="col-sm-6">
            <form name="csv_upload" id="csv_upload" method='post' action='/importCsvUsers' enctype='multipart/form-data' >
               {{ csrf_field() }}
               <input class="" type='file' name='file' id="file" >
               <input class="btn btn-sm btn-warning" type='submit' name='submit' value='Import'>
             </form>
             <a href="/format.csv" target="_blank">CSV Format</a>
          </div>
          @endif
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row" id="list">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users List </h3>
                <button class="btn btn-sm btn-success" style="float:right;" id='add'><i class="fas fa-plus"></i> New</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($data as $d)
                  <tr>
                    <td>{{$d->name}}</td>
                    <td>{{$d->email}}</td>
                    <td>{{$d->company_name}}</td>
                    <td>
                      <!-- <a class="btn btn-info btn-sm edit" data-id="{{$d->id}}" href="#"><i class="fas fa-pencil-alt"></i></a>
                      <a class="btn btn-danger btn-sm delete" data-id="{{$d->id}}" href=""><i class="fas fa-trash"></i></a> -->
                    </td>
                  </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div> <!-- list row -->

        <div class="row" id="new" style="display:none;">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Create User</h3>
                <button class="btn btn-sm btn-success" style="float:right;" id='showlist'><i class="fas fa-plus"></i> List</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="/create_user" name="create_user" id="create_user" method="post">
                  {{ csrf_field() }}
                    <div class="form-group">
                      <label for="name">Name *</label>
                      <input type="text" id="name" name="name" class="form-control" value="" autocomplete="off" required="">
                    </div>
                    <div class="form-group">
                      <label for="email">Email *</label>
                      <input type="text" id="email" name="email" class="form-control" value="" autocomplete="off" required="">
                    </div>
                    
                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-6">
                          <label for="password">Password *</label>
                          <input type="password" id="password" name="password" class="form-control" value="" autocomplete="off" required="">
                        </div>
                        <div class="col-sm-6">
                          <label for="conf_password">Confirm Password *</label>
                          <input type="password" id="conf_password" name="conf_password" class="form-control" value="" autocomplete="off" required="">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="company_id">Company *</label>
                      <select class="form-control custom-select" id="company_id" name="company_id" required="">
                        <option selected disabled>Select one</option>
                        @if(Auth::user()->role_id==1)
                          @foreach($company as $comp)
                            <option value="{{$comp->id}}">{{$comp->company_name}}</option>
                          @endforeach
                        @else
                          @foreach($company as $comp)
                            @if(Auth::user()->company_id==$comp->id)
                              <option value="{{$comp->id}}" selected="selected">{{$comp->company_name}}</option>
                            @endif
                          @endforeach
                        @endif  
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="role_id">Role *</label>
                      <select class="form-control custom-select" id="role_id" name="role_id" required="">
                        <option selected disabled>Select Role</option>
                        <option value="2">Admin</option>
                        <option value="3" selected="selected">User</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <input type="submit" id="submit" class="btn btn-sm btn-success" value="Create">
                    </div>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div> <!-- add user -->

        <div class="row" id="edit" style="display:none;">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">User Edit</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="form-group">
                  <label for="inputName">Name *</label>
                  <input type="text" id="inputName" class="form-control" value="" required="">
                </div>
                <div class="form-group">
                  <label for="inputEmail">Email *</label>
                  <input type="text" id="inputEmail" class="form-control" value="" required="">
                </div>
                <div class="form-group">
                  <label for="inputCompany">Company *</label>
                  <select class="form-control custom-select" required="">
                    <option selected disabled>Select one</option>
                    <option>On Hold</option>
                    <option>Canceled</option>
                    <option>Success</option>
                  </select>
                </div>
                <div class="form-group">
                  <input type="submit" id="submit" class="btn btn-sm btn-primary" value="Update">
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div> <!-- edit user -->



        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->



  <script>
  $(function () {
    $("#example2").DataTable({
      "responsive": true,
      "autoWidth": false,
      "paging": true,
      "ordering": true,
     // "order": [[ 3, "desc" ]],
    });
  });

  $( "#add" ).click(function() {
    $('#list').slideUp(500);
    $('#new').slideDown(500);
  });
  $(".edit").click(function() {
    $('#list').slideUp(500);
    $("#edit").slideDown(500);
  });

  $("#showlist").click(function() {
    window.location.reload();
    $('#new').slideUp(500);
    $("#edit").slideUp(500);
    $('#list').slideDown(500);
  });

  /* attach a submit handler to the form */
$("#create_user").submit(function(event) {

  /* stop form from submitting normally */
  event.preventDefault();

  var password = $('#password').val();
  var conf_password = $('#conf_password').val();
  if(password!=conf_password){
      $(document).Toasts('create', {
        autohide: true,
        class: 'bg-danger', 
        title: 'Password Not Matched',
        subtitle: 'Error',
        body: 'Password and Confirm Password not matched.'
      });
  }


  /* get the action attribute from the <form action=""> element */
  var $form = $(this),
    url = $form.attr('action');

  /* Send the data using post with element id name and name2*/
  var posting = $.post(url, {
    _token:$('input[name=_token]').val(),
    name: $('#name').val(),
    email: $('#email').val(),
    password: $('#password').val(),
    company_id:$('#company_id').val(),
    role_id:$('#role_id').val()
  });

  /* Alerts the results */
  posting.done(function(data) {
    $(document).Toasts('create', {
        autohide: true,
        class: 'bg-success', 
        title: 'User created',
        subtitle: 'Success',
        body: 'New User Created Successfully.'
      });
    $('#create_user').trigger("reset");
  });
  posting.fail(function() {
    $('#result').text('failed');
  });
});

  /* attach a submit handler to the form */
$("#csv_upload").submit(function(event) {
debugger;
  /* stop form from submitting normally */
  event.preventDefault();

  /* get the action attribute from the <form action=""> element */
  var $form = $(this),
    url = $form.attr('action');

  if(!$('#file')[0].files[0]){
    $(document).Toasts('create', {
      autohide: true,
      class: 'bg-danger', 
      title: 'No file selected',
      subtitle: 'error',
      body: "Please select file"
    });
    return false;
  }

  formdata = new FormData();
  var files = $('#file')[0].files[0]; 
  formdata.append('file', files); 
  formdata.append('_token', $('input[name=_token]').val());
  formdata.append('submit','submit'); 

  $(document).Toasts('create', {
            autohide: true,
            class: 'bg-warning', 
            title: 'CSV Import',
            subtitle: 'processing',
            body: 'Import data in process...'
          });

$.ajax({
    url: url,
    type: "POST",
    data: formdata,
    processData: false,
    contentType: false,
    success: function (result) {
        $(document).Toasts('create', {
            autohide: true,
            class: 'bg-success', 
            title: 'User created',
            subtitle: 'Success',
            body: 'CSV Imported Successfully.'
          });
        $('#csv_upload').trigger("reset");
        },
      error: function (jqXHR, exception) {
        $(document).Toasts('create', {
            autohide: true,
            class: 'bg-danger', 
            title: 'Error',
            subtitle: 'error',
            body: exception
          });
      }
});


  /* Send the data using post with element id name and name2*/

  /* Alerts the results */

});

</script>
@endsection




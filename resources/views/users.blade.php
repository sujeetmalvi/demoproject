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
          @if(Auth::user()->role_id==2)
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

        @if($view=='list')
        <div class="row" id="list">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users List </h3>
                <a href="{{url('/users/new')}}" class="btn btn-sm btn-success" style="float:right;" id='add'><i class="fas fa-plus"></i> New</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Location</th>
                    <th>Company</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($data as $d)
                  <tr>
                    <td>{{$d->name}}</td>
                    <td>{{$d->email}}</td>
                    <td>{{$d->mobile}}</td>
                    <td>{{$d->location}}</td>
                    <td>{{$d->company_name}}</td>
                    <td>
                      <a class="btn btn-info btn-sm edit" href="{{url('/users/edit_')}}{{$d->id}}"><i class="fas fa-pencil-alt"></i></a>
                      <a class="btn btn-danger btn-sm delete" href="#" onclick="deleterecord({{$d->id}})"><i class="fas fa-trash"></i></a>
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
        @endif

        @if($view=='new')
        <div class="row" id="new">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Create User</h3>
                <a href="{{url('/users/list')}}" class="btn btn-sm btn-success" style="float:right;" id='showlist'><i class="fas fa-plus"></i> List</a>
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
                      <div class="row">
                          <div class="col-sm-6">
                            <label for="email">Email *</label>
                            <input type="text" id="email" name="email" class="form-control" value="" autocomplete="off" required="">
                          </div>
                          <div class="col-sm-6">
                            <label for="mobile">Mobile </label>
                            <input type="number" id="mobile" name="mobile" class="form-control" value="" autocomplete="off" required="">
                          </div>
                      </div>
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
                      <div class="row">
                        <div class="col-sm-6">
                          <label for="location">Location </label>
                          <input type="text" id="location" name="location" class="form-control" value="" autocomplete="off" required="">
                        </div>
                        <div class="col-sm-6">
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
                      </div>
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
        @endif

        @if($view=='edit')
        <div class="row" id="new">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Edit User</h3>
                <a href="{{url('/users/list')}}" class="btn btn-sm btn-success" style="float:right;" id='showlist'><i class="fas fa-plus"></i> List</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="/edit_user" name="edit_user" id="edit_user" method="post">
                  {{ csrf_field() }}
                    <div class="form-group">
                      <label for="name">Name *</label>
                      <input type="text" id="name" name="name" class="form-control" value="{{$data->name}}" autocomplete="off" required="">
                    </div>
                    <div class="form-group">
                      <div class="row">
                          <div class="col-sm-6">
                            <label for="email">Email *</label>
                            <input type="text" id="email" name="email" class="form-control" value="{{$data->email}}" autocomplete="off" required="">
                          </div>
                          <div class="col-sm-6">
                            <label for="mobile">Mobile </label>
                            <input type="number" id="mobile" name="mobile" class="form-control" value="{{$data->mobile}}" autocomplete="off" required="">
                          </div>
                      </div>
                    </div>
                      
                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-4">
                          <label for="password">Old Password *</label>
                          <input type="password" id="oldpassword" name="oldpassword" class="form-control" value="" autocomplete="off" placeholder="Leave empty to remail same">
                        </div>
                        <div class="col-sm-4">
                          <label for="password">New Password *</label>
                          <input type="password" id="password" name="password" class="form-control" value="" autocomplete="off"  placeholder="New Password">
                        </div>
                        <div class="col-sm-4">
                          <label for="conf_password">Confirm Password *</label>
                          <input type="password" id="conf_password" name="conf_password" class="form-control" value="" autocomplete="off"  placeholder="Confirm Password">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-6">
                          <label for="location">Location </label>
                          <input type="text" id="location" name="location" class="form-control" value="{{$data->location}}" autocomplete="off" required="">
                        </div>
                        <div class="col-sm-6">
                          <label for="company_id">Company *</label>
                          <select class="form-control custom-select" id="company_id" name="company_id" required="">
                            <option selected disabled>Select one</option>
                            @if(Auth::user()->role_id==1)
                              @foreach($company as $comp)
                                @if($data->company_id==$comp->id)
                                <option value="{{$comp->id}}" selected="selected">{{$comp->company_name}}</option>
                                @else
                                <option value="{{$comp->id}}">{{$comp->company_name}}</option>
                                @endif
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
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="role_id">Role *</label>
                      <select class="form-control custom-select" id="role_id" name="role_id" required="">
                        <option selected disabled>Select Role</option>

                        <option value="2" @if($data->role_id=='2') selected="selected" @endif>Admin</option>
                        <option value="3" @if($data->role_id=='3') selected="selected" @endif>User</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <input type="hidden" name="id" id="id" value="{{$data->id}}">
                      <input type="submit" id="submit" class="btn btn-sm btn-success" value="Update">
                    </div>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div> <!-- edit user -->
        @endif


        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->



 

@if($view=='list')  
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

  function deleterecord(id){
    var r = confirm("Do you want to delete this record ?");
    if (r == true) {
      window.location.href = '/users/delete_'+id;
    } 
  }

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

});
</script>
@endif

@if($view=='new')
<script>

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
    mobile: $('#mobile').val(),
    location: $('#location').val(),
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
</script>
@endif

@if($view=='edit')
<script>

$("#edit_user").submit(function(event) {

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
    mobile: $('#mobile').val(),
    location: $('#location').val(),
    password: $('#password').val(),
    company_id:$('#company_id').val(),
    role_id:$('#role_id').val(),
    id:$('#id').val()
  });

  /* Alerts the results */
  posting.done(function(data) {
    $(document).Toasts('create', {
        autohide: true,
        class: 'bg-success', 
        title: 'User Edited',
        subtitle: 'Success',
        body: 'User Edited Successfully.'
      });
  });
  posting.fail(function() {
    $('#result').text('failed');
  });
});


</script>
@endif

@endsection




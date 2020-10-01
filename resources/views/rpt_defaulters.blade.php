@extends('maintemplate')
@section('content')
  <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Defaulters</h1>
          </div>
          <div class="col-sm-6">
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <a href="/exl_defaulters"><h3 style="float: right" class="card-title">Excel Export </h3></a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Voilation count</th>
                    <th>Date</th>
                  </tr>
                  </thead>
                  <tbody>
                @foreach($data as $d)
                  <tr>
                    <td>{{$d->name}}</td>
                    <td>{{$d->voilation}}</td>
                    <td>{{$d->ddate}}</td>
                  </tr>
                @endforeach
                  </tbody>
                  <!--<tfoot>
                  <tr>
                    <th>User Name</th>
                    <th>Near By Person</th>
                    <th>Distance(in Meter)</th>
                    <th>Datetime</th>
                  </tr>
                  </tfoot>-->
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
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
    //  "order": [[ 2, "desc" ]],
    });
  });
</script>
@endsection

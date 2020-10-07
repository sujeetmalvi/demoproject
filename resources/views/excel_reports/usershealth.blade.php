<?php
// The function header by sending raw excel
header("Content-type: application/vnd-ms-excel");
// Defines the name of the export file "codelution-export.xls"
header("Content-Disposition: attachment; filename=usershealth.xls");
?>
<table border='1'>
  <thead>
    <tr>
      <th>User Name</th>
      <th>User Email</th>
      <th>Condition Type</th>
      <th>Datetime</th>
    </tr>
    </thead>
    <tbody>
  @foreach($data as $d)
    <tr>
      <td>{{$d->name}}</td>
      <td>{{$d->email}}</td>
      <td>{{Config::get('constants.CONDITION_TYPES.'.$d->condition_type)}}</td>
      <td>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at, 'Asia/Kolkata')->format('d-M-Y h:i A')}}</td>
    </tr>
  @endforeach
    </tbody>
</table>

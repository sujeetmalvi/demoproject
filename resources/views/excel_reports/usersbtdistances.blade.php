<?php
// The function header by sending raw excel
header("Content-type: application/vnd-ms-excel");
// Defines the name of the export file "codelution-export.xls"
header("Content-Disposition: attachment; filename=usersbtdistances.xls");
?>
<table border='1'>
  <thead>
  <tr>
    <th>User Name</th>
    <th>Near By Person</th>
    <th>Distance(in Meter)</th>
    <th>Datetime</th>
  </tr>
  </thead>
  <tbody>
@foreach($data as $d)
  <tr>
    <td>{{$d->name}}</td>
    <td>{{$d->user2name}}</td>
    <td>{{$d->distance}}</td>
    <td>{{$d->created_at}}</td>
  </tr>
@endforeach
  </tbody>
</table>


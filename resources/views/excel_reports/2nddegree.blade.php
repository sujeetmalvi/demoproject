<?php
// The function header by sending raw excel
header("Content-type: application/vnd-ms-excel");
// Defines the name of the export file "codelution-export.xls"
header("Content-Disposition: attachment; filename=2nddegree.xls");
?>
<table border='1'>
  <thead>
    <tr>
      <th>Person</th>
      <th>Person (Email)</th>
      <th>Near By Person </th>
      <th>Near By Person (Email)</th>
      <th>Distance(in Meter)</th>
      <th>Datetime</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $d)
    <tr>
      <td>{{$d->personname}}</td>
      <td>{{$d->personemail}}</td>
      <td>{{$d->user2name}}</td>
      <td>{{$d->user2email}}</td>
      <td>{{$d->distance}}</td>
      <td>{{$d->created_at}}</td>
    </tr>
    @endforeach
    </tbody>
</table>

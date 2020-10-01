<?php
// The function header by sending raw excel
header("Content-type: application/vnd-ms-excel");
// Defines the name of the export file "codelution-export.xls"
header("Content-Disposition: attachment; filename=breaches.xls");
?>
<table border='1'>
  <thead>
  <tr>
    <th>Name</th>
    <th>Voilation count</th>
  </tr>
  </thead>
  <tbody>
  @foreach($data as $d)
  <tr>
    <td>{{$d->name}}</td>
    <td>{{$d->voilation}}</td>
  </tr>
  @endforeach
  </tbody>
</table>

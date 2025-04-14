@extends('master')
@section('BodySec')
    <div class="container-fluid">
        <div class="position-ref">
            <div class="content">
                <div class="title m-b-md">
                    <b>List of People</b>
                </div>

                <div class="row">
                    <table id="people_list" class="table table-hover table-inverse table-striped">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Address</th>
                            <th scope="col">Father Name</th>
                            <th scope="col">Grand Father Name</th>
                            <th scope="col">Citizenship No.</th>
                            <th scope="col">Date of Issue</th>
                            <th scope="col">Bank Name</th>
                            <th scope="col">Account Number</th>
                            <th scope="col">BOID</th>
                            <th scope="col">Remark</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($peoples as $index=>$people)
                            <tr class="{!! $tr_class[$index%5] !!}">
                                <th scope="row">{!! $index + 1!!}</th>
                                <td><?php
                                    $fullname=(strlen($people['mname'])==0)?$people['fname'] ." ".$people['lname'] : $people['fname'] ." ".substr($people['mname'],0)." ".$people['lname'];
                                    echo $fullname;
                                    ?></td>
                                <td>{!! $people["addr"] !!}</td>
                                <td>{!! $people["father_name"] !!}</td>
                                <td>{!! $people["gfather_name"] !!}</td>
                                <td>{!! $people["citizenship_no"] !!}</td>
                                <td>{!! $people["date_of_issue"] !!}</td>
                                <td>{!! $people["bank_name"] !!}</td>
                                <td>{!! str_replace(" ","-",trim($people["acc_num"])) !!}</td>
                                <td>{!! $people["BOID"] !!}</td>
                                <td><a href="{!! "/people/update/".encrypt($people['bid']) !!}" class="btn btn-outline-primary waves-effect">Change Info</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function(){
            $('table#people_list').DataTable();
        });
    </script>
@endsection

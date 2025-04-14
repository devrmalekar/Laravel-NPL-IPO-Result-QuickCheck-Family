@extends('master')
@section('BodySec')
    <div class="container">
        <div class="position-ref">
            <div class="content">
                <div class="title m-b-md">
                    IPO Result of <br /> <b>{!! $cname !!}</b>
                </div>

                <div class="row">
                    <table id="ipoResults" class="table table-hover table-inverse table-striped">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">BOID</th>
                            <th scope="col">Applied Kitta</th>
                            <th scope="col">Alloted Kitta</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($ipoResults as $index=>$ipo)
                                <tr class="{!! ($ipo["Alloted Kitta"] != 'N/A')? 'success':'danger' !!}">
                                    <th scope="row">{!! $index + 1!!}</th>
                                    <td>{!! $ipo["Name"] !!}</td>
                                    <td>{!! $ipo["DEMAT No."] !!}</td>
                                    <td>{!! $ipo["Applied Kitta"] !!}</td>
                                    <td>{!! $ipo["Alloted Kitta"] !!}</td>
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
        $('table#ipoResults').DataTable();
    });
</script>
@endsection

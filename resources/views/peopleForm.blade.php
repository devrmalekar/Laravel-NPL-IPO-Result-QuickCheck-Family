@extends('master')
@section('BodySec')
    <div class="position-ref">
        <div class="content">
            <div class="title m-b-md">
                @if($task=='insert')
                    <b>Add New People </b>
                @else
                    <b>Update People </b>
                @endif
            </div>
            <div class="row">
                {{  Form::open(['route'=>'people.add', 'method' => 'post']) }}
                <fieldset class="col col-sm-6">
                    <legend>Personal Information</legend>
                    <div class="container-fluid">
                        <div class="form-group">
                            <div @if(($errors)->has('fname'))
                                 class='control-group error'
                                 @else
                                 class='control-group'
                                    @endif
                            >
                                <div class="col col-sm-12">
                                    <input type="text" class="form-control" id="fname" name="fname" required placeholder="Enter First Name (required)" value="{!! $peoples['fname'] or old('fname') !!}">
                                </div>
                                @if(($errors)->has('fname'))
                                    <span class="error">{!! $errors->first('fname') !!}</span>
                                @endif
                            </div>

                            <div @if(($errors)->has('mname'))
                                 class='control-group error'
                                 @else
                                 class='control-group'
                                    @endif
                            >
                                <div class="col col-sm-12">
                                    <input type="text" class="form-control" id="mname" name="mname" placeholder="Enter Middle Name if any" value="{!!$peoples['mname'] or old('mname') !!}">
                                </div>
                                @if(($errors)->has('mname'))
                                    <span class="error">{!! $errors->first('mname') !!}</span>
                                @endif
                            </div>

                            <div @if(($errors)->has('lname'))
                                 class='control-group error'
                                 @else
                                 class='control-group'
                                    @endif
                            >
                                <div class="col col-sm-12">
                                    <input type="text" class="form-control" id="lname" name="lname" required placeholder="Enter Last Name (required)" value="{!! $peoples['lname'] or old('lname') !!}">
                                </div>
                                @if(($errors)->has('lname'))
                                    <span class="error">{!! $errors->first('lname') !!}</span>
                                @endif
                            </div>

                            <div class="clearfix"></div>
                        </div>

                        <div class="form-group">
                            <div @if(($errors)->has('faname'))
                                 class='control-group error'
                                 @else
                                 class='control-group'
                                    @endif
                            >
                                <div class="col col-sm-12">
                                    <input type="text" class="form-control" id="faname" name="faaname" placeholder="Enter Father Name" value="{!! $peoples['father_name'] or old('faname') !!}">
                                </div>
                                @if(($errors)->has('faname'))
                                    <span class="error">{!! $errors->first('faname') !!}</span>
                                @endif
                            </div>
                            <div @if(($errors)->has('gname'))
                                 class='control-group error'
                                 @else
                                 class='control-group'
                                    @endif
                            >
                                <div class="col col-sm-12">
                                    <input type="text" class="form-control" id="gname" name="gname" placeholder="Enter Grand Father Name" value="{!! $peoples['gfather_name'] or old('gname') !!}">
                                </div>
                                @if(($errors)->has('gname'))
                                    <span class="error">{!! $errors->first('gname') !!}</span>
                                @endif
                            </div>
                            <div @if(($errors)->has('addr'))
                                 class='control-group error'
                                 @else
                                 class='control-group'
                                    @endif
                            >
                                <div class="col col-sm-12">
                                    <input type="text" class="form-control" id="addr" name="addr" placeholder="Enter address" value="{!! $peoples['addr'] or old('addr') !!}">
                                </div>
                                @if(($errors)->has('addr'))
                                    <span class="error">{!! $errors->first('addr') !!}</span>
                                @endif
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="form-group">
                            <div @if(($errors)->has('cnum'))
                                 class='control-group error'
                                 @else
                                 class='control-group'
                                    @endif
                            >
                                <div class="col col-sm-12">
                                    <input type="text" class="form-control" id="cnum" name="cnum" required placeholder="Enter citizenship number (required)" value="{!! $peoples['citizenship_no'] or old('cnum') !!}">
                                </div>
                                @if(($errors)->has('cnum'))
                                    <span class="error">{!! $errors->first('cnum') !!}</span>
                                @endif
                            </div>

                            <div @if(($errors)->has('dos'))
                                 class='control-group error'
                                 @else
                                 class='control-group'
                                    @endif
                            >
                                <div class="col col-sm-12">
                                    <div class='input-group date' id='datetimepicker1'>
                                        <input type="text" class="form-control" id="dos" name="dos" placeholder="Enter Date of Issue" value="{!! $peoples['date_of_issue'] or old('dos') !!}">
                                        <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    </div>
                                </div>
                                @if(($errors)->has('dos'))
                                    <span class="error">{!! $errors->first('dos') !!}</span>
                                @endif
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="col col-sm-6">
                    <legend>Bank Information</legend>
                    <div class="container-fluid">
                        <div class="form-group">
                            <div @if(($errors)->has('bname'))
                                 class='control-group error'
                                 @else
                                 class='control-group'
                                    @endif
                            >
                                <div class="col col-sm-12">
                                    <input type="text" class="form-control" id="bname" name="bname" required placeholder="Enter Bank Name (required)" value="{!! $peoples['bank_name'] or old('bname') !!}">
                                </div>
                                @if(($errors)->has('bname'))
                                    <span class="error">{!! $errors->first('bname') !!}</span>
                                @endif
                            </div>

                            <div @if(($errors)->has('accnum'))
                                 class='control-group error'
                                 @else
                                 class='control-group'
                                    @endif
                            >
                                <div class="col col-sm-12">
                                    <input type="text" class="form-control" id="accnum" name="accnum" required placeholder="Enter account number (required)" value="{!! $peoples['acc_num'] or old('accnum') !!}">
                                </div>
                                @if(($errors)->has('accnum'))
                                    <span class="error">{!! $errors->first('accnum') !!}</span>
                                @endif
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="form-group">
                            <div @if(($errors)->has('did'))
                                 class='control-group error'
                                 @else
                                 class='control-group'
                                    @endif
                            >
                                <div class="col col-sm-12">
                                    <input type="text" class="form-control" id="did" name="did" required placeholder="Enter DMAT ID (required)" value="{!! $peoples['dmat_id'] or old('did') !!}">
                                </div>
                                @if(($errors)->has('did'))
                                    <span class="error">{!! $errors->first('did') !!}</span>
                                @endif
                            </div>

                            <div @if(($errors)->has('cid'))
                                 class='control-group error'
                                 @else
                                 class='control-group'
                                    @endif
                            >
                                <div class="col col-sm-12">
                                    <input type="text" class="form-control" id="cid" name="cid" required placeholder="Enter Client ID (required)" value="{!! $peoples['client_id'] or old('cid') !!}">
                                </div>
                                @if(($errors)->has('cid'))
                                    <span class="error">{!! $errors->first('cid') !!}</span>
                                @endif
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="col col-sm-12">
                    <input type="hidden" name="task" value="{!! $task !!}">
                    <input type="hidden" name="pid" value="{!! $peoples['pid'] !!}">
                    <input type="hidden" name="bid" value="{!! $peoples['bid'] !!}">
                    <input type="submit" class="btn btn-outline-primary waves-effect" name="submit" value="Save">
                </fieldset>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="<?php echo e(asset('css/bootstrap-datetimepicker.css')); ?>">
    <script src="<?php echo e(asset('js/moment.js')); ?>"></script>
    <script src="<?php echo e(asset('js/bootstrap-datetimepicker.js')); ?>"></script>
    <script src="<?php echo e(asset('js/bootstrap-select.min.js')); ?>"></script>
    <script>
        $(document).ready(function(){
            $('#dos').datetimepicker({
                format: 'YYYY-MM-DD'
            });

        })
    </script>
@endsection

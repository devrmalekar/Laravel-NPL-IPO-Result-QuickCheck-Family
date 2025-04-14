@extends('master')
@section('BodySec')
    <div class="flex-center position-ref">
        <?php if(Route::has('login')): ?>
        <div class="top-right links">
            <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(url('/home')); ?>">Home</a>
            <?php else: ?>
            <a href="<?php echo e(route('login')); ?>">Login</a>
            <a href="<?php echo e(route('register')); ?>">Register</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="content container-fluid" style="width: 50%;">
            <div class="title m-b-md">
                <b>Check you IPO Result.</b>
            </div>

            <div class="row flex-center">
                {{  Form::open(['route'=>'ipo.getResult', 'method' => 'post']) }}
                <div class="form-group">
                    <div @if(($errors)->has('cid') || $errors->has('cname'))
                            class='control-group error'
                        @else
                            class='control-group'
                        @endif
                    >
                        <div class="controls">
                            <select class="selectpicker form-control" data-live-search="true" name="cid" >
                                <option selected="selected" value="">--Select Company--</option>
                                <?php $__currentLoopData = $company_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value=>$cname): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" data-tokens="<?php echo e($cname); ?>"><?php echo e($cname); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            @if(($errors)->has('cid') || $errors->has('cname'))
                            <span class="error">Please select company first. And then proceed on.</span>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="cname" value="" />
                </div>
                <div class="form-group">
                        <input type="submit" class="btn btn-outline-primary waves-effect" value="submit" name="Get Results"/>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>


<script src="<?php echo e(asset('js/bootstrap-select.min.js')); ?>"></script>
<script>
    $(document).ready(function(){
        $('input[name=cname]').val("");
        $('select[name=cid]').change(function(){
            $('input[name=cname]').val($('select[name=cid]>option:selected').text());
        });
    })
</script>
@endsection

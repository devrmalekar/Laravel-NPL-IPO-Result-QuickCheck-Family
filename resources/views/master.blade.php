<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SB IPO Result</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/bootstrap-select.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/master.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/mdb.min.css')); ?>">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="<?php echo e(asset('js/mdb.min.js')); ?>"></script>
</head>
<body>
 <nav class="navbar navbar-default navbar-me">
    <div class="container-fluid ">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed menu-collapsed-button" data-toggle="collapse" data-target="#navbar-primary-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand site-logo" href="/">Check IPO Result</a>
        </div>

        <div class="collapse navbar-collapse navbar-right  header-right-menu" id="navbar-primary-collapse">
            <ul class="nav navbar-nav ">
                <li role="presentation" data-toggle="tooltip" title="Update Existing People"><a href="/people/list"><i class="glyphicon glyphicon-list"></i>People</a></li>
                <li role="presentation" data-toggle="tooltip" title="Add New People"><a href="/people/new"><i class="glyphicon glyphicon-plus"> </i>People</a></li>
                <li role="presentation" data-toggle="tooltip" title="Update Existing People"><a href="/people/list"><i class="glyphicon glyphicon-refresh"></i>People</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div>
</nav>
@yield('BodySec');

<script>
    $(document).ready(function(){
        $('[data-togle="tooltip"]').tooltip();

        $(window).scroll(function() {
            if($(this).scrollTop()>5) {
                $( ".navbar-me" ).addClass("fixed-me");
            } else {
                $( ".navbar-me" ).removeClass("fixed-me");
            }
        });
    });
</script>
</>
</body>
</html>

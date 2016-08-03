<?php require_once("../php/logged.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Games.ly</title>

    <!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="../dist/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../bower_components/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="../dist/css/games.ly.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body ng-app="mainApp">
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Alternar navegación</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#/">Games.ly</a>
                <ul class="nav navbar-top-links navbar-left">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="">
                            <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#/userProfile"><i class="fa fa-user fa-fw"></i> Perfil de usuario</a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="../php/logout.php"><i class="fa fa-sign-out fa-fw"></i> Salir</a>
                            </li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>
                <!-- /.navbar-top-links -->
            </div>
            <!-- /.navbar-header -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <form  role="form" name="addGameForm" class="form-horizontal" ng-controller="gameController">
                                <div class="form-group">
                                    <input type="text" class="form-control" ng-model="searchString" placeholder="Buscar..." required>
                                </div>
                                <button type="submit" class="btn btn-default" ng-click="searchGamesClick()" ng-disabled="addGameForm.$invalid">
                                    <i class="fa fa-search"></i>
                                </button>
                            </form>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="#/"><i class="glyphicon glyphicon-home"></i> Principal</a>
                        </li>
                        <li>
                            <a href="#/catalog"><i class="glyphicon glyphicon-book"></i> Catálogo</a>
                        </li>
                        <li>
                            <a href="#/notices"><i class="glyphicon glyphicon-duplicate"></i> Noticias</a>
                        </li>
                        <li>
                            <a href="#/addGame"><i class="glyphicon glyphicon-copy"></i> Añadir juego</a>
                        </li>
                        <li>
                            <a href="#/log" ng-show="<?php require_once '../php/functions.php';
                                if ( is_session_started() === FALSE ) session_start();
                                if (isset($_SESSION['adminId'])) {
                                    echo 'true';
                                }else{
                                    echo 'false';
                                }  ?>">
                                    <i class="glyphicon glyphicon-file"></i> Ver el log
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="visibleView"></div>
                <div ng-view>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                
            </div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- AngularJS -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular.js"></script>
    <script src="https://code.angularjs.org/1.4.5/angular-route.js"></script>
    <script src="../bower_components/angular-match/angular-input-match.js"></script>

    <!-- Bootbox -->
    <script src="../bower_components/bootbox/bootbox.min.js"></script>

    <!-- Propios -->
    <!-- Controladores Angular -->
    <script src="mainApp.js"></script>
    <script src="../home/home.controller.js"></script>
    <script src="../catalog/catalog.controller.js"></script>
    <script src="../notices/notices.controller.js"></script>
    <script src="../games/games.controller.js"></script>
    <script src="../user/userProfile.controller.js"></script>
    <script src="../log/log.controller.js"></script>

</body>

</html>

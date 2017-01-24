<div class="col-md-12" ng-init="listNotices()">
	<div class="panel panel-primary">
		<div class="panel-heading"><h1>Noticias</h1></div>
		<div class="panel-body">
            <form  role="form" name="addNoticeForm" class="form-inline" ng-controller="noticeController">
            	<div class="form-group">
			    	<input type="text" class="form-control" ng-model="searchString" placeholder="Buscar..." required>
			  	</div>
			  	<button type="submit" class="btn btn-default" ng-click="searchNoticesClick()">
                    <i class="fa fa-search"></i>
               	</button>
			</form>
			<hr>
			<div class="row container-fluid">
				<a href="#/addNotice"><h3>Añadir una nueva noticia</h3></a>
			</div>
			<hr>
			<div class="row container-fluid">
				<div class="container-fluid col-md-12" ng-show="!notices.length"><h1>Actualmente no hay ninguna noticia, ayudanos añadiendo una.</h1></div>
				<div class="container-fluid col-md-12" ng-show="notices.length">
					<ul>
						<li class="list-unstyled" ng-repeat="notice in notices">
							<div class="container-fluid col-md-3">
								<a href="#/notice/{{ notice._id.$id }}">
									<img class="img-rounded img-responsive" ng-src="../img/notices/{{notice.noticeIMG}}" alt="{{notice.title}}"/>
									<span>{{notice.title}}</span>
								</a>
								<button class="btn btn-default" ng-click="deleteNotice(notice._id.$id)" ng-show="<?php require_once '../php/functions.php';
					               	if ( is_session_started() === FALSE ) session_start();
					               	if (isset($_SESSION['adminId'])) {
					               		echo 'true';
					               	}else{
					               		echo 'false';
					               	}  ?>">
				                    <i class="glyphicon glyphicon-remove text-danger"></i>
				               	</button>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
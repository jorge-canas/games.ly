<div class="col-md-12" ng-init="getGameCatalog(0)">
	<div class="panel panel-primary">
		<div class="panel-heading"><h1>Catálogo de juegos</h1></div>
		<div class="panel-body">
			<div class="row">
				<div class="container-fluid col-md-12">
					<ul>
						<li class="list-unstyled" ng-repeat="game in games">
							<div class="container-fluid col-md-2">
								<a href="#/game/{{ game.gameID }}">
									<img ng-src="../img/games/{{game.gameIMG}}" alt="{{game.gameName}}"/>
									<span>{{game.gameName}}</span>
								</a>
								<button ng-controller="gameController" class="btn btn-default" ng-click="deleteGame(game.gameID)" ng-show="<?php require_once '../php/functions.php';
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
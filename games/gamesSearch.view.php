<div class="col-md-12" ng-init="searchGames()">
	<div class="panel panel-primary">
		<div class="panel-heading"><h1>Búsqueda de juegos</h1></div>
		<div class="panel-body">
			<form  role="form" name="addGameForm" class="form-inline container-fluid" ng-controller="gameController">
	            <div class="form-group">
	                <input type="text" class="form-control" ng-model="searchString" placeholder="Buscar..." required>
	            </div>
	            <button type="submit" class="btn btn-default" ng-click="searchGamesClick()" ng-disabled="addGameForm.$invalid">
	                <i class="fa fa-search"></i>
	            </button>
	        </form>
	        <hr>
			<div class="row">
				<div class="container-fluid col-md-12" ng-show="!games.length"><h3>No se ha encontrado ningún juego con ese criterio, pruebe con otro.</h3></div>
				<div class="container-fluid col-md-12">
					<ul>
						<li class="list-unstyled" ng-repeat="game in games">
							<div class="container-fluid col-md-2">
								<a href="#/game/{{ game.gameID }}">
									<img ng-src="../img/games/{{game.gameIMG}}" alt="{{game.gameName}}"/>
									<span>{{game.gameName}}</span>
								</a>
				               	<button class="btn btn-default" ng-click="deleteGame(game.gameID)" ng-show="<?php require_once '../php/functions.php';
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
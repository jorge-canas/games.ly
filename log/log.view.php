<div class="col-md-12" ng-init="getLog()">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h1>Registro de entradas</h1>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="container-fluid col-md-12">
					<h3 ng-show="!logLines.length">Actualmente no hay ninguna línea en el registro.</h3>
					<table class="table">
	                    <thead>
	                        <tr>
	                        	<th>#</th>
	                            <th>Fecha</th>
	                            <th>IP</th>
	                            <th>Usuario</th>
	                            <th>Acción</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        <tr ng-repeat="logLine in logLines">
	                        	<td>{{$index+1}}</td>
	                            <td>{{logLine[0]}}</td>
	                            <td>{{logLine[1]}}</td>
	                            <td>{{logLine[2]}}</td>
	                            <td>{{logLine[3]}}</td>
	                        </tr>
	                    </tbody>
	                    <tfoot></tfoot>
	                </table>
				</div>
			</div>
		</div>
		<div class="panel-footer"></div>
	</div>
</div>
<?php
	
    // Define o limitador de cache
    session_cache_limiter('must-revalidate');
    $cache_limiter = session_cache_limiter();

    // Define tempo da sess�o
    session_cache_expire(300);
    $cache_expire = session_cache_expire();
    
	session_start();

	if(!isset($_SESSION['usuario'])){
		header('Location: index.php?erro=1');
	}

	require_once('db.class.php');

	$objDb = new db();
	$link = $objDb->conecta_mysql();

	$id_usuario = $_SESSION['id_usuario'];

	//--qtde de tweets
	$sql = " SELECT COUNT(*) AS qtde_tweets FROM tweet WHERE id_usuario = $id_usuario ";
	$resultado_id = mysqli_query($link, $sql);
	$qtde_tweets = 0;
	if($resultado_id){
		$registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC);
		$qtde_tweets = $registro['qtde_tweets'];
	} else {
		echo 'Erro ao executar a query';
	}

	//--qtde de seguidores
	$sql = " SELECT COUNT(*) AS qtde_seguires FROM usuarios_seguidores WHERE seguindo_id_usuario = $id_usuario ";
	$resultado_id = mysqli_query($link, $sql);
	$qtde_seguidores = 0;
	if($resultado_id){
		$registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC);
		$qtde_seguidores = $registro['qtde_seguires'];
	} else {
		echo 'Erro ao executar a query';
	}

?>

<!DOCTYPE HTML>
<html lang="pt-br">
	<head>
		<meta charset="UTF-8">

		<title>Up Project</title>
		
		<!-- jquery - link cdn -->
		<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>

		<!-- bootstrap - link cdn -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="estilo.css" rel="stylesheet">
	
		<script type="text/javascript">

			$(document).ready( function(){

				//associar o evento de click ao botão
				$('#btn_tweet').click( function(){
					
					if($('#texto_tweet').val().length > 0){
						
						$.ajax({
							url: 'inclui_tweet.php',
							method: 'post',
							data: $('#form_tweet').serialize(),
							success: function(data) {
								$('#texto_tweet').val('');
								atualizaTweet();
							}
						});
					}

				});

				function atualizaTweet(){
					//carregar os tweets 

					$.ajax({
						url: 'get_tweet.php',
						success: function(data) {
							$('#tweets').html(data);
						}
					});
				}

				atualizaTweet();

			});

		</script>

	</head>

	<body>

		<nav class="navbar navbar-fixed-top navbar-inverse navbar-transparente">
      <div class="container">
        
        <!-- header -->
        <div class="navbar-header">
          
          <!-- botao toggle -->
          <button type="button" class="navbar-toggle collapsed"
                  data-toggle="collapse" data-target="#barra-navegacao">
            <span class="sr-only">alternar navegação</span>
            <span class="icon-bar" style="background-color: black !important"></span>
            <span class="icon-bar" style="background-color: black !important"></span>
            <span class="icon-bar" style="background-color: black !important"></span>
          </button>

           <a class="navbar-brand" style="color: black;font-family: Brush Script MT, Brush Script Std, cursive;text-shadow: 0 0 2px black;text-rendering: optimizeLegibility !important;" href="#home">Up Project</a>

        </div>

        <!-- navbar -->
        <div class="collapse navbar-collapse" id="barra-navegacao">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="sair.php" style="font-size: 19px">Sair</a></li>
          </ul>
        </div>
      </div><!-- /container -->
    </nav><!-- /nav -->


	    <div class="container" style="padding:70px ">
	    	<div class="col-md-3">
	    		<div class="panel panel-default">
	    			<div class="panel-body">
	    				<h4><?= $_SESSION['usuario'] ?></h4>

	    				<hr />
	    				<div class="col-md-6">
	    					TWEETS <br /> <?= $qtde_tweets ?>
	    				</div>
	    				<div class="col-md-6">
	    					SEGUIDORES <br /> <?= $qtde_seguidores ?>
	    				</div>
	    			</div>
	    		</div>
	    	</div>
	    	
	    	<div class="col-md-6">
	    		<div class="panel panel-default">
	    			<div class="panel-body">
	    				<form id="form_tweet" class="input-group">
	    					<input type="text" id="texto_tweet" name="texto_tweet" class="form-control" placeholder="O que está acontecendo agora?" maxlength="140" />
	    					<span class="input-group-btn">
	    						<button class="btn btn-default" id="btn_tweet" type="button">Tweet</button>
	    					</span>
	    				</form>
	    			</div>
	    		</div>

	    		<div id="tweets" class="list-group"></div>

			</div>
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-body">
						<h4><a href="procurar_pessoas.php">Procurar por pessoas</h4>
					</div>
				</div>
			</div>
		</div>


	    </div>
	
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	
	</body>
</html>
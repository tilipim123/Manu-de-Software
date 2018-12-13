<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['code'])){

  // Informe o seu App ID abaixo
  $appId = '544312629375425';

  // Digite o App Secret do seu aplicativo abaixo:
  $appSecret = '123456789';

  // Url informada no campo "Site URL"
  $redirectUri = urlencode('www.seusite.com.br/cadastro.php');

  // Obt�m o c�digo da query string
  $code = $_GET['code'];

  // Monta a url para obter o token de acesso e assim obter os dados do usu�rio
  $token_url = "https://graph.facebook.com/oauth/access_token?"
  . "client_id=" . $appId . "&redirect_uri=" . $redirectUri
  . "&client_secret=" . $appSecret . "&code=" . $code;

  //pega os dados
  $response = @file_get_contents($token_url);
  if($response){
    $params = null;
    parse_str($response, $params);
    if(isset($params['access_token']) && $params['access_token']){
      $graph_url = "https://graph.facebook.com/me?access_token="
      . $params['access_token'];
      $user = json_decode(file_get_contents($graph_url));

	// nesse IF verificamos se veio os dados corretamente
      if(isset($user->email) && $user->email){

	/*
	*Apartir daqui, voc� j� tem acesso aos dados usuario, podendo armazen�-los
	*em sess�o, cookie ou j� pode inserir em seu banco de dados para efetuar
	*autentica��o.
	*No meu caso, solicitei todos os dados abaixo e guardei em sess�es.
	*/

        $_SESSION['email'] = $user->email;
        $_SESSION['nome'] = $user->name;
        $_SESSION['localizacao'] = $user->location->name;
        $_SESSION['uid_facebook'] = $user->id;
        $_SESSION['user_facebook'] = $user->username;
        $_SESSION['link_facebook'] = $user->link;
      }
    }else{
      echo "Erro de conex�o com Facebook";
      exit(0);
    }

  }else{
    echo "Erro de conex�o com Facebook";
    exit(0);
  }
}else if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['error'])){
  echo 'Permiss�o n�o concedida';
}
?>
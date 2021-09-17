<?php

$page = '';
if (empty($page)) {
 $page = "dbc";
 // On limite l'inclusion aux fichiers.php en ajoutant dynamiquement l'extension
 // On supprime également d'éventuels espaces
 $page = trim($page.".php");

}

// On évite les caractères qui permettent de naviguer dans les répertoires
$page = str_replace("../","protect",$page);
$page = str_replace(";","protect",$page);
$page = str_replace("%","protect",$page);

// On interdit l'inclusion de dossiers protégés par htaccess
if (preg_match("/config/",$page)) {
 echo "Vous n'avez pas accès à ce répertoire";
 }

else {

    // On vérifie que la page est bien sur le serveur
    if (file_exists("../../../../../config/".$page) && $page != 'index.php') {
       include("../../../../../config/".$page); 
    }

    else {
        echo "Page inexistante !";
    }
}
page_protect();
if(!checkAdmin()) {
die("Secured");
exit();
}

$job = '';
$id  = '';

if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_webmaster' ||
      $job == 'get_webmaster_add'   ||
      $job == 'add_webmaster'   ||
      $job == 'edit_webmaster'  ||
      $job == 'get_crypte_email'  ||
      $job == 'delete_email_crypto'  ||      
      $job == 'delete_webmaster'){
    if (isset($_GET['id'])){
      $id = $_GET['id'];
      if (!is_numeric($id)){
        $id = '';
      }
    }
  } else {
    $job = '';
  }
}


$mysql_data = array();


if ($job != ''){
  

  $db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  mysqli_set_charset($db_connection, "utf8");
  if (mysqli_connect_errno()){
    $result  = 'error';
    $message = 'Connexion à la base de données impossible : ' . mysqli_connect_error();
    $job     = '';
  }  

  if ($job == 'get_webmaster'){ 
	 
    $rs_webmaster = "SELECT * FROM webmaster";
    $rs_webmaster = mysqli_query($db_connection, $rs_webmaster);
    if (!$rs_webmaster){
        $result  = 'error';
        $message = 'Échec de requête';
      } else {
        $result  = 'success';
        $message = 'Succès de requête';
    while ($rrows = mysqli_fetch_array($rs_webmaster)) {
          
      $jouvers = (get_nb_open_days($rrows['date_debut_webmaster'], $rrows['date_fin_webmaster'])+1)*$rrows['nb_participants_webmaster']*0.50;		
      $id_webmaster = $rrows['id'];
      $update_webmaster = "UPDATE webmaster SET ";
      $update_webmaster .= "nb_jh_webmaster         = '" . mysqli_real_escape_string($db_connection, $jouvers)         . "' ";
      $update_webmaster .= "WHERE id = '" . mysqli_real_escape_string($db_connection, $id_webmaster) . "'";
      $update_webmaster  = mysqli_query($db_connection, $update_webmaster);	  
      
    }
    }

      $query_webmaster = "SELECT * FROM webmaster";
      $query_webmaster = mysqli_query($db_connection, $query_webmaster);
      if (!$query_webmaster){
        $result  = 'error';
        $message = 'Échec de requête';
      } else {
        $result  = 'success';
        $message = 'Succès de requête';
        while ($webmaster = mysqli_fetch_array($query_webmaster)){
          $functions  = '<center>';
          $functions .= '<a href="#" id="function_edit_web" data-id="'   . $webmaster['id'] . '" data-name="' . $webmaster['operations_webmaster'] . '"><span class="badge badge-success badge-rounded mb-3 mr-3">Modifier</span></a>';
          $functions .= '<a  href="#" id="del" data-id="' . $webmaster['id'] . '" data-name="' . $webmaster['operations_webmaster'] . '"><span  class="badge badge-danger badge-rounded mb-3 mr-3">Effacer</span></a>';
          $functions .= '</center>';
          $mysql_data[] = array(
            "Operation"          => $webmaster['operations_webmaster'],
            "participant"  => $webmaster['nb_participants_webmaster'],
            "compagne"    => $webmaster['compagnes_webmaster'],
            "debut"  => date("d/m/Y", strtotime($webmaster['date_debut_webmaster'])),
        "fin"  => date("d/m/Y", strtotime($webmaster['date_fin_webmaster'])),
        "jh"  => $webmaster['nb_jh_webmaster'],
            "functions"     => $functions
          );
        }
      }
    
  } elseif ($job == 'get_crypte_email'){ 
	 
    

      $query_crypte = "SELECT * FROM webmaster_crypte";
      $query_crypte = mysqli_query($db_connection, $query_crypte);
      if (!$query_crypte){
        $result  = 'error';
        $message = 'Échec de requête';
      } else {
        $result  = 'success';
        $message = 'Succès de requête';

        function myCrypt($value, $key, $iv){
          $encrypted_data = openssl_encrypt($value, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
          return base64_encode($encrypted_data);
        }

        $key="01234567890123456789012345678901"; // 32 bytes
        $vector="1234567890123412"; // 16 bytes

        
        

        while ($crypte = mysqli_fetch_array($query_crypte)){

          $functions  = '<center>';
          //$functions .= '<a href="#" id="function_edit_web" data-id="'   . $crypte['id_email'] . '" data-name="' . $crypte['email_original'] . '"><span class="badge badge-success badge-rounded mb-3 mr-3">Modifier</span></a>';
          $functions .= '<a href="#" id="del" data-id="' . $crypte['id_email'] . '" data-name="' . $crypte['email_original'] . '"><span  class="badge badge-danger badge-rounded mb-3 mr-3">Effacer</span></a>';
          $functions .= '</center>';
          $encrypted = myCrypt($crypte['email_original'], $key, $vector);
          $encrypted_final = urlencode($encrypted);
          $mysql_data[] = array(
              "id"          => $crypte['id_email'],
              "original"  => $crypte['email_original'],
              "crypte"    => $encrypted_final,
              "firstname"  => $crypte['contact_firstname'],
              "rs"  => $crypte['contact_RS'],
              "date"  => date("d/m/Y", strtotime($crypte['date_upload'])),
              "functions"     => $functions
          );
        }
      }
    
  } elseif ($job == 'get_webmaster_add'){
    

    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $query = "SELECT * FROM webmaster WHERE id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
		  $result  = 'error';
		  $message = 'Échec de requête';
		} else {
		  $result  = 'success';
		  $message = 'Succès de requête';
        while ($company = mysqli_fetch_array($query)){
          $mysql_data[] = array(
            "operation"          => $company['operations_webmaster'],
            "nb"  => $company['nb_participants_webmaster'],
            "compagne"    => $company['compagnes_webmaster'],
            "volume"       => $company['volume_mail_webmaster'],
			"debut"       => $company['date_debut_webmaster'],
			"fin"       => $company['date_fin_webmaster']
          );
        }
      }
    }
  
  } elseif ($job == 'add_webmaster'){
    

    $query = "INSERT INTO webmaster SET ";
    if (isset($_GET['operation']))         { $query .= "operations_webmaster         = '" . mysqli_real_escape_string($db_connection, $_GET['operation'])         . "', "; }
    if (isset($_GET['nb'])) { $query .= "nb_participants_webmaster = '" . mysqli_real_escape_string($db_connection, $_GET['nb']) . "', "; }
    if (isset($_GET['compagne']))   { $query .= "compagnes_webmaster   = '" . mysqli_real_escape_string($db_connection, $_GET['compagne'])   . "', "; }
	if (isset($_GET['volume']))   { $query .= "volume_mail_webmaster   = '" . mysqli_real_escape_string($db_connection, $_GET['volume'])   . "', "; }
	if (isset($_GET['debut']))   { $query .= "date_debut_webmaster   = '" . mysqli_real_escape_string($db_connection, $_GET['debut'])   . "', "; }
	if (isset($_GET['fin']))   { $query .= "date_fin_webmaster   = '" . mysqli_real_escape_string($db_connection, $_GET['fin'])   . "' "; }
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'Échec de requête';
    } else {
      $result  = 'success';
      $message = 'Succès de requête';
    }
  
  } elseif ($job == 'edit_webmaster'){
    

    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $query = "UPDATE webmaster SET ";
		if (isset($_GET['operation']))         { $query .= "operations_webmaster         = '" . mysqli_real_escape_string($db_connection, $_GET['operation']). "', "; }
		if (isset($_GET['nb'])) { $query .= "nb_participants_webmaster = '" . mysqli_real_escape_string($db_connection, $_GET['nb']) . "', "; }
		if (isset($_GET['compagne']))   { $query .= "compagnes_webmaster   = '" . mysqli_real_escape_string($db_connection, $_GET['compagne']). "', "; }
		if (isset($_GET['volume']))   { $query .= "volume_mail_webmaster   = '" . mysqli_real_escape_string($db_connection, $_GET['volume']). "', "; }
		if (isset($_GET['debut']))   { $query .= "date_debut_webmaster   = '" . mysqli_real_escape_string($db_connection, $_GET['debut']). "', "; }
		if (isset($_GET['fin']))   { $query .= "date_fin_webmaster   = '" . mysqli_real_escape_string($db_connection, $_GET['fin']). "' "; }
      $query .= "WHERE id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query  = mysqli_query($db_connection, $query);
      if (!$query){
			$result  = 'error';
			$message = 'Échec de requête';
			} else {
			$result  = 'success';
			$message = 'Succès de requête';
      }
    }
    
  } elseif ($job == 'delete_webmaster'){
  

    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $query = "DELETE FROM webmaster WHERE id = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
          $result  = 'error';
		  $message = 'Échec de requête';
		} else {
		  $result  = 'success';
		  $message = 'Succès de requête';
      }
    }
  
  } elseif ($job == 'delete_email_crypto'){
  

    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
      $query = "DELETE FROM webmaster_crypte WHERE id_email = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
          $result  = 'error';
		  $message = 'Échec de requête';
		} else {
		  $result  = 'success';
		  $message = 'Succès de requête';
      }
    }
  
  }
  

  mysqli_close($db_connection);

}


$data = array(
  "result"  => $result,
  "message" => $message,
  "data"    => $mysql_data
);


$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
print $json_data;
?>
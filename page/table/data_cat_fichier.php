<?php
include '../../config/dbc.php';
page_protect();

$job = '';
$id  = '';
if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_cat_fichier' ||
      $job == 'get_cat_fichier_add'   ||
      $job == 'add_cat_fichier'   ||
      $job == 'edit_cat_fichier'  ||
      $job == 'delete_cat_fichier'){
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
    $message = 'Failed to connect to database: ' . mysqli_connect_error();
    $job     = '';
  }
  
  if ($job == 'get_cat_fichier'){
    
    
    $query_qld = "SELECT * FROM fichiers_cat";
    $query_qld = mysqli_query($db_connection, $query_qld);
    if (!$query_qld){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
      while ($qld = mysqli_fetch_array($query_qld)){
        $functions  = '<center>';
        $functions .= '<a href="#" id="function_edit_cat_fichier" data-id="'   . $qld['id_cat_fichiers'] . '" data-name="' . $qld['nom_cat_fichiers'] . '"><span class="badge badge-success badge-rounded mb-3 mr-3">Modifier</span></a>';
        $functions .= '<a href="#" id="del" data-id="' . $qld['id_cat_fichiers'] . '" data-name="' . $qld['nom_cat_fichiers'] . '"><span  class="badge badge-danger badge-rounded mb-3 mr-3">Effacer</span></a>';
        $functions .= '</center>';
        $mysql_data[] = array(
          "nom"          => $qld['nom_cat_fichiers'],
          "functions"     => $functions
        );
      }
    }
    
  } elseif ($job == 'get_cat_fichier_add'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "SELECT * FROM fichiers_cat WHERE id_cat_fichiers = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
        	while ($company = mysqli_fetch_array($query)){
          $mysql_data[] = array(
            "nom"  => $company['nom_cat_fichiers']
          );
        }
      }
    }
  
  } elseif ($job == 'add_cat_fichier'){
    
    $query = "INSERT INTO fichiers_cat SET ";
    if (isset($_GET['nom']))         { $query .= "nom_cat_fichiers         = '" . mysqli_real_escape_string($db_connection, $_GET['nom'])         . "' "; }
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
    }
  
  } elseif ($job == 'edit_cat_fichier'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "UPDATE fichiers_cat SET ";
		if (isset($_GET['nom']))         { $query .= "nom_cat_fichiers         = '" . mysqli_real_escape_string($db_connection, $_GET['nom'])         . "' "; }
      $query .= "WHERE id_cat_fichiers = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query  = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
      }
    }
    
  } elseif ($job == 'delete_cat_fichier'){
  
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "DELETE FROM fichiers_cat WHERE id_cat_fichiers = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
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
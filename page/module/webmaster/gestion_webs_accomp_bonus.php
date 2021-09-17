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
    if (file_exists("../../../config/".$page) && $page != 'index.php') {
       include("../../../config/".$page); 
    }

    else {
        echo "Page inexistante !";
    }
}
if(isset($_GET['web_id'])){$web = $_GET['web_id'];}else{$web = '';}
page_protect();
if(!checkAdmin()) {
header("Location: ../index.php");
exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    
    <title>Gestion des primes - Affectation Bonus</title>
    
    <link rel="shortcut icon" href="img/logo/logop.ico">  
    <link rel="stylesheet" href="vendor/date-range-picker/daterangepicker.css">
    <link rel="stylesheet" href="vendor/datatables/datatables.min.css">
    <link rel="stylesheet" href="vendor/jquery-confirm/jquery-confirm.min.css">   
    <link rel="stylesheet" href="fonts/open-sans/style.min.css">
    <link rel="stylesheet" href="fonts/iconfont/iconfont.css">
    <link rel="stylesheet" href="vendor/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="vendor/select2/css/select2.min.css">
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.min.css" id="stylesheet">

    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet">
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    
    <link rel="stylesheet" href="module/webmaster/table/css/layout_web.css">

	<script src="js/ie.assign.fix.min.js"></script>
  
</head>

<body class="js-loading sidebar-md">

<div class="preloader">
  <div class="loader">
    <span class="loader__indicator"></span>
    <div class="loader__label"><img src="img/logo/LogoEnr.png" alt="" width="200"></div>
  </div>
</div>


<?php
$page = '';
if (empty($page)) {
 $page = "top";
 // On limite l'inclusion aux fichiers.php en ajoutant dynamiquement l'extension
 // On supprime également d'éventuels espaces
 $page = trim($page.".php");

}

// On évite les caractères qui permettent de naviguer dans les répertoires
$page = str_replace("../","protect",$page);
$page = str_replace(";","protect",$page);
$page = str_replace("%","protect",$page);

// On interdit l'inclusion de dossiers protégés par htaccess
if (preg_match("/include/",$page)) {
 echo "Vous n'avez pas accès à ce répertoire";
 }

else {

    // On vérifie que la page est bien sur le serveur
    if (file_exists("../../include/".$page) && $page != 'index.php') {
       include("../../include/".$page); 
    }

    else {
        echo "Page inexistante !";
    }
}
?>

<div class="page-wrap">
  
<?php
$page = '';
if (empty($page)) {
 $page = "sidebar";
 // On limite l'inclusion aux fichiers.php en ajoutant dynamiquement l'extension
 // On supprime également d'éventuels espaces
 $page = trim($page.".php");

}

// On évite les caractères qui permettent de naviguer dans les répertoires
$page = str_replace("../","protect",$page);
$page = str_replace(";","protect",$page);
$page = str_replace("%","protect",$page);

// On interdit l'inclusion de dossiers protégés par htaccess
if (preg_match("/include/",$page)) {
 echo "Vous n'avez pas accès à ce répertoire";
 }

else {

    // On vérifie que la page est bien sur le serveur
    if (file_exists("../../include/".$page) && $page != 'index.php') {
       include("../../include/".$page); 
    }

    else {
        echo "Page inexistantes !";
    }
}
?>

<div class="page-content">
    <div class="container-fluid">
    
      	<h2 class="content-heading">Module : Gestion des primes > <b>Affectation Bonus</b></h2> 
        <div class="content-description">
          
            Service : <span class="badge badge-secondary badge-rounded">Webmasters IPD</span>
                  
          </div>       
        <div class="main-container">
        
        <div class="container-block">            
        <div class="row">
        <div class="col-lg-6"> 
        <a class="btn btn-success icon-left mr-3" href="WebsAccomp">Retour liste globale <span class="btn-icon iconfont iconfont-step-arrow-left"></span></a>
        <a class="btn btn-info" href="#"  id="add_bonus">Ajouter un bonus</a> 
        </div>
        <div class="col-lg-6" style="text-align:right">             
        <a class="btn btn-primary icon-right"  href="#" id="refresh">Rafraîchissement du tableau <span class="btn-icon iconfont iconfont-refresh"></span></a>
        </div>
        </div>       
        </div> 
             
        <div class="container-block">
        <div class="row">            
        <div class="content table-responsive table-full-width">
                                      
        <table class="datatable table table-striped" id="table_webs_accomp_bonus" data-id="<?php echo $web;?>">
            <thead>
                <tr> 
                	
                    <th>Webmaster</th>
                    <th>Période</th>
                    <th>Commentaire</th>
                    <th>Bonus / DT</th>
                    <th></th>  
                     
                </tr>
            </thead>
            <tbody></tbody>
        </table>
                                         
        </div>
        </div>                                
        </div>
        </div>                    
    </div>
</div>
</div>   

<div class="lightbox_bg"></div>

<div class="lightbox_container">

  <div class="lightbox_close"></div>

  <div class="lightbox_content">
        
        <h2></h2>          
        
        <hr>
        <form class="form add" id="form_company" data-id="">

        <input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION['user_id'];?>" >
        <input type="hidden" id="user" name="user" value="<?php echo $_SESSION['user_name'];?>" >
        


        <div class="input_container">
        <label for="web">WEBMASTER : <span class="required">*</span></label>
        <div class="field_container">
            <select id="web" name="web" class="form-control" required>
                <option value="" selected>CHOISIR UN WEBMASTER</option>
                <?php
                $query = $bdd->prepare("SELECT full_name, id FROM `users` WHERE equipe_id = 1 AND id = :id");
                $query->bindParam(":id", $web, PDO::PARAM_INT);
                $query->execute();  
                while($query_collab = $query->fetch()){
                 echo '<option value="'.$query_collab['id'].'">'.$query_collab['full_name'].'</option>';   
                }
                $query->closeCursor();                  
                ?>
            </select>
        </div>
        </div>

        <div class="input_container">
        <label for="tri">TRIMESTRE : <span class="required">*</span></label>
        <div class="field_container">
            <select id="tri" name="tri" class="form-control" required>
                <option value="" selected>CHOISIR UN TRIMESTRE</option>
                <?php
                $query = $bdd->prepare("SELECT annee_accomp_tri, nom_accomp_tri, id_accomp_tri FROM `webmaster_accomp_tri` WHERE id_accomp_tri NOT IN (SELECT accomp_tri FROM webmaster_accomp_bonus WHERE web_id = :web_id)");
                $query->bindParam(":web_id", $web, PDO::PARAM_INT);
                $query->execute();  
                while($query_tri = $query->fetch()){
                 echo '<option value="'.$query_tri['id_accomp_tri'].'">'.$query_tri['nom_accomp_tri'].' - '.$query_tri['annee_accomp_tri'].'</option>';   
                }
                $query->closeCursor();                      
                ?>
            </select>
        </div>
        </div>

        <div class="input_container">
                <label for="comm">COMMENTAIRE :</label>
                <textarea rows="4" placeholder="" class="form-control" id="comm" name="comm"></textarea>
        </div>


        <div class="input_container">
                <label for="bonus">Bonus : <span class="required">*</span></label>
                <div class="field_container">
                    <input id="bonus" name="bonus" type="number" max="270" value="" class="form-control" required/>
                </div>
        </div>       

        <hr>

            <div class="form-group">
            <button type="submit" class="btn btn-info btn-block"></button>
            </div>
          
        </form>
    </div>
</div>



<div id="message_container">
    <div class="success" id="message">
        <p>Opération réussie.</p>
    </div>
</div>
<div id="loading_container">
    <div id="loading_container2">
        <div id="loading_container3">
            <div id="loading_container4">
                Chargement ...
            </div>
        </div>
    </div>
</div>

<script src="vendor/echarts/echarts.min.js"></script>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/datatables/datatables.min.js"></script>
<script src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
<script src="js/jquery.validate.min.js"></script>

<script src="module/webmaster/table/js/webapp_gestion_webs_accomp_bonus.js"></script>

<script src="vendor/jquery-confirm/jquery-confirm.min.js"></script>
<script src="vendor/popper/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/simplebar/simplebar.js"></script>
<script src="vendor/text-avatar/jquery.textavatar.js"></script>
<script src="vendor/flatpickr/flatpickr.min.js"></script>
<script src="vendor/wnumb/wNumb.js"></script>
<script src="js/main.js"></script>
<script src="js/growl-notification/growl-notification.js"></script>
<script src="js/preview/growl-notifications.min.js"></script>
<script src="vendor/momentjs/moment-with-locales.min.js"></script>
<script src="vendor/date-range-picker/daterangepicker.js"></script>
<script src="js/preview/date-range-picker.js"></script>
<script src="vendor/nouislider/nouislider.min.js"></script>
<script src="vendor/tagify/tagify.min.js"></script>
<script src="js/preview/modal.min.js"></script>
<script src="js/preview/datepicker.min.js"></script>
<!--<script src="vendor/select2/js/select2.full.min.js"></script>-->

<div class="sidebar-mobile-overlay"></div> 
 
</body>
</html>
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
page_protect();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <title>CARTES VISITES (LK)</title>
  <link rel="shortcut icon" href="img/logo/logop.ico">
<link rel="stylesheet" href="vendor/datatables/datatables.min.css">
  
<link rel="stylesheet" href="fonts/open-sans/style.min.css">
<link rel="stylesheet" href="fonts/iconfont/iconfont.css">
<link rel="stylesheet" href="vendor/flatpickr/flatpickr.min.css">
<link rel="stylesheet" href="vendor/select2/css/select2.min.css">
<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.min.css" id="stylesheet">

<link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet">
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="module/linkedin/table/css/layout_lk.css">
  

<script src="js/ie.assign.fix.min.js"></script>
  
</head>
<body class="js-loading  sidebar-md">

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
<div class="container-fluid m-tasks">
  <div class="main-container m-tasks__container container-heading-bordered">
    <h2 class="container-heading">
      <span>Bibliothèque Cartes Visites (LK)</span>
      <a class="btn btn-primary icon-right btn-sm mr-3" href="javascript:window.location.reload()">Rafraîchissement des données <span class="btn-icon iconfont iconfont-refresh"></span></a>
    </h2>
    
    <div class="container-body m-tasks__columns">
      
      
      <div class="m-tasks__column m-tasks__column--pending list-group">      
      
        <div class="m-tasks__column-header">
          <span class="m-tasks__column-icon iconfont iconfont-task-label"></span>
          <span class="m-tasks__column-name">EN ATTENTES</span>
        </div>
        <div class="m-tasks__items"> 
        <?php
			
			$query = $bdd->prepare("SELECT count(*) FROM cat_acide WHERE id_cat_acide NOT IN (SELECT id_cat_acide FROM cat_synthese_acide)");
			$query->execute();
			$count = $query->fetchColumn();
			$query->closeCursor();
			if($count == 0){
			}else{
			$query = $bdd->prepare("SELECT * FROM cat_acide WHERE id_cat_acide NOT IN (SELECT id_cat_acide FROM cat_synthese_acide)");	
			$query->execute();			
			while ($donnees_attente = $query->fetch()){
					echo '<a href="LinkedinBiblio-'.$donnees_attente['id_cat_acide'].'">       
					<div class="m-tasks__item list-group-item">            
					<h6 class="m-tasks__item-name">'.$donnees_attente['nom_cat_acide'].'</h6>
					<div class="m-tasks__item-desc">
					<span class="badge badge-buttercup mb-3 mr-3">En attente de traitement</span><br>
					</div>
					<span class="m-tasks__item-date">Date d\'insertion : '.date("d-M-Y", strtotime($donnees_attente['date_ajout_cat_acide'])).'</span>				
					</div>
					</a>';			
			}			
			$query->closeCursor();
			}
		
		?>
        </div>
        
        
      </div>
      
      <div class="m-tasks__column m-tasks__column--in-progress list-group">
        <div class="m-tasks__column-header">
          <span class="m-tasks__column-icon iconfont iconfont-task-label"></span>
          <span class="m-tasks__column-name">EN COURS</span>
        </div>
        <div class="m-tasks__items">
          <?php
			$query = $bdd->prepare("SELECT count(*) FROM cat_acide INNER JOIN cat_synthese_acide ON cat_synthese_acide.id_cat_acide = cat_acide.id_cat_acide WHERE cat_synthese_acide.niveau = 1");
			$query->execute();
			$count = $query->fetchColumn();
			$query->closeCursor();
			if($count == 0){		
				
			}else{
			$query = $bdd->prepare("SELECT cat_acide.id_cat_acide, cat_acide.nom_cat_acide, cat_acide.fichier_cat_acide, cat_acide.date_ajout_cat_acide, cat_synthese_acide.id_intervenant_cat_acide, cat_synthese_acide.intervenant_cat_acide FROM cat_acide INNER JOIN cat_synthese_acide ON cat_synthese_acide.id_cat_acide = cat_acide.id_cat_acide WHERE cat_synthese_acide.niveau = 1");	
			$query->execute();			
			while ($donnees_attente = $query->fetch()){
				
				if($donnees_attente['id_intervenant_cat_acide'] == $_SESSION['user_id']){					
				echo '<a href="LinkedinBiblio-debut-'.$donnees_attente['id_cat_acide'].'">       
				<div class="m-tasks__item list-group-item">            
				<h6 class="m-tasks__item-name">'.$donnees_attente['nom_cat_acide'].'</h6>
				<div class="m-tasks__item-desc">
				<span class="badge badge-lasur mb-3 mr-3">En cours</span><br>
				</div>
				<span class="m-tasks__item-date">Date d\'insertion : '.date("d-M-Y", strtotime($donnees_attente['date_ajout_cat_acide'])).'</span><br>
				<span class="m-tasks__item-date">Intervenant : <b>'.$donnees_attente['intervenant_cat_acide'].'</b></span>
				<span class="m-tasks__item-priority iconfont iconfont-task-bell m-tasks__item-priority--normal"></span>
				</div>
				</a>';				
				}else{					
				echo '      
				<div class="m-tasks__item list-group-item">            
				<h6 class="m-tasks__item-name">'.$donnees_attente['nom_cat_acide'].'</h6>
				<div class="m-tasks__item-desc">
				<span class="badge badge-lasur mb-3 mr-3">En cours</span><br>
				</div>
				<span class="m-tasks__item-date">Date d\'insertion : '.date("d-M-Y", strtotime($donnees_attente['date_ajout_cat_acide'])).'</span><br>
				<span class="m-tasks__item-date">Intervenant : <b>'.$donnees_attente['intervenant_cat_acide'].'</b></span>
				<span class="m-tasks__item-priority iconfont iconfont-task-bell m-tasks__item-priority--normal"></span>
				</div>';				
				}
					
			}
			$query->closeCursor();
			
			}
				
			
		
		?>         
          
        </div>
      </div>
      
      <div class="m-tasks__column m-tasks__column--completed list-group">
        <div class="m-tasks__column-header">
          <span class="m-tasks__column-icon iconfont iconfont-task-label"></span>
          <span class="m-tasks__column-name">EN PROGRESSION</span>
        </div>
        <div class="m-tasks__items">
          
          <?php
			
			$query = $bdd->prepare("SELECT cat_synthese_acide.niveau, cat_acide.statut_cat_fichier, cat_acide.id_cat_acide, cat_acide.nom_cat_acide, cat_acide.fichier_cat_acide, cat_acide.date_ajout_cat_acide, cat_synthese_acide.intervenant_cat_acide FROM cat_acide INNER JOIN cat_synthese_acide ON cat_synthese_acide.id_cat_acide = cat_acide.id_cat_acide GROUP BY cat_synthese_acide.id_cat_acide");	
			$query->execute();			
			while ($donnees_attente = $query->fetch()){
				
				$query_ligne = $bdd->prepare("SELECT * FROM acide WHERE reporting = 0 AND id_cat_acide = :id_cat_acide");
				$query_ligne->bindParam(":id_cat_acide", $donnees_attente['id_cat_acide'], PDO::PARAM_INT);
				$query_ligne->execute();
				$verif_ligne = $query_ligne->fetchColumn();
				$query_ligne->closeCursor();
				
				$query_etat = $bdd->prepare("SELECT * FROM cat_synthese_acide WHERE niveau = 1 AND id_cat_acide = :id_cat_acide");
				$query_etat->bindParam(":id_cat_acide", $donnees_attente['id_cat_acide'], PDO::PARAM_INT);
				$query_etat->execute();
				$verif_etat = $query_etat->fetchColumn();
				$query_etat->closeCursor();
				if($verif_ligne > 0){
					
					if($verif_etat > 0){
					echo '      
					<div class="m-tasks__item list-group-item">            
					<h6 class="m-tasks__item-name">'.$donnees_attente['nom_cat_acide'].'</h6>
					<div class="m-tasks__item-desc">
					<span class="badge badge-bittersweet mb-3 mr-3">En progression</span><br>					
					</div>
					<span class="m-tasks__item-date">Date d\'insertion : '.date("d-M-Y", strtotime($donnees_attente['date_ajout_cat_acide'])).'</span><br>
					<span class="m-tasks__item-priority iconfont iconfont-task-bell m-tasks__item-priority--normal"></span>
					</div>
					';
					}else{
					echo '<a href="LinkedinBiblio-'.$donnees_attente['id_cat_acide'].'">      
					<div class="m-tasks__item list-group-item">            
					<h6 class="m-tasks__item-name">'.$donnees_attente['nom_cat_acide'].'</h6>
					<div class="m-tasks__item-desc">
					<span class="badge badge-bittersweet mb-3 mr-3">En progression</span><br>					
					</div>
					<span class="m-tasks__item-date">Date d\'insertion : '.date("d-M-Y", strtotime($donnees_attente['date_ajout_cat_acide'])).'</span><br>
					<span class="m-tasks__item-priority iconfont iconfont-task-bell m-tasks__item-priority--normal"></span>
					</div>
					</a>';
					
					}
				
				}else{			
					
						
					
					
					
				}
			
			}
			$query->closeCursor();		
		
		?>
        
        </div>
        
      </div>
      
      <div class="m-tasks__column m-tasks__column--completed list-group">
        <div class="m-tasks__column-header">
          <span class="m-tasks__column-icon iconfont iconfont-task-label"></span>
          <span class="m-tasks__column-name">TERMINÉES</span>
        </div>
        <div class="m-tasks__items">
          
          <?php
			$query = $bdd->prepare("SELECT cat_acide.id_cat_acide, cat_acide.nom_cat_acide, cat_acide.fichier_cat_acide, cat_acide.date_ajout_cat_acide, cat_synthese_acide.intervenant_cat_acide FROM cat_acide INNER JOIN cat_synthese_acide ON cat_synthese_acide.id_cat_acide = cat_acide.id_cat_acide GROUP BY cat_acide.id_cat_acide");	
			$query->execute();			
			while ($donnees_attente = $query->fetch()){
				
				$query_ligne = $bdd->prepare("SELECT * FROM acide WHERE reporting = 0 AND id_cat_acide = :id_cat_acide");
				$query_ligne->bindParam(":id_cat_acide", $donnees_attente['id_cat_acide'], PDO::PARAM_INT);
				$query_ligne->execute();
				$verif_ligne = $query_ligne->fetchColumn();
				$query_ligne->closeCursor();
				if($verif_ligne > 0){
					
					
				
				}else{			
					
					
					echo '<a href="LinkedinBiblio-'.$donnees_attente['id_cat_acide'].'">       
					<div class="m-tasks__item list-group-item">            
					<h6 class="m-tasks__item-name">'.$donnees_attente['nom_cat_acide'].'</h6>
					<div class="m-tasks__item-desc">
					<span class="badge badge-shamrock mb-3 mr-3">TerminÉ</span><br>
					</div>
					<span class="m-tasks__item-date">Date d\'insertion : '.date("d-M-Y", strtotime($donnees_attente['date_ajout_cat_acide'])).'</span><br>
					<span class="m-tasks__item-priority iconfont iconfont-task-bell m-tasks__item-priority--normal"></span>
					</div>
					</a>';
					
				}
				
			}
			$query->closeCursor();	
		
		?>
        </div>
        
      </div>
      
      
    </div>
</div>

  </div>
</div>   
</div>  
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/popper/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/select2/js/select2.full.min.js"></script>
<script src="vendor/simplebar/simplebar.js"></script>
<script src="vendor/text-avatar/jquery.textavatar.js"></script>
<script src="vendor/flatpickr/flatpickr.min.js"></script>
<script src="vendor/wnumb/wNumb.js"></script>
<script src="js/main.js"></script>


<script src="vendor/sortable/sortable.min.js"></script>
<script src="js/preview/tasks.min.js"></script>

<div class="sidebar-mobile-overlay"></div>  
</body>
</html>
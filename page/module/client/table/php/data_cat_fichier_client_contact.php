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
}
$job = '';
$id  = '';

if (isset($_GET['job'])){
	
  $job = $_GET['job'];
  
  if ($job == 'get_cat_fichier' ||
      $job == 'get_cat_fichier_add' ||
      $job == 'add_cat_fichier' ||
      $job == 'edit_cat_fichier' ||
      $job == 'delete_cat_fichier' ||
	  $job == 'get_gestion_traitement_lk_obj' ||
      $job == 'delete_gestion_traitement_lk_obj' ||
	  $job == 'edit_gestion_traitement_lk_obj' ||
	  $job == 'add_gestion_traitement_lk_obj'   ||
	  $job == 'get_gestion_traitement_add_lk_obj'){
		  
		if (isset($_GET['id'])){
			$id = $_GET['id'];
			if (!is_numeric($id)){
			$id = '';
			}
    	}
		if (isset($_GET['id_cat_mere'])){
			$id_cat_mere = $_GET['id_cat_mere'];
			if (!is_numeric($id_cat_mere)){
			$id_cat_mere = '';
			}
    	}
		if (isset($_GET['cat'])){
			$cat = $_GET['cat'];}
			
		if (isset($_GET['intervalle'])){
		  $date = $_GET['intervalle'];
		}
		
  }else{$job = '';}
}

$mysql_data = array();

if ($job != ''){  
  
  if ($job == 'get_cat_fichier'){
	  
    /*try 
	{*/
	$query_global = $bdd->prepare("SELECT * FROM client_cat WHERE id_client_cat_oraga = :id_client_cat_oraga ORDER BY id_cat DESC");
	$query_global->bindParam(":id_client_cat_oraga", $id_cat_mere, PDO::PARAM_INT);
	$query_global->execute();
	
	while ($doc = $query_global->fetch()){
		
		
			
		$query = $bdd->prepare("SELECT MAX(date_fin_traitement) AS date_fin, MIN(date_debut_traitement) AS date_debut FROM client_cat_synthese_fiche_update_contact WHERE lot_id = :lot_id");
		$query->bindParam(":lot_id", $doc['id_cat'], PDO::PARAM_INT);
		$query->execute();
		$select_date = $query->fetch();
		$query->closeCursor();			
			if($select_date['date_fin'] <> NULL){$fin = date("d-m-Y", strtotime($select_date['date_fin']));}else{$fin = '';}
			if($select_date['date_debut'] <> NULL){$debut = date("d-m-Y", strtotime($select_date['date_debut']));}else{$debut = '';}
		
		
		/*$query_somme_total_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat");
		$query_somme_total_ligne->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_somme_total_ligne->execute();
		$somme_total_s = $query_somme_total_ligne->fetchColumn();
		$query_somme_total_ligne->closeCursor();*/
		
		/*$query_somme_total_ligne = $bdd->prepare("SELECT COUNT(DISTINCT(siret_client)) FROM `client_traitement` WHERE id_cat = :id_cat");
		$query_somme_total_ligne->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_somme_total_ligne->execute();
		$somme_total = $query_somme_total_ligne->fetchColumn();
		$query_somme_total_ligne->closeCursor();*/
		
		$query_ligne_taiter = $bdd->prepare("SELECT COUNT(DISTINCT(siret_client)) FROM `client_traitement` WHERE id_cat = :id_cat AND (n_stat is null or n_stat = '')");
		$query_ligne_taiter->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_ligne_taiter->execute();
		$ligne_total_societe = $query_ligne_taiter->fetchColumn();
		$query_ligne_taiter->closeCursor();
		
		$query_ligne_taiter = $bdd->prepare("SELECT COUNT(DISTINCT(siret_client)) FROM `client_traitement` WHERE reporting_contact = 1 AND id_cat = :id_cat AND (n_stat is null or n_stat = '')");
		$query_ligne_taiter->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_ligne_taiter->execute();
		$ligne_taiter_societe = $query_ligne_taiter->fetchColumn();
		$query_ligne_taiter->closeCursor();
		
		/*$query_ligne_taiter = $bdd->prepare("SELECT COUNT(DISTINCT(siret_client)) FROM `client_traitement` WHERE reporting_contact = 0 AND id_cat = :id_cat");
		$query_ligne_taiter->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_ligne_taiter->execute();
		$ligne_taiter_societe_non = $query_ligne_taiter->fetchColumn();
		$query_ligne_taiter->closeCursor();*/
			
		$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0");
		$query_somme_traite_ligne->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_somme_traite_ligne->execute();
		$somme_traite = $query_somme_traite_ligne->fetchColumn();
		$query_somme_traite_ligne->closeCursor();
		
		$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 0");
		$query_somme_traite_ligne->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_somme_traite_ligne->execute();
		$somme_traite_indispo = $query_somme_traite_ligne->fetchColumn();
		$query_somme_traite_ligne->closeCursor();
		
		$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 1");
		$query_somme_traite_ligne->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_somme_traite_ligne->execute();
		$somme_traite_non_verif = $query_somme_traite_ligne->fetchColumn();
		$query_somme_traite_ligne->closeCursor();
		
		
		$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 2");
		$query_somme_traite_ligne->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_somme_traite_ligne->execute();
		$somme_traite_quitter = $query_somme_traite_ligne->fetchColumn();
		$query_somme_traite_ligne->closeCursor();
		
		$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 3");
		$query_somme_traite_ligne->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_somme_traite_ligne->execute();
		$somme_traite_ok = $query_somme_traite_ligne->fetchColumn();
		$query_somme_traite_ligne->closeCursor();
		
		$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 4");
		$query_somme_traite_ligne->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_somme_traite_ligne->execute();
		$somme_traite_ok_modif = $query_somme_traite_ligne->fetchColumn();
		$query_somme_traite_ligne->closeCursor();
		
		
		$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 5");
		$query_somme_traite_ligne->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_somme_traite_ligne->execute();
		$somme_traite_remplace = $query_somme_traite_ligne->fetchColumn();
		$query_somme_traite_ligne->closeCursor();
		
		$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 6");
		$query_somme_traite_ligne->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_somme_traite_ligne->execute();
		$somme_traite_hc = $query_somme_traite_ligne->fetchColumn();
		$query_somme_traite_ligne->closeCursor();
		
		
		$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 7");
		$query_somme_traite_ligne->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_somme_traite_ligne->execute();
		$somme_traite_ajouter = $query_somme_traite_ligne->fetchColumn();
		$query_somme_traite_ligne->closeCursor();


		$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 8");
		$query_somme_traite_ligne->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_somme_traite_ligne->execute();
		$somme_traite_refus = $query_somme_traite_ligne->fetchColumn();
		$query_somme_traite_ligne->closeCursor();


		$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 9");
		$query_somme_traite_ligne->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_somme_traite_ligne->execute();
		$somme_traite_encours = $query_somme_traite_ligne->fetchColumn();
		$query_somme_traite_ligne->closeCursor();


		$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 10");
		$query_somme_traite_ligne->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_somme_traite_ligne->execute();
		$somme_traite_ok_charge = $query_somme_traite_ligne->fetchColumn();
		$query_somme_traite_ligne->closeCursor();


		$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 11");
		$query_somme_traite_ligne->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_somme_traite_ligne->execute();
		$somme_traite_ok_prise = $query_somme_traite_ligne->fetchColumn();
		$query_somme_traite_ligne->closeCursor();


		$query_somme_traite_ligne = $bdd->prepare("SELECT COUNT(*) FROM `client_traitement` WHERE id_cat = :id_cat AND reporting_contact <> 0 AND n_stat_contact = 12");
		$query_somme_traite_ligne->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_somme_traite_ligne->execute();
		$somme_traite_ko = $query_somme_traite_ligne->fetchColumn();
		$query_somme_traite_ligne->closeCursor();
		
		
		
		$query_somme_participant = $bdd->prepare("SELECT COUNT(DISTINCT(user_id_contact)) FROM `client_traitement` WHERE id_cat = :id_cat AND user_id_contact <> 0");
		$query_somme_participant->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_somme_participant->execute();
		$somme_collab = $query_somme_participant->fetchColumn();
		$query_somme_participant->closeCursor();
						
		/*$query_save = $bdd->prepare("UPDATE client_cat SET intervenant_cat = :intervenant_cat WHERE id_cat = :id_cat");
		$query_save->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_save->bindParam(":intervenant_cat", $somme_collab, PDO::PARAM_INT);
		$query_save->execute();
		$query_save->closeCursor();*/
		
		
		if($id_cat_mere == 2)	{
		$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec) + 165000) AS datee FROM client_cat_synthese_fiche_update_contact WHERE lot_id = :lot_id");
		}else{
		$query = $bdd->prepare("SELECT SEC_TO_TIME(SUM(temps_sec)) AS datee FROM client_cat_synthese_fiche_update_contact WHERE lot_id = :lot_id");
		}
		
		
		$query->bindParam(":lot_id", $doc['id_cat'], PDO::PARAM_INT);
		$query->execute();	
		$query_temps = $query->fetch();
		$query->closeCursor();
		
		if($query_temps['datee'] <> NULL){	
		
		$traitement = '<strong>'.$query_temps['datee'].'</strong>';
		
		$pieces = explode(":", $query_temps['datee']);
				
		$duree_decimal = $pieces[0] + ($pieces[1]/60) + ($pieces[2]/3600);	
			
		$jh = round($duree_decimal/8, 2);	
		
		//$somme_traite_pourcentage = '<span class="badge badge-buttercup badge-rounded mb-3 mr-3">'.round(($somme_traite/$somme_total)*100).'%</span>';	
		
		}else{$traitement = '';	
			
		$jh = '';
		
		//$somme_traite_pourcentage = '<span class="badge badge-info badge-rounded mb-3 mr-3">0%</span>';
		
		}		
		
		
		
		
		$query_calcul_update = $bdd->prepare("SELECT count(*) FROM client_cat_synthese WHERE id_cat IN (SELECT id_cat FROM client_cat_synthese WHERE id_cat = :id_cat)");
		$query_calcul_update->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
		$query_calcul_update->execute();
		$rowcountupdate = $query_calcul_update->fetchColumn();
		$query_calcul_update->closeCursor();
	
		if ($rowcountupdate > 0){
			
			$query_calcul_statut = $bdd->prepare("SELECT count(*) FROM client_cat_synthese WHERE id_cat = :id_cat  AND niveau <> 2");
			$query_calcul_statut->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
			$query_calcul_statut->execute();
			$rowcountst = $query_calcul_statut->fetchColumn();
			$query_calcul_statut->closeCursor();
			
			if ($rowcountst > 0){
				$query_update_statut = $bdd->prepare("UPDATE client_cat SET statut_cat_fichier = 2 WHERE id_cat = :id_cat");
				$query_update_statut->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
				$query_update_statut->execute();
				$query_update_statut->closeCursor();
			}elseif ($rowcountst == 0){
				$query_verif_ligne = $bdd->prepare("SELECT count(*) FROM client_traitement WHERE reporting = 0 AND id_cat = :id_cat");
				$query_verif_ligne->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
				$query_verif_ligne->execute();
				$verif_ligne = $query_verif_ligne->fetchColumn();
				$query_verif_ligne->closeCursor();
				if($verif_ligne > 0){
					$query_update_statut = $bdd->prepare("UPDATE client_cat SET statut_cat_fichier = 2 WHERE id_cat = :id_cat");
					$query_update_statut->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
					$query_update_statut->execute();
					$query_update_statut->closeCursor();
				}else{
					$query_update_statut = $bdd->prepare("UPDATE client_cat SET statut_cat_fichier = 1 WHERE id_cat = :id_cat");
					$query_update_statut->bindParam(":id_cat", $doc['id_cat'], PDO::PARAM_INT);
					$query_update_statut->execute();
					$query_update_statut->closeCursor();
				}
			}
			
		}else{
			$query_update_statut = $bdd->prepare("UPDATE client_cat SET statut_cat_fichier = 3 WHERE id_cat = :id_cat");
			$query_update_statut->bindParam(":id_cat", $acide['id_cat'], PDO::PARAM_INT);
			$query_update_statut->execute();
			$query_update_statut->closeCursor();		
		}
		
		
		if ($doc['statut_cat_fichier'] == 1) {
		$statut = '<span class="badge badge-success">CLOTURÉ</span>';	
		}elseif ($doc['statut_cat_fichier'] == 3){
		$statut = '<span class="badge badge-info">EN ATTENTE</span>';	
		}elseif ($doc['statut_cat_fichier'] == 2){
		$statut = '<span class="badge badge-warning">EN PROGRESSION</span>';
		}
		
		$functions  = '';
				
		$functions .= '<a href="ClientBiblioDetailsJourContact-' . $doc['id_cat'] . '-'.$id_cat_mere.'"><span class="badge badge-info mb-3 mr-3"><span class="btn-icon iconfont iconfont-listing"></span></span></a>';
		$functions .= '<a href="ClientBiblioAdmin-' . $doc['id_cat'] . '-2-'.$id_cat_mere.'"><span class="badge badge-success mb-3 "><span class="btn-icon iconfont iconfont-document-solid"></span></span></a>';
				
		$functions .= '';	
		
		//$somme_non_traite = $somme_total - $somme_traite;		
		
		$ligne_taiter_societe_non = $ligne_total_societe - $ligne_taiter_societe;
		
		$fichier = ''.$doc['nom_cat'].'';	
		
		$mysql_data[] = array(
		
		  "statut"  => $statut,	
		  
		  "societetotall"  => $ligne_total_societe,
		  
		  "societetotal"  => $ligne_taiter_societe_non,
		  
		  "societetraitee"  => $ligne_taiter_societe,
		  	  
		  "traite"  => $somme_traite,
		  
		  "traiteindispo"  => $somme_traite_indispo,
		  "traitenonverif"  => $somme_traite_non_verif,
		  "traitequite"  => $somme_traite_quitter,
		  "traiteok"  => $somme_traite_ok,
		  "traiteokmodif"  => $somme_traite_ok_modif,
		  "traiteremplace"  => $somme_traite_remplace,
		  "traitehc"  => $somme_traite_hc,
		  "traiteajout"  => $somme_traite_ajouter,

		  "traiteokcharge"  => $somme_traite_ok_charge,
		  "traiteokprise"  => $somme_traite_ok_prise,
		  "traiteko"  => $somme_traite_ko,
		  "traiteencour"  => $somme_traite_encours,
		  "traiterefus"  => $somme_traite_refus,
		  
		  
		  
		  
		  "fichier"  => $fichier,
		  "collab" => $somme_collab,
		  "fin"  => $fin,
		  "debut"  => $debut,
		  "temps"  => $traitement,
		  "jh"  => $jh,
          "functions" => $functions
        );
		
	}
	  
    $query_global->closeCursor();
	$result  = 'success';
	$message = 'Succès de requête';					
	/*}
	catch(PDOException $x) 
	{ 	
	die("Secured");	
	$result  = 'error';
	$message = 'Échec de requête';	
	}*/ 
	$bdd = null;   
    
  } elseif ($job == 'get_cat_fichier_add'){    
   	  
  }elseif ($job == 'add_cat_fichier'){ 
  
  }elseif ($job == 'edit_cat_fichier'){    
       
  }elseif ($job == 'get_gestion_traitement_lk_obj'){

	try
	{
	$PDO_query_traitement = $bdd->prepare("SELECT * FROM `client_cat_synthese_fiche_obj` WHERE type = 1 ORDER BY id_objectif ASC");
	$PDO_query_traitement->execute();
	while ($traitement = $PDO_query_traitement->fetch()){
		
			$ligne = $traitement['nbligne_objectif'];
			$heure = $traitement['nbheure_objectif'];
			
			$date_debut = date_change_format($traitement['debut_objectf'],'Y-m-d','d/m/Y');
			$date_fin = date_change_format($traitement['fin_objectif'],'Y-m-d','d/m/Y');
			
			$datenow = date("Y-m-d");
			if($datenow <= $traitement['fin_objectif'] && $datenow >= $traitement['debut_objectf']){$actif = '<span class="badge-circle badge-circle-success mr-3">Actif</span>';}else{$actif = '<span class="badge-circle badge-circle-danger mr-3">Non actif</span>';}
			
			
			$functions  = '';
			$functions .= '<a  href="#" id="function_edit_obj" data-id="'   . $traitement['id_objectif'] . '" data-name="OBJECTIF"><span class="badge badge-shamrock mb-3 mr-3">Modifier</span></a>';			
			$functions .= '<a href="#" id="del_obj" data-id="' . $traitement['id_objectif'] . '" data-name="OBJECTIF"  data-doc="OBJECTIF"><span  class="badge badge-bittersweet mb-3 mr-3">Effacer</span></a>';
			$functions .= '';	
			  
			$mysql_data[] = array(		
			  "ligne" => $ligne,
			  "heure" => $heure,
			  "date_debut" => $date_debut,
			  "date_fin" => $date_fin,
			  "actif" => $actif,
			  "functions" => $functions
			);
		
	}
	$PDO_query_traitement->closeCursor();
	$result  = 'success';
	$message = 'Succès de requête';					
	}
	catch(PDOException $x) 
	{ 	
	die("Secured");	
	$result  = 'error';
	$message = 'Échec de requête';	
	}
	$bdd = null;
	$PDO_query_traitement = null;
	    
  
  }elseif ($job == 'get_gestion_traitement_add_lk_obj'){
    
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
		try
		{
		$query = $bdd->prepare("SELECT * FROM client_cat_synthese_fiche_obj WHERE id_objectif = :id AND type = 1");
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->execute();
		while ($traitement_edit = $query->fetch()){
			
			$intervalle = $traitement_edit['debut_objectf'].' - '.$traitement_edit['fin_objectif'];
			$mysql_data[] = array(
			"heure"  => $traitement_edit['nbheure_objectif'],
			"fiche"  => $traitement_edit['nbligne_objectif'],
			"intervalle"  => $intervalle
			);
		  
		  
		}
		$query->closeCursor();
		$result  = 'success';
		$message = 'Succès de requête';					
		}
		catch(PDOException $x) 
		{ 	
		die("Secured");	
		$result  = 'error';
		$message = 'Échec de requête';	
		}
		$bdd = null;
		$query = null;
      
    }
  
}elseif ($job == 'add_gestion_traitement_lk_obj'){
    
		try
		{
		$debut = substr($date, 0,10);
		$fin = substr($date, 13,22);
		$query = $bdd->prepare("INSERT INTO client_cat_synthese_fiche_obj (nbligne_objectif, nbheure_objectif, debut_objectf, fin_objectif, type) VALUES (:nbligne_objectif, :nbheure_objectif, :debut_objectf, :fin_objectif, 1)");
		$query->bindParam(":nbligne_objectif", $_GET['fiche'], PDO::PARAM_INT);
		$query->bindParam(":nbheure_objectif", $_GET['heure'], PDO::PARAM_INT);
		$query->bindParam(":debut_objectf", $debut, PDO::PARAM_STR);
		$query->bindParam(":fin_objectif", $fin, PDO::PARAM_STR);
		$query->execute();
		$query->closeCursor();
		$result  = 'success';
		$message = 'Succès de requête';					
		}
		catch(PDOException $x) 
		{ 	
		die("Secured");	
		$result  = 'error';
		$message = 'Échec de requête';	
		}
		$bdd = null;
		$query = null;
  
  }elseif ($job == 'edit_gestion_traitement_lk_obj'){
	  
	  
    if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
   		try
		{
			$debut = substr($date, 0,10);
		$fin = substr($date, 13,22);
		$query = $bdd->prepare("UPDATE client_cat_synthese_fiche_obj SET nbligne_objectif = :nbligne_objectif, nbheure_objectif = :nbheure_objectif, debut_objectf = :debut_objectf, fin_objectif = :fin_objectif, type = 1 WHERE id_objectif = :id");
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		
		
		$query->bindParam(":nbligne_objectif", $_GET['fiche'], PDO::PARAM_STR);
		$query->bindParam(":nbheure_objectif", $_GET['heure'], PDO::PARAM_STR);
		$query->bindParam(":debut_objectf", $debut, PDO::PARAM_STR);
		$query->bindParam(":fin_objectif", $fin, PDO::PARAM_STR);
		$query->execute();
		$query->closeCursor();
		$result  = 'success';
		$message = 'Succès de requête';					
		}
		catch(PDOException $x) 
		{ 	
		die("Secured");	
		$result  = 'error';
		$message = 'Échec de requête';	
		}
		$bdd = null;
		$query = null;
	}
    
  }elseif ($job == 'delete_gestion_traitement_lk_obj'){
	  
  	if ($id == ''){
      $result  = 'error';
      $message = 'Échec id';
    } else {
    	try
		{
		$query = $bdd->prepare("DELETE FROM client_cat_synthese_fiche_obj WHERE id_objectif = :id AND type = 1");
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();
		$result  = 'success';
		$message = 'Succès de requête';					
		}
		catch(PDOException $x) 
		{ 	
		die("Secured");	
		$result  = 'error';
		$message = 'Échec de requête';	
		}
		$bdd = null;
		$query = null;
	}
    
  
  }elseif ($job == 'delete_cat_fichier'){
  	
	 
  }
}

$data = array(
  "result"  => $result,
  "message" => $message,
  "data"    => $mysql_data
);

$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
print $json_data;
?>
<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=database', 'root', '');
$msg = $bdd->prepare('SELECT * FROM messages WHERE id_destinataire = ? ORDER BY id DESC');
$msg->execute(array($_SESSION['user_id']));
$msg_nbr = $msg->rowCount();
?>
<!DOCTYPE html>
<html>
<head>
   <title>Boîte de réception</title>
   <meta charset="utf-8" />
</head>
<body>
   <a href="envoi.php">Nouveau message</a><br /><br /><br />
   <h3>Votre boîte de réception:</h3>
   <?php
   if($msg_nbr == 0) { echo "Vous n'avez aucun message..."; }
   
   while($m = $msg->fetch()) {
      $p_exp = $bdd->prepare('SELECT full_name FROM users WHERE id = ?');
      $p_exp->execute(array($m['id_expediteur']));
      $p_exp = $p_exp->fetch();
      $p_exp = $p_exp['full_name'];
   ?>
   <a href="lecture.php?id=<?= $m['id'] ?>"<?php if($m['lu'] == 1) { ?><span style="color:grey"><?php } ?><b><?= $p_exp ?></b> vous a envoyé un message<br />
      <b>Objet:</b> <?= $m['objet'] ?><?php if($m['lu'] == 1) { ?></span><?php } ?></a><br />
   -------------------------------------<br/>
   <?php } ?>
</body>
</html>

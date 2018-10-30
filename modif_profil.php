<?php

    # Règles SEO
    $page = "Modifier mon profil";
    $seo_description = "Regardez votre profil qui est sublime, magnifique, vous êtes une star !";

    require_once("inc/header.php");


    if($_POST)
    {
        if(empty($msg))
            {

                if(!empty($_POST['id_membre'])) # L'utilisateur est en train de modifier son profil
                {
                    $result = $pdo->prepare("UPDATE membre SET nom=:nom, prenom=:prenom, email=:email, civilite=:civilite, ville=:ville, code_postal=:code_postal, adresse=:adresse WHERE id_membre = :id_membre");

                    $result->bindValue(":id_membre", $_POST['id_membre'], PDO::PARAM_INT);
                    $result->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
                    $result->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
                    $result->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
                    $result->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
                    $result->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
                    $result->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
                    $result->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
                
                    if($result->execute())
                    {
                        $msg .= "<div class='alert alert-success'>Votre profil a bien été mis à jour.</div>";
                    }
                }
                else
                {
                    $msg .= "<div class='alert alert-danger'>Un problème est survenu, veuillez réessayer.</div>";
                }
        
            }

    }


    

    foreach($_SESSION['user'] as $key => $value)
    {
        $info[$key] = htmlspecialchars($value); # nous vérifions que les informations à afficher ne comporte pas d'injections et ne perturberont pas notre service
    }

    // debug($info);


    $id_membre = (isset($info['id_membre'])) ? $info['id_membre'] : "";   
    $pseudo = (isset($info['pseudo'])) ? $info['pseudo'] : "";
    $prenom = (isset($info['prenom'])) ? $info['prenom'] : "";
    $nom = (isset($info['nom'])) ? $info['nom'] : "";
    $email = (isset($info['email'])) ? $info['email'] : "";
    $adresse = (isset($info['adresse'])) ? $info['adresse'] : "";
    $code_postal = (isset($info['code_postal'])) ? $info['code_postal'] : "";
    $ville = (isset($info['ville'])) ? $info['ville'] : "";
    $civilite = (isset($info['civilite'])) ? $info['civilite'] : "";
  

?>

<div class="starter-template">
    <h1><?= $page ?></h1>
        <form method="post" action="">
            <?= $msg ?>
            <input type="hidden" name="id_membre" value="<?=$id_membre?>">
            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?=$prenom?>">
            </div>
            <div class="form-group">
                <label for="nom">Nom: </label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?=$nom?>">
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" class="form-control" id="email" name="email" value="<?=$email?>">
            </div>
            <div class="form-group">
                <label for="civilite">Civilité :</label>
                <select class="form-control" id="civilite" name="civilite">
                    <option value="f" <?php if($civilite == 'f'){echo 'selected';} ?> >Femme</option>
                    <option value="m" <?php if ($civilite == 'm') {echo 'selected';} ?> >Homme</option>
                    <option value="o" <?php if ($civilite == 'o') {echo 'selected';} ?> >Je ne souhaite pas le préciser</option>
                </select>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse :</label>
                <input type="text" class="form-control" id="adresse" name="adresse" value="<?=$adresse?>">
            </div>
            <div class="form-group">
                <label for="code_postal">Code postal :</label>
                <input type="text" class="form-control" id="code_postal" name="code_postal" value="<?=$code_postal?>">
            </div>
            <div class="form-group">
                <label for="ville">Ville :</label>
                <input type="text" class="form-control" id="ville" name="ville" value="<?=$ville?>">
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>


<?php require_once("inc/footer.php"); ?>
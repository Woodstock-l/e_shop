<?php

# Définir mon nom de page
$page = "Gestion des utilisateurs";

require_once("inc/header_back.php");


if($_POST)
{
    if(empty($msg))
        {

            if(!empty($_POST['id_membre'])) # Je suis en train de modifier un membre
            {
                $result = $pdo->prepare("UPDATE membre SET pseudo=:pseudo, nom=:nom, prenom=:prenom, email=:email, civilite=:civilite, ville=:ville, code_postal=:code_postal, adresse=:adresse, statut=:statut WHERE id_membre = :id_membre");

                $result->bindValue(":id_membre", $_POST['id_membre'], PDO::PARAM_INT);
                $result->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
                $result->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
                $result->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
                $result->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
                $result->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
                $result->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
                $result->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
                $result->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
                $result->bindValue(':statut', $_POST['statut'], PDO::PARAM_INT);
            }
        

            if($result->execute()) # Si j'enregistre bien en BDD
            {
                header("location:formulaire_user.php?m=update");
            }

        }

    }

    if($_GET)
    {

       if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))
        {
            $req = "SELECT * FROM membre WHERE id_membre = :id";

            $result = $pdo->prepare($req);
            $result->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
            $result->execute();

            if($result->rowCount() == 1)
            {
                $modif_membre = $result->fetch();

                // debug($modif_membre);
            }
            else 
            {
                $msg .= "<div class='alert alert-danger'>Aucune correspondance en base de données.</div>";
            }
        }
        else 
        {
            $msg .= "<div class='alert alert-danger'>Aucune correspondance en base de données.</div>";
        } 


    }

    $pseudo = (isset($modif_membre)) ? $modif_membre['pseudo'] : "";
    $nom = (isset($modif_membre)) ? $modif_membre['nom'] : "";
    $prenom = (isset($modif_membre)) ? $modif_membre['prenom'] : "";
    $email = (isset($modif_membre)) ? $modif_membre['email'] : "";
    $civilite = (isset($modif_membre)) ? $modif_membre['civilite'] : "";
    $ville = (isset($modif_membre)) ? $modif_membre['ville'] : "";
    $code_postal = (isset($modif_membre)) ? $modif_membre['code_postal'] : "";
    $adresse = (isset($modif_membre)) ? $modif_membre['adresse'] : "";
    $statut = (isset($modif_membre)) ? $modif_membre['statut'] : "";
   
    $id_membre = (isset($modif_membre)) ? $modif_membre['id_membre'] : "";


    
?>

 <div class="starter-template">
        <form action="" method="post">
            <?= $msg ?>
            <input type="hidden" name="id_membre" value="<?=$id_membre?>">
            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input type="text" class="form-control" id="pseudo" value="<?=$pseudo?>"placeholder="Choisissez votre pseudo ..." name="pseudo" required value="<?= $pseudo ?>">
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" class="form-control" id="prenom" value="<?=$prenom?>" placeholder="Quel est votre prénom ..." name="prenom" value="<?= $prenom ?>">
            </div>
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id="nom" value="<?=$nom?>" placeholder="Quel est votre nom ..." name="nom" value="<?= $nom ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" value="<?=$email?>" placeholder="Entrez votre email ..." name="email" value="<?= $email ?>">
            </div>
            <div class="form-group">
                <label for="civilite">Civilité</label>
                <select class="form-control" id="civilite" name="civilite">
                    <option value="f" <?php if($civilite == 'f'){echo 'selected';} ?> >Femme</option>
                    <option value="m" <?php if ($civilite == 'm') {echo 'selected';} ?> >Homme</option>
                    <option value="o" <?php if ($civilite == 'o') {echo 'selected';} ?> >Je ne souhaite pas le préciser</option>
                </select>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" class="form-control" id="adresse" value="<?=$adresse?>" placeholder="Quelle est votre adresse ..." name="adresse" value="<?= $adresse ?>">
            </div>
            <div class="form-group">
                <label for="code_postal">Code postal</label>
                <input type="text" class="form-control" id="code_postal" value="<?=$code_postal?>" placeholder="Quel est votre code postal ..." name="code_postal" value="<?= $code_postal ?>">
            </div>
            <div class="form-group">
                <label for="ville">Ville</label>
                <input type="text" class="form-control" id="ville" value="<?=$ville?>" placeholder="Quelle est votre ville ..." name="ville" value="<?= $ville ?>">
            </div>
            <div class="form-group">
                <label for="statut">Statut</label>
                <select class="form-control" id="statut" name="statut">
                    <option value="0" <?php if($statut == '0'){echo 'selected';} ?> >Utilisateur</option>
                    <option value="1" <?php if ($statut == '1') {echo 'selected';} ?> >Administrateur</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block">Modifier</button>
        </form>
    </div>


    <?php require_once("inc/footer_back.php"); ?>
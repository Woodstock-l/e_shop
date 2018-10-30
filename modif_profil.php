<?php

    # Règles SEO
    $page = "Modifier mon profil";
    $seo_description = "Regardez votre profil qui est sublime, magnifique, vous êtes une star !";

    require_once("inc/header.php");

    // debug($_POST);
    // Eviter d'afficher un message d'erreur si pas de photo + éviter de copier 50 fois dans l'uploads la photo par défaut
    if (empty($msg)) {
        // Envoi du fichier/picture
        if (!empty($_FILES['photo']['name'])) {
            # Première solution pour donner un nom unique à notre fichier (en évitant d'écraser un fichier avec un même nom)
            # Fonction uniqid() retourne un id unique
            # Fonction md5() : hashage de la valeur entrée (sécurité)
            // $picture = uniqid(md5($_FILES['picture']['name']));

            # Donner un nom unique au fichier photo de l'user : pseudo + valeur temps au moment de l'enregistrement + chiffre aléatoire + nom du fichier envoyé
            $avatar = $_POST['id_membre'] . '_' . time() . '-' . rand(1, 999) . '_' . $_FILES['photo']['name'];
        
            # Remplacer les espaces du nom de la photo de l'user par des '_'
            $avatar = str_replace(' ', '_', $avatar);

            # Fonction copy() : Copier/coller la photo sur un fichier uploads pour la sauvegarder -> 2 arguments : endroit où trouver mon fichier + endroit ciblé
            copy($_FILES['photo']['tmp_name'], "../eshop-master/assets/uploads/user/" . $avatar);
        } else {
            # Si l'utilisateur n'envoie pas de avatar, donner une photo par défaut
            $avatar = "../eshop-master/assets/uploads/user/geek.png";
        }
    }

    // debug($_POST);

    // Modification profil
    if($_POST)
    {
        if(empty($msg))
            {

                if(!empty($_POST['id_membre']))
                # L'utilisateur est en train de modifier son profil
                {
                    $result = $pdo->prepare("UPDATE membre SET photo=:photo, nom=:nom, prenom=:prenom, email=:email, civilite=:civilite, ville=:ville, code_postal=:code_postal, adresse=:adresse WHERE id_membre = :id_membre");

                    $result->bindValue(":id_membre", $_POST['id_membre'], PDO::PARAM_INT);
                    $result->bindValue(':photo', $avatar, PDO::PARAM_STR);
                    $result->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
                    $result->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
                    $result->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
                    $result->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
                    $result->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
                    $result->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
                    $result->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
                    # Enregistrement en BDD
                    $result->execute(); 
                }
                else
                {
                    $msg .= "<div class='alert alert-danger'>Un problème est survenu. Veuillez réessayer.</div>";
                }
            }
    }

    foreach($_SESSION['user'] as $key => $value)
    {
        # nous vérifions que les informations à afficher ne comporte pas d'injections et ne perturberont pas notre service
        $info[$key] = htmlspecialchars($value); 
    }

    // debug($info);

    $id_membre = (isset($info['id_membre'])) ? $info['id_membre'] : "";   
    $photo = (isset($info['photo'])) ? $info['photo'] : "";
    $prenom = (isset($info['prenom'])) ? $info['prenom'] : "";
    $nom = (isset($info['nom'])) ? $info['nom'] : "";
    $email = (isset($info['email'])) ? $info['email'] : "";
    $adresse = (isset($info['adresse'])) ? $info['adresse'] : "";
    $code_postal = (isset($info['code_postal'])) ? $info['code_postal'] : "";
    $ville = (isset($info['ville'])) ? $info['ville'] : "";
    $civilite = (isset($info['civilite'])) ? $info['civilite'] : "";

?>

<div class="starter-template">

    <!-- Titre page -->
    <h1><?= $page ?></h1>

        <!-- Formulaire POST-->
        <form method="post" action="" enctype="multipart/form-data">

            <!-- Message erreur -->
            <?= $msg ?>

            <!-- ID membre caché -->
            <input type="hidden" name="id_membre" value="<?=$id_membre?>">

            <!-- Changement photo profil -->
            <div class="form-group">
                <label for="photo">Photo de profil :</label>
                <input type="file" class="form-control-file" id="photo" name="photo" value="<?=$photo?>">
            </div>

            <!-- Prénom -->
            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?=$prenom?>">
            </div>

            <!-- Nom -->
            <div class="form-group">
                <label for="nom">Nom: </label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?=$nom?>">
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" class="form-control" id="email" name="email" value="<?=$email?>">
            </div>

            <!-- Civilité -->
            <div class="form-group">
                <label for="civilite">Civilité :</label>
                <select class="form-control" id="civilite" name="civilite">
                    <option value="f" <?php if($civilite == 'f'){echo 'selected';} ?> >Femme</option>
                    <option value="m" <?php if ($civilite == 'm') {echo 'selected';} ?> >Homme</option>
                    <option value="o" <?php if ($civilite == 'o') {echo 'selected';} ?> >Je ne souhaite pas le préciser</option>
                </select>
            </div>

            <!-- Adresse -->
            <div class="form-group">
                <label for="adresse">Adresse :</label>
                <input type="text" class="form-control" id="adresse" name="adresse" value="<?=$adresse?>">
            </div>

            <!-- Code postal -->
            <div class="form-group">
                <label for="code_postal">Code postal :</label>
                <input type="text" class="form-control" id="code_postal" name="code_postal" value="<?=$code_postal?>">
            </div>

            <!-- Ville -->
            <div class="form-group">
                <label for="ville">Ville :</label>
                <input type="text" class="form-control" id="ville" name="ville" value="<?=$ville?>">
            </div>

            <!-- Bouton submit -->
            <button type="submit" class="btn btn-primary">Enregistrer</button>

        <!-- Fin du formulaire -->
        </form>

<!-- Appel footer -->
<?php require_once("inc/footer.php"); ?>
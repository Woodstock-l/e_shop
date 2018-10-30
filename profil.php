<?php

    # Règles SEO
    $page = "Mon profil";
    $seo_description = "Regardez votre profil qui est sublime, magnifique, vous êtes une star !";

    require_once("inc/header.php");

    if(!userConnect())
    {
        header("location:connexion.php");
        exit(); // die() fonctionne aussi
    }

    // debug($_SESSION, 2);
    foreach($_SESSION['user'] as $key => $value)
    {
        $info[$key] = htmlspecialchars($value); # nous vérifions que les informations à afficher ne comporte pas d'injections et ne perturberont pas notre service
    }

    // debug($info);

    if(isset($_GET['a']) && isset($_GET['id']) && $_GET['a'] == "delete" && is_numeric($_GET['id'])) # la fonction is_numeric() me permet de vérifier que le paramètre rentré est bien un chiffre
    if(isset($_GET['m']) && isset($_GET['id']) && $_GET['m'] == "update" && is_numeric($_GET['id'])) # la fonction is_numeric() me permet de vérifier que le paramètre rentré est bien un chiffre
    {
        $req = "SELECT * FROM membre WHERE id_membre = :id";
        $result = $pdo->prepare($req);
        $result->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $result->execute();
        // debug($result);

        if($result->rowCount() == 1)
        {
            $membre = $result->fetch();
            
            //debug($membre);
            
            $delete_req = "DELETE FROM membre WHERE id_membre = $membre[id_membre]";
            
            $delete_result = $pdo->exec($delete_req);
            
            if($delete_result)
            {
                header("location:profil.php?m=success");
            }
            else
            {
                header("location:profil.php?m=fail");  
            //debug($produit);
            }
            
            $update_req = "UPDATE FROM membre WHERE id_membre = $membre[id_membre]";
            
            $update_result = $pdo->exec($update_req);
            
            if($update_result)
            {
                header("location:modif_profil.php?m=success");
            }
            else
            {
                header("location:modif_profil.php?m=fail");  
            }
            
        }
        else 
        {
            header("location:profil.php?m=fail");    
            header("location:modif_profil.php?m=fail");    
        }
    }
    
    if(isset($_GET['m']) && !empty($_GET['m']))
    {
        switch($_GET['m'])
        {
            case "success":
            $msg .= "<div class='alert alert-success'>Votre compte a bien été supprimé.</div>";
            $msg .= "<div class='alert alert-success'>L'utilisateur a bien été supprimé.</div>";
            break;
            case "fail":
            $msg .= "<div class='alert alert-danger'>Une erreur est survenue, veuillez réessayer.</div>";
            break;
            case "update":
            $msg .= "<div class='alert alert-success'>L'utilisateur a bien été mis à jour.</div>";
            break;
            default:
            $msg .= "<div class='alert alert-warning'>Une erreur est survenue, veuillez réessayer.</div>";
            $msg .= "<div class='alert alert-warning'>A pas compris !</div>";
            break;
        }
    }

    deleteModal($info['id_membre'], $info['pseudo'], 'le membre');
    // debug($_POST);

    // Changement MDP
    if($_POST)
    {
        if(isset($_SESSION['id_membre']) && isset($_POST['mdp']) && isset($_POST['new_mdp']) && isset($_POST['verif_new_mdp']))
		{
			$verif_mdp=password_verify($_POST['mdp'], $_SESSION['id_membre']);
            $req=$pdo->prepare('SELECT id FROM membres WHERE id = :id AND mdp = :mdp');
            $req->bindValue(":id", $_POST['id'], PDO::PARAM_INT);
            $req->bindValue(":mdp", $verif_mdp, PDO::PARAM_STR);
			$req->execute();
			$resultat=$req->fetch();

			if(!$resultat)
			{
				$msg .= 'Ce n\'est pas votre mot de passe';
			}
			elseif (empty($_POST['new_mdp']))
			{
				$msg .= 'Le nouveau mot de passe n\'a pas été renseigné.';
			}
			elseif ($_POST['new_mdp'] != $_POST['verif_new_mdp'])
			{
				$msg .= 'Les mots de passe ne correspondent pas.';
			}
			else
			{
				$new_verif_mdp=password_verify($_POST['new_mdp'], $_SESSION['id_membre']);
                $req=$pdo->prepare('UPDATE membre SET mdp = :mdp WHERE id = :id');
                $req->bindValue(":id", $_POST['id'], PDO::PARAM_INT);
                $req->bindValue(":mdp", $new_verif_mdp, PDO::PARAM_STR);
				$req->execute();
				$msg .= 'Le mot de passe a été changé.';
			}
        }
    }
// debug($info);
?>

    <div class="starter-template">
        <h1><?= $page ?></h1>
        <div class="card">
            <img class="card-img-top img-thumbnail rounded mx-auto d-block" src="<?=URL?>assets/uploads/user/<?= $info['photo'] ?>" alt="Card image cap" style="width:25%;">
            <div class="card-body">
                <h5 class="card-title">Bonjour <?= $info['pseudo'] ?></h5>
                <p class="card-text">Nous sommes ravis de vous revoir sur notre plateforme.</p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Prénom: <?= $info['prenom'] ?></li>
                <li class="list-group-item">Nom: <?= $info['nom'] ?></li>
                <li class="list-group-item">Email: <?= $info['email'] ?></li>

                <li class="list-group-item">Civilité: <?php switch($info['civilite']){case "m": echo "homme"; break; case "f": echo "femme"; break; default: echo "Non défini"; break;} ?></li>
                
                <li class="list-group-item">Adresse: <?= $info['adresse'] ?></li>
                <li class="list-group-item">Code postal: <?= $info['code_postal'] ?></li>
                <li class="list-group-item">Ville: <?= $info['ville'] ?></li>
            </ul>
            <div class="card-body">
                <a href="modif_profil.php?m=update" class="card-link">Modifier</a>
                <a data-toggle='modal' data-target='#deleteModal<?= $info['id_membre'] ?>' class='card-link'> Supprimer</a>
        </div>
    </div>

    <?= $msg ?>

    <?php require_once("inc/footer.php"); ?>
                <a href="modif_profil.php?m=update" class="card-link">Modifier le profil</a>
                <a href="#" class="card-link">Supprimer</a>
            </div>

            <!-- Modification mdp -->
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Modifier le mot de passe</button>
                        </h5>
                    </div>

                <form action="" method="post">
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">

                            <?= $msg ?>

                            <!-- Ancien mdp -->
                            <div class="form-group">
                                <label for="mdp">Ancien mot de passe :</label>
                                <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Taper votre ancien mot de passe">
                            </div>

                            <!-- Nouveau mdp -->
                            <div class="form-group">
                                <label for="new_mdp">Nouveau mot de passe :</label>
                                <input type="password" class="form-control" id="new_mdp" name="new_mdp" placeholder="Taper votre nouveau mot de passe">
                            </div>

                            <!-- Validation nouveau mdp -->
                            <div class="form-group">
                                <label for="verif_new_mdp">Nouveau mot de passe :</label>
                                <input type="password" class="form-control" id="verif_new_mdp" name="verif_new_mdp" placeholder="Taper encore votre nouveau mot de passe">
                            </div>

                            <!-- Bouton validation -->
                            <a href="profil.php?a=update_mdp"><button type="submit" class="btn btn-primary">Valider</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

<?php require_once("inc/footer.php"); ?>
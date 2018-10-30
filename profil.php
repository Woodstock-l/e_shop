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
            }
            
        }
        else 
        {
            header("location:profil.php?m=fail");    
        }
    }
    
    if(isset($_GET['m']) && !empty($_GET['m']))
    {
        switch($_GET['m'])
        {
            case "success":
            $msg .= "<div class='alert alert-success'>Votre compte a bien été supprimé.</div>";
            break;
            case "fail":
            $msg .= "<div class='alert alert-danger'>Une erreur est survenue, veuillez réessayer.</div>";
            break;
            case "update":
            $msg .= "<div class='alert alert-success'>L'utilisateur a bien été mis à jour.</div>";
            break;
            default:
            $msg .= "<div class='alert alert-warning'>Une erreur est survenue, veuillez réessayer.</div>";
            break;
        }
    }

    deleteModal($info['id_membre'], $info['pseudo'], 'le membre');
?>

    <div class="starter-template">
        <h1><?= $page ?></h1>
        <div class="card">
            <img class="card-img-top img-thumbnail rounded mx-auto d-block" src="<?=URL?>/assets/uploads/user/default.png" alt="Card image cap" style="width:25%;">
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
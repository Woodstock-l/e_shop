<?php

# Définir mon nom de page
$page = "Liste des utilisateurs";

require_once("inc/header_back.php");

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
            
            //debug($produit);
            
            $delete_req = "DELETE FROM membre WHERE id_membre = $membre[id_membre]";
            
            $delete_result = $pdo->exec($delete_req);

            // debug($delete_result);
            
            if($delete_result)
            {
                unlink($delete_req); # la fonction unlink() me permet de supprimer un fichier
                header("location:liste_user.php?m=success");
            }
            else
            {
                header("location:liste_user.php?m=fail");  
            }
            
        }
        else 
        {
            header("location:liste_user.php?m=fail");    
        }
    }
    
    if(isset($_GET['m']) && !empty($_GET['m']))
    {
        switch($_GET['m'])
        {
            case "success":
            $msg .= "<div class='alert alert-success'>L'utilisateur a bien été supprimé.</div>";
            break;
            case "fail":
            $msg .= "<div class='alert alert-danger'>Une erreur est survenue, veuillez réessayer.</div>";
            break;
            case "update":
            $msg .= "<div class='alert alert-success'>L'utilisateur a bien été mis à jour.</div>";
            break;
            default:
            $msg .= "<div class='alert alert-warning'>A pas compris !</div>";
            break;
        }
    }

# Je sélectionne tous mes résultats en BDD pour la table membre
$result = $pdo->query('SELECT * FROM membre');
$membres = $result->fetchAll();

$contenu .= "<div class='table-responsive'><table class='table table-striped table-sm'><thead class='thead-dark'><tr>";
    
for($i= 0; $i < $result->columnCount(); $i++)
{
    $colonne = $result->getColumnMeta($i);
<<<<<<< HEAD
    
    if($colonne['name'] =="mdp")
        {
            continue;
        }
        else
        {
            $contenu .= "<th scope='col'>" . ucfirst(str_replace('_', ' ', $colonne['name'])) . "</th>";
        }
}   
=======
    $contenu .= "<th scope='col'>" . ucfirst(str_replace('_', ' ', $colonne['name'])) . "</th>";
}
    
>>>>>>> cd3f3f8594957de55adaae93136fe79eba5eec94
$contenu .= "<th colspan='2'>Actions</th></tr></thead><tbody>";
    
// debug($membres);
    
foreach($membres as $membre)
{
    $contenu .= "<tr>";
        foreach ($membre as $key => $value) 
        {
<<<<<<< HEAD
            if($key =="mdp")
            {
                continue;
            }
            else
            {
            $contenu .= "<td>" . $value . "</td>";
            }    
=======
            $contenu .= "<td>" . $value . "</td>";    
>>>>>>> cd3f3f8594957de55adaae93136fe79eba5eec94
        }
        $contenu .= "<td><a href='formulaire_user.php?id=" . $membre['id_membre'] . "'><i class='fas fa-pen'></i></a></td>";
    
        $contenu .= "<td><a data-toggle='modal' data-target='#deleteModal" . $membre['id_membre'] . "'><i class='fas fa-trash-alt'></i></a></td>";

        # J'appelle ma modal de supression (fonction créée dans fonction.php)
        deleteModal($membre['id_membre'], $membre['pseudo']);

    $contenu .= "</tr>";
}
    
$contenu .= "</tbody></table></div>";
?>

<?= $contenu ?>

<?php require_once("inc/footer_back.php"); ?>
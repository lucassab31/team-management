<?php
    require_once('includes/header.php');
?>
<main>
    <?php
    if (isset($_GET['action'])) {
        if ($_GET['action'] == "liste") {
            ?>
            <section class="joueurs">
                <div class="joueurs__header">
                    <h2 class="section-title text-orange">Liste des joueurs <a href="?action=ajout"><i class="fas fa-plus fa-fw" style="background-color:green;"></i></a></h2>
                    <div class="recherche">
                        <form method="post">
                            <input type="text" name="recherche" placeholder="Rechercher ...">
                            <select name="option">
                                <option value="nom">Nom</option>
                                <option value="numLicence">Num Licence</option>
                                <option value="statut">Statut</option>
                                <option value="poste">Poste</option>
                            </select>
                            <input type="submit" value="Rechercher">
                        </form>
                    </div>
                </div>
                <div class="liste-joueurs">
                    <table>
                        <tr>
                            <th>Num Licence</th>
                            <th>Photo</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Statut</th>
                            <th>Poste</th>
                            <th>Action</th>
                        </tr>
                        <?php
                            $select = $bdd->query("SELECT * FROM joueurs");
                            while ($data = $select->fetch()) {
                                    ?>
                                    <tr>
                                        <td><?= $data['numLicence'] ?></td>
                                        <td><img src="img/joueurs/<?= $data['numLicence']?>.jpeg" alt="photo du joueur"></td>
                                        <td><?= $data['nom'] ?></td>
                                        <td><?= $data['prenom'] ?></td>
                                        <td><?= $data['statut'] ?></td>
                                        <td><?= $data['poste'] ?></td>
                                        <td>
                                            <a href="?action=modification&id=<?= $data['numLicence'] ?>"><i style="background-color:orange;" class="fas fa-pen"></i></a>
                                            <a href="?action=suppression&id=<?= $data['numLicence'] ?>" onclick="Supp(this.href); return(false)"><i style="background-color:red;" class="fas fa-times"></i></a>
                                        </td>
                                    </tr>
                                        <?php
                                    }
                                ?>
                            </table>
                        </div>
                    </section>
            <?php
        }
        if ($_GET['action'] == "ajout") {
            ?>
            <section class="ajout-joueur">
                <h2 class="section-title text-center  text-orange">Ajout d'un joueur</h2>
                <form method="post" enctype="multipart/form-data">
                    <input type="number" name="numLicence" placeholder="N° licence" required>
                    <input type="text" name="nom" placeholder="Nom" required>
                    <input type="text" name="prenom" placeholder="Prénom" required>
                    <label for="date">Date de naissance</label>
                    <input type="date" name="dateN" required>
                    <input type="number" name="taille" placeholder="Taille" required>
                    <input type="number" name="poids" placeholder="Poids" required>
                    <textarea name="commentaire" placeholder="Commentaire" rows="30"></textarea>
                    <label for="statut">Statut</label>
                    <select name="statut">
                        <option value="actif">Actif</option>
                        <option value="blesse">Blessé</option>
                        <option value="suspendu">Suspendu</option>
                        <option value="absent">Absent</option>
                    </select><br/>
                    <label for="poste">Poste</label>
                    <select name="poste">
                        <option value="gardien">Gardien</option>
                        <option value="ailierG">Ailier gauche</option>
                        <option value="ailierD">Ailier droit</option>
                        <option value="arriereG">Arrière gauche</option>
                        <option value="arriereD">Arrière droit</option>
                        <option value="demiCentre">Demi centre</option>
                        <option value="pivot">Pivot</option>
                    </select>
                    <input type="file" name="photo">
                    <input type="submit" name="submitA" value="Ajouter">
                </form>
            </section>
            <?php

            if (isset($_POST['submitA'])){
                $insert = $bdd->prepare('INSERT INTO joueurs(numLicence,nom,prenom,commentaire,dateN,taille,poids,statut,poste)VALUES(?,?,?,?,?,?,?,?,?)');
                $insert->execute(array($_POST['numLicence'],$_POST['nom'],$_POST['prenom'],$_POST['commentaire'],$_POST['dateN'],$_POST['taille'],$_POST['poids'],$_POST['statut'],$_POST['poste']));
            }
        }

        if ($_GET['action'] == "modification") {
            ?>
            <section class="modification-joueur">
                <h2 class="section-title  text-center text-orange">Modification de XXX</h2>
                    <form method="post" enctype="multipart/form-data">
                        <input type="number" name="numLicence" placeholder="N° licence" required>
                        <input type="text" name="nom" placeholder="Nom" required>
                        <input type="text" name="prenom" placeholder="Prénom" required>
                        <label for="date">Date de naissance</label>
                        <input type="date" name="dateN" required>
                        <input type="number" name="taille" placeholder="Taille" required>
                        <input type="number" name="poids" placeholder="Poids" required>
                        <textarea name="commentaire" placeholder="Commentaire" rows="30"></textarea>
                        <label for="statut">Statut</label>
                        <select name="statut">
                            <option value="actif">Actif</option>
                            <option value="blesse">Blessé</option>
                            <option value="suspendu">Suspendu</option>
                            <option value="absent">Absent</option>
                        </select><br/>
                        <label for="poste">Poste</label>
                        <select name="poste">
                            <option value="gardien">Gardien</option>
                            <option value="ailierG">Ailier gauche</option>
                            <option value="ailierD">Ailier droit</option>
                            <option value="arriereG">Arrière gauche</option>
                            <option value="arriereD">Arrière droit</option>
                            <option value="demiCentre">Demi centre</option>
                            <option value="pivot">Pivot</option>
                        </select>
                        <input type="file" name="photo">
                        <input type="submit" name="submitM" value="Modifier">
                    </form>
            </section>
            <?php
            if (isset($_POST['submitModC'])) {
                $id = $_GET['numLicence'];
                $req = $bdd->prepare("UPDATE joueurs SET nom=?,prenom=?,dateN=?,taille=?,poids=?,commentaire=?,statut=?,poste=? WHERE numLicence=$id");
                $req->execute(array($_POST['nom'], $_POST['prenom'], $_POST['adresse'], $_POST['cp'], $_POST['ville'], $_POST['tel']));
                header('Location: ?');
    }
        }

        if ($_GET['action'] == "detail") {
            ?> 
            <section class="detail-joueur">
                <h2 class="section-title">Nom prénom joueur</h2>
                 <img src="" alt="photo du joueur">
            </section>
              <?php
        }

        if ($_GET['action'] == "suppression") {

        } 
    }
?>

    <?php
        
    ?>    
      
</main>
<script>
    function Supp(link){
        if(confirm('Confirmer la suppression ?')){
        document.location.href = link;
        }
   };
</script>
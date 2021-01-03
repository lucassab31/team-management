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
                            <input type="submit" name="submitR" value="Rechercher">
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
                        if (isset($_POST['submitR']) && !empty($_POST['recherche'])) {
                            switch ($_POST['option']){
                                case 'nom':
                                    $select = $bdd->prepare("SELECT * FROM joueurs WHERE nom=?");
                                    break;
                                case 'numLicence':
                                    $select = $bdd->prepare("SELECT * FROM joueurs WHERE numLicence=?");
                                    break;
                                case 'statut':
                                    $select = $bdd->prepare("SELECT * FROM joueurs WHERE statut=?");
                                    break;
                                case 'poste':
                                    $select = $bdd->prepare("SELECT * FROM joueurs WHERE poste=?");
                                    break;
                            }
                            $select->execute(array($_POST['recherche']));
                        }else {
                            $select = $bdd->query("SELECT * FROM joueurs ORDER BY nom");
                        }   
                        while ($data = $select->fetch()) {
                            ?>
                            <tr>
                                <td><?= $data['numLicence'] ?></td>
                                <td><img src="img/<?= sha1($data['numLicence']) ?>.jpg" alt="photo du joueur" height="50"></td>
                                <td><?= $data['nom'] ?></td>
                                <td><?= $data['prenom'] ?></td>
                                <td><?= $data['statut'] ?></td>
                                <td><?= $data['poste'] ?></td>
                                <td>
                                    <a href="?action=detail&id=<?= $data['numLicence'] ?>"><i style="background-color:blue;" class="fas fa-search"></i></a>
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
                        <option value="Actif">Actif</option>
                        <option value="Blessé">Blessé</option>
                        <option value="Suspendu">Suspendu</option>
                        <option value="Absent">Absent</option>
                    </select><br/>
                    <label for="poste">Poste</label>
                    <select name="poste">
                        <option value="Gardien">Gardien</option>
                        <option value="Ailier gauche">Ailier gauche</option>
                        <option value="Ailier droit">Ailier droit</option>
                        <option value="Arrière gauche">Arrière gauche</option>
                        <option value="Arrière droit">Arrière droit</option>
                        <option value="Demi centre">Demi centre</option>
                        <option value="Pivot">Pivot</option>
                    </select>
                    <input type="file" name="photo">
                    <input type="submit" name="submitA" value="Ajouter">
                </form>
                <a href="?action=liste"><i style="background-color:grey;" class="fas fa-arrow-left"></i></a>
            </section>
            <?php

            if (isset($_POST['submitA'])){
                $insert = $bdd->prepare('INSERT INTO joueurs(numLicence,nom,prenom,commentaire,dateN,taille,poids,statut,poste)VALUES(?,?,?,?,?,?,?,?,?)');
                $insert->execute(array($_POST['numLicence'],$_POST['nom'],$_POST['prenom'],$_POST['commentaire'],$_POST['dateN'],$_POST['taille'],$_POST['poids'],$_POST['statut'],$_POST['poste']));
                $img = $_FILES['photo']['name'];
                $img_tmp = $_FILES['photo']['tmp_name'];

                $image = explode('.', $img);
                $image_ext = end($image);

                if (in_array(strtolower($image_ext), array('png','jpg','jpeg')) === false)
                {
                    echo "Veuillez rentrer une image ayant pour extension : png, jpg, jpg";
                }
                else
                {
                    $image_size = getimagesize($img_tmp);
                    if ($image_size['mime'] == 'image/jpeg')
                    {
                        $image_src = imagecreatefromjpeg($img_tmp);
                    }
                    elseif ($image_size['mime'] == 'image/png')
                    {
                        $image_src = imagecreatefrompng($img_tmp);
                    }
                    else
                    {
                        $image_src = false;
                        echo "Veuillez entrer une image valide";
                    }

                    if ($image_src !== false)
                    {
                        imagejpeg($image_src,'./img/'.sha1($_POST['numLicence']).'.jpg');
                    }
                }
                header('Location: ?action=liste');
            }
        }

        if ($_GET['action'] == "modification") {
            $id = $_GET['id'];
            $select = $bdd->prepare("SELECT * FROM joueurs WHERE numLicence=$id");
            $select->execute();
            $data = $select->fetch();
            ?>
            
            <section class="modification-joueur">
                <h2 class="section-title  text-center text-orange">Modification de <?=$data['nom'] . " " . $data['prenom']?> </h2>
                    <form method="post" enctype="multipart/form-data">
                        <input type="text" name="nom" value="<?= $data['nom'] ?>"placeholder="Nom" required>
                        <input type="text" name="prenom" value="<?= $data['prenom'] ?>"placeholder="Prénom" required>
                        <label for="date">Date de naissance</label>
                        <input type="date" name="dateN" value="<?= $data['dateN'] ?>" required>
                        <input type="number" name="taille" value="<?= $data['taille'] ?>"placeholder="Taille" required>
                        <input type="number" name="poids" value="<?= $data['poids'] ?>"placeholder="Poids" required>
                        <textarea name="commentaire" value="<?= $data['commentaire'] ?>"placeholder="Commentaire" rows="30"></textarea>
                        <label for="statut">Statut</label>
                        <select name="statut" value="<?= $data['statut'] ?>">
                            <option value="<?= $data['statut'] ?>" selected><?= $data['statut'] ?></option>
                            <option value="Actif">Actif</option>
                            <option value="Blessé">Blessé</option>
                            <option value="Suspendu">Suspendu</option>
                            <option value="Absent">Absent</option>
                        </select><br/>
                        <label for="poste">Poste</label>
                        <select name="poste">
                            <option value="<?= $data['poste'] ?>" selected><?= $data['poste'] ?></option>
                            <option value="Gardien">Gardien</option>
                            <option value="Ailier gauche">Ailier gauche</option>
                            <option value="Ailier droit">Ailier droit</option>
                            <option value="Arrière gauche">Arrière gauche</option>
                            <option value="Arrière droit">Arrière droit</option>
                            <option value="Demi centre">Demi centre</option>
                            <option value="Pivot">Pivot</option>
                        </select>
                        <input type="file" name="photo">
                        <input type="submit" name="submitM" value="Modifier">
                    </form>
                    <a href="?action=liste"><i style="background-color:grey;" class="fas fa-arrow-left"></i></a>
            </section>

            <?php
            if (isset($_POST['submitM'])) {
                $req = $bdd->prepare("UPDATE joueurs SET nom=?,prenom=?,dateN=?,taille=?,poids=?,commentaire=?,statut=?,poste=? WHERE numLicence=$id");
                $req->execute(array($_POST['nom'], $_POST['prenom'], $_POST['dateN'], $_POST['taille'], $_POST['poids'], $_POST['commentaire'], $_POST['statut'],$_POST['poste']));
                if(!empty($_FILES['photo']['name'])){
                    unlink('./img/'.sha1($id).'.jpg');
                    $img = $_FILES['photo']['name'];
                    $img_tmp = $_FILES['photo']['tmp_name'];

                    $image = explode('.', $img);
                    $image_ext = end($image);

                    if (in_array(strtolower($image_ext), array('png','jpg','jpeg')) === false)
                    {
                        echo "Veuillez rentrer une image ayant pour extension : png, jpg, jpg";
                    }
                    else
                    {
                        $image_size = getimagesize($img_tmp);
                        if ($image_size['mime'] == 'image/jpeg')
                        {
                            $image_src = imagecreatefromjpeg($img_tmp);
                        }
                        elseif ($image_size['mime'] == 'image/png')
                        {
                            $image_src = imagecreatefrompng($img_tmp);
                        }
                        else
                        {
                            $image_src = false;
                            echo "Veuillez entrer une image valide";
                        }

                        if ($image_src !== false)
                        {
                            imagejpeg($image_src,'./img/'.sha1($id).'.jpg');
                        }
                    }
                }
                header('Location: ?action=liste');
            }
        }

        if ($_GET['action'] == "detail") {
            $id = $_GET['id'];
            $select = $bdd->prepare("SELECT * FROM joueurs WHERE numLicence=$id");
            $select->execute();
            $data = $select->fetch();
            ?>
            <section class="detail-joueur">
                <h2 class="section-title text-orange text-center">Joueur <?= $data['nom'] . " " . $data['prenom'] ?></h2>
                <div class="carte-joueur">
                    <div class="carte-joueur__image">
                        <img src="img/<?= sha1($id)?>.jpg" alt="photo du joueur" width="30%"> 
                    </div>
                    <div class="carte-joueur__information">
                        N° de Licence : <?= $data['numLicence']?></br>
                        Date de Naissance : <?= $data['dateN']?></br>
                        Taille : <?= $data['taille']?>cm
                        Poids : <?= $data['poids']?>kg</br>
                        Poste : <?= $data['poste']?></br>
                        Etat : <?= $data['statut']?></br>
                        Commentaire : <?= $data['commentaire']?>
                    </div>
                </div>

                <a href="?action=liste"><i style="background-color:grey;" class="fas fa-arrow-left"></i></a>
            </section>
            <?php
        }

        if ($_GET['action'] == "suppression") {
            $delete = $bdd->prepare("DELETE FROM joueurs WHERE numLicence=? ");
            $delete->execute(array($_GET['id']));
            
            $delete = $bdd->prepare("DELETE FROM jouer WHERE numLicence=? ");
            $delete->execute(array($_GET['id']));
            header('Location: ?action=liste');
        } 
}
?>

   
</main>
<script>
    function Supp(link){
        if(confirm('Confirmer la suppression ?')){
        document.location.href = link;
        }
   };
</script>
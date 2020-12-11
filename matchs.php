<?php
    require_once('includes/header.php');
?>
<main>
<?php

    if (isset($_GET['action'])) {
        if ($_GET['action'] == "liste") {
            ?>
            <section class="matchs">
                <div class="matchs__header">
                    <h2 class="section-title text-orange">Liste des matchs <a href="?action=ajout"><i class="fas fa-plus fa-fw" style="background-color:green;"></i></a></h2>
                    <div class="recherche">
                        <form method="post">
                            <input type="date" name="recherche">
                            <input type="submit" value="Rechercher">
                        </form>
                    </div>
                </div>
                <div class="liste-matchs">
                    <table>
                        <tr>
                            <th>Date</th>
                            <th>L'heure</th>
                            <th>opposant</th>
                            <th>Lieu</th>
                            <th>Score</th>
                            <th>Action</th>
                        </tr>
                        <?php
                            $select = $bdd->query("SELECT * FROM matchs");
                            while ($data = $select->fetch()) {
                                ?>
                                <tr>
                                    <td><?= $data['dateM'] ?></td>
                                    <td><?= $data['heureM'] ?></td>
                                    <td><?= $data['opposant'] ?></td>
                                    <td><?= $data['lieu'] ?></td>
                                    <td><?= isset($data['scU']) ? $data['scU'] . "-" . $data['scO'] : "NA" ?></td>
                                    <td>
                                        <a href="?action=detail&id=<?= $data['idMatch'] ?>"><i style="background-color:blue;" class="fas fa-search"></i></a>
                                        <a href="?action=ajout_joueurs&id=<?= $data['idMatch'] ?>"><i style="background-color:green;" class="fas fa-user-plus"></i></a>
                                        <a href="?action=modification&id=<?= $data['idMatch'] ?>"><i style="background-color:orange;" class="fas fa-pen"></i></a>
                                        <a href="?action=suppression&id=<?= $data['idMatch'] ?>" onclick="Supp(this.href); return(false)"><i style="background-color:red;" class="fas fa-times"></i></a>
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
            <section class="ajout-match">
                <h2 class="section-title text-orange text-center">Ajout d'un match</h2>
                <form method="post">
                    <label for="dateM">Date du match</label>
                    <input type="date" name="dateM" required>
                    <label for="heureM">Heure du match</label>
                    <input type="time" name="heureM" required>
                    <input type="text" name="opposant" placeholder="Opposant" required>
                    <label for="lieu">Lieu</label>
                    <select name="lieu">
                        <option value="domicile">Domicile</option>
                        <option value="exterieur">Extérieur</option>
                    </select>
                    <input type="submit" name="submitA" value="Ajouter">
                </form>
                <a href="?action=liste"><i style="background-color:grey;" class="fas fa-arrow-left"></i></a>
            </section>
            <?php

            if (isset($_POST['submitA'])) {
                $insert = $bdd->prepare("INSERT INTO matchs(dateM, heureM, opposant, lieu) VALUES(?, ?, ?, ?)");
                $insert->execute(array($_POST['dateM'], $_POST['heureM'], $_POST['opposant'], $_POST['lieu']));
                header('Location: ?action=liste');
            }
        }

        
        if ($_GET['action'] == "ajout_joueurs") {
            ?>
            <section class="ajout_joueurs-match">
                <h2 class="section-title text-orange">Ajout des joueurs participant au match</h2>
                <a href="?action=liste"><i style="background-color:grey;" class="fas fa-arrow-left"></i></a>
                <div class="liste-matchs">
                    <?php
                        $selectP = $bdd->prepare("SELECT * FROM jouer WHERE idMacth=?");
                        $selectP->execute(array($_GET['id']));
                    ?>
                    <table>
                        <tr>
                            <th>Num Licence</th>
                            <th>Photo</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Poste</th>
                            <th>Action</th>
                        </tr>
                        <?php
                            $selectJ = $bdd->prepare("SELECT * FROM joueurs WHERE statut=?");
                            $selectJ->execute(array("actif"));

                            while ($data = $select->fetch()) {
                                ?>
                                <tr>
                                    <td><?= $data['numLicence'] ?></td>
                                    <td><img src="img/joueurs/<?= $data['numLicence'] ?>.jpeg" alt="photo du joueur"></td>
                                    <td><?= $data['nom'] ?></td>
                                    <td><?= $data['prenom'] ?></td>
                                    <td><?= $data['poste'] ?></td>
                                    <td>
                                        <a href="?action=ajout_joueurs&id=<?= $data['numLicence'] ?>"><i style="background-color:green;" class="fas fa-user-plus"></i></a>
                                        <a href="?action=modification&id=<?= $data['numLicence'] ?>"><i style="background-color:orange;" class="fas fa-pen"></i></a>
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

        if ($_GET['action'] == "modification") {
            $id = $_GET['id'];
            $select = $bdd->prepare("SELECT * FROM matchs WHERE idMatch=$id");
            $select->execute();
            $data = $select->fetch();
            ?>
            <section class="modification-match">
                <h2 class="section-title text-orange text-center">Modification du match du <?= $data['dateM'] ?> à <?= $data['heureM'] ?></h2>
                <form method="post">
                    <label for="date">Date du match</label>
                    <input type="date" name="dateM" value="<?= $data['dateM'] ?>" required>
                    <label for="date">Heure du match</label>
                    <input type="time" name="heureM" value="<?= $data['heureM'] ?>" required>
                    <input type="text" name="opposant" placeholder="Opposant" value="<?= $data['opposant'] ?>" required>
                    <label for="lieu">Lieu</label>
                    <select name="lieu">
                        <option value="<?= $data['lieu'] ?>"><?= $data['lieu'] ?></option>
                        <option value="Domicile">Domicile</option>
                        <option value="Extérieur">Extérieur</option>
                    </select>
                    <input type="texte" name="score" placeholder="Score : x-x"  value="<?= isset($data['scU']) ? $data['scU'] . "-" . $data['scO'] : "" ?>">
                    <input type="submit" name="submitM" value="Modifier">
                </form>
                <a href="?action=liste"><i style="background-color:grey;" class="fas fa-arrow-left"></i></a>
            </section>
            <?php
            if (isset($_POST['submitM'])) {
                if (!empty($_POST['score'])) {
                    $score = explode("-", $_POST['score']);
                } else {
                    $score = array(null, null);
                }
                $update = $bdd->prepare("UPDATE matchs SET dateM=?, heureM=?, opposant=?, scO=?, scU=? WHERE idMatch=$id");
                $update->execute(array($_POST['dateM'], $_POST['heureM'], $_POST['opposant'], $score[1], $score[0]));
                header('Location: ?action=liste');
            }
        }

        if ($_GET['action'] == "detail") {
            $id = $_GET['id'];
            $select = $bdd->prepare("SELECT * FROM matchs WHERE idMatch=$id");
            $select->execute();
            $data = $select->fetch();
            ?>
            <section class="detail-match">
                <h2 class="section-title text-orange">Match du <?= $data['dateM'] ?> à <?= $data['heureM'] ?></h2>
                <a href="?action=liste"><i style="background-color:grey;" class="fas fa-arrow-left"></i></a>
                <div class="liste-matchs">
                    <table>
                        <tr>
                            <th>Date</th>
                            <th>L'heure</th>
                            <th>opposant</th>
                            <th>Lieu</th>
                            <th>Score</th>
                        </tr>
                        <tr>
                            <td><?= $data['dateM'] ?></td>
                            <td><?= $data['heureM'] ?></td>
                            <td><?= $data['opposant'] ?></td>
                            <td><?= $data['lieu'] ?></td>
                            <td><?= isset($data['scU']) ? $data['scU'] . "-" . $data['scO'] : "NA" ?></td>
                        </tr>
                    </table>
                    <br/>
                    <h2 class="section-title text-orange">Joueurs participant :</h2>
                    <br/>
                    <table>
                        <tr>
                            <th>Num Licence</th>
                            <th>Photo</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Poste</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                        <?php
                            $selectP = $bdd->prepare("SELECT idJoueur, statutM FROM jouer WHERE idMacth=?");
                            $selectP->execute(array($_GET['id']));

                            while ($participant = $selectP->fetch()) {
                                $selectJ = $bdd->prepare("SELECT * FROM joueurs WHERE idJoueur=?");
                                $selectJ->execute(array($participant['idJoueur']));
                                $data = $selectJ->fetch();
                                ?>
                                <tr>
                                    <td><?= $data['numLicence'] ?></td>
                                    <td><img src="img/joueurs/<?= $data['numLicence'] ?>.jpeg" alt="photo du joueur"></td>
                                    <td><?= $data['nom'] ?></td>
                                    <td><?= $data['prenom'] ?></td>
                                    <td><?= $data['poste'] ?></td>
                                    <td><?= $participant['statutM'] ?></td>
                                    <td>
                                        <a href="?action=suppressionParticipant&id=<?= $participant['idJoueur'] ?>" onclick="Supp(this.href); return(false)"><i style="background-color:red;" class="fas fa-times"></i></a>
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

        if ($_GET['action'] == "suppression") {

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
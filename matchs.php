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
            $idMatch = $_GET['id'];
            ?>
            <section class="ajout_joueurs-match">
                <h2 class="section-title text-orange">Ajout des joueurs participant au match</h2>
                <a href="?action=liste"><i style="background-color:grey;" class="fas fa-arrow-left"></i></a>
                <i style="background-color:purple;" class="fas fa-volleyball-ball"> Titulaire</i> <i style="background-color:pink;" class="fas fa-volleyball-ball"> Remplaçant</i>
                <div class="liste-matchs">
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
                            $selectJ->execute(array("Actif"));

                            while ($data = $selectJ->fetch()) {
                                $selectP = $bdd->prepare("SELECT * FROM jouer WHERE idMatch=? AND numLicence=?");
                                $selectP->execute(array($idMatch, $data['numLicence']));
                                $dataP = $selectP->fetch();
                                ?>
                                <tr>
                                    <td><?= $data['numLicence'] ?></td>
                                    <td><img src="img/<?= sha1($data['numLicence']) ?>.jpg" alt="photo du joueur"></td>
                                    <td><?= $data['nom'] ?></td>
                                    <td><?= $data['prenom'] ?></td>
                                    <td><?= $data['poste'] ?></td>
                                    <?php
                                        if (isset($dataP['statutM'])) {
                                            if ($dataP['statutM'] == "Titulaire") {
                                                ?>
                                                <td><i style="background-color:purple;" class="fas fa-volleyball-ball"></i></td>
                                                <?php

                                            } else if ($dataP['statutM'] == "Remplaçant") {
                                                ?>
                                                <td><i style="background-color:pink;" class="fas fa-volleyball-ball"></i></td>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <td>
                                                <a href="?action=ajout_joueurs&id=<?= $idMatch ?>&statutM=Titulaire&numLicence=<?= $data['numLicence'] ?>"><i style="background-color:purple;" class="fas fa-volleyball-ball"></i></a>
                                                <a href="?action=ajout_joueurs&id=<?= $idMatch ?>&statutM=Remplaçant&numLicence=<?= $data['numLicence'] ?>"><i style="background-color:pink;" class="fas fa-volleyball-ball"></i></a>
                                            </td>
                                            <?php
                                        }
                                    ?>
                                </tr>
                                <?php
                            }
                        ?>
                    </table>
                </div>
            </section>
            <?php
            if (isset($_GET['statutM'])) {
                $idMatch = $_GET['id'];
                $insert = $bdd->prepare("INSERT INTO jouer(numLicence, idMatch, statutM) VALUES(?,?,?)");
                $insert->execute(array($_GET['numLicence'], $idMatch, $_GET['statutM']));
                header('Location: ?action=ajout_joueurs&id='.$idMatch);
            }
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
                $update = $bdd->prepare("UPDATE matchs SET dateM=?, heureM=?, opposant=?, lieu=? scO=?, scU=? WHERE idMatch=$id");
                $update->execute(array($_POST['dateM'], $_POST['heureM'], $_POST['opposant'], $_POST['lieu'], $score[1], $score[0]));
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
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                        <?php
                            $selectP = $bdd->prepare("SELECT * FROM jouer WHERE idMatch=?");
                            $selectP->execute(array($_GET['id']));
                            while ($participant = $selectP->fetch()) {
                                $selectJ = $bdd->prepare("SELECT * FROM joueurs WHERE numLicence=?");
                                $selectJ->execute(array($participant['numLicence']));
                                $data = $selectJ->fetch();
                                ?>
                                <tr>
                                    <td><?= $data['numLicence'] ?></td>
                                    <td><img src="img/<?= sha1($data['numLicence']) ?>.jpg" alt="photo du joueur"></td>
                                    <td><?= $data['nom'] ?></td>
                                    <td><?= $data['prenom'] ?></td>
                                    <td><?= $data['poste'] ?></td>
                                    <td><?= $participant['statutM'] ?></td>
                                    <td><form name="<?= $data['numLicence'] ?>" method="post"><input name="numLicence" value="<?= $data['numLicence'] ?>" hidden><input onChange="Change(this.form, this.value)" type="range" value="<?= !empty($participant['note']) ? $participant['note'] : 0 ?>" name="note" min="0" max="5"></form></td>
                                    <td>
                                        <a href="?action=detail&id=<?= $_GET['id'] ?>&modify=suppr&numLicence=<?= $data['numLicence'] ?>" onclick="Supp(this.href); return(false)"><i style="background-color:red;" class="fas fa-times"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                    </table>
                </div>
            </section>
            <?php
            if (isset($_GET['modify'])) {
                if ($_GET['modify'] == "suppr") {
                    $delete = $bdd->prepare("DELETE FROM jouer WHERE numLicence=? AND idMatch=? ");
                    $delete->execute(array($_GET['numLicence'], $_GET['id']));
                    header('Location: ?action=detail&id='.$_GET['id']);
                }
            }

            if (isset($_POST['note'])) {
                $update = $bdd->prepare("UPDATE jouer SET note=? WHERE numLicence=? AND idMatch=?");
                $update->execute(array($_POST['note'], $_POST['numLicence'], $_GET['id']));
            }
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

    function Change(form, value) {
        form.submit();
    };
</script>
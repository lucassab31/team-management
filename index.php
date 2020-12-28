<?php
    require_once('includes/header.php');
?>
<main>
    <section class="stats">
        <div class="stats__matchs">
            <h2 class="section-title text-orange">Résultats</h2>
            camembert
            <div class="stats__matchs__nombres">
                <h3 class="text-orange">Matchs :</h3>
                <?php
                        $select = $bdd->query("SELECT * FROM matchs WHERE scO IS NOT NULL");
                        $win = 0; $lose = 0; $draw = 0;
                        while ($data = $select->fetch()) {
                            if ($data['scU'] > $data['scO']) {
                                $win++;
                            } else if ($data['scU'] < $data['scO']) {
                                $lose++;
                            } else {
                                $draw++;
                            }
                        }
                ?>
                <div class="stats__matchs__nombres__nombre">
                    <p><strong>Victoires : </strong><?= $win ?></p>
                </div>
                <div class="stats__matchs__nombres__nombre">
                    <p><strong>Défaites : </strong><?= $lose ?></p>
                </div>
                <div class="stats__matchs__nombres__nombre">
                    <p><strong>Matchs nuls : </strong><?= $draw ?></p>
                </div>
            </div>
        </div>
        <div class="stats_joueurs">
            <h2 class="section-title text-orange">Statistiques des joueurs</h2>
            <table>
                <tr>
                    <th>Photo</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Statut</th>
                    <th>Poste</th>
                    <th>Nb titulaire</th>
                    <th>Nb remplaçant</th>
                    <th>Moy évaluation</th>
                    <th>Victoire</th>
                    <th>Fiche</th>
                </tr>
                <?php
                    // select unique de tout les joueurs ayant jouer un match
                    // parcourire ces joueurs :
                        // select info joueur from joueurs
                            // photo
                            // nom
                            // prenom
                            // statut
                            // poste
                        // select info from jouer

                        // SELECT
                        //     (
                        //         SELECT COUNT(`statutM`) 
                        //         FROM jouer 
                        //         WHERE `numLicence` = 323456789 
                        //         AND `statutM` = "Titulaire"
                        //     ) as count1,
                        //     (
                        //         SELECT COUNT(`statutM`) 
                        //         FROM jouer 
                        //         WHERE `numLicence` = 323456789 
                        //         AND `statutM` = "RemplaÃ§ant"
                        //     ) as count2,
                        //     (
                        //         SELECT AVG(`note`) 
                        //         FROM jouer 
                        //         WHERE `numLicence` = 323456789
                        //     ) as moy,
                        //     (
                        //         SELECT COUNT(*)
                        //         FROM matchs, jouer
                        //         WHERE jouer.numLicence = 323456789
                        //         AND jouer.idMatch = matchs.idMatch
                        //         AND matchs.scU > matchs.scO
                        //     ) as nbVic;

                            // SELECT
                            // (SELECT COUNT(`statutM`) FROM jouer WHERE `numLicence` = 323456789 AND `statutM` = "Titulaire") as nbTitu,
                            // (SELECT COUNT(`statutM`) FROM jouer WHERE `numLicence` = 323456789 AND `statutM` = "RemplaÃ§ant") as nbRemp,
                            // (SELECT AVG(`note`) FROM jouer WHERE `numLicence` = 323456789) as moyNote;
                            // 
                                // nb titulaire
                                    // SELECT COUNT(`statutM`) AS nbTitu FROM `jouer` WHERE `numLicence` = numLicence AND `statutM` = "Titulaire"
                                // nb remp
                                    // SELECT COUNT(`statutM`) AS nbTitu FROM `jouer` WHERE `numLicence` = numLicence AND `statutM` = "Remplaçant"
                                // moy note
                                    // SELECT AVG(`note`) FROM jouer WHERE `numLicence` = 323456789
                        // select info from matchs
                            // nb victoire
                                // SELECT COUNT(*) as nbVic
                                // FROM matchs, jouer
                                // WHERE jouer.numLicence = 323456789
                                // AND jouer.idMatch = matchs.idMatch
                                // AND matchs.scU > matchs.scO;
                    $selectM = $bdd->query("SELECT DISTINCT numLicence FROM jouer");
                    while ($joueurs = $selectM->fetch()) {
                        $selectJ = $bdd->prepare("SELECT * FROM joueurs WHERE numLicence=?");
                        $selectJ->execute(array($joueurs['numLicence']));
                        $joueur = $selectJ->fetch();
                        $selectS = $bdd->prepare('SELECT
                                                    (
                                                        SELECT COUNT(statutM) 
                                                        FROM jouer 
                                                        WHERE numLicence = :numLicence 
                                                        AND statutM = "Titulaire"
                                                    ) as nbTitu,
                                                    (
                                                        SELECT COUNT(`statutM`) 
                                                        FROM jouer 
                                                        WHERE numLicence = :numLicence 
                                                        AND statutM = "Remplaçant"
                                                    ) as nbRemp,
                                                    (
                                                        SELECT AVG(note) 
                                                        FROM jouer 
                                                        WHERE numLicence = :numLicence
                                                    ) as moyNote,
                                                    (
                                                        SELECT COUNT(*)
                                                        FROM matchs, jouer
                                                        WHERE jouer.numLicence = :numLicence
                                                        AND jouer.idMatch = matchs.idMatch
                                                        AND matchs.scU > matchs.scO
                                                    ) as nbVic
                        ');
                        $selectS->execute(array(':numLicence' => $joueur['numLicence']));
                        $stat = $selectS->fetch();
                ?>
                        <tr>
                            <td><img src="img/<?= sha1($joueur['numLicence']) ?>.jpg" alt="photo du joueur" height="50"></td>
                            <td><?= $joueur['nom'] ?></td>
                            <td><?= $joueur['prenom'] ?></td>
                            <td><?= $joueur['statut'] ?></td>
                            <td><?= $joueur['poste'] ?></td>
                            <td><?= $stat['nbTitu'] ?></td>
                            <td><?= $stat['nbRemp'] ?></td>
                            <td><?= $stat['moyNote'] ?></td>
                            <td><?= $stat['nbVic'] ?></td>
                            <td><a href="players.php?action=detail&id=<?= $joueur['numLicence'] ?>"><i style="background-color:blue;" class="fas fa-search"></i></a></td>
                        </tr>
                <?php
                    }
                ?>
            </table>
        </div>
    </section>
</main>

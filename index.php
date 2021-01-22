<?php
    require_once('includes/header.php');
?>
<main>
    <section class="stats">
        <div class="stats__matchs">
            <?php // comptage des victoires, défaites et matchs null
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
            <h2 class="section-title text-orange">Résultats</h2>
            <canvas id="myChart"></canvas>
            <br/><br/>
            <div class="stats__matchs__nombres">
                <h3 class="text-orange">Matchs :</h3>
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
                    $selectM = $bdd->query("SELECT DISTINCT numLicence FROM jouer");
                    while ($joueurs = $selectM->fetch()) {
                        $selectJ = $bdd->prepare("SELECT * FROM joueurs WHERE numLicence=?");
                        $selectJ->execute(array($joueurs['numLicence']));
                        $joueur = $selectJ->fetch();
                        $selectS = $bdd->prepare('SELECT
                                                    ( -- nombre de fois titulaire
                                                        SELECT COUNT(statutM) 
                                                        FROM jouer 
                                                        WHERE numLicence = :numLicence 
                                                        AND statutM = "Titulaire"
                                                    ) as nbTitu,
                                                    ( -- nombre de fois remplaçant
                                                        SELECT COUNT(`statutM`) 
                                                        FROM jouer 
                                                        WHERE numLicence = :numLicence 
                                                        AND statutM = "Remplaçant"
                                                    ) as nbRemp,
                                                    ( -- moyenne des notes données
                                                        SELECT round(AVG(note), 0) 
                                                        FROM jouer 
                                                        WHERE numLicence = :numLicence
                                                    ) as moyNote,
                                                    ( -- nombre de victoire du joueur
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
<script type="text/javascript">
    var win = <?= json_encode($win) ?>;
    var lose = <?= json_encode($lose) ?>;
    var draw = <?= json_encode($draw) ?>;
    var total = win + lose + draw;

    if (win > 0 || lose > 0 || draw > 0) {
        new Chart(document.getElementById("myChart"), {
            type: 'pie',
            data: {
                labels: ["Victoire", "Défaite", "Matchs nuls"],
                datasets: [{
                    backgroundColor: ['green', 'red', 'gray'],
                    data: [win,lose, draw]
                }]
            },
            options: {
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            return ((data['datasets'][0]['data'][tooltipItem['index']]/total)*100).toFixed(2) + '%';
                        }
                    }
                }
            }
        });
    } else {
        new Chart(document.getElementById("myChart"), {
            type: 'pie',
            data: {
                labels: ["Pas de matchs"],
                datasets: [{
                    backgroundColor: ['orange'],
                    data: [100]
                }]
            },
            options: {
                tooltips: {
                    mode: 'label',
                    callbacks: {
                        label: function(tooltipItem, data) {
                            return data['datasets'][0]['data'][tooltipItem['index']] + '%';
                        }
                    }
                }
            }
        });
    }
    
</script>
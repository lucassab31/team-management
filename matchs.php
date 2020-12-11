<?php
    require_once('includes/header.php');
?>
<main>
    <section class="matchs">
        <div class="matchs__header">
            <h2 class="section-title text-orange">Liste des matchs  <a href="#"><i class="fas fa-plus fa-fw" style="background-color:green;"></i></a></h2>
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
                            <td><?= isset($date['score']) ? $data['score'] : "NA" ?></td>
                            <td>
                                <a href="?modify=<?= $data['idMatch'] ?>"><i style="background-color:orange;" class="fas fa-pen"></i></a>
                                <a href="?delete=<?= $data['idMatch'] ?>" onclick="Supp(this.href); return(false)"><i style="background-color:red;" class="fas fa-times"></i></a>
                            </td>
                        </tr>
                        <?php
                    }
                ?>
            </table>
        </div>
    </section>

    <section class="detail-match">
        <h2 class="section-title">Match du XX/XX/XXXX à XX:XX</h2>
    </section>

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
    </section>

    <?php
        if (isset($_POST['submitA'])) {
            $insert = $bdd->prepare("INSERT INTO matchs(dateM, heureM, opposant, lieu) VALUES(?, ?, ?, ?)");
            $insert->execute(array($_POST['dateM'], $_POST['heureM'], $_POST['opposant'], $_POST['lieu']));
        }
    ?>

    <section class="modification-match">
    <h2 class="section-title text-orange text-center">Modification du match du XX/XX/XXXX à XX:XX</h2>
        <form method="post">
        <label for="date">Date du match</label>
            <input type="date" name="dateM" required>
            <label for="date">Heure du match</label>
            <input type="time" name="heureM" required>
            <input type="text" name="opposant" placeholder="Opposant" required>
            <label for="lieu">Lieu</label>
            <select name="lieu">
                <option value="domicile">Domicile</option>
                <option value="exterieur">Extérieur</option>
            </select>
            <input type="texte" name="score" placeholder="Score : x-x">
            <input type="submit" name="submitM" value="Modifier">
        </form>
    </section>
</main>
<script>
    function Supp(link){
        if(confirm('Confirmer la suppression ?')){
        document.location.href = link;
        }
   };
</script>
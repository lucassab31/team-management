<?php
    require_once('includes/header.php');
?>
<main>
    <section class="liste-matchs">
        <h2 class="section-title">Liste des matchs <i class="fa fa-plus"></i></h2>
        <div class="matchs__recherche">
            <form method="post">
                <input type="date" name="recherche">
                <input type="submit" value="Rechercher">
            </form>
        </div>
        <table>
            <tr>
                <th>Date</th>
                <th>L'heure</th>
                <th>Lieu</th>
                <th>Score</th>
                <th>Action</th>
            </tr>
        </table>
    </section>

    <section class="detail-match">
        <h2 class="section-title">Match du XX/XX/XXXX à XX:XX</h2>
    </section>

    <section class="ajout-match">
        <h2 class="section-title text-orange text-center">Ajout d'un match</h2>
        <form method="post">
            <label for="date">Date du match</label></br>
            <input type="date" name="dateM" required></br>
            <label for="date">Heure du match</label></br>
            <input type="time" name="heureM" required></br>
            <label for="satut">Lieu</label></br>
            <select name="satut">
                <option value="domicile">Domicile</option>
                <option value="exterieur">Extérieur</option>
            </select></br>
            <input type="submit" name="submitA" value="Ajouter">
        </form>
    </section>

    <section class="modification-match">
    <h2 class="section-title">Modification du match du XX/XX/XXXX à XX:XX</h2>
        <form method="post">
        <label for="date">Date du match</label>
            <input type="date" name="dateM" required>
            <label for="date">Heure du match</label>
            <input type="time" name="heureM" required>
            <label for="satut">Lieu</label>
            <select name="satut">
                <option value="domicile">Domicile</option>
                <option value="exterieur">Extérieur</option>
            </select>
            <label for="score">Score</label>
            <input type="texte" name="score" placeholder="3-1">
            <input type="submit" name="submitM" value="Modifier">
        </form>
    </section>
</main>
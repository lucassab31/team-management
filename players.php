<?php
    require_once('includes/header.php');
?>
<main>
    <section class="joueurs">
        <div class="joueurs__header">
            <h2 class="section-title text-orange">Liste des joueurs <a href="#"><i class="fas fa-plus fa-fw" style="background-color:green;"></i></a></h2>
            <div class="joueurs__recherche">
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
            </table>
        </div>
    </section>

    <section class="detail-joueur">
        <h2 class="section-title">Nom prénom joueur</h2>
        <img src="" alt="photo du joueur">
    </section>

    <section class="ajout-joueur">
        <h2 class="section-title text-center  text-orange">Ajout d'un joueur</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="number" name="numLicence" placeholder="N° licence" required>
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <label for="date">Date de naissance</label>
            <input type="date" name="dateN" required>
            <input type="number" name="taille" placeholder="Taille" required>
            <input type="number" name="poid" placeholder="Poid" required>
            <label for="satut">Statut</label>
            <select name="satut">
                <option value="actif">Actif</option>
                <option value="blesse">Blessé</option>
                <option value="suspendu">Suspendu</option>
                <option value="absent">Absent</option>
            </select>
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

    <section class="modification-joueur">
    <h2 class="section-title  text-center text-orange">Modification de XXX</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="number" name="numLicence" placeholder="N° licence" required>
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <label for="date">Date de naissance</label>
            <input type="date" name="dateN" required>
            <input type="number" name="taille" placeholder="Taille" required>
            <input type="number" name="poid" placeholder="Poid" required>
            <label for="satut">Statut</label>
            <select name="satut">
                <option value="actif">Actif</option>
                <option value="blesse">Blessé</option>
                <option value="suspendu">Suspendu</option>
                <option value="absent">Absent</option>
            </select>
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
</main>
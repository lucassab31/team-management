<?php
    require_once('includes/header.php');
    if (!isset($_SESSION['id']) && $_SESSION['id'] != 1) {
        header('Location: index.php');
    }
?>
<main>
<?php
    if (isset($_GET['action'])){
        
        if ($_GET['action'] == "liste") {
            ?>
            <section class="admin">
                <h2 class="section-title text-orange">Liste des matchs <a href="?action=ajout"><i class="fas fa-plus fa-fw" style="background-color:green;"></i></a></h2>
                <div class="liste-admin">
                    <table>
                        <tr>
                            <th>Nom</th>
                            <th>Action</th>
                        </tr>
                        <?php
                            $select = $bdd->query("SELECT * FROM utilisateurs");
                            while ($data = $select->fetch()) {
                                ?>
                                <tr>
                                    <td><?= $data['nomUtilisateur'] ?></td>
                                    <td>
                                        <a href="?action=modification&id=<?= $data['idUtilisateur'] ?>"><i style="background-color:orange;" class="fas fa-pen"></i></a>
                                        <a href="?action=suppression&id=<?= $data['idUtilisateur'] ?>" onclick="Supp(this.href); return(false)"><i style="background-color:red;" class="fas fa-times"></i></a>
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
            <section class="ajout-admin">
                <h2 class="section-title text-orange text-center">Ajout d'un utilisateur</h2>
                <form method="post">
                    <input type="text" name="login" placeholder="Nom d'utilisateur" required>
                    <input type="password" name="pass" placeholder="Mot de passe" required>
                    <input type="submit" name="submitA" value="Ajouter">
                </form>
                <a href="?action=liste"><i style="background-color:grey;" class="fas fa-arrow-left"></i></a>
            </section>
            <?php

            if (isset($_POST['submitA'])) {
                $login = htmlspecialchars($_POST['login']);
                $pass = sha1($_POST['pass']);
                $insert = $bdd->prepare("INSERT INTO utilisateurs(nomUtilisateur, mdp) VALUES(?, ?)");
                $insert->execute(array($login, $pass));
                header('Location: ?action=liste');
            }
        }

        if ($_GET['action'] == "modification") {
            $select = $bdd->prepare("SELECT * FROM utilisateurs WHERE idUtilisateur=?");
            $select->execute(array($_GET['id']));
            $data = $select->fetch();
            ?>
            <section class="modification-admin">
                <h2 class="section-title text-orange text-center">Modification d'un utilisateur</h2>
                <form method="post">
                    <input type="text" name="login" placeholder="Nom d'utilisateur" value="<?= $data['nomUtilisateur'] ?>" required>
                    <input type="password" name="pass" placeholder="Mot de passe">
                    <input type="submit" name="submitM" value="Modifier">
                </form>
                <a href="?action=liste"><i style="background-color:grey;" class="fas fa-arrow-left"></i></a>
            </section>
            <?php

            if (isset($_POST['submitM'])) {
                $id = $_GET['id'];
                $login = htmlspecialchars($_POST['login']);
                if (!empty($_POST['pass'])) {
                    $pass = sha1($_POST['pass']);
                    $update = $bdd->prepare("UPDATE utilisateurs SET nomUtilisateur=?, mdp=? WHERE idUtilisateur=$id");
                    $update->execute(array($login, $pass));
                } else {
                    $update = $bdd->prepare("UPDATE utilisateurs SET nomUtilisateur=? WHERE idUtilisateur=$id");
                    $update->execute(array($login));
                }
                header('Location: ?action=liste');
            }
        }

        if ($_GET['action'] == "suppression") {
            $delete = $bdd->prepare("DELETE FROM utilisateurs WHERE idUtilisateur=? ");
            $delete->execute(array($_GET['id']));
            header('Location: admin.php?action=liste');
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
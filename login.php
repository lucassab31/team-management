<?php session_start();
    try {
        $bdd = new PDO("mysql:host=localhost; dbname=team-management", "root", "");
        // $bdd = new PDO("mysql:host=localhost; dbname=id15659294_team_management", "id15659294_dbusr", "O4R9!H9\N|J5ycKW");
    } catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
    if (isset($_SESSION['id'])) {
        header('Location: index.php');
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamManagement</title>
    <link rel="icon" type="image/png" href="includes/logo.png" />
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="connexion-background">
    <main>
        <?php
            $select = $bdd->query("SELECT * FROM utilisateurs");
            $users = $select->rowCount();
            if ($users != 0) {
        ?>
                <section class="connexion">
                    <h1 class="section-title">Team<span class="text-orange">Management</span></h1>
                    <h2 class="section-title">Connexion</h2>
                    <form method="post">
                    <input type="text" name="username" placeholder="Nom d'utilisateur" required><br/>
                    <input type="password" name="password" placeholder="Mot de passe" required><br/>
                    <input type="submit" name="connexion" value="Connexion">
                    </form>
                </section>
        <?php
                if (isset($_POST['connexion'])) {
                    $username = htmlspecialchars($_POST['username']);
                    $password = sha1($_POST['password']);
                    $requser = $bdd->prepare("SELECT * FROM utilisateurs WHERE nomUtilisateur=? AND mdp=?");
                    $requser->execute(array($username, $password));
                    // $requser->debugDumpParams();
                    $userexist = $requser->rowCount();
                    if ($userexist == 1) {
                        $userinfo = $requser -> fetch();
                        $_SESSION['id'] = $userinfo['idUtilisateur'];
    
                        if (empty($userinfo['token'])) {
                            $id = $userinfo['idUtilisateur'];
                            $token = sha1($userinfo['id']);
                            $update = $bdd->prepare("UPDATE utilisateurs SET token=? WHERE idUtilisateur=$id");
                            $update->execute(array($token));
                            setcookie("token", $token, time()+3600*24*7, "/");
                        } else {
                            setcookie("token", $userinfo['token'], time()+3600*24*7, "/");
                        }
                        header('Location: index.php');
                    }
                }

            } else {
                ?>
                <section class="connexion">
                    <h1 class="section-title">Team<span class="text-orange">Management</span></h1>
                    <h2 class="section-title">Premier compte</h2>
                    <form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
                    <input type="text" name="username" placeholder="Nom d'utilisateur" required><br/>
                    <input type="password" name="password" placeholder="Mot de passe" required><br/>
                    <input type="submit" name="inscription" value="Création">
                    </form>
                </section>
                <?php

                if (isset($_POST['inscription'])) {
                    $username = htmlspecialchars($_POST['username']);
                    $password = sha1($_POST['password']);
                    $insert = $bdd->prepare("INSERT INTO utilisateurs(nomUtilisateur, mdp) VALUES(?,?)");
                    $insert->execute(array($username, $password));
                    header("Refresh:1");
                }
            }
        ?>
    </main>
</body>
</html>
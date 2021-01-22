<?php session_start();
ob_start();
// connection à la BDD
    try {
        $bdd = new PDO("mysql:host=localhost; dbname=team-management", "root", "");
        // $bdd = new PDO("mysql:host=localhost; dbname=id15659294_team_management", "id15659294_dbusr", "O4R9!H9\N|J5ycKW");
    } catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }

    if (isset($_COOKIE['token']) && !isset($_SESSION['id'])) {
        $req = $bdd->prepare("SELECT * FROM utilisateurs WHERE token=?");
        $req->execute(array($_COOKIE['token']));
        $userexist = $req->rowCount();
        if ($userexist == 1) {
            $requser = $req->fetch();
            $_SESSION['id'] = $requser['idUtilisateur'];
            setcookie("token", $_COOKIE['token'], time()+3600*24*7, "/");
        }
    }
// verification de l'authentification de l'utilisateur
    if (!isset($_SESSION['id'])) {
        header('Location: login.php');
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Management</title>
    <link rel="icon" type="image/png" href="includes/logo.png" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
</head>
<body>
    <header> <!-- Menu -->
        <h1 class="section-title"><a href="index.php"><img src="includes/logo.png" alt="logo" height="40">  Team<span class="text-orange">Management</span></a></h1>
        <nav>
            <ul>
                <li><a href="index.php">ACCUEIL</a></li>
                <li><a href="players.php?action=liste">JOUEURS</a></li>
                <li><a href="matchs.php?action=liste">MATCHS</a></li>
                <?= isset($_SESSION['id']) && $_SESSION['id'] == 1 ? '<li><a href="admin.php?action=liste">ADMIN</a></li>' : "" ?>
            </ul>
        </nav>
    </header>
</body>
</html>
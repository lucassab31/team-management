<?php session_start();
    try {
        $bdd = new PDO("mysql:host=localhost; dbname=team-management", "root", "");
    } catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamManagement</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="connexion-background">
    <main>
        <section class="connexion">
            <h1 class="section-title">Team<span class="text-orange">Management</span></h1>
            <form method="post">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required><br/>
            <input type="password" name="password" placeholder="Mot de passe" required><br/>
            <input type="submit" name="connexion" value="Connexion">
            </form>
        </section>
    </main>
</body>
</html>
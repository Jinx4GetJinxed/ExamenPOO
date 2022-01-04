<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>Inscription</title>
</head>

<body>
    <?php
    include_once "./menu.php";
    include_once "../controleur/formulaireInscription.php";
    $valid1 = $valid2 = $valid3 = $valid4 = $valid5 = $valid6 = 0;
    if (isset($_POST["pseudo"]) && isset($_POST["email"]) && isset($_POST["psw"])) {
        $form = new formulaireInscription($_POST["pseudo"], $_POST["email"], $_POST["psw"]);
    }
    ?>
    <div id="bords">

        <h1 class="titre">Inscription</h1>
        <hr>

        <form action="inscription.php" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <table class="centrer">
                <tr>
                    <td> <label for="pseudo">Pseudo :</label></td>
                    <td> <input type="text" id="pseudo" name="pseudo" <?php if (isset($_POST["pseudo"]) && !empty($_POST["pseudo"])) { ?> value="<?php echo $_POST["pseudo"]; ?>" <?php } ?> required><br>
                        <?php
                        if (isset($_POST["pseudo"])) {
                            $valid1 = $form->checkEmailPseudo($_POST["pseudo"]);
                            if ($valid1 == 1) {
                                $valid1 = $form->verificationPseudo($_POST["pseudo"]);
                            }
                        } ?></td>
                </tr>
                <tr>
                    <td> <label for="confirmPseudo">Confirmation du pseudo :</label></td>
                    <td> <input type="text" id="confirmPseudo" name="confirmPseudo" required><br>
                        <?php
                        if (isset($_POST["confirmPseudo"]) && $valid1 == 1) {
                            $valid2 = $form->confirmationPseudo($_POST["confirmPseudo"]);
                        } ?>
                    </td>
                </tr>
                <tr>
                    <td> <label for="email">Email :</label></td>
                    <td> <input type="email" id="email" name="email" required><br>
                        <?php
                        if (isset($_POST["email"])) {
                            $valid3 = $form->verificationEmail($_POST["email"]);
                        } ?></td>
                </tr>
                <tr>
                    <td> <label for="confirmEmail">Confirmation de l'email :</label></td>
                    <td> <input type="email" id="confirmEmail" name="confirmEmail" required><br>
                        <?php if (isset($_POST["confirmEmail"]) && $valid3 == 1) {
                            $valid4 = $form->confirmationEmail($_POST["confirmEmail"]);
                        } ?></td>
                </tr>
                <tr>
                    <td> <label for="psw">Mot de passe :</label></td>
                    <td> <input type="password" id="psw" name="psw" required><br>
                        <?php if (isset($_POST["psw"])) {
                            $valid5 = $form->verificationPassword($_POST["psw"]);
                        } ?></td>
                </tr>
                <tr>
                    <td> <label for="confirmPsw">Confirmation du mot de passe :</label></td>
                    <td> <input type="password" id="confirmPsw" name="confirmPsw" required><br>
                        <?php if (isset($_POST["confirmPsw"]) && $valid5 == 1) {
                            $valid6 = $form->confirmationPassword($_POST["confirmPsw"]);
                        } ?></td>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="titre">
                        <hr><input type="submit" id="button" value="Inscription">
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php
    if (((((($valid1 == $valid2) && $valid3) && $valid4) && $valid5) && $valid6) == 1) {

        try {
            session_set_cookie_params(600);
            session_start();

            $_SESSION["pseudo"] = $_POST["pseudo"];
            $_SESSION["email"] = $_POST["email"];
            $psw = password_hash($_POST["psw"], PASSWORD_DEFAULT);
            $form->insertionInscription($_POST["pseudo"], $_POST["email"], $psw);
            $_SESSION["admin"] = $form->verificationAdmin($_SESSION["pseudo"]);
            header("Location:./quiz.php");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } else {
        $_POST["confirmPseudo"] = $_POST["email"] = $_POST["confirmEmail"] = $_POST["psw"] = $_POST["confirmPsw"] = null;
    }
    ?>
    <footer id="footer"><?php include_once './footer.php' ?></footer>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./pico.classless.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="./style.css?<?php echo time(); ?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" defer></script>
    <script src="script.js?<?php echo time(); ?>" defer></script>
    <title>User Verwaltung</title>
</head>

<body>
    <header>
        <h1>User Verwaltung</h1>
    </header>

    <main id="user-management">
        <section>
            <form id="add-form">
                <h3>Benutzer hinzufügen</h3>
                <label for="vorname">Vorname:</label>
                <input type="text" id="vorname" name="vorname"><br>
                <label for="nachname">Nachname:</label>
                <input type="text" id="nachname" name="nachname"><br>
                <label for="email">E-Mail:</label>
                <input type="email" id="email" name="email"><br>
            </form>
            <input type="submit" value="Benutzer hinzufügen">
        </section>
        <section>
            <form id="edit-form">
                <h3>Benutzer bearbeiten</h3>
                <label for="edit-userID">Benutzer ID:</label>
                <input type="number" id="edit-userID" name="edit-userID" min="1"><br>
                <label for="edit-vorname">Vorname:</label>
                <input type="text" id="edit-vorname" name="vorname"><br>
                <label for="edit-nachname">Nachname:</label>
                <input type="text" id="edit-nachname" name="nachname"><br>
                <label for="edit-email">E-Mail:</label>
                <input type="email" id="edit-email" name="email"><br>
            </form>
            <input type="submit" value="Benutzer bearbeiten">
        </section>
        <article>
            <h2>Benutzer Liste</h2>
            <hr>
            <div style="overflow:auto;">
                <table id="users-table">
                </table>
            </div>
        </article>
    </main>

    <footer>
        <p><em>Bewerbung für <a href="https://www.siwa.at/"><strong>SIWA</strong></a></em></p>
    </footer>
</body>

</html>
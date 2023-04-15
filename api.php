<?php
header('Content-Type: application/json');

$conn = mysqli_connect('localhost', 'root', '', 'testing_db', '3306');

// Aktion
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'get_users':
            get_users($conn);
            break;
        case 'add_user':
            add_user($conn);
            break;
        case 'edit_user':
            edit_user($conn);
            break;
        case 'delete_user':
            delete_user($conn);
            break;
        default:
            echo "Keine valide Aktion erhalten.";
            break;
    }
} else {
    echo "Keine Aktion empfangen.";
}

// Laden
function get_users($conn)
{
    $query = "SELECT * FROM users";
    $result = mysqli_query($conn, $query);
    $users = array();
    while ($row = mysqli_fetch_array($result)) {
        $users[] = array(
            "userID" => $row['userID'],
            "vorname" => $row['vorname'],
            "nachname" => $row['nachname'],
            "email" => $row['email']
        );
    }
    echo json_encode(array(
        "status" => "success",
        "data" => $users
    ));
}

// Hinzufügen
function add_user($conn)
{
    $vorname = mysqli_real_escape_string($conn, $_POST['vorname']);
    $nachname = mysqli_real_escape_string($conn, $_POST['nachname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $query = "INSERT INTO users (vorname, nachname, email) VALUES ('$vorname', '$nachname', '$email')";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo json_encode(array(
            "status" => "success",
            "message" => "Benutzer erfolgreich hinzugefügt"
        ));
    } else {
        echo json_encode(array(
            "status" => "error",
            "message" => "Fehler beim Hinzufügen des Benutzers: " . mysqli_error($conn)
        ));
    }
}

// Bearbeiten
function edit_user($conn)
{
    $userID = mysqli_real_escape_string($conn, $_POST['userID']);
    $vorname = mysqli_real_escape_string($conn, $_POST['vorname']);
    $nachname = mysqli_real_escape_string($conn, $_POST['nachname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $query = "UPDATE users SET vorname='$vorname', nachname='$nachname', email='$email' WHERE userID=$userID";

    if (mysqli_query($conn, $query)) {
        echo "Benutzer erfolgreich bearbeitet.";
    } else {
        echo "Fehler beim Bearbeiten des Benutzers: " . mysqli_error($conn);
    }
}

// Löschen
function delete_user($conn)
{
    $userID = mysqli_real_escape_string($conn, $_GET['userID']);

    $query = "DELETE FROM users WHERE userID=$userID";

    if (mysqli_query($conn, $query)) {
        echo "Benutzer erfolgreich gelöscht.";
    } else {
        echo "Fehler beim Löschen des Benutzers: " . mysqli_error($conn);
    }
}

mysqli_close($conn);

<?php
header('Content-Type: application/json');

// Load the contents of the .env file
$env_file = file_get_contents('.env');

// Split the file into lines and loop through them
$lines = explode("\n", $env_file);
foreach ($lines as $line) {

    // Skip empty lines and comments
    if (empty($line) || substr($line, 0, 1) === '#') {
        continue;
    }

    // Split each line into key/value pairs
    $parts = explode('=', $line, 2);
    $key = trim($parts[0]);
    $value = trim($parts[1]);

    // Set the environment variable
    putenv("$key=$value");
}

if (strpos($_SERVER['HTTP_HOST'], 'infinityfreeapp') !== false) {
    // Running on hosting platform, use platform-specific database settings
    $conn = connectToDatabase("hosted");
} else {
    // Running locally, use local database settings
    $conn = connectToDatabase("local");
}

function connectToDatabase($type)
{
    $type = strtoupper($type);
    $conn = null;
    $host = getenv("$type" . "_HOST_NAME");
    $user = getenv("$type" . "_USERNAME");
    $pass = getenv("$type" . "_PASSWORD");
    $db = getenv("$type" . "_DATABASE");
    $port = intval(getenv("$type" . "_PORT")) === 0 ? 3306 : intval(getenv("$type" . "_PORT"));
    $socket = getenv("$type" . "_SOCKET");

    if (!empty($socket)) {
        $conn = mysqli_connect($host, $user, $pass, $db, $port, $socket);
    } else {
        $conn = mysqli_connect($host, $user, $pass, $db, $port);
    }

    return $conn;
}

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
    $query = "SELECT * FROM jquery_user_management_USER";
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
    $query = "INSERT INTO jquery_user_management_USER (vorname, nachname, email) VALUES ('$vorname', '$nachname', '$email')";
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

    $query = "UPDATE jquery_user_management_USER SET vorname='$vorname', nachname='$nachname', email='$email' WHERE userID=$userID";

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

    $query = "DELETE FROM jquery_user_management_USER WHERE userID=$userID";

    if (mysqli_query($conn, $query)) {
        echo "Benutzer erfolgreich gelöscht.";
    } else {
        echo "Fehler beim Löschen des Benutzers: " . mysqli_error($conn);
    }
}

mysqli_close($conn);

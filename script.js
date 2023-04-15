function loadAllUsers() {
    $.get("api.php", {
        action: "get_users"
    }, function(data) {
        // Tabelle mit Daten füllen
        console.log("user data", data.data);

        document.getElementById("users-table").innerHTML = `
    <thead>
        <tr>
            <td>userID</td>
            <td>Vorname</td>
            <td>Nachname</td>
            <td>Email</td>
            <td>Aktion</td>
        </tr>
    </thead>`;

        for (let i = 0; i < data.data.length; i++) {
            let currentUserData = data.data[i];

            let user = document.createElement("tr");

            user.innerHTML = `
        <td>${currentUserData.userID}</td>
        <td>${currentUserData.vorname}</td>
        <td>${currentUserData.nachname}</td>
        <td>${currentUserData.email}</td>
        <td><button class='delete-button' data-user-id='${currentUserData.userID}'>Löschen</button></td>`;

            document.getElementById("users-table").appendChild(user);
        }
    });
}

$(document).ready(function() {
    // Laden
    loadAllUsers();

    // Hinzufügen
    $("#add-form").submit(function(e) {
        e.preventDefault();
        $.post("api.php", $(this).serialize() + "&action=add_user", function(
            data) {
            console.log("added", data);
            loadAllUsers();
        });
    });
    // Bearbeiten
    $("#edit-form").submit(function(e) {
        let user = $(this).serialize().replace("edit-", "");
        e.preventDefault();
        $.post("api.php", user + "&action=edit_user", function(
            data) {
            console.log("edited", data);
            loadAllUsers();
        });
    });
    // Löschen
    $(document).on("click", ".delete-button", function() {
        var userID = $(this).attr("data-user-id");
        $.get("api.php", {
            action: "delete_user",
            userID: userID
        });

        console.log("deleted", userID);

        loadAllUsers();
    });
});
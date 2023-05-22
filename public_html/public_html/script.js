function populateClients() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var clients = JSON.parse(xhr.responseText);
                var clientsDropdown = document.getElementById("clients");

                // Clear the existing options
                clientsDropdown.innerHTML = "";

                // Add new options based on the retrieved data
                clients.forEach(function(client) {
                    var option = document.createElement("option");
                    option.value = client.clientID;
                    option.text = client.fname + " " + client.lname;
                    clientsDropdown.appendChild(option);
                });
            } else {
                console.error("Request failed with status:", xhr.status);
            }
        }
    };
    xhr.open("GET", "getClients.php", true);
    xhr.send();
}

// Call the function to populate the drop-down menu when the page loads
window.addEventListener("DOMContentLoaded", populateClients);

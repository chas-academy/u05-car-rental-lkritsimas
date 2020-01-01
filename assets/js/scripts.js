document
    .querySelectorAll('[data-target="removeVehicle"]')
    .forEach(function(buttonEl) {
        buttonEl.addEventListener("click", function() {
            // Table row for corresponding vehicle ID
            let row = event.target.parentNode.parentNode;
            let vehicleId = this.dataset.id;
            let removeVehicle = confirm(
                `Remove vehicle with id "${vehicleId}"?`
            );
            if (removeVehicle !== true) return;

            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.status !== 200) {
                    alert("Error: Something went wrong!");
                } else {
                    // Check if request was successful
                    let parsedResponse = JSON.parse(xhr.response);

                    // Remove row if so
                    if (parsedResponse.success) {
                        row.remove();
                    } else {
                        alert("Error: Something went wrong!");
                    }
                }
            };

            xhr.open("POST", `/vehicle/remove/${vehicleId}`, false);
            xhr.setRequestHeader(
                "Content-Type",
                "application/x-www-form-urlencoded"
            );
            xhr.send(`id=${vehicleId}`);
        });
    });

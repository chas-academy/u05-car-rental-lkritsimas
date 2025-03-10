/*
 * Validates a date of birth between 1900-01-01 and current date, also checks if age is 18+
 *
 * This part of the assignment makes no sense as there is no way of properly validating a date of birth without a full year (e.g. YYYY)
 * We should be storing the full date of birth.
 * See: https://sv.wikipedia.org/wiki/Personnummer_i_Sverige#Personnumrets_uppbyggnad
 *
 * Should people under 18 really be renting cars?
 */
function validateDate(yy, mm, dd) {
    let today = new Date();
    let todayYY = today
        .getFullYear()
        .toString()
        .substr(2, 4);

    // Check if given year is before current year and apply century
    let yyyy = +yy < todayYY ? "20" + yy : "19" + yy;
    let dob = new Date(`${yyyy}-${mm}-${dd}`);
    let past = new Date("1900-01-01");

    return !(
        isNaN(dob) ||
        +dob < +past ||
        new Date(dob.getFullYear() + 18, dob.getMonth(), dob.getDate()) >=
            +today
    );
}

// Validate control number
function validateControlNumber(num) {
    let sum = num
        .split("")
        .reverse()
        .map(n => +n)
        .reduce((previous, current, index) => {
            if (index % 2) current *= 2;
            if (current > 9) current -= 9;
            return previous + current;
        });

    return 0 === sum % 10;
}

// Validate vehicle form
let vehicleForm = document.querySelector("form#vehicle");
if (vehicleForm) {
    vehicleForm.addEventListener("submit", function(e) {
        // Prevent form from being submitted
        e.preventDefault();

        let errors = [];

        let id = document.querySelector("input[name=id]").value;
        let make = document.querySelector("select[name=make]").value;
        let color = document.querySelector("select[name=color]").value;
        let year = document.querySelector("input[name=year]").value;
        let price = document.querySelector("input[name=price]").value;

        let parsedYear = parseInt(year, 10) || 0;
        let parsedPrice = parseFloat(price, 10) || 0;

        let dateToday = new Date();

        if (!/^[A-Z]{3}(\d{3}|\d{2}[A-Z]{1})$/.test(id)) {
            errors.push(
                "License plate number must contain 3 characters A-Z followed by 3 digits 0-9 or 3 characters A-Z followed by 2 digits 0-9 followed by 1 character A-Z. E.g. ABC123, ABC12X"
            );
        }
        if (!make.length) {
            errors.push("Make must be selected");
        }
        if (!color.length) {
            errors.push("Color must be selected");
        }
        if (parsedYear < 1900 || +new Date(year) > +dateToday) {
            errors.push(
                `Year must be a value between 1900 and ${dateToday.getFullYear()}`
            );
        }
        if (parsedPrice <= 0) {
            errors.push("Price must have a positive value");
        }

        // Display errors or submit form
        if (errors.length) {
            alert(errors.join("\n"));
        } else {
            this.submit();
        }
    });
}

// Validate customer form
let customerForm = document.querySelector("form#customer");
if (customerForm) {
    customerForm.addEventListener("submit", function(e) {
        // Prevent form from being submitted
        e.preventDefault();

        let errors = [];

        let id = document.querySelector("input[name=id]");
        let firstname = document.querySelector("input[name=firstname]");
        let surname = document.querySelector("input[name=surname]");
        let address = document.querySelector("input[name=address]");
        let postcode = document.querySelector("input[name=postcode]");
        let city = document.querySelector("input[name=city]");
        let phone = document.querySelector("input[name=phone]");

        let idMatch = id.value.match(
            /^([0-9]{2})([0-9]{2})([0-9]{2})[0-9]{4}$/
        );
        if (idMatch && !validateDate(idMatch[1], idMatch[2], idMatch[3])) {
            errors.push("Personal identity number must have a valid date");
        }
        if (idMatch && !validateControlNumber(id.value)) {
            errors.push("Control number is incorrect");
        }
        if (firstname.value.length < 2) {
            errors.push("First name must consist of 2 or more characters");
        }
        if (surname.value.length < 2) {
            errors.push("Surname must consist of 2 or more characters");
        }
        if (address.value.length < 1) {
            errors.push("Address must consist of 1 or more characters");
        }
        if (city.value.length < 2) {
            errors.push("City must consist of 2 or more characters");
        }
        if (!/^[0-9]{5}$/.test(postcode.value)) {
            errors.push("Postcode must consist of 5 digits");
        }
        if (!/^[0][0-9]{9}/.test(phone.value)) {
            errors.push(
                "Phone number must begin with the digit zero and consist of 10 characters"
            );
        }

        // Display errors or submit form
        if (errors.length) {
            alert(errors.join("\n"));
        } else {
            this.submit();
        }
    });
}

function addRowRemoveListener(elements, type) {
    document.querySelectorAll(elements).forEach(function(buttonEl) {
        buttonEl.addEventListener("click", function(e) {
            // Table row for corresponding vehicle ID
            let row = event.target.parentNode.parentNode;
            let id = this.dataset.id;

            // Show confirmation dialog and singularize type
            let confirmed = confirm(
                `Remove ${type.substring(0, type.length - 1)} with id "${id}"?`
            );
            if (confirmed !== true) return;

            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.status === 200) {
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

            xhr.open("POST", `/${type}/${id}/remove`, false);
            xhr.setRequestHeader(
                "Content-Type",
                "application/x-www-form-urlencoded"
            );
            xhr.send(`id=${id}`);
        });
    });
}

addRowRemoveListener('[data-target="removeCustomer"]', `customers`);
addRowRemoveListener('[data-target="removeVehicle"]', `vehicles`);

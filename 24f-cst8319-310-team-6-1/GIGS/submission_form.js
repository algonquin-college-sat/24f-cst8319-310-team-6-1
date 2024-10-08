// Hamza Aourarh(Creator)
// Javascript code for submission form.
// Prevent User from intering info in the database, unlsess they follow certain rules.


let bookInput = document.querySelector("#company_name");
let typeInput = document.querySelector("#country");
let authorInput = document.querySelector("#email");
let genreInput = document.querySelector("#city");
let publisherInput = document.querySelector("#phone");
let yearInput = document.querySelector("#province");


let bookError = document.createElement('p');
bookError.setAttribute("class", "warning");
document.querySelectorAll(".form_content")[0].append(bookError);

let typeError = document.createElement('p');
typeError.setAttribute("class", "warning");
document.querySelectorAll(".form_content")[1].append(typeError);

let authorError = document.createElement('p');
authorError.setAttribute("class", "warning");
document.querySelectorAll(".form_content")[2].append(authorError);

let genreError = document.createElement('p');
genreError.setAttribute("class", "warning");
document.querySelectorAll(".form_content")[3].append(genreError);


let publisherError = document.createElement('p');
publisherError.setAttribute("class", "warning");
document.querySelectorAll(".form_content")[4].append(publisherError);

let yearError = document.createElement('p');
yearError.setAttribute("class", "warning");
document.querySelectorAll(".form_content")[5].append(yearError);







let defaultMSg = "";
let errorMSG1 = "Must start with Capital Letter";
let errorMSG2 = "All fields must be Inputted";




function validatePopulatedFields() {
    let name = bookInput.value;
    let author = authorInput.value;
    let publisher = publisherInput.value;
    let type = typeInput.value;
    let genre = genreInput.value;
    let year = yearInput.value;

    if(name=="") {
        error = errorMSG2;
        document.getElementById("company_name").style.borderColor ="red";
    }

    else if(author =="") {
        error = errorMSG2;
        document.getElementById("email").style.borderColor ="red";
    }

    else if(publisher =="") {
        error = errorMSG2;
        document.getElementById("phone").style.borderColor ="red";
    }

    else if(type =="") {
        error = errorMSG2;
        document.getElementById("country").style.borderColor ="red";
    }

    else if(genre =="") {
        error = errorMSG2;
        document.getElementById("city").style.borderColor ="red";
    }

    else if(year =="") {
        error = errorMSG2;
        document.getElementById("province").style.borderColor ="red";
    }

    else {
        error = defaultMsg;
    }
    return error;

}


function validate() {
    let valid = true; 

    let fieldValidation = validatePopulatedFields();

    if(fieldValidation !==defaultMSg) {
        bookError.textContent = fieldValidation;
        typeError.textContent = fieldValidation;
        authorError.textContent = fieldValidation;
        genreError.textContent = fieldValidation;
        publisherError.textContent = fieldValidation;
        yearError.textContent = fieldValidation;

        valid = false;
    }

    return valid;
};

bookInput.addEventListener("blur", () => { 
    let y = validatePopulatedFields();
    if (y !== defaultMSg) {
        bookError.textContent = defaultMSg;
        document.getElementById(".company_name").style.borderColor =null;
    }
});

authorInput.addEventListener("blur", () => { 
    let x = validatePopulatedFields();
    if (x !== defaultMSg) {
        authorError.textContent = defaultMSg;
        document.getElementById(".email").style.borderColor =null;
    }
});



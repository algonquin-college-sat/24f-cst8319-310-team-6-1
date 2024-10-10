

const form = document.getElementById("form");
const companyName = document.getElementById("company_name");
const email = document.getElementById("email");
const phone = document.getElementById("phone");
const country = document.getElementById("country");
const city = document.getElementById("city");
const province = document.getElementById("province");
const password = document.getElementById("password");
const passwordConfirmation  = document.getElementById("password_confirmation");

form.addEventListener("submit", (event) => {
    event.preventDefault();

    checkForm();    
})

//Functions to clean error messages when a correct input is given
companyName.addEventListener("blur", ()=>{
    checkInputCompanyName();
})

email.addEventListener("blur", () =>{
    checkInputEmail();
})

phone.addEventListener("blur", () =>{
    checkInputPhone();
})

country.addEventListener("blur", () =>{
    checkInputCountry();
})

city.addEventListener("blur", () =>{
    checkInputCity();
})

province.addEventListener("blur", () =>{
    checkInputProvince();
})

password.addEventListener("blur", () =>{
    checkInputPassword();
})

passwordConfirmation.addEventListener("blur", () =>{
    checkInputPasswordConfirmation();
})


function checkInputCompanyName(){
    const companyNameValue = companyName.value;

    if(companyNameValue === ""){
        errorInput(companyName, "Please, insert a Company Name")
    }else{
        const formItem = companyName.parentElement;
        formItem.className = "form_content"
    }
}

function checkInputEmail(){    
    const emailValue = email.value;    
   
    if(emailValue === ""){
        errorInput(email, "Please, insert an email address")
    }else{
        const formItem = email.parentElement;
        formItem.className = "form_content"
    }
}

function checkInputPhone(){
    const phoneValue = phone.value;

    if(phoneValue === ""){
        errorInput(phone, "Please insert a phone number")
    }else if(phoneValue.length < 10){
        errorInput(phone, "Please, insert a valid phone number")
    }else{
        const formItem = phone.parentElement;
        formItem.className = "form_content"
    }
}

function checkInputCountry(){
    const countryValue = country.value;

    if(countryValue === ""){
        errorInput(country, "Plese, insert a country")
    }else{
        const formItem = country.parentElement;
        formItem.className = "form_content"
    }
}

function checkInputCity(){
    const cityValue = city.value;

    if(cityValue === ""){
        errorInput(city, "Please, insert a city")
    }else{
        const formItem = city.parentElement;
        formItem.className = "form_content"
    }
}

function checkInputProvince(){
    const provinceValue = province.value;

    if(provinceValue === ""){
        errorInput(province, "Please, insert a province")
    }else{
        const formItem = province.parentElement;
        formItem.className = "form_content"
    }
}

function checkInputPassword(){
    const passwordValue = password.value;

    if(passwordValue === ""){
        errorInput(password, "Please insert a valid password")
    }else if(passwordValue.length < 8){
        errorInput(password, "Minimum of 8 characters")
    }else{
        const formItem = password.parentElement;
        formItem.className = "form_content"
    }
}

function checkInputPasswordConfirmation(){
    const passwordValue = password.value;
    const passwordConfirmationValue = passwordConfirmation.value;

    if(passwordConfirmationValue === ""){
        errorInput(passwordConfirmation, "Please insert a valid password")
    }else if(passwordConfirmationValue !== passwordValue){
        errorInput(passwordConfirmation, "The passwords are different")    
    }else{
        const formItem = passwordConfirmation.parentElement;
        formItem.className = "form_content"
    }
}

function checkForm(){
    checkInputCompanyName();
    checkInputEmail();
    checkInputPhone();
    checkInputCountry();
    checkInputCity();
    checkInputProvince();
    checkInputPassword();
    checkInputPasswordConfirmation();

    const formItems = form.querySelectorAll(".form_content")

    const isValid = [...formItems].every( (item) => {
        return item.className === "form_content"
    });

    if(isValid){
        alert("Registration SUCCESSFULLY!!!")
    }


}

function errorInput(input, message){
    const formItem = input.parentElement;
    const textMessage = formItem.querySelector("a")

    textMessage.innerText = message;

    formItem.className = "form_content error"
}




//-------------------------------------Creat Account Validation--------------------------------------//



// Password Validation-------------------------------------------
let passwordInput = document.querySelector("#password");
let cPasswordInput = document.querySelector("#cPassword");
let passwordError=document.createElement('p');
passwordError.setAttribute("class","error");
document.querySelectorAll(".form--input-group")[4].append(passwordError);


let passErrorMsg1="Password Must be Inputted";
let passErrorMsg2="Please Confirm Password";
let passErrorMsg3="Password Must have 8 Characters Long!";
let passErrorMsg4="Password Must be less than 20 Characters Long!";
let passErrorMsg5="Passwords must match!";


function validatePass() {
    let pass = passwordInput.value;
    let pass2 = cPasswordInput.value;
    
    if (pass == "") {
        error = passErrorMsg1;
        document.getElementById("password").style.borderColor ="red";
        document.getElementById("cPassword").style.borderColor ="red";
    }

    else if (pass2 =="") {
        error = passErrorMsg2;
        document.getElementById("password").style.borderColor ="red";
        document.getElementById("cPassword").style.borderColor ="red";
    }
    
    else if (pass.length < 8) {
        error = passErrorMsg3;
        document.getElementById("password").style.borderColor ="red";
        document.getElementById("cPassword").style.borderColor ="red";
    }
    
    else if (pass.length > 20) {
        error = passErrorMsg4;
        document.getElementById("password").style.borderColor ="red";
        document.getElementById("cPassword").style.borderColor ="red";
    }

    else if (pass2 !== pass) {
        error = passErrorMsg5;
        document.getElementById("password").style.borderColor ="red";
        document.getElementById("cPassword").style.borderColor ="red";
    }
   
    else {
        error = defaultMsg;
    }
    return error;

}

// Main Validation
function validate(){
    let valid = true;//global validation 
    let emailValidation=validateEmail();
    let passValidation = validatePass();
    if(emailValidation !==defaultMsg){
        emailError.textContent = emailValidation;
        valid = false;
    }

    if (passValidation !== defaultMsg) {
        passwordError.textContent = passValidation;
        valid = false;
    }
    return valid;
};

// Remove error message once correct information is inputted


passwordInput.addEventListener("blur",()=>{ // arrow function
    let x=validatePass();
    if(x == defaultMsg){
        passwordError.textContent = defaultMsg;
        document.getElementById("password").style.borderColor =null;
    }
    });
cPasswordInput.addEventListener("blur",()=>{ // arrow function
    let x=validatePass();
    if(x == defaultMsg){
        passwordError.textContent = defaultMsg;
        document.getElementById("cPassword").style.borderColor =null;
    }
    });

//------------------------------------------------------------------------//
//Validate Login

let usernameInput = document.querySelector("#loginName");
let loginPasswordInput = document.querySelector("#loginPassword");
let loginError=document.createElement('p');
loginError.setAttribute("class","error");
document.querySelectorAll(".loginName")[0].append(loginError);



let loginErrorMsg1="Login Name Must be Inputted";
let loginPassErrorMsg1="Password Must be Inputted";



function login() {
    let userName = usernameInput.value;
    if (userName == defaultMsg) {
        error = loginErrorMsg1;
        document.getElementById("loginName").style.borderColor ="red";
    }
}



function validate1(){
    let valid = true;//global validation 
    let loginValidation=login();

    if(loginValidation ==defaultMsg){
        loginError.textContent = loginValidation;
        valid = false;
    }
    return valid;
};

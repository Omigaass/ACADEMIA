const signin = document.querySelector(".login");
const signup = document.querySelector(".signup");
const slider = document.querySelector(".slider");
const formselect = document.querySelector(".form-value");

signup.addEventListener("click", () => {
    slider.classList.add("moveslider");
    formselect.classList.add("form-slide");
});

signin.addEventListener("click", () => {
    slider.classList.remove("moveslider");
    formselect.classList.remove("form-slide");
});
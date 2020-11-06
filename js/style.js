// chick If There's Local Storage color OPtion
let mainColors = localStorage.getItem("color_option");

if (mainColors !== null) {

    document.documentElement.style.setProperty('--main--color', localStorage.getItem("color_option"));
    // Remove Active Class From All Color List Item
    document.querySelectorAll(".color-list li").forEach(element => {
        element.classList.remove("active");
        // Add Active Class On Element With Data-color === Local Storage ITem
        if(element.dataset.color === mainColors){
            // Add Active Class
            element.classList.add("active");
        }
    });

}

// Swith Colors 
const colorsLi = document.querySelectorAll(".color-list li");
// Loop On All List Items
colorsLi.forEach(li => {
    // Click On Every List Items
    li.addEventListener("click", (e) => {
        // Set Color On Root
        document.documentElement.style.setProperty('--main-color', e.target.dataset.color);
        // Set Color On Local Storage
        localStorage.setItem("color_option", e.target.dataset.color);
        // Remove Active Class From All Childrens
        e.target.parentElement.querySelectorAll(".active").forEach(element => {
            element.classList.remove("active");
        });
        // Add Active Class On Self
        e.target.classList.add("active");
    });
});
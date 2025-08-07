// assets/js/script.js

document.addEventListener("DOMContentLoaded", function () {
    console.log("Custom script loaded");

    // Optional: Example of dynamic feedback
    const alerts = document.querySelectorAll(".alert");
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add("fade");
            alert.style.opacity = "0";
        }, 3000);
    });
});

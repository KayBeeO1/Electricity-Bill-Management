document.getElementById("showTable").addEventListener("click", function() {
    document.getElementById("customerTable").classList.toggle("hide");
});

document.getElementById("logoutButton").addEventListener("click", function() {
    window.location.href = "logout.php";
});

document.addEventListener("keydown", function(event) {
    if (event.key === "Backspace") {
        window.location.href = "logout.php";
    }
});

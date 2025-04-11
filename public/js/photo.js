
// UPLOAD PHOTO ON STUDENTLIST ROUTE
const image = document.getElementById("upload");
const input = document.getElementById("photo");

// FUNCTION CHANGE IMAGE INPUT FILE
input.addEventListener("change", function () {
    if (input.files && input.files[0]) {
        image.src = URL.createObjectURL(input.files[0]);
    }
});

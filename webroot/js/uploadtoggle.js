const toggleButton = document.getElementById("record");
const fileInput = document.getElementById("fileInput");
const buttonContainer = document.getElementById("buttonContainer");
const uploadBoxContainer = document.getElementById("uploadBoxContainer");

document.querySelectorAll('input[type=radio][name=option]').forEach((radio) => {
radio.addEventListener('change', (e) => {
  if (e.target.id === "showButton") {
    buttonContainer.style.display = "block";
    uploadBoxContainer.style.display = "none";
  } else if (e.target.id === "showUploadBox") {
    buttonContainer.style.display = "none";
    uploadBoxContainer.style.display = "block";
  }
});
});
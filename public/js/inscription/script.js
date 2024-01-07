const image = document.querySelector('#image_etudiant');
const texte = document.querySelector('#info-title');
image.classList.add('animation');

setInterval(() => {
    texte.classList.toggle('bold-animation');
}, 1000);
// console.log(image);

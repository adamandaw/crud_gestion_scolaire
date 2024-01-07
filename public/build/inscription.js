"use strict";
// type strBool=string|boolean;
// const gradeFiltreur=document.querySelector('.grade')
// const zoneGrade=document.querySelector('.grade_filtre')
// zoneGrade.style.display = "none";
const button = document.querySelector('#filtreClasseInscrit');
const selecteurDeClasse = document.querySelector('#research_inscrits_classe_classes');
const buttonSelecteurDeClasse = document.querySelector('#research_inscrits_classe_save');
const filtreClasseInscrit = document.querySelector('.formulaire_filtre');
filtreClasseInscrit.style.display = "none";
// button.addEventListener('mouseenter', function() {
//     filtreClasseInscrit.style.display = (filtreClasseInscrit.style.display === "none") ? "block" : "none";
//     button.innerText = (filtreClasseInscrit.style.display === "block") ? 'voir le filtre' : 'Appliquer un filtre';
// });
const myFunc = (btn, zoneDeFiltre, select) => {
    const bouton = btn;
    const leFiltreur = zoneDeFiltre;
    bouton.addEventListener('mouseenter', function () {
        leFiltreur.style.display = (leFiltreur.style.display === "none") ? "block" : "none";
        select.addEventListener('change', function () {
            buttonSelecteurDeClasse.innerText = (leFiltreur.style.display === "block") ? 'voir le filtre' : 'Enregistrer';
        });
    });
};
myFunc(button, filtreClasseInscrit, selecteurDeClasse);


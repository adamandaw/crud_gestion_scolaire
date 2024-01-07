
// filtre classe niveau filiere btn
const btnFilter=document.querySelector('#filtreClasse')
const zoneDeFiltre=document.querySelector('.zone-de-filtre')
zoneDeFiltre.style.display = "none";
// click | mouseenter|mouseleave
btnFilter.addEventListener('mouseenter', function() {
    zoneDeFiltre.style.display = (zoneDeFiltre.style.display === "none") ? "block" : "none";
    btnFilter.innerText = (zoneDeFiltre.style.display === "block") ? 'voir le filtre' : 'Appliquer un filtre';
});


// filtre grade professeur
const gradeFiltreur=document.querySelector('.grade')
const zoneGrade=document.querySelector('.grade_filtre')
zoneGrade.style.display = "none";


gradeFiltreur.addEventListener('mouseenter', function() {
    zoneGrade.style.display = (zoneGrade.style.display === "none") ? "block" : "none";
    gradeFiltreur.innerText = (zoneGrade.style.display === "block") ? 'voir le filtre' : 'Appliquer un filtre';
});

// console.log(gradeFiltreur);
const gradeSelect=document.querySelector('#research_professeur_grade_grade');
const btnGradeSelect=document.querySelector('#research_professeur_grade_save');
btnGradeSelect.style.display = "none";
gradeSelect.addEventListener('change',function () {
    btnGradeSelect.style.display = "block"
    
});

const zoneCrudSuscces=document.querySelector('#zoneDuCrudSuscces')
if (null === zoneCrudSuscces) {
    // console.log('il est  nulle');
}else{
    // console.log('il est pas nulle');
    zoneCrudSuscces.classList.add('fade-right');
    setTimeout(() => {
        zoneCrudSuscces.style.display='none';
    }, 5000);
  
}
// console.log(zoneCrudSuscces);





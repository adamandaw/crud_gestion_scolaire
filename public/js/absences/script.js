
// filtre classe niveau filiere btn
const makeAbscences=document.querySelector('.btn_make_absences')
const form=document.querySelector('.zone_form')

// 

// click | mouseenter|mouseleave
makeAbscences.addEventListener('click', function() {
   form.classList.toggle('d-none')
   this.style.display='none'
});
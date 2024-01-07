

const infosEtudiant = document.querySelector('.info_etudiant');
const formulaire = document.querySelector('.formulaire');
formulaire.style.display='none';
console.log(infosEtudiant.childNodes.length);

if (infosEtudiant.childNodes.length > 3) {
    // console.log("il est pas vide ");
    setTimeout(function() {
        formulaire.style.display = 'block';
    }, 2000); // 5000 millisecondes = 5 secondes
}

function validateForm() {
    var montantInput = document.querySelector("#Montant");
    var montantError = document.querySelector("#montant-error");
    var montant = montantInput.value;

    if (montant <= 0) {
        montantError.style.display = "block";
        return false; // Empêche la soumission du formulaire
    } else {
        montantError.style.display = "none";
        return true; // Autorise la soumission du formulaire
    }
}
// validateForm()
// window.onload = () =>{
//     let niveau = document.querySelector('#cours_niveau');
//     niveau.addEventListener('change',function(){
//         let form = this.closest("form") 
//         let data = this.id + "=" + this.value;
//         console.log(data);
//         fetch(form.action,{
//             method : form.getAttribute('method'),
//             body : data,
//             headers : {
//                 "Content-Type" : "application/x-www-form-urlencoded; charset:UTF-8"
//             }
//         })
//         .then(response  => response.text())
//         .then(html  => {
//             let content  = document.createElement('html')
//             content.innerHTML = html;
//             let nouveauxSelect = content.querySelector('#cours_classes')
//             console.log(nouveauxSelect);
//             document.querySelector('#cours_classes').replaceWith(nouveauxSelect)
//         })
//         .catch(error  => {
//             console.log(error);
//         })
//     })
// }
// // --------------------------------------
// const btn = document.querySelector('.lien');

// // console.log(btn);

fetch('http://localhost:8000/cours/new')
    .then( response => console.log(response));

const selectNiveau = document.querySelector('#cours_niveau');
selectNiveau.addEventListener("change", function(event) { 
   const option = event.target.options[selectNiveau.selectedIndex]; 
   const path = option.getAttribute('data-path');

   fetch(path, {
      method: 'GET',
      headers: {
         'Content-Type': 'application/x-www-form-urlencoded; charset:UTF-8'
      }
   })
   .then(response => (response.json()))
   .then(url => {   
      // console.log(url);
      window.location.href = url; 
   })
   .catch(error => {
      console.log( error);
   });
});
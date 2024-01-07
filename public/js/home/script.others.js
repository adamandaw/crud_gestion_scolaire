
// filtre classe niveau filiere btn
const listMode=document.querySelector('#list_mode')
const calendarMode=document.querySelector('#calendar_mode')
const calendar=document.querySelector('#calendar')
const table=document.querySelector('.list_planning')
calendar.style.display = "none";
// 
//firlte 
const selectDate=document.querySelector('#date')
const  date = new Date();

for (let i = 1; i <= 14; i++) {
    const option = document.createElement("option");
    const dateOption = new Date(date.getFullYear(), date.getMonth(), date.getDate() + i);

    const day = dateOption.getDate();
    const month = dateOption.getMonth() + 1;
    const year = dateOption.getFullYear();
    
    const formattedDate = `${day < 10 ? '0' + day : day}-${month < 10 ? '0' + month : month}-${year}`;

    option.value = formattedDate;
    option.innerHTML = formattedDate;
    
    selectDate.appendChild(option);
}



// click | mouseenter|mouseleave
calendarMode.addEventListener('click', function() {
    calendar.style.display = "block";
    table.style.display = "none";
    // calendarMode.style.backgroundColor="red"
});

listMode.addEventListener('click', function() {
    table.style.display = "block";
    calendar.style.display = "none";
    // table.classList.add("highlight");
});

// Fonction pour générer le calendrier/planning des cours
function generateCourseCalendar() {
    // Obtenir l'élément div qui contiendra le calendrier
    var calendarDiv = document.getElementById("calendar");

    // Tableau des heures de cours
    var hours = ['8h - 10h', '10h - 12h', '13h - 15h', '15h30 - 17h'];

    // Tableau multidimensionnel représentant les cours
    var courses = [
        ['Mathématiques', 'Physique', '', '', '', ''],
        ['Anglais', 'Histoire', '', '', '', ''],
        ['Php', '', '', '', '', ''],
        ['', '', '', '', '', '']
    ];

    // Créer la table du calendrier/planning
    var table = document.createElement("table");

    // Créer la ligne des jours de la semaine
    var daysRow = document.createElement("tr");
    daysRow.appendChild(document.createElement("th")); 
    daysRow.style.border ="2px solid"

    // Obtenir la date actuelle
    var currentDate = new Date();

    // Ajouter les cellules des dates du jour actuel au samedi
    for (var i = 0; i < 6; i++) {
        var dayCell = document.createElement("th");
        var date = currentDate.getDate() + i; // Ajouter le jour actuel + index


        if (date > getLastDayOfMonth(currentDate)) {
            date -= getLastDayOfMonth(currentDate);
        }
        
        dayCell.innerHTML = date;
        daysRow.appendChild(dayCell);
    }
    table.appendChild(daysRow);

    // Créer les lignes pour chaque heure de cours
    for (var hourIndex = 0; hourIndex < hours.length; hourIndex++) {
        var hourRow = document.createElement("tr");

        // Colonne des heures de cours
        var hourCell = document.createElement("th");
        hourCell.innerHTML = hours[hourIndex];
        hourCell.style.backgroundColor ="white"
        hourCell.style.color ="dark"
        hourCell.style.border ="2px solid"
        hourCell.classList.add("header");
        hourRow.appendChild(hourCell);

        // Colonnes pour chaque jour de la semaine
        for (var dayIndex = 0; dayIndex < 6; dayIndex++) {
            var courseCell = document.createElement("td");
            courseCell.innerHTML = courses[hourIndex][dayIndex];
            courseCell.classList.add("course");
            hourRow.appendChild(courseCell);
        }

        table.appendChild(hourRow);
    }

    // Ajouter la table au div du calendrier
    calendarDiv.appendChild(table);
}

// Fonction pour obtenir le dernier jour du mois
function getLastDayOfMonth(date) {
    var nextMonth = new Date(date.getFullYear(), date.getMonth() + 1, 1);
    var lastDay = new Date(nextMonth - 1);
    return lastDay.getDate();
}

// Appeler la fonction pour générer le calendrier/planning des cours
generateCourseCalendar();


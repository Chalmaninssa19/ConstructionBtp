function addRowToTable(data) {
    // Récupérer la référence de la table
    var table = document.getElementById('tableau').getElementsByTagName('tbody')[0];

        // Créer une nouvelle ligne
        var newRow = table.insertRow();

        // Ajouter les cellules avec les données
        var cell1 = newRow.insertCell(0);
        cell1.innerHTML = data.designation; // Supposons que votre donnée a un attribut 'name'

        var cell2 = newRow.insertCell(1);
        cell2.innerHTML = data.prix_unitaire; // Supposons que votre donnée a un attribut 'quantity'

        var cell3 = newRow.insertCell(2);
        cell3.innerHTML = data.unite; // Supposons que votre donnée a un attribut 'unit'

        var cell4 = newRow.insertCell(3);
        var urlModif = '<a href="edit_matiere_premiere?id_matiere_premiere='+data.id_matiere_premiere+'" class="text-warning"><i class="mdi mdi-settings action-icon me-5"></i></a>';
        cell4.innerHTML = urlModif;
}

const tableau = document.getElementById('tableau');
const ths = tableau.querySelectorAll('th');


ths.forEach(function(th) {
    th.addEventListener('click', function() {

        const colonne = th.getAttribute('data-colonne');
        const triAscendant = !th.classList.contains('sorted-up');

        //Reinitialise tous les th
        ths.forEach(th => {
            th.classList.remove('sorted-up', 'sorted-down');
            th.querySelector('.arrow').innerHTML = '&#x25B2;'; // Fleche vers le haut par defaut
        });

        if (triAscendant) {
            th.classList.add('sorted-up');
            th.querySelector('.arrow').innerHTML = '&#x25BC;'; //Fleche vers le bas
        } else {
            th.classList.add('sorted-down');
            th.querySelector('.arrow').innerHTML = '&#x25B2' // Fleche vers le haut
        }



        //const colonne = this.dataset.colonne;
        const ordre =this.classList.contains('asc') ? 'desc' : 'asc';

        // Envoyer une requete Ajaxpour trier les donnees
        fetch('/tri-matiere-premiere-list', {
            method: 'POST',
            headers: {
                'Content-Type':'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ colonne: colonne, ordre: ordre })
        })
        .then(response => response.json())
        .then(data => {
            // Mettre a jour le contenu du tableau avec les donnees triees
            // Exemple : document.querySelector('#tableau tbody').innerHTML = ...;
            const tbody = document.querySelector('#tableau tbody');
            tbody.innerHTML = ''; // Efface le contenu actuel du tbody
            //Parcourir les donnees triees et les ajouter au tbody
            data.forEach(row => {
                addRowToTable(row);
            });

            // Mettre a jour les classes CSS pour les fleches de tri
            document.querySelectorAll('th').forEach(th => {
                th.classList.remove('asc', 'desc');
            });
            this.classList.add(ordre); //Ajouter la classe CSS correspondant a l'ordre de tri
        })
        .catch(error => console.error('Erreur :', error));
    });
});
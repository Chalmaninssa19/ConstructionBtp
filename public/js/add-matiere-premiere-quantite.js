function addRowToTable(data) {
    // Récupérer la référence de la table
    var table = document.getElementById('list').getElementsByTagName('tbody')[0];

        // Créer une nouvelle ligne
        var newRow = table.insertRow();

        // Ajouter les cellules avec les données
        var cell1 = newRow.insertCell(0);
        cell1.innerHTML = data.nom; // Supposons que votre donnée a un attribut 'name'

        var cell2 = newRow.insertCell(1);
        cell2.innerHTML = data.quantite; // Supposons que votre donnée a un attribut 'quantity'

        var cell3 = newRow.insertCell(2);
        cell3.innerHTML = data.unite; // Supposons que votre donnée a un attribut 'unit'

        var cell4 = newRow.insertCell(3);
        cell4.innerHTML = '<a href="" class="text-danger"><i class="mdi mdi-close action-icon"></i></a>';
}

function addNewArticle() {
    matierePremiere = document.getElementById('itemInput').value;
    quantite = document.getElementById('quantiteInput').value;
    
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: 'add-matiere-premiere-quantite',
        type: 'POST',
        dataType: 'json',
        data: {
            // Données à envoyer avec la requête AJAX
            id_matiere_premiere : matierePremiere,
            quantite : quantite
        },
        success: function(response) {
            // Logique à exécuter en cas de succès
            console.log(response)
            if(response.is_exist == true) {
                console.log('Yes');
                updateTableList(response.nom, response.quantity);
            } else {
                addRowToTable(response);
                console.log('No');
            }
        },
        error: function(xhr, status, error) {
            // Logique à exécuter en cas d'erreur
            console.error(xhr.responseText);
        }
    });
}


//Mettre a jour une table existant
function updateTableList(item, quantity) {
    // Obtenez la référence de l'élément table par son ID
    var list = document.getElementById('list');

    // Vérifiez si l'élément avec l'ID spécifié existe
    if (list) {
        // Obtenez toutes les lignes de la table (éléments tr)
        var rows = list.getElementsByTagName('tr');

        // Parcourez toutes les lignes de la table
        for (var i = 0; i < rows.length; i++) {
            // Obtenez toutes les cellules de la ligne actuelle (éléments td)
            var cells = rows[i].getElementsByTagName('td');

            if(cells[0].textContent == item) {
                cells[1].textContent = quantity;
            }
        }
    } else {
        console.log("L'élément n'a pas été trouvé.");
    }
}


// fonction pour supprimé un article ajoute
function deleteRequest(bouton) {
    var article = bouton.closest('.article');
    var id = article.id;
    console.log(id);
    // Ensuite supprimé du session
    $.ajax({
        type: 'GET',
        url: 'http://localhost:8080/SystemeCommerciale/DeleteRequest',
        data: {
            code: id
        },
        success: function (reponse) {
            article.remove();
        },
        error: function () {
            alert("Une erreur est survenue lors du suppression !");
        }
    });
}
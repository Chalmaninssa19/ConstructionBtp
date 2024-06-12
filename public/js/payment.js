function createErrorMessage(error) {
    var errorDiv = document.createElement("div");
    errorDiv.classList.add("error");

    var alertDiv = document.createElement("div");
    alertDiv.classList.add("alert", "alert-danger");

    var errorMessage = document.createTextNode(error);

    alertDiv.appendChild(errorMessage);

    errorDiv.appendChild(alertDiv);

    return errorDiv;
}

function createSuccessMessage(error) {
    var errorDiv = document.createElement("div");
    errorDiv.classList.add("message");

    var alertDiv = document.createElement("div");
    alertDiv.classList.add("alert", "alert-success");

    var errorMessage = document.createTextNode(error);

    alertDiv.appendChild(errorMessage);

    errorDiv.appendChild(alertDiv);

    return errorDiv;
}

function paye() {
    date = document.getElementById('dateInput').value;
    montant = document.getElementById('amountInput').value;
    id_estimate = document.getElementById('id_estimate').value;
    
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: 'save_payment',
        type: 'POST',
        dataType: 'json',
        data: {
            date_payment : date,
            amount : montant,
            id_estimate : id_estimate
        },
        success: function(response) {
            var messageSuccess = 'Paiement de '+montant+' Ar reussi';
            var messagesDiv = createSuccessMessage(messageSuccess);
            document.getElementById('message').appendChild(messagesDiv);
            window.location.reload();
        },
        error: function(xhr, status, error) {
            var jsonData = JSON.parse(xhr.responseText);
            var messageErreur = jsonData.message;
            var messagesDiv = createErrorMessage(messageErreur);
            document.getElementById('error').appendChild(messagesDiv);
            //$(".mt-3 .error").text(messageErreur);
        }
    });
}
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

$(function () {
  function fetchData() {
    $.ajax({
      url: 'histogramme',
      type: 'GET',
      dataType: 'json',
      success: function (data) {
        updateChartData(data);
      },
      error: function (error) {
        var messagesDiv = createErrorMessage(error);
        document.getElementById('error').appendChild(messagesDiv);
        console.error('Erreur lors de la récupération des données : ', error);
      }
    });
  }

  // Fonction pour mettre à jour les données du graphique
  function updateChartData(newData) {
    data.datasets[0].data = newData.data;
    data.datasets[0].label = newData.title;
    data.datasets[0].backgroundColor = newData.colors;
    data.datasets[0].borderColor = newData.borderColors;
    data.labels = newData.label;
    $(".histogramme").text(newData.h4);

    barChart.update();
  }
 


  var data = {
    labels: [],
    datasets: [{
      label: '',
      data: [],
      backgroundColor: [],
      borderColor: [],
      borderWidth: 2,
      fill: true
    }]
  };

  var options = {
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    },
    legend: {
      display: false
    },
    elements: {
      point: {
        radius: 0
      }
    }

  };


  // Get context with jQuery - using jQuery's .get() method.
  if ($("#barChart").length) {
    var barChartCanvas = $("#barChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var barChart = new Chart(barChartCanvas, {
      type: 'bar',
      data: data,
      options: options
    });

    // Appel initial pour récupérer les données
    fetchData();

    // Mise en place d'une temporisation pour actualiser périodiquement les données
    setInterval(fetchData, 60000); // Actualisation toutes les 60 secondes (ajustez selon vos besoins)
  }
});
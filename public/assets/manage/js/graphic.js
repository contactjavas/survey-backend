/**
 * Used global vars:
 * - surveyCharts
 */
(function () {
  var charts = {};

  function init() {
    setupChart();
  }

  function setupChart() {
    surveyCharts.forEach(function (surveyChart) {
      var ctx = document.querySelector(surveyChart.selector);

      charts["question_" + surveyChart.id] = new Chart(ctx, {
        type: "pie",
        data: {
          labels: surveyChart.labels,
          datasets: [
            {
              label: "# of Votes",
              data: surveyChart.data,
              backgroundColor: surveyChart.bgColors,
              borderColor: surveyChart.borderColors,
              borderWidth: 1,
            },
          ],
        },
        options: {
          scales: {
            yAxes: [
              {
                ticks: {
                  beginAtZero: true,
                },
              },
            ],
          }, // End of scales.
        }, // End of options.
      }); // End of charts.
    }); // End of forEach.
  }

  init();
})();

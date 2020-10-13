/**
 * Used global vars:
 * - surveyOpts
 * - surveyCharts
 */
(function () {
  var state = {
    isRequesting: false,
  };
  var xhr = new XMLHttpRequest();
  var apiUrl = 'https://votty-survey.diggy.id/api';
  var elms = {};
  var loading = {};
  var charts = {};

  function init() {
    setupElms();
    setupChart();
    document.forms[0].addEventListener('submit', onSubmit);
  }

  function setupElms() {
    elms.submitButton = document.querySelector(".submit-button");
    elms.filterFields = document.querySelectorAll('.filter-param');
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
          legend: {
            position: "right",
            labels: {
              generateLabels: function (chart) {
                console.log(chart);
                var data = chart.data;
                console.log(data);

                if (data.labels.length && data.datasets.length) {
                  return data.labels.map(function (label, i) {
                    var meta = chart.getDatasetMeta(0);
                    var style = meta.controller.getStyle(i);

                    var total = data.datasets[0].data.reduce(function (
                      acc,
                      val
                    ) {
                      return acc + val;
                    },
                    0);
                    var value = data.datasets[0].data[i];
                    var percent = (value / total) * 100;

                    percent =
                      Math.round((percent + Number.EPSILON) * 100) / 100;
                    
                    var textLabel =
                      label + ": " + value + " suara (" + percent + "%)";

                    return {
                      text: textLabel,
                      fillStyle: style.backgroundColor,
                      strokeStyle: style.borderColor,
                      lineWidth: style.borderWidth,
                      hidden:
                        isNaN(data.datasets[0].data[i]) || meta.data[i].hidden,

                      // Extra data used for toggling the correct item
                      index: i,
                    };
                  });
                }
                return [];
              } // End of generateLabels
            }, // End of labels
          }, // End of legend
          tooltips: {
            callbacks: {
              label: function (tooltipItem, data) {
                var total = data.datasets[0].data.reduce(function (acc, val) {
                  return acc + val;
                }, 0);
                var value = data.datasets[0].data[tooltipItem.index];
                var label = data.labels[tooltipItem.index];
                var percent = (value / total) * 100;
                percent = Math.round((percent + Number.EPSILON) * 100) / 100;

                return label + ": " + value + " suara (" + percent + "%)";
              },
            },
          }, // End of tooltips
        }, // End of options
      }); // End of charts
    }); // End of forEach
  }

  loading.start = function () {
    elms.submitButton.classList.add('is-loading');
  };

  loading.stop = function () {
    elms.submitButton.classList.remove('is-loading');
  };

  function onSubmit(e) {
    e.preventDefault();
    if (state.isRequesting) return;
    state.isRequesting = true;
    loading.start();

    var formData = new FormData();
    var ajaxUrl = apiUrl + '/survey/result/' + surveyOpts.id + '/filter';

    elms.filterFields.forEach(function (field) {
      if (field.value) {
        console.log(field.value);
        formData.append(field.name, field.value);
      }
    });

    xhr.abort();
    xhr.open("POST", ajaxUrl, true);
    xhr.send(formData);
    xhr.onload = function () {
      var r;

      if (xhr.status === 200) {
        r = JSON.parse(xhr.responseText);
        console.log(r);

        for (var key in r.data) {
          if (r.data.hasOwnProperty(key)) {
            charts[key].data.datasets[0].data = r.data[key];
            charts[key].update();
          }
        }
      }
      
      state.isRequesting = false;
      loading.stop();
    };
  }

  init();
})();

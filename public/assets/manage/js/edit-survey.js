(function ($) {
  const $elms = {
    provinceId: $('#province_id'),
    regencyId: $('#regency_id')
  };

  $elms.provinceId.select2();
  $elms.regencyId.select2();

  $elms.provinceId.on('change', function () {
    fetchRegencies($(this).val());
  });

  function fetchRegencies(provinceId) {
    const ajax = new XMLHttpRequest();
    let ajaxUrl = baseUrl + "/api/wilayah/province/" + provinceId + "/regencies/";

    ajax.open("GET", ajaxUrl, true);
    ajax.send();
    ajax.onload = function () {
      const response = JSON.parse(ajax.responseText);

      $elms.regencyId.select2('destroy').empty().select2({data: response.data});
    };
  }

  const startDatePicker = flatpickr(document.querySelector('#start_date'), {
    altInput: true,
    altFormat: 'j F Y',
    dateFormat: 'Y-m-d',
    onChange: function (selectedDates, dateStr, instance) {
      endDatePicker.set('minDate', dateStr);
    },
  });
  
  const endDatePicker = flatpickr(document.querySelector('#end_date'), {
    altInput: true,
    altFormat: 'j F Y',
    dateFormat: 'Y-m-d',
    onChange: function (selectedDates, dateStr, instance) {
      startDatePicker.set('maxDate', dateStr);
    },
  });
})(jQuery);
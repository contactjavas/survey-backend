(function ($) {
  const $elms = {
    provinceId: $('#province_id'),
    regencyId: $('#regency_id'),
    districtId: $('#district_id'),
    villageId: $('#village_id')
  };

  $elms.provinceId.select2();
  $elms.regencyId.select2();
  $elms.districtId.select2();
  $elms.villageId.select2();

  $elms.provinceId.on('change', function () {
    fetchRegencies($(this).val());
  });

  $elms.regencyId.on('change', function () {
    fetchDistricts($(this).val());
  });

  $elms.districtId.on('change', function () {
    fetchVillages($(this).val());
  });

  function fetchRegencies(provinceId) {
    const ajax = new XMLHttpRequest();
    let ajaxUrl = baseUrl + "/api/wilayah/province/" + provinceId + "/regencies/";

    ajax.open("GET", ajaxUrl, true);
    ajax.send();
    ajax.onload = function () {
      const response = JSON.parse(ajax.responseText);

      $elms.regencyId.select2('destroy').empty().select2({data: response.data});
      $elms.regencyId.trigger('change');
    };
  }

  function fetchDistricts(regencyId) {
    const ajax = new XMLHttpRequest();
    let ajaxUrl = baseUrl + "/api/wilayah/regency/" + regencyId + "/districts/";

    ajax.open("GET", ajaxUrl, true);
    ajax.send();
    ajax.onload = function () {
      const response = JSON.parse(ajax.responseText);

      $elms.districtId.select2('destroy').empty().select2({data: response.data});
      $elms.districtId.trigger('change');
    };
  }

  function fetchVillages(districtId) {
    const ajax = new XMLHttpRequest();
    let ajaxUrl = baseUrl + "/api/wilayah/district/" + districtId + "/villages/";

    ajax.open("GET", ajaxUrl, true);
    ajax.send();
    ajax.onload = function () {
      const response = JSON.parse(ajax.responseText);

      $elms.villageId.select2('destroy').empty().select2({data: response.data});
      $elms.villageId.trigger('change');
    };
  }
})(jQuery);
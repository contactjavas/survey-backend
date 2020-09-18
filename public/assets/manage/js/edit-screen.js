(function () {
  document.querySelector('.save-button').addEventListener('click', function () {
    document.forms[0].submit();
  });

  if (ImageBrowser) ImageBrowser.init();
})();
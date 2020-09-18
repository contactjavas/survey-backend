var ImageBrowser = (function($) {
	var modal;

	function init(selector) {
		setupModal();
		setupEvents(selector);
		setupRfmCallback();
	}

	function setupEvents(selector) {
		var elms;

		if (selector) {
			elms = document.querySelectorAll(selector);
		} else {
			elms = document.querySelectorAll(".use-image-browser");
		}

		if (!elms.length) return;

		elms.forEach(function(el) {
			el.addEventListener("focus", openModal);
		});
	}

	function openModal() {
		modal.open();
	}

	function closeModal() {
		modal.close();
	}

	function setupModal() {
		modal = new tingle.modal({
			cssClass: ['file-manager-modal'],
			closeMethods: ["button", "escape"]
		});
		modal.setContent(
			'<iframe width="100%" height="400" src="/filemanager/dialog.php?field_id=image-browser&lang=en_EN" frameborder="0" allowfullscreen></iframe>'
		);
	}

	function setupRfmCallback() {
		if (!window.responsive_filemanager_callback) {
			window.responsive_filemanager_callback = function() {
				closeModal();
			};
		}
	}

	return {
		init: init
	};
})(jQuery);

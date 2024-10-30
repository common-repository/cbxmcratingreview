(function ($) {
	'use strict';

	var cbxmcratingreview_video_filetypes = ["mp4", "m4v", "webm", "ogv", "wmv", "flv"];
	var cbxmcratingreview_audio_filetypes = ["mp3", "m4a", "ogg", "wav", "wma"];



	/**
	 * Get file extension from a file name
	 *
	 * @param $file_url
	 * @returns string
	 */
	function cbxmcratingreview_getFileExtension(file_url) {
		return file_url.split('.').pop();
	}

	/**
	 * Check if the string is valid url
	 * https://stackoverflow.com/a/14582229/341955
	 *
	 * @param str
	 * @returns {boolean}
	 */
	function cbxmcratingreview_isURL(str) {
		var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
			'((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|' + // domain name
			'((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
			'(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
			'(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
			'(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
		return pattern.test(str);
	}



	$(document).ready(function () {
		//any where read only rating
		$('.cbxmcratingreview_readonlyrating_score_js').each(function (index, element) {
			var $element = $(element);
			$(element).cbxmcratingreview_raty({
				hints     : cbxmcratingreview_admin.rating.hints,
				noRatedMsg: cbxmcratingreview_admin.rating.noRatedMsg,
				//starType  : 'img',
				starType  : 'img',
				starHalf  : cbxmcratingreview_admin.rating.img_path + 'star-half.png',                                // The name of the half star image.
				starOff   : cbxmcratingreview_admin.rating.img_path + 'star-off.png',                                 // Name of the star image off.
				starOn    : cbxmcratingreview_admin.rating.img_path + 'star-on.png',                                  // Name of the star image on.
				halfShow  : true,
				readOnly  : true
			});
		});

		//review listing comment details popup using sweetalert
		$('.cbxmcratingreview_review_text_expand').on('click', function (e) {
			e.preventDefault();

			var $this       = $(this);
			var $review_id  = $this.data('reviewid');
			var $headline   = $this.data('headline');
			var reviews_arr = cbxmcratingreview_admin.reviews_arr;

			swal({
				title            : $headline,
				html             : reviews_arr[$review_id],
				//type: "info",
				confirmButtonText: cbxmcratingreview_admin.button_text_ok,
				cancelButtonText : cbxmcratingreview_admin.button_text_cancel
			});
		});
		//end review listing comment details popup using sweetalert

		// in review edit view trigger rating
		/*var $form = $('.cbxmcratingreview-review-edit-form');
		$form.find('.cbxmcratingreview_rating_trigger').cbxmcratingreview_raty({
			cancelHint : cbxmcratingreview_admin.rating.cancelHint,
			hints      : cbxmcratingreview_admin.rating.hints,
			noRatedMsg : cbxmcratingreview_admin.rating.noRatedMsg,
			//starType   : 'img',
			starType   : 'img',
			starHalf  : cbxmcratingreview_admin.rating.img_path + 'star-half.png',                                // The name of the half star image.
			starOff   : cbxmcratingreview_admin.rating.img_path + 'star-off.png',                                 // Name of the star image off.
			starOn    : cbxmcratingreview_admin.rating.img_path + 'star-on.png',                                  // Name of the star image on.
			half       : true,
			halfShow   : true,
			targetScore: $form.find('.cbxmcratingreview_rating_score')
		});
*/


		/*var $cbxmcratingreview_review_readonly_section = $('.cbxmcratingreview-review-readonly-section');
		$cbxmcratingreview_review_readonly_section.find('.cbxmcratingreview-btn-review-delete').on('click', function (e) {
			e.preventDefault();

			var $this = $(this);
			var $review_id = $this.data('review_id');

			$.ajax({
				type    : "post",
				dataType: 'json',
				url     : cbxmcratingreview_admin.ajaxurl,
				data    : {
					action   : "cbxmcratingreview_review_delete_process",
					security : cbxmcratingreview_admin.nonce,
					review_id: $review_id,
				},
				success : function (data) {
					if (data.process_status == 1) {
						alert(data.success_msg);
						window.location = data.redirect_url;
					} else {
						var $err_msg = '<p class="cbxmcratingreview-alert cbxmcratingreview-alert-warning">' + data.error_msg + '</p>';
						$cbxmcratingreview_review_readonly_section.find('.cbxmcratingreview_review_readonly_global_msg').html($err_msg);
					}
				},
			});
		});*/

		//flatpickr enable for review submission
		/*$form.find(".cbxmcratingreview_review_date_flatpicker").flatpickr({
			dateFormat: 'Y-m-d',
		});*/


		// delete single photo
		/*$cbxmcratingreview_review_readonly_section.on('click', 'a.cbxmcratingreview_review_photo_delete', function (e) {
			e.preventDefault();
			var $this = $(this);

			var $review_id = $this.data('review_id');
			var $filename = $this.data('name');

			var request = $.ajax({
				url     : cbxmcratingreview_admin.ajaxurl,
				type    : 'post',
				data    : {
					action   : "file_delete_process_admin",
					security : cbxmcratingreview_admin.nonce,
					filename : $filename, //only input
					review_id: $review_id, //only input
				},
				dataType: 'json',
			});

			request.done(function (data) {
				if (data.ok_to_progress == 1) {
					$this.parent('.cbxmcratingreview_review_photo').fadeOut("slow", function () {
						$this.parent('.cbxmcratingreview_review_photo').remove();
					});
				} else {
					var $err_msg = '<p class="cbxmcratingreview-alert cbxmcratingreview-alert-warning">' + data.error_msg + '</p>';
					$cbxmcratingreview_review_readonly_section.find('.cbxmcratingreview_review_readonly_global_msg').html($err_msg);
				}
			});

			request.fail(function () {
				alert(cbxmcratingreview_admin.delete_error);
			});

			return false;
		});*/


	});

})(jQuery);

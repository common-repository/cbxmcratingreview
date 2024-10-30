(function ($) {
	'use strict';
	$(document).ready(function () {

		//Initiate Color Picker
		$('.wp-color-picker-field').wpColorPicker();

		//add chooser
		$(".chosen-select").chosen({
			width: "95%"
		});



		//form setting page
		$('.ratingtabgroup').hide();
		var $cbratingactivetab = '';
		var rating_form_id = parseInt($('.nav-tab-wrapper').data('form-id'));

		if (typeof (localStorage) != 'undefined') {
			$cbratingactivetab = localStorage.getItem('cbxmcratingreview-form-id-'+rating_form_id);
		}

		//if url has section id as hash then set it as active or override the current local storage value
		if(window.location.hash){
			if($(window.location.hash).hasClass('group')){
				$cbratingactivetab = window.location.hash;
				if (typeof(localStorage) != 'undefined' ) {
					localStorage.setItem('cbxmcratingreview-form-id-'+rating_form_id, $cbratingactivetab);
				}
			}
		}

		if ($cbratingactivetab !== '' && $($cbratingactivetab).length && $($cbratingactivetab).hasClass('ratingtabgroup')) {
			$('.nav-tab-wrapper a').removeClass('nav-tab-active');
			$($cbratingactivetab).fadeIn();
		} else {
			$('.ratingtabgroup:first').fadeIn();
		}


		$('.ratingtabgroup .collapsed').each(function () {
			$(this).find('input:checked').parent().parent().parent().nextAll().each(
				function () {
					if ($(this).hasClass('last')) {
						$(this).removeClass('hidden');
						return false;
					}
					$(this).filter('.hidden').removeClass('hidden');
				});
		});

		if ($cbratingactivetab !== '' && $($cbratingactivetab + '-tab').length) {
			$($cbratingactivetab + '-tab').addClass('nav-tab-active');
		}
		else {
			$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
		}


		$('.nav-tab-wrapper a').on('click', function( evt ) {
			evt.preventDefault();

			$('.nav-tab-wrapper a').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active').blur();
			var clicked_group = $(this).attr('href');
			if (typeof (localStorage) != 'undefined') {
				//set
				localStorage.setItem("cbxmcratingreview-form-id-"+rating_form_id, $(this).attr('href'));
			}

			$('.ratingtabgroup').hide();
			$(clicked_group).fadeIn();

			//$('.chosen-container').removeAttr('style');
		});
		//end form setting page


		//question js starts

		/**
		 * On click question title/label
		 */
		$('.edit-custom-question-fields-wrapper').on('click', 'label.question-label-editable', function (e) {
			e.preventDefault();

			var $this = $(this);
			var $this_input = $this.next('input.question-label-input-editable');

			$this.hide();
			$this_input.show();
			$this_input.focus();
		});//end on click of question title hide label, show input


		/**
		 * on blur of question title input
		 */
		$('.edit-custom-question-fields-wrapper').on('blur', 'input.question-label-input-editable', function (e) {
			e.preventDefault();

			var $this = $(this);
			var $this_label = $this.prev('label.question-label-editable');


			if ($this.hasClass('error')) {
				$this.removeClass('error');
			}

			var val = $this.val(); //get the inline edit input field value

			if (val.length != 0) {
				$this.hide();

				$this_label.text(val); //show the edit text from input field back to label
				$this_label.show();

			} else {
				$this.addClass('error');
			}
		});//end question label input blue
		//question end


		//show/hide criteria box
		$('.edit-custom-criteria-fields-wrapper').on('click','.tools-star-block',function(e){
			e.preventDefault();

			var $this = $(this);
			var $state = parseInt($this.data('state'));
			if($state == 0){
				$this.parents('.custom-criteria-wrapper').find('.custom-criteria-wrapper_inside').show();
				$this.data('state', 1);
			}
			else{
				$this.parents('.custom-criteria-wrapper').find('.custom-criteria-wrapper_inside').hide();
				$this.data('state', 0);
			}
		});
		//question end
	});

})(jQuery);
"use strict";
/***************************************************
****************************************************
// Core Active JS
****************************************************
***************************************************/
$(function () {
	hljs.initHighlightingOnLoad();
	$(document).adminify({
		submenu_animation_speed: 100,
		submenu_opacity_animation: true,
		page_boxed: false,
		page_sidebar_fixed: true,
		page_sidebar_collapsed: false
	});

});


/***************************************************
****************************************************
// Color Switcher JS
****************************************************
***************************************************/
// Show and hide color-switcher
var handleColorSwitcher = function() {
	$(".color-switcher .switcher-button").on('click', function () {
		$(".color-switcher").toggleClass("show-color-switcher", "hide-color-switcher", 800);
	});

	// Color Skins
	$('a.color').on('click', function () {
		var title = $(this).attr('title');
		$('#style-colors').attr('href', 'assets/css/skin/skin-' + title + '.css');
		return false;
	});
};


/***************************************************
****************************************************
// Dropdown Animation
****************************************************
***************************************************/
// Add slidedown & fadein animation to dropdown
var handleDropdownAnimation = function() {
	$('.dropdown').on('show.bs.dropdown', function (e) {
		var $dropdown = $(this).find('.dropdown-menu');
		var orig_margin_top = parseInt($dropdown.css('margin-top'));
		$dropdown.css({
			'margin-top': (orig_margin_top + 25) + 'px',
			opacity: 0
		}).animate({
			'margin-top': orig_margin_top + 'px',
			opacity: 1
		}, 500, function () {
			$(this).css({
				'margin-top': ''
			});
		});
	});
	// Add slidedown & fadeout animation to dropdown
	$('.dropdown').on('hide.bs.dropdown', function (e) {
		var $dropdown = $(this).find('.dropdown-menu');
		var orig_margin_top = parseInt($dropdown.css('margin-top'));
		$dropdown.css({
			'margin-top': orig_margin_top + 'px',
			opacity: 1,
			display: 'block'
		}).animate({
			'margin-top': (orig_margin_top + 25) + 'px',
			opacity: 0
		}, 500, function () {
			$(this).css({
				'margin-top': '',
				display: ''
			});
		});
	});

}


/***************************************************
****************************************************
// Tables Responsive
****************************************************
***************************************************/
var handleTablesResponsive = function tableResponsive() {

	setTimeout(function () {
		$('.table').each(function () {
		let	window_width = $(window).width();
		let table_width = $(this).width();
		let content_width = $(this).parent().width();
			if (table_width > content_width) {
				$(this).parent().addClass('force-table-responsive');
			} else {
				$(this).parent().removeClass('force-table-responsive');
			}
		});
	}, 200);
}


/***************************************************
****************************************************
// Toster Notifications
****************************************************
***************************************************/
var handleTosterNotifications = function() {
	var i = -1;
	var toastCount = 0;
	var $toastlast;
	var getMessage = function () {
		var msg = 'Hi, welcome to Adminify. This is example of Toastr notification box.';
		return msg;
	};

	$('#showsimple').click(function () {
		// Display a success toast, with a title
		toastr.success('Without any options', 'Simple notification!')
	});
	$('#showtoast').click(function () {
		var shortCutFunction = $("#toastTypeGroup input:radio:checked").val();
		var msg = $('#message').val();
		var title = $('#title').val() || '';
		var $showDuration = $('#showDuration');
		var $hideDuration = $('#hideDuration');
		var $timeOut = $('#timeOut');
		var $extendedTimeOut = $('#extendedTimeOut');
		var $showEasing = $('#showEasing');
		var $hideEasing = $('#hideEasing');
		var $showMethod = $('#showMethod');
		var $hideMethod = $('#hideMethod');
		var toastIndex = toastCount++;
		toastr.options = {
			closeButton: $('#closeButton').prop('checked'),
			debug: $('#debugInfo').prop('checked'),
			progressBar: $('#progressBar').prop('checked'),
			preventDuplicates: $('#preventDuplicates').prop('checked'),
			positionClass: $('#positionGroup input:radio:checked').val() || 'toast-top-right',
			onclick: null
		};
		if ($('#addBehaviorOnToastClick').prop('checked')) {
			toastr.options.onclick = function () {
				alert('You can perform some custom action after a toast goes away');
			};
		}
		if ($showDuration.val().length) {
			toastr.options.showDuration = $showDuration.val();
		}
		if ($hideDuration.val().length) {
			toastr.options.hideDuration = $hideDuration.val();
		}
		if ($timeOut.val().length) {
			toastr.options.timeOut = $timeOut.val();
		}
		if ($extendedTimeOut.val().length) {
			toastr.options.extendedTimeOut = $extendedTimeOut.val();
		}
		if ($showEasing.val().length) {
			toastr.options.showEasing = $showEasing.val();
		}
		if ($hideEasing.val().length) {
			toastr.options.hideEasing = $hideEasing.val();
		}
		if ($showMethod.val().length) {
			toastr.options.showMethod = $showMethod.val();
		}
		if ($hideMethod.val().length) {
			toastr.options.hideMethod = $hideMethod.val();
		}
		if (!msg) {
			msg = getMessage();
		}
		$("#toastrOptions").text("Command: toastr[" +
			shortCutFunction +
			"](\"" +
			msg +
			(title ? "\", \"" + title : '') +
			"\")\n\ntoastr.options = " +
			JSON.stringify(toastr.options, null, 2)
		);
		var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
		$toastlast = $toast;
		if ($toast.find('#okBtn').length) {
			$toast.delegate('#okBtn', 'click', function () {
				alert('you clicked me. i was toast #' + toastIndex + '. goodbye!');
				$toast.remove();
			});
		}
		if ($toast.find('#surpriseBtn').length) {
			$toast.delegate('#surpriseBtn', 'click', function () {
				alert('Surprise! you clicked me. i was toast #' + toastIndex + '. You could perform an action here.');
			});
		}
	});

	function getLastToast() {
		return $toastlast;
	}
	$('#clearlasttoast').click(function () {
		toastr.clear(getLastToast());
	});
	$('#cleartoasts').click(function () {
		toastr.clear();
	});
};


/***************************************************
****************************************************
// Mask Formatter
****************************************************
***************************************************/
var handleMaskFormatter = function() {
	provider.initFormatter = function () {
		if (!$.fn.formatter) {
			return;
		}

		provider.provide('formatter', function () {
			var options = {
				pattern: $(this).data('format'),
				persistent: $(this).dataAttr('persistent', true),
			}

			$(this).formatter(options);
		});

	}

};


/***************************************************
****************************************************
// Card - Remove / Reload / Collapse / Expand
****************************************************
***************************************************/
var cardActionRunning = false;
var handleCardAction = function() {
	"use strict";

	if (cardActionRunning) {
		return false;
	}
	cardActionRunning = true;

	// collapse
	$(document).on('mouseover', '[data-toggle=collapse]', function(e) {
		if (!$(this).attr('data-init')) {
			$(this).tooltip({
				title: 'Collapse/Expand',
				placement: 'bottom',
				trigger: 'hover',
				container: 'body'
			});
			$(this).tooltip('show');
			$(this).attr('data-init', true);
		}
	});
	/*$(document).on('click', '[data-toggle=collapse]', function(e) {
		e.preventDefault();
		$(this).closest('.card').find('.card-body').slideToggle();
	});*/

	// reload
	$(document).on('mouseover', '[data-toggle=refresh]', function(e) {
		if (!$(this).attr('data-init')) {
			$(this).tooltip({
				title: 'Refresh',
				placement: 'bottom',
				trigger: 'hover',
				container: 'body'
			});
			$(this).tooltip('show');
		}
	});
	$(document).on('click', '[data-toggle=refresh]', function(e) {
		e.preventDefault();
		var target = $(this).closest('.card');
		if (!$(target).hasClass('card-loading')) {
			var targetBody = $(target).find('.card-body');
			var spinnerClass = ($(this).attr('data-spinner-class')) ? $(this).attr('data-spinner-class') : 'text-primary';
			var spinnerHtml = '<div class="card-loader"><div class="spinner-border '+ spinnerClass +'"></div></div>';
			$(target).addClass('card-loading');
			if ($(targetBody).length !== 0) {
				$(targetBody).append(spinnerHtml);
			} else {
				$(target).append(spinnerHtml);
			}
			setTimeout(function() {
				$(target).removeClass('card-loading');
				$(target).find('.card-loader').remove();
			}, 2000);
		}
	});

	// expand
	$(document).on('mouseover', '[data-toggle=expand]', function(e) {
		if (!$(this).attr('data-init')) {
			$(this).tooltip({
				title: 'Minimize/Maximize',
				placement: 'bottom',
				trigger: 'hover',
				container: 'body'
			});
			$(this).tooltip('show');
			$(this).attr('data-init', true);
		}
	});
	$(document).on('click', '[data-toggle=expand]', function(e) {
		e.preventDefault();
		var target = $(this).closest('.card');
		var targetBody = $(target).find('.card-body');
		var targetClass = 'card-expand';
		var targetTop = 40;
		if ($(targetBody).length !== 0) {
			var targetOffsetTop = $(target).offset().top;
			var targetBodyOffsetTop = $(targetBody).offset().top;
			targetTop = targetBodyOffsetTop - targetOffsetTop;
		}

		if ($('body').hasClass(targetClass) && $(target).hasClass(targetClass)) {
			$('body, .card').removeClass(targetClass);
			$('.card').removeAttr('style');
			$(targetBody).removeAttr('style');
		} else {
			$('body').addClass(targetClass);
			$(this).closest('.card').addClass(targetClass);
		}
		$(window).trigger('resize');
	});

	// remove
	$(document).on('mouseover', '[data-toggle=remove]', function(e) {
		if (!$(this).attr('data-init')) {
			$(this).tooltip({
				title: 'Remove',
				placement: 'bottom',
				trigger: 'hover',
				container: 'body'
			});
			$(this).tooltip('show');
			$(this).attr('data-init', true);
		}
	});
	$(document).on('click', '[data-toggle=remove]', function(e) {
		e.preventDefault();
		$(this).tooltip('dispose');
		$(this).closest('.card').remove();
	});


};


/***************************************************
****************************************************
// Tooltip & Popover
****************************************************
***************************************************/
var handelTooltipPopoverActivation = function() {
	"use strict";
	if ($('[data-toggle="tooltip"]').length !== 0) {
		$('[data-toggle=tooltip]').tooltip();
	}
	if ($('[data-toggle="popover"]').length !== 0) {
		$('[data-toggle=popover]').popover();
	}
};


/***************************************************
****************************************************
// Scrollbar
****************************************************
***************************************************/
var handleSlimScroll = function() {
	"use strict";
	$('[data-scrollbar=true]').each( function() {
		generateSlimScroll($(this));
	});
};
var generateSlimScroll = function(element) {
	if ($(element).attr('data-init')) {
		return;
	}
	var dataHeight = $(element).attr('data-height');
		dataHeight = (!dataHeight) ? $(element).height() : dataHeight;

	var scrollBarOption = {
		height: dataHeight,
		alwaysVisible: false
	};
	if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
		$(element).css('height', dataHeight);
		$(element).css('overflow-x','scroll');
	} else {
		$(element).slimScroll(scrollBarOption);
		$(element).closest('.slimScrollDiv').find('.slimScrollBar').hide();
	}
	$(element).attr('data-init', true);
};


/***************************************************
****************************************************
// Scroll to Top Button
****************************************************
***************************************************/
var handleScrollToTopButton = function() {
	"use strict";
	$(document).scroll( function() {
		var totalScroll = $(document).scrollTop();

		if (totalScroll >= 200) {
			$('[data-click=scroll-top]').addClass('show');
		} else {
			$('[data-click=scroll-top]').removeClass('show');
		}
	});
	$('.content').scroll( function() {
		var totalScroll = $('.content').scrollTop();

		if (totalScroll >= 200) {
			$('[data-click=scroll-top]').addClass('show');
		} else {
			$('[data-click=scroll-top]').removeClass('show');
		}
	});
	$('[data-click=scroll-top]').on('click', function(e) {
		e.preventDefault();
		$('html, body, .content').animate({
			scrollTop: $("body").offset().top
		}, 500);
	});
};

/***************************************************
****************************************************
// Custom ScrollBar JS
****************************************************
***************************************************/

jQuery(document).ready(function(){
    jQuery('.scrollbar-outer').scrollbar();
});


/***************************************************
****************************************************
// Application Controller
****************************************************
***************************************************/
var App = function () {
	"use strict";

	return {
		init: function () {
			this.initComponent();
		},
		initComponent: function() {
			handleColorSwitcher();
			handleDropdownAnimation();
			handleTablesResponsive();
			handleTosterNotifications();
			handleCardAction();
			handelTooltipPopoverActivation();
			handleSlimScroll();
			handleScrollToTopButton();
		},
		scrollTop: function() {
			$('html, body, .content').animate({
				scrollTop: $('body').offset().top
			}, 0);
		}
	};
}();



/* dhsvfvdbfjbdskgnfklnglkfdngl;mfd;lgml;fdml;gmfd;lgm;ldfmglfdlmg;ldfm */

/* ON ME */

function ajaxQuery({url,method,form_data,uuid,redirect}){

    switch (method) {
        case 'POST':

            $.ajax(
                {
                url:url,
                processData: false,
                contentType: false,
                method:method,
                data:form_data,
                beforeSend: function(xhr) {
                showLoader();
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
                success:function(res){
                    hideLoader();

                    if (res.state == 'done') {

                        toast(res.msg,res.title);

                        $('#loader-container').show();
                        $('#academic_modal').modal('hide')
                        if (redirect) {
                            return window.location.href = redirect;
                        }
                        datatable.draw();
                    }
                },
                error:function(res){
                    hideLoader();
                    console.log(res)
                }
            }
                )
            break;

        case 'DELETE':
            $.ajax(
                {
                url:url,
                method:method,
                data:{
                    uuid:uuid
                },
                beforeSend: function(xhr) {
                showLoader();
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
                success:function(res){
                    hideLoader();
                    if (res.state == 'done') {
                        $('#loader-container').show();
                        $('#academic_modal').modal('hide')
                        datatable.draw();
                    }
                },
                error:function(res){


                }
                }
                )


            break;

        default:
            break;
    }

    }

        function clearForm(form) {
            form.reset();
            var checkboxes = form.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
            $('.select2s').val(null).trigger('change');
        }



        function cheers($msg,$type){

            Swal.fire({
                position: "top-end",
                icon: $type,
                title: $msg,
                showConfirmButton: false,
                timer: 1500
              });

        }





    function toast(message,type){

        switch (type) {
            case 'info':
                toastr.info(message,type)
                break;
            case 'success':
                toastr.success(message,type)
                break;

            case 'warning':
                toastr.warning(message,type)
                break;

            case 'error':
                toastr.error(message,type)
                break;

            default:
                break;
        }
    }





    // function showNotification(message, type) {
    //     Swal.fire({
    //       icon: type, // 'success', 'error', 'warning', etc.
    //       title: type,
    //       text:message,
    //       showConfirmButton: false,
    //       timer: 2000, // Automatically close after 2 seconds (similar to Toastr)
    //       timerProgressBar: true,
    //       position: 'top-end', // Adjust as needed
    //       toast: true,
    //     //   background: '#f8f9fa', // Adjust to match your app's design
    //       // Other customization options...
    //     });
    //   }


    //   function showCustomAlert(title, message, icon, buttons) {
    //     Swal.fire({
    //       icon: icon,
    //       title: title,
    //       text: message,
    //       showCancelButton: true,
    //       confirmButtonText: buttons.confirmText || 'OK',
    //       cancelButtonText: buttons.cancelText || 'Cancel',
    //       showCloseButton: buttons.showClose || false,
    //       html: buttons.additionalContent || '',
    //       // Other customization options...
    //     }).then((result) => {
    //       if (result.isConfirmed) {
    //         if (buttons.onConfirm) {
    //           buttons.onConfirm();
    //         }
    //       } else if (result.dismiss === Swal.DismissReason.cancel) {
    //         if (buttons.onCancel) {
    //           buttons.onCancel();
    //         }
    //       }
    //     });
    //   }


      function defaultTodaysDate(id){

        let currentDate = new Date();
        let formattedDate = currentDate.toISOString().substr(0, 10);
        document.getElementById(`${id}`).value = formattedDate;

        }


        function setMaxTodayDate(id){

            let currentDate = new Date();

            // Format the date as yyyy-mm-dd
            var formattedDate = currentDate.toISOString().substr(0, 10);

            // Set the minimum date of the input field to today's date
            document.getElementById(id).max = formattedDate;

        }


        function stepThreeFormWizard ({ url,form_data,currentStep }){

            $.ajax({
                url: url,

                   type: "POST",
                    timeout: 250000,
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: form_data,
                    dataType:'JSON',


                    beforeSend: function(xhr) {
                        loadSpinner();
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },

                     success:function(response){
                        stopSpinner();

                        if (response.state =='done') {

                            console.log(response)
                            $('#finalize_btn').removeClass('d-none');
                            $('.next-step').addClass('d-none');
                            // $('#cnt_person_std_id').val(response.student_uuid);
                            $('#step-' + currentStep).removeClass('active');
                            $('#step-nav a[href="#step-' + currentStep + '"]').addClass('success_bg').addClass('text-white');
                            $('#step-nav a[href="#step-' + currentStep + '"]').find('.font_icon').removeClass('d-none').addClass('text-white');

                            let prev_btn =  document.getElementById('prev-step');

                            prev_btn.setAttribute("data-prev-step",currentStep);

                            const dataToStore = {
                            step: currentStep,
                            formData: Array.from(form_data.entries())
                            };

                            localStorage.setItem("formWizardData", JSON.stringify(dataToStore));

                            ++currentStep;

                            $('#step-' + currentStep).addClass('active');
                            $('.nav-link').removeClass('active');
                            $('#step-nav a[href="#step-' + currentStep + '"]').addClass('active').addClass('text-white');
                            $('.prev-step').removeClass('d-none').addClass('to_step_1');
                            $('#the_next_btn').attr('data-step', currentStep).trigger('change');


                        }


                    },

                        error:function(response){
                            if(response.statusCode == 500){
                                toastr.error('Internal Server Error', 'Error');
                                $('.loader_gif').addClass("d-none");
                            }
                            console.log(response);
                            // {{-- if(response.msg.contains('users_email_unique')){
                            //     alert('email not unique')
                            // } --}}
                    }


        });



        }





        function stepTwoFormWizard ({ url,form_data,currentStep }){

            $.ajax({
                url: url,

                   type: "POST",
                    timeout: 250000,
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: form_data,
                    dataType:'JSON',


                    beforeSend: function(xhr) {
                        loadSpinner();
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },


                     success:function(response){
                        stopSpinner();

                        if (response.state =='done') {
                            $('.next-step').removeClass('disabled');
                            $('.student_id').val(response.student.uuid);
                            // $('#cnt_person_std_id').val(response.student_uuid);
                            $('#step-' + currentStep).removeClass('active');
                            $('#step-nav a[href="#step-' + currentStep + '"]').addClass('success_bg').addClass('text-white');
                            $('#step-nav a[href="#step-' + currentStep + '"]').find('.font_icon').removeClass('d-none').addClass('text-white');

                            let prev_btn =  document.getElementById('prev-step');

                            prev_btn.setAttribute("data-prev-step",currentStep);

                            const dataToStore = {
                            step: currentStep,
                            formData: Array.from(form_data.entries())
                            };

                            localStorage.setItem("formWizardData", JSON.stringify(dataToStore));

                            ++currentStep;

                            $('#step-' + currentStep).addClass('active');
                            $('.nav-link').removeClass('active');
                            $('#step-nav a[href="#step-' + currentStep + '"]').addClass('active').addClass('text-white');
                            $('.prev-step').removeClass('d-none').addClass('to_step_1');
                            $('#the_next_btn').attr('data-step', currentStep).trigger('change');


                        }


                    },

                        error:function(response){
                            if(response.statusCode == 500){
                                toastr.error('Internal Server Error', 'Error');
                                $('.loader_gif').addClass("d-none");
                            }
                            console.log(response);
                            // {{-- if(response.msg.contains('users_email_unique')){
                            //     alert('email not unique')
                            // } --}}
                    }


        });



        }



        function step1FormWizard({ url,form_data,currentStep }){


            $.ajax({
                url: url,
                type: "POST",
                timeout: 250000,
                processData: false,
                contentType: false,
                cache: false,
                data: form_data,
                dataType:'JSON',


                    beforeSend: function(xhr) {
                        loadSpinner();
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },


                     success:function(response){
                        stopSpinner();

                        if (response.state =='done') {
                            $('.next-step').removeClass('disabled');
                            $('.student_id').val(response.student_uuid);
                            $('#cnt_person_std_id').val(response.student_uuid);
                            $('#step-' + currentStep).removeClass('active');
                            $('#step-nav a[href="#step-' + currentStep + '"]').addClass('success_bg').addClass('text-white');
                            $('#step-nav a[href="#step-' + currentStep + '"]').find('.font_icon').removeClass('d-none').addClass('text-white');

                            let prev_btn =  document.getElementById('prev-step');

                            prev_btn.setAttribute("data-prev-step",currentStep);

                            const dataToStore = {
                            step: currentStep,
                            formData: Array.from(form_data.entries())
                            };

                            localStorage.setItem("formWizardData", JSON.stringify(dataToStore));

                            ++currentStep;

                            $('#step-' + currentStep).addClass('active');
                            $('.nav-link').removeClass('active');
                            $('#step-nav a[href="#step-' + currentStep + '"]').addClass('active').addClass('text-white');
                            $('.prev-step').removeClass('d-none').addClass('to_step_1');
                             $('#the_next_btn').attr('data-step', currentStep).trigger('change');


                        }


                    },

                        error:function(response){
                            if(response.statusCode == 500){
                                toastr.error('Internal Server Error', 'Error');
                                $('.loader_gif').addClass("d-none");
                            }
                            console.log(response);
                            // {{-- if(response.msg.contains('users_email_unique')){
                            //     alert('email not unique')
                            // } --}}
                    }


        });






        }





        /* LOADERS */


        let loader = $('#ts-preloader-absolute22');

        function showLoader() {

            loader.css({'display':'block'});

        }
        function hideLoader() {
            loader.removeAttr('style');
            loader.css({'display':'none','z-index':0});
        }

        function loadSpinner(){

            let loadingOverlay = document.getElementById('loadingOverlay');
            loadingOverlay.style.display = 'block';

        }

    /* cxs HAMAS */

        function spark() {
        var loader = '<div class="cxs"><i class="spinner fas fa-spinner fa-spin"></i> Processing...</div>';
        $('body').append(loader);

    }

    function unspark() {
        $('.cxs').remove();
    }

    /* END */

        function stopSpinner(){
        let loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.style.display = 'none';
        document.getElementById('content-inner-all').style.pointerEvents = 'auto';

        }


        /* END LOADERS */


        function showNotification($msg,$type){

            Swal.fire({
                position: "top-end",
                icon: $type,
                title: $msg,
                showConfirmButton: false,
                timer: 1500
              });

        }



$(document).ready(function() {
	App.init();
});

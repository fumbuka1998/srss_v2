
function cheers($msg,$type){

    Swal.fire({
        position: "top-end",
        icon: $type,
        title: $msg,
        showConfirmButton: false,
        timer: 1500
      });

}



function ajaxQuery({url,method,form_data,uuid,redirect}){
    spark()

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
                    unspark()

                    if (res.state == 'done') {
                        unspark()
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
                    unspark();
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
                    toast(res.msg,res.title);
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





    function toast(message,type){

        switch (key) {
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



/* CHECK FOR EXTENSION TYPE */

        // $(document).ready(function() {
        //     // Select the file input element by its ID
        //     const fileInput = document.getElementById('imageFileInput');

        //     // Add an event listener for the change event
        //     fileInput.addEventListener('change', function() {
        //         // Get the selected file
        //         const selectedFile = fileInput.files[0];

        //         if (selectedFile) {
        //             // Get the file's extension
        //             const fileExtension = selectedFile.name.split('.').pop().toLowerCase();

        //             // Define the allowed image extensions
        //             const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        //             // Check if the selected file has a valid extension
        //             if (allowedExtensions.includes(fileExtension)) {
        //                 // Valid image file selected
        //                 alert('Image file selected: ' + selectedFile.name);
        //             } else {
        //                 // Invalid file selected, show an error message
        //                 alert('Please select a valid image file with one of the following extensions: jpg, jpeg, png, gif.');
        //                 // Clear the file input
        //                 fileInput.value = '';
        //             }
        //         }
        //     });
        // });







document.addEventListener('DOMContentLoaded',function(){
    document.getElementById('loader-wrapper').style.display='none';

    // Escuchador de eventos para el campo de la fecha de nacimiento
    document.getElementById('registeredDob').addEventListener('change', function(){
        const dob=new Date(this.value);
        const today=new Date();
        const age=today.getFullYear()-dob.getFullYear();
        if(today.getMonth() < dob.getMonth() || (today.getMonth() === dob.getMonth() && today.getDate() < dob.getDate())){
            age--;
        }
        const dobHelp=document.getElementById('registerDobHelp');
        if(age<18){
            this.classList.add('is-invalid');
            dobHelp.textContent='Debes ser mayor de edad para registrarte';
        } else {
            this.classList.remove('is-invalid');
            dobHelp.textContent='';
        }
    });
});

$(document).ready(function(){
    const form=$('#multi-step-form');
    const steps=form.find('.step');
    let currentStep=0;

    // Función para mostrar el paso actual
    function showStep(stepIndex){
        steps.hide();
        steps.eq(stepIndex).show();
        updateProgress(stepIndex);
    }

    // Función para actualizar la barra de progreso
    function updateProgress(stepIndex){
        const progress=stepIndex*100/(steps.length-1)*100;
        $('.progress-bar').css('width',progress+'%');
        $('.progress-bar').attr('aria-valuenow',progress);
    }

    // Función para manejar el siguiente paso
    function nextStep(){
        if(currentStep<steps.length-1 && validateStep(currentStep)){
            currentStep++;
            showStep(currentStep);
        }
    }

    // Función para manejar el paso anterior
    function prevStep(){
        if(currentStep>0){
            currentStep--;
            showStep(currentStep);
        }
    }

    // Función para validar un paso
    function validateStep(stepIndex){
        const inputs=steps.eq(stepIndex).find('input');
        let isValid=true;
        inputs.each(function(){
            if(!this.checkValidity()){
                $(this).addClass('is-invalid');
                isValid=false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        return isValid;
    }

    // Manejadores de eventos
    $('.next-step').click(nextStep);
    $('.prev-step').click(prevStep);

    // Manajamos los eventos para el servicio de correo electrónico
    $('#hasEmailServiceYes').click(function(){
        nextStep();
    });

    $('#hasEmailServiceNo').click(function(){
        currentStep+=2;
        showStep(currentStep);
    });

    // AJAX para enviar el formulario
    form.on('submit',function(e){
        e.preventDefault();
        if(validateStep(currentStep)){
            const formData=form.serialize();
            $.ajax({
                url: 'install.php',
                type:'POST',
                data: form.serialize(),
                beforeSend:function(){
                    $('#installer-spinner').show();
                },
                success:function(response){
                    if(response.success){
                    // Ocultamos el spinner
                        $('#installer-spinner').hide();
                        // Mostramos el mensaje de éxito
                        $('#successMessage').show();
                        // Deshabilitamos el botón de enviar
                        $('#submitButton').prop('disabled',true);
                        // Redirigimos al usuario a la página de inicio 3 segundos después
                        setTimeout(function(){
                            window.location.href='index.php';
                        },3000);
                    } else{
                        // Mostramos el mensaje de error
                        alert('Error en la instalación: '+response.message);
                    }
                },
                error:function(jqXHR, textStatus, errorThrown){
                    // Ocultamos el spinner
                    $('#installer-spinner').hide();
                    console.error('Error: ',textStatus, errorThrown);
                }
            });
        }
    });
});
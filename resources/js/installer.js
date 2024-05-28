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
        if(age<18){
            this.setCus
        }
    })
})
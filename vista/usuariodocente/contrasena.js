function ChequeaContrasena(actualiza){
    var contrasenaA = document.getElementById("contrasena").value;
    var contrasenaB = document.getElementById("contrasenaB").value;

    if (actualiza && contrasenaA == "" && contrasenaB == "")
        document.getElementById("formulario").submit();
    else if (contrasenaA != contrasenaB)
        alert("No coinciden las contraseñas");
    else{
        let ComplejaContrasena = new RegExp('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})');
        if(ComplejaContrasena.test(contrasenaA))
            document.getElementById("formulario").submit();
        else
            alert("La contraseña debe tener mínimo una minúscula, una mayúscula, un número, un caracter especial y mínimo de 8 letras");
    }
}
// Función que valida la dirección de email siguiendo las reglas especificadas
function validarEmail(email) {
    if (!email || email.trim() === "") return "El correo electrónico no puede estar vacío.";
    if (email.length > 254) return "La dirección de email no puede exceder los 254 caracteres.";

    const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]{1,64}@[a-zA-Z0-9-]{1,63}(\.[a-zA-Z0-9-]{1,63}){1,255}$/;
    if (!emailRegex.test(email)) return "Formato de correo no válido.";

    if (/["'<>&]/.test(email)) return "El email contiene caracteres no permitidos.";
    
    return null;
}


// Añadimos un evento "submit" al formulario, para que se ejecute esta función antes de enviarlo
document.querySelector('form').addEventListener('submit', function(event) {
    // Array para almacenar los mensajes de error
    let errores = [];

    // Obtenemos los valores de los campos del formulario
    let username = document.getElementById('username').value.trim();
    let password = document.getElementById('password').value.trim();
    let confirmPassword = document.getElementById('confirm_password').value.trim();
    let email = document.getElementById('email').value.trim();
    let sexo = document.getElementById('sexo').value;
    let birthdate = document.getElementById('birthdate').value;

    // Validación del nombre de usuario usando expresión regular: Solo letras y números, no empezar con un número, longitud entre 3-15 caracteres
    if (!/^[a-zA-Z][a-zA-Z0-9]{2,14}$/.test(username)) {
        /*
        Explicación de la expresión regular:
        - ^[a-zA-Z]: El primer carácter debe ser una letra (mayúscula o minúscula).
        - [a-zA-Z0-9]{2,14}$: El resto de la cadena puede contener letras y números, con una longitud total de entre 3 y 15 caracteres.
        */
        errores.push("El nombre de usuario debe tener entre 3 y 15 caracteres, comenzar con una letra y solo contener letras y números.");
        document.getElementById('username').style.border = "2px solid red";
    } else {
        document.getElementById('username').style.border = "";
    }

    // Validación de la contraseña usando expresión regular: debe tener entre 6-15 caracteres, al menos una mayúscula, una minúscula y un número
    if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d_-]{6,15}$/.test(password)) {
        /*
        Explicación de la expresión regular:
        - ^(?=.*[a-z]): La contraseña debe contener al menos una letra minúscula.
        - (?=.*[A-Z]): La contraseña debe contener al menos una letra mayúscula.
        - (?=.*\d): La contraseña debe contener al menos un número.
        - [A-Za-z\d_-]{6,15}$: La contraseña solo puede contener letras, números, guiones y guiones bajos, y debe tener entre 6 y 15 caracteres.
        */
        errores.push("La contraseña debe tener entre 6 y 15 caracteres, contener al menos una mayúscula, una minúscula y un número. Solo se permiten letras, números, guiones y guiones bajos.");
        document.getElementById('password').style.border = "2px solid red";
    } else {
        document.getElementById('password').style.border = "";
    }

    // Comprobamos que la contraseña repetida coincida con la original
    if (password !== confirmPassword) {
        errores.push("Las contraseñas no coinciden.");
        document.getElementById('confirm_password').style.border = "2px solid red";
    } else {
        document.getElementById('confirm_password').style.border = "";
    }

    // Validación del email usando la función creada anteriormente
    let emailError = validarEmail(email);
    if (emailError !== "") {
        errores.push(emailError);
        document.getElementById('email').style.border = "2px solid red";
    } else {
        document.getElementById('email').style.border = "";
    }

    // Validación del sexo: debe seleccionarse una opción
    if (!sexo) {
        errores.push("Debes seleccionar un sexo.");
        document.getElementById('sexo').style.border = "2px solid red";
    } else {
        document.getElementById('sexo').style.border = "";
    }

    // Validación de la fecha de nacimiento: debe ser una fecha válida y tener al menos 18 años
    const fechaRegex = /^\d{2}\/\d{2}\/\d{4}$/; // Expresión regular que verifica que el formato de la fecha sea dd/mm/yyyy
    if (!fechaRegex.test(birthdate)) {
        // Si la fecha no coincide con el formato dd/mm/yyyy, se agrega un mensaje de error
        errores.push("La fecha de nacimiento debe estar en formato dd/mm/yyyy.");
        document.getElementById('birthdate').style.border = "2px solid red"; // Se marca el campo como erróneo
    } else {
        // Si la fecha está en el formato correcto, la dividimos en día, mes y año y los convertimos a números
        const [dia, mes, año] = birthdate.split('/').map(Number);

        // Verificamos que el mes esté entre 1 y 12, el día entre 1 y 31, y el año sea razonable (entre 1900 y el año actual)
        if (mes < 1 || mes > 12 || dia < 1 || dia > 31 || año < 1900 || año > today.getFullYear()) {
            // Si alguna de estas condiciones no se cumple, la fecha es inválida
            errores.push("La fecha de nacimiento no es válida.");
            document.getElementById('birthdate').style.border = "2px solid red"; // Se marca el campo como erróneo
        } else {
            // Creamos un objeto de fecha temporal para validar el día correcto para el mes y año, teniendo en cuenta años bisiestos
            const fechaTemporal = new Date(año, mes - 1, dia); // El mes se pasa restando 1 porque los meses en JavaScript van de 0 a 11

            // Verificamos si el día, mes y año coinciden con los valores originales
            // Esto asegura que la fecha sea válida (por ejemplo, que no sea 30/02)
            if (fechaTemporal.getDate() !== dia || fechaTemporal.getMonth() + 1 !== mes || fechaTemporal.getFullYear() !== año) {
                errores.push("La fecha de nacimiento no es válida."); // Mensaje de error si la fecha no es válida
                document.getElementById('birthdate').style.border = "2px solid red"; // Se marca el campo como erróneo
            } else {
                // Si la fecha es válida, calculamos la edad del usuario
                let birthDateObj = fechaTemporal;
                let age = today.getFullYear() - birthDateObj.getFullYear(); // Calculamos la edad en años

                // Calculamos la diferencia de meses entre la fecha actual y la fecha de nacimiento
                let monthDiff = today.getMonth() - birthDateObj.getMonth();
                let dayDiff = today.getDate() - birthDateObj.getDate(); // Calculamos la diferencia de días

                // Si la diferencia de meses es negativa o es el mismo mes pero el día actual es menor, restamos un año a la edad
                if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
                    age--; // Se ajusta la edad si la persona aún no ha cumplido años este año
                }

                // Verificamos si la edad es al menos 18 años
                if (age < 18) {
                    errores.push("Debes tener al menos 18 años."); // Mensaje de error si el usuario es menor de 18 años
                    document.getElementById('birthdate').style.border = "2px solid red"; // Se marca el campo como erróneo
                } else {
                    // Si la edad es válida, se limpia el borde del campo
                    document.getElementById('birthdate').style.border = "";
                }
            }
        }
    }

    // Si hay errores, mostramos un alert con los mensajes y prevenimos que el formulario se envíe
    if (errores.length > 0) {
        alert(errores.join("\n"));
        event.preventDefault();  // Prevenimos el envío del formulario si hay errores
    }
});

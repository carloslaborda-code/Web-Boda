// Función para aplicar el criterio de ordenación seleccionado en el menú desplegable
function aplicarOrden() {
    // Obtiene el valor del criterio seleccionado en el <select id="criterioOrden">
    const criterio = document.getElementById("criterioOrden").value;
    
    // Separa el criterio en dos partes: 'campo' y 'orden' (por ejemplo, "titulo-asc" se convierte en "titulo" y "asc")
    const [campo, orden] = criterio.split("-");
    
    // Log de depuración para verificar qué criterio y orden se están aplicando
    console.log(`Ordenando por: ${campo} en orden ${orden}`);
    
    // Llama a ordenarFotos, pasando el campo y el orden (ascendente si 'orden' es "asc")
    ordenarFotos(campo, orden === "asc");
}

// Función para ordenar las fotos según el criterio seleccionado
function ordenarFotos(criterio, ascendente = true) {
    // Selecciona el <ul> que contiene la lista de fotos en la sección principal
    const listaFotos = document.querySelector("section > ul");
    
    // Convierte los elementos <li> (fotos) en un array para poder ordenarlos
    const items = Array.from(listaFotos.querySelectorAll("li"));

    // Ordena el array 'items' en función del criterio y del orden
    items.sort((a, b) => {
        let valorA, valorB;

        // Extrae el valor correspondiente de cada <li> basado en el criterio seleccionado
        switch (criterio) {
            case "titulo":
                // Obtiene el título de la foto (primer <p> dentro de cada <li>)
                valorA = a.querySelector("p:nth-of-type(1)")?.textContent.split(": ")[1] || "";
                valorB = b.querySelector("p:nth-of-type(1)")?.textContent.split(": ")[1] || "";
                break;
            
            case "autor":
                // Obtiene el autor de la foto (tercer <p> dentro de cada <li>)
                valorA = a.querySelector("p:nth-of-type(3)")?.textContent.split(": ")[1] || "";
                valorB = b.querySelector("p:nth-of-type(3)")?.textContent.split(": ")[1] || "";
                break;
            default:
                return 0; // Retorna 0 si el criterio no coincide con ninguno de los casos
        }

        // Comparación ascendente o descendente:
        // - Para cadenas de texto (título, autor, país) usa localeCompare para tener en cuenta acentos
        if (criterio === "titulo" || criterio === "autor") {
            return ascendente ? valorA.localeCompare(valorB, 'es') : valorB.localeCompare(valorA, 'es');
        } else {
            // Para fechas, compara valores numéricos de milisegundos
            return ascendente ? valorA - valorB : valorB - valorA;
        }
    });

    // Limpia la lista original y agrega los elementos <li> ordenados al <ul> para actualizar el orden en la página
    items.forEach(item => listaFotos.appendChild(item));
}


// Ejecuta la ordenación predeterminada cuando se carga la página
document.addEventListener("DOMContentLoaded", () => {
    // Llama a ordenarFotos por defecto para ordenar por título en orden ascendente al cargar la página
    ordenarFotos("titulo", true);
});

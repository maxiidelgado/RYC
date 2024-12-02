// Inicializar el mapa
const map = L.map('map').setView([-26.1865, -58.1753], 13); // Formosa Capital


// Cargar el mapa de OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
}).addTo(map);

let marker;

// Capturar clic en el mapa para obtener la ubicación
map.on('click', function(e) {
    const lat = e.latlng.lat;
    const lng = e.latlng.lng;

    // Si ya existe un marcador, actualizar su posición
    if (marker) {
        marker.setLatLng(e.latlng);
    } else {
        marker = L.marker(e.latlng).addTo(map);
    }

    // Usar Nominatim para obtener la dirección a partir de latitud y longitud
    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
        .then(response => response.json())
        .then(data => {
            if (data.address) {
                const direccion = data.display_name;
                document.getElementById('direccion').value = direccion;
                document.getElementById('direccion_hidden').value = direccion;
            } else {
                alert("No se encontró la dirección.");
            }
        })
        .catch(err => alert("Error al obtener la dirección: " + err));
});

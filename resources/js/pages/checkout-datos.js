document.addEventListener('DOMContentLoaded', () => {

    const radios       = document.querySelectorAll('input[name="pedEntrega"]');
    const campoDirec   = document.getElementById('campo-direccion');
    const inputDirec   = document.getElementById('envDireccion');
    const deliverCards = document.querySelectorAll('.delivery-card');

    function actualizarEntrega(valor) {
        // Mostrar/ocultar campo dirección
        if (campoDirec) {
            campoDirec.style.display = valor === 'EN' ? '' : 'none';
            if (inputDirec) inputDirec.required = (valor === 'EN');
        }

        // Marcar la card seleccionada
        deliverCards.forEach(card => {
            const radio = card.querySelector('input[type="radio"]');
            card.classList.toggle('selected', radio?.value === valor);
        });
    }

    radios.forEach(radio => {
        radio.addEventListener('change', () => actualizarEntrega(radio.value));
    });

    // Estado inicial
    const checkedRadio = document.querySelector('input[name="pedEntrega"]:checked');
    if (checkedRadio) actualizarEntrega(checkedRadio.value);

    // Click en la card activa el radio aunque el click no cayera en el input
    deliverCards.forEach(card => {
        card.addEventListener('click', () => {
            const radio = card.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                actualizarEntrega(radio.value);
            }
        });
    });

});

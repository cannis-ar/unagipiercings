document.addEventListener('DOMContentLoaded', () => {

    const input    = document.getElementById('comprobante');
    const area     = document.getElementById('upload-area');
    const preview  = document.getElementById('upload-preview');
    const label    = document.getElementById('upload-label');
    const MAX_SIZE = 5 * 1024 * 1024; // 5 MB

    if (!input) return;

    input.addEventListener('change', () => {
        const file = input.files[0];
        if (!file) return;

        if (file.size > MAX_SIZE) {
            label.textContent = '⚠ El archivo supera los 5 MB. Elegí otro.';
            label.style.color = 'var(--color-bordo)';
            input.value = '';
            return;
        }

        area.classList.add('has-file');
        label.textContent = file.name;
        label.style.color = 'var(--color-text)';

        // Preview solo para imágenes
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src     = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Drag & drop
    area.addEventListener('dragover', e => { e.preventDefault(); area.classList.add('has-file'); });
    area.addEventListener('dragleave', () => area.classList.remove('has-file'));
    area.addEventListener('drop', e => {
        e.preventDefault();
        const file = e.dataTransfer.files[0];
        if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            input.dispatchEvent(new Event('change'));
        }
    });

});

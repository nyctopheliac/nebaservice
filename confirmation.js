
function showConfirmation(message) {
    const backdrop = document.createElement('div');
    backdrop.className = 'confirmation-backdrop';

    const container = document.createElement('div');
    container.className = 'confirmation-container';

    const text = document.createElement('p');
    text.textContent = message;

    const okButton = document.createElement('button');
    okButton.className = 'btn btn-ok';
    okButton.textContent = 'OK';

    container.appendChild(text);
    container.appendChild(okButton);
    backdrop.appendChild(container);
    document.body.appendChild(backdrop);

    okButton.addEventListener('click', () => {
        backdrop.remove();
    });

    // Apply dark mode if necessary
    if (document.body.classList.contains('dark')) {
        container.classList.add('dark');
    }
}

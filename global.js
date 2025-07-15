
document.addEventListener('DOMContentLoaded', function() {
    // Adicionar ao carrinho
    document.querySelectorAll('.btn-add-to-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            fetch(`adicionar_carrinho.php?id=${productId}`)
                .then(response => response.json())
                .then(data => {
                    showConfirmation(data.message);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showConfirmation('Ocorreu um erro.');
                });
        });
    });

    // Submissão de formulários (Login/Registo)
    const handleFormSubmit = (formId, url) => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(form);

                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.redirectUrl) {
                        showConfirmation(data.message);
                        setTimeout(() => {
                            window.location.href = data.redirectUrl;
                        }, 1500);
                    } else {
                        showConfirmation(data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showConfirmation('Ocorreu um erro. Por favor, tente novamente.');
                });
            });
        }
    };

    handleFormSubmit('login-form', 'login.php');
    handleFormSubmit('register-form', 'register.php');
});


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

    // Adicionar configuração ao carrinho
    const addToCartBtn = document.getElementById('addToCartBtn');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const productSelects = document.querySelectorAll('.product-select');
            const selectedProducts = {};
            productSelects.forEach(select => {
                if (select.value) {
                    selectedProducts[select.dataset.typeId] = select.value;
                }
            });

            const productIds = Object.values(selectedProducts);

            if (productIds.length > 0) {
                fetch('adicionar_carrinho.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `configuracao=${JSON.stringify(productIds)}`
                })
                .then(response => response.json())
                .then(data => {
                    showConfirmation(data.message);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showConfirmation('Ocorreu um erro ao adicionar a configuração ao carrinho.');
                });
            } else {
                showConfirmation('Selecione pelo menos um componente para adicionar ao carrinho.');
            }
        });
    }

    // Configurador logic
    const productSelects = document.querySelectorAll('.product-select');
    const summaryList = document.getElementById('summaryList');
    const totalPriceEl = document.getElementById('totalPrice');
    const minPriceInput = document.getElementById('minPrice');
    const maxPriceInput = document.getElementById('maxPrice');
    const applyFiltersBtn = document.getElementById('applyFiltersBtn');

    let selectedProducts = {};
    let totalPrice = 0;

    // Event listeners for product selection
    productSelects.forEach(select => {
        select.addEventListener('change', function() {
            const productId = this.value;
            const typeId = this.dataset.typeId;
            const selectedOption = this.options[this.selectedIndex];
            const price = parseFloat(selectedOption.dataset.price) || 0;
            const name = selectedOption.textContent;

            if (selectedProducts[typeId]) {
                totalPrice -= selectedProducts[typeId].price;
            }

            if (productId) {
                selectedProducts[typeId] = { id: productId, name: name, price: price, categoria_nome: allCategories.find(cat => cat.ID == typeId).nome };
                totalPrice += price;
            } else {
                delete selectedProducts[typeId];
            }

            updateSummary();
            updateTotalPrice();
            filterProducts(); // Re-filter products after a selection changes
        });
    });

    // Event listener for apply filters button
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', filterProducts);
    }

    function updateSummary() {
        summaryList.innerHTML = '';
        for (const typeId in selectedProducts) {
            const product = selectedProducts[typeId];
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            listItem.textContent = product.name;
            summaryList.appendChild(listItem);
        }
    }

    function updateTotalPrice() {
        totalPriceEl.textContent = `€${totalPrice.toFixed(2).replace('.', ',')}`;
    }

    function getProductSpecs(productId) {
        return allSpecifications[productId] || [];
    }

    function getProductCompatibility(productId) {
        return allCompatibilities[productId] || [];
    }

    function filterProducts() {
        const minPrice = parseFloat(minPriceInput.value) || 0;
        const maxPrice = parseFloat(maxPriceInput.value) || Infinity;

        const selectedCpu = Object.values(selectedProducts).find(p => p.categoria_nome === 'Processadores');
        const selectedMotherboard = Object.values(selectedProducts).find(p => p.categoria_nome === 'Motherboards');

        productSelects.forEach(select => {
            const currentTypeId = select.dataset.typeId;
            const currentCategory = allCategories.find(cat => cat.ID == currentTypeId);
            const currentCategoryName = currentCategory ? currentCategory.nome : '';
            const previouslySelectedValue = select.value;

            select.innerHTML = '<option value="" data-price="0">Selecione...</option>';

            allProducts.forEach(product => {
                if (product.categoriaID != currentTypeId) {
                    return;
                }

                let isCompatible = true;

                // Price filter
                if (product.preco < minPrice || product.preco > maxPrice) {
                    isCompatible = false;
                }

                // Compatibility Checks
                if (isCompatible && currentCategoryName === 'Processadores') {
                    if (selectedMotherboard) {
                        const cpuSocket = (getProductSpecs(product.ID).find(s => s.chaves === 'Socket') || {}).valor;
                        const motherboardSocket = (getProductSpecs(selectedMotherboard.id).find(s => s.chaves === 'Socket') || {}).valor;
                        if (cpuSocket && motherboardSocket && cpuSocket !== motherboardSocket) {
                            isCompatible = false;
                        }
                    }
                } else if (isCompatible && currentCategoryName === 'Motherboards') {
                    if (selectedCpu) {
                        const motherboardSocket = (getProductSpecs(product.ID).find(s => s.chaves === 'Socket') || {}).valor;
                        const cpuSocket = (getProductSpecs(selectedCpu.id).find(s => s.chaves === 'Socket') || {}).valor;
                        if (motherboardSocket && cpuSocket && motherboardSocket !== cpuSocket) {
                            isCompatible = false;
                        }
                    }
                }
                // Add more rules here, e.g., for RAM type (DDR4/DDR5) vs Motherboard support

                if (isCompatible) {
                    const option = document.createElement('option');
                    option.value = product.ID;
                    option.dataset.price = product.preco;
                    const price = parseFloat(product.preco);
                    option.textContent = `${product.nome} - €${price.toFixed(2).replace('.', ',')}`;
                    select.appendChild(option);
                }
            });

            if (select.querySelector(`option[value="${previouslySelectedValue}"]`)) {
                select.value = previouslySelectedValue;
            } else {
                if (previouslySelectedValue && selectedProducts[currentTypeId] && selectedProducts[currentTypeId].id == previouslySelectedValue) {
                    totalPrice -= selectedProducts[currentTypeId].price;
                    delete selectedProducts[currentTypeId];
                    updateSummary();
                    updateTotalPrice();
                }
                select.value = "";
            }
        });
    }

    // Initial filtering when the page loads
    filterProducts();
});

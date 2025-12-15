// Функции для управления модальным окном
function openModal() {
    document.getElementById('myModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('myModal').style.display = 'none';
}

// Функции для переключения между формами
function showRegistrationForm() {
    document.getElementById('loginForm').classList.remove('active');
    document.getElementById('registrationForm').classList.add('active');
}

function showLoginForm() {
    document.getElementById('registrationForm').classList.remove('active');
    document.getElementById('loginForm').classList.add('active');
}

// Закрытие модального окна при клике вне его
window.onclick = function (event) {
    const modal = document.getElementById('myModal');
    if (event.target === modal) {
        closeModal();
    }
}

function smoothScrollTo(targetId) {
    const targetElement = document.getElementById(targetId);
    if (targetElement) {
        targetElement.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Использование
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        smoothScrollTo(targetId);
    });
});


    document.addEventListener('DOMContentLoaded', function() {
        const menuItems = document.querySelectorAll('.menu-item[data-tab]');

        menuItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                // Убираем активный класс у всех пунктов меню
                menuItems.forEach(i => i.classList.remove('active'));

                // Добавляем активный класс к текущему пункту
                this.classList.add('active');

                // Скрываем все вкладки
                document.querySelectorAll('.tab-content').forEach(tab => {
                    tab.classList.remove('active');
                });

                // Показываем выбранную вкладку
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');

                // Специальная инициализация для нового заказа
                if (tabId === 'new_order') {
                    resetOrderForm();
                }
            });
        });
    });

        document.addEventListener('DOMContentLoaded', function() {
        const transition = document.querySelectorAll('.transition[data-tab]');
        
        transition.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Убираем активный класс у всех пунктов меню
                transition.forEach(i => i.classList.remove('active'));
                
                // Добавляем активный класс к текущему пункту
                this.classList.add('active');
                
                // Скрываем все вкладки
                document.querySelectorAll('.tab').forEach(tab => {
                    tab.classList.remove('active');
                });
                
                // Показываем выбранную вкладку
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
    });

document.addEventListener('DOMContentLoaded', function() {
    // Проверяем, есть ли сообщения об ошибках или успехе
    const errorMsg = document.querySelector('[style*="color: red"]');
    const successMsg = document.querySelector('[style*="color: green"]');

    if (errorMsg || successMsg) {
        openModal();
    }

    // Проверяем, что мы не на странице админки
    const isAdminPage = window.location.pathname.includes('admin.php');
    
    // Устанавливаем минимальную дату для desired_date если элемент существует И мы не в админке
    if (!isAdminPage) {
        const dateControl = document.getElementById('desired_date');
        if (dateControl) {
            const today = new Date().toISOString().split('T')[0];
            dateControl.setAttribute('min', today);
            
            // Устанавливаем значение только если поле пустое
            if (!dateControl.value) {
                // Устанавливаем дату на завтра
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                dateControl.value = tomorrow.toISOString().split('T')[0];
            }
        }
    }

    // Инициализация только если мы на странице account.php (не админка)
    const isAccountPage = window.location.pathname.includes('account.php');
    
    if (isAccountPage) {
        // Устанавливаем текущий шаг
        window.currentStep = 1;
        window.selectedVehicle = null;

        // Добавляем обработчик изменения чекбоксов услуг для пересчета стоимости
        document.querySelectorAll('input[name="services[]"]').forEach(service => {
            service.addEventListener('change', calculateTotal);
        });

        // Устанавливаем минимальную дату для поля desired_date
        const dateControl = document.getElementById('desired_date');
        if (dateControl) {
            const today = new Date().toISOString().split('T')[0];
            dateControl.setAttribute('min', today);
        }
    }
});

// Функции для формы нового заказа

// Глобальные переменные для отслеживания текущего шага
let currentStep = 1;
let selectedVehicle = null;

function validateStep(step) {
    const steps = ['address_step', 'cargo_step', 'service_step', 'confirm_step'];
    let isValid = true;

    // Проверяем валидность полей текущего шага
    switch(step) {
        case 1: // Адрес
            const requiredAddressFields = ['from_country', 'from_city', 'from_address', 'to_country', 'to_city', 'to_address'];
            requiredAddressFields.forEach(field => {
                const element = document.querySelector(`input[name="${field}"]`);
                if (!element || !element.value.trim()) {
                    if (element) {
                        element.style.borderColor = 'red';
                    }
                    isValid = false;
                } else {
                    element.style.borderColor = '';
                }
            });
            break;

        case 2: // Груз и транспорт
            const cargoType = document.querySelector('select[name="cargo_type"]');
            const cargoWeight = document.querySelector('input[name="cargo_weight"]');
            const cargoDescription = document.querySelector('textarea[name="cargo_description"]');

            if (!cargoType || !cargoType.value) {
                if (cargoType) cargoType.style.borderColor = 'red';
                isValid = false;
            } else {
                cargoType.style.borderColor = '';
            }

            if (!cargoWeight || !cargoWeight.value.trim()) {
                if (cargoWeight) cargoWeight.style.borderColor = 'red';
                isValid = false;
            } else {
                cargoWeight.style.borderColor = '';
            }

            if (!cargoDescription || !cargoDescription.value.trim()) {
                if (cargoDescription) cargoDescription.style.borderColor = 'red';
                isValid = false;
            } else {
                cargoDescription.style.borderColor = '';
            }

            // Проверяем, выбран ли транспорт
            if (!selectedVehicle) {
                alert('Пожалуйста, выберите транспорт');
                isValid = false;
            }
            break;

        case 3: // Услуги
            // Услуги не обязательны, переходим дальше
            break;
    }

    if (isValid) {
        // Скрываем текущий шаг
        document.getElementById(steps[step - 1]).classList.remove('active');
        // Показываем следующий шаг
        document.getElementById(steps[step]).classList.add('active');
        currentStep = step + 1;

        // Обновляем превью для шага подтверждения
        if (step === 3) {
            updateOrderPreview();
        }
    } else {
        alert('Пожалуйста, заполните все обязательные поля');
    }
}

function prevStep(step) {
    const steps = ['address_step', 'cargo_step', 'service_step', 'confirm_step'];

    // Скрываем текущий шаг
    document.getElementById(steps[step]).classList.remove('active');
    // Показываем предыдущий шаг
    document.getElementById(steps[step - 1]).classList.add('active');
    currentStep = step;
}

function selectVehicleFromCart(element) {
    // Проверяем, существует ли элемент
    if (!element) return;
    
    // Убираем выделение у всех транспортных средств
    document.querySelectorAll('.car-cart').forEach(cart => {
        if (cart) cart.classList.remove('selected');
    });

    // Выделяем выбранное
    element.classList.add('selected');

    // Сохраняем данные выбранного транспорта
    selectedVehicle = {
        id: element.getAttribute('data-vehicle-id'),
        name: element.getAttribute('data-vehicle-name'),
        price: parseFloat(element.getAttribute('data-vehicle-price')) || 0
    };

    // Заполняем скрытые поля
    const vehicleIdField = document.getElementById('vehicle_id');
    const vehicleNameField = document.getElementById('vehicle_name');
    const vehiclePriceField = document.getElementById('vehicle_price');
    
    if (vehicleIdField) vehicleIdField.value = selectedVehicle.id;
    if (vehicleNameField) vehicleNameField.value = selectedVehicle.name;
    if (vehiclePriceField) vehiclePriceField.value = selectedVehicle.price;

    // Пересчитываем итоговую стоимость
    calculateTotal();
}

function calculateTotal() {
    let vehiclePrice = selectedVehicle ? selectedVehicle.price : 0;
    let servicesPrice = 0;

    // Суммируем стоимость выбранных услуг
    const serviceCheckboxes = document.querySelectorAll('input[name="services[]"]:checked');
    if (serviceCheckboxes) {
        serviceCheckboxes.forEach(service => {
            const priceAttr = service.getAttribute('data-price');
            servicesPrice += parseFloat(priceAttr) || 0;
        });
    }

    const totalPrice = vehiclePrice + servicesPrice;

    // Обновляем отображение цен
    const vehicleDisplay = document.getElementById('vehicle-price-display');
    const servicesDisplay = document.getElementById('services-price-display');
    const totalDisplay = document.getElementById('total-price-display');
    const totalInput = document.getElementById('total_price');
    
    if (vehicleDisplay) vehicleDisplay.textContent = vehiclePrice.toLocaleString('ru-RU') + ' ₽';
    if (servicesDisplay) servicesDisplay.textContent = servicesPrice.toLocaleString('ru-RU') + ' ₽';
    if (totalDisplay) totalDisplay.textContent = totalPrice.toLocaleString('ru-RU') + ' ₽';
    if (totalInput) totalInput.value = totalPrice;
}

function updateOrderPreview() {
    // Обновляем превью маршрута
    const fromCountry = document.querySelector('input[name="from_country"]');
    const fromCity = document.querySelector('input[name="from_city"]');
    const fromAddress = document.querySelector('input[name="from_address"]');
    const toCountry = document.querySelector('input[name="to_country"]');
    const toCity = document.querySelector('input[name="to_city"]');
    const toAddr = document.querySelector('input[name="to_address"]');

    const previewFrom = document.getElementById('preview_from');
    const previewTo = document.getElementById('preview_to');
    
    if (previewFrom && fromCountry && fromCity && fromAddress) {
        previewFrom.textContent = `${fromCountry.value}, ${fromCity.value}, ${fromAddress.value}`;
    }
    
    if (previewTo && toCountry && toCity && toAddr) {
        previewTo.textContent = `${toCountry.value}, ${toCity.value}, ${toAddr.value}`;
    }

    // Обновляем превью груза
    const cargoType = document.querySelector('select[name="cargo_type"]');
    const cargoWeight = document.querySelector('input[name="cargo_weight"]');
    const cargoDescription = document.querySelector('textarea[name="cargo_description"]');
    const previewCargo = document.getElementById('preview_cargo');
    
    if (previewCargo && cargoType && cargoWeight && cargoDescription) {
        previewCargo.innerHTML = `${cargoType.value}, ${cargoWeight.value} кг<br>${cargoDescription.value}`;
    }

    // Обновляем превью транспорта
    const previewVehicle = document.getElementById('preview_vehicle');
    if (previewVehicle && selectedVehicle) {
        previewVehicle.textContent = selectedVehicle.name;
    }

    // Обновляем превью услуг
    const selectedServices = [];
    const serviceCheckboxes = document.querySelectorAll('input[name="services[]"]:checked');
    const previewServices = document.getElementById('preview_services');
    
    if (serviceCheckboxes && previewServices) {
        serviceCheckboxes.forEach(service => {
            const serviceCart = service.closest('.service-cart');
            if (serviceCart) {
                const serviceNameElement = serviceCart.querySelector('.service-cart-name');
                if (serviceNameElement) {
                    selectedServices.push(serviceNameElement.textContent);
                }
            }
        });

        previewServices.textContent = selectedServices.length > 0 ? selectedServices.join(', ') : 'Услуги не выбраны';
    }

    // Обновляем превью стоимости
    const totalPrice = document.getElementById('total_price');
    const previewTotal = document.getElementById('preview_total');
    
    if (totalPrice && previewTotal) {
        previewTotal.textContent = parseFloat(totalPrice.value || 0).toLocaleString('ru-RU') + ' ₽';
    }
}

function resetOrderForm() {
    window.currentStep = 1;
    window.selectedVehicle = null;

    // Скрываем все шаги кроме первого
    const orderSteps = document.querySelectorAll('.order-step');
    if (orderSteps) {
        orderSteps.forEach(step => {
            step.classList.remove('active');
        });
    }
    
    const addressStep = document.getElementById('address_step');
    if (addressStep) {
        addressStep.classList.add('active');
    }

    // Сбрасываем форму
    const orderForm = document.getElementById('orderForm');
    if (orderForm) {
        orderForm.reset();
    }
    
    calculateTotal();
}
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
// Функция для открытия модального окна при клике на Личный кабинет
document.addEventListener('DOMContentLoaded', function() {
    // Проверяем, есть ли сообщения об ошибках или успехе
    const errorMsg = document.querySelector('[style*="color: red"]');
    const successMsg = document.querySelector('[style*="color: green"]');

    if (errorMsg || successMsg) {
        openModal();
    }

    // Устанавливаем значение для поля даты
    var dateControl = document.querySelector('input[type="date"]');
    if (dateControl) {
        dateControl.value = "2017-06-01";
        console.log(dateControl.value); // prints "2017-06-01"
        console.log(dateControl.valueAsNumber); // prints 1496275200000, a JavaScript timestamp (ms)
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
                if (!element.value.trim()) {
                    element.style.borderColor = 'red';
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

            if (!cargoType.value) {
                cargoType.style.borderColor = 'red';
                isValid = false;
            } else {
                cargoType.style.borderColor = '';
            }

            if (!cargoWeight.value.trim()) {
                cargoWeight.style.borderColor = 'red';
                isValid = false;
            } else {
                cargoWeight.style.borderColor = '';
            }

            if (!cargoDescription.value.trim()) {
                cargoDescription.style.borderColor = 'red';
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
    // Убираем выделение у всех транспортных средств
    document.querySelectorAll('.car-cart').forEach(cart => {
        cart.classList.remove('selected');
    });

    // Выделяем выбранное
    element.classList.add('selected');

    // Сохраняем данные выбранного транспорта
    selectedVehicle = {
        id: element.getAttribute('data-vehicle-id'),
        name: element.getAttribute('data-vehicle-name'),
        price: parseFloat(element.getAttribute('data-vehicle-price'))
    };

    // Заполняем скрытые поля
    document.getElementById('vehicle_id').value = selectedVehicle.id;
    document.getElementById('vehicle_name').value = selectedVehicle.name;
    document.getElementById('vehicle_price').value = selectedVehicle.price;

    // Пересчитываем итоговую стоимость
    calculateTotal();
}

function calculateTotal() {
    let vehiclePrice = selectedVehicle ? selectedVehicle.price : 0;
    let servicesPrice = 0;

    // Суммируем стоимость выбранных услуг
    document.querySelectorAll('input[name="services[]"]:checked').forEach(service => {
        servicesPrice += parseFloat(service.getAttribute('data-price'));
    });

    const totalPrice = vehiclePrice + servicesPrice;

    // Обновляем отображение цен
    document.getElementById('vehicle-price-display').textContent = vehiclePrice.toLocaleString('ru-RU') + ' ₽';
    document.getElementById('services-price-display').textContent = servicesPrice.toLocaleString('ru-RU') + ' ₽';
    document.getElementById('total-price-display').textContent = totalPrice.toLocaleString('ru-RU') + ' ₽';
    document.getElementById('total_price').value = totalPrice;
}

function updateOrderPreview() {
    // Обновляем превью маршрута
    const fromAddress = `${document.querySelector('input[name="from_country"]').value}, ${document.querySelector('input[name="from_city"]').value}, ${document.querySelector('input[name="from_address"]').value}`;
    const toAddress = `${document.querySelector('input[name="to_country"]').value}, ${document.querySelector('input[name="to_city"]').value}, ${document.querySelector('input[name="to_address"]').value}`;

    document.getElementById('preview_from').textContent = fromAddress;
    document.getElementById('preview_to').textContent = toAddress;

    // Обновляем превью груза
    const cargoType = document.querySelector('select[name="cargo_type"]').value;
    const cargoWeight = document.querySelector('input[name="cargo_weight"]').value;
    const cargoDescription = document.querySelector('textarea[name="cargo_description"]').value;

    document.getElementById('preview_cargo').innerHTML = `${cargoType}, ${cargoWeight} кг<br>${cargoDescription}`;

    // Обновляем превью транспорта
    if (selectedVehicle) {
        document.getElementById('preview_vehicle').textContent = selectedVehicle.name;
    }

    // Обновляем превью услуг
    const selectedServices = [];
    document.querySelectorAll('input[name="services[]"]:checked').forEach(service => {
        const serviceName = service.closest('.service-cart').querySelector('.service-cart-name').textContent;
        selectedServices.push(serviceName);
    });

    document.getElementById('preview_services').textContent = selectedServices.length > 0 ? selectedServices.join(', ') : 'Услуги не выбраны';

    // Обновляем превью стоимости
    const totalPrice = document.getElementById('total_price').value;
    document.getElementById('preview_total').textContent = parseFloat(totalPrice).toLocaleString('ru-RU') + ' ₽';
}

function resetOrderForm() {
    window.currentStep = 1;
    window.selectedVehicle = null;

    // Скрываем все шаги кроме первого
    document.querySelectorAll('.order-step').forEach(step => {
        step.classList.remove('active');
    });
    document.getElementById('address_step').classList.add('active');

    // Сбрасываем форму
    document.getElementById('orderForm').reset();
    calculateTotal();
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    // Устанавливаем текущий шаг
    currentStep = 1;

    // Добавляем обработчик изменения чекбоксов услуг для пересчета стоимости
    document.querySelectorAll('input[name="services[]"]').forEach(service => {
        service.addEventListener('change', calculateTotal);
    });

    // Устанавливаем минимальную дату для поля desired_date
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('desired_date').setAttribute('min', today);
});
// Админ-панель JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin JS loaded');
    
    // Только для админ-панели
    if (!document.querySelector('.account-container aside')) {
        return; // Не админ-панель
    }
    
    // Инициализация вкладок админ-панели
    initAdminTabs();
    
    // Инициализация фильтров заказов
    initOrderFilters();
    
    // Инициализация всех обработчиков
    initAllHandlers();
    
    // Загружаем начальные данные
    loadOrders();
});

// Функции для работы с модальными окнами
function openModal(modalId) {
    console.log('Opening modal:', modalId);
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    console.log('Closing modal:', modalId);
    document.getElementById(modalId).style.display = 'none';
}

// Закрытие модального окна при клике вне его
window.onclick = function(event) {
    if (event.target.classList.contains('admin-modal')) {
        event.target.style.display = 'none';
    }
}

function initAdminTabs() {
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
            const tabElement = document.getElementById(tabId);
            if (tabElement) {
                tabElement.classList.add('active');
            }
        });
    });
}

function initOrderFilters() {
    const applyBtn = document.querySelector('.apply');
    const resetBtn = document.querySelector('.reset');
    
    if (applyBtn && resetBtn) {
        applyBtn.addEventListener('click', function(e) {
            e.preventDefault();
            loadOrders();
        });
        
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            resetFilters();
        });
    }
}

function initAllHandlers() {
    // Обработчики для редактирования заказов и пользователей
    document.querySelectorAll('tr .fa-pen-to-square[data-id]').forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.getAttribute('data-id');
            const row = this.closest('tr');
            
            if (row && row.querySelector('.status')) {
                // Это заказ
                editOrder(id);
            } else {
                // Это пользователь
                editUser(id);
            }
        });
    });
    
    // Обработчики для удаления заказов и пользователей
    document.querySelectorAll('tr .fa-trash[data-id]').forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.getAttribute('data-id');
            const row = this.closest('tr');
            let type = 'order';
            
            if (row) {
                if (row.querySelector('.status')) {
                    type = 'order';
                } else {
                    type = 'user';
                }
            }
            
            if (confirm('Вы уверены, что хотите удалить этот элемент?')) {
                switch(type) {
                    case 'order':
                        deleteOrderAjax(id);
                        break;
                    case 'user':
                        deleteUserAjax(id);
                        break;
                }
            }
        });
    });
    
    // Обработчики для транспорта
    document.querySelectorAll('.edit-vehicle').forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log('Edit vehicle clicked');
            const id = this.getAttribute('data-id');
            console.log('Vehicle ID:', id);
            editVehicle(id);
        });
    });
    
    document.querySelectorAll('.delete-vehicle').forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.getAttribute('data-id');
            if (confirm('Вы уверены, что хотите удалить этот транспорт?')) {
                deleteVehicleAjax(id);
            }
        });
    });
    
    // Обработчики для услуг
    document.querySelectorAll('.edit-service').forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.getAttribute('data-id');
            editService(id);
        });
    });
    
    document.querySelectorAll('.delete-service').forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.getAttribute('data-id');
            if (confirm('Вы уверены, что хотите удалить эту услугу?')) {
                deleteServiceAjax(id);
            }
        });
    });
    
    // Кнопка добавления транспорта
    const addVehicleBtn = document.querySelector('.cars-cart-add');
    if (addVehicleBtn) {
        addVehicleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log('Add vehicle button clicked');
            
            // Сброс формы
            const vehicleForm = document.getElementById('vehicleForm');
            if (vehicleForm) vehicleForm.reset();
            
            document.getElementById('vehicle_id').value = '';
            document.getElementById('vehicle_name').value = '';
            document.getElementById('vehicle_capacity').value = '';
            document.getElementById('vehicle_price').value = '';
            document.getElementById('vehicle_status').value = 'available';

            document.getElementById('deleteVehicleBtn').style.display = 'none';
            
            const modalTitle = document.querySelector('#vehicleModal h3');
            if (modalTitle) modalTitle.textContent = 'Добавление транспорта';
            
            openModal('vehicleModal');
        });
    }
    
    // Кнопка добавления услуги
    const addServiceBtn = document.querySelector('.services-cart-add');
    if (addServiceBtn) {
        addServiceBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            
            // Сброс формы
            const serviceForm = document.getElementById('serviceForm');
            if (serviceForm) serviceForm.reset();
            
            document.getElementById('service_id').value = '';
            document.getElementById('service_name').value = '';
            document.getElementById('service_description').value = '';
            document.getElementById('service_price').value = '';
            document.getElementById('deleteServiceBtn').style.display = 'none';
            
            const modalTitle = document.querySelector('#serviceModal h3');
            if (modalTitle) modalTitle.textContent = 'Добавление услуги';
            
            openModal('serviceModal');
        });
    }
    
    // Назначаем обработчики отправки форм
    const orderForm = document.getElementById('orderForm');
    if (orderForm) {
        orderForm.addEventListener('submit', saveOrder);
    }
    
    const userForm = document.getElementById('userForm');
    if (userForm) {
        userForm.addEventListener('submit', saveUser);
    }
    
    const vehicleForm = document.getElementById('vehicleForm');
    if (vehicleForm) {
        vehicleForm.addEventListener('submit', saveVehicle);
    }
    
    const serviceForm = document.getElementById('serviceForm');
    if (serviceForm) {
        serviceForm.addEventListener('submit', saveService);
    }
}

// Функции для заказов
function editOrder(orderId) {
    console.log('Editing order ID:', orderId);
    
    fetch(`get_admin_data.php?type=order&id=${orderId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Ошибка сети: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Order data:', data);
            
            if (data.error) {
                alert('Ошибка: ' + data.error);
                return;
            }
            
            document.getElementById('order_id').value = data.id || '';
            document.getElementById('order_status').value = data.status || 'В обработке';
            document.getElementById('order_comment').value = data.comment || '';
            
            openModal('orderModal');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ошибка загрузки данных заказа: ' + error.message);
        });
}

function saveOrder(event) {
    event.preventDefault();
    console.log('Saving order...');
    
    const formData = new FormData(event.target);
    formData.append('action', 'update_order_status');
    
    fetch('admin_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Ошибка сервера: ' + response.status);
        }
        return response.text();
    })
    .then(data => {
        alert('Статус заказа обновлен!');
        closeModal('orderModal');
        loadOrders();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ошибка сохранения: ' + error.message);
    });
}

function deleteOrder() {
    const orderId = document.getElementById('order_id').value;
    deleteOrderAjax(orderId);
}

function deleteOrderAjax(orderId) {
    const formData = new FormData();
    formData.append('action', 'delete_order');
    formData.append('order_id', orderId);
    
    fetch('admin_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert('Заказ удален!');
        closeModal('orderModal');
        loadOrders();
    })
    .catch(error => console.error('Error:', error));
}

// Функции для пользователей
function editUser(userId) {
    console.log('Editing user ID:', userId);
    
    fetch(`get_admin_data.php?type=user&id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Ошибка: ' + data.error);
                return;
            }
            
            document.getElementById('user_id').value = data.id || '';
            document.getElementById('user_username').value = data.username || '';
            document.getElementById('user_email').value = data.email || '';
            document.getElementById('user_phone').value = data.phone || '';
            document.getElementById('user_full_name').value = data.full_name || '';
            document.getElementById('user_role').value = data.role || 'user';
            
            openModal('userModal');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ошибка загрузки данных пользователя: ' + error.message);
        });
}

function saveUser(event) {
    event.preventDefault();
    console.log('Saving user...');
    
    const formData = new FormData(event.target);
    formData.append('action', 'update_user');
    
    fetch('admin_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert('Данные пользователя обновлены!');
        closeModal('userModal');
        location.reload();
    })
    .catch(error => console.error('Error:', error));
}

function deleteUser() {
    const userId = document.getElementById('user_id').value;
    deleteUserAjax(userId);
}

function deleteUserAjax(userId) {
    const formData = new FormData();
    formData.append('action', 'delete_user');
    formData.append('user_id', userId);
    
    fetch('admin_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert('Пользователь удален!');
        closeModal('userModal');
        location.reload();
    })
    .catch(error => console.error('Error:', error));
}

// Функции для транспорта
function editVehicle(vehicleId) {
    console.log('Editing vehicle ID:', vehicleId);
    
    fetch(`get_admin_data.php?type=vehicle&id=${vehicleId}`)
        .then(response => response.json())
        .then(data => {
            console.log('Vehicle data:', data);
            
            if (data && data.error) {
                alert('Ошибка: ' + data.error);
                return;
            }
            
            if (!data) {
                alert('Транспорт не найден');
                return;
            }
            
            // Заполняем форму
            document.getElementById('vehicle_id').value = data.id || '';
            document.getElementById('vehicle_name').value = data.name || '';
            
            // Обрабатываем capacity
            let capacity = data.capacity || '0';
            if (typeof capacity === 'string') {
                capacity = capacity.replace(/\D/g, '');
                capacity = parseInt(capacity) || 0;
            }
            document.getElementById('vehicle_capacity').value = capacity;
            
            document.getElementById('vehicle_price').value = data.price || 0;
            document.getElementById('vehicle_status').value = data.status || 'available';

            
            // Показываем кнопку удаления
            document.getElementById('deleteVehicleBtn').style.display = 'inline-block';
            
            // Меняем заголовок
            const modalTitle = document.querySelector('#vehicleModal h3');
            if (modalTitle) modalTitle.textContent = 'Редактирование транспорта';
            
            openModal('vehicleModal');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ошибка загрузки данных транспорта: ' + error.message);
        });
}

function saveVehicle(event) {
    event.preventDefault();
    console.log('Saving vehicle...');
    
    const formData = new FormData(event.target);
    const vehicleId = document.getElementById('vehicle_id').value;
    
    // Убираем description из formData так как его нет в таблице
    if (formData.has('description')) {
        formData.delete('description');
    }
    
    if (vehicleId) {
        formData.append('action', 'update_vehicle');
        formData.append('vehicle_id', vehicleId);
    } else {
        formData.append('action', 'add_vehicle');
    }
    
    console.log('Sending form data:', Object.fromEntries(formData));
    
    fetch('admin_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('Ошибка сервера: ' + response.status);
        }
        return response.text();
    })
    .then(data => {
        console.log('Response data:', data);
        alert(vehicleId ? 'Транспорт обновлен!' : 'Транспорт добавлен!');
        closeModal('vehicleModal');
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ошибка сохранения транспорта: ' + error.message);
    });
}

function deleteVehicle() {
    const vehicleId = document.getElementById('vehicle_id').value;
    if (confirm('Вы уверены, что хотите удалить этот транспорт?')) {
        deleteVehicleAjax(vehicleId);
    }
}

function deleteVehicleAjax(vehicleId) {
    const formData = new FormData();
    formData.append('action', 'delete_vehicle');
    formData.append('vehicle_id', vehicleId);
    
    fetch('admin_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert('Транспорт удален!');
        closeModal('vehicleModal');
        location.reload();
    })
    .catch(error => console.error('Error:', error));
}

// Функции для услуг
function editService(serviceId) {
    fetch(`get_admin_data.php?type=service&id=${serviceId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Ошибка: ' + data.error);
                return;
            }
            
            document.getElementById('service_id').value = data.id;
            document.getElementById('service_name').value = data.name;
            document.getElementById('service_description').value = data.description || '';
            document.getElementById('service_price').value = data.price;
            document.getElementById('deleteServiceBtn').style.display = 'inline-block';
            
            const modalTitle = document.querySelector('#serviceModal h3');
            if (modalTitle) modalTitle.textContent = 'Редактирование услуги';
            
            openModal('serviceModal');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ошибка загрузки данных услуги: ' + error.message);
        });
}

function saveService(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const serviceId = document.getElementById('service_id').value;
    
    if (serviceId) {
        formData.append('action', 'update_service');
        formData.append('service_id', serviceId);
    } else {
        formData.append('action', 'add_service');
    }
    
    fetch('admin_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(serviceId ? 'Услуга обновлена!' : 'Услуга добавлена!');
        closeModal('serviceModal');
        location.reload();
    })
    .catch(error => console.error('Error:', error));
}

function deleteService() {
    const serviceId = document.getElementById('service_id').value;
    if (confirm('Вы уверены, что хотите удалить эту услугу?')) {
        deleteServiceAjax(serviceId);
    }
}

function deleteServiceAjax(serviceId) {
    const formData = new FormData();
    formData.append('action', 'delete_service');
    formData.append('service_id', serviceId);
    
    fetch('admin_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert('Услуга удалена!');
        closeModal('serviceModal');
        location.reload();
    })
    .catch(error => console.error('Error:', error));
}

// Функции для фильтрации заказов
function loadOrders() {
    const statusFilter = document.getElementById('pet-select');
    const dateFrom = document.getElementById('date-from');
    const dateTo = document.getElementById('date-to');
    
    if (!statusFilter || !dateFrom || !dateTo) {
        console.log('Фильтры заказов не найдены');
        return;
    }
    
    const status = statusFilter.value;
    const dateFromValue = dateFrom.value;
    const dateToValue = dateTo.value;
    
    let url = `get_admin_data.php?type=orders`;
    if (status && status !== 'All-statuses') {
        url += `&status=${encodeURIComponent(status)}`;
    }
    if (dateFromValue) {
        url += `&date_from=${dateFromValue}`;
    }
    if (dateToValue) {
        url += `&date_to=${dateToValue}`;
    }
    
    console.log('Loading orders from:', url);
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data && typeof data === 'object') {
                renderOrdersTable(data);
            } else {
                console.error('Invalid data received:', data);
                renderOrdersTable([]);
            }
        })
        .catch(error => {
            console.error('Ошибка загрузки заказов:', error);
            renderOrdersTable([]);
        });
}

function renderOrdersTable(orders) {
    const tbody = document.querySelector('table.orders tbody');
    if (!tbody) {
        console.log('Таблица заказов не найдена');
        return;
    }
    
    tbody.innerHTML = '';
    
    if (!Array.isArray(orders)) {
        console.error('orders не является массивом:', orders);
        orders = [];
    }
    
    if (orders.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td colspan="6" style="text-align: center; padding: 20px;">
                Нет заказов для отображения
            </td>
        `;
        tbody.appendChild(row);
        return;
    }
    
    orders.forEach(order => {
        const row = document.createElement('tr');
        
        let statusClass = 'Waiting';
        if (order.status === 'В пути') statusClass = 'On-the-way';
        if (order.status === 'Доставлен') statusClass = 'Delivered';
        
        row.innerHTML = `
            <td>#${order.id}</td>
            <td>${escapeHtml(order.full_name || order.username || 'Не указано')}</td>
            <td>${escapeHtml(order.from_address || '')} → ${escapeHtml(order.to_address || '')}</td>
            <td class="status">
                <span class="${statusClass}">${escapeHtml(order.status || 'Не указан')}</span>
            </td>
            <td>${formatNumber(order.total_price || 0)} ₽</td>
            <td class="actions">
                <i class="fa-solid fa-pen-to-square" data-id="${order.id}"></i>
                <i class="fa-solid fa-trash" data-id="${order.id}"></i>
            </td>
        `;
        
        tbody.appendChild(row);
    });
    
    // Повторно инициализируем обработчики для новых элементов
    initAllHandlers();
}

function resetFilters() {
    const statusFilter = document.getElementById('pet-select');
    const dateFrom = document.getElementById('date-from');
    const dateTo = document.getElementById('date-to');
    
    if (statusFilter) statusFilter.value = 'All-statuses';
    if (dateFrom) dateFrom.value = '';
    if (dateTo) dateTo.value = '';
    
    loadOrders();
}

// Вспомогательные функции
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
}
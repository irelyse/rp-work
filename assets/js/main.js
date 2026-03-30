/**
 * Classic Academy - Main Management Script
 * Handles API calls, modals, and user interactions
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('Main Script Loaded');
    
    // Dismiss Preloader strictly when window fully loads
    window.addEventListener('load', () => {
        const preloader = document.getElementById('global-preloader');
        if (preloader) {
            preloader.classList.add('preloader-hidden');
            setTimeout(() => preloader.remove(), 500); // Wait for transition fade entirely
        }
    });
    
    // Initialize Lucide Icons if available
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Modal Close logic
    const closeModal = () => {
        document.querySelector('.modal-overlay')?.classList.remove('active');
    }

    document.querySelectorAll('.close-modal, .btn-secondary').forEach(btn => {
        btn.addEventListener('click', closeModal);
    });

    window.addEventListener('click', (e) => {
        if (e.target.classList.contains('modal-overlay')) {
            closeModal();
        }
    });

    // Auto-Pagination for Dashboard Tables
    const tables = document.querySelectorAll('.table-container table');
    tables.forEach(table => {
        const tbody = table.querySelector('tbody');
        if(!tbody) return;
        const rows = Array.from(tbody.querySelectorAll('tr'));
        if (rows.length === 0 || rows[0].querySelector('td[colspan]')) return; // empty state
        
        const rowsPerPage = 10;
        if (rows.length <= rowsPerPage) return;
        
        let currentPage = 1;
        const totalPages = Math.ceil(rows.length / rowsPerPage);
        
        // Setup container
        const tableContainer = table.parentElement;
        const paginationContainer = document.createElement('div');
        paginationContainer.className = 'pagination-controls';
        tableContainer.appendChild(paginationContainer);
        
        const renderTable = (page) => {
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            
            rows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });
            renderControls(page);
        };
        
        const renderControls = (page) => {
            paginationContainer.innerHTML = '';
            
            const prev = document.createElement('button');
            prev.textContent = 'Previous';
            prev.className = 'page-btn';
            prev.disabled = page === 1;
            prev.onclick = () => { currentPage--; renderTable(currentPage); };
            paginationContainer.appendChild(prev);
            
            // Show only pages around current
            let startPage = Math.max(1, page - 2);
            let endPage = Math.min(totalPages, page + 2);
            
            for(let i=startPage; i<=endPage; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = `page-btn ${i === page ? 'active' : ''}`;
                btn.onclick = () => { currentPage = i; renderTable(currentPage); };
                paginationContainer.appendChild(btn);
            }
            
            const next = document.createElement('button');
            next.textContent = 'Next';
            next.className = 'page-btn';
            next.disabled = page === totalPages;
            next.onclick = () => { currentPage++; renderTable(currentPage); };
            paginationContainer.appendChild(next);
        };
        
        renderTable(currentPage);
    });
});

/**
 * Show a floating toast notification
 */
function showToast(message, type = 'primary') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.add('show'), 100);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}

/**
 * Generic Delete Item handler
 */
async function deleteItem(resource, id) {
    if (!confirm(`Are you sure you want to delete this ${resource}?`)) return;

    try {
        const response = await fetch(`backend/api.php/${resource}/${id}`, {
            method: 'DELETE'
        });
        const result = await response.json();
        
        if (result.error) {
            showToast(result.error, 'error');
        } else {
            showToast('Item deleted successfully', 'success');
            // Refresh the page or remove the row
            setTimeout(() => window.location.reload(), 800);
        }
    } catch (error) {
        showToast('Connection error', 'error');
    }
}

/**
 * Open Modal and populate for editing
 * This is a generic implementation - specific pages might override or handle differently
 */
function openEditModal(resource, id, data) {
    const overlay = document.querySelector('.modal-overlay');
    if (!overlay) return;
    
    const title = document.querySelector('.modal-header h2');
    const body = document.querySelector('.modal-body');
    const saveBtn = document.querySelector('.btn-save');
    
    title.textContent = `Edit ${resource.charAt(0).toUpperCase() + resource.slice(1)}`;
    
    // Dynamically extract keys from data object
    const keys = Object.keys(data);
    
    const excludeKeys = ['id', 'student_count', 'balance'];
    if (resource !== 'routes') excludeKeys.push('route_name');
    if (resource !== 'parents') excludeKeys.push('parent_name', 'parent_phone');
    if (resource !== 'students') excludeKeys.push('student_name');
    
    // Use an async function to build the body
    const buildBody = async () => {
        let formHTML = '';
        for (const key of keys) {
            if (excludeKeys.includes(key)) continue;
            
            const label = key.replace('_', ' ').split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
            
            if (key === 'parent_id') {
                const parents = await fetch('backend/api.php/parents').then(r => r.json());
                let options = parents.map(p => `<option value="${p.parent_id}" ${p.parent_id == data[key] ? 'selected' : ''}>${p.full_name}</option>`).join('');
                formHTML += `<div class="form-group"><label>${label}</label><select id="edit_${key}" class="input-field">${options}</select></div>`;
            } else if (key === 'route_id') {
                const routes = await fetch('backend/api.php/routes').then(r => r.json());
                let options = routes.map(r => `<option value="${r.id}" ${r.id == data[key] ? 'selected' : ''}>${r.route_name}</option>`).join('');
                formHTML += `<div class="form-group"><label>${label}</label><select id="edit_${key}" class="input-field">${options}</select></div>`;
            } else if (key === 'student_id') {
                const students = await fetch('backend/api.php/students').then(r => r.json());
                let options = students.map(s => `<option value="${s.id}" ${s.id == data[key] ? 'selected' : ''}>${s.fullname}</option>`).join('');
                formHTML += `<div class="form-group"><label>${label}</label><select id="edit_${key}" class="input-field">${options}</select></div>`;
            } else if (key === 'uses_transport') {
                formHTML += `
                    <div class="form-group">
                        <label>${label}</label>
                        <select id="edit_${key}" class="input-field">
                            <option value="1" ${data[key] == 1 ? 'selected' : ''}>Yes</option>
                            <option value="0" ${data[key] == 0 ? 'selected' : ''}>No</option>
                        </select>
                    </div>
                `;
            } else if (key !== 'id') {
                formHTML += `
                    <div class="form-group">
                        <label>${label}</label>
                        <input type="text" id="edit_${key}" value="${data[key] || ''}" class="input-field">
                    </div>
                `;
            }
        }
        body.innerHTML = formHTML;
    };

    buildBody();
    overlay.classList.add('active');

    
    // Set up save logic
    saveBtn.onclick = async () => {
        const updatedData = {};
        for (const key of keys) {
            if (key === 'id' || key === 'parent_id' || key === 'student_id' || key === 'route_id' || key === 'parent_id') {
               const el = document.getElementById(`edit_${key}`);
               if (el) updatedData[key] = el.value;
            } else {
               const el = document.getElementById(`edit_${key}`);
               if (el) updatedData[key] = el.value;
            }
        }
        
        // Simpler approach: gather all fields that WERE rendered
        const inputs = body.querySelectorAll('.input-field');
        const finalData = {};
        inputs.forEach(input => {
            const key = input.id.replace('edit_', '');
            finalData[key] = input.value;
        });

        
        try {
            const response = await fetch(`backend/api.php/${resource}/${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(finalData)
            });
            const result = await response.json();
            
            if (result.error) {
                showToast(result.error, 'error');
            } else {
                showToast('Updated successfully', 'success');
                overlay.classList.remove('active');
                setTimeout(() => window.location.reload(), 800);
            }
        } catch (error) {
            showToast('Update failed', 'error');
        }
    };
}

/**
 * Open Modal for Adding new item
 */
function openAddModal(resource, fields) {
    const overlay = document.querySelector('.modal-overlay');
    if (!overlay) return;
    
    const title = document.querySelector('.modal-header h2');
    const body = document.querySelector('.modal-body');
    const saveBtn = document.querySelector('.btn-save');
    
    title.textContent = `Register New ${resource.charAt(0).toUpperCase() + resource.slice(1)}`;
    body.innerHTML = '<div style="text-align:center; padding: 20px;">Loading form fields...</div>';
    
    const buildAddBody = async () => {
        let formHTML = '';
        for (const field of fields) {
            const label = field.replace('_', ' ').split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
            
            if (field === 'parent_id') {
                const parents = await fetch('backend/api.php/parents').then(r => r.json());
                let options = parents.map(p => `<option value="${p.parent_id}">${p.full_name}</option>`).join('');
                formHTML += `<div class="form-group"><label>${label}</label><select id="add_${field}" class="input-field">${options}</select></div>`;
            } else if (field === 'route_id') {
                const routes = await fetch('backend/api.php/routes').then(r => r.json());
                let options = routes.map(r => `<option value="${r.id}">${r.route_name}</option>`).join('');
                formHTML += `<div class="form-group"><label>${label}</label><select id="add_${field}" class="input-field">${options}</select></div>`;
            } else if (field === 'student_id') {
                const students = await fetch('backend/api.php/students').then(r => r.json());
                let options = students.map(s => `<option value="${s.id}">${s.fullname}</option>`).join('');
                formHTML += `<div class="form-group"><label>${label}</label><select id="add_${field}" class="input-field">${options}</select></div>`;
            } else if (field === 'payment_method') {
                formHTML += `
                    <div class="form-group">
                        <label>${label}</label>
                        <select id="add_${field}" class="input-field">
                            <option value="Cash">Cash</option>
                            <option value="Momo">MoMo (Mobile Money)</option>
                            <option value="Bank">Bank Transfer</option>
                            <option value="Card">Credit/Debit Card</option>
                        </select>
                    </div>
                `;
            } else if (field.includes('date')) {
                const today = new Date().toISOString().split('T')[0];
                formHTML += `
                    <div class="form-group">
                        <label>${label}</label>
                        <input type="date" id="add_${field}" value="${today}" class="input-field">
                    </div>
                `;
            } else if (field === 'uses_transport') {
                formHTML += `
                    <div class="form-group">
                        <label>${label}</label>
                        <select id="add_${field}" class="input-field">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                `;
            } else {
                formHTML += `
                    <div class="form-group">
                        <label>${label}</label>
                        <input type="text" id="add_${field}" placeholder="Enter ${label}" class="input-field">
                    </div>
                `;
            }
        }
        body.innerHTML = formHTML;
    };

    buildAddBody();
    overlay.classList.add('active');

    
    saveBtn.onclick = async () => {
        const data = {};
        fields.forEach(field => {
            data[field] = document.getElementById(`add_${field}`).value;
        });
        
        try {
            const response = await fetch(`backend/api.php/${resource}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            
            if (result.error) {
                showToast(result.error, 'error');
            } else {
                showToast('Registered successfully', 'success');
                overlay.classList.remove('active');
                setTimeout(() => window.location.reload(), 800);
            }
        } catch (error) {
            showToast('Submission failed', 'error');
        }
    };
}

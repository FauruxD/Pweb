// Tab Management
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            
            // Remove active class from all tabs
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            document.getElementById(tabName + '-content').classList.add('active');
            
            // Load data based on tab
            if (tabName === 'film') {
                loadFilms();
            } else if (tabName === 'user') {
                loadUsers();
            }
        });
    });

    // Load films by default
    loadFilms();
});

// Load Films
function loadFilms() {
    fetch('/admin/dashboard/getFilms', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('film-table-body');
        
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="loading">Tidak ada data film</td></tr>';
            return;
        }

        let html = '';
        data.forEach(film => {
            html += `
                <tr>
                    <td>${film.id}</td>
                    <td>${film.title}</td>
                    <td>${film.genre}</td>
                    <td>${film.year}</td>
                    <td>
                        <button class="btn-delete" onclick="deleteFilm(${film.id})">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('film-table-body').innerHTML = 
            '<tr><td colspan="5" class="loading">Error loading data</td></tr>';
    });
}

// Load Users
function loadUsers() {
    fetch('/admin/dashboard/getUsers', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('user-table-body');
        
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="loading">Tidak ada data user</td></tr>';
            return;
        }

        let html = '';
        data.forEach(user => {
            const date = new Date(user.created_at);
            const formattedDate = date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            });

            html += `
                <tr>
                    <td>${user.id}</td>
                    <td>${user.email}</td>
                    <td>${user.role}</td>
                    <td>${formattedDate}</td>
                    <td>
                        <button class="btn-delete" onclick="deleteUser(${user.id})">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('user-table-body').innerHTML = 
            '<tr><td colspan="5" class="loading">Error loading data</td></tr>';
    });
}

// Modal Functions
function showAddFilmModal() {
    document.getElementById('addFilmModal').classList.add('active');
}

function closeAddFilmModal() {
    document.getElementById('addFilmModal').classList.remove('active');
    document.getElementById('addFilmForm').reset();
}

// Add Film Form Submit
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addFilmForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('.btn-submit');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
            
            fetch('/admin/dashboard/addFilm', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Film berhasil ditambahkan!');
                    closeAddFilmModal();
                    loadFilms();
                } else {
                    alert('Error: ' + (data.message || 'Gagal menambahkan film'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menambahkan film');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Simpan';
            });
        });
    }
});

// Delete Film
function deleteFilm(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus film ini?')) {
        return;
    }
    
    fetch(`/admin/dashboard/deleteFilm/${id}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Film berhasil dihapus!');
            loadFilms();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus film');
    });
}

// Delete User
function deleteUser(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus user ini?')) {
        return;
    }
    
    fetch(`/admin/dashboard/deleteUser/${id}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('User berhasil dihapus!');
            loadUsers();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus user');
    });
}

// Close modal when clicking outside
window.addEventListener('click', function(e) {
    const modal = document.getElementById('addFilmModal');
    if (e.target === modal) {
        closeAddFilmModal();
    }
});
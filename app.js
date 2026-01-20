document.addEventListener('DOMContentLoaded', () => {
    checkAuth();
    loadCategories();
    loadInternships();
});

async function checkAuth() {
    try {
        const response = await fetch('api/check_auth.php');
        const data = await response.json();

        if (!data.authenticated) {
            window.location.href = 'login.html';
            return;
        }

        // Show user greeting
        document.getElementById('userGreeting').classList.remove('d-none');
        document.getElementById('userName').textContent = data.username;

        // Hide overlay
        document.getElementById('authCheckOverlay').style.display = 'none';

    } catch (error) {
        console.error('Auth check failed:', error);
        window.location.href = 'login.html';
    }
}

let currentCategory = '';

async function loadCategories() {
    try {
        const response = await fetch('api/get_categories.php');
        const categories = await response.json();
        const container = document.getElementById('categoryContainer');

        categories.forEach(cat => {
            const btn = document.createElement('button');
            btn.className = 'category-chip';
            btn.innerHTML = `<i class="${cat.icon}"></i> ${cat.name}`;
            btn.onclick = () => filterByCategory(cat.id, btn);
            container.appendChild(btn);
        });
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

async function loadInternships(search = '', category = '') {
    const container = document.getElementById('internshipContainer');
    container.innerHTML = '<div class="loading">Loading internships...</div>';

    try {
        const response = await fetch(`api/get_internships.php?search=${encodeURIComponent(search)}&category=${category}`);
        const internships = await response.json();

        container.innerHTML = '';

        if (internships.length === 0) {
            container.innerHTML = '<p>No internships found matching your criteria.</p>';
            return;
        }

        internships.forEach(job => {
            const col = document.createElement('div');
            col.className = 'col-md-6 col-lg-4';
            col.innerHTML = `
                <div class="job-card d-flex flex-column">
                    <span class="stipend-tag">${job.stipend || 'Not Specified'}</span>
                    <div class="mb-3">
                        <h3>${job.title}</h3>
                        <span class="company">${job.company}</span>
                    </div>
                    <div class="job-details mt-auto">
                        <div><i class="fas fa-map-marker-alt"></i> ${job.location}</div>
                        <div><i class="fas fa-calendar-alt"></i> ${job.duration}</div>
                        <div><i class="fas fa-tag"></i> ${job.category_name}</div>
                    </div>
                    <a href="apply.html?id=${job.id}" class="btn apply-btn py-2 mt-3">Apply Now</a>
                </div>
            `;
            container.appendChild(col);
        });
    } catch (error) {
        container.innerHTML = '<p>Error loading internships. Please try again later.</p>';
        console.error('Error loading internships:', error);
    }
}

function searchInternships() {
    const query = document.getElementById('jobSearch').value;
    loadInternships(query, currentCategory);
}

function filterByCategory(categoryId, element) {
    currentCategory = categoryId;

    // Update active state of chips
    document.querySelectorAll('.category-chip').forEach(chip => {
        chip.classList.remove('active');
    });

    if (element) {
        element.classList.add('active');
    } else {
        document.querySelector('.category-chip').classList.add('active');
    }

    const query = document.getElementById('jobSearch').value;
    loadInternships(query, categoryId);
}

// Add enter key listener for search
document.getElementById('jobSearch').addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        searchInternships();
    }
});

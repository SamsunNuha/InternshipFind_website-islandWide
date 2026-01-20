<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Internship - LankaIntern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <!-- Auth Check Overlay -->
    <div id="authCheckOverlay" style="position: fixed; inset: 0; background: white; z-index: 9999; display: flex; align-items: center; justify-content: center;">
        <div class="spinner-border text-teal" style="color: var(--primary-color)" role="status"></div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand logo" href="index.html">Lanka<span>Intern</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                    <li class="nav-item d-none" id="userGreeting"><span class="nav-link text-white fw-bold">Hi, <span id="userName">User</span></span></li>
                    <li class="nav-item"><a class="btn btn-outline-light ms-lg-3 px-4" href="api/logout.php" id="logoutBtn">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <!-- Job Details Card -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow border-0">
                    <div class="card-header" style="background: var(--primary-color); color: white;">
                        <h5 class="mb-0"><i class="fas fa-briefcase"></i> Job Details</h5>
                    </div>
                    <div class="card-body" id="jobDetails">
                        <div class="text-center">
                            <div class="spinner-border text-teal" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Form -->
            <div class="col-lg-8">
                <div class="card shadow border-0">
                    <div class="card-header" style="background: var(--primary-color); color: white;">
                        <h4 class="mb-0"><i class="fas fa-file-alt"></i> Application Form</h4>
                    </div>
                    <div class="card-body">
                        <form id="applicationForm" enctype="multipart/form-data">
                            <input type="hidden" name="internship_id" id="internship_id">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" name="full_name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address *</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="phone" class="form-control" placeholder="+94 XX XXX XXXX">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Qualifications / Education *</label>
                                <textarea name="qualifications" class="form-control" rows="3" required placeholder="e.g., BSc in Computer Science, University of Colombo"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Upload Resume (PDF only) *</label>
                                <input type="file" name="resume" class="form-control" accept=".pdf" required>
                                <small class="text-muted">Maximum file size: 5MB</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Cover Letter (Optional)</label>
                                <textarea name="cover_letter" class="form-control" rows="4" placeholder="Tell us why you're interested in this position..."></textarea>
                            </div>

                            <button type="submit" class="btn w-100 text-white py-3" style="background: var(--primary-color); font-weight: 600;">
                                <i class="fas fa-paper-plane"></i> Submit Application
                            </button>
                        </form>
                        <div id="message" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentUser = null;

        async function checkAuth() {
            try {
                const response = await fetch('api/check_auth.php');
                const data = await response.json();
                
                if (!data.authenticated) {
                    window.location.href = 'login.html';
                    return;
                }

                currentUser = data;
                document.getElementById('userGreeting').classList.remove('d-none');
                document.getElementById('userName').textContent = data.username;
                document.getElementById('authCheckOverlay').style.display = 'none';

            } catch (error) {
                console.error('Auth check failed:', error);
                window.location.href = 'login.html';
            }
        }

        async function loadJobDetails() {
            const urlParams = new URLSearchParams(window.location.search);
            const jobId = urlParams.get('id');
            
            if (!jobId) {
                window.location.href = 'index.html';
                return;
            }

            document.getElementById('internship_id').value = jobId;

            try {
                const response = await fetch(`api/get_internships.php`);
                const jobs = await response.json();
                const job = jobs.find(j => j.id == jobId);

                if (!job) {
                    document.getElementById('jobDetails').innerHTML = '<p class="text-danger">Job not found</p>';
                    return;
                }

                document.getElementById('jobDetails').innerHTML = `
                    <h5 class="text-primary">${job.title}</h5>
                    <p class="mb-2"><i class="fas fa-building text-muted"></i> <strong>${job.company}</strong></p>
                    <p class="mb-2"><i class="fas fa-map-marker-alt text-muted"></i> ${job.location}</p>
                    <p class="mb-2"><i class="fas fa-money-bill-wave text-muted"></i> ${job.stipend || 'Not Specified'}</p>
                    <p class="mb-2"><i class="fas fa-calendar text-muted"></i> ${job.duration}</p>
                    <p class="mb-0"><i class="fas fa-tag text-muted"></i> ${job.category_name}</p>
                    ${job.description ? `<hr><p class="small">${job.description}</p>` : ''}
                `;
            } catch (error) {
                document.getElementById('jobDetails').innerHTML = '<p class="text-danger">Error loading job details</p>';
            }
        }

        document.getElementById('applicationForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const msg = document.getElementById('message');
            const submitBtn = e.target.querySelector('button[type="submit"]');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Submitting...';
            msg.innerHTML = '';

            try {
                const response = await fetch('api/submit_application.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    msg.innerHTML = `<div class="alert alert-success"><i class="fas fa-check-circle"></i> ${result.message}</div>`;
                    e.target.reset();
                    setTimeout(() => window.location.href = 'index.html', 2000);
                } else {
                    msg.innerHTML = `<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> ${result.message}</div>`;
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Application';
                }
            } catch (error) {
                msg.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Application';
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            checkAuth();
            loadJobDetails();
        });
    </script>
</body>
</html>

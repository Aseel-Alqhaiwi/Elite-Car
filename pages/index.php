<?php include '../includes/header.php'; ?>
<?php include '../config.php'; ?>
<style>
/* نفس الـ CSS كما هو */
.card.car-card {
    background-color: #212529;
    border: none;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    color: #fff;
    border-radius: 12px;
    font-size: 0.9rem;
    box-shadow: 0 0 10px rgba(0,0,0,0.4);
    overflow: hidden;
}
body, html {
    background-color: #121416;
    color: #fff;
}
#resultsContainer {
    background: linear-gradient(to bottom, #1a1d20, #121416);
    padding: 3rem;
    border-radius: 20px;
    margin-top: 2rem;
    box-shadow: 0 0 25px rgba(0, 0, 0, 0.6);
}
.card.car-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 6px 20px rgba(255, 75, 75, 0.2);
}
.card.car-card img {
    height: 170px;
    object-fit: cover;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}
.card.car-card .card-title {
    color: #ff4d4d;
    font-size: 1rem;
}
.card.car-card .btn-primary {
    background-color: #0d6efd;
    border: none;
}
.card.car-card .btn-primary:hover {
    background-color: #084298;
}
.form-control, .form-select {
    background-color: #1e1e1e;
    color: #f1f1f1;
    border: 1px solid #555;
}
.form-control::placeholder {
    color: #aaa;
}
.btn-outline-light {
    color: #fff;
    border-color: #ccc;
}
.btn-outline-light:hover {
    background-color: #fff;
    color: #000;
}
.pagination .page-item .page-link {
    background-color: #1e1e1e;
    border: 1px solid #444;
    color: #fff;
}
.pagination .page-item.active .page-link {
    background-color: #e63946;
    border-color: #e63946;
}
.card.car-card .btn-contact {
    background-color: #e63946;
    color: #fff;
    border: none;
    margin-top: 8px;
    transition: background-color 0.3s ease;
}
.card.car-card .btn-contact:hover {
    background-color: #c9303e;
}
</style>

<section class="py-5 text-center bg-dark text-white">
    <div class="container">
        <h1 class="display-5 fw-bold">Find Your Dream Car</h1>
        <form id="filterForm" class="row justify-content-center mt-4" method="GET">
            <div class="col-md-2 mb-2">
                <input type="text" name="search" class="form-control" placeholder="Search name or brand">
            </div>
            <div class="col-md-2 mb-2">
                <select name="brand" class="form-select">
                    <option value="">All Brands</option>
                    <option value="Nissan">Nissan</option>
                    <option value="Toyota">Toyota</option>
                    <option value="Honda">Honda</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <input type="number" name="min_price" class="form-control" placeholder="Min Price">
            </div>
            <div class="col-md-2 mb-2">
                <input type="number" name="max_price" class="form-control" placeholder="Max Price">
            </div>
            <div class="col-md-3 mb-2 d-flex gap-2">
                <button type="submit" class="btn btn-danger w-100">Search</button>
                <button type="button" id="resetFilters" class="btn btn-outline-light">Reset</button>
            </div>
        </form>
    </div>
</section>

<div class="container py-5" id="resultsContainer">
    <div class="text-center text-light">Loading cars...</div>
</div>

<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-light">
      <form id="contactForm">
        <div class="modal-header">
          <h5 class="modal-title">Contact Seller</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="car_name" id="car_name_input">
            <div class="mb-3"><label>Your Name</label><input type="text" class="form-control" name="name" required></div>
            <div class="mb-3"><label>Your Email</label><input type="email" class="form-control" name="email" required></div>
           <div class="mb-3">
  <label>Phone</label>
  <input type="text" class="form-control" name="phone" id="phoneInput" inputmode="numeric" pattern="[0-9]*" maxlength="15" required>
</div>

            <div class="mb-3">
                <label>Preferred Time</label>
                <select class="form-select" name="preferred_time" required>
                    <option value="">-- Select --</option>
                    <option>Morning</option><option>Noon</option>
                    <option>Afternoon</option><option>Evening</option>
                    <option>Any time</option>
                </select>
            </div>
            <div class="mb-3"><label>Message</label><textarea class="form-control" name="message" rows="3" required></textarea></div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Send</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Car Details Modal -->
<div class="modal fade" id="carDetailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header">
        <h5 class="modal-title">Car Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="carDetailsContent">
        <div class="text-center text-muted">Loading...</div>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function fetchCars(page = 1) {
    const form = document.getElementById('filterForm');
    const params = new URLSearchParams(new FormData(form));
    params.append('page', page);
    const resultsContainer = document.getElementById('resultsContainer');
    resultsContainer.innerHTML = '<div class="text-center text-light">Loading cars...</div>';

    fetch('ajax/fetch_cars.php?' + params.toString())
        .then(res => res.text())
        .then(html => resultsContainer.innerHTML = html);
}

document.getElementById('filterForm').addEventListener('submit', function (e) {
    e.preventDefault();
    fetchCars();
});
document.getElementById('resetFilters').addEventListener('click', function () {
    document.getElementById('filterForm').reset();
    fetchCars();
});

document.addEventListener('DOMContentLoaded', () => {
    fetchCars();
document.getElementById("phoneInput").addEventListener("input", function (e) {
  this.value = this.value.replace(/[^0-9]/g, '');
});

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('view-details-btn')) {
            const carId = e.target.getAttribute('data-id');
            const modalBody = document.getElementById('carDetailsContent');
            modalBody.innerHTML = '<div class="text-center text-muted">Loading...</div>';
            fetch('ajax/car_details_popup.php?id=' + carId)
                .then(res => res.text())
                .then(html => {
                    modalBody.innerHTML = html;
                    const modal = new bootstrap.Modal(document.getElementById('carDetailsModal'));
                    modal.show();
                })
                .catch(() => modalBody.innerHTML = '<div class="text-danger">Failed to load details.</div>');
        }

        if (e.target.classList.contains('btn-contact')) {
            const carName = e.target.getAttribute('data-car');
            document.getElementById('car_name_input').value = carName;
        }
    });

    // Contact Form Submission
    document.getElementById("contactForm").addEventListener("submit", function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        fetch("/CarElite/forms/submit_contact.php", {
            method: "POST",
            body: formData
        })
        .then(response => {
            if (response.ok) {
                const modalEl = document.getElementById('contactModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();

                form.reset();

                Swal.fire({
                    icon: 'success',
                    title: 'Message Sent!',
                    text: 'We will contact you shortly.',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Something went wrong. Please try again.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Could not send the message.'
            });
        });
    });
});
</script>

<?php include '../includes/footer.php'; ?>

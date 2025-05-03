<?php include '../includes/header.php'; ?>

<style>
.card-hover:hover {
    transform: translateY(-5px);
    transition: 0.3s ease;
    box-shadow: 0 0 10px rgba(230, 57, 70, 0.3);
}
</style>

<!-- Hero Section -->
<section class="py-5 text-center" style="background-color: #1a1a1a;">
    <div class="container">
        <h1 class="display-5 fw-bold text-white">Find Your Dream Car</h1>
        <p class="text-muted">Browse and filter top car deals</p>
        <form id="filterForm" class="row justify-content-center mt-4">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Search by name or brand">
            </div>
            <div class="col-md-2">
                <select name="brand" class="form-select bg-dark text-white border-secondary">
                    <option value="">All Brands</option>
                    <option value="Nissan">Nissan</option>
                    <option value="Toyota">Toyota</option>
                    <option value="Honda">Honda</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="min_price" class="form-control bg-dark text-white border-secondary" placeholder="Min Price">
            </div>
            <div class="col-md-2">
                <input type="number" name="max_price" class="form-control bg-dark text-white border-secondary" placeholder="Max Price">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-danger w-100">Search</button>
                <button type="button" class="btn btn-outline-light" id="resetFilters">Reset</button>
            </div>
        </form>
    </div>
</section>

<div class="container py-5">
    <div id="carResults">Loading cars...</div>
</div>

<!-- Contact Modal -->
<?php include '../includes/contact_modal.php'; ?>

<script>
function loadCars(page = 1) {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    formData.append('page', page);

    fetch('fetch_cars.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(html => {
        document.getElementById('carResults').innerHTML = html;
    });
}

document.addEventListener('DOMContentLoaded', function () {
    loadCars();

    document.getElementById('filterForm').addEventListener('submit', function (e) {
        e.preventDefault();
        loadCars();
    });

    document.getElementById('resetFilters').addEventListener('click', function () {
        document.getElementById('filterForm').reset();
        loadCars();
    });

    // Contact modal logic
    var contactModal = document.getElementById('contactModal');
    contactModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var carName = button.getAttribute('data-car');
        document.getElementById('car_name_input').value = carName;
    });
});
</script>

<?php include '../includes/footer.php'; ?>

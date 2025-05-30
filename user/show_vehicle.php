<?php
$title = "NagarYatra | Vehicle Details";
$current_page = "vehicle";

require_once '../config/connection.php';
include_once 'master_header.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "SELECT v.*, vc.name AS company_name, cat.name AS category_name 
            FROM vehicle v
            INNER JOIN vehicle_company vc ON v.vehicle_company_id = vc.id
            INNER JOIN vehicle_category cat ON v.vehicle_category_id = cat.id
            WHERE v.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Get driver details
        $driver_sql = "SELECT * FROM user WHERE vehicle_id = ? AND role = 1";
        $driver_stmt = $conn->prepare($driver_sql);
        $driver_stmt->bind_param("i", $id);
        $driver_stmt->execute();
        $driver_result = $driver_stmt->get_result();
        $driver = $driver_result->fetch_assoc();
        ?>

        <style>
            :root {
                --theme-color: #092448;
                --theme-light: #0d356d;
                --theme-accent: #ff6b00;
                --theme-light-bg: #f5f8fc;
            }

            .theme-bg {
                background-color: var(--theme-color);
                color: white;
            }

            .theme-border {
                border-color: var(--theme-color);
            }

            .theme-text {
                color: var(--theme-color);
            }

            .accent-text {
                color: var(--theme-accent);
            }

            .card-header {
                background: linear-gradient(135deg, var(--theme-color), var(--theme-light));
                border-radius: 10px 10px 0 0 !important;
            }

            .detail-card {
                border-radius: 10px;
                box-shadow: 0 5px 20px rgba(9, 36, 72, 0.1);
                border: none;
                transition: transform 0.3s ease;
                overflow: hidden;
            }



            .detail-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(9, 36, 72, 0.15);
            }

            .detail-icon {
                width: 50px;
                height: 50px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(9, 36, 72, 0.1);
                border-radius: 50%;
                margin-right: 15px;
            }

            .detail-icon i {
                font-size: 1.2rem;
                color: var(--theme-color);
            }

            .detail-label {
                font-weight: 600;
                color: var(--theme-color);
                margin-bottom: 3px;
            }

            .detail-value {
                font-size: 1.1rem;
            }

            .vehicle-img {
                height: 280px;
                object-fit: contain;
                border-radius: 10px 10px 0 0;
            }

            .driver-img {
                width: 120px;
                height: 120px;
                object-fit: cover;
                border: 3px solid var(--theme-color);
            }

            .status-badge {
                position: absolute;
                top: 15px;
                right: 15px;
                padding: 6px 12px;
                border-radius: 20px;
                font-weight: 600;
                font-size: 0.85rem;
            }

            .back-btn {
                background: white;
                color: var(--theme-color);
                border: 2px solid var(--theme-color);
                border-radius: 30px;
                padding: 8px 25px;
                font-weight: 600;
                transition: all 0.3s;
            }

            .back-btn:hover {
                background: var(--theme-color);
                color: white;
            }

            .section-title {
                position: relative;
                padding-bottom: 15px;
                margin-bottom: 25px;
            }

            .section-title::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 60px;
                height: 3px;
                background: var(--theme-color);
                border-radius: 3px;
            }

            .info-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 25px;
            }

            .main-content {
                margin: 0;
                padding: 0;
            }

            .menu-bar {
                margin-top: -1.5rem;
            }
        </style>

        <div class="container" style="box-shadow: none !important;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="theme-text fw-bold">Vehicle Details</h1>
                <a href="vehicles" class="back-btn d-flex align-items-center">
                    <i class="fas fa-arrow-left me-2"></i> Back to Vehicles
                </a>
            </div>

            <div class="row g-4 mb-5">
                <!-- Vehicle Card -->
                <div class="col-lg-6">
                    <div class="detail-card h-100">
                        <?php
                        $imagePath = (!empty($row['thumb_image']) && file_exists($row['thumb_image']))
                            ? htmlspecialchars($row['thumb_image'])
                            : '../assets/logo1.png';
                        ?>

                        <img src="<?= $imagePath ?>" class="vehicle-img w-100" alt="Vehicle Image">


                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="theme-text mb-0"><?= htmlspecialchars($row['vehicle_number']) ?></h3>
                                <span class="status-badge bg-success text-white">Active</span>
                            </div>

                            <div class="info-grid">
                                <div class="d-flex">
                                    <div class="detail-icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div>
                                        <div class="detail-label">Company</div>
                                        <div class="detail-value"><?= htmlspecialchars($row['company_name']) ?></div>
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <div class="detail-icon">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <div>
                                        <div class="detail-label">Category</div>
                                        <div class="detail-value" style="margin-left: -35px;"><?= htmlspecialchars($row['category_name']) ?></div>
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <div class="detail-icon">
                                        <i class="fas fa-barcode"></i>
                                    </div>
                                    <div>
                                        <div class="detail-label" style="margin-left: -35px;">Chassis No</div>
                                        <div class="detail-value"><?= htmlspecialchars($row['chassis_number']) ?></div>
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <div class="detail-icon">
                                        <i class="fas fa-palette"></i>
                                    </div>
                                    <div>
                                        <div class="detail-label">Color</div>
                                        <div class="detail-value"><?= htmlspecialchars($row['color']) ?></div>
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <div class="detail-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div>
                                        <div class="detail-label">Bill Book Expiry</div>
                                        <div class="detail-value" style="margin-left: -35px;"><?= htmlspecialchars($row['bill_book_expiry_date']) ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <h5 class="section-title theme-text">Description</h5>
                                <p class="mb-0"><?= $row['description'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Driver Card -->
                <div class="col-lg-5">
                    <div class="detail-card h-100">
                        <div class="card-header text-white p-4">
                            <h3 class="mb-0"><i class="fas fa-user me-2"></i> Driver Details</h3>
                        </div>

                        <div class="card-body p-4">
                            <?php if ($driver): ?>
                                <div class="text-center mb-4">
                                    <?php
                                    $driverImage = !empty($driver['image']) ? htmlspecialchars($driver['image']) : '../assets/logo1.png';
                                    ?>

                                    <img src="<?= $driverImage ?>" class="driver-img rounded-circle mb-3" alt="Driver Image">

                                    <h4 class="theme-text mb-1"><?= htmlspecialchars($driver['name']) ?></h4>
                                    <p class="text-muted">Licensed Driver</p>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-label">Email</div>
                                        <div class="detail-value"><?= htmlspecialchars($driver['email']) ?></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="detail-label">Phone</div>
                                        <div class="detail-value"><?= htmlspecialchars($driver['phone']) ?></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="detail-label">Gender</div>
                                        <div class="detail-value"><?= htmlspecialchars($driver['gender']) ?></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="detail-label">Date of Birth</div>
                                        <div class="detail-value"><?= htmlspecialchars($driver['dob']) ?></div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h5 class="section-title theme-text">License Information</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="detail-label">License Number</div>
                                            <div class="detail-value"><?= htmlspecialchars($driver['dl_number']) ?></div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="detail-label">Expiry Date</div>
                                            <div class="detail-value"><?= htmlspecialchars($driver['dl_expiry_date']) ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h5 class="section-title theme-text">Address</h5>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($driver['address'])) ?></p>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="fas fa-user-slash text-muted" style="font-size: 5rem;"></i>
                                    </div>
                                    <h4 class="theme-text mb-2">No Driver Assigned</h4>
                                    <p class="text-muted">This vehicle currently has no assigned driver</p>
                                    <button class="btn btn-primary mt-2">Assign Driver</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php
    } else {
        echo '<div class="container my-5 py-5">
            <div class="text-center">
                <i class="fas fa-car-crash text-danger mb-4" style="font-size: 5rem;"></i>
                <h2 class="theme-text mb-3">Vehicle Not Found</h2>
                <p class="lead mb-4">The vehicle you are looking for does not exist in our records.</p>
                <a href="vehicles" class="btn btn-lg btn-primary px-4">
                    <i class="fas fa-arrow-left me-2"></i> Back to Vehicles
                </a>
            </div>
        </div>';
    }
} else {
    echo '<div class="">
 <div class="container d-flex justify-content-center align-items-center">
    <div class="text-center">
        <i class="fas fa-exclamation-triangle text-warning mb-4" style="font-size: 5rem;"></i>
        <h2 class="theme-text mb-3">Invalid Request</h2>
        <p class="lead mb-4">Please provide a valid vehicle ID to view details.</p>
        <a href="index" class="back-btn d-inline-flex align-items-center">
            <i class="fas fa-arrow-left me-2"></i> Back to Vehicles
        </a>
    </div>
</div></div>';

}

include_once 'master_footer.php';
?>
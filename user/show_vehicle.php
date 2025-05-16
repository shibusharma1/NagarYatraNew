<?php
$title = "NagarYatra | Vehicles";
$current_page = "show_vehicle";

require_once '../config/connection.php';
include_once 'master_header.php';


    $user_id = $_SESSION['id'];
    $query = "SELECT vehicle_id FROM user WHERE id = $user_id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $id = $row['vehicle_id'];

    
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
?>

<!-- <div class="container"> -->
    <div class="vehicle-container">
        <!-- Vehicle Image -->
        <div class="vehicle-image">
            <img src="<?php echo htmlspecialchars($row['thumb_image']); ?>" alt="Vehicle Image">
              <div class="info-item">
                <?php 
                if($row['status']==1){
                    echo "<i class='fas fa-check-circle text-success'></i><strong>Status:</strong>";
                    echo "Active"; 
                }else{
                    echo "<i class='fas fa-times-circle text-danger'></i><strong>Status:</strong>";
                    echo "Active"; 
                }
                ?>
            </div>


            <div class="info-item">
                <i class="fas fa-building"></i> <strong>Company:</strong> <?php echo htmlspecialchars($row['company_name']); ?>
            </div>
            
            <div class="info-item">
                <i class="fas fa-layer-group"></i> <strong>Category:</strong> <?php echo htmlspecialchars($row['category_name']); ?>
            </div>

            <div class="info-item">
                <i class="fas fa-barcode"></i> <strong>Chassis No:</strong> <?php echo htmlspecialchars($row['chassis_number']); ?>
            </div>

            <div class="info-item">
                <i class="fas fa-palette"></i> <strong>Color:</strong> <?php echo htmlspecialchars($row['color']); ?>
            </div>

            <div class="info-item">
                <i class="fas fa-car"></i> <strong>Vehicle Number:</strong> <?php echo htmlspecialchars($row['vehicle_number']); ?>
            </div>

            <div class="info-item">
                <i class="fas fa-calendar-alt"></i> <strong>Bill Book Expiry:</strong> <?php echo htmlspecialchars($row['bill_book_expiry_date']); ?>
            </div>
        

        <!-- Vehicle Details -->
        <!-- <div class="vehicle-info"> -->
        
            <div class="info-item" >
                <strong>Description:</strong>
                <?php echo $row['description']    ; ?>
            </div>
            <a href="index" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>
<!-- </div> -->
<!-- </div> -->

<?php
    } else {
        echo "<p class='text-center text-danger'>Vehicle not found.</p>";
    }
include_once 'master_footer.php';

?>

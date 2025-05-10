<?php

require_once('createdb.php');
//connecting to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "NagarDB";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

//create a connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

//Check Connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// admin start
//creating table for Admin 
$sql = "CREATE TABLE IF NOT EXISTS admin(
    a_id INT PRIMARY KEY AUTO_INCREMENT,
    a_name VARCHAR(30) NOT NULL,
    a_role VARCHAR(30) NOT NULL,
    a_email VARCHAR(30) NOT NULL,
    a_phone BIGINT(10) NOT NULL,
    a_password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    // Table created successfully, no need to echo anything
} else {
    echo "Error Creating table: " . mysqli_error($conn);
}

// Inserting default admin data
// Hash the password
$hashed_password = password_hash('admin123', PASSWORD_BCRYPT);

// SQL Query
$sql = "INSERT IGNORE INTO admin (a_id, a_name,a_role, a_email, a_phone, a_password) 
        VALUES ('101', 'Admin','admin', 'admin@gmail.com', '9880922648', '$hashed_password')";

if (mysqli_query($conn, $sql)) {
    // Data inserted successfully
} else {
    echo "Error Inserting data: " . mysqli_error($conn);
}
// Admin End



// For vehicle registration
// Creating table for vehicle_categories
$sql = "CREATE TABLE IF NOT EXISTS vehicle_category (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,  -- name i.e car,auto,ct,bike
    image VARCHAR(255) NOT NULL,  -- name i.e car,auto,ct,bike
    seats INT NOT NULL,
    min_cost INT DEFAULT 20,
    per_km_cost INT DEFAULT 18,
    Fuel_type VARCHAR(255) NOT NULL,  -- EV, Petrol, Diesel, CNG
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    // Table created successfully
} else {
    echo "Error Creating table: " . mysqli_error($conn);
}

$sql = "INSERT IGNORE INTO vehicle_category (id, name, seats, Fuel_type) 
        VALUES 
        (1, 'Car', 3, 'Petrol'), 
        (2, 'Bike', 1, 'Petrol'), 
        (3, 'Auto', 3, 'CNG'), 
        (4, 'CT', 4, 'EV')";

if (mysqli_query($conn, $sql)) {
    // Data inserted successfully
} else {
    echo "Error Inserting data: " . mysqli_error($conn);
}

// Creating table for vehicle_company
$sql = "CREATE TABLE IF NOT EXISTS vehicle_company (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    headquarter VARCHAR(255) NOT NULL,
    global_presence BOOLEAN DEFAULT 0, -- Changed to BOOLEAN with default value 0 (false)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";

if (mysqli_query($conn, $sql)) {
    // Table created successfully
} else {
    echo "Error Creating table: " . mysqli_error($conn);
}

$sql = "INSERT IGNORE INTO vehicle_company (id, name, headquarter, global_presence) 
        VALUES 
        (1, 'Toyota', 'Japan', 1), 
        (2, 'Tesla', 'USA', 1), 
        (3, 'Tata Motors', 'India', 1), 
        (4, 'BYD', 'China', 1), 
        (5, 'Mahindra', 'India', 1)";

if (mysqli_query($conn, $sql)) {
    // Data inserted successfully
} else {
    echo "Error Inserting data: " . mysqli_error($conn);
}



// Creating table for Vehicle
$sql = "CREATE TABLE IF NOT EXISTS vehicle (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vehicle_company_id INT NOT NULL,
    chassis_number  VARCHAR(255) NOT NULL,   
    vehicle_category_id INT NOT NULL,   
    color VARCHAR(255) NOT NULL,   
    vehicle_number VARCHAR(255) NOT NULL UNIQUE,  -- Ensuring uniqueness for vehicle numbers
    thumb_image VARCHAR(255) NOT NULL,   
    description LONGTEXT NOT NULL,
    bill_book_expiry_date DATE NOT NULL,  -- Renamed for clarity
    bill_book_image VARCHAR(255) NOT NULL,
    is_delete TINYINT(1) DEFAULT 0,
    is_approved TINYINT(1) DEFAULT 0,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_company_id) REFERENCES vehicle_company(id) ON DELETE CASCADE,
    FOREIGN KEY (vehicle_category_id) REFERENCES vehicle_category(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql)) {
    // Table created successfully
} else {
    echo "Error Creating table: " . mysqli_error($conn);
}
$sql = "INSERT IGNORE INTO vehicle (vehicle_company_id, chassis_number, vehicle_category_id, color, vehicle_number, thumb_image, description, bill_book_expiry_date, bill_book_image) 
        VALUES 
        (1, 'CH1234567890', 2, 'Red', 'AB123CD', 'thumb_image_1.jpg', 'A sleek red sedan for city commuting.', '2026-12-31', 'bill_image_1.jpg'),
        (2, 'CH9876543210', 1, 'Blue', 'XY456ZT', 'thumb_image_2.jpg', 'A spacious blue SUV for long trips.', '2025-11-30', 'bill_image_2.jpg')";

if (mysqli_query($conn, $sql)) {
    // Data inserted successfully
} else {
    echo "Error Inserting data: " . mysqli_error($conn);
}


// Creating table for User(both driver and Passanger) Registration
$sql = "CREATE TABLE IF NOT EXISTS user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    role TINYINT DEFAULT 0,  -- 0 passanger 1 driver
    email VARCHAR(50) NOT NULL,  
    password VARCHAR(255) NOT NULL,
    phone BIGINT(10) NOT NULL,
    status INT DEFAULT 1,  -- Changed from ENUM to INT with default 0
    cancel_status INT DEFAULT 0,
    dob DATE NOT NULL,
    gender ENUM('MALE', 'FEMALE', 'OTHERS') NOT NULL,
    image VARCHAR(255),   
    address TEXT,  -- Added address column

    dl_number VARCHAR(255),   
    dl_image VARCHAR(255),   
    dl_expiry_date DATE,   
    vehicle_id INT,
    latitude DECIMAL(10, 8) DEFAULT 26.455050,
    longitude DECIMAL(11, 8) DEFAULT 87.270070,
    otp VARCHAR(10) NOT NULL ,  -- Added OTP column
    otp_expiry DATETIME NOT NULL ,  -- Added OTP expiry column
    is_verified BOOLEAN DEFAULT 0,  -- Added is_verified column
    FOREIGN KEY (vehicle_id) REFERENCES vehicle(id),
    is_delete TINYINT(1) DEFAULT 0,
    is_block TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    // Table created successfully
} else {
    echo "Error Creating table: " . mysqli_error($conn);
}

$hashedpassword = password_hash('password', PASSWORD_DEFAULT);

// Separate insert statements to isolate issues
$sql1 = "INSERT IGNORE INTO user (id, name, role, email, password, phone, status, cancel_status, dob, gender, image, address, dl_number, dl_image, dl_expiry_date, vehicle_id, otp, otp_expiry, is_verified) 
        VALUES (1, 'NagarYatra User', 0, 'user@gmail.com', '$hashedpassword', 9876543210, 1, 0, '1990-05-15', 'MALE', NULL, 'New York, USA', NULL, NULL, NULL, NULL, '123456', NOW() + INTERVAL 5 MINUTE, 1)";

$sql2 = "INSERT IGNORE INTO user (id, name, role, email, password, phone, status, cancel_status, dob, gender, image, address, dl_number, dl_image, dl_expiry_date, vehicle_id, otp, otp_expiry, is_verified) 
        VALUES (2, 'NagarYatra Driver', 1, 'driver@gmail.com', '$hashedpassword', 9876543211, 1, 0, '1988-09-20', 'FEMALE', NULL, 'Los Angeles, USA', 'DL123456789', NULL, '2030-12-31', 1, '654321', NOW() + INTERVAL 5 MINUTE, 1)";

if (mysqli_query($conn, $sql1)) {
    // First record inserted successfully
} else {
    echo "Error Inserting first record: " . mysqli_error($conn);
}

if (mysqli_query($conn, $sql2)) {
    // Second record inserted successfully
} else {
    echo "Error Inserting second record: " . mysqli_error($conn);
}



// Creating table for Notifications
$sql = "CREATE TABLE IF NOT EXISTS notification (
    id INT PRIMARY KEY AUTO_INCREMENT,
    message VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    // Table created successfully
} else {
    echo "Error Creating table: " . mysqli_error($conn);
}

// Creating table for gets_Notifications
$sql = "CREATE TABLE IF NOT EXISTS gets_notification (
    id INT PRIMARY KEY AUTO_INCREMENT,
    -- message VARCHAR(255) NOT NULL,
    user_id INT NULL,
    notification_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (notification_id) REFERENCES notification(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql)) {
    // Table created successfully
} else {
    echo "Error Creating table: " . mysqli_error($conn);
}

// New booking table
$sql = "CREATE TABLE IF NOT EXISTS booking (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    vehicle_id INT NULL,
    otp VARCHAR(10) NOT NULL ,  -- Added OTP column
    
    pick_up_place VARCHAR(255) NOT NULL,
    pickup_lat DECIMAL(10, 8) DEFAULT NULL,
    pickup_lng DECIMAL(11, 8) DEFAULT NULL,

    destination VARCHAR(255) NOT NULL,
    destination_lat DECIMAL(10, 8) DEFAULT NULL,
    destination_lng DECIMAL(11, 8) DEFAULT NULL,

    estimated_cost DECIMAL(10,2) NOT NULL,
    estimated_KM DECIMAL(10,2) NOT NULL,
    estimated_ride_duration VARCHAR(50) NOT NULL, -- Duration of the ride (days/hours)
    booking_date DATE NOT NULL,

    rating DECIMAL(3,1) DEFAULT 5, -- Allows decimal ratings like 4.5, 3.0
    nearest_users VARCHAR(255) NULL, -- The user can write his view regarding the specific ride
    booking_description TEXT NOT NULL,

    status INT DEFAULT 2,  -- Default status set to cancel,pending,success,Rejected
    -- booking_end DATETIME NULL DEFAULT NULL, -- Nullable if not known at booking time
    pre_booking TINYINT(1) DEFAULT 0,


    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE SET NULL,
    FOREIGN KEY (vehicle_id) REFERENCES vehicle(id) ON DELETE SET NULL,
    -- FOREIGN KEY (driver_id) REFERENCES driver(id) ON DELETE SET NULL,

    is_delete TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";


if (mysqli_query($conn, $sql)) {
    // Table created successfully
} else {
    echo "Error Creating table: " . mysqli_error($conn);
}

// table to store feedback

$sql = "CREATE TABLE IF NOT EXISTS feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    subject VARCHAR(255) NOT NULL,
    message VARCHAR(255) NOT NULL,
    status TINYINT(1) DEFAULT 0,
    user_id INT NULL,
    -- driver_id INT NULL,
    is_delete TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql)) {
    // Table created successfully
} else {
    echo "Error Creating table: " . mysqli_error($conn);
}

// for report
$sql = "CREATE TABLE IF NOT EXISTS report (
    id INT PRIMARY KEY AUTO_INCREMENT,
    users_count INT,
    vehicles_count INT,
    bookings_count INT,
    feedback_count INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";

if (mysqli_query($conn, $sql)) {
    // Table created successfully
} else {
    echo "Error Creating table: " . mysqli_error($conn);
}

// Inserting initial data 
$insert_sql = "
INSERT IGNORE INTO report (users_count, vehicles_count, bookings_count, feedback_count) VALUES
(5, 2, 10, 1);
";

if (mysqli_query($conn, $insert_sql)) {
    // echo "Dummy data inserted successfully";
} else {
    echo "Error inserting data: " . mysqli_error($conn);
}
?>
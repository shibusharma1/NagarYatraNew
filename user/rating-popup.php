<?php
include '../config/connection.php';
$userId = $_SESSION['id'];

$sql = "SELECT * FROM booking WHERE user_id = $userId AND rating IS NULL AND status = 5 LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $booking = $result->fetch_assoc();
?>
<!-- Modal HTML -->
<div id="ratingModal" class="modal-overlay">
  <div class="modal-content">
    <span class="close-modal" onclick="closeModal()">&times;</span>
    <h2>Rate Your Ride Experience</h2>
    <div class="booking-info">
      <p><strong>Pickup:</strong> <?= htmlspecialchars($booking['pick_up_place']) ?></p>
      <p><strong>Destination:</strong> <?= htmlspecialchars($booking['destination']) ?></p>
      <p><strong>Date:</strong> <?= htmlspecialchars($booking['booking_date']) ?></p>
      <p><strong>Distance:</strong> <?= $booking['estimated_KM'] ?> KM</p>
      <p><strong>Cost:</strong> Rs. <?= $booking['estimated_cost'] ?></p>
      <p><strong>Description:</strong> <?= htmlspecialchars($booking['booking_description']) ?></p>
    </div>

    <form id="ratingForm" method="POST" action="submit_rating.php">
      <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
      <div class="stars">
        <span class="star" data-value="1">★</span>
        <span class="star" data-value="2">★</span>
        <span class="star" data-value="3">★</span>
        <span class="star" data-value="4">★</span>
        <span class="star" data-value="5">★</span>
        <input type="hidden" name="rating" id="ratingInput" required>
      </div>

      <textarea name="experience_description" placeholder="Write your feedback..."></textarea>
      <button type="submit">Submit Rating</button>
    </form>
  </div>
</div>

<!-- Modal Style -->
<style>
  .modal-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
  }
  .modal-content {
    background: #fff;
    padding: 30px;
    width: 90%;
    max-width: 600px;
    border-radius: 10px;
    position: relative;
    color: #092448;
    font-family: Arial, sans-serif;
    box-shadow: 0 0 15px rgba(0,0,0,0.4);
  }
  .modal-content h2 {
    background: #092448;
    color: #fff;
    padding: 10px 15px;
    margin: -30px -30px 20px -30px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }
  .booking-info p {
    margin: 5px 0;
  }
  .stars {
    text-align: center;
    margin: 15px 0;
    user-select: none;
  }
  .star {
    font-size: 32px;
    color: #ccc;
    cursor: pointer;
    transition: color 0.2s;
  }
  .star.selected,
  .star.hovered {
    color: gold;
  }
  textarea {
    width: 100%;
    height: 100px;
    resize: none;
    margin-top: 15px;
    padding: 10px;
    border: 1px solid #ccc;
    font-family: Arial;
    border-radius: 5px;
  }
  button {
    background-color: #092448;
    color: white;
    padding: 10px 20px;
    margin-top: 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    float: right;
  }
  .close-modal {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 24px;
    color: #092448;
    cursor: pointer;
    font-weight: bold;
  }
</style>

<!-- SweetAlert and Rating Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function closeModal() {
    document.getElementById('ratingModal').remove();
  }

  const stars = document.querySelectorAll('.star');
  const ratingInput = document.getElementById('ratingInput');

  if (stars && ratingInput) {
    stars.forEach((star, index) => {
      star.addEventListener('click', () => {
        const rating = parseInt(star.getAttribute('data-value'));
        ratingInput.value = rating;
        stars.forEach((s, i) => s.classList.toggle('selected', i < rating));
      });

      star.addEventListener('mouseover', () => {
        const hoverValue = parseInt(star.getAttribute('data-value'));
        stars.forEach((s, i) => s.classList.toggle('hovered', i < hoverValue));
      });

      star.addEventListener('mouseout', () => {
        stars.forEach(s => s.classList.remove('hovered'));
      });
    });
  }

</script>
<?php } ?>

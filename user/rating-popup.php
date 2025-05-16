<?php
require '../config/connection.php'; // update to your DB connection file

$bookingQuery = $conn->query("SELECT * FROM booking WHERE rating IS NULL AND status = 5 LIMIT 1");
if ($bookingQuery && $bookingQuery->num_rows > 0):
    $booking = $bookingQuery->fetch_assoc();
?>
<style>
    .modal-header, .btn-primary {
        background-color: #092448;
        color: white;
    }
    .star-rating {
        direction: rtl;
        font-size: 2em;
        unicode-bidi: bidi-override;
        display: inline-flex;
        gap: 5px;
    }
    .star-rating input {
        display: none;
    }
    .star-rating label {
        color: #ccc;
        cursor: pointer;
    }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: gold;
    }
</style>

<!-- Rating Modal -->
<div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="ratingModalLabel">Rate Your Ride Experience</h5>
      </div>
      <form method="POST" action="submit-rating.php">
        <div class="modal-body">
          <div class="mb-3">
            <strong>Pickup:</strong> <?php echo htmlspecialchars($booking['pick_up_place']); ?><br>
            <strong>Destination:</strong> <?php echo htmlspecialchars($booking['destination']); ?><br>
            <strong>Date:</strong> <?php echo htmlspecialchars($booking['booking_date']); ?><br>
            <strong>Distance:</strong> <?php echo $booking['estimated_KM']; ?> KM<br>
            <strong>Cost:</strong> Rs. <?php echo $booking['estimated_cost']; ?><br>
            <strong>Description:</strong><br>
            <p class="text-muted"><?php echo nl2br(htmlspecialchars($booking['booking_description'])); ?></p>
          </div>

          <div class="mb-3 text-center">
            <label class="form-label"><strong>Your Rating</strong></label><br>
            <div class="star-rating">
              <?php for ($i = 5; $i >= 1; $i--): ?>
                <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>">
                <label for="star<?php echo $i; ?>">★</label>
              <?php endfor; ?>
            </div>
          </div>
          <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit Rating</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    $(document).ready(function () {
        $('#ratingModal').modal('show');
    });
</script>

<?php endif; ?>

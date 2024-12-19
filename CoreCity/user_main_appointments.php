
<div class="content">
    <h1>Book an Appointment</h1>
    <div class="appointment-container">
        <form action="book_appointment.php" id="appointment-form" method="POST">
            <label for="staff">Select Staff Member:</label>
            <select id="staff" name="staff_id" required>
                <option value="" disabled selected>Select a staff member</option>
                <!-- Staff options populated by JavaScript -->
            </select>

            <label for="date">Select Date:</label>
            <input type="date" id="date" name="appointment_date" required>

            <label for="time">Select Time:</label>
            <select id="time" name="time" required>
                <option value="" disabled selected>Select a time</option>
                <!-- Time options populated dynamically -->
            </select>

            <button type="submit">Book Appointment</button>
        </form>
    </div>
</div>

<script>
// Fetch staff list when page loads
document.addEventListener('DOMContentLoaded', function() {
    fetch('populate_staff.php')
        .then(response => response.text())
        .then(data => document.getElementById('staff').innerHTML += data);

    // When staff is selected, fetch available times for the selected staff and date
    document.getElementById('staff').addEventListener('change', function() {
        const staffId = this.value;
        const appointmentDate = document.getElementById('date').value;
        
        if (staffId && appointmentDate) {
            fetch(`populate_times.php?staff_id=${staffId}&appointment_date=${appointmentDate}`)
                .then(response => response.text())
                .then(data => document.getElementById('time').innerHTML = data);
        }
    });

    // When date is selected, update available times
    document.getElementById('date').addEventListener('change', function() {
        const staffId = document.getElementById('staff').value;
        const appointmentDate = this.value;
        
        if (staffId && appointmentDate) {
            fetch(`populate_times.php?staff_id=${staffId}&appointment_date=${appointmentDate}`)
                .then(response => response.text())
                .then(data => document.getElementById('time').innerHTML = data);
        }
    });
});

// Handle the form submission using AJAX
document.getElementById('appointment-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the form from submitting the traditional way

    const formData = new FormData(this); // Get the form data

    // Send the form data via AJAX
    fetch('book_appointment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Parse the JSON response
    .then(data => {
        // Handle the success or error based on the response
        if (data.status === 'success') {
            alert(data.message); // Success message
        } else {
            alert(data.message); // Error message
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while booking the appointment.');
    });
});
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #198754;
            --secondary-color: #6c757d;
            --cricket-color: #4CAF50;
            --badminton-color: #2196F3;
            --tennis-color: #FF9800;
            --squash-color: #9C27B0;
            --basketball-color: #F44336;
            --available-color: #e8f5e9;
            --booked-color: #ffebee;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .card-header {
            background-color: #358f50;
            color: white;
            border-bottom: none;
            padding: 1.5rem;
            border-radius: 1rem 1rem 0 0 !important;
        }

        /* Game Tabs */
        .game-tabs {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .game-tab {
            padding: 12px 20px;
            cursor: pointer;
            font-weight: 500;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
            color: #495057;
            flex-grow: 1;
            text-align: center;
        }

        .game-tab:hover {
            background-color: #f8f9fa;
        }

        .game-tab.active {
            border-bottom: 3px solid var(--primary-color);
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Calendar Table */
        .booking-calendar {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            table-layout: fixed; /* Ensures columns distribute space evenly */
        }

        .booking-calendar th,
        .booking-calendar td {
            border: 1px solid #dee2e6;
            text-align: center;
            vertical-align: middle;
            padding: 12px;
            font-size: 0.95rem;
            transition: background-color 0.2s ease-in-out;
        }

        /* Header style */
        .booking-calendar th {
            background-color: #e9ecef;
            font-weight: 700;
            color: #343a40;
            border-bottom: 2px solid #dee2e6;
            padding: 15px 10px;
            text-align: center;
            white-space: nowrap;
        }

        /* Time column styling */
        .booking-calendar .time-column {
            font-weight: 600;
            background-color: #f8f9fa;
            width: 20%; /* Set a specific width for the time column */
            text-align: center; /* Explicitly ensure content is centered */
        }

        /* Hover effect and pointer cursor */
        .booking-calendar td:hover,
        .booking-calendar th:hover {
            background-color: #e9ecef;
        }

        /* Available slot hover greenish */
        .time-slot.available:hover {
            background-color: #d4edda;
            cursor: pointer;
        }

        /* Booked slot still pointer but red-tinted on hover */
        .time-slot.booked:hover {
            background-color: #f8d7da;
            cursor: pointer; /* Changed to pointer as it now opens a modal */
        }

        .time-slot {
            position: relative;
            height: 100%;
            padding: 8px;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        .available {
            background-color: var(--available-color);
            border-left: 4px solid #4CAF50;
        }

        .booked {
            background-color: var(--booked-color);
            border-left: 4px solid #F44336;
        }

        .booking-info {
            margin-top: 2px;
            font-size: 0.75rem;
            line-height: 1.3;
        }

        .sport-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 1rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: white;
            margin-right: 3px;
        }

        .badge-cricket { background-color: var(--cricket-color); }
        .badge-badminton { background-color: var(--badminton-color); }
        .badge-tennis { background-color: var(--tennis-color); }
        .badge-squash { background-color: var(--squash-color); }
        .badge-basketball { background-color: var(--basketball-color); }

        /* Date Navigation */
        .date-navigation {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
        }

        .current-date {
            font-weight: 600;
            font-size: 1.2rem;
            color: #495057;
        }

        .date-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        /* Timer styles */
        .timer-display {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s;
        }

        .timer-display:hover {
            transform: scale(1.05);
        }

        .timer-running {
            color: #dc3545;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        /* Modal styles */
        .modal-timer {
            font-size: 3.5rem;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            margin: 20px 0;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .game-tabs {
                justify-content: center;
                overflow-x: auto;
                white-space: nowrap;
                display: block;
                padding-bottom: 10px;
            }

            .game-tab {
                display: inline-block;
                min-width: 120px;
            }

            .booking-calendar th,
            .booking-calendar td {
                padding: 8px 5px;
                font-size: 0.8rem;
            }

            .time-column {
                font-size: 0.8rem;
            }

            .modal-timer {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 text-white"><i class="fas fa-calendar-alt me-2"></i>Game Booking System</h5>
            </div>
           <div class="card-body">
                <div class="game-tabs">
                    @foreach ($games as $game)
                        @php
                            $icon = match(strtolower($game['name'])) {
                                'cricket' => 'fas fa-baseball-ball',
                                'badminton' => 'fas fa-table-tennis',
                                'tennis' => 'fas fa-table-tennis',
                                'squash' => 'fas fa-running',
                                'basketball' => 'fas fa-basketball-ball',
                                default => 'fas fa-gamepad',
                            };
                        @endphp

                        <div class="game-tab {{ strtolower($game['name']) }}" data-game="{{ strtolower($game['name']) }}">
                            <i class="{{ $icon }} me-2"></i>{{ ucfirst($game['name']) }}
                        </div>
                    @endforeach
                </div>

                <div class="date-navigation">
                    <button class="btn btn-outline-secondary date-btn" id="prevDay">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="current-date" id="currentDate">November 15, 2023</div>
                    <button class="btn btn-outline-secondary date-btn" id="nextDay">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="booking-calendar">
                        <thead>
                            <tr>
                                <!-- Table headers will be injected dynamically by JS -->
                            </tr>
                        </thead>
                        <tbody id="calendarBody">
                            <!-- Booking slots injected via JS -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="bookingForm" class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="bookingModalLabel"><i class="fas fa-calendar-plus me-2"></i>Add Booking</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="addBooking">
                            <div class="alert alert-info">
                                <p class="mb-1"><strong><i class="fas fa-gamepad me-2"></i>Game:</strong> <span id="modalGame" wire:model="selectedGame"></span></p>
                                <p class="mb-1"><strong><i class="fas fa-calendar-day me-2"></i>Date:</strong> <span id="modalDate" wire:model="selectedDate"></span></p>
                                <p class="mb-1"><strong><i class="fas fa-clock me-2"></i>Time:</strong> <span id="modalTime" wire:model="selectedTime"></span></p>
                                <p class="mb-0"><strong><i class="fas fa-map-marker-alt me-2"></i>Court:</strong> <span id="modalCourt" wire:model="selectedCourt"></span></p>
                            </div>

                            <div class="mb-3">
                                <label for="playerName" class="form-label">Player Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="playerName" wire:model="playerName" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="phoneNumber" class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="tel" class="form-control" id="phoneNumber" wire:model="phoneNumber" required>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <label for="status" class="form-label">Status</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                        <select class="form-select" id="status" wire:model="status" required>
                                            <option value="Confirmed">Confirmed</option>
                                            <option value="Pending">Pending</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch pt-3">
                                        <input class="form-check-input" type="checkbox" role="switch" id="permanent" wire:model="permanent">
                                        <label class="form-check-label" for="permanent">Permanent Booking</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" rows="3"></textarea>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cancel</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-check me-2"></i>Book Slot</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="timerModal" tabindex="-1" aria-labelledby="timerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="timerModalLabel"><i class="fas fa-stopwatch me-2"></i>Timer Controls</h5>
                        <button type="button" class="btn btn-light btn-sm" id="newWindowBtn">New window</button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="alert alert-info mb-3">
                            <p class="mb-1"><strong>Player:</strong> <span id="modalTimerPlayerName"></span></p>
                            <p class="mb-0"><strong>Game Start:</strong> <span id="modalTimerGameStartTime"></span></p>
                        </div>
                        <div class="modal-timer" id="modalTimer">00:00:00</div>
                        <div class="d-flex justify-content-center gap-3">
                            <button id="startButton" class="btn btn-success btn-lg" onclick="startTimer(activeModalTimerId)">
                                <i class="fas fa-play me-2"></i>Start
                            </button>
                            <button id="pauseButton" class="btn btn-warning btn-lg" onclick="pauseTimer(activeModalTimerId)" disabled>
                                <i class="fas fa-pause me-2"></i>Pause
                            </button>
                            <button id="resetButton" class="btn btn-danger btn-lg" onclick="resetTimer(activeModalTimerId)">
                                <i class="fas fa-redo me-2"></i>Reset
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <small class="text-muted">Timer will automatically update in the booking view</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Dummy data for each sport (this would be replaced by your Livewire component's data)
        const sportData = @json($bookingdetails);
        // This variable will hold the game configurations from your PHP backend
        const gamesConfig = @json($games);

        let currentGame = Object.keys(sportData)[0]?.toLowerCase() || 'badminton'; // Start with first sport in your data, ensure lowercase
        let currentDate = new Date();

        // Object to hold active timers for each booked slot
        // Structure: { 'timerId': { totalDuration: seconds, remaining: seconds, intervalId: null, isRunning: false, player: '', game: '', startTimeDisplay: '', popupWindow: null } }
        let activeTimers = {};
        let activeModalTimerId = null; // The ID of the timer currently being controlled by the modal

        // Generate time slots from 12:00 AM to 11:00 PM
        function generateTimeSlots() {
            const slots = [];
            for (let hour = 0; hour < 24; hour++) {
                const startHour = hour % 12 || 12; // 12-hour format
                const ampm = hour < 12 ? 'AM' : 'PM';
                const nextHour24 = (hour + 1) % 24;
                const nextHour12 = nextHour24 % 12 || 12;
                const nextAmpm = nextHour24 < 12 ? 'AM' : 'PM';

                slots.push({
                    time24: `${hour.toString().padStart(2, '0')}:00:00`,  // Add seconds here for consistency
                    display: `${startHour}:00 ${ampm} - ${nextHour12}:00 ${nextAmpm}`
                });
            }
            return slots;
        }

        // Format date as "Month Day, Year"
        function formatDate(date) {
            return date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
        }

        // Format date as YYYY-MM-DD
        function formatDateKey(date) {
            return date.toISOString().split('T')[0];
        }

        // Helper function to calculate duration in seconds between two "HH:MM" or "HH:MM:SS" time strings
        function calculateDurationInSeconds(startTimeStr, endTimeStr) {
            const [startHour, startMinute] = startTimeStr.split(':').map(Number);
            const [endHour, endMinute] = endTimeStr.split(':').map(Number); // Assuming end time won't have seconds

            const startDate = new Date(0, 0, 0, startHour, startMinute, 0);
            let endDate = new Date(0, 0, 0, endHour, endMinute, 0);

            // Handle cases where end time is on the next day (e.g., 23:00 - 01:00)
            if (endDate < startDate) {
                endDate.setDate(endDate.getDate() + 1);
            }

            return (endDate.getTime() - startDate.getTime()) / 1000;
        }

        // Update calendar with current game and date
        function updateCalendar() {
            const dateKey = formatDateKey(currentDate);
            // Ensure currentGame is lowercase to match the keys in sportData
            const gameData = sportData[currentGame]?.[dateKey] || {};

            // Determine the number of courts for the current game based on gamesConfig
            let numberOfCourts = 3; // Default to 3 if no specific court count is found
            const currentGameObject = gamesConfig.find(game => game.name.toLowerCase() === currentGame);
            if (currentGameObject && currentGameObject.court) {
                numberOfCourts = currentGameObject.court;
            }

            // Create the dynamic courts array
            const courts = Array.from({ length: numberOfCourts }, (_, i) => `Court ${i + 1}`);

            // 1. Stop all currently running timers before rebuilding the DOM
            for (const timerId in activeTimers) {
                if (activeTimers[timerId].intervalId) {
                    clearInterval(activeTimers[timerId].intervalId);
                    activeTimers[timerId].intervalId = null; // Mark as stopped
                }
            }

            let calendarHtml = '';
            // Apply the 'time-column' class to the 'Time' header for consistent styling
           let tableHeaderHtml = '<th class="time-header">Time</th>';
            courts.forEach(courtName => {
                tableHeaderHtml += `<th>${courtName}</th>`;
            });
            $('thead tr').html(tableHeaderHtml); // Update the table header dynamically

            generateTimeSlots().forEach(slot => {
                calendarHtml += `<tr>
                    <td class="time-column">${slot.display}</td>`;

                courts.forEach(court => {
                    const bookings = gameData[court] || {};
                    let bookingInfo = bookings[slot.time24]; // Directly get booking info for the slot

                    if (bookingInfo) { // If bookingInfo exists, it means the slot is booked
                        const timerId = `${currentGame}-${dateKey}-${court.replace(/\s/g, '')}-${slot.time24.replace(/:/g, '-')}`;

                        // Calculate total duration for this booking
                        const totalDuration = calculateDurationInSeconds(slot.time24, bookingInfo.end);

                        // Initialize timer state if it doesn't exist or if totalDuration has changed
                        if (!activeTimers[timerId] || activeTimers[timerId].totalDuration !== totalDuration) {
                            activeTimers[timerId] = {
                                totalDuration: totalDuration,
                                remaining: totalDuration,
                                intervalId: null,
                                isRunning: false, // Default to not running
                                player: bookingInfo.player,
                                game: currentGame.charAt(0).toUpperCase() + currentGame.slice(1),
                                startTimeDisplay: slot.display,
                                popupWindow: null // Initialize popupWindow reference
                            };
                        }

                        const currentDisplayTime = formatTime(activeTimers[timerId].remaining);
                        const isActiveRunningTimer = activeTimers[timerId].isRunning; // Use isRunning flag

                        calendarHtml += `
                        <td>
                            <div class="time-slot booked"
                                data-timer-id="${timerId}"
                                data-bs-toggle="modal"
                                data-bs-target="#timerModal">
                                <div class="booking-info">
                                    <strong>${bookingInfo.player}</strong><br>
                                    <small>${bookingInfo.phone}</small><br>
                                    ${bookingInfo.status === 'Confirmed' ?
                                        `<span class="badge bg-success">Confirmed</span>` :
                                        `<span class="badge bg-warning">Pending</span>`}
                                    ${bookingInfo.permanent ? '<span class="badge bg-primary">Permanent</span>' : ''}
                                    <div id="${timerId}"
                                        class="timer-display mt-2 ${isActiveRunningTimer ? 'timer-running' : ''}">
                                        ${currentDisplayTime}
                                    </div>
                                </div>
                            </div>
                        </td>`;
                    } else {
                        calendarHtml += `
                        <td>
                            <div class="time-slot available"
                                data-time="${slot.time24}"
                                data-display="${slot.display}"
                                data-court="${court}">
                                Available
                            </div>
                        </td>`;
                    }
                });

                calendarHtml += `</tr>`;
            });

            $('#calendarBody').html(calendarHtml);
            $('#currentDate').text(formatDate(currentDate));

            // 2. Re-start timers that should be running
            for (const timerId in activeTimers) {
                const timerState = activeTimers[timerId];
                // If the timer should be running and has time left, re-start its interval
                if (timerState.isRunning && timerState.remaining > 0) {
                    startTimer(timerId); // Call the main startTimer function
                }
            }
        }

        // Format time in HH:MM:SS
        function formatTime(seconds) {
            const hrs = Math.floor(seconds / 3600).toString().padStart(2, '0');
            const mins = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
            const secs = (seconds % 60).toString().padStart(2, '0');
            return `${hrs}:${mins}:${secs}`;
        }

        // Set the active timer for the modal and update its display
        function setActiveTimer(timerId) {
            activeModalTimerId = timerId;
            const timerState = activeTimers[activeModalTimerId];

            if (timerState) {
                $('#modalTimerPlayerName').text(timerState.player);
                $('#modalTimerGameStartTime').text(timerState.startTimeDisplay);
                $('#modalTimer').text(formatTime(timerState.remaining));

                // Update modal buttons based on current timer state
                if (timerState.isRunning) { // Check isRunning flag
                    $('#startButton').prop('disabled', true);
                    $('#pauseButton').prop('disabled', false);
                } else {
                    $('#startButton').prop('disabled', false);
                    $('#pauseButton').prop('disabled', true);
                }
            } else {
                // Should not happen if logic is correct, but for safety
                console.error('No timer state found for ID:', timerId);
                $('#modalTimerPlayerName').text('N/A');
                $('#modalTimerGameStartTime').text('N/A');
                $('#modalTimer').text('00:00:00');
                $('#startButton').prop('disabled', false);
                $('#pauseButton').prop('disabled', true);
            }
        }

        // Start a specific timer by its ID
        function startTimer(timerIdToControl) {
            const timerState = activeTimers[timerIdToControl];
            if (!timerState || timerState.intervalId) return; // Already running or no state

            // Set isRunning flag to true
            timerState.isRunning = true;

            // Update modal buttons if this is the active modal timer
            if (activeModalTimerId === timerIdToControl) {
                $('#startButton').prop('disabled', true);
                $('#pauseButton').prop('disabled', false);
            }

            timerState.intervalId = setInterval(() => {
                if (timerState.remaining > 0) {
                    timerState.remaining--;
                    updateTimerDisplay(timerIdToControl); // Update modal and table cell
                } else {
                    clearInterval(timerState.intervalId);
                    timerState.intervalId = null;
                    timerState.isRunning = false; // Timer finished
                    // Re-enable start button and disable pause button if this was the active modal timer
                    if (activeModalTimerId === timerIdToControl) {
                        $('#startButton').prop('disabled', false);
                        $('#pauseButton').prop('disabled', true);
                    }
                    updateTimerDisplay(timerIdToControl); // Final update when timer reaches 0
                }
            }, 1000);

            // If a popup window exists for this timer, tell it to start
            if (timerState.popupWindow && !timerState.popupWindow.closed && typeof timerState.popupWindow.startWindowTimer === 'function') {
                timerState.popupWindow.startWindowTimer();
            }
        }

        // Pause a specific timer by its ID
        function pauseTimer(timerIdToControl) {
            const timerState = activeTimers[timerIdToControl];
            if (!timerState || !timerState.intervalId) return; // Not running or no state

            clearInterval(timerState.intervalId);
            timerState.intervalId = null;
            timerState.isRunning = false; // Set isRunning flag to false

            // Update modal buttons if this is the active modal timer
            if (activeModalTimerId === timerIdToControl) {
                $('#startButton').prop('disabled', false);
                $('#pauseButton').prop('disabled', true);
            }
            updateTimerDisplay(timerIdToControl); // Update display after pausing

            // If a popup window exists for this timer, tell it to pause
            if (timerState.popupWindow && !timerState.popupWindow.closed && typeof timerState.popupWindow.pauseWindowTimer === 'function') {
                timerState.popupWindow.pauseWindowTimer();
            }
        }

        // Reset a specific timer by its ID
        function resetTimer(timerIdToControl) {
            const timerState = activeTimers[timerIdToControl];
            if (!timerState) return;

            pauseTimer(timerIdToControl); // First pause any running timer
            timerState.remaining = timerState.totalDuration; // Reset to original duration
            timerState.isRunning = false; // Ensure it's not marked as running after reset
            updateTimerDisplay(timerIdToControl); // Update display after resetting

            // If a popup window exists for this timer, tell it to reset
            if (timerState.popupWindow && !timerState.popupWindow.closed && typeof timerState.popupWindow.resetWindowTimer === 'function') {
                timerState.popupWindow.resetWindowTimer();
            }
        }

        // Updates the timer display in both the modal and the corresponding table cell
        function updateTimerDisplay(timerId) {
            const timerState = activeTimers[timerId];
            if (!timerState) return;

            const formattedTime = formatTime(timerState.remaining);

            // Update modal timer if this is the active modal timer
            if (activeModalTimerId === timerId) {
                $('#modalTimer').text(formattedTime);
            }

            // Update table cell timer
            const tableTimerElement = $(`#${timerId}`);
            if (tableTimerElement.length) {
                tableTimerElement.text(formattedTime);
                if (timerState.isRunning) { // Check isRunning flag
                    tableTimerElement.addClass('timer-running');
                } else {
                    tableTimerElement.removeClass('timer-running');
                }
            }
        }

        // Function to be called by the popup window to update the main window's timer state
        window.updateMainTimerDisplay = function(remaining, timerIdFromPopup, isRunningFromPopup) {
            const timerState = activeTimers[timerIdFromPopup];
            if (timerState) {
                timerState.remaining = remaining;
                timerState.isRunning = isRunningFromPopup;
                updateTimerDisplay(timerIdFromPopup); // Update the cell display
                // If the modal is open for this timer, also update its display
                if (activeModalTimerId === timerIdFromPopup) {
                    $('#modalTimer').text(formatTime(remaining));
                    if (isRunningFromPopup) {
                        $('#startButton').prop('disabled', true);
                        $('#pauseButton').prop('disabled', false);
                    } else {
                        $('#startButton').prop('disabled', false);
                        $('#pauseButton').prop('disabled', true);
                    }
                }
            }
        };

        // Initialize
        $(document).ready(function() {
            // Set the initial active game to the first game available, or 'badminton' as a fallback, and ensure it's lowercase.
            if (Object.keys(sportData).length > 0) {
                currentGame = Object.keys(sportData)[0].toLowerCase();
                // Also ensure the corresponding tab is active on load
                $(`.game-tab[data-game="${currentGame}"]`).addClass('active');
            } else {
                // If no sport data, default to 'badminton' and activate its tab
                currentGame = 'badminton';
                $('.game-tab[data-game="badminton"]').addClass('active');
            }

            updateCalendar();

            // Game tab switching
            $('.game-tab').click(function() {
                $('.game-tab').removeClass('active');
                $(this).addClass('active');

                currentGame = $(this).data('game'); // data-game is already lowercase from PHP
                updateCalendar();
            });

            // Date navigation
            $('#prevDay').click(function() {
                currentDate.setDate(currentDate.getDate() - 1);
                updateCalendar();
            });

            $('#nextDay').click(function() {
                currentDate.setDate(currentDate.getDate() + 1);
                updateCalendar();
            });

            // Delegate click to dynamically created elements for available slots
            $(document).on('click', '.time-slot.available', function() {
                const time = $(this).data('time');
                const display = $(this).data('display');
                const court = $(this).data('court');
                const date = formatDate(currentDate);
                const dateKey = formatDateKey(currentDate);

                $('#modalGame').text(currentGame.charAt(0).toUpperCase() + currentGame.slice(1));
                $('#modalDate').text(date);
                $('#modalTime').text(display);
                $('#modalCourt').text(court);

                // Store for use on submit
                $('#bookingForm').data('bookingInfo', {
                    game: currentGame,
                    dateKey: dateKey,
                    time: time,
                    court: court
                });

                // Reset form
                $('#playerName').val('');
                $('#phoneNumber').val('');
                $('#status').val('Confirmed');
                $('#permanent').prop('checked', false);

                const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
                bookingModal.show();
            });

            // Delegate click to dynamically created elements for booked slots (to open timer modal)
            $(document).on('click', '.time-slot.booked', function() {
                const timerId = $(this).data('timer-id');
                setActiveTimer(timerId); // Set the active timer for the modal
                const timerModal = new bootstrap.Modal(document.getElementById('timerModal'));
                timerModal.show();
            });

            // Form submission for new booking
            $('#bookingForm').on('submit', function(e) {
                e.preventDefault();

                const { game, dateKey, time, court } = $(this).data('bookingInfo');
                const playerName = $('#playerName').val();
                const phoneNumber = $('#phoneNumber').val();
                const status = $('#status').val();
                const isPermanent = $('#permanent').is(':checked');

                // Create sportData path if it's not exist
                if (!sportData[game]) sportData[game] = {};
                if (!sportData[game][dateKey]) sportData[game][dateKey] = {};
                if (!sportData[game][dateKey][court]) sportData[game][dateKey][court] = {};

                // Calculate end time (assuming 1 hour duration for new bookings, adjust if needed)
                // Ensure to handle cases where time is at the end of the day, or if it crosses midnight
                const [startHour, startMinute] = time.split(':').map(Number);
                let endHour = startHour + 1;
                let endMinute = startMinute;

                // If endHour goes beyond 23 (11 PM), cap it at 23:00 if it extends beyond a single day's representation
                // For simplicity here, assuming a booking cannot cross midnight within the current display logic.
                // If a booking can go from 23:00 to 00:00 (next day), more complex date arithmetic is needed.
                if (endHour >= 24) {
                    endHour = 23;
                    endMinute = 59;
                }

                const endTime = `${endHour.toString().padStart(2, '0')}:${endMinute.toString().padStart(2, '0')}:00`;


                // Add the booking
                sportData[game][dateKey][court][time] = {
                    end: endTime,
                    player: playerName,
                    phone: phoneNumber,
                    status: status,
                    permanent: isPermanent
                };

                // Close modal and update calendar
                $('#bookingModal').modal('hide');
                updateCalendar();
            });

            // When timer modal is hidden, clear the active modal timer, but do NOT pause the timer interval
            $('#timerModal').on('hidden.bs.modal', function() {
                activeModalTimerId = null; // Clear active modal timer
            });

            // Handle "New window" button click
            $('#newWindowBtn').on('click', function() {
                if (activeModalTimerId) {
                    const timerState = activeTimers[activeModalTimerId];

                    // Close any existing popup window for this timer first
                    if (timerState.popupWindow && !timerState.popupWindow.closed) {
                        timerState.popupWindow.close();
                        timerState.popupWindow = null;
                    }

                    // Get the Bootstrap modal instance for the timer modal
                    const timerModalElement = document.getElementById('timerModal');
                    const timerModalInstance = bootstrap.Modal.getInstance(timerModalElement) || new bootstrap.Modal(timerModalElement);

                    // Remove focus from the button before opening new window (still good practice for accessibility)
                    $(this).blur();

                    // Open the new window using the working URL
                    const newWindow = window.open(
                        'http://127.0.0.1:8000/storage/staff/timer_window.html', // Use the full URL that works for you
                        activeModalTimerId, // Use timerId as window name for easy reference
                        'width=450,height=350,resizable=yes,scrollbars=no'
                    );

                    if (newWindow) {
                        timerState.popupWindow = newWindow; // Store reference to the new window
                        // Pass data to the new window once it's loaded
                        newWindow.onload = () => {
                            if (newWindow.receiveTimerData) {
                                newWindow.receiveTimerData({
                                    timerId: activeModalTimerId,
                                    totalDuration: timerState.totalDuration,
                                    remaining: timerState.remaining,
                                    isRunning: timerState.isRunning,
                                    player: timerState.player,
                                    game: timerState.game,
                                    startTimeDisplay: timerState.startTimeDisplay
                                });
                            }
                        };
                        // >>> IMPORTANT: UNCOMMENT THE LINE BELOW IF YOU WANT THE MODAL TO CLOSE <<<
                        // timerModalInstance.hide();
                    } else {
                        // This alert will trigger if the browser blocks the popup
                        alert('Popup blocked! Please allow popups for this site.');
                    }
                }
            });
        });

        // Update the calendar on initial load
        // Initial setup for the active game tab based on the first key in sportData
        document.addEventListener('DOMContentLoaded', () => {
            if (Object.keys(sportData).length > 0) {
                currentGame = Object.keys(sportData)[0].toLowerCase();
                // Remove 'active' class from all tabs
                document.querySelectorAll('.game-tab').forEach(tab => tab.classList.remove('active'));
                // Add 'active' class to the correct initial tab
                const initialTab = document.querySelector(`.game-tab[data-game="${currentGame}"]`);
                if (initialTab) {
                    initialTab.classList.add('active');
                }
            }
            updateCalendar();
        });

    </script>
</body>
</html>

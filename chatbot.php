<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $userMessage = strtolower(trim($_POST['message']));


    $responses = [
        // Greetings
        "hi" => "Hello! How can I help you today?",
        "hello" => "Hi there! What can I do for you?",
        "hey" => "Hey! Need help booking a ride?",
        "good morning" => "Good morning! Ready to book a ride?",
        "good afternoon" => "Good afternoon! How may I assist?",
        "good evening" => "Good evening! Need a vehicle?",

        // Booking Intent
        "book" => "You can book your ride from the 'Book Now' section.",
        "i want to book" => "Please go to the 'Book Now' page to start your ride.",
        "how to book" => "Just fill in your pickup and destination, then confirm.",
        "booking" => "Sure! Head to the booking page to begin.",
        "can i book" => "Absolutely, bookings are open 24/7.",

        // Vehicle Availability
        "available" => "Yes, vehicles are available for most locations.",
        "vehicle available" => "Please provide pickup and destination to check availability.",
        "is any vehicle available" => "Yes, we have vehicles ready. Try booking one.",
        "are there vehicles" => "Yes, you can choose from multiple options.",
        "check availability" => "You can check real-time availability on our 'Book Now' page.",

        // Fare & Cost
        "fare" => "Our fare starts at Rs. 20 for the first 2KM, then Rs. 18/km.",
        "how much" => "Cost depends on distance. You’ll see a breakdown before confirming.",
        "price" => "Prices are calculated based on the distance and type of vehicle.",
        "charge" => "Standard charges apply. You can estimate on the booking screen.",
        "cost" => "Your estimated cost will be shown once pickup and destination are selected.",
        "how much does it cost" => "It depends on the distance. Estimation appears during booking.",

        // Status
        "status" => "You can check your booking status in 'My Bookings'.",
        "check status" => "Login and head to 'My Bookings' to check status.",
        "pending" => "Your booking might still be pending. Please wait or check status.",
        "approved" => "Approved bookings will show under 'My Bookings'.",
        "completed" => "Completed rides appear in your history.",

        // Cancellation
        "cancel" => "You can cancel a booking before it’s approved from 'My Bookings'.",
        "how to cancel" => "Go to 'My Bookings' and click cancel.",
        "cancel booking" => "Yes, you can cancel it from your dashboard.",
        "i want to cancel" => "Login and cancel your ride before approval.",

        // Payment
        "pay" => "Payments can be made through eSewa or cash.",
        "payment" => "We support eSewa and cash payments after ride completion.",
        "online payment" => "Yes, you can pay using eSewa during checkout.",
        "how to pay" => "Choose your method during booking i.e eSewa or Cash.",

        // Thank You / Ending
        "thank you" => "You're welcome! Feel free to ask anything else.",
        "thanks" => "Glad to help!",
        "bye" => "Goodbye! Have a safe journey.",
        "goodbye" => "Take care!",
        "see you" => "See you soon!",

        // Help & Support
        "help" => "I'm here to assist! Ask me about booking, payments, or ride info.",
        "support" => "Please describe your issue so I can assist.",
        "i need help" => "Sure, how can I assist you today?",
        "what can you do" => "I can help you book a ride, check status, and more!",
        "i have an issue" => "Sorry to hear that. Tell me more about the issue.",

        // General Questions
        "who are you" => "I’m your booking assistant bot.",
        "what is this" => "This is a vehicle booking system chatbot.",
        "where am i" => "You're in the vehicle booking assistant.",
        "what can i do here" => "You can book, cancel, and track rides from here.",
        "what services do you provide" => "We provide ride booking, cancellation, and support.",

        // Location / Map
        "map" => "The map will help you set pickup and drop locations.",
        "location" => "Please enter your pickup and destination locations.",
        "where to" => "Enter your destination to proceed.",
        "current location" => "Make sure location access is enabled.",
        "destination" => "Type in your destination to calculate route and cost.",

        // Driver Questions
        "who is my driver" => "Driver details will be shown once the booking is approved.",
        "driver info" => "Driver’s name and contact will appear on confirmation.",
        "driver number" => "You'll get the number once your booking is approved.",

        // Timing
        "how long" => "Duration depends on traffic and distance. Estimated time shown at booking.",
        "when will it arrive" => "Estimated arrival time is shown after confirming the ride.",
        "ride time" => "Ride duration is based on your route.",
        "how much time" => "Estimated time will be shown before booking.",

        // Rating
        "rate" => "You can rate your ride after completion.",
        "rating" => "After your ride is completed, you’ll be asked to provide a rating.",
        "how to rate" => "Go to your bookings and select the completed ride to rate.",

        // Confirmation
        "confirmed" => "If your booking is confirmed, you’ll see it under 'My Bookings'.",
        "is it booked" => "Check 'My Bookings' to confirm.",
        "i got confirmation" => "Great! You’re all set for the ride.",
        "got it" => "Awesome. Let me know if you need anything else.",

        // Errors / Unknown
        "not working" => "Try refreshing or checking your internet connection.",
        "error" => "Please explain the issue so we can help.",
        "i don’t understand" => "No worries! I’m here to clarify things.",
        "what" => "Can you rephrase that?",
        "why" => "Could you be more specific?",
        "huh" => "Hmm, I didn't catch that. Can you repeat?",

        // Confirmations / Acknowledgements
        "ok" => "Okay! Let me know if you need anything else.",
        "okay" => "Sure! I'm here to help.",
        "fine" => "Got it.",
        "great" => "Happy to help!",
        "awesome" => "Thanks! Glad I could assist.",
        "cool" => "Cool! Let me know if you need anything else.",
        "sure" => "Yes, go ahead!",
        // "yes" => "Perfect. Proceed to booking.",
        "no" => "Alright, let me know if you change your mind.",
    ];


    $reply = "I'm sorry, I didn't understand that. Can you please rephrase?";

    foreach ($responses as $keyword => $response) {
        if (strpos($userMessage, $keyword) !== false) {
            $reply = $response;
            break;
        }
    }

    echo json_encode(["reply" => $reply]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
       

        /* Chatbox Container */
        #chat-container {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 350px;
            max-width: 90%;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            display: none;
            flex-direction: column;
            z-index: 99999999999;
        }

        #chatbox {
            display: flex;
            flex-direction: column;
            min-height: 300px;
            max-height: 500px;
        }

        #chatlog {
            padding: 15px;
            flex: 1;
            overflow-y: auto;
            scroll-behavior: smooth;
        }

        .message {
            margin: 10px 0;
            max-width: 80%;
            padding: 10px 15px;
            border-radius: 20px;
            word-wrap: break-word;
            font-size: 14px;
        }

        .user {
            background-color: #092448;
            color: #fff;
            align-self: flex-end;
            border-bottom-right-radius: 0;
        }

        .bot {
            background-color: #f0f0f0;
            color: #333;
            align-self: flex-start;
            border-bottom-left-radius: 0;
        }

        #inputArea {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ccc;
            background: #fafafa;
        }

        input[type="text"] {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 30px;
            outline: none;
            font-size: 14px;
        }

        button#sendBtn {
            background-color: #092448;
            border: none;
            color: white;
            padding: 10px 16px;
            border-radius: 50%;
            margin-left: 10px;
            cursor: pointer;
            font-size: 18px;
            transition: background 0.3s;
        }

        button#sendBtn:hover {
            background-color: #0d325e;
        }

        /* Chat Icon Button */
        #chat-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #092448;
            color: white;
            width: 55px;
            height: 55px;
            border-radius: 50%;
            border: none;
            font-size: 22px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: background 0.3s ease;
            z-index: 99999999999;
        }

        #chat-toggle:hover {
            background-color: #0d325e;
        }
    </style>
</head>

<body>

    <!-- Chat Toggle Button -->
    <button id="chat-toggle" onclick="toggleChat()">
        <i class="fas fa-comment-dots"></i>
    </button>

    <!-- Chat Window -->
    <div id="chat-container">
        <div id="chatbox">
            <div id="chatlog"></div>
            <div id="inputArea">
                <input type="text" id="userInput" placeholder="Type your message..." />
                <button id="sendBtn" onclick="sendMessage()">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        function toggleChat() {
            const chat = document.getElementById("chat-container");
            chat.style.display = chat.style.display === "none" ? "flex" : "none";
        }

        function appendMessage(content, sender) {
            const chatlog = document.getElementById("chatlog");
            const div = document.createElement("div");
            div.className = "message " + sender;
            div.textContent = content;
            chatlog.appendChild(div);
            chatlog.scrollTop = chatlog.scrollHeight;
        }

        function sendMessage() {
            const input = document.getElementById("userInput");
            const message = input.value.trim();
            if (!message) return;

            appendMessage(message, "user");
            input.value = "";

            fetch(window.location.href, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "message=" + encodeURIComponent(message)
            })
            
                .then(response => response.json())
                .then(data => {
                    appendMessage(data.reply, "bot");
                })
                .catch(() => {
                    appendMessage("Error connecting to the chatbot.", "bot");
                });
        }

        // script to submit the message to the bot when enter key is pressed from the keyboard.
        document.getElementById("userInput").addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                sendMessage();
            }
        });

       
    </script>

</body>

</html>
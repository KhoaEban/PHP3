<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trò chuyện với BookBot</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFFFFF;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 580px;
        }
        #chat-box {
            background-color: #fff;
            border-radius: 8px;
            width: 100%;
            max-width: 500px;
            height: 580px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        #messages {
            flex-grow: 1;
            padding: 15px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .message {
            padding: 10px 15px;
            margin-bottom: 10px;
            border-radius: 18px;
            clear: both;
            word-break: break-word;
        }

        .ai-message {
            background-color: #e0f7fa;
            color: #00838f;
            align-self: flex-start;
        }

        .user-message {
            background-color: #e8eaf6;
            color: #1a237e;
            align-self: flex-end;
        }

        .input-container {
            padding: 10px;
            display: flex;
            border-top: 1px solid #eee;
        }

        #message-input {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            margin-right: 10px;
            outline: none;
        }

        button.send-btn {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        button.send-btn:hover {
            background-color: #45a049;
        }

        #loading-indicator {


            text-align: center;
            padding: 10px;
            color: #777;
            font-size: 0.9em;
        }

        /* Scrollbar customization */
        #messages::-webkit-scrollbar {
            width: 8px;
        }

        #messages::-webkit-scrollbar-track {
            background-color: #f1f1f1;
            border-radius: 4px;
        }

        #messages::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 4px;
        }

        #messages::-webkit-scrollbar-thumb:hover {
            background-color: #bbb;
        }

        .navbar-chat {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
        }

        .img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div id="chat-box">
        <div class="navbar-chat">
            <img class="img" src="{{ asset('images/pngtree-smart-chatbot-cartoon-clipart-png-image_9015126.png') }}" alt="Logo">
        </div>
        <div id="messages">
            @if (Auth::check())
                <p class="ai-message"><strong>BookBot:</strong> Chào {{ Auth::user()->name }}, tôi có thể giúp gì về sách
                    hoặc đơn hàng của bạn?</p>
            @else
                <p class="ai-message"><strong>BookBot:</strong> Chào bạn, hãy đăng nhập để khám phá thêm về sách hoặc hỏi
                    tôi bất cứ điều gì!</p>
            @endif
        </div>
        <div id="loading-indicator" style="display: none;">Đang chuẩn bị câu trả lời...</div>
        <div class="input-container">
            <input type="text" id="message-input" placeholder="Nhập tin nhắn...">
            <button class="send-btn" onclick="sendMessage()">Gửi</button>
        </div>
    </div>
    <script>
        const messagesDiv = document.getElementById('messages');
        const loadingIndicator = document.getElementById('loading-indicator');
        const messageInput = document.getElementById('message-input');

        function displayMessage(message, isUser = false) {
            const senderClass = isUser ? 'user-message' : 'ai-message';
            const senderName = isUser ? 'Bạn' : 'BookBot';
            messagesDiv.insertAdjacentHTML('beforeend',
                `<p class="message ${senderClass}"><strong>${senderName}:</strong> ${message}</p>`
            );
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        function loadChatHistory() {
            axios.get('/chat/history')
                .then(response => {
                    if (response.data.history) {
                        messagesDiv.innerHTML = '';
                        response.data.history.forEach(message => {
                            if (message && typeof message.message === 'string') {
                                displayMessage(message.message, message.is_user);
                            } else {
                                console.warn('Invalid message data:', message);
                            }
                        });
                        messagesDiv.scrollTop = messagesDiv.scrollHeight;
                    } else if (response.data.error) {
                        displayMessage(response.data.error);
                    } else {
                        console.warn('Unexpected history response:', response.data);
                    }
                })
                .catch(error => {
                    console.error('Lỗi tải lịch sử chat:', error);
                    displayMessage('Không thể tải lịch sử chat.');
                });
        }

        function sendMessage() {
            const userMessage = messageInput.value.trim();
            if (!userMessage) return;

            displayMessage(userMessage, true);
            messageInput.value = '';
            loadingIndicator.style.display = 'block';

            axios.post('/chat/send', {
                    message: userMessage
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                })
                .then(response => {
                    console.log('Send response:', response.data);
                    if (response.data && typeof response.data.reply === 'string') {
                        displayMessage(response.data.reply, false);
                    } else {
                        console.warn('Unexpected send response:', response.data);
                        displayMessage('Phản hồi không hợp lệ từ server.');
                    }
                })
                .catch(error => {
                    console.error('Lỗi gửi tin nhắn:', error);
                    let errorMsg = 'Không thể nhận phản hồi.';
                    if (error.response && error.response.data && error.response.data.error) {
                        errorMsg = error.response.data.error;
                    }
                    displayMessage(errorMsg);
                })
                .finally(() => {
                    loadingIndicator.style.display = 'none';
                });
        }
        document.addEventListener('DOMContentLoaded', () => {
            @if (Auth::check())
                loadChatHistory();
            @endif
        });

        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        messageInput.focus();
    </script>
</body>

</html>

<!DOCTYPE html>
<html>
<head>
    <title>Chat HaloDIKA</title>
    <style>
        .chat-popup {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            font-family: Arial, sans-serif;
        }

        .chat-box {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            padding: 12px 16px;
            margin-bottom: 8px;
            display: flex;
            flex-direction: column;
            max-width: 240px;
        }

        .chat-header {
            background-color: #25D366;
            color: white;
            padding: 8px 12px;
            border-radius: 8px 8px 0 0;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-body {
            padding: 10px;
            font-size: 14px;
            color: #333;
        }

        .chat-icon {
            width: 60px;
            cursor: pointer;
        }

        .close-btn {
            cursor: pointer;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Popup Chat -->
    <div class="chat-popup" id="chatPopup">
        <div class="chat-box" id="chatBox">
            <div class="chat-header">
                HaloDIKA
                <span class="close-btn" onclick="document.getElementById('chatBox').style.display='none';">&times;</span>
            </div>
            <div class="chat-body">
                Ada Pertanyaan?<br>
                Chat Sekarang di HaloDIKA!
            </div>
        </div>
        <img src="<?= base_url('assets/images/whatsapp-logo.png') ?>" alt="Chat" class="chat-icon" onclick="openWhatsApp();">
    </div>

    <script>
        function openWhatsApp() {
            window.open('https://wa.me/628118886325', '_blank');
        }
    </script>

</body>
</html>

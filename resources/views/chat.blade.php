<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat App - Bootdey.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
</head>

<body>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <div class="container">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card chat-app">
                    <div id="plist" class="people-list">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Search...">
                        </div>
                        <ul class="list-unstyled chat-list mt-2 mb-0">
                            @foreach ($conversations as $conversation)
                                <a href="javascript:void(0)"
                                    onclick="getConversationDetail('{{ $conversation['id'] }}')">
                                    <li class="clearfix">
                                        @php
                                            $participant = $conversation['participants']['data'][0];
                                        @endphp
                                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="avatar">
                                        <div class="about">
                                            <div class="participant-name"
                                                style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;"
                                                title="{{ $participant['name'] }}">{{ $participant['name'] }}</div>
                                            <div class="status"> <i class="fa fa-circle offline"></i> left 7 mins ago
                                            </div>
                                        </div>
                                    </li>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                    <div class="chat">
                        <div class="chat-header clearfix">
                            <div class="row">
                                <div class="col-lg-6">
                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                        <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
                                    </a>
                                    <div class="chat-about">
                                        <h6 class="m-b-0">Aiden Chavez</h6>
                                        <small>Last seen: 2 hours ago</small>
                                    </div>
                                </div>
                                <div class="col-lg-6 hidden-sm text-right">
                                    <a href="javascript:void(0);" class="btn btn-outline-secondary"><i
                                            class="fa fa-camera"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-outline-primary"><i
                                            class="fa fa-image"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-outline-info"><i
                                            class="fa fa-cogs"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-outline-warning"><i
                                            class="fa fa-question"></i></a>
                                </div>
                            </div>
                        </div>
                        <div id="chat-history-container" class="chat-history">
                            <!-- Chat history will be updated dynamically here -->
                        </div>
                        <div class="chat-message clearfix">
                            <div class="input-group mb-0">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-send"></i></span>
                                </div>
                                <textarea class="form-control" id="message" name="message" rows="3" placeholder="Enter text here..."></textarea>

                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image" name="image"
                                        accept="image/*">
                                    <label class="custom-file-label" for="image">Choose file</label>
                                </div>

                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" id="sendMessageBtn"
                                        onclick="sendMessage()">Send Message</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
        var currentRecipientId;
        var currentConversationId;

        $(document).ready(function() {
            // Xử lý sự kiện khi người dùng chọn một tệp tin hình ảnh
            $('#imageInput').on('change', function() {
                var fileInput = $(this)[0];
                var fileName = fileInput.files[0].name;

                // Hiển thị tên tệp tin đã chọn trong label
                $('.custom-file-label').html(fileName);
            });
            //         var pollingInterval = 5000;

            // // Start polling
            // setInterval(function () {
            //     if (currentConversationId) {
            //         getConversationDetail(currentConversationId);
            //     }
            // }, pollingInterval);
        });

        function getConversationDetail(conversationId) {
            currentRecipientId = null;
            currentConversationId = conversationId;

            $.ajax({
                type: 'GET',
                url: '/mess-details/' + conversationId,
                success: function(response) {
                    console.log(response);
                    updateChatHistory(response.messages, response.pageId);
                    currentRecipientId = response.participants;
                },
                error: function(error) {
                    console.error('Error getting conversation detail:', error);
                }
            });
        }

        function sendMessage() {
            // Thiết lập các tiêu đề mặc định với CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            if (currentRecipientId) {
                var messageText = $('#message').val();
                var imageData = $('#image')[0].files[0];

                // Sử dụng FormData để xử lý tải lên tệp tin
                var formData = new FormData();
                formData.append('recipientId', currentRecipientId);
                formData.append('messageText', messageText);
                formData.append('image', imageData);

                $.ajax({
                    type: 'POST',
                    url: '/send-message',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log('Phản hồi thành công:', response);
                        getConversationDetail(currentConversationId);
                        $('#message').val('');
                        $('#image').val('');
                    },
                    error: function(error) {
                        console.error('Phản hồi lỗi:', error.responseJSON);

                        // Xử lý lỗi khi gửi tin nhắn
                        alert('Đã xảy ra lỗi khi gửi tin nhắn: ' + error.responseJSON.message);

                        // In ra thông điệp lỗi chi tiết từ Facebook API
                        if (error.responseJSON.error && error.responseJSON.error.message) {
                            console.error('Lỗi API Facebook:', error.responseJSON.error.message);
                        }
                    }
                });
            } else {
                // Xử lý khi chưa chọn đối tượng nhận tin nhắn
                console.log('Vui lòng chọn đối tượng nhận tin nhắn.');
            }
        }

        function updateChatHistory(messages, pageId) {
            var chatHistoryContainer = $(".chat-history");

            messages.sort(function(a, b) {
                return new Date(a.created_time) - new Date(b.created_time);
            });

            chatHistoryContainer.empty();

            messages.forEach(function(message) {
                var formattedTime = moment(message.created_time).format('[Ngày] DD [tháng] MM, YYYY HH:mm ');
                var isMyMessage = message.from.id === pageId;

                var messageHTML = `
                <div style="text-align: ${isMyMessage ? 'right' : 'left'};">
                    <span class="timestamp">${formattedTime}</span>
                    <p>${message.message}</p>
                    ${message.sticker ? `<img src="${message.sticker}" alt="Sticker">` : ''}
                    ${getAttachmentHTML(message.attachments)}
                </div><br>`;

                chatHistoryContainer.append(messageHTML);
            });

            scrollChatToBottom();
        }

        function getAttachmentHTML(attachments) {
            var attachmentHTML = '';

            if (attachments && attachments.data && attachments.data.length > 0) {
                var imageData = attachments.data[0].image_data;

                if (imageData) {
                    attachmentHTML += `<img src="${imageData.url}" alt="Ảnh">`;
                }
            }

            return attachmentHTML;
        }

        function scrollChatToBottom() {
            var chatHistoryContainer = $(".chat-history");
            chatHistoryContainer.scrollTop(chatHistoryContainer[0].scrollHeight);
        }
    </script>

</body>

</html>

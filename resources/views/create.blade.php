<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="relative flex justify-center mt-2">
        <div id="success-message" class="absolute px-4 py-2 text-white rounded-lg bg-cyan-500 w-fit" style="display: none;"></div>
    </div>
    <div class="grid grid-cols-1 gap-8 px-10 mt-8 md:grid-cols-2">
        <div class="w-full px-10 py-10 border shadow-lg rounded-3xl h-fit">
            <h2 class="mb-8 text-xl font-bold capitalize text-slate-800">Upload User Information</h2>

            <form id="user-form" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="user_id" id="user_id">
                <input type="text" name="name" id="name" class="w-full px-4 py-2 border-b border-slate-300 text-semibold" placeholder="Enter user name" required>
                <input type="email" name="email" id="email" class="w-full px-4 py-2 border-b border-slate-300 text-semibold" placeholder="Enter user email" required>
                <input type="password" name="password" id="password" class="w-full px-4 py-2 border-b border-slate-300 text-semibold" placeholder="Enter a password">
                <input type="file" name="image" id="image" class="w-full">
                <div id="form-buttons" class="space-x-2">
                    <button type="submit" id="submit-btn" class="px-10 py-2 font-semibold text-white capitalize border-b bg-cyan-500">Submit</button>
                </div>
            </form>
        </div>

        <div class="w-full px-10 py-10 border shadow-lg h-fit rounded-3xl">
            <h3 class="mb-8 text-xl font-bold capitalize text-slate-800">User List</h3>
            <div id="user-list">
                @include('partials.user-list', ['users' => $users])
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var token = $('meta[name="csrf-token"]').attr('content');

            function showMessage(message, isError = false) {
                var messageElement = $('#success-message');
                messageElement.removeClass('text-green-800 bg-green-100 text-red-800 bg-red-100');

                if (isError) {
                    messageElement.addClass('text-red-800 bg-red-100');
                } else {
                    messageElement.addClass('text-green-800 bg-green-100');
                }

                messageElement.text(message).show();

                setTimeout(function() {
                    messageElement.fadeOut();
                }, 3000);
            }

            $('#user-form').on('submit', function(event) {
                event.preventDefault();

                var formData = new FormData(this);
                var url = formData.get('user_id') ? '/updateUserData' : '/addUserData';

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    success: function(response) {
                        showMessage(response.success);
                        $('#user-form')[0].reset();
                        $('#submit-btn').text('Submit');
                        $('#user_id').val('');
                        $('#user-list').html(response.users);
                        $('#discard-btn').remove();
                    },
                    error: function(response) {
                        var errors = response.responseJSON.errors;
                        var errorMessage = '';
                        for (var key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                errorMessage += errors[key] + '\n';
                            }
                        }
                        showMessage(errorMessage, true);
                    }
                });
            });

            $(document).on('click', '.edit-btn', function() {
                var userId = $(this).data('id');

                $.get('/getUserData/' + userId, function(user) {
                    $('#user_id').val(user.id);
                    $('#name').val(user.name);
                    $('#email').val(user.email);
                    $('#password').prop('required', false);
                    $('#submit-btn').text('Update');

                    if (!$('#discard-btn').length) {
                        $('#form-buttons').append('<button type="button" id="discard-btn" class="px-10 py-2 font-semibold text-white capitalize bg-red-500 border-b">Discard</button>');
                    }
                });
            });

            $(document).on('click', '#discard-btn', function() {
                $('#user-form')[0].reset();
                $('#submit-btn').text('Submit');
                $('#user_id').val('');
                $(this).remove();
            });

            $(document).on('click', '.delete-btn', function() {
                var userId = $(this).data('id');

                $.ajax({
                    url: '/deleteUser/' + userId,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    success: function(response) {
                        showMessage(response.success);
                        $('#user-list').html(response.users);
                    },
                    error: function(response) {
                        var errorMessage = response.responseJSON ? response.responseJSON.error : 'An error occurred';
                        showMessage(errorMessage, true);
                    }
                });
            });
        });
    </script>

</body>

</html>
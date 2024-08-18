<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Task List</h2>
        
        <div class="card">
            <div class="card-body">
                <form id="addTaskForm">
                    <div class="form-group">
                        <input type="text" id="taskName" name="name" class="form-control" placeholder="Enter a new task" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Add Task</button>
                </form>
            </div>
        </div>

        <!-- Showing All Task Buton -->
        <div class="text-center mt-4">
            <button id="showAllTasksBtn" class="btn btn-info">Show All Tasks</button>
        </div>

        <!-- Task List -->
        <ul id="taskList" class="list-group mt-4">
            <!-- Tasks will be Displayed here -->
        </ul>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function(){
           //alert();
           $('#addTaskForm').on('submit', function(e){
               e.preventDefault();
               let taskName = $('#taskName').val();
               //alert(taskName);
               $.ajax({
                     type: 'POST',
                     url: '/tasks',
                     data: { name: taskName, _token: '{{ csrf_token() }}' },
                     success:function(response){
                        //console.log(response);
                        $('#taskName').append(
                            `<li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${response.name}</span>
                                <div>
                                    <input type="checkbox" class="mark-completed mr-2" data-id="${response.id}">
                                    <button class="btn btn-danger btn-sm delete-task" data-id="${response.id}">Delete</button>
                                </div>
                            </li>`
                        );
                         $('#taskName').val('');
                     },
               });
           });

           $('#showAllTasksBtn').on('click', function() {
                $.get('/fetch/tasks', function(tasks) {
                    $('#taskList').empty();
                    tasks.forEach(task => {
                        $('#taskList').append(
                            `<li class="list-group-item d-flex justify-content-between align-items-center ${task.completed ? 'completed' : ''}">
                                <span>${task.name}</span>
                                <div>
                                    <input type="checkbox" class="mark-completed mr-2" data-id="${task.id}" ${task.completed ? 'checked' : ''}>
                                    <button class="btn btn-danger btn-sm delete-task" data-id="${task.id}">Delete</button>
                                </div>
                            </li>`
                        );
                    });
                });
            });


            $(document).on('change', '.mark-completed', function(){
                //alert();
                let taskId = $(this).data('id');
                let isCompleted = $(this).is(':checked') ? 1 : 0;
                //alert(isCompleted);
                $.ajax({
                    type: 'PATCH',
                    url: `/tasks/${taskId}`,
                    data: { completed: isCompleted, _token: '{{ csrf_token() }}' },
                    success: () => {
                        $(this).closest('li').toggleClass('completed', isCompleted);
                    }
                });
            });

            $(document).on('click', '.delete-task', function() {
                let taskId = $(this).data('id');
                
                if (confirm('Are you want to dlete?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: `/tasks/${taskId}`,
                        data: { _token: '{{ csrf_token() }}' },
                        success: function() {
                            $(`button[data-id="${taskId}"]`).closest('li').remove();
                        }
                    });
                }
            });

        });
    </script>
</body>
</html>
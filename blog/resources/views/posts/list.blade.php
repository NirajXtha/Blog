<x-app-layout>
    <div class="container">
        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Posts') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <input type="text" id="success" value="{{ session('success') }}" readonly hidden>
        <input type="text" id="error" value="{{ session('error') }}" readonly hidden>

        <a href="{{ route('post.create') }}" class="btn btn-primary mb-4">Create Post</a>
        <table id="postTable" class="table table-striped" style="width:100%" data-posts="{{ json_encode($posts) }}">
            <thead>
                <th>SN</th>
                <th>Title</th>
                <th>Description</th>
                <th>Action</th>
            </thead>
            <tbody>
                @php
                    $serialNumber = 1;
                @endphp
                @foreach ($posts as $post)
                    <tr>
                        <td>{{ $serialNumber++ }}</td>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->description }}</td>
                        <td>
                            <a href="{{ route('post.edit', ['postid' => $post->id]) }}"
                                class="btn btn-primary mb-4">Edit</a>
                            <span class="btn btn-danger mb-4 post-delete-btn"
                                data-id="{{ $post->id }}">Delete</span>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
        </container>
    </div>
</x-app-layout>

<script>
    
    $(document).ready(function() {
      
        new DataTable('#postTable');
        if ($('#success').val() != '') {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: $('#success').val(),
                showConfirmButton: false,
                timer: 1500
            });
        }

        if ($('#error').val() != '') {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: $('#error').val(),
                showConfirmButton: false,
                timer: 1500
            });
        }
        
        $(".post-delete-btn").click(function() {
            let postid = $(this).attr('data-id');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/post/delete/' + postid,
                        success: function(result) {
                            let message = JSON.parse(result);

                            if (message.status) {

                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Your post has been deleted.",
                                    icon: "success"
                                });
                                //Table reload function
                                $(function() {
                                    var posts = ($('#postTable').data(
                                        'posts'));
                                    
                                    let table = $('#postTable').DataTable();

                                    // Clear existing data
                                    table.clear().draw();
                                    var serialNum = 0;
                                    // Add new data to the table
                                    $.each(posts, function(index, post) {
                                        try{
                                            if (postid != post.id) {
                                                serialNum = serialNum + 1;
                                                var newRow = [
                                                    serialNum,
                                                    post.title,
                                                    post
                                                    .description,
                                                    '<a href="/admin/post/edit/' +
                                                    post.id +
                                                    '" class="btn btn-primary mb-4">Edit</a> <span class="btn btn-danger mb-4 post-delete-btn" data-id="' +
                                                    post.id +
                                                    '">Delete</span>'
                                                ];
                                            }
                                            table.row.add(newRow);
                                            // Redraw table
                                            table.draw();
                                        }catch{
                                            index = index - 1;
                                            console.log("Row Deleted!");

                                        }
                                    });

                                    
                                    
                                });

                            } else {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Something went weong!",
                                    icon: "error"
                                });
                            }
                        }
                    })

                }
            });
        });
    });
</script>

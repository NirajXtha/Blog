<x-app-layout>
    <div class="container">
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Posts') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    
        <input type="text" id="success" value="{{ session('success')}}" readonly hidden>
        <input type="text" id="error" value="{{ session('error')}}" readonly hidden>
        <a href="{{ route('post.create') }}" class="btn btn-primary mb-4">Create Post</a>
        <table id="postTable" class="table table-striped" style="width:100%">
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
                        <a href="{{ route('post.edit',['postid' => $post->id]) }}" class="btn btn-primary mb-4">Edit</a>
                        <a href="{{ route('post.delete',['postid' => $post->id]) }}" class="btn btn-primary mb-4">Delete</a>
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
    });
</script>
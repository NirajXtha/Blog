<style>

    .swal2-html-container{
        color:white;
    }
</style>

<x-app-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Posts') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="container">
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
            </tbody>
        </table>
    </div>
</x-app-layout>

<script>
    $(document).ready(function() {
        fetchDataList();
        function fetchDataList() {
            var table = $('#postTable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                url:  '/admin/post/serversidelist/',
            },
                    "columnDefs": [
                        { className: " action-btn-gap ", "targets": [-1] }
                    ],
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'title', name: 'title'},
                    {data: 'description', name: 'description'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]

            });
        }


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
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });


        $(".delete-button").click(function(){
            let postid = $(this).attr('data-id');
            
        swalWithBootstrapButtons.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
               $.ajax({url: '/admin/post/delete/' + postid,
                success:function(result){
                    let message = JSON.parse(result);
                    console.log(message);
                    if(message.status){
                        swalWithBootstrapButtons.fire({
                            title: "Deleted!",
                            text: message.message,
                            icon: "success"
                        });

                        window.location.href = "/admin/post/list/";
                    }else{
                        swalWithBootstrapButtons.fire({
                            title: "Error",
                            text: message.message,
                            icon: "error"
                        });
                    }

               }})
                
            } else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire({
                    title: "Cancelled",
                    text: "Post delete cancelled",
                    icon: "error"
                });
            }
        });
        });

     
    });
</script>
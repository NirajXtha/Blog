<style>

    .swal2-html-container{
        color:white;
    }
</style>

<x-app-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Groups') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="container">
        <input type="text" id="success" value="{{ session('success')}}" readonly hidden>
        <input type="text" id="error" value="{{ session('error')}}" readonly hidden>
        <a href="{{ route('group.create') }}" class="btn btn-primary mb-4">Create Group</a>
        <table id="groupTable" class="table table-striped" style="width:100%">
            <thead>
                <th>SN</th>
                <th>Title</th>
                <th>Action</th>
            </thead>
            <tbody>
                @php
                $serialNumber = 1;
                @endphp
                @foreach ($groups as $group)
                <tr>
                    <td>{{ $serialNumber++ }}</td>
                    <td>{{ $group->title }}</td>
                    <td>
                       
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>

<script>
    $(document).ready(function() {
        new DataTable('#groupTable');
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


      

     
    });
</script>
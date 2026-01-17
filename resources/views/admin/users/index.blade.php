@extends('admin.layouts.app')
@section('title', 'Users')
@section('user', 'active')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Users /</span> Users Table</h4>
    <a href="{{ route('admin.user.create') }}" class="btn btn-primary">Add User</a>
    @include('admin.message')
    <div class="card">
        <h5 class="card-header">Table Basic</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>SNo.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if ($users->isNotEmpty())
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($users as $user)
                    <tr>
                        <td>{{$i++}}</td>
                        <td><strong>{{$user->name}}</strong></td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->mobile}}</td>
                        <td>
                            @if ($user->status == 1)
                                <span class="badge bg-label-primary me-1">Active</span>
                            @else
                                <span class="badge bg-label-danger me-1">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.user.edit', $user->guid) }}"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="deleteUser('{{ $user->guid }}')"><i class="bx bx-trash me-1"></i> Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="5">Record Not Found</td>
                    </tr>
                    @endif

                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    function deleteUser(id) {
        var url = "{{ route('admin.user.delete', 'ID') }}";
        var newUrl = url.replace('ID', id);

        if (confirm('Are you sure want to delete')) {
            $.ajax({
                url: newUrl,
                type: 'get',
                data: {},
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response['status']) {
                        window.location.href = "{{ route('admin.user.index') }}";
                    }
                }
            });
        }
    }
</script>
@endsection
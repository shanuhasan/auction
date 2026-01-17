@extends('admin.layouts.app')
@section('title', 'Auction')
@section('auction', 'active')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Home /</span> Auction</h4>
        <a href="{{ route('admin.auction.create') }}" class="btn btn-primary">Add Auction</a>
    </div>    
    @include('admin.message')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <form action="" method="get">
                    <h5 class="card-header">Filters</h5>
                    <div class="row card-body">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Auction Name" value="{{ Request::get('name') }}" />
                        </div>
                    
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option>Select</option>
                                <option {{ Request::get('status') == 'upcoming' ? 'selected' : '' }} value="upcoming" >Upcoming</option>
                                <option {{ Request::get('status') == 'ongoing' ? 'selected' : '' }} value="ongoing">Ongoing</option>
                                <option {{ Request::get('status') == 'completed' ? 'selected' : '' }} value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-success">Search</button>
                            <a href="{{ route('admin.auction.index') }}" class="btn btn-outline-dark ml-3">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>SNo.</th>
                        <th>Name</th>
                        <th>Season</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if ($auctions->isNotEmpty())
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($auctions as $auction)
                    <tr>
                        <td>{{$i++}}</td>
                        <td><strong>{{$auction->name}}</strong></td>
                        <td>{{$auction->season}}</td>
                        <td>
                            @if ($auction->status == 'upcoming')
                            <span class="badge bg-label-primary me-1">Upcoming</span>
                            @elseif($auction->status == 'ongoing')
                            <span class="badge bg-label-success me-1">Ongoing</span>
                            @else
                            <span class="badge bg-label-danger me-1">Completed</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.auction.edit', $auction->guid) }}"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="deleteAuction('{{ $auction->guid }}')"><i class="bx bx-trash me-1"></i> Delete</a>
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
        <div class="card-footer clearfix">
            {{ $auctions->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    function deleteAuction(id) {
        var url = "{{ route('admin.auction.delete', 'ID') }}";
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
                        window.location.href = "{{ route('admin.auction.index') }}";
                    }
                }
            });
        }
    }
</script>
@endsection
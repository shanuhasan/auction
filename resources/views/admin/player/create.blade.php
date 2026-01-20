@extends('admin.layouts.app')
@section('title', 'New Player')
@section('player', 'active')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Player /</span> Create</h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Player Info</h5>
                    <small class="text-muted float-end"></small>
                </div>
                <div class="card-body">
                    <form action="" id="playerForm" method="post">
                         @csrf
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="name">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control text-to-upper" id="name" placeholder="Enter Name" name="name" />
                                <p class="error"></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="base_price">Base Price</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control only-number" id="base_price" placeholder="Enter Base Price" name="base_price" />
                                <p class="error"></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="jersey_name">Jersey Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control text-to-upper" id="jersey_name" placeholder="Enter Jersey Name" name="jersey_name" />
                                <p class="error"></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="jersey_number">Jersey Number</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control only-number" id="jersey_number" placeholder="Enter Jersey Number" name="jersey_number" />
                                <p class="error"></p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="role" class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-10">
                                <select class="form-select" id="role" name="role">
                                    <option selected>Select Role</option>
                                    @foreach(\App\Models\Player::playerRole() as $key=>$item)
                                        <option value="{{ $key }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                                <p class="error"></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="status" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                                <select class="form-select" id="status" name="status">
                                    <option selected>Select Auction</option>
                                    @foreach(\App\Models\Player::playerStatus() as $key=>$item)
                                        <option value="{{ $key }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                                <p class="error"></p>
                            </div>
                        </div>
                       
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="image">Photo</label>
                            <input type="hidden" name="image_id" id="image_id" value="">
                            <div class="col-sm-10">
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">
                                        <br>Drop files here or click to upload.<br><br>
                                    </div>
                                </div>
                                <p class="error"></p>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Create</button>
                                <a href="{{ route('admin.player.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $('#playerForm').submit(function(e) {
        e.preventDefault();
        var elements = $(this);
        $('button[type=submit]').prop('disabled', true);
        $.ajax({
            url: "{{ route('admin.player.store') }}",
            type: 'post',
            data: elements.serializeArray(),
            dataType: 'json',
            success: function(response) {
                $('button[type=submit]').prop('disabled', false);
                if (response['status'] == true) {
                    window.location.href = "{{ route('admin.player.index') }}";
                    $('.error').removeClass('invalid-feedback').html('');
                    $('input[type="text"],input[type="number"],select').removeClass('is-invalid');
                } else {
                    var errors = response['errors'];

                    $('.error').removeClass('invalid-feedback').html('');
                    $('input[type="text"],input[type="number"],select').removeClass('is-invalid');
                    $.each(errors, function(key, val) {
                        $('#' + key).addClass('is-invalid').siblings('p').addClass(
                            'invalid-feedback').html(val);
                    });
                }
            },
            error: function(jqXHR) {
                console.log('Something went wrong.');
            }
        });
    });

    Dropzone.autoDiscover = false;
    const dropzone = $("#image").dropzone({
        init: function() {
            this.on('addedfile', function(file) {
                if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                }
            });
        },
        url: "{{ route('admin.media.create') }}",
        maxFiles: 1,
        paramName: 'image',
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg,image/png,image/gif",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(file, response) {
            $("#image_id").val(response.image_id);
            //console.log(response)
        }
    });
</script>
@endsection
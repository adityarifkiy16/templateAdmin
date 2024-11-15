@extends('layouts.app')

@section('title', 'Role Page')

@section('header-title', 'Roles')

@section('breadcrumb-title', 'Role')

@section('breadcrumb-sub', 'Create Role')

@section('breadcrumb-url', route('roles.index'))

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-maroon">
            <!-- .card-header -->
            <div class="card-header">
                <h2 class="card-title">Create Role</h2>
            </div>
            <!-- /.card-header -->

            <div class="card-body">
                <form action="{{route('roles.store')}}" method="POST" id="form-tambah">
                    @csrf
                    <!-- input states -->
                    <div class="form-group">
                        <label class="col-form-label" for="name"><i class="fas fa-users"></i> Role</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter role name..." name="name">
                        @error('name')
                        <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <!-- checkbox -->
                            <div class="form-group">
                                <label class="col-form-label" for="permissions[]"><i class="fas fa-exclamation-triangle" id="permissions-label"></i> Permission</label>

                                @if($permissions && count($permissions) > 0)
                                @php
                                $userPermissions = $permissions->filter(function($permission) {
                                return in_array('user', explode(' ', $permission->name));
                                });
                                @endphp

                                @if($userPermissions->count() > 0)
                                <p class="mb-0 font-weight-bold">User:</p>
                                @foreach($userPermissions as $permission)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="{{ $permission->id }}" value="{{ $permission->name }}">
                                    <label class="form-check-label">{{ $permission->name }}</label>
                                </div>
                                @endforeach
                                @endif

                                <!-- Display Role Permissions -->
                                @php
                                $rolePermissions = $permissions->filter(function($permission) {
                                return in_array('role', explode(' ', $permission->name));
                                });
                                @endphp

                                @if($rolePermissions->count() > 0)
                                <p class="mb-0 font-weight-bold mt-2">Role:</p>
                                @foreach($rolePermissions as $permission)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" id="{{ $permission->id }}" value="{{ $permission->name }}">
                                    <label class="form-check-label">{{ $permission->name }}</label>
                                </div>
                                @endforeach
                                @endif
                                @else
                                <p>No data found</p>
                                @endif
                            </div>


                        </div>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i> Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        $('#form-tambah').submit(function(e) {
            e.preventDefault();
            let form = $(this);
            let formData = form.serialize();
            let token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                data: formData,
                success: function(response) {
                    if (response.code === 200) {
                        Toast.fire({
                            icon: "success",
                            title: response.data.message
                        }).then(function() {
                            window.location = '{{route("roles.index")}}';
                        });
                    } else if (response.code === 403) {
                        Toast.fire({
                            icon: "error",
                            title: "Please check the form and correct the errors."
                        });

                        $('.error.invalid-feedback').remove();
                        $('.is-invalid').removeClass('is-invalid');

                        $.each(response.data.message, function(key, messages) {
                            if (key === 'permissions') {
                                input = $('#permissions-label');
                            } else {
                                input = $(`[name="${key}"]`);
                            }
                            if (input.length) {
                                input.addClass('is-invalid');
                                input.after(`<span class="error invalid-feedback">${messages[0]}</span>`);
                            }
                        });

                    }
                },
                error: function(response) {
                    Toast.fire({
                        icon: "error",
                        title: "Server Error"
                    }).then(function() {
                        window.location.reload;
                    });
                }
            });
        });
    })
</script>
@endpush
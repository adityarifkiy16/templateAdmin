@extends('layouts.app')

@section('title', 'Role Page')

@section('header-title')
<ion-icon name="lock-open-outline" class="mx-1"></ion-icon> Role & Permissions
@endsection

@section('breadcrumb-title', 'Role')

@section('breadcrumb-sub', 'Detail Role')

@section('breadcrumb-url', route('roles.index'))

@section('content')
<div class="card card-info">
    <div class="card-body">
        <h3 class="text-muted">{{$role->name}}</h3>
        <div class="form-group">
            <label class="col-form-label" for="permissions[]"><i class="fas fa-exclamation-triangle"></i> Permission : </label>
            <!-- Display User Permissions -->
            @if($permissions && count($permissions) > 0)
            @php
            $userPermissions = $permissions->filter(function($permission) {
            return in_array('user', explode(' ', $permission->name));
            });
            @endphp

            @if($userPermissions->count() > 0)
            <p class="mb-0 font-weight-bold">User</p>
            @foreach($userPermissions as $permission)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissions[]" id="{{ $permission->id }}" value="{{ $permission->name }}" {{ in_array($permission->id, $rolePermissions) ? 'checked':'' }} disabled>
                <label class="form-check-label">{{ $permission->name }}</label>
            </div>
            @endforeach
            @endif

            <!-- Display Role Permissions -->
            @php
            $rolesPermissions = $permissions->filter(function($permission) {
            return in_array('role', explode(' ', $permission->name));
            });
            @endphp

            @if($rolesPermissions->count() > 0)
            <p class="mb-0 font-weight-bold mt-2">Role</p>
            @foreach($rolesPermissions as $permission)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissions[]" id="{{ $permission->id }}" value="{{ $permission->name }}" {{ in_array($permission->id, $rolePermissions) ? 'checked':'' }} disabled>
                <label class="form-check-label">{{ $permission->name }}</label>
            </div>
            @endforeach
            @endif
            @else
            <p>No data found</p>
            @endif
        </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@endpush
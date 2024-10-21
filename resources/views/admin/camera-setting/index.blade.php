@extends('admin.template.layout.app')
@section('content')
    <div class="content">
        <form action="{{ route('admin.store_ptz') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="zoom_level">Zoom Level (1x)</label>
                <input type="number" name="zoom_level" id="zoom_level" class="form-control" value="1" readonly>
            </div>

            <div class="form-group">
                <label for="min_pan_limit">Min Pan Limit (degrees)</label>
                <input type="number" name="min_pan_limit" id="min_pan_limit" class="form-control" placeholder="Enter min pan limit">
            </div>

            <div class="form-group">
                <label for="max_pan_limit">Max Pan Limit (degrees)</label>
                <input type="number" name="max_pan_limit" id="max_pan_limit" class="form-control" placeholder="Enter max pan limit">
            </div>

            <div class="form-group">
                <label for="min_tilt_limit">Min Tilt Limit (degrees)</label>
                <input type="number" name="min_tilt_limit" id="min_tilt_limit" class="form-control" placeholder="Enter min tilt limit">
            </div>

            <div class="form-group">
                <label for="max_tilt_limit">Max Tilt Limit (degrees)</label>
                <input type="number" name="max_tilt_limit" id="max_tilt_limit" class="form-control" placeholder="Enter max tilt limit">
            </div>

            <button type="submit" class="btn btn-primary">Set Limitations</button>
        </form>
    </div>
@endsection

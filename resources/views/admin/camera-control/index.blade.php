@extends('admin.template.layout.app')
@include('admin.camera-control.css')
@section('content')
    <div class="content">
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="customSwitch1">
            <label class="custom-control-label" for="customSwitch1">Camera On/Off</label>
        </div>
        <div class="slider-container" style="display: none" id="slider-container">
            <label for="zoom-slider">Zoom:</label>
            <input type="range" id="zoom-slider" min="1" max="3" step="0.1" value="1">
        </div>
        <div class="mirror-card mb-2">
            <button id="mirror-btn" style="display: none">Mirror</button>
        </div>
        <div class="photo-card">
            <button id="take-photo-btn" style="display: none">Take Photo</button>
            <canvas id="canvas" style="display: none;"></canvas>
        </div>

        <div class="video-card">
            <i id="camera-icon" class="fas fa-camera camera-icon"></i>
            <video id="video" autoplay style="display: none"></video>
        </div>
    </div>
@endsection
@include('admin.camera-control.js')


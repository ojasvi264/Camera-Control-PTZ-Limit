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
        <div class="photo-card mb-2">
            <button id="take-photo-btn" style="display: none">Take Photo</button>
            <canvas id="canvas" style="display: none;"></canvas>
        </div>

        <button id="toggleButton" style="display: none">Apply Filters</button>

        <div class="apply-filter-slider" id="filterSliderContainer">
            <label for="saturation">Saturation</label>
            <input type="range" id="saturation" min="0" max="200" value="100"><br>

            <label for="brightness">Brightness</label>
            <input type="range" id="brightness" min="0" max="200" value="100"><br>

            <label for="sharpness">Sharpness</label>
            <input type="range" id="sharpness" min="0" max="200" value="100"><br>

            <label for="noiseReduction">Noise Reduction</label>
            <input type="range" id="noiseReduction" min="0" max="100" value="0"><br>

            <button id="resetFilter">Reset Filters</button>
        </div>

        <div class="video-card">
            <i id="camera-icon" class="fas fa-camera camera-icon"></i>
            <video id="video" autoplay style="display: none"></video>
        </div>
    </div>
@endsection
@include('admin.camera-control.js')


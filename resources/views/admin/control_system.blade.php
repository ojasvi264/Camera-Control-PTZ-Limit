@extends('admin.template.layout.app')
@section('styles')
    <style>
        .video-card {
            position: relative;
            width: 640px;
            height: 480px;
            border: solid 2px black;
            overflow: hidden;
            margin: 100px auto;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
        }
        #video {
            width: 100%;
            height: 100%;
            transform-origin: center center;
            transition: transform 0.3s ease;
        }
        .camera-icon {
            font-size: 4rem;
            color: gray;
            display: block;
        }
        .slider-container {
            margin: 20px 0;
        }
    </style>
@endsection
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
        <div class="mirror-card">
            <button id="mirror-btn" style="display: none">Mirror</button>
        </div>

        <div class="video-card">
            <i id="camera-icon" class="fas fa-camera camera-icon"></i>
            <video id="video" autoplay style="display: none"></video>
        </div>
    </div>
    <script>
        let stream = null;
        let isMirrored = false;
        const video = document.getElementById('video');
        let zoomSlider = document.getElementById('zoom-slider');
        let mirrorButton = document.getElementById('mirror-btn');
        const cameraIcon = document.getElementById('camera-icon');
        const cameraSwitch = document.getElementById('customSwitch1')
        let sliderContainer = document.getElementById('slider-container');

        // let zoomLevel = 1;


        async function turnOnCamera(){
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video:true })
                video.srcObject = stream;
                video.style.display = "block";
            } catch (error){
                console.error('Error accessing the camera:', error);
                alert("Couldn't access the camera.");
            }
        }

        function turnOffCamera() {
            if(stream){
                const tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
                video.style.display = "none";
                stream = null;
            }
        }

        cameraSwitch.addEventListener('change', () => {
            if(cameraSwitch.checked) {
                mirrorButton.style.display = 'block';
                sliderContainer.style.display = 'block';
                cameraIcon.style.display = "none";
                turnOnCamera();
            } else{
                cameraIcon.style.display = "block";
                mirrorButton.style.display = 'none';
                sliderContainer.style.display = 'none';
                turnOffCamera();
            }
        });

        zoomSlider.addEventListener('input', function(){
            let zoomLevel = zoomSlider.value;
            updateTransform(zoomLevel);
        });

        mirrorButton.addEventListener('click', function (){
            isMirrored = !isMirrored;
            updateTransform(zoomSlider.value);
        })

        function updateTransform(zoomLevel){
            let mirrorScale = isMirrored ? '-1' : '1';
            video.style.transform = `scale(${mirrorScale}, 1) scale(${zoomLevel})`;
        }

    </script>
@endsection

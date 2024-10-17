@section('scripts')
    <script>
        let stream = null;
        let isMirrored = false;
        const video = document.getElementById('video');
        let zoomSlider = document.getElementById('zoom-slider');
        let mirrorButton = document.getElementById('mirror-btn');
        const cameraIcon = document.getElementById('camera-icon');
        const cameraSwitch = document.getElementById('customSwitch1')
        let sliderContainer = document.getElementById('slider-container');
        let canvas = document.getElementById('canvas');
        let takePhotoButton = document.getElementById('take-photo-btn');

        const saturation = document.getElementById('saturation');
        const brightness =document.getElementById('brightness');
        const sharpness = document.getElementById('sharpness');
        const noiseReduction = document.getElementById('noiseReduction');

        const toggleButton = document.getElementById('toggleButton');
        const resetFilter = document.getElementById('resetFilter');
        const filterSliderContainer = document.getElementById('filterSliderContainer');

        // let zoomLevel = 1;

        toggleButton.addEventListener('click', () => {
            if (filterSliderContainer.style.display === 'none' || filterSliderContainer.style.display === '') {
                filterSliderContainer.style.display = 'block';
                toggleButton.textContent = 'Hide Filters';
            } else {
                filterSliderContainer.style.display = 'none';
                toggleButton.textContent = 'Apply Filters';
            }
        });

        resetFilter.addEventListener('click', () => {
            saturation.value = 100;
            brightness.value = 100;
            sharpness.value = 100;
            noiseReduction.value = 0;

            applyFilters();  // Reapply filters to reflect the reset values in the video
        });

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
                takePhotoButton.style.display = 'block';
                sliderContainer.style.display = 'block';
                toggleButton.style.display = "block";
                cameraIcon.style.display = "none";
                turnOnCamera();
            } else{
                cameraIcon.style.display = "block";
                toggleButton.style.display = 'none';
                mirrorButton.style.display = 'none';
                takePhotoButton.style.display = 'none';
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

        takePhotoButton.addEventListener('click', function (){
           const context = canvas.getContext('2d');
           canvas.width = video.videoWidth;
           canvas.height = video.videoHeight;
           context.drawImage(video, 0, 0, canvas.width, canvas.height);
           const imageData = canvas.toDataURL('image/png');
           savePhoto(imageData);
        });

        function savePhoto(imageData){
            fetch('/save-photo', {
                method: 'POST',
                headers: {
                    'Content-Type' : 'application/json',
                    'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ image: imageData })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success){
                    alert(data.file);
                    alert("Photo Saved Successfully");
                } else{
                    alert('Error Saving Photo');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function applyFilters(){
            const saturationValue = saturation.value;
            const brightnessValue = brightness.value;
            const sharpnessValue = sharpness.value;
            const noiseReductionValue = noiseReduction.value;

            video.style.filter =`
                saturate(${saturationValue}%)
                brightness(${brightnessValue}%)
                contrast(${sharpnessValue}%)
                blur(${noiseReductionValue /10}px)
           `;
        }
        saturation.addEventListener('input', applyFilters);
        brightness.addEventListener('input', applyFilters);
        sharpness.addEventListener('input', applyFilters);
        noiseReduction.addEventListener('input', applyFilters);

        window.addEventListener('load', function() {
            video.style.filter = 'saturate(100%) brightness(100%) contrast(100%) blur(0px)';
        });
    </script>
@endsection

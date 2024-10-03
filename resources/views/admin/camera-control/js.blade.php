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

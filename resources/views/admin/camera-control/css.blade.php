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

        .apply-filter-slider {
            display: none; /* Hide the slider container by default */
            margin-bottom: 20px;
            transition: all 0.3s ease-in-out;
        }

    </style>
@endsection

@extends('admin.template.layout.app')
@section('content')
    <div class="content">
        <button id="getCameraInfoBtn" class="btn btn-success">Get Camera Info</button>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#getCameraInfoBtn').click(function() {
                $.ajax({
                    url: "{{ url('api/camera/info') }}",  // Call the route you defined
                    type: 'GET',
                    success: function(response) {
                        console.log('Success:', response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', xhr.responseJSON);
                    }
                });
            });
        });
    </script>
@endsection

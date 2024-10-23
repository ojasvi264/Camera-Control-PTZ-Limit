@extends('admin.template.layout.app')
@section('content')
    <div class="content">
        <table class="table">
            <tr>
                <th>S.N</th>
                <th>Zoom Level</th>
                <th>Min PAN</th>
                <th>Max PAN</th>
                <th>Min Tilt</th>
                <th>Max Tilt</th>
            </tr>
            @foreach($ptzSettings as $index => $ptzSetting)
                <tr>
                    <td>{{ $ptzSettings->firstItem() + $index }}</td>
                    <td>{{$ptzSetting->zoom_level}}</td>
                    <td>{{$ptzSetting->pan_limit_min}}</td>
                    <td>{{$ptzSetting->pan_limit_max}}</td>
                    <td>{{$ptzSetting->tilt_limit_min}}</td>
                    <td>{{$ptzSetting->tilt_limit_max}}</td>
                </tr>
            @endforeach
        </table>
        {{ $ptzSettings->links() }}
    </div>
@endsection
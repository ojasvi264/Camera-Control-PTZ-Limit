@extends('admin.template.layout.app')
@section('styles')
    <style>
        .container {
            padding-top: 50px;
        }
        .duplicate-row {
            margin-top: 10px;
            background: #e0e5e9;
            position: relative;
            padding: 30px;
        }
        #removeClone {
            position: absolute;
            top: 60px;
            right: 12px;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-6">
                <h4>Clone Record</h4>
            </div>
            <div class="col-6">
                <div class="float-right">
                    <input type="button" value="Add" class="btn btn-primary" id="addMore">
                </div>
            </div>
            <div class="col-12">

                <form action="" class="multipleRecord">
                    <div class="row duplicate-row">
                        <div class="col-5">
                            <label for="">Camera</label>
                            <select name="camera" id="" class="form-control">
                                <option value="">Select Camera</option>
                                <option value="1">Camera 1</option>
                                <option value="1">Camera 2</option>
                                <option value="1">Camera 3</option>
                            </select>
                        </div>
                        <div class="col-5">
                            <label for="">Preset</label>
                            <select name="camera" class="form-control" id="">
                                <option value="">Select Preset</option>
                                <option value="1">Preset 1</option>
                                <option value="1">Preset 2</option>
                                <option value="1">Preset 3</option>
                            </select>
                        </div>
                        <input type="button" value="Delete" class="btn btn-danger" id="removeClone">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#addMore').click(function (){
               $(".multipleRecord .duplicate-row:last-child").clone().appendTo(".multipleRecord");
            });

           $(document).on('click', '#removeClone', function (){
               if($('.multipleRecord .duplicate-row').length > 1){
                   $(this).parents('.duplicate-row').remove();
               }
           })
        });
    </script>
@endsection

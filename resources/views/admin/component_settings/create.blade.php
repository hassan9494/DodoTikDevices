@extends('layouts.admin')

@section('styles')
    <style>
        .picture-container {
            position: relative;
            cursor: pointer;
            text-align: center;
        }

        .picture {
            width: 300px;
            height: 400px;
            background-color: #999999;
            border: 4px solid #CCCCCC;
            color: #FFFFFF;
            /* border-radius: 50%; */
            margin: 5px auto;
            overflow: hidden;
            transition: all 0.2s;
            -webkit-transition: all 0.2s;
        }

        .picture:hover {
            border-color: #2ca8ff;
        }

        .picture input[type="file"] {
            cursor: pointer;
            display: block;
            height: 100%;
            left: 0;
            opacity: 0 !important;
            position: absolute;
            top: 0;
            width: 100%;
        }

        .picture-src {
            width: 100%;
            height: 100%;
        }

        input[type="radio"] {
            cursor: pointer;
        }

        input[type="radio"]:focus {
            color: #495057;
            background-color: #0477b1;
            border-color: transparent;
            outline: 0;
            box-shadow: none;
        }
    </style>
@endsection
@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.component_settings.store') }}" method="POST">
        @csrf
        <div class="form-groups">
            <div class="form-group ml-5">
                <label for="name" class="col-sm-2 col-form-label">{{__('message.Name')}} </label>
                <div class="col-sm-9" id="key">
                    <input type="text" name="name" placeholder="name" id="name"
                           class="form-control {{$errors->first('name') ? "is-invalid" : "" }} "
                           value="{{old('name')}}">
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                </div>
            </div>
            <div class="form-group ml-5">
                <div class="col-sm-9">
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title align-items-start flex-column" style="align-self: center;">
                                <span
                                    class="card-label font-weight-bolder text-dark" style="font-size: 1rem;">{{__('message.Settings')}} :  </span>

                            </h3>
                            <div class="card-toolbar">
                                <ul class="nav nav-pills nav-pills-sm nav-dark-75 nav nav-test" role="tablist">
                                    <li class="nav-item nav-item">
                                        <span onclick="addKeyValue()" style="cursor: pointer"
                                              class="nav-link py-2 px-4   nav-link active"
                                              id="custom">{{__('message.Add')}}</span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="key-value">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="key" class="col-sm-4 col-form-label">{{__('message.Key')}} </label>
                                        <div class="col-sm-9" id="keyDiv">
                                            <input type="text" name="key[]" placeholder="key" id="key"
                                                   class="form-control {{$errors->first('key') ? "is-invalid" : "" }} "
                                            >
                                            <div class="invalid-feedback">
                                                {{ $errors->first('key') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="value"
                                               class="col-sm-4 col-form-label">{{__('message.Value')}} </label>
                                        <div class="col-sm-9" id="valueDiv">
                                            <input type="text" name="value[]" placeholder="value" id="value"
                                                   class="form-control {{$errors->first('value') ? "is-invalid" : "" }} "
                                                   value="{{old('value[]')}}">
                                            <div class="invalid-feedback">
                                                {{ $errors->first('value') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="form-group col-md-12">
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-info">{{__('message.Create')}}</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')

    <script>
        // Prepare the preview for profile picture
        $("#wizard-picture").change(function () {
            readURL(this);
        });

        //Function to show image before upload
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
        $(document).ready(function () {
            $('#parameters').select2({
                placeholder: "Choose Some parameters"
            });
        });
        $(function () {
            $('.selectpicker').selectpicker();
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#settings').select2({
                placeholder: "Choose Some settings"
            });
        });
        $(function () {
            $('.selectpicker').selectpicker();
        });
    </script>
    <script>
        function addKeyValue() {
            var addid = 1;
            var addList = document.getElementById('keyDiv');
            var docstyle = addList.style.display;
            if (docstyle == 'none') addList.style.display = '';

            addid++;

            var text = document.createElement('div');
            text.id = 'additem_' + addid;
            text.innerHTML = '<input type="text" placeholder="Key" class="form-control"  name="key[]" />';

            addList.appendChild(text);
            // document.getElementById("keyDiv").innerHTML =
            //   x +  '<input type="text" placeholder="Key" class="form-control"  name="key[]" />' ;

            var addid1 = 1;
            var addList1 = document.getElementById('valueDiv');
            var docstyle1 = addList1.style.display;
            if (docstyle1 == 'none') addList1.style.display = '';

            addid1++;

            var text1 = document.createElement('div');
            text1.id = 'additem_' + addid1;
            text1.innerHTML = '<input type="text" placeholder="Value" class="form-control"  name="value[]" />';
            addList1.appendChild(text1);

        }
    </script>
@endpush

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

    <form action="{{ route('admin.device_parameters.color_range',$parameter->id) }}" method="POST">
        @csrf
        <div class="form-groups">
            <div class="form-group ml-5">
                <div class="col-sm-12">
                    <div class="card card-custom">
                        <div class="card-header">
                            <h3 class="card-title align-items-start flex-column" style="align-self: center;">
                                <span
                                    class="card-label font-weight-bolder text-dark" style="font-size: 1rem;">{{__('message.Colors Range') .' ' .$parameter->name }}   </span>

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
                                        <div class="col-md-2">
                                            <label for="level_name"
                                                   class="col-sm-12 col-form-label">{{__('message.Level Name')}} </label>
                                            <div class="col-sm-12" id="level_nameDiv">
                                                <input type="text" name="level_name[]" placeholder="level name" id="level_name"
                                                       class="form-control {{$errors->first('level_name') ? "is-invalid" : "" }} "
                                                       value="{{old('level_name[]')}}">
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('level_name') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="from" class="col-sm-12 col-form-label">{{__('message.from')}} </label>
                                            <div class="col-sm-12" id="fromDiv">
                                                <input type="text" name="from[]" placeholder="from" id="from"
                                                       class="form-control {{$errors->first('from') ? "is-invalid" : "" }} "
                                                       value="{{old('from[]')}}">
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('from') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="to" class="col-sm-12 col-form-label">{{__('message.to')}} </label>
                                            <div class="col-sm-12" id="toDiv">
                                                <input type="text" name="to[]" placeholder="to" id="to"
                                                       class="form-control {{$errors->first('to') ? "is-invalid" : "" }} "
                                                       value="{{old('to[]')}}">
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('to') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="color" class="col-sm-12 col-form-label">{{__('message.color')}} </label>
                                            <div class="col-sm-12" id="colorDiv">
                                                <input type="color" name="color[]" placeholder="color" id="color"
                                                       class="form-control {{$errors->first('color') ? "is-invalid" : "" }} "
                                                       value="{{old('color[]')}}">
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('color') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="description"
                                                   class="col-sm-12 col-form-label">{{__('message.Description')}} </label>
                                            <div class="col-sm-12" id="descriptionDiv">
                                            <textarea  name="description[]" rows="1" placeholder="description" id="description"
                                                       class="form-control {{$errors->first('description') ? "is-invalid" : "" }} "
                                            >{{old('description[]')}}</textarea>
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('description') }}
                                                </div>
                                            </div>
                                        </div>

                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="form-group col-md-12">
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-info">{{__('message.Create')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </form>
@endsection

@push('scripts')




    <script>
        function addKeyValue() {
            //////////////start add level name input ////////////////
            var addid = 1;
            var addList = document.getElementById('level_nameDiv');
            var docstyle = addList.style.display;
            if (docstyle == 'none') addList.style.display = '';

            addid++;

            var text = document.createElement('div');
            text.id = 'additem_' + addid;
            text.innerHTML = '<hr><input type="text" placeholder="level name" class="form-control"  name="level_name[]" />';

            addList.appendChild(text);
            //////////////end add level name input ////////////////

            //////////////start add from input ////////////////
            var addid1 = 1;
            var addList1 = document.getElementById('fromDiv');
            var docstyle1 = addList1.style.display;
            if (docstyle1 == 'none') addList1.style.display = '';

            addid1++;

            var text1 = document.createElement('div');
            text1.id = 'additem_' + addid1;
            text1.innerHTML = '<hr><input type="text" placeholder="From" class="form-control"  name="from[]" />';
            addList1.appendChild(text1);
            //////////////end add level name input ////////////////


            //////////////start add to input ////////////////
            var addid2 = 1;
            var addList2 = document.getElementById('toDiv');
            var docstyle2 = addList2.style.display;
            if (docstyle2 == 'none') addList2.style.display = '';

            addid2++;

            var text2 = document.createElement('div');
            text2.id = 'additem_' + addid2;
            text2.innerHTML = '<hr><input type="text" placeholder="To" class="form-control"  name="to[]" />';
            addList2.appendChild(text2);
            //////////////end add to input ////////////////


            //////////////start add color input ////////////////
            var addid3 = 1;
            var addList3 = document.getElementById('colorDiv');
            var docstyle3 = addList3.style.display;
            if (docstyle3 == 'none') addList3.style.display = '';

            addid3++;

            var text3 = document.createElement('div');
            text3.id = 'additem_' + addid3;
            text3.innerHTML = '<hr><input type="color" placeholder="Color" class="form-control"  name="color[]" />';
            addList3.appendChild(text3);
            //////////////end add color input ////////////////


            //////////////start add desc input ////////////////
            var addid4 = 1;
            var addList4 = document.getElementById('descriptionDiv');
            var docstyle4 = addList4.style.display;
            if (docstyle4 == 'none') addList4.style.display = '';

            addid4++;

            var text4 = document.createElement('div');
            text4.id = 'additem_' + addid4;
            text4.innerHTML = '<hr><textarea  placeholder="Description" class="form-control" rows="1"  name="description[]"></textarea>';
            addList4.appendChild(text4);
            //////////////end add desc input ////////////////

        }
    </script>
@endpush

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

    <form action="{{ route('admin.factories.store') }}" method="POST">
        @csrf
        <div class="form-groups">
            <div class="form-group ml-5">
                <label for="name" class="col-sm-2 col-form-label">{{__('message.Name')}} </label>
                <div class="col-sm-9">
                    <input type="text" name="name" placeholder="name" id="name"
                           class="form-control {{$errors->first('name') ? "is-invalid" : "" }} "
                           value="{{old('name')}}">
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
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


@endpush

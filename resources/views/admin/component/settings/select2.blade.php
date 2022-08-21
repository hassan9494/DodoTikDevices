

<label for="{{$name}}" class="col-sm-2 col-form-label">{{$title}}</label>

<div class="col-sm-9">

    <select name='{{$name}}[]' class="form-control {{$errors->first($name) ? "is-invalid" : "" }} select2"
            id="{{isset($id) ? $id : $name}}" multiple>
        @if($choosen != null)
        @foreach ($choosen as $param)
            <option selected value="{{ (int)$param }}">{{ $options->where('id',(int)$param)->first()->name }}</option>
        @endforeach
        @endif
        @foreach ($options as $option)
            <option value="{{ $option->id }}">{{ $option->name }}</option>
        @endforeach
    </select>
    <div class="invalid-feedback">
        {{ $errors->first($name) }}
    </div>

</div>

{{--@include('admin.component.settings.select2', [--}}
{{--       'name' => "name",--}}
{{--       'title' => __('message.Name'),--}}
{{--       'options' => $types,--}}
{{--       ])--}}
@push('scripts')
@endpush

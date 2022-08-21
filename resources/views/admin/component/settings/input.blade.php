

<label for="{{$name}}" class="col-sm-2 col-form-label">{{$title}}</label>
<div class="col-sm-7">
    <input type="{{$type}}" name='{{$name}}' class="form-control {{$errors->first($name) ? "is-invalid" : "" }} "
           value="{{old($name)}}" id="{{$name}}" placeholder="{{$name}}">
    <div class="invalid-feedback">
        {{ $errors->first($name) }}
    </div>
</div>

{{--@include('admin.component.settings.input', [--}}
{{--       'name' => "name",--}}
{{--       'title' => __('message.Name'),--}}
{{--       'type' => 'text',--}}
{{--       ])--}}
@push('scripts')
@endpush

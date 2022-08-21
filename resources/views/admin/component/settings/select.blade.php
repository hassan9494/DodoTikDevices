

<label for="{{$name}}" class="col-sm-2 col-form-label">{{$title}}</label>
<div class="col-sm-9">
    <select name='{{$name}}' class="form-control {{$errors->first($name) ? "is-invalid" : "" }}" id="{{$name}}" >
        <option selected disabled>choose one</option>
        @foreach ($options as $option)
            <option value="{{ $option->id }}">{{ $option->name }}</option>
        @endforeach
    </select>
    <div class="invalid-feedback">
        {{ $errors->first($name) }}
    </div>
</div>

{{--@include('admin.component.settings.select', [--}}
{{--       'name' => "name",--}}
{{--       'title' => __('message.Name'),--}}
{{--       'options' => $types,--}}
{{--       ])--}}
@push('scripts')
@endpush

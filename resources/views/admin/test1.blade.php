@extends('layouts.admin')


@section('content')

    <!-- Page Heading -->

    <h1 class="h3 mb-2 text-gray-800">{{__('message.Test Data')}}</h1>



    <!-- DataTales Example -->

    <div class="card shadow mb-4">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>{{__('message.No.')}}</th>

                        <th>{{__('message.Data')}}</th>

                    </tr>

                    </thead>

                    <tbody>

                    @php

                        $no=0;

                    @endphp

                    @foreach ($allData as $data)
                            <tr>
                                <td>{{ ++$no }}</td>
                                <td>{{ $data->settings }}</td>

                            </tr>
                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection


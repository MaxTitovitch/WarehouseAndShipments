@extends('layouts.app')

@section('title')
    Inbound shipment
@endsection

@section('content')

    <button type="button" class="btn btn-dark btn-lg float-right my-3 mr-3 create-shipment">Add New</button>
    <form action="{{ route('parse') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type" value="shipments" class="display-none">
        <input id="import-input" type="file" name="file" class="display-none" accept=".csv, .xlsx, .xls">
        <input id="import-submit" type="submit" value="Submit" class="display-none">
        <button id="import-open" type="button" class="btn btn-dark btn-lg float-right my-3 mr-3">Import</button>
    </form>

    <table class="table mt-5 dashboard-table-div">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">User</th>
            <th scope="col">Shipped</th>
            <th scope="col">Received</th>
            <th scope="col">Shipping company</th>
            <th scope="col">Tracking number</th>
            <th scope="col">Comment</th>
            <th scope="col">Quantity</th>
            <th scope="col">Created</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($shipments as $shipment)
        <tr>
            <th scope="row">{{ $shipment->id }}</th>
            <td>{{ $shipment->user->id }}</td>
            <td>{{ $shipment->shipped }}</td>
            <td>{{ $shipment->received }}</td>
            <td>{{ $shipment->shipping_company }}</td>
            <td>{{ $shipment->tracking_number }}</td>
            <td>{{ $shipment->comment }}</td>
            <td>{{ $shipment->quantity }}</td>
            <td>{{ $shipment->created_at->format('Y-m-d') }}</td>
            <td>
                <a href="#" class="show-shipment">Show</a>
                <a href="#" class="edit-shipment">Edit</a>
            </td>
        </tr>
        @endforeach

        </tbody>
    </table>

    {{--    @section('content')--}}
    {{--        @dump($shipments)--}}
    {{--    @endsection--}}

@endsection

@extends('layouts.app')

@section('title')
    Inbound shipment
@endsection

@section('content')

    <button type="button" class="btn btn-dark btn-lg float-right my-3 mr-3">Add New</button>

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
            <th scope="col">Edit</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td><a href="">Edit</a></td>
        </tr>
        <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td><a href="">Edit</a></td>
        </tr>
        <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td><a href="">Edit</a></td>
        </tr>
        </tbody>
    </table>




    <div class="">
        <div class="custom-file float-right choose-file my-3 mr-3">
            <input type="file" class="custom-file-input " id="customFile">
            <label class="custom-file-label" for="customFile">Choose file</label>
        </div>
    </div>

{{--    @section('content')--}}
{{--        @dump($shipments)--}}
{{--    @endsection--}}

@endsection

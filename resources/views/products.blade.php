@extends('layouts.app')

@section('title')
    Products
@endsection

@section('content')
    @if(\Illuminate\Support\Facades\Auth::user()->role == 'Admin')
        <button type="button" class="btn btn-dark btn-lg float-right my-3 mr-3 create-product" data-toggle="modal"
                data-target="#modalAdd">Add New
        </button>

        <form action="{{ route('parse') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="products" class="display-none">
            <input id="import-input" type="file" name="file" class="display-none" accept=".csv, .xlsx, .xls">
            <input id="import-submit" type="submit" value="Submit" class="display-none">
            <button id="import-open" type="button" class="btn btn-dark btn-lg float-right my-3 mr-3">Import</button>
        </form>
    @endif

    <div class="table-container">
        <table class="table table-bordered table-striped table-hover" id="dtEntityTable">
            <thead class="thead-dark">
            <tr>
                <th scope="col" class="th-sm">ID</th>
                <th scope="col" class="th-sm">Created</th>
                <th scope="col" class="th-sm">UPC</th>
                <th scope="col" class="th-sm">SKU</th>
                <th scope="col" class="th-sm">Brand</th>
                <th scope="col" class="th-sm">Name</th>
                <th scope="col" class="th-sm">In Transit</th>
                <th scope="col" class="th-sm">Reserved</th>
                <th scope="col" class="th-sm">Available</th>
                <th scope="col" class="th-sm">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <th scope="row">{{ $product->id }}</th>
                    <td>{{ $product->created_at->format('Y-m-d') }}</td>
                    <td>{{ $product->upc }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->brand }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->in_transit ? "True" : "False" }}</td>
                    <td>{{ $product->received }}</td>
                    <td>{{ $product->available ? "True" : "False" }}</td>
                    <td>
                        <a href="#" class="show-product text-dark font-weight-bold show-entity-button"
                           data-value-id="{{ $product->id }}">Show</a>
                        @if(\Illuminate\Support\Facades\Auth::user()->role == 'Admin')
                            <a href="#" class="edit-product text-dark font-weight-bold edit-entity-button"
                               data-value-id="{{ $product->id }}">Edit</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('modal')
    <div class="modal fade" data-backdrop="static" id="modalAdd" tabindex="-1" role="dialog"
         aria-labelledby="modalAddLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddLabelProduct">Add new product</h5>
                    <button type="button" class="close close-modal-button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-submit">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" required maxlength="255" id="name"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Name">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="brand">Brand</label>
                            <input type="text" class="form-control" required maxlength="255" id="brand"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Brand">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="upc">UPC</label>
                            <input type="text" class="form-control" required maxlength="50" id="upc"
                                   aria-describedby="commentHelp" placeholder="UPC">
                            <small id="commentHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input type="text" class="form-control" required maxlength="50" id="sku"
                                   aria-describedby="commentHelp" placeholder="SKU">
                            <small id="commentHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="reserved">Date of reserving</label>
                            <input type="date" class="form-control" id="reserved" name="reserved" placeholder="date"
                                   aria-describedby="dateHelp">
                            <small id="dateHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="available">
                            <label for="available" class="custom-control-label">Available</label>
                            <small id="availabletHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="in_transit">
                            <label for="in_transit" class="custom-control-label">In transit</label>
                            <small id="inTransitHelp" class="form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary close-modal-button" data-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-dark save-changes">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="showModal" tabindex="-1" role="dialog"
         aria-labelledby="showModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showModalLabel">Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductId">ID</label>
                            <span class="form-control form-control-height " id="showProductId"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductCreated">Created</label>
                            <span class="form-control form-control-height " id="showProductCreated"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductUPC">UPC</label>
                            <span class="form-control form-control-height " id="showProductUPC"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductSKU">SKU</label>
                            <span class="form-control form-control-height " id="showProductSKU"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductBrand">Brand</label>
                            <span class="form-control form-control-height " id="showProductBrand"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductName">Name</label>
                            <span class="form-control form-control-height " id="showProductName"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductTransit">In transit</label>
                            <span class="form-control form-control-height " id="showProductTransit"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductReserved">Reserved</label>
                            <span class="form-control form-control-height " id="showProductReserved"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showProductAvailable">Available</label>
                            <span class="form-control form-control-height " id="showProductAvailable"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/product.js') }}" defer></script>
@endsection

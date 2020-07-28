@extends('layouts.app')

@section('title')
    Inbound shipment
@endsection

@section('content')
    <button type="button" class="btn btn-dark btn-lg float-right my-3 mr-3 create-shipment" data-toggle="modal"
            data-target="#modalAdd">Add New
    </button>

    <form action="{{ route('parse') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type" value="shipments" class="display-none">
        <input id="import-input" type="file" name="file" class="display-none" accept=".csv, .xlsx, .xls">
        <input id="import-submit" type="submit" value="Submit" class="display-none">
        <button id="import-open" type="button" class="btn btn-dark btn-lg float-right my-3 mr-3">Import</button>
    </form>

    <div class="table-container">
        <table class="table mt-5">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Created</th>
                <th scope="col">UPC</th>
                <th scope="col">SKU</th>
                <th scope="col">Brand</th>
                <th scope="col">Name</th>
                <th scope="col">In Transit</th>
                <th scope="col">Reserved</th>
                <th scope="col">Available</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <th scope="row">{{ $product->id }}</th>
                    <td>{{ $product->shipped }}</td>
                    <td>{{ $product->received }}</td>
                    <td>{{ $product->shipping_company }}</td>
                    <td>{{ $product->tracking_number }}</td>
                    <td>{{ $product->comment }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ $product->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="#" class="show-shipment text-dark font-weight-bold show-entity-button">Show</a>
                        <a href="#" class="edit-shipment text-dark font-weight-bold edit-entity-button" data-value-id="{{ $product->id }}">Edit</a>
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
                    <h5 class="modal-title" id="modalAddLabel">Add new Inbound Shipment</h5>
                    <button type="button" class="close close-modal-button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-submit">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="tracking_number-number">Tracking number</label>
                            <input type="text" class="form-control" required maxlength="255" id="tracking_number"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Tracking number">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="shipping_company">Shipping company</label>
                            <select class="form-control" id="shipping_company">
                                <option>USPS</option>
                                <option>FedEx</option>
                                <option>DHL</option>
                                <option>UPS</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <input type="text" class="form-control" required maxlength="255" id="comment"
                                   aria-describedby="commentHelp" placeholder="comment">
                            <small id="commentHelp" class="form-text text-danger"></small>
                        </div>
                        @if(\Illuminate\Support\Facades\Auth::user()->role == 'admin')
                            <div class="form-group">
                                <label for="received">Date of receiving</label>
                                <input type="date" class="form-control" id="received" name="date" placeholder="date"
                                       aria-describedby="dateHelp">
                                <small id="dateHelp" class="form-text text-danger"></small>
                            </div>
                            <div class="form-group">
                                <label for="shipped">Date of shipping</label>
                                <input type="date" class="form-control" id="shipped" name="date" placeholder="date"
                                       aria-describedby="dateHelp">
                                <small id="dateHelp" class="form-text text-danger"></small>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="productFormControlSelect1">Select product</label>
                            <div class="products-container">
                                <div class="product-container">
                                    <select class="form-control product-select"></select>
                                    <input type="number" class="form-control quantity" placeholder="quantity"
                                           required min="1" max="10000">
                                    <a href="#" class="remove-product-select">
                                        <i class="fa fa-times fa-2x text-dark" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="button-plus mt-2 mr-2">
                                <a href="#" class="add-product-select">
                                    <i class="fa fa-2x fa-plus text-dark" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary close-modal-button" data-dismiss="modal">Close
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
                    <h5 class="modal-title" id="showModalLabel">Inbound shipment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label class="font-weight-bold"  for="showId">ID</label>
                            <span class="form-control form-control-height " id="showId">  ssd sdf ssd xsdfs dfsd f sdf sdf sd f</span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showUser">User</label>
                            <span class="form-control form-control-height " id="showUser">  ssdxdfs dfsd f sdf sdf sd f</span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showShipped">Shipped</label>
                            <span class="form-control form-control-height " id="showShipped">  ssd fsd fsdf sdfs dfsd f sdf sdf sd f</span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showReceived">Received</label>
                            <span class="form-control form-control-height " id="showReceived">  ssf ssd fsd fsd fsdf sdfs dfsd f sdf sdf sd f</span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showCompany">Shipping company</label>
                            <span class="form-control form-control-height " id="showCompany">  ssd sdf ssd sdf sdfs dfsd f sdf sdf sd f</span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showTrackingNumber">Tracking number</label>
                            <span class="form-control form-control-height " id="showTrackingNumber">  ssdd fsd fsdf sdfs dfsd f sdf sdf sd f</span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showComment">Comment</label>
                            <span class="form-control form-control-height " id="showComment">  ssd sdf ssd sdf sdfs dfsd f sdf sdf sd f</span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showQuantity">Quantity</label>
                            <span class="form-control form-control-height " id="showQuantity">  ssd sdf ssd fsd fsd fsd fsdf sdfs dfsd f sdf sdf sd f</span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showCreated">Created</label>
                            <span class="form-control form-control-height " id="showCreated">  ssd sdf ssd fsd fsd fsdf sdd fsdf sdfs dfsd f sdf sdf sd f</span>
                        </div>

                        <div class="form-group">
                            <label for="productFormControlSelect1" class="font-weight-bold">Products</label>
                            <div class="products-container">
                                <div class="product-container">
                                    <span class="form-control form-control-height " id="showProduct"> Chear</span>
                                    <span class="form-control form-control-height quantity-width" id="showQuantity">15</span>
                                </div>
                            </div>
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
    <script src="{{ asset('js/shipment.js') }}" defer></script>
@endsection

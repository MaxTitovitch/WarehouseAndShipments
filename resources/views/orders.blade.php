@extends('layouts.app')

@section('title')
    Products
@endsection

@section('content')
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

    <div class="table-container">
        <table class="table table-bordered table-striped table-hover" id="dtEntityTable">
            <thead class="thead-dark">
            <tr>
                <th scope="col" class="th-sm">ID</th>
                <th scope="col" class="th-sm">Created</th>
                <th scope="col" class="th-sm">Customer</th>
                <th scope="col" class="th-sm">Comment</th>
                <th scope="col" class="th-sm">Order Status</th>
                <th scope="col" class="th-sm">Shipping cost</th>
                <th scope="col" class="th-sm">Tracking number</th>
                <th scope="col" class="th-sm">Shipped</th>
                <th scope="col" class="th-sm">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <th scope="row">{{ $order->id }}</th>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                    <td>{{ $order->customer }}</td>
                    <td>{{ $order->comment }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->shipping_cost }}</td>
                    <td>{{ $order->tracking_number }}</td>
                    <td>{{ $order->shipped }}</td>
                    <td>
                        <a href="#" class="show-product text-dark font-weight-bold show-entity-button">Show</a>
                        <a href="#" class="edit-product text-dark font-weight-bold edit-entity-button"
                           data-value-id="{{ $order->id }}">Edit</a>
                        <a href="#" class="show-product text-dark font-weight-bold show-entity-button">Delete</a>
                        <a href="#" class="show-product text-dark font-weight-bold show-entity-button">Copy</a>
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
                    <h5 class="modal-title" id="modalAddLabelProduct">Add new order</h5>
                    <button type="button" class="close close-modal-button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-submit">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="customer">Customer</label>
                            <input type="text" class="form-control" required maxlength="255" id="customer"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Customer">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="companyName">Company Name</label>
                            <input type="text" class="form-control" required maxlength="255" id="companyName"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Company Name">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" required maxlength="50" id="address"
                                   aria-describedby="commentHelp" placeholder="Address">
                            <small id="commentHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="tracking_number-number">Tracking number</label>
                            <input type="number" class="form-control" required maxlength="255" id="tracking_number"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Tracking number">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" class="form-control" required maxlength="50" id="city"
                                   aria-describedby="commentHelp" placeholder="City">
                            <small id="commentHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="shippingCost">Shipping cost</label>
                            <input type="number" class="form-control" required maxlength="50" id="shippingCost"
                                   aria-describedby="commentHelp" placeholder="Shipping cost">
                            <small id="commentHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="zipCode">Zip code / Postal Code</label>
                            <input type="text" class="form-control" id="zipCode" placeholder="Zip code / Postal Code"
                                   aria-describedby="dateHelp">
                            <small id="dateHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="state">State / Region</label>
                            <input type="text" class="form-control" id="state" placeholder="State / Region"
                                   aria-describedby="dateHelp">
                            <small id="dateHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="country">Country</label>
                            <select class="form-control" id="country"></select>
                        </div>
                        <div class="form-group">
                            <label for="shipped">Date of shipping</label>
                            <input type="date" class="form-control" id="shipped" name="date" placeholder="date"
                                   aria-describedby="dateHelp">
                            <small id="dateHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status">
                                <option>Created</option>
                                <option>Shipped</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" pattern="^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$" class="form-control" required maxlength="255" id="phone"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Phone">
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
                            <input type="text" class="form-control" id="comment" placeholder="Comment"
                                   aria-describedby="dateHelp">
                            <small id="dateHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>Packing selection</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="packing_selection" id="bubblesPack"
                                       value="Bubbles Pack" checked>
                                <label class="form-check-label" for="bubblesPack">
                                    Bubbles Pack
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="packing_selection" id="carton"
                                       value="Carton">
                                <label class="form-check-label" for="carton">
                                    Carton
                                </label>
                            </div>
                            <small id="dateHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>Products</label>
                            <div class="products-container">
                                <div class="product-container product-order-area">
                                    <select class="form-control product-select product-order-select"></select>
                                    <a href="#" class="remove-product-select product-order-remove">
                                        <i class="fa fa-times fa-2x text-dark" aria-hidden="true"></i>
                                    </a>
                                    <input type="number" class="form-control quantity product-order-quantity" placeholder="quantity"
                                           required min="1" max="10000">
                                    <input type="number" class="form-control price product-order-price" placeholder="price"
                                                                               min="1" max="10000">
                                    <textarea style="resize: none; height: 100px" class="form-control description product-order-description" placeholder="Description"
                                              required maxlength="10000"></textarea>
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
                    <h5 class="modal-title" id="showModalLabel">Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showOrderId">ID</label>
                            <span class="form-control form-control-height " id="showOrderId"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showOrderCustomer">Customer</label>
                            <span class="form-control form-control-height " id="showOrderCustomer"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showOrderCompanyName">Company Name</label>
                            <span class="form-control form-control-height " id="showOrderCompanyName"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showOrderStatus">Status</label>
                            <span class="form-control form-control-height " id="showOrderStatus"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showOrderTrackingNumber">Tracking number</label>
                            <span class="form-control form-control-height " id="showOrderTrackingNumber"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showOrderAddress">Address</label>
                            <span class="form-control form-control-height " id="showOrderAddress"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showOrderShippingCost">Shipping cost</label>
                            <span class="form-control form-control-height " id="showOrderShippingCost"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showOrderCity">City</label>
                            <span class="form-control form-control-height " id="showOrderCity"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showOrderShipped">Shipped</label>
                            <span class="form-control form-control-height " id="showOrderShipped"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showOrderPackingSelection">Packing Selection</label>
                            <span class="form-control form-control-height " id="showOrderPackingSelection"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showOrderZipCode">Zip code / Postal Code</label>
                            <span class="form-control form-control-height " id="showOrderZipCode"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showOrderState">State / Region</label>
                            <span class="form-control form-control-height " id="showOrderState"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showOrderCountry">Country</label>
                            <span class="form-control form-control-height " id="showOrderCountry"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showOrderCreated">Created</label>
                            <span class="form-control form-control-height " id="showOrderCreated"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showOrderPhone">Phone</label>
                            <span class="form-control form-control-height " id="showOrderPhone"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showOrderShippingCompany">Shipping company</label>
                            <span class="form-control form-control-height " id="showOrderShippingCompany"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showOrderComment">Comment</label>
                            <span class="form-control form-control-height " id="showOrderComment"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="showOrderUser">User</label>
                            <span class="form-control form-control-height " id="showOrderUser"></span>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Products</label>
                            <div class="show-products-container">
                                <div class="show-product-container product-order-area">
                                    <span class="form-control form-control-height show-product">Chear</span>
                                    <span class="form-control form-control-height w-50 show-quantity">5 <span class="font-weight-bold">pcs</span></span>
                                    <span class="form-control form-control-height w-50 show-price"><span class="font-weight-bold">$</span>150</span>
                                    <span class="form-control form-control-height show-description">edf wef wef wefw efw defytygytyttt</span>
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
    <script src="{{ asset('js/order.js') }}" defer></script>
@endsection

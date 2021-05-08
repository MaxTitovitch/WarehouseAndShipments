@extends('layouts.app')

@section('title')
    Входящие посылки
@endsection

@section('content')

    <div class="main-container">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @if(Auth::user()->role != 'Admin')
        <button type="button" class="btn btn-dark btn-lg float-right my-3 mr-3 create-product" data-toggle="modal"
                data-target="#modalAdd">Добавить
        </button>
        @endif

        <form action="{{ route('parse') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="products" class="display-none">
            <input id="import-input" type="file" name="file" class="display-none" accept=".csv, .xlsx, .xls">
            <input id="import-submit" type="submit" value="Submit" class="display-none">
    {{--        <button id="import-open" type="button" class="btn btn-dark btn-lg float-right my-3 mr-3">Import</button>--}}
        </form>

        <a href="{{route('exportOrders')}}" class="btn btn-dark btn-lg float-right my-3 mr-3">Экспорт</a>

        <div class="table-container">
            <table class="table table-bordered table-striped table-hover" id="dtEntityTable">
                <thead class="thead-dark">
                <tr>
                    <th scope="col" class="th-sm">ID</th>
                    @if (Auth::user()->role == 'Admin')
                        <th scope="col" class="th-sm">Пользователь</th>
                    @endif
                    <th scope="col" class="th-sm">Создано</th>
                    <th scope="col" class="th-sm">Доставлено</th>
                    <th scope="col" class="th-sm">Код Посылки</th>
                    <th scope="col" class="th-sm">Order Статус</th>
{{--                    <th scope="col" class="th-sm">Сумма налога</th>--}}
                    <th scope="col" class="th-sm">Цена Доставки</th>
                    <th scope="col" class="th-sm">Производитель</th>
                    <th scope="col" class="th-sm">Коммент</th>
                    <th scope="col" class="th-sm">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <th scope="row">{{ $order->id }}</th>
                        @if (Auth::user()->role == 'Admin')
                            <td>{{ $order->user->name }} ({{ $order->user->suite }})</td>
                        @endif
                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        <td>{{ $order->shipped }}</td>
                        <td>{{ $order->tracking_number }}</td>
                        <td>{{ $order->status }}</td>
{{--                        <td>{{ $order->fee_cost }}</td>--}}
                        <td>{{ $order->shipping_cost }}</td>
                        <td>{{ $order->customer }}</td>
                        <td>{{ $order->comment }}</td>
                        <td>
                            <a href="#" class="show-product text-dark font-weight-bold show-entity-button"
                               data-value-id="{{ $order->id }}">Показать</a>
                            @if(Auth::user()->role == 'Admin' || $order->shipped == null)
                                <a href="#" class="edit-product text-dark font-weight-bold edit-entity-button"
                                    data-value-id="{{ $order->id }}">Редактировать</a>
                            @endif
                            @if($order->status == 'Создано')
                            <a href="#" class="show-product text-dark font-weight-bold delete-entity-button"
                               data-value-id="{{ $order->id }}">Удалить</a>
                            @endif

                            @if(Auth::user()->role != 'Admin')
                            <a href="#" class="show-product text-dark font-weight-bold copy-entity-button"
                               data-value-id="{{ $order->id }}">Дублировать</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-12 text-center-mobile">
                <a href="{{ route('inbound-shipments') }}" class="badge badge-dark text-full-size"><i class="fa fa-list-alt" aria-hidden="true"></i> Исходящие посылки</a>
                <a href="{{ route('products') }}" class="badge badge-dark text-full-size"><i class="fa fa-cube" aria-hidden="true"></i> Перечень Вещей</a>
                <a href="{{ route('orders') }}" class="badge badge-dark text-full-size"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Входящие посылки</a>
                <a href="{{ route('balance') }}" class="badge badge-dark text-full-size"><i class="fa fa-dollar" aria-hidden="true"></i> Баланс Получателей</a>
                @if(\Illuminate\Support\Facades\Auth::user())
                    @if(\Illuminate\Support\Facades\Auth::user()->role == 'Admin')
                        <a href="{{ route('users') }}" class="badge badge-dark text-full-size"><i class="fa fa-users" aria-hidden="true"></i> Пользователи</a>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <div class="modal fade" data-backdrop="static" id="modalAdd" tabindex="-1" role="dialog"
         aria-labelledby="modalAddLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddLabel">Добавить Посылку</h5>
                    <button type="button" class="close close-modal-button" data-dismiss="modal" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-submit">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group {{ Auth::user()->role == 'Admin' ? 'display-none' : '' }}" >
                            <label for="customer">Производитель</label>
                            <input type="text" class="form-control" required maxlength="255" id="customer"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Производитель">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group {{ Auth::user()->role == 'Admin' ? 'display-none' : '' }}" >
                            <label for="company_name">Название компании</label>
                            <input type="text" class="form-control" maxlength="255" id="company_name"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Название компании">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group {{ Auth::user()->role == 'Admin' ? 'display-none' : '' }}" >
                            <label for="address">Адрес</label>
                            <input type="text" class="form-control" required maxlength="50" id="address"
                                   aria-describedby="commentHelp" placeholder="Address">
                            <small id="commentHelp" class="form-text text-danger"></small>
                        </div>
                        @if(\Illuminate\Support\Facades\Auth::user()->role == 'Admin')
                            <div class="form-group">
                                <label for="tracking_number">Код Посылки</label>
                                <input type="text" class="form-control" maxlength="255" id="tracking_number"
                                       aria-describedby="ariaDescribedbyHelp" placeholder="Код Посылки" required>
                                <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                            </div>
                        @endif
                        <div class="form-group {{ Auth::user()->role == 'Admin' ? 'display-none' : '' }}" >
                            <label for="city">Город</label>
                            <input type="text" class="form-control" required maxlength="50" id="city"
                                   aria-describedby="commentHelp" placeholder="Город">
                            <small id="commentHelp" class="form-text text-danger"></small>
                        </div>
                        @if(\Illuminate\Support\Facades\Auth::user()->role == 'Admin')
                            <div class="form-group">
                                <label for="shippingCost">Цена Доставки</label>
                                <input type="number" class="form-control" min="1" max="1000000" id="shipping_cost"
                                       aria-describedby="commentHelp" placeholder="Цена Доставки" required>
                                <small id="commentHelp" class="form-text text-danger"></small>
                            </div>
                        @endif
                        <div class="form-group {{ Auth::user()->role == 'Admin' ? 'display-none' : '' }}" >
                            <label for="zipCode">Почтовый Индекс</label>
                            <input type="text" class="form-control" id="zip_postal_code"
                                   placeholder="Почтовый Индекс"
                                   aria-describedby="dateHelp">
                            <small id="dateHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group {{ Auth::user()->role == 'Admin' ? 'display-none' : '' }}" >
                            <label for="state_region">Регион</label>
                            <input type="text" class="form-control" id="state_region" placeholder="Регион"
                                   aria-describedby="dateHelp">
                            <small id="dateHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group {{ Auth::user()->role == 'Admin' ? 'display-none' : '' }}" >
                            <label for="country">Страна</label>
                            <select class="form-control custom-select" id="country"></select>
                        </div>
                        <div class="form-group {{ Auth::user()->role == 'Admin' ? 'display-none' : '' }}" >
                            <label for="phone">Телефон</label>
                            <input type="text" pattern="^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$"
                                   class="form-control" maxlength="255" id="phone"
                                   aria-describedby="ariaDescribedbyHelp" placeholder="Телефон">
                            <small id="ariaDescribedbyHelp" class="form-text text-danger"></small>
                        </div>
                        @if(\Illuminate\Support\Facades\Auth::user()->role == 'Admin')
                            <div class="form-group">
                                <label for="shipped">Доставлено</label>
                                <input type="date" class="form-control" id="shipped" name="date" placeholder="date"
                                       aria-describedby="dateHelp" required>
                                <small id="dateHelp" class="form-text text-danger"></small>
                            </div>
{{--                            <div class="form-group">--}}
{{--                                <label for="status">Статус</label>--}}
{{--                                <select class="form-control" id="status">--}}
{{--                                    <option>Создано</option>--}}
{{--                                    <option>Доставлено</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
                        @endif
                        <div class="form-group {{ Auth::user()->role == 'Admin' ? 'display-none' : '' }}" >
                            <label for="shipping_company">Компания Доставки</label>
                            <select class="form-control" id="shipping_company">
                                <option>USPS</option>
                                <option>FedEx</option>
                                <option>DHL</option>
                                <option>UPS</option>
                                <option>APC</option>
                            </select>
                        </div>
                        <div class="form-group {{ Auth::user()->role == 'Admin' ? 'display-none' : '' }}" >
                            <label for="comment">Коммент</label>
                            <textarea rows="5" class="form-control" id="comment" placeholder="Коммент"
                                      aria-describedby="dateHelp" maxlength="255"></textarea>
                            <small id="dateHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group {{ Auth::user()->role == 'Admin' ? 'display-none' : '' }}" >
                            <label>Перечень Вещей</label>
                            <div class="products-container">
{{--                                <div class="product-container product-order-area">--}}
{{--                                    <select class="form-control product-select product-order-select"></select>--}}
{{--                                    <a href="#" class="remove-product-select product-order-remove">--}}
{{--                                        <i class="fa fa-times fa-2x text-dark" aria-hidden="true"></i>--}}
{{--                                    </a>--}}
{{--                                    <input type="number" class="form-control quantity product-order-quantity"--}}
{{--                                           placeholder="quantity"--}}
{{--                                           required min="1" max="10000">--}}
{{--                                    <input type="number" class="form-control price product-order-price"--}}
{{--                                           placeholder="price"--}}
{{--                                           min="1" max="10000">--}}
{{--                                    <textarea rows="1" style="resize: none;"--}}
{{--                                              class="form-control description product-order-description"--}}
{{--                                              placeholder="Описание"--}}
{{--                                              maxlength="10000"></textarea>--}}
{{--                                </div>--}}
                            </div>

                            <div class="button-plus mt-2 mr-2">
                                <a href="#" class="add-product-select">
                                    <i class="fa fa-2x fa-plus text-dark" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="form-group {{ Auth::user()->role == 'Admin' ? 'display-none' : '' }}" id="packing_selection" >
                            <label>Упаковка</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="packing_selection" id="bubblesPack"
                                       value="Bubbles Pack" checked>
                                <label class="form-check-label" for="bubblesPack">
                                    Пластик
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="packing_selection" id="carton"
                                       value="Carton">
                                <label class="form-check-label" for="carton">
                                    Картон
                                </label>
                            </div>
                            <small id="dateHelp" class="form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary close-modal-button" data-dismiss="modal">
                            Закрыть
                        </button>
                        <button type="submit" class="btn btn-dark save-changes">Создать</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="showModal" tabindex="-1" role="dialog"
         aria-labelledby="showModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showModalLabel">Входящие посылки</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <p class="col-12">
                            <strong>ИД Посылки: </strong><span id="showOrderId"></span>,
                            <strong>Статус: </strong><span id="showOrderStatus"></span>
                        </p>
                        <p class="col-12">
                            <strong>Пользователь: </strong> <br>
                            <span id="showOrderUser"></span>
                        </p>
                        <p class="col-6">
                            <strong>Доставлено к: </strong><br>
                            <strong id="showOrderCustomer"></strong>.
                            <strong id="showOrderCompanyName"></strong><br>
                            <span id="showOrderZipCode"></span><br>
                            <span id="showOrderCity"></span>,
                            <span id="showOrderState"></span>,
                            <span id="showOrderAddress"></span><br>
                            <span id="showOrderCountry"></span><br>
                            tel. <span id="showOrderPhone"></span>
                        </p>
                        <p class="col-6">
                            <strong>Перевозчик: </strong><br>
                            <span id="showOrderShippingCompany"></span><br>
                            <strong>Тип упаковки: </strong><br>
                            <span id="showOrderPackingSelection"></span><br>
                            <strong>Коммент: </strong><br>
                            <span id="showOrderComment"></span><br>
                        </p>
                        <p class="col-6">
                            <strong>Код посылки: </strong><br>
                            <span id="showOrderTrackingNumber"></span><br>
                            <strong>Стоимость доставки: </strong><br>
                            <span id="showOrderShippingCost"></span><br>
{{--                            <strong>Стоимость комиссии: </strong><br>--}}
{{--                            <span id="showOrderFeeCost"></span><br>--}}
                        </p>
                        <p class="col-6">
                            <strong>Создано: </strong><br>
                            <span id="showOrderCreated"></span><br>
                            <strong>Доставлено: </strong><br>
                            <span id="showOrderShipped"></span><br>
                        </p>
                        <div class="col-12">
                            <label class="font-weight-bold">Вещи:</label>
                            <table class="show-products-container w-100" cellspacing="0" border="1" >
                                <thead>
                                    <tr>
                                        <td><strong>UPC</strong></td>
                                        <td><strong>SKU</strong></td>
                                        <td><strong>Бренд</strong></td>
                                        <td><strong>Название</strong></td>
                                        <td><strong>Количество</strong></td>
                                        <td><strong>Цена</strong></td>
                                        <td><strong>Описание</strong></td>
                                    </tr>
                                </thead>
                                <tr class="show-product-container product-order-area">
                                    <td class="show-upc"></td>
                                    <td class="show-sku"></td>
                                    <td class="show-brand"></td>
                                    <td class="show-product"></td>
                                    <td>
                                        <span class="show-quantity"></span><span class="font-weight-bold"> шт.</span>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold">BYN </span><span class="show-price"></span>
                                    </td>
                                    <td class="show-description"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script src="{{ asset('js/order.js') }}" defer></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
@endsection

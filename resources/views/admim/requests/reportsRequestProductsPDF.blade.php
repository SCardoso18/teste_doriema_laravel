<!DOCTYPE html>
<html lang="en">
<head>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
    </head>

</head>

<style>
    .invoice {
        position: relative;
        background: #fff;
        border: 1px solid #f4f4f4;
        padding: 20px;
        margin: 10px 25px;
    }

    .row {
        margin-right: -15px;
        margin-left: -15px;
    }

    .col-xs-12 {
        width: 100%;
        float: left;
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }

    .page-header {
        margin: 10px 0 20px 0;
        font-size: 22px;
        padding-bottom: 9px;
        border-bottom: 1px solid #eee;
        font-family: 'Source Sans Pro',sans-serif;
        font-weight: 500;
        line-height: 1.1;
        color: inherit;
        display: block;
        margin-block-start: 0.83em;
        margin-block-end: 0.83em;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
    }

    .page-header>small {
        color: #666;
        display: block;
        margin-top: 5px;
        font-size: 65%;
        font-weight: 400;
        line-height: 1;
    }

    .pull-right {
        float: right!important;
    }

    .row {
        margin-right: -15px;
        margin-left: -15px;
    }

    .row::before
    {
        display: table;
        content: " ";
    }

    .row:after {
        clear: both;
        display: table;
        content: " ";
    }

    body {
        font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
        font-weight: 400;
        font-size: 14px;
        line-height: 1.42857143;
    }

    .table-responsive {
        min-height: .01%;
        overflow-x: auto;
    }

    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
        background-color: transparent;
        border-collapse: collapse;
        border-spacing: 0;
        display: table;
        border-color: grey;
    }

    .table>thead:first-child>tr:first-child>th {
        border-top: 0;
    }

    .table>thead>tr>th {
        border-bottom: 2px solid #f4f4f4;
    }

    .table>thead>tr>th {
        vertical-align: bottom;
    }

    .table>thead>tr>th {
        padding: 8px;
        line-height: 1.42857143;
    }

    th {
        text-align: left;
    }

    th {
        display: table-cell;
    }

    table {
        border-collapse: collapse;
        border-spacing: 0;
    }

    tbody {
        display: table-row-group;
        vertical-align: middle;
        border-color: inherit;
    }

    .table-striped>tbody>tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    tr {
        display: table-row;
        vertical-align: inherit;
        border-color: inherit;
    }

    .table>tbody>tr>td
    {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 2px solid #ddd;
    }

    .page-header
    {
        font-weight: bold;
    }

    .col-sm-4 {
    position: relative;
    min-height: 1px;
    width: 30%;
    float: left;
    padding-left: 13px;

    .lead
    {
        margin-bottom: 20px;
        font-size: 16px;
        font-weight: 300;
        line-height: 1.4;
    }

    .table-responsive {
    min-height: .01%;
    overflow-x: auto;
    }

}

</style>

<body>

    <!-- Main content -->
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> Doriema Lda.
                    <small class="pull-right">{{date('d/M/Y H:i')}}</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>

        <!-- info row -->
        <div class="row invoice-info">

            <div class="col-sm-4 invoice-col">
                <address>
                    <strong style="font-weight: bold">Doriema Lda.</strong><br>
                    795 Folsom Ave, Suite 600<br>
                    San Francisco, CA 94107<br>
                    Phone: (804) 123-5432<br>
                    Email: info@almasaeedstudio.com
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                Cliente:
                <address>
                    <strong>{{$client_name}}</strong><br>
                    {{$province}}<br>
                    {{$district}}<br>
                    {{$neighborhood}}<br>
                    {{$street}}<br>
                    @if ($house_number != null)
                        {{$house_number}}<br>
                    @endif
                    {{$client_telephone}}<br>
                    {{$client_email}}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b>Pedido Refer.: {{$request_id}} </b><br> <br>

                @if ($request_status == 'EC')
                    <b>Status: Encomendado </b><br> <br>
                @endif

                @if ($request_status == 'PA')
                    <b>Status: Pago </b><br> <br>
                @endif

                @if ($request_status == 'EN')
                    <b>Status: Entregue </b><br><br>
                @endif

                @if ($request_status == 'CA')
                    <b>Status: Cancelado </b><br> <br>
                @endif
            </div>
            <!-- /.col -->
        </div> <br> <br>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Qtd</th>
                            <th>Preço</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Criado em</th>
                            <th>Actualizado em</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requestProducts as $requestProduct)
                        <tr>
                            <td> {{$requestProduct->name}} </td>
                            <td> {{$requestProduct->qtd}} </td>
                            <td> {{number_format($requestProduct->value, 2, ',', '.')}} </td>
                            @php $total = $requestProduct->value * $requestProduct->qtd @endphp
                            <td> {{number_format($total, 2, ',', '.')}} </td>
                            <td> {{$requestProduct->status}} </td>
                            <td> {{$requestProduct->created_at}} {{$requestProduct->time}} </td>
                            <td> {{$requestProduct->updated_at}} </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-xs-12">
                {{-- <p class="lead">Pagamento</p> --}}

                <strong> <p class="lead">Total: {{number_format($total_request, 2, ',', '.')}}</p> </strong>

                <strong> <p class="lead">Forma de pagamento: {{$payment_method}}</p> </strong>

                {{-- <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Total:</th>
                            <td>{{number_format($total_request, 2, ',', '.')}}</td>
                        </tr>
                        <tr>
                            <th>Forma de pagamento</th>
                            <td>{{$payment_method}}</td>
                        </tr>
                    </table>
                </div> --}}
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->

</body>
</html>

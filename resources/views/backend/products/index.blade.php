@extends('backend.layouts.parent')

@section('title', 'All Produts')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div class="row">
        @include('backend.includes.message')
        <div class="col-12">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name En</th>
                        <th>Code</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{$product->id}}</td>
                            <td>{{$product->name_en}}</td>
                            <td>{{$product->code}}</td>
                            <td>{{$product->price}}</td>
                            <td>{{$product->quantity}}</td>
                            <td class="{{$product->status == 0 ? 'text-danger' : 'text-success'}}">{{$product->status == 0 ? 'Not Active' : 'Active'}}</td>
                            <td>{{$product->created_at}}</td>
                            <td>
                                <a href="{{route('products.edit',$product->id)}}" class="btn btn-warning">Edit</a>
                                <form action="{{route('products.destroy',$product->id)}}" method="post" class="d-inline">
                                    @method('DELETE')
                                    @csrf
                                    <button class=" btn btn-danger"> Delete </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>   
            </table>
        </div>
    </div>
@endsection

@section('js')
    <!-- DataTables  & Plugins -->
    <script src="{{ url('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ url('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ url('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ url('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ url('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection

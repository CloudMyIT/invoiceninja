@extends('layouts.master', ['header' => $header])

@section('head')

@endsection

@section('body')
    @parent
    <main class="main" >

        <div class="container-fluid" id="client_list">
            <vue-toastr ref="toastr"></vue-toastr>
            
            <list-actions :listaction="{{ $listaction }}" :per_page_prop="{{ $datatable['per_page'] }}"></list-actions>

            <div style="background: #fff;">
                
                <client-list :datatable="{{ $datatable }}"></client-list>
                
            </div>

        </div>

    </main>

    @if (app()->environment('local'))
    <script defer src=" {{ mix('/js/client_list.min.js') }}"></script>
    @else
    <script defer src=" {{ asset('/js/client_list.min.js') }}"></script>
    @endif

@endsection

@section('footer')
    
@endsection
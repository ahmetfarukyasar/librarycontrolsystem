@extends('layout')

@section('title', 'İade İşlemlerini Yönet')

@section('content')
<div class="row">
    <div class="col-12">
        <h2>İade İşlemlerini Yönet</h2>
        <hr>
        <p>Bu sayfada iade işlemlerini yönetebilirsiniz.</p>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>İade ID</th>
                        <th>Kitap Adı</th>
                        <th>Üye Adı</th>
                        <th>İade Tarihi</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example row -->
                    <tr>
                        <td>1</td>
                        <td>Kitap Adı Örneği</td>
                        <td>Üye Adı Örneği</td>
                        <td>01/01/2023</td>
                        <td>Tamamlandı</td>
                        <td>
                            <button class="btn btn-info btn-sm">Detay</button>
                        </td>
                    </tr>
                    <!-- Add dynamic rows here -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

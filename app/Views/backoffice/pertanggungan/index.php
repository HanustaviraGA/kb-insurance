<div class="row table_data" data-roleable="false" data-role="Company-Read">
    <div class=" col-12" id="tablePertanggunganContainer">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row mb-5">
                    <div class="col-12 col-xl d-flex align-items-center justify-content-between ">
                        <div class=" d-flex align-items-center">
                            <p> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M15 2.28572V3.71428C15 4.97322 11.8646 6 8 6C4.13541 6 1 4.97322 1 3.71428V2.28572C1 1.02678 4.13541 0 8 0C11.8646 0 15 1.02678 15 2.28572ZM15 5.5V8.71428C15 9.97322 11.8646 11 8 11C4.13541 11 1 9.97322 1 8.71428V5.5C2.50391 6.53572 5.2565 7.01788 8 7.01788C10.7435 7.01788 13.4961 6.53572 15 5.5ZM15 10.5V13.7143C15 14.9732 11.8646 16 8 16C4.13541 16 1 14.9732 1 13.7143V10.5C2.50391 11.5357 5.2565 12.0179 8 12.0179C10.7435 12.0179 13.4961 11.5357 15 10.5Z" fill="black" />
                                </svg></p>
                            <h3 class="mt-1 mb-5 ms-5">List Pertanggungan</h3>
                        </div>
                        <div class=" col-lg-6 d-none d-xl-block">
                            <p class="mt-4 mb-5 ms-5 text-end">Pencarian :</p>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-md-6 col-lg-6 d-flex align-items-center mt-5 mt-md-0">
                        <label for="customSearchInput" class="text-muted las la-search position-absolute ms-3 fs-2 mt-1"></label>
                        <input type="search" class="form-control form-control ps-10 bg-gray-100" id="customSearchInput" placeholder="Ketik untuk mencari">
                    </div>
                    <div class="col-12 col-xl-3 col-md-6 col-lg-6 mt-5 my-md-0">
                        <button class="btn btn-primary w-100" onclick="onAdd()" id="toggleFormButton"><i class="las la-plus fs-2"></i> Tambah Pertanggungan</button>
                    </div>
                </div>
                <div class="table-responsive border-2 border-top">
                    <table class="table table-striped table-row-bordered align-middle rounded tdFirstCenter" id="tablePertanggungan">
                        <thead>
                            <tr class="fw-bolder text-dark">
                                <th class="ps-4" width="20">No</th>
                                <th>Nama Nasabah </th>
                                <th>Harga Pertanggungan </th>
                                <th>Jenis Pertanggungan </th>
                                <th>Nama Agent </th>
                                <th>Aksi </th>
                            </tr>
                        </thead>
                        <tbody id="tanggung-body">
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('form.php') ?>

<?php include('javascript.php') ?>

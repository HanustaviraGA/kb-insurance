<div class="col-12 form_data" style="display: none;">
    <form action="javascript:onSave('formPertanggungan')" method="post" id="formPertanggungan" name="formPertanggungan" autocomplete="off" enctype="multipart/form-data">
        <div class="card card-bordered">
            <div class="card-body">
                <div class=" d-md-flex justify-content-between align-items-center border-2 border-bottom">
                    <div onclick="onBack()" style="cursor: pointer;">
                        <p class="text-primary fw-bold"><i class="fas fa-arrow-left text-primary"></i> Kembali ke List Pertanggungan</p>
                    </div>
                    <div class=" d-flex align-items-center ">
                        <div class=" d-flex align-items-center col py-2">
                            <p> <svg xmlns="http://www.w3.org/2000/svg" class=" text-primary" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M15 2.28572V3.71428C15 4.97322 11.8646 6 8 6C4.13541 6 1 4.97322 1 3.71428V2.28572C1 1.02678 4.13541 0 8 0C11.8646 0 15 1.02678 15 2.28572ZM15 5.5V8.71428C15 9.97322 11.8646 11 8 11C4.13541 11 1 9.97322 1 8.71428V5.5C2.50391 6.53572 5.2565 7.01788 8 7.01788C10.7435 7.01788 13.4961 6.53572 15 5.5ZM15 10.5V13.7143C15 14.9732 11.8646 16 8 16C4.13541 16 1 14.9732 1 13.7143V10.5C2.50391 11.5357 5.2565 12.0179 8 12.0179C10.7435 12.0179 13.4961 11.5357 15 10.5Z" fill="black" />
                                </svg></p>
                            <h3 id="jdl_form_pertanggungan" class="mt-1 mb-5 ms-5 fw-bold">Form Tambah Pertanggungan</h3>
                        </div>
                    </div>
                </div>

                <div class=" border border-2 rounded-3 mt-10">
                    <div class=" mx-3 md:mx-10 my-8">
                        <h3 class=" text-primary">Detail Pertanggungan</h3>
                    </div>
                    <div class="border-2 border-bottom"></div>
                    <input type="hidden" name="id_premi" id="id_premi">

                    <div class=" mt-8 mx-3 md:mx-7">

                        <div class=" d-md-flex">
                            <div class="fv-row mb-5 col-md-12 px-3 ">
                                <label for="" class="required form-label mb-3 fw-bold">Nama Nasabah</label>
                                <input type="text" name="nama_nasabah" id="nama_nasabah" class="form-control bg-white border border-2 py-4 px-6 rounded-3 fw-light fs-6" placeholder="Input Nama Nasabah" />
                            </div>
                        </div>

                        <div class=" d-md-flex">
                            <div class="fv-row mb-5 col-md-12 px-3 ">
                                <?php
                                    $today = date('m/d/Y'); // Tanggal hari ini
                                    $nextYear = date('m/d/Y', strtotime('+1 year')); // Tanggal hari ini di tahun berikutnya
                                    $date = $today . ' - ' . $nextYear;
                                ?>
                                <label for="" class="required form-label mb-3 fw-bold">Periode Pertanggungan</label>
                                <input type="text" name="periode_pertanggungan" value="<?= $date ?>" id="periode_pertanggungan" class="form-control bg-white border border-2 py-4 px-6 rounded-3 fw-light fs-6" placeholder="Input Periode Pertanggungan" />
                            </div>
                        </div>

                        <div class=" d-md-flex">
                            <div class="fv-row mb-5 col-md-12 px-3 ">
                                <label for="" class="required form-label mb-3 fw-bold">Pertanggungan / Kendaraan</label>
                                <input type="text" name="pertanggungan_kendaraan" id="pertanggungan_kendaraan" class="form-control bg-white border border-2 py-4 px-6 rounded-3 fw-light fs-6" placeholder="Input Pertanggungan / Kendaraan" />
                            </div>
                        </div>

                        <div class=" d-md-flex">
                            <div class="fv-row mb-5 col-md-12 px-3 ">
                                <label for="" class="required form-label mb-3 fw-bold">Harga Pertanggungan</label>
                                <input type="number" name="harga_pertanggungan" id="harga_pertanggungan" class="form-control bg-white border border-2 py-4 px-6 rounded-3 fw-light fs-6" placeholder="Input Harga Pertanggungan" />
                            </div>
                        </div>

                        <div class=" d-md-flex">
                            <div class="fv-row mb-5 col-md-12 px-3 ">
                                <label for="" class="required form-label mb-3 fw-bold">Jenis Pertanggungan</label>
                                <select name="jenis_pertanggungan" id="jenis_pertanggungan" class="form-control bg-white border border-2 py-4 px-6 rounded-3 fw-light fs-6">
                                    <option selected disabled>Pilih Jenis Pertanggungan</option>
                                    <option value="1">Comprehensive</option>
                                    <option value="2">Total Loss Only</option>
                                </select>
                            </div>
                        </div>

                        <div class=" d-md-flex">
                            <div class=" ms-3">
                                <label for="" class="required form-label">Resiko Pertanggungan</label>
                                <div class="fv-row form-check form-switch py-4">
                                    <input class="form-check-input" name="banjir" type="checkbox" checked id="flexSwitchDefault" />
                                    <label class="form-check-label" for="flexSwitchDefault">
                                        Banjir
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class=" d-md-flex">
                            <div class=" ms-3">
                                <div class="fv-row form-check form-switch py-4">
                                    <input class="form-check-input" name="gempa" type="checkbox" id="flexSwitchDefault2" />
                                    <label class="form-check-label" for="flexSwitchDefault">
                                        Gempa
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                    

                </div>

            </div>

            <div class="card-footer d-flex justify-content-lg-end justify-content-center py-6 px-9">
                <div class="d-flex">
                    <!-- <button type="button" onclick="onCancel(this)" class="btn fw-bold fs-7 fs-sm-5 text-danger border border-2 border-danger me-2 py-3 px-10 px-sm-20 ">
                        Cancel
                    </button> -->
                    <button type="reset" onclick="onReset()" class="btn fw-bold fs-5 btn-outline btn-outline-primary btn-sm ms-2 py-3 px-10 px-sm-20 actCreate">
                        Reset
                    </button>
                    <button type="submit" class="btn fw-bold fs-5 btn-primary btn-sm ms-2 py-3 px-10 px-sm-20 actCreate">
                        Simpan
                    </button>
                    <!-- <button type="button" onclick="verifyCompany()" id="verification_button" class="btn fw-bold fs-5 btn-success btn-sm ms-2 py-3 px-10 px-sm-20 d-none">
                        Verifikasi
                    </button> -->
                </div>
            </div>
        </div>
    </form>
</div>
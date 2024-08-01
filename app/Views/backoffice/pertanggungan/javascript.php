<script>
    $(document).ready(function() {
        $('input[name="periode_pertanggungan"]').daterangepicker();
        let searchDelay;
        let $searchInput = $('#customSearchInput');
        // Bind keyup event to custom search input with a delay
        $searchInput.keyup(function() {
            clearTimeout(searchDelay);
            let searchText = $searchInput.val();
            searchDelay = setTimeout(function() {
                table.search(searchText).draw();
            }, 300); // Adjust the delay time (in milliseconds) as needed
        });
        init_table();
    });

    function init_table() {
        // Destroy the table if it already exists
        if ($.fn.DataTable.isDataTable('#tablePertanggungan')) {
            $('#tablePertanggungan').DataTable().destroy();
        }

        // Initialize the DataTable
        table = $('#tablePertanggungan').DataTable({
            responsive: true,
            serverSide: true,
            processing: true,
            pageLength: 10,
            ajax: {
                url: '<?= base_url('/pertanggungan/init_table') ?>',
                type: 'POST',
                data: function(d) {
                    d.search.value = $('#customSearchInput').val(); // Custom search input
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 419 || xhr.status === 401) {
                        SUPER.showMessage({
                            success: false,
                            message: 'Sesi telah berakhir',
                            title: 'Gagal'
                        });
                        setLogout();
                    } else {
                        console.error(error);
                    }
                }
            },
            columns: [
                { 
                    data: '0', 
                    orderable: true,
                },
                { 
                    data: '2', 
                    name: 'nama_nasabah',
                    orderable: true,
                    render: function (data, type, row) {
                        return SUPER.trim_string(row[2], 30);
                    }
                },
                { 
                    data: '3', 
                    name: 'harga_pertanggungan',
                    orderable: true,
                    render: function (data, type, row) {
                        return SUPER.ntr(row[3]);
                    }
                },
                { 
                    data: '4', 
                    name: 'nama_jenis_pertanggungan',
                    orderable: true,
                    render: function (data, type, row) {
                        return row[4];
                    }
                },
                { 
                    data: '5', 
                    name: 'name',
                    orderable: true,
                    render: function (data, type, row) {
                        return SUPER.trim_string(row[5], 30);
                    }
                },
                { 
                    data: null,
                    orderable: false,
                    render: function (data, type, row) {
                        var btn_aksi = '';

                        btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md mr-2" title="Print" onclick="onPrint(this)" data-id="` + row[1] + `">
                            <i class="la la-print"></i> Print
                        </a>`;

                        btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md mr-2" style="margin-left: 15px !important" title="Edit" onclick="onEdit(this)" data-id="` + row[1] + `">
                            <i class="la la-edit"></i> Edit
                        </a>`;

                        btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-danger" style="margin-left: 15px !important" onclick="onDestroy(this)" data-id="` + row[1] + `" title="Hapus" >
                            <span class="la la-trash"></span> Hapus
                        </a>`;

                        return btn_aksi;
                    }
                }
            ]
        });
    }

    function onAdd(){
        blockPage();
        $('#formPertanggungan')[0].reset();
        $('#reset_button').show();
        $('#jdl_form_pertanggungan').text('Form Tambah Pertanggungan');
        SUPER.switchForm({
			tohide: 'table_data',
			toshow: 'form_data'
		});
        unblockPage();
    }

    function onBack(){
        blockPage();
        $('#formPertanggungan')[0].reset();
        $('#reset_button').show();
        $('#jdl_form_pertanggungan').text('Form Tambah Pertanggungan');
        SUPER.switchForm({
			tohide: 'form_data',
			toshow: 'table_data'
		});
        init_table();
        unblockPage();
    }

    function onEdit(element){
        var id = $(element).data('id');
        blockPage();
        $('#formPertanggungan')[0].reset();
        $('#jdl_form_pertanggungan').text('Form Edit Pertanggungan');
        $('#reset_button').hide();
        SUPER.switchForm({
            tohide: 'table_data',
            toshow: 'form_data'
        });
        $.post('<?= base_url('pertanggungan/read') ?>', {id: id}, function(data){
            $('#id_premi').val(data.result.id_premi).trigger('change');
            $('#nama_nasabah').val(data.result.nama_nasabah).trigger('change');
            $('#periode_pertanggungan').val(formatDateToMDY(data.result.periode_awal_pertanggungan) + ' - ' + formatDateToMDY(data.result.periode_akhir_pertanggungan)).trigger('change');
            $('#pertanggungan_kendaraan').val(data.result.pertanggungan_kendaraan).trigger('change');
            $('#harga_pertanggungan').val(data.result.harga_pertanggungan).trigger('change');
            $('#jenis_pertanggungan').val(data.result.jenis_pertanggungan).trigger('change');
            $.each(data.result.detail_premi, function(key, value){
                var rsk = value.nama_resiko_jenis_pertanggungan.toLowerCase();
                $('input[name="'+rsk+'"]').prop('checked', true);
                $('#'+rsk+'_switch').prop('checked', true);
            });
        });
        $('#nama_nasabah').attr('readonly', true);
        unblockPage();
    }

    function onReset(){
        $('#formPertanggungan')[0].reset();
    }

    function onSave(form){
        if (!$('input[type="checkbox"]:checked').length) {
            SUPER.showMessage({
                success: false,
                message: 'Pilih salah satu resiko pertanggungan',
                title: 'Gagal'
            });
            return;
        }
        SUPER.saveForm({
            element: form,
            checker: 'id_premi',
            add_route: '<?= base_url('pertanggungan/create') ?>',
            update_route: '<?= base_url('pertanggungan/update') ?>',
            onBack: true,
            // reInitTable: true,
        });
    }

    function onDestroy(element){
        var id = $(element).data('id');
        SUPER.confirm({
			message: "Apa Anda yakin ingin menghapus data ini?",
			callback: (result) => {
				if (result) {
                    $.ajax({
                        url: '<?= base_url('pertanggungan/delete') ?>',
                        type: 'DELETE',
                        data: {
                            'id': id
                        },
                        success: function(response) {
                            if(response.success) {
                                SUPER.showMessage({
                                    success: true,
                                    message: 'Berhasil melakukan penghapusan data',
                                    title: 'Berhasil'
                                });
                            }else{
                                SUPER.showMessage();
                            }
                            init_table();
                        },error: function(response) {
                            SUPER.showMessage({
                                success: false,
                                message: response.message,
                                title: 'Gagal'
                            });
                        }
                    });
                }
			}
		});
    }

    function onPrint(element){
        var id = $(element).data('id');
        window.open('<?= base_url('pertanggungan/print') ?>/' + id, '_blank');
    }

    function formatDateToMDY(dateString) {
        var dateParts = dateString.split('-');
        if (dateParts.length === 3) {
            var year = dateParts[0];
            var month = dateParts[1];
            var day = dateParts[2];
            return month + '/' + day + '/' + year;
        } else {
            return 'Invalid date format';
        }
    }
</script>
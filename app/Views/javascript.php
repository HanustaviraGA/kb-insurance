<script type="text/javascript">
    $(document).ready(function() {
        init();
    });

    function init() {
        var t, e, i;
        t = document.querySelector("#kt_sign_in_form");
        e = document.querySelector("#kt_sign_in_submit");
        i = FormValidation.formValidation(t, {
            fields: {
                email: {
                    validators: {
                        notEmpty: {
                            message: "Alamat email wajib diisi",
                        },
                        emailAddress: {
                            message: "Alamat email tidak valid",
                        },
                    },
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: "Kata sandi wajib diisi",
                        },
                    },
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: ".fv-row",
                }),
            },
        });

        e.addEventListener("click", function (n) {
            n.preventDefault();
            i.validate().then(function (i) {
                if ("Valid" == i) {
                    e.setAttribute("data-kt-indicator", "on");
                    e.disabled = true;
                    // setTimeout(function () {
                    // }, 2000);
                    blockPage();
                    e.removeAttribute("data-kt-indicator");
                    e.disabled = false;

                    $.ajax({
                        type: "POST",
                        url: "<?= base_url('auth/login') ?>",
                        data: {
                            email: t.querySelector('[name="email"]').value,
                            password: t.querySelector('[name="password"]').value,
                        },
                        success: function (data) {
                            t.querySelector('[name="email"]').value = "";
                            t.querySelector('[name="password"]').value = "";
                            unblockPage();
                            redirect_url();
                        },
                        error: function (data) {
                            unblockPage();
                            $.confirm({
                                title: 'Gagal',
                                content: 'Silakan periksa kembali inputan anda.',
                                theme: 'material',
                                type: 'red',
                                buttons: {
                                    ok: {
                                        text: "ok!",
                                        btnClass: 'btn-primary',
                                        keys: ['enter'],
                                        // action: function () {
                                        //     config.callback(true);
                                        // }
                                    }
                                }
                            });
                        },

                    });
                } else {
                    unblockPage();
                    $.confirm({
                        title: 'Gagal',
                        content: 'Silakan periksa kembali inputan anda.',
                        theme: 'material',
                        type: 'red',
                        buttons: {
                            ok: {
                                text: "ok!",
                                btnClass: 'btn-primary',
                                keys: ['enter'],
                                // action: function () {
                                //     config.callback(true);
                                // }
                            }
                        }
                    });
                }
            });
        });
    }

    function redirect_url(){
        var currentURL = window.location.href;
        // Check if the URL contains '/login'
        if(currentURL.indexOf('/') !== -1) {
            window.location.href = '/dashboard';
        }
        // Check if the URL contains '/panel'
        if(currentURL.indexOf('/dashboard') !== -1) {
            // Use regular expression to match any characters after '/panel/'
            var regex = /\/dashboard\/([^\/]+)/;
            var match = currentURL.match(regex);
            if(match) {
                var subpage = match[1];
                // Redirect to the page specified after '/panel/'
                window.location.href = '/dashboard/' + subpage;
            }else{
                window.location.href = '/dashboard';
            }
        }
    }

    function blockPage(message = 'Memuat...'){
        $.blockUI({
            message: `<div class="blockui-message" style="z-index: 9999"><span class="spinner-border text-primary"></span> ${message} </div>`,
            css: {
                border: 'none',
                backgroundColor: 'rgba(47, 53, 59, 0)',
                'z-index': 9999
            }
        });
    }

    function unblockPage(delay = 500){
        window.setTimeout(function () {
            $.unblockUI();
        }, delay);
    }
</script>

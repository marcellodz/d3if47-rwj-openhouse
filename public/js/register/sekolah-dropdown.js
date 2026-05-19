$(document).ready(function(){

    /* ================= LOAD SEKOLAH ================= */

    $('#kota').change(function(){

        const kota = $(this).val();

        if(!kota){

            $('#sekolah_select').html('<option value="">Pilih Sekolah/Instansi</option>');
            $('#error-sekolah').text('');
            return;

        }

        $.ajax({

            url: '/api/sekolah/' + encodeURIComponent(kota),
            type: 'GET',

            success: function(data){

                let options = '<option value="">Pilih Sekolah/Instansi</option>';

                data.forEach(function(sekolah){
                    options += `<option value="${sekolah}">${sekolah}</option>`;
                });

                // optional: tambah opsi "Lainnya"
                options += '<option value="Lainnya">Lainnya</option>';

                $('#sekolah_select').html(options);
                $('#error-sekolah').text('');

            },

            error: function(){

                $('#error-sekolah').text('Gagal memuat data sekolah.');

            }

        });

    });


    /* ================= CEK KOTA ================= */

    $('#sekolah_select').on('mousedown', function(e){

        const kota = $('#kota').val();

        if(!kota){

            e.preventDefault();
            $('#error-sekolah').text('Pilih kota terlebih dahulu.');

        }

    });


    /* ================= SEKOLAH LAINNYA ================= */

    $('#sekolah_select').change(function(){

        const sekolah = $(this).val();

        if(sekolah === "Lainnya"){

            $('#sekolah_lainnya_wrapper').slideDown(150);

        }else{

            $('#sekolah_lainnya_wrapper').slideUp(150);

        }

    });

});
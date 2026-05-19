$(document).ready(function(){

    /* ================= LOAD PROVINSI ================= */

    const provinsiSelect = $('#provinsi');

    if(provinsiSelect.length){

        fetch('/api/provinsi')
        .then(res => res.json())
        .then(data => {

            let options = '<option value="">Pilih Provinsi</option>';

            data.forEach(function(prov){
                options += `<option value="${prov}">${prov}</option>`;
            });

            provinsiSelect.html(options);

        })
        .catch(err => {
            console.error("Error load provinsi:", err);
        });

    }


    /* ================= LOAD KOTA ================= */

    $('#provinsi').change(function(){

        const provinsi = $(this).val();

        if(!provinsi){

            $('#kota').html('<option value="">Pilih Kota/Kabupaten</option>');
            $('#error-kota').text('');
            return;

        }

        $.ajax({

            url: '/api/kota/' + encodeURIComponent(provinsi),
            type: 'GET',

            success: function(data){

                let options = '<option value="">Pilih Kota/Kabupaten</option>';

                data.forEach(function(kota){
                    options += `<option value="${kota}">${kota}</option>`;
                });

                $('#kota').html(options);
                $('#error-kota').text('');

            },

            error: function(){

                $('#error-kota').text('Gagal memuat data kota.');

            }

        });

    });


    /* ================= VALIDASI KOTA ================= */

    $('#kota').on('mousedown', function(e){

        const provinsi = $('#provinsi').val();

        if(!provinsi){

            e.preventDefault();
            $('#error-kota').text('Pilih provinsi terlebih dahulu.');

        }

    });

});
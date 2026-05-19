<h2 class="section-title">Pendaftaran Open House Telkom University</h2>

<div class="contact-container">

    <div class="contact-form">

        <form action="{{ route('register.store') }}" method="POST">
            @csrf

            <!-- ================= NAMA ================= -->
            <div class="form-group">

                <label for="nama">Nama Lengkap</label>

                <input type="text" name="nama" id="nama" placeholder="Masukkan nama lengkap Anda" required>

            </div>


            <!-- ================= WHATSAPP ================= -->
            <div class="form-group">

                <label for="hp">No. WhatsApp</label>

                <input type="tel" name="hp" id="hp" placeholder="Format: 628xxxxxxxxxx" pattern="^62[0-9]{8,15}$"
                    inputmode="numeric" required>

                <small class="form-info">
                    (contoh: 628123456789)
                </small>

            </div>


            <!-- ================= EMAIL ================= -->
            <div class="form-group">

                <label for="email">Email</label>

                <input type="email" name="email" id="email" placeholder="Masukkan email Anda" required>

            </div>


            <!-- ================= PASSWORD ================= -->
            <div class="form-group">

                <label for="password">Password</label>

                <div class="password-container">

                    <input type="password" name="password" id="password"
                        placeholder="Masukkan password untuk akun Open House Anda" required>

                    <i class="fas fa-eye-slash toggle-password" id="togglePassword"></i>

                </div>

            </div>

            <!-- ================= SAYA MERUPAKAN ================= -->

            <div class="form-group">

                <label for="kelas">Saya Merupakan</label>

                <select name="kelas" id="kelas_select" class="form-control" required>

                    <option value="">Pilih</option>

                    <option value="12">Siswa Kelas 12</option>
                    <option value="11">Siswa Kelas 11</option>
                    <option value="10">Siswa Kelas 10</option>
                    <option value="Gap Year">Alumni SMA (Gap Year)</option>

                    <option value="Guru">Guru</option>
                    <option value="Orang Tua">Orang Tua Calon Mahasiswa</option>
                    <option value="Mahasiswa">Mahasiswa</option>
                    <option value="Fresh Graduate">Fresh Graduate</option>
                    <option value="Karyawan">Karyawan</option>
                    <option value="Dosen">Dosen</option>
                    <option value="Entrepreneur">Wiraswasta / Entrepreneur</option>

                </select>

            </div>


            <!-- ================= PROVINSI ================= -->

            <div class="form-group">

                <label for="provinsi">Provinsi</label>

                <select name="provinsi" id="provinsi" class="form-control" required>

                    <option value="">Pilih Provinsi</option>


                </select>

            </div>


            <!-- ================= KOTA ================= -->

            <div class="form-group">

                <label for="kota">Kota/Kabupaten</label>

                <select name="kota" id="kota" class="form-control" required>
                    <option value="">Pilih Kota/Kabupaten</option>
                </select>

                <small id="error-kota" class="error-text"></small>

            </div>


            <!-- ================= SEKOLAH ================= -->

            <div id="sekolah_wrapper" style="display:none;">

                <div class="form-group">

                    <label for="sekolah_select">Sekolah/Instansi</label>

                    <select name="sekolah" id="sekolah_select" class="form-control">

                        <option value="">Pilih Sekolah/Instansi</option>

                    </select>

                    <small id="error-sekolah" class="error-text"></small>

                </div>


                <div class="form-group" id="sekolah_lainnya_wrapper" style="display:none;">

                    <label for="sekolah_lainnya">Sekolah/Instansi Lainnya</label>

                    <input type="text" name="sekolah_lainnya" id="sekolah_lainnya" placeholder="Masukkan nama sekolah">

                </div>

            </div>

            <!-- ================= FIELD KHUSUS SISWA ================= -->

            <div class="form-group" id="siswa_fields" style="display:none;">

                <label for="jurusan_sekolah">Jurusan Sekolah</label>

                <select name="jurusan_sekolah" id="jurusan_sekolah" class="form-control">

                    <option value="">Pilih Jurusan</option>

                    <option value="IPA">IPA</option>
                    <option value="IPS">IPS</option>
                    <option value="Bahasa">Bahasa</option>
                    <option value="Agama">Agama</option>
                    <option value="SMK Teknik">SMK Teknik</option>
                    <option value="SMK Non-Teknik">SMK Non-Teknik</option>
                    <option value="Kurikulum Merdeka">Kurikulum Merdeka</option>

                </select>


                <br><br>


                <label for="jurusan_minat">Jurusan Yang Diminati</label>

                <select name="jurusan_minat" id="jurusan_minat" class="form-control">

                    <option value="">Pilih Jurusan Minat</option>

                    <option value="Akuntansi & Keuangan">Akuntansi & Keuangan</option>
                    <option value="Seni">Seni</option>
                    <option value="Game & App Development">Game & App Development</option>
                    <option value="Engineering">Engineering</option>
                    <option value="Kesehatan">Kesehatan</option>
                    <option value="Energi Terbarukan">Energi Terbarukan</option>
                    <option value="Perhotelan & Pariwisata">Perhotelan & Pariwisata</option>
                    <option value="Teknik Industri & Logistik">Teknik Industri & Logistik</option>
                    <option value="Psikologi">Psikologi</option>
                    <option value="Teknologi Informasi">Teknologi Informasi</option>
                    <option value="Komunikasi & Media">Komunikasi & Media</option>
                    <option value="Bisnis">Bisnis</option>

                </select>

            </div>

            <!-- ================= KAMPUS ASAL ================= -->
            <div class="form-group" id="kampus_wrapper" style="display:none;">

                <label for="kampus_asal">Kampus Asal</label>

                <input type="text" id="kampus_asal" placeholder="Masukkan nama kampus asal" class="form-control">

                <!-- masuk ke sekolah_lainnya -->
                <input type="hidden" name="sekolah_lainnya" id="kampus_hidden">

            </div>


            <!-- ================= INSTANSI ================= -->
            <div class="form-group" id="instansi_wrapper" style="display:none;">

                <label for="instansi_asal">Nama Instansi</label>

                <input type="text" id="instansi_asal" placeholder="Masukkan nama instansi / perusahaan"
                    class="form-control">

                <!-- masuk ke sekolah_lainnya -->
                <input type="hidden" name="sekolah_lainnya" id="instansi_hidden">

            </div>
            <!-- ================= GURU FIELD ================= -->

            <div id="guru_fields" style="display:none;">

                <div class="form-group">

                    <label for="prodi_sekarang">Jenjang Pendidikan Terakhir</label>

                    <select name="prodi_sekarang" id="prodi_sekarang" class="form-control">

                        <option value="">Pilih Jenjang Pendidikan</option>
                        <option value="D3">Diploma (D3)</option>
                        <option value="S1">Sarjana (S1)</option>
                        <option value="S2">Magister (S2)</option>
                        <option value="Lainnya">Lainnya</option>

                    </select>

                </div>


                <div class="form-group" id="lanjut_studi_wrapper" style="display:none;">

                    <label for="jenjang_studi">
                        Apakah Anda berminat melanjutkan studi di Telkom University?
                    </label>

                    <select name="jenjang_studi" id="jenjang_studi" class="form-control">

                        <option value="">Pilih</option>
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>

                    </select>

                </div>


                <div class="form-group" id="prodi_tujuan_wrapper" style="display:none;">

                    <label for="prodi_tujuan">
                        Program Studi Tujuan di Telkom University
                    </label>

                    <select name="prodi_tujuan" id="prodi_tujuan" class="form-control">

                        <option value="">Pilih Program Studi Tujuan</option>

                    </select>

                </div>

            </div>

            <!-- SESI -->
            <div class="form-group">
                <label><b>Kegiatan yang ingin diikuti (opsional)</b></label>
                <p style="color:#aaa;font-size:13px;margin-top:4px;">
                    Pilih sesi yang ingin kamu ikuti atau tidak mengikuti, anda bisa memnuat beberapa pilihan.
                    <br>
                    Klik pada pilihan sesi yang sama untuk menutup kolom pilihan kegiatan pada sesi tersebut.
                </p>
            </div>

            <div id="sesi_container">

                <div class="sesi-card" data-sesi="1" data-waktu="09.15 - 10.45">
                    SESI 1 (09.15 - 10.45)
                </div>

                <div class="sesi-card" data-sesi="2" data-waktu="10.35 - 12.10">
                    SESI 2 (10.35 - 12.10)
                </div>

                <div class="sesi-card" data-sesi="3" data-waktu="12.00 - 13.35">
                    SESI 3 (12.00 - 13.35)
                </div>

                <div class="sesi-card" data-sesi="4" data-waktu="13.25 - 15.00">
                    SESI 4 (13.25 - 15.00)
                </div>

                <div class="sesi-card" data-sesi="none">
                    TIDAK MENGIKUTI KEGIATAN
                </div>

            </div>

            <input type="hidden" name="sesi" id="selected_sesi">

            <!-- container dropdown kegiatan -->
            <div id="kegiatan_container" style="display:none;"></div>
            <br>
            <!-- ================= CAMPUS TOUR ================= -->

            <div class="form-group">

                <label>Apakah ingin mengikuti Campus Tour?</label>

                <select name="ikut_tour" id="ikut_tour" class="form-control">
                    <option value="">Pilih</option>
                    <option value="Ya">Ya</option>
                    <option value="Tidak">Tidak</option>
                </select>

            </div>

            <div class="form-group" id="campus_tour_wrapper" style="display:none;">

                <label>Pilih Sesi Campus Tour</label>

                <select name="campus_tour_sesi" id="campus_tour_sesi" class="form-control">
                    <option value="">Pilih sesi campus tour</option>
                </select>

            </div>

            <!-- ================= TEL-U EXPLORE ================= -->

            <div class="form-group">

                <label for="telu_explore">
                    APAKAH KAMU INGIN MENGIKUTI <b>TEL-U EXPLORE</b>?
                </label>

                <select name="telu_explore" id="telu_explore" class="form-control">

                    <option value="Tidak">Tidak</option>
                    <option value="Ya">Ya</option>

                </select>

            </div>


            <div id="telu_explore_info" class="info-box" style="display:none;">

                <div class="info-icon">
                    <i class="fas fa-clock"></i>
                </div>

                <div class="info-text">
                    <b>Informasi:</b> Lakukan registrasi pada pukul
                    <span class="highlight">10.00 - 13.00</span>
                    Jangan lupa datang tepat waktu ya!
                </div>

            </div>

            <!-- kebijakan privasi -->
            <div class="form-group">
                <div class="form-group-checkbox">
                    <input name="kebijakan_privasi" type="checkbox" id="kebijakan_privasi" value="Setuju" required>
                    <label for="kebijakan_privasi" style="font-size:12px;"><b>Saya telah membaca dan menyetujui
                            <a href="https://smb.telkomuniversity.ac.id/kebijakan-privasi-telkom-university/"
                                target="_blank" style="color:#ff6363">Kebijakan Privasi</a> yang diberikan oleh
                            Telkom University</b></label>
                </div>
            </div>
            <br>


            <!-- ================= SUBMIT ================= -->

            <button type="submit" name="submit" class="submit-btn">
                Daftar
            </button>

        </form>

    </div>

    <div class="contact-info">
        <h3>Connect With Us</h3>
        <div class="info-item">
            <div class="info-icon">📧</div>
            <div class="info-details">
                <h4>Email</h4>
                <p>info@smbbtelkom.ac.id</p>
            </div>
        </div>
        <div class="info-item">
            <div class="info-icon">📱</div>
            <div class="info-details">
                <h4>Phone</h4>
                <p>(022) 7565930</p>
            </div>
        </div>
        <div class="info-item">
            <div class="info-icon">📍</div>
            <div class="info-details">
                <h4>Location</h4>
                <p>Jl. Telekomunikasi No. 1,. Terusan Buah Batu. Bandung 40257,. Jawa Barat, Indonesia</p>
            </div>
        </div>
        <div class="map-container">
            <div class="map-placeholder">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.3389366293327!2d107.62558207572332!3d-6.9692819930313!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e9bc3974981d%3A0x613eec0feec9fcf7!2sTelkom%20University%20Landmark%20Tower%20(TULT)!5e0!3m2!1sen!2sid!4v1759571561672!5m2!1sen!2sid"
                    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="map-overlay"></div>
        </div>
    </div>

</div>
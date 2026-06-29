@php

    /*
    =========================
    DATA RINGKASAN CEPAT
    =========================
    */

    $total_peserta =
        DB::table('super_user')
            ->count();

    $total_booth =
        DB::table('booth')
            ->count();

    $total_admin =
        DB::table('admin_user')
            ->where('role', 'superadmin')
            ->count();

    $total_staff =
        DB::table('admin_user')
            ->where('role', 'staff')
            ->count();

    $total_kunjungan =
        DB::table('booth_kunjungan')
            ->count();

@endphp

<!--
=========================
SUPER ADMIN DASHBOARD
=========================
-->

<div class="dashboard-container">

    <div class="dashboard-grid">

        <!--
        =========================
        TOTAL PESERTA
        =========================
        -->

        <div class="card"
            onclick="showData('peserta', event)">

            <i class="fas fa-user-graduate"></i>

            <h3>
                Total Peserta
            </h3>

            <p>
                {{ $total_peserta }} Terdaftar
            </p>

        </div>

        <!--
        =========================
        TOTAL BOOTH
        =========================
        -->

        <div class="card"
            onclick="showData('booth', event)">

            <i class="fas fa-store"></i>

            <h3>
                Total Booth
            </h3>

            <p>
                {{ $total_booth }} Aktif
            </p>

        </div>

        <!--
        =========================
        ADMIN & STAFF
        =========================
        -->

        <div class="card"
            onclick="showData('staff', event)">

            <i class="fas fa-users-cog"></i>

            <h3>
                Administrator & Staff
            </h3>

            <div class="role-summary">

                <span class="admin-count">
                    Admin: {{ $total_admin }} orang
                </span>

                <span class="staff-count">
                    Staff: {{ $total_staff }} orang
                </span>

            </div>

        </div>

        <!--
        =========================
        TOTAL KUNJUNGAN
        =========================
        -->

        <div class="card"
            onclick="showData('kunjungan', event)">

            <i class="fas fa-handshake"></i>

            <h3>
                Total Kunjungan
            </h3>

            <p>
                {{ $total_kunjungan }} Kunjungan
            </p>

        </div>

        <!--
        =========================
        REWARD CONFIG
        =========================
        -->

        <div class="card"
            onclick="showData('reward_config', event)">

            <i class="fas fa-gift"></i>

            <h3>
                Reward Config
            </h3>

            <p>
                Atur Syarat Hadiah
            </p>

        </div>

        <!--
        =========================
        KEGIATAN PESERTA
        =========================
        -->

        <div class="card"
            onclick="showData('kegiatan_peserta', event)">

            <i class="fas fa-clipboard-list"></i>

            <h3>
                Kegiatan Peserta
            </h3>

            <p>
                Lihat Data Per Sesi
            </p>

        </div>

    </div>

    <!--
    =========================
    DATA SECTION
    =========================
    -->

    <div id="data-section"
        class="table-container">

        <h2>
            Pilih salah satu card di atas untuk melihat data 🔍
        </h2>

    </div>

</div>

<!--
=========================
MODAL FORM STAFF
=========================
-->

<div id="staffModal"
    class="modal">

    <div class="modal-card">

        <div class="modal-header">

            <h2 id="staffFormTitle">

                <i class="fas fa-user-gear"></i>

            </h2>

            <button class="btn-close"
                type="button"
                onclick="closeForm()">

                <i class="fas fa-times"></i>

            </button>

        </div>

        <form id="staffForm">

            @csrf

            <input type="hidden"
                name="id_admin"
                id="id_admin">

            <div class="input-group">

                <input type="text"
                    name="nama_lengkap"
                    id="nama_lengkap"
                    placeholder=" "
                    required>

                <label for="nama_lengkap">

                    <i class="fas fa-id-card"></i>

                    Nama/Posisi

                </label>

            </div>

            <div class="input-group">

                <input type="text"
                    name="username"
                    id="username"
                    placeholder=" "
                    required>

                <label for="username">

                    <i class="fas fa-user"></i>

                    Username

                </label>

            </div>

            <div class="input-group">

                <input type="password"
                    name="password"
                    id="password"
                    placeholder=" ">

                <label for="password">

                    <i class="fas fa-lock"></i>

                    Password kosongkan jika tidak diubah

                </label>

            </div>

            <div class="select-group">

                <label for="role">

                    <i class="fas fa-user-tag"></i>

                    Role

                </label>

                <div class="select-wrapper">

                    <select name="role"
                        id="role">

                        <option value="staff">
                            Staff
                        </option>

                        <option value="superadmin">
                            Superadmin
                        </option>

                    </select>

                    <i class="fas fa-chevron-down select-arrow"></i>

                </div>

            </div>

            <div class="form-footer">

                <button type="submit"
                    class="btn-save">

                    <i class="fas fa-floppy-disk"></i>

                    Simpan

                </button>

                <button type="button"
                    class="btn-cancel"
                    onclick="closeForm()">

                    <i class="fas fa-xmark"></i>

                    Batal

                </button>

            </div>

        </form>

    </div>

</div>

<!--
=========================
MODAL BOOTH
=========================
-->

<div id="boothModal"
    class="modal">

    <div class="modal-card">

        <div class="modal-header">

            <h2 id="boothFormTitle">

                <i class="fas fa-store"></i>

                Tambah Booth

            </h2>

            <button class="btn-close"
                type="button"
                onclick="closeBoothForm()">

                <i class="fas fa-times"></i>

            </button>

        </div>

        <form id="boothForm">

            @csrf

            <input type="hidden"
                name="id_booth"
                id="id_booth">

            <div class="input-group">

                <input type="text"
                    name="nama_booth"
                    id="nama_booth"
                    placeholder=" "
                    required>

                <label for="nama_booth">

                    <i class="fas fa-store"></i>

                    Nama Booth

                </label>

            </div>

            <div class="select-group">

                <label for="kategori">

                    <i class="fas fa-tags"></i>

                    Kategori Booth

                </label>

                <div class="select-wrapper">

                    <select name="kategori"
                        id="kategori"
                        required>

                        <option value=""
                            disabled
                            selected>

                            Pilih Kategori Booth

                        </option>

                        <option value="Booth Fakultas">
                            Booth Fakultas
                        </option>

                        <option value="Booth Pascasarjana">
                            Booth Pascasarjana
                        </option>

                        <option value="Booth IUP">
                            Booth IUP
                        </option>

                        <option value="Booth Stages">
                            Booth Stages
                        </option>

                        <option value="Booth Trial Class">
                            Booth Trial Class
                        </option>

                        <option value="Booth Parent Class">
                            Booth Parent Class
                        </option>

                        <option value="Booth Interaktif">
                            Booth Interaktif
                        </option>

                    </select>

                    <i class="fas fa-chevron-down select-arrow"></i>

                </div>

            </div>

            <div class="select-group">

                <label for="lantai">

                    <i class="fas fa-layer-group"></i>

                    Lantai

                </label>

                <div class="select-wrapper">

                    <select name="lantai"
                        id="lantai"
                        required>

                        <option value=""
                            disabled
                            selected>

                            Pilih Lantai

                        </option>

                        @for ($i = 1; $i <= 10; $i++)

                            <option value="{{ $i }}">

                                Lantai {{ $i }}

                            </option>

                        @endfor

                    </select>

                    <i class="fas fa-chevron-down select-arrow"></i>

                </div>

            </div>

            <div class="form-footer">

                <button type="submit"
                    class="btn-save">

                    <i class="fas fa-floppy-disk"></i>

                    Simpan

                </button>

                <button type="button"
                    class="btn-cancel"
                    onclick="closeBoothForm()">

                    <i class="fas fa-xmark"></i>

                    Batal

                </button>

            </div>

        </form>

    </div>

</div>
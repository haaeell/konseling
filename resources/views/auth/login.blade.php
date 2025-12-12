    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login SI-BK</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <style>
            body {
                background-color: #f8f9fa;
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .login-card {
                width: 100%;
                max-width: 500px;
                padding: 30px;
                border: 2px solid #d467b2;
                border-radius: 12px;
                text-align: center;
                background: #fff;
            }

            .login-logo {
                width: 100px;
                height: 100px;
                background: #d467b2;
                color: #fff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px auto;
                font-weight: bold;
            }

            .form-control {
                border-radius: 10px;
                box-shadow: #d467b2;
            }

            .btn-login {
                background: #d467b2;
                color: #fff;
                border-radius: 10px;
                width: 100%;
                font-weight: bold;
                padding: 10px 0;
            }

            .btn-login:hover {
                background: #d467b2;
                color: #fff;
            }

            .extra-links {
                font-size: 0.9rem;
            }

            .extra-links a {
                text-decoration: none;
                font-weight: 600;
            }
        </style>
    </head>

    <body>
        <div class="login-card">
            <div class="login-logo mb-5">
                <img src="/logosmanja.png" width="100" alt="">
            </div>
            <h5 class="fw-bold">SISTEM INFORMASI BIMBINGAN KONSELING</h5>
            <h6 class="fw-bold mb-5">(SI-BK)</h6>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Username -->
                <div class="mb-3 text-start">
                    <input type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                        placeholder="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3 text-start position-relative">
                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" placeholder="Password" required>
                    <i class="bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3" id="togglePassword"
                        style="cursor: pointer;"></i>
                    @error('password')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember + Forgot -->
                <div class="d-flex justify-content-between mb-3 extra-links">
                    <div>
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember Me</label>
                    </div>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                        Lapor Lupa Password
                    </a>
                </div>

                <!-- Button -->
                <button type="submit" class="btn btn-login">Login</button>
            </form>

        </div>

        <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Laporkan Lupa Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body text-start">
                        <p>
                            Jika Anda tidak dapat masuk ke akun karena lupa password, silakan laporkan kendala tersebut
                            agar dapat segera dibantu pemulihannya.
                        </p>

                        <p class="fw-semibold mb-1">Untuk proses verifikasi dan reset password, hubungi:</p>
                        <ul>
                            <li><strong>BK — Bapak Suraji</strong></li>
                            <a href="https://wa.me/6285327806848?text=Halo%20Pak%20Suraji,%0A%0ASaya%20ingin%20melaporkan%20lupa%20password%20akun%20SI-BK.%0A%0A*Jenis%20Akun:*%20(Siswa%20/%20Orang%20Tua%20/%20Guru)%0A*Nama%20Lengkap:*%20%0A*NIS:*%20%0A*Kelas:*%20%0A*Email%20Login:*%20%0A*Masalah:*%20%0A%0ATerima%20kasih."
                                target="_blank" class="btn btn-success">
                                <i class="bi bi-whatsapp"></i> Hubungi via WhatsApp
                            </a>

                        </ul>

                        <hr>

                        <p class="fw-semibold">Saat mengirim laporan, sertakan informasi berikut:</p>

                        <p class="fw-bold mb-1">Akun Siswa:</p>
                        <ul>
                            <li>Nama lengkap</li>
                            <li>NIS</li>
                            <li>Kelas</li>
                            <li>Email yang digunakan untuk login</li>
                            <li>Penjelasan singkat masalah</li>
                        </ul>

                        <p class="fw-bold mb-1">Akun Orang Tua:</p>
                        <ul>
                            <li>Nama lengkap siswa (anak)</li>
                            <li>NIS siswa (anak)</li>
                            <li>Email yang digunakan untuk login</li>
                            <li>Penjelasan singkat masalah</li>
                        </ul>

                        <p class="fw-bold mb-1">Guru dan Kepala Sekolah:</p>
                        <p>
                            Dapat langsung dikomunikasikan dengan <strong>Bapak Suraji</strong>.
                        </p>

                        <p class="mt-3 text-muted">
                            Tim BK akan memverifikasi data Anda dan membantu memulihkan akses akun.
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            // Toggle password visibility
            const togglePassword = document.querySelector("#togglePassword");
            const password = document.querySelector("#password");
            togglePassword.addEventListener("click", function() {
                const type = password.getAttribute("type") === "password" ? "text" : "password";
                password.setAttribute("type", type);
                this.classList.toggle("bi-eye-slash");
            });
        </script>
    </body>

    </html>

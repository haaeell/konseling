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
                border: 2px solid #000;
                border-radius: 12px;
                text-align: center;
                background: #fff;
            }

            .login-logo {
                width: 100px;
                height: 100px;
                background: #000;
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
                box-shadow: 2px 2px 3px rgba(0, 0, 0, 0.15);
            }

            .btn-login {
                background: #000;
                color: #fff;
                border-radius: 10px;
                width: 100%;
                font-weight: bold;
                padding: 10px 0;
            }

            .btn-login:hover {
                background: #333;
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
            <div class="login-logo">
                LOGO<br>SMANJA
            </div>
            <h5 class="fw-bold">SISTEM INFORMASI BIMBINGAN KONSELING</h5>
            <h6 class="fw-bold">(SI-BK)</h6>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Username -->
                <div class="mb-3 text-start">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        placeholder="name" value="{{ old('name') }}" required>
                    @error('name')
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
                    <a href="#">Forgot Password?</a>
                </div>

                <!-- Button -->
                <button type="submit" class="btn btn-login">Login</button>
            </form>

        </div>

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

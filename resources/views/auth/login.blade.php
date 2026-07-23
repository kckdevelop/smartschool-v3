<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Login — SmartSchool</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="Login to SmartSchool portal.">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite CSS/JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="login-container">
        <div class="login-card glass-panel">
            <div class="login-header">
                <div class="login-logo" id="app-logo">
                    <i class="fa-solid fa-graduation-cap"></i> SmartSchool
                </div>
                <div class="login-subtitle">Sign in to manage your school portal</div>
            </div>

            <form action="{{ route('login') }}" method="POST" id="login-form">
                @csrf
                
                <!-- Username Field -->
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-wrapper">
                        <input type="text" 
                               name="username" 
                               id="username" 
                               class="form-control" 
                               placeholder="Enter your username" 
                               value="{{ old('username') }}" 
                               required 
                               autocomplete="username" 
                               autofocus>
                        <i class="fa-solid fa-user input-icon"></i>
                    </div>
                    @error('username')
                        <div class="error-message">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="form-control" 
                               placeholder="••••••••" 
                               required 
                               autocomplete="current-password">
                        <i class="fa-solid fa-lock input-icon"></i>
                    </div>
                    @error('password')
                        <div class="error-message">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-login" id="submit-login">
                    <span>Sign In</span>
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>
        </div>
    </div>
</body>
</html>

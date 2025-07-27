@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #006C35 0%, #00582C 100%); color: white;">
                    <h3 class="mb-0 fw-bold">{{ __('تسجيل الدخول') }}</h3>
                    <p class="mt-2 mb-0">منصة موثوق - المنصة الرسمية للمعاملات البلدية الرقمية</p>
                </div>

                <div class="card-body p-5">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="email" class="form-label fw-bold">{{ __('البريد الإلكتروني') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                </div>
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="password" class="form-label fw-bold">{{ __('كلمة المرور') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                </div>
                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('تذكرني') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-12 d-grid">
                                <button type="submit" class="btn btn-success btn-lg" style="background-color: #006C35; border-color: #006C35;">
                                    <i class="fas fa-sign-in-alt me-2"></i>{{ __('تسجيل الدخول') }}
                                </button>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link ps-0 text-success" href="{{ route('password.request') }}">
                                        {{ __('نسيت كلمة المرور؟') }}
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-6 text-end">
                                @if (Route::has('register'))
                                    <a class="btn btn-link pe-0 text-success" href="{{ route('register') }}">
                                        {{ __('إنشاء حساب جديد') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3" style="background-color: #f8f9fa;">
                    <div class="small">
                        <a href="{{ route('welcome') }}" class="text-decoration-none text-success">
                            <i class="fas fa-arrow-right me-1"></i> العودة إلى الصفحة الرئيسية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


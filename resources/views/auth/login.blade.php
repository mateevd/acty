@extends('layouts.login')

@section('content')
    @if($errors->has('login'))
        <div class="session-message session-error">{{ $errors->first('login') }}</div>
    @endif

    <div class="flex-col flex-wrap-no justify-center">
        <div class="config-title">{{trans('login.Login')}}</div>

        <form id="login_page" class="flex-col flex-wrap-no items-center hide-submit config-form" method="POST" action="{{ route('login') }}"
              aria-label="{{ trans('login.Login') }}">
            @csrf

            <div class="flex-col flex-wrap-no justify-center pad-h-medium pad-v-top-medium pad-v-bottom-small">
                <div class="flex-col flex-wrap-no justify-flex-end text-center width-rem-15 pad-v-small">
                    <label for="login"
                           class="dashboard-label text-center">{{ trans('login.Log') }}</label>
                    <div class="flex-row flex-wrap-no marg-none">
                        <i class="fas fa-user svg-inside"></i>
                        <input id="login" type="text"
                               class="form-control{{ $errors->has('login') ? ' is-invalid' : '' }} modal-custom-fields-input"
                               name="login" value="{{ old('login') }}" required autofocus tabindex="1">
                    </div>
                </div>
                <div class="flex-col flex-wrap-no justify-flex-end text-center width-rem-15 pad-v-small">
                    <label for="password"
                           class="dashboard-label text-center">{{ trans('login.Password') }}</label>
                    <div class="flex-row flex-wrap-no marg-none">
                        <i class="fas fa-key svg-inside"></i>
                        <input id="password" type="password"
                               class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} modal-custom-fields-input"
                               name="password" required tabindex="2">
                    </div>
                </div>
                <div class="flex-row flex-wrap-no justify-center text-center width-rem-15 pad-v-small">
                    <label for="remember" class="flex-row flex-wrap-yes dashboard-label text-center">{{ trans('login.Remember') }}</label>
                    <div class="flex-row flex-wrap-yes dashboard-label text-center pad-h-left-small">
                        <input type="checkbox" name="remember" id="remember"
                               {{ old('remember') ? 'checked' : '' }} tabindex="3">
                    </div>
                </div>
            </div>

            {{-- Forgot password--}}
            {{--<a class="btn btn-link" href="{{ route('password.request') }}">
				{{ __('Forgot Your Password?') }}
			</a>--}}

            <div class="config-bottom">
                <button id="btn-submit-form" class="modal-custom-btn-horizontal" tabindex="101"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Login"><i class="far fa-check-circle"></i>
                </button>

            </div>
        </form>
    </div>

    <div class="text-center total-separator"></div>

@endsection

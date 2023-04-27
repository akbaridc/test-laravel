@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif

            @if ($message = Session::get('failed'))
                <div class="alert alert-danger">
                    <p>{{ $message }}</p>
                </div>
            @endif
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Confirmation Code</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register.confirmation-process') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="code" class="col-md-4 col-form-label text-md-end">Code</label>

                                <div class="col-md-6">
                                    <input type="hidden" name="email" value="{{ $email }}" />
                                    <input id="code" type="text"
                                        class="form-control @error('code') is-invalid @enderror" name="code"
                                        value="{{ old('code') }}" required autocomplete="off" autofocus>

                                    @error('code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Konfirmation Kode
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

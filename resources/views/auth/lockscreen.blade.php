@extends('layouts.auth', ['title' => 'Lock Screen'])

@section('content')
<div class="col-xl-5">
    <div class="row">
        <div class="col-md-7 mx-auto">
            <div class="mb-0 border-0 p-md-5 p-lg-0 p-4">
                <div class="mb-4 p-0">
                    <a href="{{ route('any', 'index') }}" class="auth-logo">
                        <img src="/images/logo-dark.png" alt="logo-dark" class="mx-auto" height="28" />
                    </a>
                </div>

                <div class="pt-0">
                    <form action="#" class="my-4">
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input class="form-control" type="password" id="password" required="" placeholder="Enter your password">
                        </div>
                        
                        <div class="form-group mb-0 row">
                            <div class="col-12">
                                <div class="d-grid">
                                    <button class="btn btn-primary" type="submit"> Unlock </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-7">
    <div class="account-page-bg p-md-5 p-4">
        <div class="text-center">
            <h3 class="text-dark mb-3 pera-title">Quick, Effective, and Productive With Tapeli Admin Dashboard</h3>
            <div class="auth-image">
                <img src="/images/authentication.svg" class="mx-auto img-fluid"  alt="images">
            </div>
        </div>
    </div>
</div>
@endsection
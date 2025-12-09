@extends('admin.admin_layouts.app')

@section('title', 'Fix Password Hashing')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Fix Password Hashing</h4>
                </div>
                <div class="card-body">
                    @if($fixedCount > 0)
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle"></i> Success!</h5>
                            <p>{{ $fixedCount }} user password(s) have been successfully rehashed using bcrypt algorithm.</p>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle"></i> No Action Needed</h5>
                            <p>All user passwords are already properly hashed with bcrypt algorithm.</p>
                        </div>
                    @endif

                    @if(count($errors) > 0)
                        <div class="alert alert-danger">
                            <h5><i class="fas fa-exclamation-triangle"></i> Errors Encountered</h5>
                            <ul>
                                @foreach($errors as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

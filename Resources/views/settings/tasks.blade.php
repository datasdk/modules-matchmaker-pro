@extends('layouts.app')

@section('content')
<h3 class="mb-4">Task Settings</h3>

<form method="POST" action="{{ route('promatch.settings.store') }}">
    @csrf

    <table class="table">
        <tbody>
            <!-- Admin notification email -->
            <tr>
                <td width="250" class="fw-bold">Admin notification email</td>
                <td>
                    <input type="text" class="form-control" name="admin_notification_email" 
                        value="{{ old('admin_notification_email', $taskSettings['admin_notification_email']) }}">
                </td>
            </tr>

         
        </tbody>
    </table>

    <button class="btn btn-primary mt-3">Save Settings</button>
</form>
@endsection

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Form Pendaftaran</h2>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <label>Nama:</label>
        <input type="text" name="name" value="{{ old('name') }}" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="{{ old('email') }}" required><br>

        <label>No HP:</label>
        <input type="text" name="no_hp" value="{{ old('no_hp') }}" required><br>

        <label>Pekerjaan:</label>
        <select name="job" required>
            <option value="">-- Pilih Job --</option>
            <option value="manajer">Manajer</option>
            <option value="divisi marketing">Divisi Marketing</option>
            <option value="staff service">Staff Service</option>
            <option value="divisi finance">Divisi Finance</option>
        </select><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <label>Konfirmasi Password:</label>
        <input type="password" name="password_confirmation" required><br>

        <button type="submit">Daftar</button>
    </form>
</body>
</html>

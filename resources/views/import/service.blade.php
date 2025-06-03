<!DOCTYPE html>
<html>
<head><title>Import Excel</title></head>
<body>

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif
<h3>Import Service</h3>
<form action="/service/import" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required />
    <button type="submit">Import Excel</button>
</form>

</body>
</html>

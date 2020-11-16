<?php
  $imageName = explode('.', $xibo->image_name)
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <title>Document</title>
</head>
<body>
  <div class="container">
    <div class="row p-3">
      <a href="/">Kembali</a>
    </div>
    <div class="row" style="background-color: #f5f5f5">
      <div class="col-8">
        <img src="{{ $xibo->xiboImage() }}" alt="" class="w-50 p-5">
      </div>
      <div class="col-4 py-5">
        <p class="py-3">{{ $xibo->image_name }}</p>
        <p>Nama gambar</p>
        <form action="/edit/store" method="post" class="form-group">
          @csrf
          <input type="text" value="{{ $imageName[0] }}" name="image_name" class="form-control my-3">
          <input type="hidden" name="image_type" value="{{ $imageName[1] }}">
          <input type="hidden" name="media_id" value="{{ $xibo->media_id }}">
          <input type="hidden" name="id" value="{{ $xibo->id }}">
          <button type="submit" class="btn btn-primary">Edit</button>
          <a href="/" class="btn btn-info">Cancel</a>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
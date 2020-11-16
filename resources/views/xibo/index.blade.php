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
    <div class="row">
      <div class="col-8 d-flex flex-wrap py-4" style="height: 100vh; overflow-y: scroll">
        @foreach($xibo as $x)
          <?php
            $name = explode('.', $x["image_name"]);
            $imagename = $name[0];
          ?>
          <div class="card m-3 p-3" style="width: 18rem;">
            <img class="card-img-top" src="{{ 'storage/' . $x["image"] }}" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">{{ ucwords($imagename) }}</h5>
              <p class="card-text">{{ $x["image_name"] }}</p>
              <a href="/edit/{{ $x["id"] }}" class="btn btn-primary">Edit</a>
            </div>
          </div>
        @endforeach
      </div>
      <div class="col-4">
        <form action="/store" method="post" enctype="multipart/form-data">
          @csrf
          <div class="form-group pt-4">
            <label for="file">Example file input</label>
            <input type="file" class="form-control-file" type="file" name="file" id="file">
            <button type="submit" class="btn btn-primary my-3">Upload</button>
          </div>
        </form>
      </div>
    </div>
  </div>
    {{-- <img src="{{ 'data:image/jpeg;base64, ' . base64_encode($x['image']) }}" alt="" style="width: 300px;"> --}}

</body>
</html>
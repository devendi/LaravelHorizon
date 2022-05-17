@extends('layouts.main')

@section('container')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
{{-- 
<form action="import_product" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="input-group files mb-3">
        <input type="file" name="file" class="form-control">
        <button class="btn btn-primary" type="submit">Submit</button>
    </div>
</form> --}}

<form action="saveCSV" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="input-group files mb-3">
        <input type="file" name=" " class="form-control">
        <button class="btn btn-primary" type="submit">Submit</button>
    </div>
</form>


<div class="table-responsive col-lg-8 ">
    <table class="table table-striped table-sm ">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Title</th>
          <th scope="col">Category</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        {{-- @foreach ($files as $file)
            
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $file->title }}</td>
          <td>{{ $file->category->name }}</td>
        </tr>
        @endforeach  --}}
      </tbody>
    </table>
  </div>
@endsection

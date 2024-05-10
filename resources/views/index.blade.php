
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">

<div class="container pt-2">

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        <?php
        foreach($plugins as $plugin)
        {
        ?>
      <div class="col">
        <div class="card shadow-sm">
            <img src="{{ $plugin->image ?? "" }}" class="img-fluid" alt="...">

          <div class="card-body" style="min-height: 140px;">
            <h3>{{ $plugin->name ?? "" }}</h3>
            <p class="card-text">{{ $plugin->description ?? "" }}</p>
            <div class="d-flex justify-content-between align-items-center">


            </div>
          </div>
          <div class="card-footer text-muted">
            <div class="row align-items-center">
                <div class="col-6">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-primary">View</button>
                        <button type="button" class="btn btn-sm btn-success">Active</button>
                      </div>
                </div>
                <div class="col-6 text-end align-middle">
                    <small class="text-muted fw-bold">{{ $plugin->author ?? "" }}</small>
                </div>
            </div>


          </div>
        </div>
      </div>
      <?php
        }
      ?>
    </div>
  </div>


  @endsection



@extends('layouts.app')
@section('content')

<div class="container-fluid pt-2">
    <div class="row mb-3">
        <div class="col-12">
            <button type="button" id="btnUploadModule" class="btn btn-secondary rounded f-14 p-2 mr-3 float-left mb-2 mb-lg-0 mb-md-0 d-sm-bloc d-none d-lg-block"><i class="fa fa-file-upload mr-1"></i> Upload Module</button>
            <input type="file" id="modulePath" style="display: none;">
        </div>
    </div>
    <div class="row ">
        <?php
        foreach($plugins as $plugin)
        {
        ?>
      <div class="col-3">
        <div class="card shadow-sm">
            <img src="{{ $plugin->image ?? "" }}" class="img-fluid" alt="...">

          <div class="card-body" style="min-height: 140px;">
            <h3>{{ $plugin->title ?? $plugin->name }}</h3>
            <p class="card-text">{{ $plugin->description ?? "" }}</p>
            <div class="d-flex justify-content-between align-items-center">


            </div>
          </div>
          <div class="card-footer text-muted">
            <div class="row align-items-center">
                <div class="col-6">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="{{ $plugin->URL ?? "#" }}" target="_blank" class="btn btn-sm btn-secondary">View</a>
                            <?php
                            if(in_array($plugin->name, $activatedModules))
                            {
                                ?>
                                <button type="button" data-module-name="{{ $plugin->name ?? "" }}" class="btn btn-deactivate-module btn-group-sm btn-danger">Deactivate</button>
                                <?php
                            }
                            else
                            {
                            ?>
                                <button type="button" data-module-name="{{ $plugin->name ?? "" }}" class="btn btn-active-module btn-group-sm btn-success">Active</button>
                                <?php
                            }
                            ?>

                    </div>
                </div>
                <div class="col-6 text-right align-middle">
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

  @push('scripts')
  <script>
    $('body').on('click', '.btn-active-module', function () {
        let moduleStatus;
        const module = $(this).data('module-name');

        if ($(this).is(':checked')) {
            moduleStatus = 'active';
        } else {
            moduleStatus = 'inactive';
        }

        let url = "{{ route('plugin-manager.active') }}";

        $('#custom-module-alert').addClass('d-none');

        $.easyAjax({
            url: url,
            type: "POST",
            disableButton: true,
            buttonSelector: ".change-module-status",
            container: '.custom-modules-table',
            blockUI: true,
            data: {
                'id': module,
                'status': moduleStatus,
                '_method': 'POST',
                '_token': '{{ csrf_token() }}'
            },
            error: function (response) {
                if (response.status == 200) {
                    $('#custom-module-alert').html(response.responseJSON.message).removeClass('d-none');
                    location.reload();
                }

            }
        });
    });
    $('body').on('click', '.btn-deactivate-module', function () {
        let moduleStatus;
        const module = $(this).data('module-name');

        if ($(this).is(':checked')) {
            moduleStatus = 'active';
        } else {
            moduleStatus = 'inactive';
        }

        let url = "{{ route('plugin-manager.deactivate') }}";

        $('#custom-module-alert').addClass('d-none');

        $.easyAjax({
            url: url,
            type: "POST",
            disableButton: true,
            buttonSelector: ".change-module-status",
            container: '.custom-modules-table',
            blockUI: true,
            data: {
                'id': module,
                'status': moduleStatus,
                '_method': 'POST',
                '_token': '{{ csrf_token() }}'
            },
            error: function (response) {
                if (response.status == 200) {
                    $('#custom-module-alert').html(response.responseJSON.message).removeClass('d-none');
                    location.reload();
                }

            }
        });
    });
    $('body').on('click', '#btnUploadModule', function(e){
        $("#modulePath").click();
    });
    $("#modulePath").change(function(e) {
        var fileName = e.target.files[0].name;
        var formData = new FormData();
        formData.append("moduleFile", e.target.files[0]);
        formData.append("_token", '{{ csrf_token() }}');
        $.ajax({
            url: "{{ route('plugin-manager.upload') }}",
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status == 200) {
                    location.reload();
                } else {
                    console.error("Upload failed:", response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error uploading file:", textStatus, errorThrown);
                // Handle errors during upload
            }
        });
    });

</script>

@endpush

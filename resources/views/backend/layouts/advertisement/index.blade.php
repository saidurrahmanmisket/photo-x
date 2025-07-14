@extends('backend.app')
@section('title', 'Advertisements')
@section('header_title', 'Advertisements')

@section('content')
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Advertisements</h4>
                        <a href="{{ route('admin.advertisements.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Add Advertisement
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Media</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ads as $ad)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $ad->title }}</td>
                                            <td>
                                                <span class="badge {{ $ad->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ucfirst($ad->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($ad->media as $media)
                                                        @if($media->type === 'image')
                                                            <img src="{{ asset($media->file_path) }}" style="max-width:50px; max-height:50px; border-radius:4px;">
                                                        @else
                                                            <span title="Video"><i class="bi bi-film" style="font-size:2rem;"></i></span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>{{ $ad->created_at->diffForHumans() }}</td>
                                            <td>
                                                <a href="{{ route('admin.advertisements.edit', $ad->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                                <button class="btn btn-danger btn-sm" onclick="deleteAd({{ $ad->id }})">Delete</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No advertisements found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
<script>
function deleteAd(id) {
    if (!confirm('Are you sure you want to delete this advertisement?')) return;
    $.ajax({
        url: "{{ url('admin/advertisements') }}/" + id,
        type: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(resp) {
            if (resp.success) {
                location.reload();
            } else {
                alert('Failed to delete advertisement.');
            }
        },
        error: function() {
            alert('An error occurred while deleting the advertisement.');
        }
    });
}
</script>
@endpush 
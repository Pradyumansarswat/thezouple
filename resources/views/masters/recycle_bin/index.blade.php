@extends('masters.layout.default_layout')
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-recycle"></i> Recycle Bin</h1>
        </div>
    </div>

    <div class="row bg-white py-3">
        <div class="col-md-12">
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))
                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                @endif
            @endforeach

            <form method="get" action="{{ route('recycleBin') }}" class="row mb-3">
                <div class="col-md-5 mb-2">
                    <input type="text" name="search" class="form-control" placeholder="Search deleted records" value="{{ request('search') }}">
                </div>
                <div class="col-md-4 mb-2">
                    <select name="type" class="form-control">
                        <option value="">All deleted data</option>
                        @foreach($types as $key => $type)
                            <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $type['label'] }} - {{ ucfirst($key) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-search"></i> Filter</button>
                </div>
            </form>

            <div class="mb-3 d-flex justify-content-between flex-wrap">
                <div class="text-muted">Automatic cleanup: delete forever after {{ $cleanupDays }} days.</div>
                <form method="post" action="{{ route('recycleCleanup') }}" onsubmit="return confirm('Warning: This action cannot be undone.')">
                    @csrf
                    <input type="hidden" name="days" value="{{ $cleanupDays }}">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Run Cleanup</button>
                </form>
            </div>

            <form method="post" action="{{ route('recycleBulk') }}">
                @csrf
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="40"><input type="checkbox" id="select-all-recycle"></th>
                                <th>Name / Title</th>
                                <th>Type</th>
                                <th>Deleted Date</th>
                                <th>Deleted By</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td><input type="checkbox" name="items[]" value="{{ $item->type }}:{{ $item->id }}" class="recycle-check"></td>
                                    <td>{{ $item->name }}</td>
                                    <td><span class="badge badge-secondary">{{ $item->type_label }}</span> {{ ucfirst($item->type) }}</td>
                                    <td>{{ $item->deleted_at }}</td>
                                    <td>{{ $item->deleted_by ?: 'Unknown' }}</td>
                                    <td class="text-center">
                                        <a class="btn btn-sm btn-success" href="{{ route('restoreItem', [$item->type, $item->id]) }}"><i class="fa fa-undo"></i> Restore</a>
                                        <a class="btn btn-sm btn-danger" href="{{ route('permanentDelete', [$item->type, $item->id]) }}" onclick="return confirm('Warning: This action cannot be undone.')"><i class="fa fa-trash"></i> Delete Forever</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Recycle Bin is empty.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex align-items-center mb-3">
                    <select name="action" class="form-control mr-2" style="max-width:220px;">
                        <option value="restore">Restore selected</option>
                        <option value="delete">Delete selected forever</option>
                    </select>
                    <button class="btn btn-primary" type="submit" onclick="var selectedAction=this.form.querySelector('[name=action]').value; return confirm(selectedAction === 'delete' ? 'Warning: This action cannot be undone.' : 'Restore selected items?')">Apply</button>
                </div>
            </form>
        </div>
    </div>
</main>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var selectAll = document.getElementById('select-all-recycle');
    if (!selectAll) return;
    selectAll.addEventListener('change', function () {
        document.querySelectorAll('.recycle-check').forEach(function (box) {
            box.checked = selectAll.checked;
        });
    });
});
</script>
@stop

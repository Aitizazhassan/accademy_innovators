
<div class="btn-group gap-2">
    <a href="{{ route('mcqs.edit', $row->id) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Edit">
        <i class="fa fa-edit"></i>
    </a>
    <button type="button" class="btn btn-sm btn-danger delete-user" data-id="{{ $row->id }}" data-bs-toggle="tooltip" title="Delete">
        <i class="fa fa-trash"></i>
    </button>
</div>

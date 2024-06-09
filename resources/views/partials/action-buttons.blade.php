
<div class="btn-group">
    <a href="{{ route('mcqs.edit', $row->id) }}" class="btn btn-sm btn-primary-alt" data-bs-toggle="tooltip" title="Edit">
        <i class="fa fa-pencil-alt"></i>
    </a>
    <a href="{{ route('download.mcq.pptx', $row->id) }}" class="btn btn-sm btn-primary-alt" data-bs-toggle="tooltip" title="Download pptx">
        <i class="fa fa-download"></i>
    </a>
    <button type="button" class="btn btn-sm btn-danger-alt delete-user" data-id="{{ $row->id }}" data-bs-toggle="tooltip" title="Delete">
        <i class="fa fa-trash-can"></i>
    </button>
</div>


@forelse ($newses as $key => $news)
    <tr>
        <td class="text-center fs-sm">{{ $offset + $key + 1 }}</td>
        <td class="fw-semibold fs-sm">{{ $news->title }}</td>
        <td class="fw-semibold fs-sm">{{ $news->slug }}</td>
        <td class="fw-semibold fs-sm">{{ $news->category->name }}</td>
        <td class="fw-semibold fs-sm">
            {{ $news->subcategory?->name }}
        </td>

        <td class="fw-semibold fs-sm text-capitalize">
            @php
                $status = null;
                if ($news->status == 'published') {
                    $status = 'success';
                } elseif ($news->status == 'draft') {
                    $status = 'warning';
                } else {
                    $status = 'danger';
                }
            @endphp
            <div class="badge bg-{{ $status }}">{{ $news->status }}</div>
        </td>
        <td class="text-center">
            <div class="d-flex">
                <a href="{{ route('news.show', $news->id) }}" class="border-0 btn btn-sm">
                    <i class="fa fa-eye text-secondary fa-xl"></i>
                </a>
                <a href="{{ route('news.edit', $news->id) }}" class="border-0 btn btn-sm">
                    <i class="fa fa-pencil text-secondary fa-xl"></i>
                </a>
                <button type="button" class="border-0 btn btn-sm" onclick="deletenews(this)"
                    data-id="{{ $news->id }}"><i class="fa fa-trash text-danger fa-xl"></i></button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center">No Contribution Found!</td>
    </tr>
@endforelse

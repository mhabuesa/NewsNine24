{{-- @forelse ($contributes as $key => $contribute)
    <tr>
        <td>{{ ($page - 1) * $limit + ($key + 1) }}</td>
        <td>{{ $contribute->member->name }}</td>
        <td>
            <span class="fs-xs fw-semibold d-inline-block py-1 px-3 semi_rounded_pill bg-info-light text-info">
                {{ $contribute->cycle->name }}
            </span>
        </td>
        <td>
            <span class="fs-xs fw-semibold d-inline-block py-1 px-3 semi_rounded_pill bg-success-light text-success">
                {{ $contribute->amount }}
            </span>
        </td>
        <td class="text-center w-25">
            {{ $contribute->created_at->format('d-M-y') }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center">No Contribution Found!</td>
    </tr>
@endforelse --}}

@forelse ($newses as $key => $news)
    <tr>
        <td class="text-center fs-sm">{{ $key + 1 }}</td>
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

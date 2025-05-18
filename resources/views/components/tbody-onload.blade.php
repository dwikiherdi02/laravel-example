@props(['rows' => 5, 'cols' => 4])

{{-- @for ($irow = 0; $irow < $rows; $irow++)
    <tr>
        @for ($icol = 0; $icol < $cols; $icol++)
            <th scope="row">
                <div class="skeleton skeleton-line" style="--l-h: 20px;  --c-w: 100%;"></div>
            </th>
        @endfor
    </tr>
@endfor --}}
<tr>
    <td colspan="{{ $cols }}" class="text-center border-0"> 
        {{-- <div class="d-flex justify-content-center">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div> --}}
        <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
        {{ __('label.datatable_loading') }} 
    </td>
</tr>
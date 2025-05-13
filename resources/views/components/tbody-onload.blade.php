@props(['rows' => 5, 'cols' => 4])

@for ($irow = 0; $irow < $rows; $irow++)
    <tr>
        @for ($icol = 0; $icol < $cols; $icol++)
            <th scope="row">
                <div class="skeleton skeleton-line" style="--l-h: 20px;  --c-w: 100%;"></div>
            </th>
        @endfor
        {{-- <th scope="row">
            <div class="skeleton skeleton-line" style="--l-h: 20px;  --c-w: 100%;"></div>
        </th>
        <td>
            <div class="skeleton skeleton-line" style="--l-h: 20px;  --c-w: 100%;"></div>
        </td>
        <td>
            <div class="skeleton skeleton-line" style="--l-h: 20px;  --c-w: 100%;"></div>
        </td>
        <td>
            <div class="skeleton skeleton-line" style="--l-h: 20px;  --c-w: 100%;"></div>
        </td> --}}
    </tr>
@endfor
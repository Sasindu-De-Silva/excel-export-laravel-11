    @php
        $total_payment = 0;
    @endphp
    <p align="center">Items export</p>

    <table>
        <thead>
            <tr>
                <td align="left">
                    <b>Id</b>
                </td>
                <td align="left">
                    <b>Name</b>
                </td>
                <td align="left">
                    <b>Description</b>
                </td>
                <td align="left">
                    <b>Price</b>
                </td>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $data)
                <tr>
                    <td align="left">
                        {{ $data['id'] ? $data['id'] : '' }}
                    </td>
                    <td align="left">
                        {{ $data['name'] ? $data['name'] : '' }}
                    </td>
                    <td align="left">
                        {{ $data['description'] ? $data['description'] : '' }}
                    </td>
                    <td align="right">
                        {{ number_format($data['price'], 2) }}
                    </td>
                </tr>
                @php
                    $total_payment += $data['price'];
                @endphp
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td><b>Total</b></td>
                <td align="right"><b>{{ number_format($total_payment, 2) }}</b></td>
            </tr>
        </tbody>
    </table>
